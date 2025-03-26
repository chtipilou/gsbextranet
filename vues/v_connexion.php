<!DOCTYPE html>
<html lang="fr">
<head>
    <title>GSB - Connexion</title>
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
            <h2 class="text-center mb-4">Connexion</h2>
            <form method="post" action="index.php?uc=connexion&action=valideConnexion">
                <input name="login" class="form-control" type="text" placeholder="Identifiant" required>
                <input name="mdp" class="form-control" type="password" placeholder="Mot de passe" required>
                <button type="submit" class="btn btn-modern w-100">Se connecter</button>
            </form>
            <div class="text-center mt-3">
                <a href="index.php?uc=creation&action=demandeCreation">Créer un compte médecin</a>
            </div>
        </div>
    </div>
</body>
</html>