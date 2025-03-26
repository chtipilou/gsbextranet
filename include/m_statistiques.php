<?php 

class PdoGsbStat {
    private static $serveur = 'mysql:host=localhost:3306';
    private static $bdd = 'dbname=gsbextranetAP';       
    private static $user = 'gsbextranetAdmin';          
    private static $mdp = 'cesMyspudHZyHyt';    
    private static $monPdo;
    private static $monPdoGsbStat = null;

    // Constructeur privé pour la connexion PDO
    private function __construct() {
        try {
            // Connexion à la base de données
            PdoGsbStat::$monPdo = new PDO(PdoGsbStat::$serveur . ';' . PdoGsbStat::$bdd, PdoGsbStat::$user, PdoGsbStat::$mdp); 
            PdoGsbStat::$monPdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            PdoGsbStat::$monPdo->query("SET CHARACTER SET utf8");  // S'assurer que la connexion est en UTF-8
        } catch (PDOException $e) {
            die("Erreur de connexion à la base de données : " . $e->getMessage()); // Gestion des erreurs de connexion
        }
    }
    
    // Méthode pour récupérer l'instance PDO
    public static function getPdoGsbStat() {
        if (PdoGsbStat::$monPdoGsbStat == null) {
            PdoGsbStat::$monPdoGsbStat = new PdoGsbStat();
        }
        return PdoGsbStat::$monPdo; // Retourne l'instance PDO
    }
}

class Statistiques {
    private $pdo;

    // Constructeur pour injecter la connexion PDO
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Méthode pour récupérer les statistiques des opérations
    public function getStatistiquesOperations() {
        $sql = "SELECT * FROM logs_operations";  // SQL pour récupérer toutes les opérations du journal
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);  // Retourne les résultats sous forme de tableau associatif
    }
}

?>


<?php
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