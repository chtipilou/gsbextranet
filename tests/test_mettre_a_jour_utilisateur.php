
<?php
require_once "../include/class.pdogsb.inc.php";

$_SESSION['id'] = 86;

try {
    $pdo = PdoGsb::getPdoGsb();

    $pdo->mettreAJourUtilisateur($_SESSION['id'], 'NouveauNom', 'NouveauPrenom', '0123456789', '1990-01-01', 'nouveau@mail.com', '123456789');

    echo "Utilisateur mis à jour avec succès.";
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage();
}
?>