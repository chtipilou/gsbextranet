# Documentation du Projet GSB Extranet B3

## Table des matières
1. [Introduction](#introduction)
2. [Architecture du système](#architecture-du-système)
3. [Fonctionnalités détaillées](#fonctionnalités-détaillées)
4. [Rôles utilisateurs](#rôles-utilisateurs)
5. [User Stories](#user-stories)
6. [Guide d'utilisation](#guide-dutilisation)
7. [Aspects techniques](#aspects-techniques)
8. [Suggestions pour l'épreuve](#suggestions-pour-lépreuve)

## Introduction

Le projet GSB Extranet est une application web destinée à la société Galaxy Swiss Bourdin, une entreprise pharmaceutique. L'application permet aux différents utilisateurs (médecins, administrateurs, chefs de produit) de s'inscrire à des visioconférences, consulter des produits, et gérer leurs données personnelles. Pour les administrateurs, elle offre des fonctionnalités supplémentaires comme la gestion des produits, des visioconférences, et l'accès aux statistiques des opérations.

### Contexte du projet

Galaxy Swiss Bourdin (GSB) est une entreprise résultant de la fusion de deux sociétés pharmaceutiques : le géant américain Galaxy et le groupe suisse Bourdin. Cette fusion a nécessité une refonte complète de l'infrastructure informatique, incluant la création d'un extranet permettant aux médecins et aux employés d'accéder à diverses fonctionnalités.

### Objectifs

- Fournir une plateforme sécurisée pour les médecins et employés
- Permettre la consultation et l'inscription aux visioconférences
- Faciliter l'accès aux informations produits
- Garantir la conformité RGPD avec des fonctionnalités de portabilité des données
- Offrir des outils d'administration pour la gestion du site

## Architecture du système

### Structure des dossiers
- `/vues/` : Contient les fichiers de vue (interface utilisateur)
- `/controleurs/` : Contient les contrôleurs qui gèrent les requêtes
- `/include/` : Contient les classes modèles et fonctions utilitaires
- `/assets/` : Ressources statiques (images, CSS)
- `/bootstrap/` : Fichiers Bootstrap pour le style
- `/docs/` : Documentation du projet
- `/vendor/` : Bibliothèques tierces (PHPMailer, etc.)

### Architecture MVC

Le projet suit le modèle d'architecture MVC (Modèle-Vue-Contrôleur) :

- **Modèles** : Classes dans `/include/` qui gèrent l'accès aux données
  - `class.pdogsb.inc.php` : Classe principale d'accès à la base de données
  - `m_produits.php` : Gestion des produits
  - `m_visioconferences.php` : Gestion des visioconférences
  - `m_statistiques.php` : Accès aux statistiques et logs
  - `fct.inc.php` : Fonctions utilitaires

- **Vues** : Fichiers PHP dans `/vues/` qui gèrent l'affichage
  - `v_sommaire.php` : Menu de navigation principal
  - `v_portabilite.php` : Interface de gestion des données personnelles
  - `v_gestionMaintenance.php` : Interface admin pour le mode maintenance

- **Contrôleurs** : Fichiers PHP dans `/controleurs/` qui traitent les requêtes
  - Contrôleurs pour chaque fonctionnalité (produits, visio, etc.)

### Base de données
- Base principale : `gsbextranetAP`
  - Tables principales : `utilisateur`, `produits`, `visioconference`, `logs_operations`, `settings`
- Base d'archive : `gsbextranetArchive` (pour les données archivées)
  - Tables miroir de la base principale pour archivage RGPD

### Diagramme de l'architecture système

```
+------------------+     +------------------+     +------------------+
|     CLIENT       |     |     SERVEUR      |     |   BASE DE       |
|    (Navigateur)  |     |     (PHP)        |     |    DONNÉES      |
+------------------+     +------------------+     +------------------+
        |                        |                        |
        |  HTTP Request          |                        |
        |----------------------->|                        |
        |                        |                        |
        |                        |  SQL Query             |
        |                        |----------------------->|
        |                        |                        |
        |                        |  SQL Response          |
        |                        |<-----------------------|
        |                        |                        |
        |  HTTP Response         |                        |
        |<-----------------------|                        |
```

### Sécurité
- Authentification à double facteur
- Sessions sécurisées
- Hachage des mots de passe avec PHP `password_hash()`
- Protection contre les injections SQL via PDO
- Validation des entrées utilisateur
- Gestion des droits d'accès par rôle

## Fonctionnalités détaillées

### 1. Gestion des utilisateurs
- **Inscription** avec validation par email
  - Formulaire d'inscription collectant les informations nécessaires
  - Stockage sécurisé des mots de passe (hash)
  - Validation du compte via email
- **Connexion** avec authentification à deux facteurs
  - Saisie de l'email et du mot de passe
  - Génération et envoi d'un code à 6 chiffres par email
  - Validation du code pour accéder à l'application
- **Modification des informations personnelles**
  - Interface dédiée pour mettre à jour le profil
  - Validation des données saisies
- **Téléchargement des données personnelles (RGPD)**
  - Export de toutes les données personnelles au format JSON
- **Archivage ou suppression du compte**
  - Possibilité d'archiver les données (déplacement vers la base d'archive)
  - Option de suppression définitive des données

### 2. Gestion des visioconférences
- **Consultation des visioconférences disponibles**
  - Liste des visioconférences avec détails (date, objectif, URL)
  - Filtre par date ou thème
- **Inscription à une visioconférence**
  - Système d'inscription simple en un clic
  - Envoi d'un rappel par email avant la visioconférence
- **Pour les administrateurs**
  - Création de nouvelles visioconférences
  - Modification des détails (date, objectif, URL)
  - Suppression des visioconférences obsolètes
  - Suivi des inscriptions

### 3. Gestion des produits
- **Consultation des produits**
  - Catalogue des produits avec description détaillée
  - Filtres de recherche et tri
- **Pour les administrateurs et chefs de produit**
  - Interface d'ajout de nouveaux produits
  - Formulaire de modification des produits existants
  - Option de suppression des produits obsolètes
  - Gestion des images associées aux produits

### 4. Statistiques et journalisation
- **Consultation des logs d'opérations** (pour les administrateurs)
  - Tableau des actions effectuées par les utilisateurs
  - Horodatage et identification des actions (IP, utilisateur)
  - Filtres par type d'action, utilisateur, période
- **Enregistrement automatique des actions**
  - Traçage de toutes les actions sensibles (connexion, modification, suppression)
  - Stockage des données dans la table `logs_operations`

### 5. Mode maintenance
- **Activation/désactivation du mode maintenance** (pour les administrateurs)
  - Interface simple avec case à cocher
  - Effet immédiat sur l'application
- **Gestion des accès pendant la maintenance**
  - Redirection des utilisateurs standard vers une page de maintenance
  - Accès préservé pour les administrateurs avec bannière d'avertissement
  - Notification de l'état de maintenance

### 6. Portabilité des données
- **Export des données utilisateur au format JSON**
  - Fonctionnalité conforme aux exigences RGPD
  - Interface dédiée à la portabilité
- **Archivage des données dans une base séparée**
  - Transfert vers `gsbextranetArchive` pour conservation légale
  - Suppression de la base active
- **Suppression définitive des données**
  - Effacement complet et irréversible des données
  - Confirmation requise pour éviter les suppressions accidentelles

## Rôles utilisateurs

### Utilisateur standard
- S'inscrire et se connecter à l'application
- S'inscrire à des visioconférences
- Consulter les produits et leurs informations
- Gérer ses données personnelles
- Exercer ses droits RGPD (portabilité, suppression)

### Chef de produit
- Toutes les fonctionnalités d'un utilisateur standard
- Gestion des produits (ajout, modification, suppression)
- Accès à des statistiques basiques d'utilisation

### Administrateur
- Toutes les fonctionnalités des autres rôles
- Gestion des visioconférences
- Consultation et analyse des logs d'opérations
- Activation/désactivation du mode maintenance
- Accès aux statistiques système détaillées
- Gestion des utilisateurs

## User Stories

### Utilisateur standard

1. **En tant qu'utilisateur, je veux m'inscrire sur la plateforme afin d'accéder aux services**
   - Remplir un formulaire d'inscription
   - Recevoir un email de confirmation
   - Valider mon compte

2. **En tant qu'utilisateur, je veux me connecter à mon compte avec authentification à deux facteurs pour plus de sécurité**
   - Saisir mon email et mot de passe
   - Recevoir un code de vérification par email
   - Saisir le code pour finaliser la connexion

3. **En tant qu'utilisateur, je veux m'inscrire à une visioconférence pour participer à une session d'information**
   - Consulter la liste des visioconférences disponibles
   - Sélectionner une visioconférence
   - Confirmer mon inscription

4. **En tant qu'utilisateur, je veux télécharger mes données personnelles pour exercer mon droit à la portabilité**
   - Accéder à la section "Télécharger mes données"
   - Confirmer ma demande
   - Recevoir un fichier JSON contenant mes données

5. **En tant qu'utilisateur, je veux modifier mes informations personnelles pour les mettre à jour**
   - Accéder à mon profil
   - Modifier les champs souhaités
   - Enregistrer les modifications

6. **En tant qu'utilisateur, je veux supprimer ou archiver mon compte si je n'utilise plus le service**
   - Accéder aux paramètres du compte
   - Sélectionner l'option de suppression ou d'archivage
   - Confirmer mon choix

### Chef de produit

7. **En tant que chef de produit, je veux ajouter un nouveau produit au catalogue**
   - Accéder à l'interface de gestion des produits
   - Remplir le formulaire d'ajout de produit
   - Télécharger une image du produit
   - Soumettre le formulaire

8. **En tant que chef de produit, je veux modifier les informations d'un produit existant**
   - Sélectionner un produit dans la liste
   - Modifier les champs nécessaires
   - Enregistrer les modifications

9. **En tant que chef de produit, je veux supprimer un produit obsolète du catalogue**
   - Sélectionner un produit dans la liste
   - Confirmer la suppression

### Administrateur

10. **En tant qu'administrateur, je veux activer/désactiver le mode maintenance pour effectuer des mises à jour**
    - Accéder à la section de gestion de maintenance
    - Activer ou désactiver le mode maintenance
    - Confirmer mon choix

11. **En tant qu'administrateur, je veux créer une nouvelle visioconférence**
    - Accéder à l'interface de gestion des visioconférences
    - Remplir le formulaire de création
    - Définir la date, l'heure et l'URL
    - Soumettre le formulaire

12. **En tant qu'administrateur, je veux consulter les logs d'opérations pour surveiller l'activité**
    - Accéder à la section "Logs Opérations"
    - Visualiser la liste des opérations effectuées
    - Filtrer par type d'opération ou par utilisateur si nécessaire

13. **En tant qu'administrateur, je veux modifier ou supprimer une visioconférence existante**
    - Sélectionner une visioconférence dans la liste
    - Modifier les informations ou supprimer la visioconférence
    - Confirmer les changements

## Guide d'utilisation

### Prérequis techniques
- Navigateur web moderne (Chrome, Firefox, Edge, Safari)
- JavaScript activé
- Connexion à Internet stable
- Accès à une boîte email pour l'authentification à deux facteurs

### Installation (pour les développeurs)
1. Cloner le dépôt du projet
2. Configurer la base de données MySQL avec les scripts fournis
3. Modifier les paramètres de connexion dans les fichiers de configuration
4. Installer les dépendances via Composer
5. Configurer le serveur web (Apache/Nginx) pour pointer vers le répertoire du projet

### Connexion et authentification
1. Accédez à la page d'accueil de l'application
2. Saisissez votre adresse email et mot de passe
3. Un code de vérification sera envoyé à votre adresse email
4. Saisissez ce code pour finaliser la connexion

### Navigation dans l'interface
- La barre de navigation en haut de l'écran contient les liens vers les différentes fonctionnalités
- Les options disponibles dépendent de votre rôle (utilisateur standard, chef de produit, administrateur)
- Le menu principal donne accès à toutes les fonctionnalités autorisées pour votre profil

### Gestion des visioconférences
- Pour vous inscrire à une visioconférence, cliquez sur "M'inscrire à une visio" dans la barre de navigation
- Pour gérer les visioconférences (admin uniquement), cliquez sur "Gérer les visioconférences"
- Vous pouvez consulter la liste des visioconférences disponibles et leurs détails
- Pour les administrateurs, des boutons d'ajout, modification et suppression sont disponibles

### Gestion des produits
- Pour consulter les produits, utilisez le menu approprié
- Pour gérer les produits (admin et chef de produit), cliquez sur "Gérer les Produits"
- L'interface de gestion permet d'ajouter, modifier ou supprimer des produits
- Les formulaires guident l'utilisateur pour saisir toutes les informations nécessaires

### Gestion des données personnelles
- Cliquez sur "Télécharger mes données" pour accéder à la page de portabilité
- Vous pouvez y modifier vos informations, télécharger vos données, les archiver ou les supprimer
- Les actions sensibles (suppression, archivage) nécessitent une confirmation explicite

### Mode maintenance (admin uniquement)
- Accédez à "Gestion Maintenance" dans la barre de navigation
- Cochez ou décochez la case pour activer/désactiver le mode maintenance
- Lorsque le mode maintenance est actif, seuls les administrateurs peuvent accéder à l'application
- Une bannière vous informe du statut actuel du mode maintenance

### Dépannage courant
- **Problème de connexion** : Vérifiez que votre email et mot de passe sont corrects
- **Code de vérification non reçu** : Vérifiez vos dossiers spam/indésirables
- **Erreur d'accès** : Vérifiez que vous disposez des droits nécessaires pour l'action tentée
- **Page de maintenance** : Si vous voyez cette page, le site est en maintenance, réessayez plus tard

## Aspects techniques

### Technologies utilisées
- **Backend** : PHP 7.4+
- **Frontend** : HTML5, CSS3, JavaScript, Bootstrap
- **Base de données** : MySQL 5.7+
- **Bibliothèques** : PHPMailer pour l'envoi d'emails
- **Sécurité** : PDO pour les requêtes préparées, password_hash pour le cryptage

### Authentification à deux facteurs
- L'utilisateur se connecte avec son email et son mot de passe
- Un code à 6 chiffres est généré via la fonction `genererCodeVerification()`
- Ce code est stocké temporairement dans la base de données
- Un email contenant le code est envoyé via PHPMailer
- L'utilisateur doit saisir ce code pour compléter la connexion
- Une vérification est effectuée pour valider le code saisi

### Gestion des sessions
- Les sessions sont utilisées pour maintenir l'état de connexion
- Chaque session stocke :
  - ID utilisateur
  - Nom et prénom
  - Rôle (utilisateur, chef de produit, administrateur)
  - Adresse IP (pour la journalisation)
- Les sessions expirent après une période d'inactivité

### Journalisation des opérations
- Toutes les actions importantes sont enregistrées dans la table `logs_operations`
- La méthode `logOperation()` s'occupe de l'enregistrement
- Les informations stockées incluent :
  - ID utilisateur
  - Adresse IP
  - Action effectuée (ex: "ajouter produit", "modifier visio")
  - Date et heure de l'action
- Ces logs sont accessibles pour les administrateurs via l'interface

### Mode maintenance
- Un paramètre dans la table `settings` indique si le site est en maintenance
- La valeur est vérifiée à chaque chargement de page
- Si le mode est activé, les utilisateurs non-administrateurs sont redirigés vers une page de maintenance
- Les administrateurs voient une bannière d'avertissement mais peuvent continuer à utiliser l'application

### Portabilité des données (RGPD)
- Les données utilisateur peuvent être exportées au format JSON
- La méthode `donneinfoPortabilite()` rassemble toutes les données personnelles
- L'archivage déplace les données vers une base de données séparée (`gsbextranetArchive`)
- La méthode `archiverUtilisateur()` s'occupe du transfert des données
- La suppression efface définitivement les données de l'utilisateur via `supprimerUtilisateur()`

### Schéma de base de données
Voici les principales tables du système :

1. **utilisateur**
   - id (PK)
   - nom
   - prenom
   - mail
   - motDePasse
   - telephone
   - dateNaissance
   - dateCreation
   - rpps
   - token
   - codeVerification
   - dateVerification
   - dateConsentement
   - role

2. **visioconference**
   - id (PK)
   - nomVisio
   - objectif
   - url
   - dateVisio
   - image

3. **produits**
   - id (PK)
   - nom
   - objectif
   - information
   - effetIndesirable
   - description
   - prix
   - image

4. **logs_operations**
   - id (PK)
   - idutilisateur (FK)
   - adresse_ip
   - action
   - date

5. **historiqueconnexion**
   - id (PK)
   - idUtilisateur (FK)
   - dateDebutLog
   - dateFinLog

6. **settings**
   - id (PK)
   - maintenance_mode

## Suggestions pour l'épreuve

Voici des idées de fonctionnalités à ajouter qui pourraient être demandées lors de votre épreuve :

1. **Système de notifications**
   - Ajouter des notifications en temps réel pour informer les utilisateurs des nouvelles visioconférences ou produits
   - Envoyer des rappels par email avant une visioconférence
   - Implémentation possible : Système de notification par WebSocket ou API de notification du navigateur

2. **Tableau de bord personnalisé**
   - Créer un tableau de bord qui affiche les statistiques personnelles pour chaque utilisateur
   - Pour les administrateurs, afficher des graphiques sur l'utilisation du système
   - Implémentation possible : Utilisation de Chart.js ou D3.js pour les graphiques

3. **Système de commentaires et évaluations**
   - Permettre aux utilisateurs de laisser des commentaires et des évaluations sur les produits
   - Ajouter une modération des commentaires pour les administrateurs
   - Implémentation possible : Création de tables pour les commentaires et notations

4. **Réservation de produits**
   - Ajouter une fonctionnalité permettant aux utilisateurs de réserver des échantillons de produits
   - Gérer un stock virtuel et des notifications de disponibilité
   - Implémentation possible : Système de réservation avec gestion des stocks

5. **Amélioration de la sécurité**
   - Ajouter une authentification par QR code en plus de l'email
   - Mettre en place un système de détection des tentatives d'intrusion
   - Implémentation possible : Utilisation de bibliothèques comme Google Authenticator

6. **Rapports automatisés**
   - Générer des rapports hebdomadaires ou mensuels sur l'activité du site
   - Permettre l'export de ces rapports en différents formats (PDF, Excel)
   - Implémentation possible : Utilisation de FPDF ou PhpSpreadsheet

7. **Module de formation en ligne**
   - Ajouter une section pour des formations en ligne sur les produits
   - Intégrer un système de quiz et de certification
   - Implémentation possible : Système LMS léger intégré

8. **Internationalisation**
   - Ajouter la prise en charge de plusieurs langues
   - Adapter les formats de date et d'heure selon la localisation
   - Implémentation possible : Utilisation de fichiers de traduction ou de gettext

9. **API pour applications mobiles**
   - Développer une API RESTful pour permettre l'accès depuis des applications mobiles
   - Documenter l'API pour les développeurs externes
   - Implémentation possible : API REST avec authentification par token

10. **Amélioration de l'accessibilité**
    - Rendre l'interface conforme aux normes WCAG
    - Ajouter des options d'affichage pour les personnes malvoyantes
    - Implémentation possible : Suivi des guidelines WCAG 2.1

Ces suggestions couvrent différents aspects du développement web et pourraient vous permettre de démontrer vos compétences dans divers domaines.