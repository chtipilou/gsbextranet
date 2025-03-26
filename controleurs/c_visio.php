<?php
// Remove session_start(); it's already called in index.php

// Vérifiez si l'utilisateur est connecté et a le bon rôle
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'chef_de_produit' && $_SESSION['role'] !== 'admin')) {
    header('Location: index.php'); // Redirige vers la page de connexion
    exit();
}

// Adjust include paths
require_once 'include/class.pdogsb.inc.php';
require_once 'include/m_visioconferences.php';

$pdo = PdoGsbVisio::getPdoGsbVisio();
$visioModel = new Visioconferences($pdo);

// Vérifier l'action demandée
$action = isset($_GET['action']) ? $_GET['action'] : 'liste';

switch ($action) {
    case 'liste':
        $visios = $visioModel->consulterVisioconferences();
        include("vues/v_gestion_visios.php");
        break;

    case 'ajouter':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            ajouterVisioconference();
            header('Location: index.php?uc=visio&action=liste');
            exit();
        } else {
            include("vues/v_gestion_visios.php");
        }
        break;

    case 'modifier':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            modifierVisioconference();
            header('Location: index.php?uc=visio&action=liste');
            exit();
        } else {
            include("vues/v_gestion_visios.php");
        }
        break;

    case 'supprimer':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            supprimerVisioconference();
            header('Location: index.php?uc=visio&action=liste');
            exit();
        } else {
            echo "Méthode de requête invalide.";
        }
        break;

    default:
        echo "Action invalide.";
        break;
}

// Fonction pour ajouter une visioconférence
function ajouterVisioconference() {
    global $visioModel;
    $nomVisio = trim($_POST['nomVisio']);
    $objectif = trim($_POST['objectif']);
    $url = trim($_POST['url']);
    $dateVisio = trim($_POST['dateVisio']);

    // Validation des champs
    if (empty($nomVisio)) {
        echo "Le nom de la visioconférence est requis.";
        exit();
    }
    if (empty($objectif)) {
        echo "L'objectif est requis.";
        exit();
    }
    if (empty($url)) {
        echo "L'URL est requise.";
        exit();
    }
    if (empty($dateVisio)) {
        echo "La date de la visioconférence est requise.";
        exit();
    }

    // Gestion de l'image
    $image = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $image = uploadImage($_FILES['image']);
        if (!$image) {
            echo "Erreur lors du téléchargement de l'image.";
            exit();
        }
    }

    // Ajouter la visioconférence
    $visioModel->ajouterVisioconference($nomVisio, $objectif, $url, $dateVisio, $image);
}

// Fonction pour modifier une visioconférence
function modifierVisioconference() {
    global $visioModel;
    $id = $_POST['id'];
    $nomVisio = trim($_POST['nomVisio']);
    $objectif = trim($_POST['objectif']);
    $url = trim($_POST['url']);
    $dateVisio = trim($_POST['dateVisio']);

    // Validation des champs
    if (empty($nomVisio)) {
        echo "Le nom de la visioconférence est requis.";
        exit();
    }
    if (empty($objectif)) {
        echo "L'objectif est requis.";
        exit();
    }
    if (empty($url)) {
        echo "L'URL est requise.";
        exit();
    }
    if (empty($dateVisio)) {
        echo "La date de la visioconférence est requise.";
        exit();
    }

    // Vérifier si un fichier a été uploadé
    if (isset($_FILES['image']) && $_FILES['image']['error'] != UPLOAD_ERR_NO_FILE) {
        $image = uploadImage($_FILES['image']);
    } else {
        $image = null;
    }

    // Modifier la visioconférence avec l'image correcte
    $visioModel->modifierVisioconference($id, $nomVisio, $objectif, $url, $dateVisio, $image);
}

// Fonction pour supprimer une visioconférence
function supprimerVisioconference() {
    global $visioModel;
    $id = $_POST['id'];
    $visioModel->supprimerVisioconference($id);

}

// Fonction pour gérer l'upload de l'image
function uploadImage($file) {
    $targetDir = "assets/img/";
    $targetFile = $targetDir . basename($file["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Vérifier si l'image est un fichier image
    $check = getimagesize($file["tmp_name"]);
    if ($check === false) {
        echo "Ce fichier n'est pas une image.";
        $uploadOk = 0;
    }

    // Vérifier la taille de l'image
    if ($file["size"] > 500000) { // Limiter à 500 Ko
        echo "Désolé, votre fichier est trop gros.";
        $uploadOk = 0;
    }

    // Autoriser certains formats de fichier
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        echo "Désolé, seuls les fichiers JPG, JPEG, PNG & GIF sont autorisés.";
        $uploadOk = 0;
    }

    // Vérifier si $uploadOk est défini à 0 par une erreur
    if ($uploadOk == 0) {
        echo "Désolé, votre fichier n'a pas été téléchargé.";
        return null;
    } else {
        // Tenter de télécharger le fichier
        if (move_uploaded_file($file["tmp_name"], $targetFile)) {
            return basename($file["name"]);
        } else {
            echo "Désolé, il y a eu une erreur lors du téléchargement de votre fichier.";
            return null;
        }
    }
}
?>

