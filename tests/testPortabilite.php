<?php
require_once("../include/class.pdogsb.inc.php");

$lePdo = PdoGsb::getPdoGsb();

$userData = $lePdo->donneinfosPortabilite(54); 

$generate_json = json_encode($userData, JSON_PRETTY_PRINT);


var_dump($generate_json);
