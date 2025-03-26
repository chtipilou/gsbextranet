<?php
require_once "include/class.pdogsb.inc.php";
require_once "include/fct.inc.php";

// Vérifie si l'utilisateur est connecté
if (!estConnecte()) {
    die("Non autorisé.");
}

$action = isset($_GET['action']) ? $_GET['action'] : 'afficher';

switch ($action) {
    case 'telecharger':
        try {
            // Récupération de l'ID utilisateur
            $idutilisateur = $_SESSION['id'];

            // Connexion à la base de données
            $pdo = PdoGsb::getPdoGsb();

            // Récupération des données utilisateur
            $infoutilisateur = $pdo->donneinfoPortabilite($idutilisateur);

            // Vérifie qu'il y a des données
            if (empty($infoutilisateur)) {
                throw new Exception("Aucune donnée disponible.");
            }

            // Nettoyage des tampons de sortie (au cas où)
            if (ob_get_length()) {
                ob_clean();
            }

            // Préparation des en-têtes HTTP pour le téléchargement
            header('Content-Type: application/json');
            header('Content-Disposition: attachment; filename="GSBExtranet_' . $idutilisateur . '.json"');
            header('Pragma: no-cache');
            header('Expires: 0');

            // Envoi des données JSON
            echo json_encode($infoutilisateur, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            exit;

        } catch (Exception $e) {
            // En cas d'erreur, retourne un JSON d'erreur
            if (ob_get_length()) {
                ob_clean();
            }
            header('Content-Type: application/json');
            echo json_encode(['error' => $e->getMessage()], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            exit;
        }
        break;

    case 'archiver':
        try {
            $idutilisateur = $_SESSION['id'];
            $pdo = PdoGsb::getPdoGsb();
            $pdo->archiverUtilisateur($idutilisateur);
            echo json_encode(['success' => 'Données archivées avec succès.'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

            session_destroy();
            
            // Rediriger vers la page index
            header('Location: index.php');
            exit;
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            exit;
        }
        break;

    case 'supprimer':
        try {
            $idutilisateur = $_SESSION['id'];
            $pdo = PdoGsb::getPdoGsb();
            
            // Supprimer l'utilisateur et ses données associées
            $pdo->supprimerUtilisateur($idutilisateur);
            
            // Détruire la session pour déconnecter l'utilisateur
            session_destroy();
            
            // Rediriger vers la page index
            header('Location: index.php');
            exit;
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            exit;
        }
        break;

    case 'modifier':
        try {
            $idutilisateur = $_SESSION['id'];
            $nom = $_POST['nom'];
            $prenom = $_POST['prenom'];
            $telephone = $_POST['telephone'];
            $dateNaissance = $_POST['dateNaissance'];
            $mail = $_POST['mail'];
            $rpps = $_POST['rpps'];
            // Valider et formater dateNaissance si nécessaire
            $dateNaissance = !empty($dateNaissance) ? $dateNaissance : null;

            $pdo = PdoGsb::getPdoGsb();
            $pdo->mettreAJourUtilisateur($idutilisateur, $nom, $prenom, $telephone, $dateNaissance, $mail, $rpps);

            // Mettre à jour les informations de session
            $_SESSION['utilisateur']['nom'] = $nom;
            $_SESSION['utilisateur']['prenom'] = $prenom;
            $_SESSION['utilisateur']['telephone'] = $telephone;
            $_SESSION['utilisateur']['dateNaissance'] = $dateNaissance;
            $_SESSION['utilisateur']['mail'] = $mail;
            $_SESSION['utilisateur']['rpps'] = $rpps;

            // Rediriger vers la page de portabilité
            header('Location: index.php?uc=portabilite');
            exit;
        } catch (Exception $e) {
            echo "<div class='portabilite-alert'>Erreur : " . $e->getMessage() . "</div>";
        }
        break;

    default:
        $idutilisateur = $_SESSION['id'];
        $pdo = PdoGsb::getPdoGsb();
        // Fetch user data and store in session
        $_SESSION['utilisateur'] = $pdo->donneinfosutilisateur($idutilisateur);
        include("vues/v_portabilite.php");
        break;
}
?>
