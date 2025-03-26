<?php
require_once("include/fct.inc.php");
require_once("include/class.pdogsb.inc.php");
require_once("vues/v_footer.php"); // Consider moving this to a controller if needed

session_start();

date_default_timezone_set('Europe/Paris');

// Initialisation de la connexion PDO et vérification si l'utilisateur est connecté
$pdo = PdoGsb::getPdoGsb();
$estConnecte = estConnecte();

// Gestion de 'uc' (cas d'utilisation)
$uc = $_GET['uc'] ?? 'accueil';

// Gestion de 'action'
$action = isset($_GET['action']) ? $_GET['action'] : null;

// Redirection automatique si l'utilisateur est connecté ou non
if (!$estConnecte && $uc != 'connexion' && $uc != 'creation') {
    header('Location: index.php?uc=connexion');
    exit;
}
if ($estConnecte && $uc == 'connexion') {
    header('Location: index.php?uc=accueil');
    exit;
}

// Si l'utilisateur est connecté, inclure le sommaire (navigation)
if ($estConnecte && $uc !== 'connexion' && $uc !== '2fa') {
    include("vues/v_sommaire.php");
}

// Gestion des cas d'utilisation (uc) et des actions
switch ($uc) {
    case 'connexion': {
        include("controleurs/c_connexion.php");
        break;
    }
    case 'creation': {
        include("controleurs/c_creation.php");
        break;
    }
    case 'deconnexion': {
        include("controleurs/c_deconnexion.php");
        break;
    }
    case '2fa': {
        include("controleurs/c_2fa.php");
        break;
    }
    case 'produit': {
        switch ($action) {
            default: {
                include("controleurs/c_produit.php");
                break;
            }
        }
        break;
    }
    case 'statistique': {
        include("vues/v_statistiques.php");
        break;
    }
    case 'maintenance': {
        include("vues/v_gestionMaintenance.php");
        break;
    }
    case 'visio': {
        switch ($action) {
            case 'ajouter': {
                include("controleurs/c_visio.php");
                header('Location: index.php?uc=visio&action=ajouter');
                exit;
            }
            case 'modifier': {
                include("controleurs/c_visio.php");
                header('Location: index.php?uc=visio&action=modifier');
                exit;
            }
            case 'supprimer': {
                include("controleurs/c_visio.php");
                header('Location: index.php?uc=visio&action=supprimer');
                exit;
            }
            default: {
                include("controleurs/c_visio.php");
                break;
            }
        }
        break;
    }
    case 'portabilite': { // Gestion de la portabilité des données
        switch ($action) {
            case 'telecharger': {
                include("controleurs/c_portabilite.php");
                break;
            }
            case 'archiver': {
                include("controleurs/c_portabilite.php");
                break;
            }
            case 'supprimer': {
                include("controleurs/c_portabilite.php");
                break;
            }
            default: {
                include("controleurs/c_portabilite.php");
                break;
            }
        }
        break;
    }
    case 'accueil': {
        include("vues/v_accueil.php"); // Fixed spelling from "acceuil" to "accueil"
        break;
    }
    default: {
        echo "Page invalide ou non définie.";
        exit;
    }
}

try {
    $pdo = new PDO('mysql:host=localhost;dbname=gsbextranetAP', 'gsbextranetAdmin', 'cesMyspudHZyHyt');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

// Récupérer le mode maintenance
$stmt = $pdo->query("SELECT maintenance_mode FROM settings LIMIT 1");
$maintenanceMode = $stmt->fetchColumn();

// Vérifie si l'utilisateur est connecté et son rôle
$isAdmin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin'; // Assurez-vous que le rôle de l'utilisateur est stocké dans la session

// Code pour afficher la barre de maintenance pour les admins
if ($maintenanceMode && $isAdmin) {
    echo '
    <div style="background-color: #f8c74a; padding: 10px; text-align: center; position: fixed; top: 0; left: 0; width: 100%; z-index: 1000;">
        <strong>Avertissement :</strong> Le site est actuellement en mode maintenance.
    </div>';
}

if ($maintenanceMode && !$isAdmin) {
    echo '
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Maintenance</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 0;
            }
            .modal {
                display: flex;
                justify-content: center;
                align-items: center;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.5);
                z-index: 1000;
            }
            .modal-content {
                background-color: white;
                padding: 20px;
                border-radius: 5px;
                text-align: center;
                width: 80%;
                max-width: 500px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            }
            .modal h2 {
                margin: 0 0 10px;
                color: #d3a611; /* Couleur pour le titre */
            }
            .modal p {
                margin: 0 0 20px;
                font-size: 16px;
                color: #333; /* Couleur pour le texte */
            }
            .close {
                background-color: #28a745; /* Couleur du bouton */
                color: white;
                border: none;
                padding: 10px 20px;
                border-radius: 5px;
                cursor: pointer;
                font-size: 16px;
            }
            .close:hover {
                background-color: #218838; /* Couleur du bouton au survol */
            }
        </style>
    </head>
    <body>
        <div class="modal">
            <div class="modal-content">
                <h2>Avertissement</h2>
                <p>Le site est actuellement en mode maintenance. Vous serez redirigé vers la page de maintenance dans 10 secondes.</p>
                <button class="close" onclick="redirect()">Fermer</button>
            </div>
        </div>
        
        <script>
            function redirect() {
                window.location.href = "maintenance.php";
            }
            setTimeout(function() {
                redirect();
            }, 10000); // 10000 ms = 10 secondes
        </script>
    </body>
    </html>';
    
    exit;
}
?>

