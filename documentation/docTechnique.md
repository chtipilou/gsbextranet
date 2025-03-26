# Documentation technique - GSB Extranet B3

## Architecture technique

### Structure MVC
```
/gsbextranetB3
├── controleurs/      # Logique de contrôle
├── include/          # Classes modèles et utilitaires
├── vues/             # Interface utilisateur
├── assets/           # Ressources statiques
├── bootstrap/        # Framework CSS
└── vendor/           # Dépendances (PHPMailer, etc.)
```

### Modèles principaux
- `class.pdogsb.inc.php` : Accès principal à la base de données
- `m_produits.php` : Opérations CRUD pour les produits
- `m_visioconferences.php` : Gestion des visioconférences
- `m_statistiques.php` : Journalisation et statistiques

### Bases de données
- `gsbextranetAP` : Base principale
- `gsbextranetArchive` : Stockage pour données archivées (RGPD)

### Schéma de base de données
```sql
-- Principales tables du système
CREATE TABLE utilisateur (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(100),
  prenom VARCHAR(100),
  mail VARCHAR(255) UNIQUE,
  motDePasse VARCHAR(255),
  telephone VARCHAR(15),
  dateNaissance DATE,
  dateCreation DATETIME,
  rpps VARCHAR(50),
  token VARCHAR(100),
  codeVerification VARCHAR(10),
  dateVerification DATETIME,
  dateConsentement DATETIME,
  role VARCHAR(50)
);

CREATE TABLE visioconference (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nomVisio VARCHAR(255),
  objectif TEXT,
  url VARCHAR(255),
  dateVisio DATETIME,
  image VARCHAR(255)
);

CREATE TABLE produits (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(255),
  objectif TEXT,
  information TEXT,
  effetIndesirable TEXT,
  description TEXT,
  prix DECIMAL(10,2),
  image VARCHAR(255)
);

CREATE TABLE logs_operations (
  id INT AUTO_INCREMENT PRIMARY KEY,
  idutilisateur INT,
  adresse_ip VARCHAR(50),
  action VARCHAR(255),
  date DATETIME,
  FOREIGN KEY (idutilisateur) REFERENCES utilisateur(id)
);

CREATE TABLE historiqueconnexion (
  id INT AUTO_INCREMENT PRIMARY KEY,
  idUtilisateur INT,
  dateDebutLog DATETIME,
  dateFinLog DATETIME,
  FOREIGN KEY (idUtilisateur) REFERENCES utilisateur(id)
);

CREATE TABLE settings (
  id INT AUTO_INCREMENT PRIMARY KEY,
  maintenance_mode BOOLEAN
);
```

## Fonctionnalités techniques

### Authentification à deux facteurs
```php
// Génération du code
function genererCodeVerification() {
    return rand(100000, 999999);
}

// Stockage du code
function enregistrerCodeVerification($idutilisateur, $codeVerification) {
    $pdo = PdoGsb::$monPdo;
    $requete = "UPDATE utilisateur SET codeVerification = :codeVerification, 
               dateVerification = NOW() WHERE id = :idutilisateur";
    $stmt = $pdo->prepare($requete);
    $stmt->bindValue(':codeVerification', $codeVerification);
    $stmt->bindValue(':idutilisateur', $idutilisateur);
    $stmt->execute();
}

// Vérification du code
function verifierCodeVerification($idutilisateur, $code2fa) {
    $pdo = PdoGsb::$monPdo; 
    $sql = "SELECT codeVerification FROM utilisateur WHERE id = :idutilisateur";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':idutilisateur', $idutilisateur, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result && isset($result['codeVerification'])) {
        if ($result['codeVerification'] == $code2fa) {
            return true;
        }
    }
    return false;
}
```

### Journalisation des opérations
```php
function logOperation($idutilisateur, $action) {
    $adresse_ip = $_SESSION['user_ip'] ?? ($_SERVER['HTTP_X_FORWARDED_FOR'] 
        ?? $_SERVER['HTTP_CLIENT_IP'] ?? $_SERVER['REMOTE_ADDR']);
    
    $sql = "INSERT INTO logs_operations (idutilisateur, adresse_ip, action, date) 
           VALUES (?, ?, ?, NOW())";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([$idutilisateur, $adresse_ip, $action]);
}
```

### Mode maintenance
```php
// Vérification du mode maintenance
$stmt = $pdo->query("SELECT maintenance_mode FROM settings LIMIT 1");
$maintenanceMode = $stmt->fetchColumn();

// Si site en maintenance et utilisateur non admin
if ($maintenanceMode && !$isAdmin) {
    // Redirection vers page de maintenance
    header('Location: maintenance.php');
    exit;
}

// Si site en maintenance et utilisateur admin
if ($maintenanceMode && $isAdmin) {
    // Affichage d'un bandeau d'avertissement
    echo '<div class="maintenance-warning">Site en maintenance</div>';
}
```

### Portabilité des données RGPD
```php
// Export des données
function donneinfoPortabilite($id) {
    $requete = "SELECT nom, prenom, telephone, mail, dateNaissance, 
               dateCreation, rpps, token, dateConsentement 
               FROM utilisateur WHERE id = :lId";
    $stmt = $this->pdo->prepare($requete);
    $stmt->bindValue(':lId', $id);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Archivage des données
function archiverUtilisateur($id) {
    // Copie vers la base d'archive
    $sql = "INSERT INTO gsbextranetArchive.utilisateur
            SELECT * FROM utilisateur WHERE id = :id";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':id' => $id]);
    
    // Suppression de l'utilisateur actif
    $this->supprimerUtilisateur($id);
}
```

## Scénarios pour l'épreuve (2h)

### 1. Ajout d'un filtre de recherche pour les produits
**Difficulté: Facile**

Ajouter un champ de recherche simple pour filtrer les produits par nom.

```php
// Dans m_produits.php, ajouter:
public function rechercherProduits($terme) {
    $sql = "SELECT * FROM produits WHERE nom LIKE :terme";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':terme' => "%$terme%"]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Dans le contrôleur:
if (isset($_GET['recherche'])) {
    $resultats = $produits->rechercherProduits($_GET['recherche']);
} else {
    $resultats = $produits->consulterProduits();
}

// Dans la vue, ajouter un formulaire:
<form method="GET" action="index.php?uc=produit">
    <input type="text" name="recherche" placeholder="Rechercher...">
    <button type="submit">Rechercher</button>
</form>
```

### 2. Ajout d'une notification email pour les visioconférences
**Difficulté: Moyenne**

Envoyer un email de rappel 24h avant une visioconférence.

```php
// Créer un script à exécuter via CRON:
$pdo = new PDO('mysql:host=localhost;dbname=gsbextranetAP', 'user', 'pwd');
$tomorrow = date('Y-m-d H:i:s', strtotime('+24 hours'));
$limit = date('Y-m-d H:i:s', strtotime('+25 hours'));

$sql = "SELECT v.nomVisio, v.dateVisio, v.url, u.mail, u.prenom 
       FROM visioconference v 
       JOIN inscription_visio iv ON v.id = iv.idVisio
       JOIN utilisateur u ON iv.idUtilisateur = u.id
       WHERE v.dateVisio BETWEEN :tomorrow AND :limit";

$stmt = $pdo->prepare($sql);
$stmt->execute([':tomorrow' => $tomorrow, ':limit' => $limit]);
$rappels = $stmt->fetchAll();

foreach ($rappels as $rappel) {
    // Utiliser PHPMailer pour envoyer l'email
    // ...
}
```

### 3. Amélioration de l'affichage du mode maintenance
**Difficulté: Facile**

Créer une page de maintenance plus professionnelle avec un délai estimé.

```php
// Modifier la table settings
ALTER TABLE settings ADD COLUMN fin_maintenance DATETIME;

// Dans le contrôleur de maintenance
if (isset($_POST['fin_maintenance'])) {
    $finMaintenance = $_POST['fin_maintenance'];
    $stmt = $pdo->prepare("UPDATE settings SET fin_maintenance = ? WHERE id = 1");
    $stmt->execute([$finMaintenance]);
}

// Dans la page de maintenance (maintenance.php)
$stmt = $pdo->query("SELECT fin_maintenance FROM settings WHERE id = 1");
$finMaintenance = $stmt->fetchColumn();
```

```html
<div class="maintenance-page">
    <h1>Site en maintenance</h1>
    <p>Nous effectuons des mises à jour pour améliorer nos services.</p>
    <?php if ($finMaintenance): ?>
        <p>Fin prévue: <?= date('d/m/Y à H:i', strtotime($finMaintenance)) ?></p>
    <?php endif; ?>
</div>
```

### 4. Création d'un tableau de bord simple pour les admins
**Difficulté: Moyenne**

Ajouter une page avec des statistiques de base (nombre d'utilisateurs, produits, visioconférences).

```php
// Dans m_statistiques.php
function getStatistiquesGenerales() {
    return [
        'nbUtilisateurs' => $this->compterElements('utilisateur'),
        'nbProduits' => $this->compterElements('produits'),
        'nbVisios' => $this->compterElements('visioconference'),
        'nbConnexions' => $this->compterConnexions(),
        'dernierProduit' => $this->getDernierElement('produits', 'nom')
    ];
}

function compterElements($table) {
    $sql = "SELECT COUNT(*) FROM $table";
    $stmt = $this->pdo->query($sql);
    return $stmt->fetchColumn();
}

function compterConnexions() {
    $sql = "SELECT COUNT(*) FROM historiqueconnexion 
           WHERE dateDebutLog > DATE_SUB(NOW(), INTERVAL 7 DAY)";
    $stmt = $this->pdo->query($sql);
    return $stmt->fetchColumn();
}

function getDernierElement($table, $champ) {
    $sql = "SELECT $champ FROM $table ORDER BY id DESC LIMIT 1";
    $stmt = $this->pdo->query($sql);
    return $stmt->fetchColumn();
}
```

### 5. Tri des visioconférences par date
**Difficulté: Facile**

Ajouter une fonctionnalité de tri pour afficher les visioconférences par date (croissant/décroissant).

```php
// Dans m_visioconferences.php
function consulterVisioconferencesTriees($ordre = 'ASC') {
    $ordre = strtoupper($ordre) === 'DESC' ? 'DESC' : 'ASC';
    $sql = "SELECT * FROM visioconference ORDER BY dateVisio $ordre";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Dans le contrôleur
$ordre = isset($_GET['ordre']) ? $_GET['ordre'] : 'ASC';
$visios = $visioconferences->consulterVisioconferencesTriees($ordre);

// Dans la vue
<a href="?uc=visio&ordre=ASC">Plus récentes</a> | 
<a href="?uc=visio&ordre=DESC">Plus anciennes</a>
```

## Suggestions d'améliorations simples

1. **Pagination des résultats**
   - Limiter à 10 résultats par page pour les listes de produits et visioconférences

2. **Exportation CSV des logs**
   - Ajouter un bouton pour télécharger les logs au format CSV

3. **Thème clair/sombre**
   - Implémenter un switch pour changer le thème de l'interface

4. **Notification de session expirée**
   - Ajouter une alerte JavaScript pour prévenir avant l'expiration de session

5. **Suggestions de produits similaires**
   - Afficher des produits similaires basés sur les mots-clés du produit consulté