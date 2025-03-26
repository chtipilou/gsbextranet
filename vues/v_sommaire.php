<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Démarre la session uniquement si elle n'est pas déjà active
}

if (!isset($_SESSION['id'])) {
    header('Location: ../index.php');
    exit();
}

// Vérification du rôle de l'utilisateur
$role = $_SESSION['role'] ?? null; // Utilisation de l'opérateur null coalescent
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GSB - Extranet</title>
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/profilcss/profil.css" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script src="js/custom.js"></script>
    <style>
        .navbar-brand {
            color: #343a40 !important; /* Changed from blue to black-gray */
        }
        .btn {
            background-color: #343a40; /* Black color */
            color: white;
            border-radius: 0; /* Square corners */
            padding: 10px 20px;
            border: none;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #23272b; /* Darker black on hover */
        }
    </style>
</head>
<body background="assets/img/laboratoire.jpg">
<nav class="navbar navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false">
                <span class="sr-only">Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index.php?uc=accueil">Galaxy Swiss Bourdin</a>
        </div>
        <div class="collapse navbar-collapse" id="navbar-collapse">
            <ul class="nav navbar-nav">
                <li class="active"><a href="index.php?uc=etatFrais&action=selectionnerMois">M'inscrire à une visio</a></li>
                <li class="active"><a href="index.php?uc=portabilite">Télécharger mes données</a></li>
                <li class="active"><a href="../gsbextranetB3/controleurs/c_deconnexion.php">Se Déconnecter</a></li>
                <?php if ($role === 'admin') : ?>
                    <li class="active"><a href="index.php?uc=produit">Gérer les Produits</a></li>
                    <li class="active"><a href="index.php?uc=visio">Gérer les visioconférences</a></li>
                    <li class="active"><a href="index.php?uc=statistique">Logs Opérations</a></li>
                    <li class="active"><a href="index.php?uc=maintenance">Gestion Maintenance</a></li>
                <?php endif; ?>

                <?php if ($role === 'chef_de_produit') : ?>
                    <li class="active"><a href="index.php?uc=produit">Gérer les Produits</a></li>
                <?php endif; ?>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li><a><?php echo isset($role) ? ucfirst(htmlspecialchars($role)) : 'Invité'; ?></a></li>
            </ul>
        </div>
    </div>
</nav>
    
<div class="page-content">
    <div class="row">
        <!-- Contenu de la page -->
    </div>
</div>
    
<!--
</nav>
    
<div class="page-content">
    <div class="row">
        <!-- Contenu de la page -->
    </div>
</div>
</body>
</html>
