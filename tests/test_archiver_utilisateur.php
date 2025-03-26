
<?php
require_once "../include/class.pdogsb.inc.php";

$_SESSION['id'] = 85; 
try {
    $pdo = PdoGsb::getPdoGsb();

    $pdo->archiverUtilisateur($_SESSION['id']);

    echo "Utilisateur archivé avec succès.";
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage();
}
?>