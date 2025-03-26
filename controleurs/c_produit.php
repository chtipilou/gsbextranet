<?php
// c_produit.php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'include/class.pdogsb.inc.php';
require_once 'include/m_produits.php';

$pdo = PdoGsbProduit::getPdoGsbProduit();
$produitModel = new Produits($pdo);

// Vérifiez si l'utilisateur est connecté et a le bon rôle
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'chef_de_produit' && $_SESSION['role'] !== 'admin')) {
    header('Location: index.php'); // Redirige vers la page de connexion
    exit();
}

// Vérifier l'action demandée
$action = isset($_GET['action']) ? $_GET['action'] : 'liste';

switch ($action) {
    case 'liste':
        $produits = $produitModel->consulterProduits();
        include("vues/v_gestion_produits.php");
        break;

    case 'ajouter':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Collecte des informations du formulaire pour ajouter un produit
            ajouterProduit();
            // Redirection après ajout
            header('Location: index.php?uc=produit&action=liste');
            exit();
        } else {
            // Affichage du formulaire d'ajout
            include("vues/v_ajouter_produit.php");
        }
        break;

    case 'modifier':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Collecte des informations du formulaire pour modifier un produit
            modifierProduit();
            // Redirection après modification
            header('Location: index.php?uc=produit&action=liste');
            exit();
        } else {
            // Affichage du formulaire de modification
            $id = $_GET['id'];
            $produit = $produitModel->consulterProduit($id);
            include("vues/v_modifier_produit.php");
        }
        break;

    case 'supprimer':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $produitModel->supprimerProduit($id);
            header('Location: index.php?uc=produit&action=liste');
            exit();
        } else {
            echo "Méthode de requête invalide.";
        }
        break;

    default:
        echo "Action invalide.";
        break;
}

// Fonction pour ajouter un produit
function ajouterProduit() {
    global $produitModel;
    $nom = trim($_POST['nom']);
    $objectif = trim($_POST['objectif']);
    $information = trim($_POST['information']);
    $effetIndesirable = trim($_POST['effetIndesirable']);
    $description = trim($_POST['description']);
    $prix = trim($_POST['prix']);

    // Validation des champs
    if (empty($nom) || strlen($nom) > 60) {
        echo "Le nom est requis et doit contenir au maximum 60 caractères.";
        exit();
    }
    if (empty($objectif)) {
        echo "L'objectif est requis.";
        exit();
    }
    if (empty($information)) {
        echo "L'information est requise.";
        exit();
    }
    if (empty($effetIndesirable)) {
        echo "Les effets indésirables sont requis.";
        exit();
    }
    if (!empty($description) && strlen($description) > 65535) {
        echo "La description est trop longue.";
        exit();
    }
    if (!empty($prix) && (!is_numeric($prix) || $prix <= 0)) {
        echo "Le prix doit être un nombre valide et positif.";
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

    // Ajouter le produit
    $produitModel->ajouterProduit($nom, $objectif, $information, $effetIndesirable, $description, $prix, $image);
    header('Location: index.php?uc=produit&action=liste');
    exit();
}

// Fonction pour modifier un produit
function modifierProduit() {
    global $produitModel;
    $id = $_POST['id'];
    $nom = trim($_POST['nom']);
    $objectif = trim($_POST['objectif']);
    $information = trim($_POST['information']);
    $effetIndesirable = trim($_POST['effetIndesirable']);
    $description = trim($_POST['description']);
    $prix = trim($_POST['prix']);

    // Validation des champs
    if (empty($nom) || strlen($nom) > 60) {
        echo "Le nom est requis et doit contenir au maximum 60 caractères.";
        exit();
    }
    if (empty($objectif)) {
        echo "L'objectif est requis.";
        exit();
    }
    if (empty($information)) {
        echo "L'information est requise.";
        exit();
    }
    if (empty($effetIndesirable)) {
        echo "Les effets indésirables sont requis.";
        exit();
    }
    if (!empty($description) && strlen($description) > 65535) {
        echo "La description est trop longue.";
        exit();
    }
    if (!empty($prix) && (!is_numeric($prix) || $prix <= 0)) {
        echo "Le prix doit être un nombre valide et positif.";
        exit();
    }

    // Vérifier si un fichier a été uploadé
    if (isset($_FILES['image']) && $_FILES['image']['error'] != UPLOAD_ERR_NO_FILE) {
        // Gestion de l'upload de l'image
        $image = uploadImage($_FILES['image']);
    } else {
        $image = null; // Si pas d'image
    }

    // Modifier le produit avec l'image correcte
    $produitModel->modifierProduit($id, $nom, $objectif, $information, $effetIndesirable, $description, $prix, $image);
    header('Location: index.php?uc=produit&action=liste');
    exit();
}

// Fonction pour supprimer un produit
function supprimerProduit() {
    global $produitModel;
    $id = $_POST['id'];
    $produitModel->supprimerProduit($id);
    header('Location: index.php?uc=produit&action=liste');
    exit();
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
