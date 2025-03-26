-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 14, 2025 at 07:29 AM
-- Server version: 10.3.39-MariaDB-0+deb10u1
-- PHP Version: 8.2.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gsbextranetAP`
--

-- --------------------------------------------------------

--
-- Table structure for table `consultations`
--

CREATE TABLE `consultations` (
  `id` int(11) NOT NULL,
  `id_produit` int(11) DEFAULT NULL,
  `id_visioconference` int(11) DEFAULT NULL,
  `date_consultation` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `consultations`
--

INSERT INTO `consultations` (`id`, `id_produit`, `id_visioconference`, `date_consultation`) VALUES
(1, 23, 12, '2024-10-19 02:00:02'),
(2, 9, 3, '2024-10-10 02:00:02'),
(3, 9, 1, '2024-09-23 02:00:02'),
(4, 10, 2, '2024-10-05 02:00:02'),
(5, 1, 12, '2024-09-25 02:00:02'),
(6, 6, 4, '2024-10-03 02:00:02'),
(8, 8, 1, '2024-09-28 02:00:02'),
(9, 9, 6, '2024-09-26 02:00:02'),
(10, 1, 7, '2024-09-28 02:00:02'),
(11, 11, 18, '2024-10-11 02:00:02'),
(12, 4, 8, '2024-10-12 02:00:02'),
(13, 13, 9, '2024-10-09 02:00:02'),
(14, 14, 10, '2024-10-17 02:00:02'),
(15, 15, 19, '2024-10-10 02:00:02'),
(16, 16, 11, '2024-10-09 02:00:02'),
(17, 17, 12, '2024-09-28 02:00:02'),
(18, 3, 20, '2024-09-30 02:00:02'),
(19, 24, 13, '2024-10-18 02:00:02'),
(20, 20, 14, '2024-10-09 02:00:02'),
(21, 21, 8, '2024-10-03 02:00:02'),
(22, 12, 15, '2024-09-27 02:00:02'),
(23, 23, 16, '2024-10-16 02:00:02'),
(24, 8, 10, '2024-10-08 02:00:02'),
(25, 6, 17, '2024-10-06 02:00:02'),
(26, 26, 18, '2024-10-16 02:00:02');

-- --------------------------------------------------------

--
-- Table structure for table `historiqueconnexion`
--

CREATE TABLE `historiqueconnexion` (
  `idUtilisateur` int(11) NOT NULL,
  `dateDebutLog` datetime NOT NULL,
  `dateFinLog` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `historiqueconnexion`
--

INSERT INTO `historiqueconnexion` (`idUtilisateur`, `dateDebutLog`, `dateFinLog`) VALUES
(82, '2024-12-13 13:40:52', '2024-12-13 13:49:11'),
(82, '2024-12-13 13:49:45', '2024-12-13 14:09:45'),
(82, '2024-12-13 14:21:51', '2024-12-13 14:24:45'),
(82, '2024-12-18 14:47:15', NULL),
(95, '2024-12-13 14:48:01', NULL),
(95, '2024-12-13 14:48:14', NULL),
(97, '2024-12-16 10:29:00', NULL),
(97, '2024-12-16 10:29:15', '2024-12-16 10:31:30'),
(97, '2024-12-16 10:31:38', NULL),
(98, '2025-03-03 13:00:06', NULL),
(98, '2025-03-03 13:00:17', NULL),
(98, '2025-03-14 08:25:20', '2025-03-14 08:27:09'),
(98, '2025-03-14 08:27:18', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `logs_operations`
--

CREATE TABLE `logs_operations` (
  `id` int(11) NOT NULL,
  `idutilisateur` int(11) NOT NULL,
  `adresse_ip` varchar(45) NOT NULL,
  `action` varchar(255) NOT NULL,
  `date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `logs_operations`
--

INSERT INTO `logs_operations` (`id`, `idutilisateur`, `adresse_ip`, `action`, `date`) VALUES
(35, 82, '185.107.56.238', 'modifier visio', '2024-12-05 14:41:02'),
(36, 82, '185.107.56.238', 'modifier visio', '2024-12-05 14:41:02'),
(37, 82, '185.107.56.238', 'modifier visio', '2024-12-05 14:41:49'),
(38, 82, '185.107.56.238', 'ajouter visio', '2024-12-05 14:42:14'),
(39, 82, '185.107.56.238', 'supprimer visio', '2024-12-05 14:42:20'),
(40, 82, '185.107.56.238', 'modifier produit', '2024-12-05 14:42:34'),
(41, 82, '185.107.56.238', 'ajouter produit', '2024-12-05 14:42:45'),
(42, 82, '185.107.56.238', 'supprimer produit', '2024-12-05 14:42:49'),
(47, 82, '185.182.193.114', 'ajouter produit', '2024-12-13 14:09:00'),
(48, 82, '185.182.193.114', 'supprimer produit', '2024-12-13 14:09:07');

-- --------------------------------------------------------

--
-- Table structure for table `produits`
--

CREATE TABLE `produits` (
  `id` int(11) NOT NULL,
  `nom` varchar(60) NOT NULL,
  `objectif` mediumtext NOT NULL,
  `information` mediumtext NOT NULL,
  `effetIndesirable` mediumtext NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `prix` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `produits`
--

INSERT INTO `produits` (`id`, `nom`, `objectif`, `information`, `effetIndesirable`, `image`, `description`, `prix`) VALUES
(1, 'Produit A', 'Traitement de l\'hypertension', 'Ce produit est utilisé pour réduire la pression artérielle.', 'Peut provoquer des maux de tête.', 'produit_a.jpg', 'Un médicament efficace pour le contrôle de l\'hypertension.', 30.00),
(2, 'Produit B', 'Antibiotique', 'Utilisé pour traiter les infections bactériennes.', 'Allergies possibles.', 'produit_b.jpg', 'Antibiotique à large spectre.', 19.99),
(3, 'Produit C', 'Antidouleur', 'Soulage la douleur modérée à sévère.', 'Nausées et somnolence.', 'produit_c.jpg', 'Efficace contre les douleurs aiguës et chroniques.', 24.99),
(4, 'Produit D', 'Vitamines', 'Complément alimentaire pour renforcer le système immunitaire.', 'Aucun effet indésirable connu.', 'produit_d.jpg', 'Vitamines essentielles pour la santé globale.', 15.99),
(5, 'Produit E', 'Antidépresseur', 'Utilisé pour traiter la dépression.', 'Peut causer des vertiges.', 'logomacromania.png', 'gsdfgfds', 62.00),
(6, 'Doliprane', 'Soulager la douleur et réduire la fièvre', 'Utilisé pour traiter des douleurs légères à modérées', 'Allergies, nausées', 'doliprane.jpg', 'Analgésique courant utilisé pour les maux de tête.', 5.99),
(7, 'Ibuprofène', 'Anti-inflammatoire', 'Efficace contre l\'inflammation et la douleur', 'Troubles gastro-intestinaux', 'ibuprofene.jpg', 'Aide à réduire la douleur et l\'inflammation.', 6.50),
(8, 'Amoxicilline', 'Antibiotique', 'Utilisé pour traiter les infections bactériennes', 'Réactions allergiques', 'amoxicilline.jpg', 'Antibactérien puissant pour diverses infections.', 12.00),
(9, 'Paracétamol', 'Soulager la douleur', 'Fréquemment utilisé contre la douleur légère à modérée', 'Rarement des réactions allergiques', 'paracetamol.jpg', 'Médicament utilisé pour traiter la douleur et la fièvre.', 4.50),
(10, 'Oméprazole', 'Réduire l\'acidité gastrique', 'Utilisé pour traiter les reflux acides', 'Maux de tête, diarrhée', 'omeprazole.jpg', 'Médicament pour le traitement des troubles digestifs.', 8.00),
(11, 'Atorvastatine', 'Réduire le cholestérol', 'Utilisé pour abaisser le taux de cholestérol dans le sang', 'Douleurs musculaires', 'atorvastatine.jpg', 'Aide à contrôler le cholestérol et à protéger le cœur.', 15.00),
(12, 'Loratadine', 'Antihistaminique', 'Utilisé pour soulager les symptômes d\'allergies', 'Somnolence, sécheresse buccale', 'loratadine.jpg', 'Anti-allergique efficace contre les rhinites.', 9.00),
(13, 'Metformine', 'Réguler le diabète de type 2', 'Utilisé pour abaisser le taux de glucose sanguin', 'Troubles gastro-intestinaux', 'metformine.jpg', 'Médicament pour le traitement du diabète.', 10.00),
(14, 'Salbutamol', 'Traitement de l\'asthme', 'Utilisé comme bronchodilatateur', 'Tremblements, palpitations', 'salbutamol.jpg', 'Inhalateur pour soulager les crises d\'asthme.', 7.50),
(15, 'Cetirizine', 'Antihistaminique', 'Utilisé pour traiter les allergies', 'Fatigue, vertiges', 'cetirizine.jpg', 'Efficace pour les démangeaisons et les éruptions cutanées.', 8.50),
(16, 'Levothyroxine', 'Thyroïde', 'Utilisé pour traiter l\'hypothyroïdie', 'Palpitations, perte de poids', 'levothyroxine.jpg', 'Hormone thyroïdienne pour un métabolisme équilibré.', 14.00),
(17, 'Furosemide', 'Diurétique', 'Utilisé pour traiter l\'œdème et l\'hypertension', 'Déséquilibre électrolytique', 'furosemide.jpg', 'Diurétique pour réduire la rétention d\'eau.', 11.00),
(18, 'Clopidogrel', 'Antiplaquettaire', 'Prévention des accidents vasculaires cérébraux', 'Saignements, éruptions cutanées', 'clopidogrel.jpg', 'Médicament pour prévenir les caillots sanguins.', 13.50),
(19, 'Simvastatine', 'Réduire le cholestérol', 'Utilisé pour abaisser le taux de cholestérol dans le sang', 'Douleurs musculaires', 'simvastatine.jpg', 'Aide à réduire les risques cardiovasculaires.', 15.50),
(20, 'Ciprofloxacine', 'Antibiotique', 'Utilisé pour traiter diverses infections bactériennes', 'Diarrhée, vertiges', 'ciprofloxacine.jpg', 'Antibiotique à large spectre pour de nombreuses infections.', 12.50),
(21, 'Ranitidine', 'Réduire l\'acidité gastrique', 'Utilisé pour traiter les ulcères et le reflux acide', 'Maux de tête, constipation', 'ranitidine.jpg', 'Utilisé pour soulager les brûlures d\'estomac.', 9.50),
(22, 'Dextrométhorphane', 'Antitussif', 'Utilisé pour soulager la toux', 'Somnolence, nausées', 'dextromethorphan.jpg', 'Médicament contre la toux sans effets sédatifs.', 7.00),
(23, 'Prednisone', 'Anti-inflammatoire', 'Utilisé pour traiter des affections inflammatoires', 'Augmentation de l\'appétit, rétention d\'eau', 'prednisone.jpg', 'Corticoïde pour des problèmes inflammatoires graves.', 14.50),
(24, 'Propranolol', 'Bêta-bloquant', 'Utilisé pour traiter l\'hypertension et l\'anxiété', 'Fatigue, froideur des extrémités', 'propranolol.jpg', 'Médicament pour gérer le stress et la pression artérielle.', 13.00),
(25, 'Venlafaxine', 'Antidépresseur', 'Utilisé pour traiter la dépression et l\'anxiété', 'Nausées, somnolence', 'venlafaxine.jpg', 'Aide à stabiliser l\'humeur.', 18.00),
(26, 'Diazépam', 'Anxiolytique', 'Utilisé pour traiter l\'anxiété et les troubles du sommeil', 'Somnolence, dépendance possible', 'diazepam.jpg', 'Médicament pour gérer l\'anxiété et l\'agitation.', 10.50),
(27, 'Aspirine', 'Anti-inflammatoire', 'Utilisé pour soulager la douleur et prévenir les caillots', 'Saignements, allergies', 'aspirine.jpg', 'Aide à prévenir les problèmes cardiovasculaires.', 5.50),
(28, 'Fluoxétine', 'Antidépresseur', 'Utilisé pour traiter la dépression', 'Insomnie, nausées', 'fluoxetine.jpg', 'Médicament couramment prescrit pour la dépression.', 11.50),
(29, 'Mirtazapine', 'Antidépresseur', 'Utilisé pour traiter la dépression', 'Somnolence, prise de poids', 'mirtazapine.jpg', 'Aide à améliorer l\'humeur et le sommeil.', 15.00),
(32, 'sgfd', 'fgd', 'fdsg', 'dfgs', 'custom-red-atlas-bg-lockscreen-and-icon-v0-1otk16n6o5vd1.jpg', 'dfsg', 5496.00),
(33, 'bvcxxc', 'xwcvcxv', ';khjl', 'uipoopiu', 'custom-red-atlas-bg-lockscreen-and-icon-v0-1otk16n6o5vd1.jpg', 'uyttruy', 21.00);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `maintenance_mode` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `maintenance_mode`) VALUES
(1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `id` int(11) NOT NULL,
  `nom` varchar(40) DEFAULT NULL,
  `prenom` varchar(30) DEFAULT NULL,
  `telephone` varchar(10) DEFAULT NULL,
  `mail` varchar(50) DEFAULT NULL,
  `dateNaissance` date DEFAULT NULL,
  `motDePasse` varchar(60) DEFAULT NULL,
  `dateCreation` datetime DEFAULT NULL,
  `rpps` varchar(10) DEFAULT NULL,
  `token` varchar(100) DEFAULT NULL,
  `dateConsentement` datetime(6) DEFAULT NULL,
  `codeVerification` int(6) DEFAULT NULL,
  `dateVerification` datetime DEFAULT NULL,
  `role` enum('admin','chef_de_produit','utilisateur') DEFAULT 'utilisateur'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `utilisateur`
--

INSERT INTO `utilisateur` (`id`, `nom`, `prenom`, `telephone`, `mail`, `dateNaissance`, `motDePasse`, `dateCreation`, `rpps`, `token`, `dateConsentement`, `codeVerification`, `dateVerification`, `role`) VALUES
(82, 'fdsqsfdq', 'dsfdqsfdq', '078685709', 'jjggj@g.g', '2000-03-12', '$2y$10$R5CdMMP8PlNjXws/RoLuM.LGFfYbsWcn0akxKON9cgn8.jcDuofki', '2024-11-29 14:32:24', '655dsqdsq', NULL, '2024-11-29 14:32:24.000000', 913891, '2024-12-18 14:47:09', 'admin'),
(95, 'dQSqds', 'dsQdsq', '1234567890', 'dfgsfgdsgfd@gmqsfpij.co', '5464-02-06', '$2y$10$WydL6Wgrl4olRb6kyoQR6O3gMRpHeYQ1kFmld.E8PDvOwv6l1v.Fm', '2024-12-13 14:48:01', '654546', NULL, '2024-12-13 14:48:00.000000', 826912, '2024-12-13 14:48:05', 'utilisateur'),
(97, 'elio', 'boyo', NULL, 'covoit2024@emeple.com', NULL, '$2y$10$sOtqrs4R2X.7gmZVCy4M6eSHd5BNy9yMskCkGI/8zshuaRBOl/j2S', '2024-12-16 10:29:00', NULL, NULL, '2024-12-16 10:28:59.000000', 288433, '2024-12-16 10:31:32', 'admin'),
(98, 'sdfggsfd', 'sdfgdgsfgf', NULL, 'exemple1@exemple.com', NULL, '$2y$10$xIarhZG7NX15tgYtEdWDrO0CYrTiWvuNCspguDYwYHwl/bqM3NBxO', '2025-03-03 13:00:06', NULL, NULL, '2025-03-03 13:00:06.000000', 635480, '2025-03-14 08:27:12', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `utilisateurproduit`
--

CREATE TABLE `utilisateurproduit` (
  `idutilisateur` int(11) NOT NULL,
  `idProduit` int(11) NOT NULL,
  `date` date NOT NULL,
  `heure` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `utilisateurvisio`
--

CREATE TABLE `utilisateurvisio` (
  `idutilisateur` int(11) NOT NULL,
  `idVisio` int(11) NOT NULL,
  `dateInscription` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `visioconference`
--

CREATE TABLE `visioconference` (
  `id` int(11) NOT NULL,
  `nomVisio` varchar(100) DEFAULT NULL,
  `objectif` text DEFAULT NULL,
  `url` varchar(100) DEFAULT NULL,
  `dateVisio` date NOT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `visioconference`
--

INSERT INTO `visioconference` (`id`, `nomVisio`, `objectif`, `url`, `dateVisio`, `image`) VALUES
(1, 'Test', '454784', 'https://google.com', '2025-01-26', 'logomacromania.png'),
(2, 'test2', '544467qds65f4', 'https://google.comf', '1999-12-05', 'logomacromania.png'),
(3, 'Conférence sur la santé', 'Discuter des nouvelles tendances en santé.', 'http://example.com/visio3', '2024-11-01', 'visio_sante.jpg'),
(4, 'Atelier de formation médicale', 'Former les médecins sur les nouvelles pratiques.', 'http://example.com/visio4d', '2024-11-15', 'visio_formation.jpg'),
(5, 'Séminaire sur les médicaments', 'Présenter les derniers médicaments sur le marché.', 'http://example.com/visio5', '2024-11-22', 'visio_medicaments.jpg'),
(6, 'Webinaire sur la nutrition', 'Informer sur les meilleures pratiques en nutrition.', 'http://example.com/visio6', '2024-12-01', 'visio_nutrition.jpg'),
(7, 'Conférence sur la santé mentale', 'Discuter de l\'importance de la santé mentale.', 'http://example.com/visio7', '2024-12-05', 'visio_sante_mentale.jpg'),
(8, 'Table ronde sur l\'innovation médicale', 'Échanger sur les innovations dans le secteur médical.', 'http://example.com/visio8', '2024-12-10', 'visio_innovation.jpg'),
(9, 'Séance de questions-réponses', 'Répondre aux questions des professionnels de santé.', 'http://example.com/visio9', '2024-12-15', 'visio_questions.jpg'),
(10, 'Forum sur les maladies chroniques', 'Aborder la gestion des maladies chroniques.', 'http://example.com/visio10', '2024-12-20', 'visio_maladies_chroniques.jpg'),
(11, 'Webinaire sur la télémédecine', 'Explorer les avantages de la télémédecine.', 'http://example.com/visio11', '2025-01-05', 'visio_telemédecine.jpg'),
(12, 'Conférence sur la recherche clinique', 'Discuter des dernières avancées en recherche clinique.', 'http://example.com/visio12', '2025-01-10', 'visio_recherche_clinique.jpg'),
(13, 'Atelier sur les soins palliatifs', 'Former sur les soins en fin de vie.', 'http://example.com/visio13', '2025-01-15', 'visio_soins_palliatifs.jpg'),
(14, 'Forum sur les droits des patients', 'Échanger sur les droits et la dignité des patients.', 'http://example.com/visio14', '2025-01-20', 'visio_droits_patients.jpg'),
(15, 'Webinaire sur la vaccination', 'Informer sur l\'importance de la vaccination.', 'http://example.com/visio15', '2025-01-25', 'visio_vaccination.jpg'),
(16, 'Séminaire sur les maladies infectieuses', 'Discuter des nouvelles infections et des traitements.', 'http://example.com/visio16', '2025-02-01', 'visio_maladies_infectieuses.jpg'),
(17, 'Conférence sur l\'obésité', 'Aborder les enjeux de l\'obésité dans la société.', 'http://example.com/visio17', '2025-02-05', 'visio_obesite.jpg'),
(18, 'Table ronde sur la santé des femmes', 'Discuter des problèmes de santé spécifiques aux femmes.', 'http://example.com/visio18', '2025-02-10', 'visio_sante_femmes.jpg'),
(19, 'Atelier sur les soins aux personnes âgées', 'Former sur les soins adaptés aux personnes âgées.', 'http://example.com/visio19', '2025-02-15', 'visio_soins_agees.jpg'),
(20, 'Webinaire sur le cancer', 'Informer sur la prévention et le traitement du cancer.', 'http://example.com/visio20', '2025-02-20', 'visio_cancer.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `consultations`
--
ALTER TABLE `consultations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_produit` (`id_produit`),
  ADD KEY `id_visioconference` (`id_visioconference`);

--
-- Indexes for table `historiqueconnexion`
--
ALTER TABLE `historiqueconnexion`
  ADD PRIMARY KEY (`idUtilisateur`,`dateDebutLog`);

--
-- Indexes for table `logs_operations`
--
ALTER TABLE `logs_operations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idutilisateur` (`idutilisateur`);

--
-- Indexes for table `produits`
--
ALTER TABLE `produits`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `utilisateurproduit`
--
ALTER TABLE `utilisateurproduit`
  ADD PRIMARY KEY (`idutilisateur`,`idProduit`,`date`,`heure`),
  ADD KEY `fk_utilisateurproduit_produit` (`idProduit`);

--
-- Indexes for table `utilisateurvisio`
--
ALTER TABLE `utilisateurvisio`
  ADD PRIMARY KEY (`idutilisateur`,`idVisio`),
  ADD KEY `fk_utilisateurvisio_visio` (`idVisio`);

--
-- Indexes for table `visioconference`
--
ALTER TABLE `visioconference`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `consultations`
--
ALTER TABLE `consultations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `logs_operations`
--
ALTER TABLE `logs_operations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `produits`
--
ALTER TABLE `produits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=99;

--
-- AUTO_INCREMENT for table `visioconference`
--
ALTER TABLE `visioconference`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `consultations`
--
ALTER TABLE `consultations`
  ADD CONSTRAINT `consultations_ibfk_1` FOREIGN KEY (`id_produit`) REFERENCES `produits` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `consultations_ibfk_2` FOREIGN KEY (`id_visioconference`) REFERENCES `visioconference` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `historiqueconnexion`
--
ALTER TABLE `historiqueconnexion`
  ADD CONSTRAINT `fk_utilisateur_historiqueconnexion` FOREIGN KEY (`idUtilisateur`) REFERENCES `utilisateur` (`id`);

--
-- Constraints for table `logs_operations`
--
ALTER TABLE `logs_operations`
  ADD CONSTRAINT `logs_operations_ibfk_1` FOREIGN KEY (`idutilisateur`) REFERENCES `utilisateur` (`id`);

--
-- Constraints for table `utilisateurproduit`
--
ALTER TABLE `utilisateurproduit`
  ADD CONSTRAINT `fk_utilisateurproduit_produit` FOREIGN KEY (`idProduit`) REFERENCES `produits` (`id`),
  ADD CONSTRAINT `fk_utilisateurproduit_utilisateur` FOREIGN KEY (`idUtilisateur`) REFERENCES `utilisateur` (`id`);

--
-- Constraints for table `utilisateurvisio`
--
ALTER TABLE `utilisateurvisio`
  ADD CONSTRAINT `fk_utilisateurvisio_utilisateur` FOREIGN KEY (`idUtilisateur`) REFERENCES `utilisateur` (`id`),
  ADD CONSTRAINT `fk_utilisateurvisio_visio` FOREIGN KEY (`idVisio`) REFERENCES `visioconference` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
