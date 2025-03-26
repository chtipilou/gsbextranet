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
        <title>GSB - Maintenance</title>
        <link href='css/styles.css' rel='stylesheet'>
    </head>
    <body>
        <div class='main-container'>
            <div class='content-box text-center'>
                <h1 class='mb-4'>Site en Maintenance</h1>
                <p class='lead'>Nous effectuons actuellement une maintenance planifiée.</p>
                <p>Notre site sera de retour très prochainement. Merci de votre patience.</p>
                <div class='mt-4'>
                    <img src='assets/img/maintenance.svg' alt='Maintenance' style='max-width: 300px'>
                </div>
            </div>
        </div>
    </body>
    </html>";
    exit(); 
}
?>
