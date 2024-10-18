<?php

require_once "fct.inc.php";
//require_once 'path/to/PHPMailer/src/Exception.php';
//require_once 'path/to/PHPMailer/src/PHPMailer.php';
//require_once 'path/to/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class PdoGsb {   		
    private static $serveur='mysql:host=localhost:3306';
    private static $bdd='dbname=gsbextranet';   		
    private static $user='gsbextranetAdmin';    		
    private static $mdp='cesMyspudHZyHyt';	
    private static $monPdo;
    private static $monPdoGsb = null;

    private function __construct() {
        try {
            PdoGsb::$monPdo = new PDO(PdoGsb::$serveur.';'.PdoGsb::$bdd, PdoGsb::$user, PdoGsb::$mdp); 
            PdoGsb::$monPdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Pour attraper les erreurs
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
/**
 * vÃ©rifie si le login et le mot de passe sont corrects
 * renvoie true si les 2 sont corrects
 * @param type $lePDO
 * @param type $login
 * @param type $pwd
 * @return bool
 * @throws Exception
 */
function checkUser($login,$pwd):bool {
    //AJOUTER TEST SUR TOKEN POUR ACTIVATION DU COMPTE
    $user=false;
    $pdo = PdoGsb::$monPdo;
    $monObjPdoStatement=$pdo->prepare("SELECT motDePasse FROM medecin WHERE mail= :login AND token IS NULL");
    $bvc1=$monObjPdoStatement->bindValue(':login',$login,PDO::PARAM_STR);
    if ($monObjPdoStatement->execute()) {
        $unUser=$monObjPdoStatement->fetch();
        if (is_array($unUser)){
           if (password_verify($pwd,$unUser['motDePasse']))
                $user=true;
        }
    }
    else
        throw new Exception("erreur dans la requÃªte");
return $user;   
}

function ajouterErreur($msg) {
    if (!isset($_SESSION['erreurs'])) {
        $_SESSION['erreurs'] = array();
    }
    $_SESSION['erreurs'][] = $msg;
}


function donneLeMedecinByMail($login) {
    
    $pdo = PdoGsb::$monPdo;
    $monObjPdoStatement=$pdo->prepare("SELECT id, nom, prenom,mail FROM medecin WHERE mail= :login");
    $bvc1=$monObjPdoStatement->bindValue(':login',$login,PDO::PARAM_STR);
    if ($monObjPdoStatement->execute()) {
        $unUser=$monObjPdoStatement->fetch();
       
    }
    else
        throw new Exception("erreur dans la requÃªte");
return $unUser;   
}


public function tailleChampsMail(){
     $pdoStatement = PdoGsb::$monPdo->prepare("SELECT CHARACTER_MAXIMUM_LENGTH FROM INFORMATION_SCHEMA.COLUMNS
WHERE table_name = 'medecin' AND COLUMN_NAME = 'mail'");
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
WHERE table_name = 'medecin' AND COLUMN_NAME = 'nom'");
   $execution = $pdoStatement->execute();
$leResultat = $pdoStatement->fetch();
     
     return $leResultat[0];
    
}


public function creeMedecin($email, $mdp, $dateConsentement)
{
   
    $pdoStatement = PdoGsb::$monPdo->prepare("INSERT INTO medecin(id,mail, motDePasse,dateCreation,dateConsentement) "
            . "VALUES (null, :leMail, :leMdp, now(),:laDateConsentement)");
    $bv1 = $pdoStatement->bindValue(':leMail', $email);
    $bv2 = $pdoStatement->bindValue(':leMdp', $mdp);
    $bv2 = $pdoStatement->bindValue(':laDateConsentement', $dateConsentement);
    $execution = $pdoStatement->execute();
    return $execution;
    
}


function testMail($email){
    $pdo = PdoGsb::$monPdo;
    $pdoStatement = $pdo->prepare("SELECT count(*) as nbMail FROM medecin WHERE mail = :leMail");
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
    $medecin= $this->donneLeMedecinByMail($mail);
    $id = $medecin['id'];
    $this->ajouteConnexionInitiale($id);
    
}

/*
function ajouteConnexionInitiale($id){
    $pdoStatement = PdoGsb::$monPdo->prepare("INSERT INTO historiqueconnexion "
            . "VALUES (:leMedecin, now(), now())");
    $bv1 = $pdoStatement->bindValue(':leMedecin', $id);
    $execution = $pdoStatement->execute();
    return $execution;
    
}
*/

function ajouteConnexionInitiale($idMedecin){
    $pdo = PdoGsb::$monPdo;
    // Insère une nouvelle entrée avec la date de début et un champ date_fin NULL
    $requete = "INSERT INTO historiqueconnexion (idMedecin, dateDebutLog, dateFinLog) VALUES (:lIdMedecin, NOW(), NULL)";
    $monObjPdoStatement = $pdo->prepare($requete);
    $monObjPdoStatement->bindValue(':lIdMedecin', $idMedecin, PDO::PARAM_INT); // Utilisation correcte de $idMedecin

    if ($monObjPdoStatement->execute()) {
        return true;
    } else {
        throw new Exception("Erreur lors de l'enregistrement de la connexion");
    }
}


function enregistreFinConnexion($idMedecin) {
    $pdo = PdoGsb::$monPdo;
    // Met à jour la date de fin là où dateFinLog est NULL pour marquer la déconnexion
    $requete = "UPDATE historiqueconnexion SET dateFinLog = NOW() WHERE idMedecin = :lIdMedecin AND dateFinLog IS NULL";
    $monObjPdoStatement = $pdo->prepare($requete);
    $monObjPdoStatement->bindValue(':lIdMedecin', $idMedecin, PDO::PARAM_INT);

    if ($monObjPdoStatement->execute()) {
        return true;
    } else {
        throw new Exception("Erreur lors de la mise à jour de l'historique de connexion");
    }
}



function donneinfosmedecin($id) {
    $pdo = PdoGsb::$monPdo;
    $monObjPdoStatement = $pdo->prepare("SELECT id, nom, prenom, mail, codeVerification FROM medecin WHERE id= :lId");
    $bvc1 = $monObjPdoStatement->bindValue(':lId', $id, PDO::PARAM_INT);
    if ($monObjPdoStatement->execute()) {
        $unUser = $monObjPdoStatement->fetch(PDO::FETCH_ASSOC);
        return $unUser; // Retourne les informations incluant codeVerification
    } else {
        throw new Exception("erreur info medecin");
    }
}



function donneinfoPortabilite($id) {
    $pdo = PdoGsb::$monPdo;
    $requete = "SELECT nom, prenom, telephone, mail, dateNaissance, dateCreation, rpps, token, dateConsentement FROM medecin WHERE id = :lId";
    $monObjPdoStatement = $pdo->prepare($requete);
    $monObjPdoStatement->bindValue(':lId', $id, PDO::PARAM_INT);

    if ($monObjPdoStatement->execute()) {
        $unUser = $monObjPdoStatement->fetch(PDO::FETCH_ASSOC);
        return $unUser;
    } else {
        throw new Exception("Erreur récupération des informations de portabilité");
    }
}


function enregistrerCodeVerification($idMedecin, $codeVerification) {
    $pdo = PdoGsb::$monPdo;
    $requete = "UPDATE medecin SET codeVerification = :codeVerification, dateVerification = NOW() WHERE id = :idMedecin";
    $monObjPdoStatement = $pdo->prepare($requete);
    $monObjPdoStatement->bindValue(':codeVerification', $codeVerification);
    $monObjPdoStatement->bindValue(':idMedecin', $idMedecin);
    $monObjPdoStatement->execute();
}

function envoyerCodeVerification($idMedecin) {
    // Récupérer le médecin et son code de vérification existant
    $medecin = $this->donneinfosmedecin($idMedecin);
    var_dump($medecin);
    
    if (is_array($medecin)) {
        $mail = $medecin['mail'];
        $codeVerification = $medecin['codeVerification'];
        
        // Vérifier si le code est disponible
        if ($codeVerification) {
            $objet = "Code de vérification pour la connexion";
            $message = "Votre code de vérification est : $codeVerification";

            /* 
            $mail = new PHPMailer();
            $mail->isSMTP();
            $mail->Host = 'localhost';
            $mail->Port = 1025;
            $mail->setFrom('quentinleroy62123@gmail.com', 'Mail Authentification GSB');
            $mail->addAddress($mail, $medecin['nom']. ' ' . $medecin['prenom']);
            $mail->Subject = $objet;
            $mail->Body = $message;
            $mail->send();
            */
        } else {
            echo "Aucun code de vérification trouvé pour ce médecin.";
        }
    } else {
        echo "ERREUR : Médécin non trouvé.";
    }
}

public function verifierCodeVerification($idMedecin, $code2fa) {
    $pdo = PdoGsb::$monPdo; 
    $sql = "SELECT codeVerification FROM medecin WHERE id = :idMedecin";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':idMedecin', $idMedecin, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if($result['codeVerification'] == $code2fa){
        return true;
    }
    else {
        return false;
    };
    
}


}

?>