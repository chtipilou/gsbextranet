<?php
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'chef_de_produit' && $_SESSION['role'] !== 'admin')) {
    header('Location: ../index.php');
    exit();
}
// Le reste de votre code suit ici...
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'include/class.pdogsb.inc.php';
require_once 'include/m_produits.php';

$pdo = PdoGsbProduit::getPdoGsbProduit();
$produitModel = new Produits($pdo);
$produits = $produitModel->consulterProduits();
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Produits</title>
    <a href="index.php?uc=accueil" class="btn btn-secondary mb-3"><i class="fas fa-arrow-left"></i> Retour</a>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<style>
    body {
        background-color: #f8f9fa;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        color: #343a40;
    }

    .main-container {
        padding-top: 80px;
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
        background-color: #ffffff;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    .table th {
        background-color: #343a40;
        color: white;
        text-align: center;
        padding: 15px;
    }

    .table td {
        text-align: center;
        vertical-align: middle;
        padding: 12px;
    }

    .btn {
        background-color: #343a40;
        color: white;
        border-radius: 0;
        padding: 10px 20px;
        border: none;
        cursor: pointer;
        min-width: 120px;
        transition: background-color 0.3s ease, transform 0.3s ease;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    }

    .btn:hover {
        background-color: #23272b;
        transform: scale(1.05);
    }

    .modal-content {
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    }

    .form-group {
        margin-bottom: 20px;
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

    /* Ajout/Modification des styles pour les boutons d'action */
    .btn-warning {
        background-color: #ffc107;
        border-color: #ffc107;
        color: white; /* Changed from #212529 to white */
    }

    .btn-warning:hover {
        background-color: #e0a800;
        border-color: #d39e00;
        color: #212529;
        transform: scale(1.05);
    }

    .btn-danger {
        background-color: #dc3545;
        border-color: #dc3545;
        color: white;
    }

    .btn-danger:hover {
        background-color: #c82333;
        border-color: #bd2130;
        transform: scale(1.05);
    }
</style>

<!-- Modification de la structure HTML -->
<body>
<div class="main-container">
    <h1 class="page-title">Gestion des Produits</h1>
    
    <div class="actions-container">
        <a href="index.php?uc=accueil" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
        <button class="btn btn-primary" data-toggle="modal" data-target="#modalAjouter">
            Ajouter un Produit
        </button>
    </div>

    <div class="table-container">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Objectif</th>
                    <th>Information</th>
                    <th>Effets Indésirables</th>
                    <th>Description</th>
                    <th>Prix</th>  
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($produits as $produit): ?>
                    <tr>
                        <td><?php echo $produit['id']; ?></td>
                        <td><?php echo htmlspecialchars($produit['nom']); ?></td>
                        <td><?php echo htmlspecialchars($produit['objectif']); ?></td>
                        <td><?php echo htmlspecialchars($produit['information']); ?></td>
                        <td><?php echo htmlspecialchars($produit['effetIndesirable']); ?></td>
                        <td><?php echo htmlspecialchars($produit['description']); ?></td> <!-- Nouvelle cellule -->
                        <td><?php echo htmlspecialchars($produit['prix']); ?></td>       <!-- Nouvelle cellule -->
                        <td><img src="assets/img/<?php echo htmlspecialchars($produit['image']); ?>" alt="<?php echo htmlspecialchars($produit['nom']); ?>" width="50"></td>
                        <td>
                            <button class="btn btn-warning mb-2" data-toggle="modal" data-target="#modalModifier" data-id="<?php echo $produit['id']; ?>" data-nom="<?php echo htmlspecialchars($produit['nom']); ?>" data-objectif="<?php echo htmlspecialchars($produit['objectif']); ?>" data-information="<?php echo htmlspecialchars($produit['information']); ?>" data-effetindesirable="<?php echo htmlspecialchars($produit['effetIndesirable']); ?>" data-description="<?php echo htmlspecialchars($produit['description']); ?>" data-prix="<?php echo htmlspecialchars($produit['prix']); ?>" data-image="<?php echo $produit['image']; ?>"><i class="fas fa-edit"></i> Modifier</button>
                            <form action="index.php?uc=produit&action=supprimer" method="POST" style="display:inline;">
                                <input type="hidden" name="id" value="<?php echo $produit['id']; ?>">
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce produit ?');"><i class="fas fa-trash"></i> Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal pour ajouter un produit -->
<div class="modal fade" id="modalAjouter" tabindex="-1" role="dialog" aria-labelledby="modalAjouterLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAjouterLabel">Ajouter un Produit</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="index.php?uc=produit&action=ajouter" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="action" value="ajouter">
                    <div class="form-group">
                        <label for="nom">Nom</label>
                        <input type="text" class="form-control" id="nom" name="nom" required>
                    </div>
                    <div class="form-group">
                        <label for="objectif">Objectif</label>
                        <textarea class="form-control" id="objectif" name="objectif" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="information">Information</label>
                        <textarea class="form-control" id="information" name="information" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="effetIndesirable">Effets Indésirables</label>
                        <textarea class="form-control" id="effetIndesirable" name="effetIndesirable" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="description">description</label>
                        <textarea class="form-control" id="description" name="description" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="prix">Prix</label>
                        <textarea class="form-control" id="prix" name="prix" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="image">Image</label>
                        <input type="file" class="form-control" id="image" name="image" accept="image/*">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                    <button type="submit" class="btn btn-primary">Ajouter</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal pour modifier un produit -->
<div class="modal fade" id="modalModifier" tabindex="-1" role="dialog" aria-labelledby="modalModifierLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalModifierLabel">Modifier un Produit</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="index.php?uc=produit&action=modifier" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="action" value="modifier">
                    <input type="hidden" name="id" id="modifier-id">
                    <div class="form-group">
                        <label for="modifier-nom">Nom</label>
                        <input type="text" class="form-control" id="modifier-nom" name="nom" required>
                    </div>
                    <div class="form-group">
                        <label for="modifier-objectif">Objectif</label>
                        <textarea class="form-control" id="modifier-objectif" name="objectif" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="modifier-information">Information</label>
                        <textarea class="form-control" id="modifier-information" name="information" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="modifier-effetIndesirable">Effets Indésirables</label>
                        <textarea class="form-control" id="modifier-effetIndesirable" name="effetIndesirable" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description" name="description" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="prix">Prix</label>
                        <input type="number" class="form-control" id="prix" name="prix" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label for="modifier-image">Image</label>
                        <input type="file" class="form-control" id="modifier-image" name="image" accept="image/*">
                        <small>Image actuelle: <span id="image-actuelle"></span></small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                    <button type="submit" class="btn btn-primary">Modifier</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Remplir le modal de modification avec les données du produit sélectionné
    $('#modalModifier').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        var nom = button.data('nom');
        var objectif = button.data('objectif');
        var information = button.data('information');
        var effetIndesirable = button.data('effetindesirable');
        var description = button.data('description');
        var prix = button.data('prix');
        var image = button.data('image');


        var modal = $(this);
        modal.find('#modifier-id').val(id);
        modal.find('#modifier-nom').val(nom);
        modal.find('#modifier-objectif').val(objectif);
        modal.find('#modifier-information').val(information);
        modal.find('#modifier-effetIndesirable').val(effetIndesirable);
        modal.find('#description').val(description);
        modal.find('#prix').val(prix);
        modal.find('#image-actuelle').text(image);
    });
</script>

</body>
</html>
