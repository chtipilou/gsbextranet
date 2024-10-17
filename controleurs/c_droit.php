<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("../include/fct.inc.php");

$action = $_GET['action'];
    switch($action){
        case 'portabilite':{
            $lePdo = PdoGsb::getPdoGsb();

            // Récupère l'ID du médecin connecté
            $idMedecin = $_GET['id'];

            // Appelle la méthode donneinfosPortabilite avec l'ID du médecin
            $userData = $lePdo->donneinfosPortabilite($idMedecin);

            $generate_json = json_encode($userData, JSON_PRETTY_PRINT);
            
            // Préparation de l'en-tête pour le téléchargement
            header('Content-Type: application/json; charset=utf-8');
            header('Content-Disposition: attachment; filename="user_data_'. $generate_json. '.json"');
            header('Pragma: no-cache');

            echo ($generate_json);

            include("../vues/v_portabilite.php");
            break;
        }
        default :{
            include("../vues/v_connexion.php");
            break;
        }
    }
