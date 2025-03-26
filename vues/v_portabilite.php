<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gérer mes données</title>
    <style>
        .portabilite-container {
            padding-top: 80px; /* Espace pour la navbar */
            min-height: calc(100vh - 80px);
            display: flex;
            justify-content: center;
            align-items: flex-start;
            background-color: #f8f9fa;
        }
        .portabilite-content {
            width: 100%;
            max-width: 600px;
            margin: 20px;
            background-color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            padding: 30px;
        }
        .portabilite-title {
            text-align: center;
            color: #343a40;
            margin-bottom: 25px;
        }
        .portabilite-alert {
            text-align: center;
            font-size: 14px;
            margin-bottom: 25px;
            padding: 15px;
            background-color: #cce5ff;
            border: 1px solid #b8daff;
            border-radius: 4px;
            color: #004085;
        }
        .portabilite-btn {
            display: block;
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .btn-download {
            background-color: #007bff;
            color: white;
        }
        .btn-download:hover {
            background-color: #0056b3;
        }
        .btn-archive {
            background-color: #28a745;
            color: white;
        }
        .btn-archive:hover {
            background-color: #218838;
        }
        .btn-delete {
            background-color: #dc3545;
            color: white;
        }
        .btn-delete:hover {
            background-color: #c82333;
        }
        .portabilite-check {
            margin-bottom: 15px;
            text-align: left;
        }
        .portabilite-check label {
            font-size: 14px;
            margin-left: 5px;
        }
    </style>
</head>
<body>
    <div class="portabilite-container">
        <div class="portabilite-content">
            <h1 class="portabilite-title">Gérer mes données</h1>
            <div class="portabilite-alert">
                Vous pouvez modifier vos informations personnelles ci-dessous.
            </div>

            <form method="post" action="index.php?uc=portabilite&action=modifier">
                <?php
                // Pré-remplir les champs avec les informations actuelles de l'utilisateur
                $utilisateur = $_SESSION['utilisateur'];
                // Format the date to YYYY-MM-DD if it's not empty
                $dateNaissanceFormatted = !empty($utilisateur['dateNaissance']) ? date('Y-m-d', strtotime($utilisateur['dateNaissance'])) : '';
                ?>
                <div class="form-group">
                    <label for="nom">Nom:</label>
                    <input type="text" id="nom" name="nom" value="<?php echo htmlspecialchars($utilisateur['nom']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="prenom">Prénom:</label>
                    <input type="text" id="prenom" name="prenom" value="<?php echo htmlspecialchars($utilisateur['prenom']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="telephone">Téléphone:</label>
                    <input type="text" id="telephone" name="telephone" value="<?php echo htmlspecialchars($utilisateur['telephone']); ?>">
                </div>
                <div class="form-group">
                    <label for="dateNaissance">Date de Naissance:</label>
                    <input type="date" id="dateNaissance" name="dateNaissance" value="<?php echo htmlspecialchars($dateNaissanceFormatted); ?>">
                </div>
                <div class="form-group">
                    <label for="mail">Email:</label>
                    <input type="email" id="mail" name="mail" value="<?php echo htmlspecialchars($utilisateur['mail']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="rpps">RPPS:</label>
                    <input type="text" id="rpps" name="rpps" value="<?php echo htmlspecialchars($utilisateur['rpps']); ?>">
                </div>
                <!-- Ajoutez d'autres champs si nécessaire -->
                <button type="submit" class="portabilite-btn btn-save">Enregistrer mes modifications</button>
            </form>

            <div class="portabilite-alert">
                Vous pouvez télécharger vos données, les archiver dans une base sécurisée ou supprimer vos données de manière permanente.
            </div>
            
            <form method="post" action="index.php?uc=portabilite&action=telecharger">
                <button type="submit" class="portabilite-btn btn-download">Télécharger mes données</button>
            </form>
            
            <form action="index.php?uc=portabilite&action=archiver&archive=true" method="post" onsubmit="return confirmAction('archiver')">
                <div class="portabilite-check">
                    <input type="checkbox" id="confirmArchive" required>
                    <label for="confirmArchive">Je comprends que cette action archivera mes données dans une base sécurisée.</label>
                </div>
                <button type="submit" class="portabilite-btn btn-archive">Archiver mes données</button>
            </form>

            <form action="index.php?uc=portabilite&action=supprimer" method="post" onsubmit="return confirmAction('supprimer')">
                <div class="portabilite-check">
                    <input type="checkbox" id="confirmDelete" required>
                    <label for="confirmDelete">Je comprends que cette action supprimera définitivement mes données.</label>
                </div>
                <button type="submit" class="portabilite-btn btn-delete">Supprimer mes données</button>
            </form>
        </div>
    </div>

    <!-- Lien vers jQuery et Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        // Fonction de confirmation avant chaque action (archiver ou supprimer)
        function confirmAction(action) {
            var checkboxId = (action === 'archiver') ? 'confirmArchive' : 'confirmDelete';
            return document.getElementById(checkboxId).checked;
        }
    </script>
</body>
</html>
