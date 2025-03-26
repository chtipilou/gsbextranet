<?php
// Connexion à la base de données
try {
    $pdo = new PDO('mysql:host=localhost;dbname=gsbextranetAP', 'gsbextranetAdmin', 'cesMyspudHZyHyt');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

// Vérifiez si l'utilisateur est un administrateur
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("Accès non autorisé.");
}

// Traitement du formulaire pour activer/désactiver le mode maintenance
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $maintenanceMode = isset($_POST['maintenance_mode']) ? 1 : 0;
    $stmt = $pdo->prepare("UPDATE settings SET maintenance_mode = ?");
    $stmt->execute([$maintenanceMode]);
}

// Récupérer l'état actuel du mode maintenance
$stmt = $pdo->query("SELECT maintenance_mode FROM settings LIMIT 1");
$currentMode = $stmt->fetchColumn();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mode Maintenance</title>
    <style>
        .maintenance-container {
            min-height: calc(100vh - 60px); /* Hauteur totale moins hauteur navbar */
            padding-top: 2rem;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .maintenance-box {
            background: #fff;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 400px;
            margin: 20px auto;
        }

        .maintenance-title {
            color: #343a40;
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .maintenance-form {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1rem;
        }

        .maintenance-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: bold;
        }

        .maintenance-button {
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        .maintenance-button:hover {
            background-color: #218838;
        }

        .status-message {
            margin-top: 1rem;
            padding: 10px;
            border-radius: 5px;
            color: white;
            text-align: center;
            width: 100%;
        }

        .maintenance { background-color: #dc3545; }
        .operational { background-color: #28a745; }

        .back-button {
            margin: 1rem;
            align-self: flex-start;
        }
    </style>
</head>
<body>
    <div class="maintenance-container">
        <div class="back-button">
            <a href="index.php?uc=accueil" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
        </div>
        
        <div class="maintenance-box">
            <h1 class="maintenance-title">Gestion du mode maintenance</h1>
            <form method="POST" class="maintenance-form">
                <label class="maintenance-label">
                    <input type="checkbox" name="maintenance_mode" value="1" <?php echo $currentMode ? 'checked' : ''; ?>>
                    Activer le mode maintenance
                </label>
                <button type="submit" class="maintenance-button">Enregistrer</button>
            </form>

            <?php if ($currentMode): ?>
                <div class="status-message maintenance">Le site est actuellement en mode maintenance.</div>
            <?php else: ?>
                <div class="status-message operational">Le site est opérationnel.</div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
