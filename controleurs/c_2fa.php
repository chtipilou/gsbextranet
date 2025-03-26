<?php
$action = isset($_GET['action']) ? $_GET['action'] : 'demande2FA';

switch ($action) {
    case 'demande2FA':
        include("vues/v_2fa.php");
        break;

    case 'envoyerCode':
        if (isset($_SESSION['id'])) {
            $pdo = PdoGsb::getPdoGsb();
            $pdo->envoyerCodeVerification($_SESSION['id']); // Envoie le code de vérification par email
            header('Location: index.php?uc=2fa');
            exit;
        } else {
            header('Location: index.php?uc=connexion');
            exit;
        }
        break;

    case 'valideCode':
        $leCode = $_POST['2fa'];
        if (!isset($_SESSION['connecte']) || !$_SESSION['connecte']) {
            header('Location: index.php?uc=connexion'); // Redirige vers la connexion
            exit;
        }
        $pdo = PdoGsb::getPdoGsb();
        $codeValide = $pdo->verifierCodeVerification($_SESSION['id'], $leCode);
        if ($codeValide) {
            $lesInfos = $pdo->donneinfosutilisateur($_SESSION['id']);
            connecter($_SESSION['id'], $lesInfos['nom'], $lesInfos['prenom']);
            $pdo->ajouteConnexionInitiale($_SESSION['id']);
            header('Location: index.php?uc=accueil'); // Rediriger vers l'accueil après validation
            exit;
        } else {
            ajouterErreur("Code incorrect");
            include("vues/v_erreurs.php");
            include("vues/v_2fa.php");
        }
        break;

    default:
        include("vues/v_2fa.php");
        break;
}
?>