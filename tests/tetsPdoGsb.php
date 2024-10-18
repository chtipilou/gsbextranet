<?php

// On insère le fichier qui contient les fonctions
require_once("../include/class.pdogsb.inc.php");

// Appel de la fonction qui permet de se connecter à la base de données
$pdo = PdoGsb::getPdoGsb();
/*

$mail = "exemple1@exemple.com";
$pwd = ")J=a3hpaZ9U2-U3";
echo "Test checkUser avec email '$mail' : ";
var_dump($pdo->checkUser($mail, $pwd));

$mail = "y@gmail.com";
$pwd = "password";
echo "Test checkUser avec email '$mail' : ";
var_dump($pdo->checkUser($mail, $pwd));

$mail = "bidule@gmail.fr"; 
$pwd = "YJhd4gR#9UAR2pGA";
echo "Test checkUser avec email '$mail' : ";
var_dump($pdo->checkUser($mail, $pwd));

$mail = "exemple1@exemple.com";
echo "Test donneLeMedecinByMail avec email '$mail' : ";
var_dump($pdo->donneLeMedecinByMail($mail));

$mail = "inconnu@exemple.com";
echo "Test donneLeMedecinByMail avec email '$mail' : ";
var_dump($pdo->donneLeMedecinByMail($mail));

echo "Test tailleChampsMail : ";
var_dump($pdo->tailleChampsMail());

$email = "nouveau_medecin@exemple.com";
$mdp = password_hash("monMotDePasse", PASSWORD_DEFAULT);
$dateConsentement = date("Y-m-d");
echo "Test creeMedecin avec email '$email' : ";
var_dump($pdo->creeMedecin($email, $mdp, $dateConsentement));


$email = "exemple1@exemple.com"; 
echo "Test testMail avec email '$email' : ";
var_dump($pdo->testMail($email));

$email = "inconnu@exemple.com"; 
echo "Test testMail avec email '$email' : ";
var_dump($pdo->testMail($email));

$id = 1; 
echo "Test donneinfosmedecin avec ID '$id' : ";
var_dump($pdo->donneinfosmedecin($id));

echo "Test donneinfoPortabilite avec ID '$id' : ";
var_dump($pdo->donneinfoPortabilite($id));
*/
/*
$idMedecin = 52; 
$codeVerification = "123456"; 
echo "Test enregistrerCodeVerification avec ID '$idMedecin' : ";
$pdo->enregistrerCodeVerification($idMedecin, $codeVerification);
echo "Code de vérification enregistré.\n";
*/

echo "Test envoyerCodeVerification avec ID 52 : ";
$pdo->envoyerCodeVerification(52);
echo "Code de vérification envoyé.\n";

echo " \nTest verifierCodeVerification avec ID 52 : ";
$pdo->verifierCodeVerification("52","158997");
echo "vérifier avec succes\n";

?>
