<?php

if (isset($_SESSION['id'])) {
    // Affichez le contenu de la session
    echo "";
} else {
    echo "Aucune session active. Veuillez vous connectez";
}

class PdoGsbProduit {
    private static $serveur = 'mysql:host=localhost:3306';
    private static $bdd = 'dbname=gsbextranetAP';       
    private static $user = 'gsbextranetAdmin';          
    private static $mdp = 'cesMyspudHZyHyt';    
    private static $monPdo;
    private static $monPdoGsbProduit = null;

    private function __construct() {
        try {
            PdoGsbProduit::$monPdo = new PDO(PdoGsbProduit::$serveur . ';' . PdoGsbProduit::$bdd, PdoGsbProduit::$user, PdoGsbProduit::$mdp); 
            PdoGsbProduit::$monPdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            PdoGsbProduit::$monPdo->query("SET CHARACTER SET utf8");
        } catch (PDOException $e) {
            die("Erreur de connexion à la base de données : " . $e->getMessage());
        }
    }
    
    public static function getPdoGsbProduit() {
        if (PdoGsbProduit::$monPdoGsbProduit == null) {
            PdoGsbProduit::$monPdoGsbProduit = new PdoGsbProduit();
        }
        return PdoGsbProduit::$monPdo; // Retourne l'instance PDO
    }
}

class Produits {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function logOperation($idutilisateur, $action) {
        // Vérifiez si l'adresse IP est stockée dans la session
        if (!isset($_SESSION['user_ip'])) {
            $_SESSION['user_ip'] = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['HTTP_CLIENT_IP'] ?? $_SERVER['REMOTE_ADDR']; // Stocke l'adresse IP de l'utilisateur
        }
        
        $adresse_ip = $_SESSION['user_ip']; // Assurez-vous que l'adresse IP est stockée dans la session
    
        // Insertion dans la base de données avec formatage de la date
        $sql = "INSERT INTO logs_operations (idutilisateur, adresse_ip, action, date) VALUES (?, ?, ?, NOW())";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$idutilisateur, $adresse_ip, $action]);
    }
    

    public function consulterProduits() {
        $sql = "SELECT * FROM produits";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Retourne un tableau associatif
    }

    public function consulterProduit($id) {
        $sql = "SELECT * FROM produits WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        $produit = $stmt->fetch(PDO::FETCH_ASSOC);

        return $produit;
    }
    

    public function ajouterProduit($nom, $objectif, $information, $effetIndesirable, $description, $prix, $image) {
        $sql = "INSERT INTO produits (nom, objectif, information, effetIndesirable, description, prix, image) 
                VALUES (:nom, :objectif, :information, :effetIndesirable, :description, :prix, :image)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':objectif', $objectif);
        $stmt->bindParam(':information', $information);
        $stmt->bindParam(':effetIndesirable', $effetIndesirable);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':prix', $prix);
        $stmt->bindParam(':image', $image);
        $stmt->execute();
        
        // Vérifiez si l'utilisateur est défini avant d'appeler logOperation
        if (isset($_SESSION['id'])) {
            $this->logOperation($_SESSION['id'], 'ajouter produit'); // Utilisation de id
        } else {
            throw new Exception("User ID non défini dans la session.");
        }
    }    

    public function modifierProduit($id, $nom, $objectif, $information, $effetIndesirable, $description, $prix, $image) {
        // Afficher les valeurs pour le débogage
        echo "ID: $id, Nom: $nom, Description: $description, Prix: $prix, Image: $image\n";

        // Vérifiez si le prix est valide
        if (!is_numeric($prix) || $prix <= 0) {
            throw new InvalidArgumentException("Le prix doit être un nombre valide et positif.");
        }

        // Si aucune image n'est fournie, conservez l'ancienne image
        if (empty($image)) {
            $produit = $this->consulterProduit($id);
            $image = $produit['image'];
        }

        $sql = "UPDATE produits SET nom = ?, objectif = ?, information = ?, effetIndesirable = ?, description = ?, prix = ?, image = ? WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$nom, $objectif, $information, $effetIndesirable, $description, $prix, $image, $id]);

        // Vérifiez si l'utilisateur est défini avant d'appeler logOperation
        if (isset($_SESSION['id'])) {
            $this->logOperation($_SESSION['id'], 'modifier produit'); // Utilisation de id
        } else {
            throw new Exception("User ID non défini dans la session.");
        }
    }

    public function supprimerProduit($id) {
        $sql = "DELETE FROM produits WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        // Vérifiez si l'utilisateur est défini avant d'appeler logOperation
        if (isset($_SESSION['id'])) {
            $this->logOperation($_SESSION['id'], 'supprimer produit'); // Utilisation de id
        } else {
            throw new Exception("User ID non défini dans la session.");
        }
    }
}
// Obtenez l'instance PDO à partir de PdoGsbProduit
$pdo = PdoGsbProduit::getPdoGsbProduit(); 
$produits = new Produits($pdo); // Créez une instance de Produits avec l'instance PDO

// Exemple d'utilisation
try {
    $tousProduits = $produits->consulterProduits();
    if(empty($tousProduits)) {
        echo " aucun produit trouvé.";
    } else {
        echo "";
    }
} catch (Exception $e) {
    echo "Erreur lors de la consultation des produits: " . $e->getMessage();
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