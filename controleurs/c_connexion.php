<?php
// Vérification de l'action
if (!isset($_GET['action'])) {
    $_GET['action'] = 'demandeConnexion';
}
$action = $_GET['action'];
// Switch pour traiter les différentes actions
switch ($action) {
    case 'demandeConnexion':
        // Affichage de la page de connexion
        include("vues/v_connexion.php");
        break;

		case 'valideConnexion': {
			$login = $_POST['login'];
			$mdp = $_POST['mdp'];
			$connexionOk = $pdo->checkUser($login, $mdp);
			
			if (!$connexionOk) {
				ajouterErreur("Login ou mot de passe incorrect");
				include("vues/v_erreurs.php");
				include("vues/v_connexion.php");
			} else {
				$idMedecin = PdoGsb::getPdoGsb()->donneLeMedecinByMail($login)['id'];
				$_SESSION['id'] = $idMedecin;
		
				// Générer un code de vérification et l'enregistrer
				$codeVerification = genererCodeVerification(); 
				PdoGsb::getPdoGsb()->enregistrerCodeVerification($idMedecin, $codeVerification); 
		
				// Récupérer le code de vérification pour le stocker dans la session
				$_SESSION['2fa'] = $codeVerification; 
		
				// Envoyer le code de vérification
				PdoGsb::getPdoGsb()->envoyerCodeVerification($idMedecin); 
				header('Location: vues/v_2fa.php');
				exit;
			}
			break;
		}
		case 'valideCode': {
			$leCode = $_POST['2fa'];
			$codeValide = PdoGsb::getPdoGsb()->verifierCodeVerification($_SESSION['id'], $leCode);
			if ($codeValide) {
				$lesInfos = PdoGsb::getPdoGsb()->donneinfosmedecin($_SESSION['id']);
				connecter($_SESSION['id'], $lesInfos['nom'], $lesInfos['nom']);
				PdoGsb::getPdoGsb()->ajouteConnexionInitiale($_SESSION['id']);
				include("vues/v_sommaire.php");
			} else {
				ajouterErreur("code incorrect");
				include("vues/v_erreurs.php");
			}
			break;
		}
		
		
        break;

    default:
        // Action par défaut : affichage de la page de connexion
        include("vues/v_connexion.php");
        break;
}