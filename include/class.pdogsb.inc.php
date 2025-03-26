<?php

require_once "fct.inc.php";
require_once __DIR__ . '/../vendor/autoload.php'; // Adjust the path as needed

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class PdoGsb {
    private static $serveur = 'mysql:host=localhost:3306';
    private static $bdd = 'dbname=gsbextranetAP';
    private static $user = 'gsbextranetAdmin';
    private static $mdp = 'cesMyspudHZyHyt';
    private static $monPdo;
    private static $monPdoGsb = null;

    private function __construct() {
        try {
            PdoGsb::$monPdo = new PDO(PdoGsb::$serveur.';'.PdoGsb::$bdd, PdoGsb::$user, PdoGsb::$mdp);
            PdoGsb::$monPdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            PdoGsb::$monPdo->query("SET CHARACTER SET utf8");
        } catch (PDOException $e) {
            die("Erreur de connexion à la base de données : " . $e->getMessage());
        }
    }

    public static function getPdoGsb() {
        if (PdoGsb::$monPdoGsb == null) {
            PdoGsb::$monPdoGsb = new PdoGsb();
        }
        return PdoGsb::$monPdoGsb;  
    }

    function checkUser($login, $pwd): bool {
        $user = false;
        $pdo = PdoGsb::$monPdo;
        $monObjPdoStatement = $pdo->prepare("SELECT motDePasse FROM utilisateur WHERE mail = :login");
        $monObjPdoStatement->bindValue(':login', $login, PDO::PARAM_STR);
        if ($monObjPdoStatement->execute()) {
            $unUser = $monObjPdoStatement->fetch();
            if (is_array($unUser)) {
                $user = true;
                if (password_verify($pwd, $unUser['motDePasse'])) {
                    $user = true;
                }
            }
        } else {
            throw new Exception("Erreur dans la requête");
        }
        return $user;
    }

    function ajouterErreur($msg) {
        if (!isset($_SESSION['erreurs'])) {
            $_SESSION['erreurs'] = array();
        }
        $_SESSION['erreurs'][] = $msg;
    }

    public function donneLeutilisateurByMail($login) {
        $pdo = PdoGsb::$monPdo;
        $monObjPdoStatement = $pdo->prepare("SELECT id, nom, prenom, mail FROM utilisateur WHERE mail = :login");
        $monObjPdoStatement->bindValue(':login', $login); 
        $monObjPdoStatement->execute();
        $unUser = $monObjPdoStatement->fetch();
        return $unUser; 
    }

    public function tailleChampsMail(){
        $pdoStatement = PdoGsb::$monPdo->prepare("SELECT CHARACTER_MAXIMUM_LENGTH FROM INFORMATION_SCHEMA.COLUMNS
    WHERE table_name = 'utilisateur' AND COLUMN_NAME = 'mail'");
        $execution = $pdoStatement->execute();
        $leResultat = $pdoStatement->fetch();

        return $leResultat[0];
    }

    public function tailleChamps($nomTable,$nomColonne){
        $pdoStatement = PdoGsb::$monPdo->prepare("SELECT CHARACTER_MAXIMUM_LENGTH FROM INFORMATION_SCHEMA.COLUMNS
    WHERE table_name = '$nomTable' AND COLUMN_NAME = '$nomColonne'");
        $execution = $pdoStatement->execute();
        $leResultat = $pdoStatement->fetch();

        return $leResultat[0];
    }

    public function tailleChampsNom(){
        $pdoStatement = PdoGsb::$monPdo->prepare("SELECT CHARACTER_MAXIMUM_LENGTH FROM INFORMATION_SCHEMA.COLUMNS
    WHERE table_name = 'utilisateur' AND COLUMN_NAME = 'nom'");
        $execution = $pdoStatement->execute();
        $leResultat = $pdoStatement->fetch();

        return $leResultat[0];
    }

    public function creeutilisateur($email, $mdp, $nom, $prenom, $dateConsentement) {
        if ($this->testMail($email)) {
            return false;
        }

        // Hash the password before storing it
        $hashedMdp = password_hash($mdp, PASSWORD_DEFAULT);

        $pdoStatement = PdoGsb::$monPdo->prepare("INSERT INTO utilisateur (id, mail, motDePasse, nom, prenom, dateCreation, dateConsentement) VALUES (null, :leMail, :leMdp, :leNom, :lePrenom, now(), :laDateConsentement)");
        $pdoStatement->bindValue(':leMail', $email); 
        $pdoStatement->bindValue(':leMdp', $hashedMdp);
        $pdoStatement->bindValue(':leNom', $nom);
        $pdoStatement->bindValue(':lePrenom', $prenom);
        $pdoStatement->bindValue(':laDateConsentement', $dateConsentement);
        return $pdoStatement->execute();
    }

    function testMail($email){
        $pdo = PdoGsb::$monPdo;
        $pdoStatement = $pdo->prepare("SELECT count(*) as nbMail FROM utilisateur WHERE mail = :leMail");
        $bv1 = $pdoStatement->bindValue(':leMail', $email);
        $execution = $pdoStatement->execute();
        $resultatRequete = $pdoStatement->fetch();
        if ($resultatRequete['nbMail']==0)
            $mailTrouve = false;
        else
            $mailTrouve=true;

        return $mailTrouve;
    }

    function connexionInitiale($mail){
        $pdo = PdoGsb::$monPdo;
        $utilisateur= $this->donneLeutilisateurByMail($mail);
        $id = $utilisateur['id'];
        $this->ajouteConnexionInitiale($id);
    }

    function ajouteConnexionInitiale($id){
        $pdoStatement = PdoGsb::$monPdo->prepare("INSERT INTO historiqueconnexion (idUtilisateur, dateDebutLog, dateFinLog) VALUES (:leutilisateur, NOW(), NULL)");
        $pdoStatement->bindValue(':leutilisateur', $id);
        $execution = $pdoStatement->execute();
        return $execution;
    }

    function enregistrerDateFinLog($id){
        $pdoStatement = PdoGsb::$monPdo->prepare("UPDATE historiqueconnexion SET dateFinLog = NOW() WHERE idUtilisateur = :leutilisateur AND dateFinLog IS NULL ORDER BY dateDebutLog DESC LIMIT 1");
        $pdoStatement->bindValue(':leutilisateur', $id);
        $execution = $pdoStatement->execute();
        return $execution;
    }

    public function donneinfosutilisateur($id) {
        $pdo = PdoGsb::$monPdo;
        $monObjPdoStatement = $pdo->prepare("SELECT id, nom, prenom, mail, telephone, dateNaissance, rpps FROM utilisateur WHERE id= :lId");
        $monObjPdoStatement->bindValue(':lId', $id, PDO::PARAM_INT);
        if ($monObjPdoStatement->execute()) {
            $unUser = $monObjPdoStatement->fetch(PDO::FETCH_ASSOC);
            return $unUser; 
        } else {
            throw new Exception("Erreur info utilisateur");
        }
    }

    function donneinfoPortabilite($id) {
        $pdo = PdoGsb::$monPdo;
        $requete = "SELECT 
            nom, 
            prenom, 
            telephone, 
            mail, 
            dateNaissance, 
            dateCreation, 
            rpps, 
            token, 
            dateConsentement,
            dateVerification,
            role
        FROM utilisateur WHERE id = :lId";
        $monObjPdoStatement = $pdo->prepare($requete);
        $monObjPdoStatement->bindValue(':lId', $id, PDO::PARAM_INT);

        if ($monObjPdoStatement->execute()) {
            $unUser = $monObjPdoStatement->fetch(PDO::FETCH_ASSOC);
            return $unUser;
        } else {
            throw new Exception("Erreur récupération des informations de portabilité");
        }
    }

    function enregistrerCodeVerification($idutilisateur, $codeVerification) {
        $pdo = PdoGsb::$monPdo;
        $requete = "UPDATE utilisateur SET codeVerification = :codeVerification, dateVerification = NOW() WHERE id = :idutilisateur";
        $monObjPdoStatement = $pdo->prepare($requete);
        $monObjPdoStatement->bindValue(':codeVerification', $codeVerification);
        $monObjPdoStatement->bindValue(':idutilisateur', $idutilisateur);
        $monObjPdoStatement->execute();
    }

    function envoyerCodeVerification($idutilisateur) {
        $utilisateur = $this->donneinfosutilisateur($idutilisateur);
        if (is_array($utilisateur)) {
            $mail = $utilisateur['mail'];
            $codeVerification = $utilisateur['codeVerification'];

            if ($codeVerification) {
                $objet = "Code de vérification pour la connexion";
                $message = "Votre code de vérification est : $codeVerification";

                $mail = new PHPMailer(true);
                try {
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'quentinleroy62123@gmail.com';
                    $mail->Password = 'junx mhbe jxxv xloj';
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = 587;

                    $mail->setFrom('quentinleroy62123@gmail.com', 'GSB');
                    $mail->addAddress($utilisateur['mail']);

                    $mail->isHTML(true);
                    $mail->Subject = $objet;
                    $mail->Body = $message;

                    $mail->send();
                } catch (Exception $e) {
                    echo "Erreur lors de l'envoi du mail : {$mail->ErrorInfo}";
                }
            } else {
                echo "Aucun code de vérification trouvé pour cet utilisateur.";
            }
        } else {
            echo "ERREUR : Utilisateur non trouvé.";
        }
    }

    public function verifierCodeVerification($idutilisateur, $code2fa) {
        $pdo = PdoGsb::$monPdo; 
        $sql = "SELECT codeVerification FROM utilisateur WHERE id = :idutilisateur";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':idutilisateur', $idutilisateur, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result && isset($result['codeVerification'])) {
            if ($result['codeVerification'] == $code2fa) {
                return true;
            }
        }

        return false;
    }

    function donneRoleUtilisateur($id) {
        $pdo = PdoGsb::$monPdo;
        $monObjPdoStatement = $pdo->prepare("SELECT role FROM utilisateur WHERE id= :lId");
        $bvc1 = $monObjPdoStatement->bindValue(':lId', $id, PDO::PARAM_INT);
        if ($monObjPdoStatement->execute()) {
            $unUser = $monObjPdoStatement->fetch(PDO::FETCH_ASSOC);
            return $unUser['role']; 
        } else {
            throw new Exception("Erreur lors de la récupération du rôle de l'utilisateur");
        }
    }

    public function enregistreFinConnexion($idUtilisateur) {
        try {
            $pdo = PdoGsb::$monPdo; 
            $sql = "UPDATE utilisateurs SET date_deconnexion = NOW() WHERE id = :idUtilisateur";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':idUtilisateur', $idUtilisateur, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            throw new Exception("Erreur lors de l'enregistrement de la fin de connexion : " . $e->getMessage());
        }
    }

    public function ajouterProduit($nom, $objectif, $information, $effetIndesirable, $description, $prix, $image) {
        $stmt = $this->pdo->prepare("INSERT INTO produits (nom, objectif, information, effetIndesirable, description, prix, image) VALUES (:nom, :objectif, :information, :effetIndesirable, :description, :prix, :image)");
        $stmt->execute([
            ':nom' => $nom,
            ':objectif' => $objectif,
            ':information' => $information,
            ':effetIndesirable' => $effetIndesirable,
            ':description' => $description,
            ':prix' => $prix,
            ':image' => $image
        ]);
        return $this->pdo->lastInsertId();
    }

    public function modifierProduit($id, $nom, $objectif, $information, $effetIndesirable, $description, $prix, $image) {
        $stmt = $this->pdo->prepare("UPDATE produits SET nom = :nom, objectif = :objectif, information = :information, effetIndesirable = :effetIndesirable, description = :description, prix = :prix, image = :image WHERE id = :id");
        return $stmt->execute([
            ':id' => $id,
            ':nom' => $nom,
            ':objectif' => $objectif,
            ':information' => $information,
            ':effetIndesirable' => $effetIndesirable,
            ':description' => $description,
            ':prix' => $prix,
            ':image' => $image
        ]);
    }

    public function supprimerProduit($id) {

        $stmt = $this->pdo->prepare("DELETE FROM produits WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    public function consulterProduits() {
        $stmt = $this->pdo->query("SELECT * FROM produits"); 
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function consulterProduit($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM produits WHERE id = :id"); 
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getLogsOperations() {
        $pdo = self::$monPdo;
        $sql = "
            SELECT 
                id,
                idutilisateur,
                adresse_ip,
                action,
                DATE_FORMAT(date, '%d/%m/%Y %H:%i:%s') AS date
            FROM logs_operations
            ORDER BY date DESC
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function archiverUtilisateur($id) {
        $pdo = self::$monPdo;
        $archivePdo = new PDO('mysql:host=localhost:3306;dbname=gsbextranetArchive', 'gsbextranetAdmin', 'cesMyspudHZyHyt');
        
        // Archiver les données de l'utilisateur dans historiqueconnexion
        $sqlHistorique = "INSERT IGNORE INTO gsbextranetArchive.historiqueconnexion (idUtilisateur, dateDebutLog, dateFinLog)
                          SELECT idUtilisateur, dateDebutLog, dateFinLog
                          FROM historiqueconnexion WHERE idUtilisateur = :id";
        $stmtHistorique = $pdo->prepare($sqlHistorique);
        $stmtHistorique->bindParam(':id', $id, PDO::PARAM_INT);
        $stmtHistorique->execute();
        
        // Archiver les logs de l'utilisateur
        $sqlLogs = "INSERT IGNORE INTO gsbextranetArchive.logs_operations (id, idutilisateur, adresse_ip, action, date)
                    SELECT id, idutilisateur, adresse_ip, action, date
                    FROM logs_operations WHERE idutilisateur = :id";
        $stmtLogs = $pdo->prepare($sqlLogs);
        $stmtLogs->bindParam(':id', $id, PDO::PARAM_INT);
        $stmtLogs->execute();

        // Archiver l'utilisateur
        $sql = "INSERT IGNORE INTO gsbextranetArchive.utilisateur (id, nom, prenom, telephone, mail, dateNaissance, dateCreation, rpps, token, dateConsentement)
                SELECT id, nom, prenom, telephone, mail, dateNaissance, dateCreation, rpps, token, dateConsentement
                FROM utilisateur WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        // Supprimer les données de l'utilisateur dans historiqueconnexion
        $stmt = $pdo->prepare("DELETE FROM historiqueconnexion WHERE idUtilisateur = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        // Supprimer les logs de l'utilisateur
        $stmtLogs = $pdo->prepare("DELETE FROM logs_operations WHERE idutilisateur = :id");
        $stmtLogs->bindParam(':id', $id, PDO::PARAM_INT);
        $stmtLogs->execute();

        // Supprimer l'utilisateur
        $sqlDelete = "DELETE FROM utilisateur WHERE id = :id";
        $stmtDelete = $pdo->prepare($sqlDelete);
        $stmtDelete->bindParam(':id', $id, PDO::PARAM_INT);
        $stmtDelete->execute();
    }

    public function supprimerUtilisateur($idutilisateur) {
        $pdo = self::$monPdo;
        
        // Supprimer les données de l'utilisateur dans historiqueconnexion
        $stmt = $pdo->prepare("DELETE FROM historiqueconnexion WHERE idutilisateur = :idutilisateur");
        $stmt->bindParam(':idutilisateur', $idutilisateur, PDO::PARAM_INT);
        $stmt->execute();
        
        // Supprimer l'utilisateur
        $stmt = $pdo->prepare("DELETE FROM utilisateur WHERE id = :idutilisateur");
        $stmt->bindParam(':idutilisateur', $idutilisateur, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function mettreAJourUtilisateur($id, $nom, $prenom, $telephone, $dateNaissance, $mail, $rpps) {
        $pdo = self::$monPdo;
        $sql = "UPDATE utilisateur SET 
            nom = :nom, 
            prenom = :prenom, 
            telephone = :telephone,
            dateNaissance = :dateNaissance,
            mail = :mail,
            rpps = :rpps
            WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':prenom', $prenom);
        $stmt->bindParam(':telephone', $telephone);

        // Gérer dateNaissance lorsqu'elle est vide ou nulle
        if (!empty($dateNaissance)) {
            $stmt->bindParam(':dateNaissance', $dateNaissance);
        } else {
            $stmt->bindValue(':dateNaissance', null, PDO::PARAM_NULL);
        }

        $stmt->bindParam(':mail', $mail);
        $stmt->bindParam(':rpps', $rpps);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        try {
            $stmt->execute();
        } catch (PDOException $e) {
            if ($e->getCode() == '22001') { // SQLSTATE[22001]: String data, right truncated
                throw new Exception("La valeur du champ est trop longue. Veuillez vérifier et réessayer.");
            } else {
                throw $e;
            }
        }
    }

}

?>