<?php
session_start();

require_once '../include/class.pdogsb.inc.php';

// Récupération du code 2FA depuis la base de données
if (isset($_SESSION['id'])) {
    $medecin = PdoGsb::getPdoGsb()->donneinfosmedecin($_SESSION['id']);
    if ($medecin) {
        $_SESSION['2fa'] = $medecin['codeVerification']; 
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GSB - Authentification 2FA</title>
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/a2f.css" rel="stylesheet">
</head>

<body>
    <div class="page-content">
        <div class="login-wrapper">
            <div class="box">
                <div class="content-wrap">
                    <legend>Authentification à 2 facteurs</legend>
                    <form method="post" action="../index.php?uc=connexion&action=valideCode">
                        <?php if (isset($_SESSION['2fa'])): ?>
                            <div class="alert alert-info text-center">
                                Votre code 2FA est : <?php echo htmlspecialchars($_SESSION['2fa']); ?>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-warning text-center">
                                Aucun code 2FA disponible.
                            </div>
                        <?php endif; ?>
                        
                        <input name="2fa" class="form-control" type="text" placeholder="Entrez votre code 2FA" required>
                        <br>
                        <button type="submit" class="btn btn-primary btn-block">Valider</button>
                    </form>
                    <br>
                    <a href="#">Je n'ai pas reçu le code</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
</body>
</html>
