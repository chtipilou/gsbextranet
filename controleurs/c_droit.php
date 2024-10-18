<?php
session_start();
require_once "../include/class.pdogsb.inc.php";
require_once "../include/fct.inc.php";

if (!estConnecte()) {
    die("Vous devez être connecté pour accéder à cette fonctionnalité.");
}

$idMedecin = $_SESSION['id'];

$pdo = PdoGsb::getPdoGsb();
try {
    $infoMedecin = $pdo->donneinfoPortabilite($idMedecin);
} catch (Exception $e) {
    die("Erreur lors de la récupération des données : " . $e->getMessage());
}

$nomFichier = "GSbExtranet_" . $idMedecin . ".json";

header('Content-Type: application/json');
header('Content-Disposition: attachment; filename="' . $nomFichier . '"');
header('Pragma: no-cache');
header('Expires: 0');

echo json_encode($infoMedecin);

exit;
?>
