<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Visioconférences</title>
    <a href="index.php?uc=accueil" class="btn btn-secondary mb-3"><i class="fas fa-arrow-left"></i> Retour</a>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<style>
    body {
        background-color: #f8f9fa; /* Couleur de fond claire */
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; /* Police moderne */
        color: #343a40; /* Couleur de texte standard */
        transition: background-color 0.3s ease; /* Animation de la couleur de fond */
    }

    h1 {
        color: #343a40; /* Changed from blue to black-gray */
        margin-bottom: 20px; /* Espacement sous le titre */
        text-align: center; /* Centrer le titre */
    }

    .btn {
        background-color: #343a40; /* Black color */
        color: white;
        border-radius: 0; /* Square corners */
        padding: 10px 20px;
        border: none;
        cursor: pointer;
        transition: background-color 0.3s ease, transform 0.3s ease; /* Animation pour les boutons */
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2); /* Enhanced shadow */
    }

    .btn:hover {
        background-color: #23272b; /* Darker black on hover */
        transform: scale(1.05); /* Légère augmentation de taille au survol */
    }

    .table {
        background-color: #ffffff; /* Couleur de fond du tableau */
        border-radius: 10px; /* Coins arrondis pour le tableau */
        overflow: hidden; /* Pour que les coins arrondis fonctionnent */
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2); /* Enhanced shadow */
        transition: box-shadow 0.3s ease; /* Animation pour l'ombre */
    }

    .table:hover {
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15); /* Ombre plus forte au survol */
    }

    .table th {
        background-color: #343a40; /* Changed from blue to black-gray */
        color: white; /* Couleur du texte des en-têtes */
        text-align: center; /* Centrer le texte des en-têtes */
        transition: background-color 0.3s ease; /* Animation pour les en-têtes */
    }

    .table th:hover {
        background-color: #0056b3; /* Couleur plus foncée au survol des en-têtes */
    }

    .table td {
        vertical-align: middle; /* Alignement vertical des cellules */
        transition: background-color 0.2s ease; /* Animation pour les cellules */
    }

    .table tr:hover td {
        background-color: #f1f1f1; /* Changement de couleur au survol de la ligne */
    }

    .modal-header {
        background-color: #007bff; /* Couleur d'arrière-plan de l'en-tête du modal */
        color: white; /* Couleur du texte de l'en-tête du modal */
        transition: background-color 0.3s ease; /* Animation pour l'en-tête du modal */
    }

    .modal-header:hover {
        background-color: #0056b3; /* Couleur plus foncée au survol de l'en-tête du modal */
    }

    .modal-footer {
        border-top: none; /* Supprimer la bordure supérieure */
    }

    .modal-body label {
        font-weight: bold; /* Mettre en gras les étiquettes */
    }

    .form-control {
        border-radius: 10px; /* Coins arrondis pour les champs de formulaire */
        transition: border 0.3s ease; /* Animation pour les champs de formulaire */
    }

    .form-control:focus {
        border-color: #007bff; /* Changement de couleur de la bordure au focus */
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.5); /* Ombre portée au focus */
    }

    .btn-warning {
        background-color: #ffc107; /* Couleur d'arrière-plan pour les boutons de modification */
        border-color: #ffc107; /* Couleur de la bordure pour les boutons de modification */
    }

    .btn-danger {
        background-color: #dc3545; /* Couleur d'arrière-plan pour les boutons de suppression */
        border-color: #dc3545; /* Couleur de la bordure pour les boutons de suppression */
    }

    .modal-content {
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2); /* Enhanced shadow */
    }

    .main-container {
        padding-top: 80px; /* Espace pour la navbar */
        max-width: 1200px;
        margin: 0 auto;
        padding-bottom: 40px;
    }

    .page-title {
        text-align: center;
        margin-bottom: 30px;
        font-size: 2.5em;
        color: #343a40;
    }

    .actions-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .table-container {
        width: 100%;
        overflow-x: auto;
        margin-top: 20px;
        background: white;
        border-radius: 10px;
        box-shadow: 0 0 20px rgba(0,0,0,0.1);
    }

    .table {
        margin: 0 auto;
        width: 100%;
    }

    .table th {
        text-align: center;
        background-color: #343a40;
        color: white;
        padding: 15px;
    }

    .table td {
        text-align: center;
        vertical-align: middle;
        padding: 12px;
    }

    .btn-container {
        display: flex;
        gap: 10px;
        justify-content: center;
    }

    .modal-dialog {
        max-width: 500px;
        margin: 1.75rem auto;
    }

    .modal-content {
        border-radius: 10px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .btn {
        min-width: 120px;
    }

    @media (max-width: 768px) {
        .main-container {
            padding: 60px 15px 20px;
        }
        
        .actions-container {
            flex-direction: column;
            gap: 10px;
        }
    }
</style>

<body>
<div class="main-container">
    <h1 class="page-title">Gestion des Visioconférences</h1>
    
    <div class="actions-container">
        <a href="index.php?uc=accueil" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
        <button class="btn btn-primary" data-toggle="modal" data-target="#modalAjouter">
            Ajouter une Visioconférence
        </button>
    </div>

    <div class="table-container">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom de la Visioconférence</th>
                    <th>Objectif</th>
                    <th>URL</th>
                    <th>Date de la Visioconférence</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($visios as $visio): ?>
                    <tr>
                        <td><?php echo $visio['id']; ?></td>
                        <td><?php echo htmlspecialchars($visio['nomVisio']); ?></td>
                        <td><?php echo htmlspecialchars($visio['objectif']); ?></td>
                        <td><?php echo htmlspecialchars($visio['url']); ?></td>
                        <td><?php echo htmlspecialchars($visio['dateVisio']); ?></td>
                        <td><img src="assets/img/<?php echo htmlspecialchars($visio['image']); ?>" alt="<?php echo htmlspecialchars($visio['nomVisio']); ?>" width="50"></td>
                        <td>
                            <button class="btn btn-warning" data-toggle="modal" data-target="#modalModifier" 
                                data-id="<?php echo $visio['id']; ?>" 
                                data-nom="<?php echo htmlspecialchars($visio['nomVisio']); ?>" 
                                data-objectif="<?php echo htmlspecialchars($visio['objectif']); ?>" 
                                data-url="<?php echo htmlspecialchars($visio['url']); ?>" 
                                data-date="<?php echo htmlspecialchars($visio['dateVisio']); ?>" 
                                data-image="<?php echo $visio['image']; ?>">
                                <i class="fas fa-edit"></i> Modifier
                            </button>
                            <form action="index.php?uc=visio&action=supprimer" method="POST" style="display:inline;">
                                <input type="hidden" name="id" value="<?php echo $visio['id']; ?>">
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette visioconférence ?');">
                                    <i class="fas fa-trash"></i> Supprimer
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal pour ajouter une visioconférence -->
<div class="modal fade" id="modalAjouter" tabindex="-1" role="dialog" aria-labelledby="modalAjouterLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAjouterLabel">Ajouter une Visioconférence</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="index.php?uc=visio&action=ajouter" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="action" value="ajouter">
                    <div class="form-group">
                        <label for="nomVisio">Nom de la Visioconférence</label>
                        <input type="text" class="form-control" id="nomVisio" name="nomVisio" required>
                    </div>
                    <div class="form-group">
                        <label for="objectif">Objectif</label>
                        <textarea class="form-control" id="objectif" name="objectif" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="url">URL</label>
                        <input type="url" class="form-control" id="url" name="url" required>
                    </div>
                    <div class="form-group">
                        <label for="dateVisio">Date de la Visioconférence</label>
                        <input type="date" class="form-control" id="dateVisio" name="dateVisio" required>
                    </div>
                    <div class="form-group">
                        <label for="image">Image</label>
                        <input type="file" class="form-control-file" id="image" name="image" accept="image/*" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Ajouter</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal pour modifier une visioconférence -->
<div class="modal fade" id="modalModifier" tabindex="-1" role="dialog" aria-labelledby="modalModifierLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalModifierLabel">Modifier une Visioconférence</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="index.php?uc=visio&action=modifier" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="action" value="modifier">
                    <input type="hidden" id="id" name="id">
                    <div class="form-group">
                        <label for="nomVisioMod">Nom de la Visioconférence</label>
                        <input type="text" class="form-control" id="nomVisioMod" name="nomVisio" required>
                    </div>
                    <div class="form-group">
                        <label for="objectifMod">Objectif</label>
                        <textarea class="form-control" id="objectifMod" name="objectif" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="urlMod">URL</label>
                        <input type="url" class="form-control" id="urlMod" name="url" required>
                    </div>
                    <div class="form-group">
                        <label for="dateVisioMod">Date de la Visioconférence</label>
                        <input type="date" class="form-control" id="dateVisioMod" name="dateVisio" required>
                    </div>
                    <div class="form-group">
                        <label for="imageMod">Image</label>
                        <input type="file" class="form-control-file" id="imageMod" name="image" accept="image/*">
                        <small class="form-text text-muted">Laissez vide si vous ne souhaitez pas changer l'image.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Modifier</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Remplir les champs du modal de modification
    $('#modalModifier').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Bouton qui a ouvert le modal
        var id = button.data('id'); // Extraire les données de l'attribut data-id
        var nom = button.data('nom'); // Extraire les données de l'attribut data-nom
        var objectif = button.data('objectif'); // Extraire les données de l'attribut data-objectif
        var url = button.data('url'); // Extraire les données de l'attribut data-url
        var date = button.data('date'); // Extraire les données de l'attribut data-date
        var image = button.data('image'); // Extraire les données de l'attribut data-image

        var modal = $(this);
        modal.find('#id').val(id);
        modal.find('#nomVisioMod').val(nom);
        modal.find('#objectifMod').val(objectif);
        modal.find('#urlMod').val(url);
        modal.find('#dateVisioMod').val(date);
    });
</script>
</body>
</html>
