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
				PdoGsb::getPdoGsb()->envoyerCodeVerification($idMedecin);
				header('Location: vues/v_2fa.php');
				exit;
			}
			break;
		}
        break;

    default:
        // Action par défaut : affichage de la page de connexion
        include("vues/v_connexion.php");
        break;
}