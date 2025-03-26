<?php
// Vérification de l'action
$action = isset($_GET['action']) ? $_GET['action'] : 'demandeConnexion';

// Initialisation de la variable $pdo
$pdo = PdoGsb::getPdoGsb();

// Switch pour traiter les différentes actions
switch ($action) {
    case 'demandeConnexion':
        // Affichage de la page de connexion
        include("vues/v_connexion.php");
        break;

    case 'valideConnexion': {
        $login = $_POST['login'];
        $mdp = $_POST['mdp'];
        $pdo = PdoGsb::getPdoGsb(); // Ensure $pdo is initialized
        try {
            $connexionOk = $pdo->checkUser($login, $mdp);
            if (!$connexionOk) {
                ajouterErreur("Login ou mot de passe incorrect");
                include("vues/v_erreurs.php");
                include("vues/v_connexion.php");
            } else {
                // Mettre l'utilisateur connecté en session
                $idutilisateur = $pdo->donneLeutilisateurByMail($login)['id'];
                $_SESSION['id'] = $idutilisateur;

                // Récupérer le rôle de l'utilisateur
                $roleUtilisateur = $pdo->donneRoleUtilisateur($idutilisateur); 
                $_SESSION['role'] = $roleUtilisateur; // Ajout du rôle à la session

                // Mettez ici l'indication que l'utilisateur est connecté
                $_SESSION['connecte'] = true; // Indique que l'utilisateur est connecté

                // Générer un code de vérification et l'enregistrer
                $codeVerification = genererCodeVerification(); 
                $pdo->enregistrerCodeVerification($idutilisateur, $codeVerification); 

                // Récupérer le code de vérification pour le stocker dans la session
                $_SESSION['2fa'] = $codeVerification; 

                // Rediriger vers la page de vérification 2FA sans envoyer l'email
                header('Location: index.php?uc=2fa'); 
                exit;
            }
        } catch (Exception $e) {
            ajouterErreur("Erreur lors de la connexion : " . $e->getMessage());
            include("vues/v_erreurs.php");
            include("vues/v_connexion.php");
            exit;
        }
        break;
    }

    default:
        // Action par défaut : affichage de la page de connexion
        include("vues/v_connexion.php");
        break;
}

?>

