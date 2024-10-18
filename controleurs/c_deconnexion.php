<?php
session_start();
require_once '../include/class.pdogsb.inc.php';

if (isset($_SESSION['id'])) {
    $idUtilisateur = $_SESSION['id'];

    try {
        $pdoGsb = PdoGsb::getPdoGsb(); // Utilisez la méthode statique pour obtenir l'instance
        $pdoGsb->enregistreFinConnexion($idUtilisateur); // Appelez la méthode sur l'instance
    } catch (Exception $e) {
        echo "Erreur lors de l'enregistrement de la fin de connexion : " . $e->getMessage();
    }
}

$_SESSION = array();
session_destroy();
header("Location: ../index.php");
exit;
?>
