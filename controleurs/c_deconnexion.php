<?php
session_start();
require_once '../include/class.pdogsb.inc.php';

if (isset($_SESSION['id'])) {
    $idUtilisateur = $_SESSION['id'];

    try {
        $pdoGsb = PdoGsb::getPdoGsb(); // Utilisez la méthode statique pour obtenir l'instance
        $pdoGsb->enregistrerDateFinLog($idUtilisateur); // Set dateFinLog to NOW
    } catch (Exception $e) {
        echo "Erreur lors de l'enregistrement de la fin de connexion : " . $e->getMessage();
    }
}

$_SESSION = array();
session_destroy();
header("Location: ../index.php");
exit;
?>
