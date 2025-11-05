-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mer. 05 nov. 2025 à 15:13
-- Version du serveur : 9.1.0
-- Version de PHP : 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `dogsitter`
--

-- --------------------------------------------------------

--
-- Structure de la table `dog_annonce`
--

DROP TABLE IF EXISTS `dog_annonce`;
CREATE TABLE IF NOT EXISTS `dog_annonce` (
  `id_annonce` int NOT NULL,
  `datePromenade` varchar(50) NOT NULL,
  `horaire` varchar(50) NOT NULL,
  `status` varchar(50) DEFAULT NULL,
  `tarif` varchar(50) DEFAULT NULL,
  `description` varchar(50) DEFAULT NULL,
  `endroitPromenade` varchar(50) DEFAULT NULL,
  `duree` int NOT NULL,
  `id_utilisateur` int DEFAULT NULL,
  PRIMARY KEY (`id_annonce`),
  KEY `id_utilisateur` (`id_utilisateur`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `dog_annonce`
--

INSERT INTO `dog_annonce` (`id_annonce`, `datePromenade`, `horaire`, `status`, `tarif`, `description`, `endroitPromenade`, `duree`, `id_utilisateur`) VALUES
(1, '2025-11-10', '10:00', 'Disponible', '20', 'Promenade matinale dans le parc', 'Parc du Bois', 1, 1),
(2, '2025-11-12', '15:00', 'Annulée', '15', 'Promenade en forêt', 'Forêt de Fontainebleau', 2, 2);

-- --------------------------------------------------------

--
-- Structure de la table `dog_avis`
--

DROP TABLE IF EXISTS `dog_avis`;
CREATE TABLE IF NOT EXISTS `dog_avis` (
  `id_avis` int NOT NULL,
  `note` varchar(50) NOT NULL,
  `texte_commentaire` varchar(50) DEFAULT NULL,
  `id_utilisateur` int DEFAULT NULL,
  `id_promenade` int DEFAULT NULL,
  `id_utilisateur_1` int DEFAULT NULL,
  PRIMARY KEY (`id_avis`),
  KEY `id_utilisateur` (`id_utilisateur`),
  KEY `id_promenade` (`id_promenade`),
  KEY `id_utilisateur_1` (`id_utilisateur_1`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `dog_avis`
--

INSERT INTO `dog_avis` (`id_avis`, `note`, `texte_commentaire`, `id_utilisateur`, `id_promenade`, `id_utilisateur_1`) VALUES
(1, '5', 'Super promenade !', 1, 1, 2),
(2, '4', 'Bien mais trop courte.', 2, 2, 1);

-- --------------------------------------------------------

--
-- Structure de la table `dog_chien`
--

DROP TABLE IF EXISTS `dog_chien`;
CREATE TABLE IF NOT EXISTS `dog_chien` (
  `id_chien` int NOT NULL,
  `nom_chien` varchar(50) NOT NULL,
  `poids` varchar(50) DEFAULT NULL,
  `taille` varchar(50) DEFAULT NULL,
  `race` varchar(50) DEFAULT NULL,
  `cheminPhoto` varchar(50) DEFAULT NULL,
  `id_utilisateur` int DEFAULT NULL,
  PRIMARY KEY (`id_chien`),
  KEY `id_utilisateur` (`id_utilisateur`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `dog_chien`
--

INSERT INTO `dog_chien` (`id_chien`, `nom_chien`, `poids`, `taille`, `race`, `cheminPhoto`, `id_utilisateur`) VALUES
(1, 'Rex', '30kg', '60cm', 'Berger Allemand', '/images/rex.jpg', 1),
(2, 'Bella', '25kg', '55cm', 'Labrador', '/images/bella.jpg', 2);

-- --------------------------------------------------------

--
-- Structure de la table `dog_concerne`
--

DROP TABLE IF EXISTS `dog_concerne`;
CREATE TABLE IF NOT EXISTS `dog_concerne` (
  `id_chien` int NOT NULL,
  `id_annonce` int NOT NULL,
  PRIMARY KEY (`id_chien`,`id_annonce`),
  KEY `id_annonce` (`id_annonce`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `dog_concerne`
--

INSERT INTO `dog_concerne` (`id_chien`, `id_annonce`) VALUES
(1, 1),
(2, 2);

-- --------------------------------------------------------

--
-- Structure de la table `dog_conversation`
--

DROP TABLE IF EXISTS `dog_conversation`;
CREATE TABLE IF NOT EXISTS `dog_conversation` (
  `id_conversation` int NOT NULL,
  `date_creation` varchar(50) NOT NULL,
  PRIMARY KEY (`id_conversation`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `dog_conversation`
--

INSERT INTO `dog_conversation` (`id_conversation`, `date_creation`) VALUES
(1, '2025-11-01'),
(2, '2025-11-02');

-- --------------------------------------------------------

--
-- Structure de la table `dog_creer`
--

DROP TABLE IF EXISTS `dog_creer`;
CREATE TABLE IF NOT EXISTS `dog_creer` (
  `id_utilisateur` int NOT NULL,
  `id_conversation` int NOT NULL,
  PRIMARY KEY (`id_utilisateur`,`id_conversation`),
  KEY `id_conversation` (`id_conversation`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `dog_creer`
--

INSERT INTO `dog_creer` (`id_utilisateur`, `id_conversation`) VALUES
(1, 1),
(2, 2);

-- --------------------------------------------------------

--
-- Structure de la table `dog_message`
--

DROP TABLE IF EXISTS `dog_message`;
CREATE TABLE IF NOT EXISTS `dog_message` (
  `id_message` int NOT NULL,
  `contenu` varchar(500) NOT NULL,
  `DateHeureMessage` datetime NOT NULL,
  `id_utilisateur` int DEFAULT NULL,
  `id_conversation` int DEFAULT NULL,
  PRIMARY KEY (`id_message`),
  KEY `id_utilisateur` (`id_utilisateur`),
  KEY `id_conversation` (`id_conversation`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `dog_message`
--

INSERT INTO `dog_message` (`id_message`, `contenu`, `DateHeureMessage`, `id_utilisateur`, `id_conversation`) VALUES
(1, 'Bonjour, je suis intéressé par la promenade de demain.', '2025-11-01 10:00:00', 1, 1),
(2, 'D’accord, je vous confirme pour demain.', '2025-11-01 10:05:00', 2, 1);

-- --------------------------------------------------------

--
-- Structure de la table `dog_participe`
--

DROP TABLE IF EXISTS `dog_participe`;
CREATE TABLE IF NOT EXISTS `dog_participe` (
  `id_chien` int NOT NULL,
  `id_promenade` int NOT NULL,
  PRIMARY KEY (`id_chien`,`id_promenade`),
  KEY `id_promenade` (`id_promenade`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `dog_participe`
--

INSERT INTO `dog_participe` (`id_chien`, `id_promenade`) VALUES
(1, 1),
(2, 2);

-- --------------------------------------------------------

--
-- Structure de la table `dog_promenade`
--

DROP TABLE IF EXISTS `dog_promenade`;
CREATE TABLE IF NOT EXISTS `dog_promenade` (
  `id_promenade` int NOT NULL,
  `statut` varchar(50) NOT NULL,
  PRIMARY KEY (`id_promenade`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `dog_promenade`
--

INSERT INTO `dog_promenade` (`id_promenade`, `statut`) VALUES
(1, 'Active'),
(2, 'Terminé'),
(3, 'En cours');

-- --------------------------------------------------------

--
-- Structure de la table `dog_répond`
--

DROP TABLE IF EXISTS `dog_répond`;
CREATE TABLE IF NOT EXISTS `dog_répond` (
  `id_annonce` int NOT NULL,
  `id_utilisateur` int NOT NULL,
  PRIMARY KEY (`id_annonce`,`id_utilisateur`),
  KEY `id_utilisateur` (`id_utilisateur`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `dog_répond`
--

INSERT INTO `dog_répond` (`id_annonce`, `id_utilisateur`) VALUES
(1, 1),
(2, 2);

-- --------------------------------------------------------

--
-- Structure de la table `dog_utilisateur`
--

DROP TABLE IF EXISTS `dog_utilisateur`;
CREATE TABLE IF NOT EXISTS `dog_utilisateur` (
  `id_utilisateur` int NOT NULL,
  `email` varchar(50) NOT NULL,
  `estMaitre` tinyint(1) NOT NULL,
  `estPromeneur` tinyint(1) NOT NULL,
  `adresse` varchar(50) DEFAULT NULL,
  `motDePasse` varchar(50) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `numTelephone` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_utilisateur`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `dog_utilisateur`
--

INSERT INTO `dog_utilisateur` (`id_utilisateur`, `email`, `estMaitre`, `estPromeneur`, `adresse`, `motDePasse`, `nom`, `prenom`, `numTelephone`) VALUES
(1, 'utilisateur1@example.com', 1, 1, '123 Rue A, Paris', 'motdepasse1', 'Dupont', 'Jean', '0123456789'),
(2, 'utilisateur2@example.com', 0, 1, '456 Rue B, Lyon', 'motdepasse2', 'Martin', 'Sophie', '0987654321'),
(3, 'utilisateur3@example.com', 1, 0, '789 Rue C, Marseille', 'motdepasse3', 'Lemoine', 'Paul', '0234567890');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
