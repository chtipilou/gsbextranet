<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Télécharger mes données</title>
    <!-- Lien vers Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa; /* Couleur de fond douce */
            display: flex; /* Utilisation de flexbox */
            justify-content: center; /* Centrer horizontalement */
            align-items: center; /* Centrer verticalement */
            height: 100vh; /* Utiliser toute la hauteur de la fenêtre */
            margin: 0; /* Enlever la marge par défaut */
        }
        .container {
            max-width: 600px; /* Largeur maximale */
            text-align: center; /* Centrer le texte */
        }
        h1 {
            margin-bottom: 30px; /* Espace en bas du titre */
            color: #343a40; /* Couleur du texte */
        }
        .btn-custom {
            background-color: #007bff; /* Couleur personnalisée */
            color: white; /* Couleur du texte du bouton */
        }
        .btn-custom:hover {
            background-color: #0056b3; /* Couleur au survol */
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Télécharger mes données</h1>
        <div class="alert alert-info" role="alert">
    En téléchargeant vos données, vous confirmez que vous avez pris connaissance de la manière dont vos données seront utilisées</div>
        <button class="btn btn-custom" onclick="location.href='../controleurs/c_droit.php';">Télécharger mes données</button>
    </div>

    <!-- Lien vers jQuery et Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
