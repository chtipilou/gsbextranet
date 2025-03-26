
CREATE DATABASE IF NOT EXISTS gsbextranetArchive;
USE gsbextranetArchive;

CREATE TABLE `utilisateur` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) DEFAULT NULL,
  `prenom` varchar(255) DEFAULT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `mail` varchar(255) DEFAULT NULL,
  `dateNaissance` date DEFAULT NULL,
  `dateCreation` datetime DEFAULT NULL,
  `rpps` varchar(255) DEFAULT NULL,
  `token` varchar(255) DEFAULT NULL,
  `dateConsentement` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `historiqueconnexion` (
  `idUtilisateur` int(11) NOT NULL,
  `dateDebutLog` datetime NOT NULL,
  `dateFinLog` datetime DEFAULT NULL,
  PRIMARY KEY (`idUtilisateur`,`dateDebutLog`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `logs_operations` (
  `id` int(11) NOT NULL,
  `idutilisateur` int(11) NOT NULL,
  `adresse_ip` varchar(45) NOT NULL,
  `action` varchar(255) NOT NULL,
  `date` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idutilisateur` (`idutilisateur`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Drop the old utilisateur_archive table
DROP TABLE IF EXISTS `utilisateur_archive`;