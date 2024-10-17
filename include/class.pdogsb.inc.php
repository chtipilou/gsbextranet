<?php

/** 
 * Classe d'accÃ¨s aux donnÃ©es. 
 
 * Utilise les services de la classe PDO
 * pour l'application GSB
 * Les attributs sont tous statiques,
 * les 4 premiers pour la connexion
 * $monPdo de type PDO 
 * $monPdoGsb qui contiendra l'unique instance de la classe
 
 * @package default
 * @author Cheri Bibi
 * @version    1.0
 * @link       http://www.php.net/manual/fr/book.pdo.php
 */

class PdoGsb{   		
      	private static $serveur='mysql:host=localhost:3306';//8.1 : 3308 erreur
      	private static $bdd='dbname=gsbextranet';   		
      	private static $user='gsbextranetAdmin' ;    		
      	private static $mdp='cesMyspudHZyHyt' ;	
	private static $monPdo;
	private static $monPdoGsb=null;
		
/**
 * Constructeur privÃ©, crÃ©e l'instance de PDO qui sera sollicitÃ©e
 * pour toutes les mÃ©thodes de la classe
 */				
	private function __construct(){
          
    	PdoGsb::$monPdo = new PDO(PdoGsb::$serveur.';'.PdoGsb::$bdd, PdoGsb::$user, PdoGsb::$mdp); 
		PdoGsb::$monPdo->query("SET CHARACTER SET utf8");
	}
	public function _destruct(){
		PdoGsb::$monPdo = null;
	}
/**
 * Fonction statique qui crÃ©e l'unique instance de la classe
 
 * Appel : $instancePdoGsb = PdoGsb::getPdoGsb();
 
 * @return l'unique objet de la classe PdoGsb
 */
	public  static function getPdoGsb(){
		if(PdoGsb::$monPdoGsb==null){
			PdoGsb::$monPdoGsb= new PdoGsb();
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

function ajouteConnexionInitiale($id){
    $pdoStatement = PdoGsb::$monPdo->prepare("INSERT INTO historiqueconnexion "
            . "VALUES (:leMedecin, now(), now())");
    $bv1 = $pdoStatement->bindValue(':leMedecin', $id);
    $execution = $pdoStatement->execute();
    return $execution;
    
}
function donneinfosmedecin($id){
  
       $pdo = PdoGsb::$monPdo;
           $monObjPdoStatement=$pdo->prepare("SELECT id,nom,prenom FROM medecin WHERE id= :lId");
    $bvc1=$monObjPdoStatement->bindValue(':lId',$id,PDO::PARAM_INT);
    if ($monObjPdoStatement->execute()) {
        $unUser=$monObjPdoStatement->fetch();
   
    }
    else
        throw new Exception("erreur info medecin");
           
    
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

function enregistreConnexion($id) {
    $pdo = PdoGsb::$monPdo;
    $requete = "INSERT INTO historiqueconnexion VALUES (:lId, now(), now())";
    $monObjPdoStatement = $pdo->prepare($requete);
    $monObjPdoStatement->bindValue(':lId', $id, PDO::PARAM_INT);

    if ($monObjPdoStatement->execute()) {
        return true;
    } else {
        throw new Exception("Erreur historique de connexion");
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
    $codeVerification = genererCodeVerification();
    $this->enregistrerCodeVerification($idMedecin, $codeVerification);
    $medecin = $this->donneLeMedecinByMail($idMedecin);
    if (is_array($medecin)) {
        $mail = $medecin['mail'];
        $objet = "Code de vérification pour la connexion";
        $message = "Votre code de vérification est : $codeVerification";
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'localhost';
        $mail->Port = 1025;
        $mail->setFrom('quentinleroy62123@gmail.com', 'Mail Authentification GSB');
        $mail->addAddress($mail, $medecin['nom']. $medecin['prenom']);
        $mail->Subject = $objet;
        $mail->Body = $message;
        $mail->send();
    } else {
        echo "ERREUR";
    }
}

function verifierCodeVerification($idMedecin, $codeVerification) {
    $pdo = PdoGsb::$monPdo;
    $requete = "SELECT codeVerification FROM medecin WHERE id = :idMedecin";
    $monObjPdoStatement = $pdo->prepare($requete);
    $monObjPdoStatement->bindValue(':idMedecin', $idMedecin);
    $monObjPdoStatement->execute();
    $resultat = $monObjPdoStatement->fetch();
    if ($resultat!== false) {
        $codeVerificationBdd = $resultat['codeVerification'];
        if ($codeVerification == $codeVerificationBdd) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

}

?>