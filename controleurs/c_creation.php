<?php

$action = isset($_GET['action']) ? $_GET['action'] : 'demandeCreation';

switch ($action) {
    case 'demandeCreation':
        include("vues/v_creation.php");
        break;

    case 'valideCreation':
        $leLogin = htmlspecialchars($_POST['login']);
        $lePassword = htmlspecialchars($_POST['mdp']);
        $leNom = htmlspecialchars($_POST['nom']);
        $lePrenom = htmlspecialchars($_POST['prenom']);
        $pdo = PdoGsb::getPdoGsb(); // Ensure $pdo is initialized
        
        if ($leLogin == $_POST['login']) {
            $loginOk = true;
            $passwordOk = true;
        } else {
            echo 'tentative d\'injection javascript - login refusé';
            $loginOk = false;
            $passwordOk = false;
        }
        
        $rempli = false;
        if ($loginOk && $passwordOk) {
            $rempli = true; 
            if (empty($leLogin)) {
                echo 'Le login n\'a pas été saisi<br/>';
                $rempli = false;
            }
            if (empty($lePassword)) {
                echo 'Le mot de passe n\'a pas été saisi<br/>';
                $rempli = false; 
            }
            if (empty($lePrenom)) {
                echo 'Le prenom n\'a pas été saisi<br/>';
                $rempli = false; 
            }
            if (empty($leNom)) {
                echo 'Le nom n\'a pas été saisi<br/>';
                $rempli = false; 
            }
            if (!isset($_POST['politiqcheck'])) {
                echo 'Veuillez cocher la case';
                $rempli = false;
            } else {
                $dateConsentement = date('Y-m-d H:i:s');
            }
            
            if ($rempli) {
                $leLogin = trim($leLogin);
                $lePassword = trim($lePassword);
                $lePrenom = trim($lePrenom);
                $leNom = trim($leNom);

                $nbCarMaxLogin = $pdo->tailleChamps('utilisateur', 'mail');
                if (strlen($leLogin) > $nbCarMaxLogin || strlen($lePassword) > $nbCarMaxLogin || strlen($lePrenom) > $nbCarMaxLogin || strlen($leNom) > $nbCarMaxLogin) {
                    echo 'Le login ne peut contenir plus de ' . $nbCarMaxLogin . '<br/>';
                    $loginOk = false;
                }

                if (!filter_var($leLogin, FILTER_VALIDATE_EMAIL)) {
                    echo 'le mail n\'a pas un format correct<br/>';
                    $loginOk = false;
                }
                
                $patternPassword = '#^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W){12,}#';
                if (!preg_match($patternPassword, $lePassword)) {
                    echo 'Le mot de passe doit contenir au moins 12 caractères, une majuscule,'
                    . ' une minuscule et un caractère spécial<br/>';
                    $passwordOk = false;
                }
            }
        }
        
        if ($rempli && $loginOk && $passwordOk) {
            echo 'tout est ok, nous allons pouvoir créer votre compte...<br/>';
            try {
                $executionOK = $pdo->creeutilisateur($leLogin, $lePassword, $leNom, $lePrenom, $dateConsentement);              
                if ($executionOK) {
                    echo "c'est bon, votre compte a bien été créé ;-)";
                    $pdo->connexionInitiale($leLogin);
                    header('Location: index.php?uc=connexion&action=demandeConnexion');
                    exit;
                } else {
                    echo "ce login existe déjà, veuillez en choisir un autre";
                    include("vues/v_creation.php");
                }
            } catch (Exception $e) {
                ajouterErreur("Erreur lors de la création du compte : " . $e->getMessage());
                include("vues/v_erreurs.php");
                include("vues/v_creation.php");
                exit;
            }
        } else {
            include("vues/v_creation.php");
        }
        
        break;	

    default:
        include("vues/v_creation.php");
        break;
}

?>

