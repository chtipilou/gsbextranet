
<?php
require_once "../include/class.pdogsb.inc.php";

$_SESSION['id'] = 84; 
try {
    $pdo = PdoGsb::getPdoGsb();

    $pdo->supprimerUtilisateur($_SESSION['id']);

    echo "Utilisateur supprimé avec succès.";
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage();
}
?>