<?php
session_start();

if (!isset($_SESSION['idMedecin'])) {
    $_SESSION['idMedecin'] = ''; // ou une valeur par défaut
}

require_once '../include/class.pdogsb.inc.php';
require_once '../include/fct.inc.php';

$pdo = PdoGsb::getPdoGsb();

$idMedecin = $_SESSION['idMedecin']; // Définir l'ID du médecin
$code2fa = $_POST['2fa'];
$authentificationOk = $pdo->verifierCodeVerification($idMedecin, $code2fa);
if ($authentificationOk) {
    $sql = "SELECT id, nom, prenom FROM medecin WHERE id = :idMedecin";
    $pdoStatement = $pdo->prepare($sql);
    $pdoStatement->bindParam(':idMedecin', $idMedecin);
    $pdoStatement->execute();
    $medecin = $pdoStatement->fetch();
    if ($medecin) {
        $id = $medecin['id'];
        $nom = $medecin['nom'];
        $prenom = $medecin['prenom'];
        connecter($id, $nom, $prenom); // Définir la fonction connecter
        header('Location: ../index.php?uc=connexion&action=valideConnexion');
        exit;
    } else {
        ajouterErreur("Médecin non trouvé");
    }
} else {
    ajouterErreur("2fa incorrect");

}