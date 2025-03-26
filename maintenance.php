<?php
// maintenance.php

// Vérifiez si le site est en mode maintenance
$siteEnMaintenance = true; // Changez cette variable pour activer/désactiver le mode maintenance

if ($siteEnMaintenance) {
    // En-têtes HTTP pour empêcher le cache
    header("HTTP/1.1 503 Service Unavailable");

    // Affichez un message de maintenance
    echo "<!DOCTYPE html>
    <html lang='fr'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Site en Maintenance</title>
        <style>
            body {
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                font-family: Arial, sans-serif;
                background-color: #f2f2f2;
                color: #333;
            }
            .message {
                text-align: center;
            }
            h1 {
                font-size: 2em;
            }
            p {
                font-size: 1.2em;
            }
        </style>
    </head>
    <body>
        <div class='message'>
            <h1>Site en Maintenance</h1>
            <p>Nous sommes désolés pour la gêne occasionnée. Le site est actuellement en maintenance.</p>
            <p>Merci de votre compréhension et à bientôt!</p>
        </div>
    </body>
    </html>";
    exit();
}
?>

