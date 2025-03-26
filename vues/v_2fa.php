<?php

require_once 'include/class.pdogsb.inc.php';

// Récupération du code 2FA depuis la base de données
if (isset($_SESSION['id'])) {
    $utilisateur = PdoGsb::getPdoGsb()->donneinfosutilisateur($_SESSION['id']);
    if ($utilisateur && isset($utilisateur['codeVerification'])) {
        $_SESSION['2fa'] = $utilisateur['codeVerification']; 
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <title>GSB - Authentification 2FA</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/styles.css" rel="stylesheet">
    <style>
        /* Add this style to center the form vertically and horizontally */
        .main-container {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh; /* Full viewport height */
        }
        .content-box {
            width: 100%;
            max-width: 400px; /* Set a max width for the form */
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background-color: #fff;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="main-container">
        <div class="content-box">
            <h2 class="text-center mb-4">Authentification à 2 facteurs</h2>
            <?php if (isset($_SESSION['2fa'])): ?>
                <div class="alert alert-info">
                    Votre code 2FA : <?php echo htmlspecialchars($_SESSION['2fa']); ?>
                </div>
                <form method="post" action="index.php?uc=2fa&action=envoyerCode">
                    <button type="submit" class="btn btn-modern w-100 mb-3">Envoyer le code par email</button>
                </form>
            <?php endif; ?>
            
            <form method="post" action="index.php?uc=2fa&action=valideCode">
                <input name="2fa" class="form-control" type="text" placeholder="Code 2FA" required>
                <button type="submit" class="btn btn-modern w-100 mb-3">Valider</button>
            </form>
            
            <form method="post" action="index.php?uc=2fa&action=envoyerCode">
                <button type="submit" class="btn btn-modern w-100">Renvoyer le code</button>
            </form>
        </div>
    </div>
</body>
</html>
