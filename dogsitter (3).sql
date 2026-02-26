-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mar. 24 fév. 2026 à 13:12
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

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

CREATE TABLE `dog_annonce` (
  `id_annonce` int(11) NOT NULL,
  `datePromenade` varchar(50) NOT NULL,
  `horaire` varchar(50) NOT NULL,
  `status` varchar(50) DEFAULT NULL,
  `tarif` varchar(50) DEFAULT NULL,
  `description` varchar(500) DEFAULT NULL,
  `endroitPromenade` varchar(50) DEFAULT NULL,
  `duree` int(11) NOT NULL,
  `id_utilisateur` int(11) DEFAULT NULL,
  `id_promeneur` int(11) DEFAULT NULL,
  `statut_promenade` varchar(50) DEFAULT NULL,
  `titre` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `dog_annonce`
--

INSERT INTO `dog_annonce` (`id_annonce`, `datePromenade`, `horaire`, `status`, `tarif`, `description`, `endroitPromenade`, `duree`, `id_utilisateur`, `id_promeneur`, `statut_promenade`, `titre`) VALUES
(27, '2026-02-20', '20:00', 'Indisponible', '20', '', '', 60, 7, NULL, 'a_venir', 'je test des trucs'),
(28, '2026-02-23', '17:30', 'Indisponible', '20', '', '', 60, 5, NULL, NULL, 'Test Annonce/Promenade'),
(29, '2026-02-21', '20:00', 'Indisponible', '20', '', '', 60, 5, 4, 'archivee', 'Test Annonce/Promenade'),
(30, '2026-02-23', '20:00', 'Indisponible', '19.98', '', 'Mouguerre, skate parc', 30, 5, 4, 'archivee', 'test  js corrigé '),
(33, '2026-02-24', '00:01', 'active', '19.99', '', 'Mouguerre, skate parc', 60, 5, NULL, NULL, 'Avec peyooooo'),
(35, '2026-02-24', '20:00', 'Indisponible', '19.97', '', '', 60, 4, 5, 'a_venir', 'Promenade de Loukia'),
(36, '2026-02-24', '00:00', 'archivee', '100', '', '', 30, 5, 4, 'a_venir', 'Promenade de mon CHIEN BRICE');

-- --------------------------------------------------------

--
-- Structure de la table `dog_avis`
--

CREATE TABLE `dog_avis` (
  `id_avis` int(11) NOT NULL,
  `note` varchar(50) NOT NULL,
  `texte_commentaire` varchar(50) DEFAULT NULL,
  `id_utilisateur` int(11) DEFAULT NULL,
  `id_annonce` int(11) DEFAULT NULL,
  `id_utilisateur_note` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `dog_avis`
--

INSERT INTO `dog_avis` (`id_avis`, `note`, `texte_commentaire`, `id_utilisateur`, `id_annonce`, `id_utilisateur_note`) VALUES
(8, '4', 'Super ! Mon chien est ravis ', 5, 36, 2),
(9, '2', 'cekfnroknbotbno', 5, 36, 2),
(10, '3', 'test avis ', 5, 36, 4);

-- --------------------------------------------------------

--
-- Structure de la table `dog_chien`
--

CREATE TABLE `dog_chien` (
  `id_chien` int(11) NOT NULL,
  `nom_chien` varchar(50) NOT NULL,
  `poids` varchar(50) DEFAULT NULL,
  `taille` varchar(50) DEFAULT NULL,
  `race` varchar(50) DEFAULT NULL,
  `cheminPhoto` varchar(50) DEFAULT NULL,
  `id_utilisateur` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `dog_chien`
--

INSERT INTO `dog_chien` (`id_chien`, `nom_chien`, `poids`, `taille`, `race`, `cheminPhoto`, `id_utilisateur`) VALUES
(13, 'Loukia', '35', 'Grand', 'Golden Retriever', '', 7),
(14, 'Loukia', '35', 'Grand', 'Golden Retriver', '', 5),
(15, 'Karim', '2.8', 'Très petit', 'Arabe', 'chien_5_1771843815.jpg', 5),
(17, 'Loukia', '15', 'Moyen', 'Golden Retrievere', '', 4),
(18, 'Brice', '90', 'Très grand', 'Batard', '', 5);

-- --------------------------------------------------------

--
-- Structure de la table `dog_concerne`
--

CREATE TABLE `dog_concerne` (
  `id_chien` int(11) NOT NULL,
  `id_annonce` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `dog_concerne`
--

INSERT INTO `dog_concerne` (`id_chien`, `id_annonce`) VALUES
(13, 27),
(14, 28),
(14, 29),
(14, 30),
(14, 33),
(15, 33),
(17, 35),
(18, 36);

-- --------------------------------------------------------

--
-- Structure de la table `dog_conversation`
--

CREATE TABLE `dog_conversation` (
  `id_conversation` int(11) NOT NULL,
  `date_creation` varchar(50) NOT NULL,
  `titre` varchar(80) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `dog_conversation`
--

INSERT INTO `dog_conversation` (`id_conversation`, `date_creation`, `titre`) VALUES
(7, '2026-02-18 16:36:56', NULL),
(8, '2026-02-19 16:07:14', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `dog_creer`
--

CREATE TABLE `dog_creer` (
  `id_utilisateur` int(11) NOT NULL,
  `id_conversation` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `dog_creer`
--

INSERT INTO `dog_creer` (`id_utilisateur`, `id_conversation`) VALUES
(4, 7),
(5, 7),
(5, 8),
(7, 8);

-- --------------------------------------------------------

--
-- Structure de la table `dog_message`
--

CREATE TABLE `dog_message` (
  `id_message` int(11) NOT NULL,
  `contenu` varchar(500) NOT NULL,
  `DateHeureMessage` datetime NOT NULL,
  `id_utilisateur` int(11) DEFAULT NULL,
  `id_conversation` int(11) DEFAULT NULL,
  `est_modifie` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `dog_message`
--

INSERT INTO `dog_message` (`id_message`, `contenu`, `DateHeureMessage`, `id_utilisateur`, `id_conversation`, `est_modifie`) VALUES
(24, 'test notifications', '2026-02-19 11:39:35', 5, 7, 0),
(25, 'retest notif', '2026-02-19 11:44:45', 4, 7, 0),
(26, 'notif', '2026-02-19 11:46:35', 5, 7, 0),
(27, 'notif', '2026-02-19 11:49:38', 4, 7, 0),
(28, 're test', '2026-02-19 11:51:40', 5, 7, 0),
(29, 'Bonjour ! J\'ai accepté votre candidature pour promener mon chien. Discutons ensemble des détails de la promenade (date, horaire, lieu de rendez-vous, instructions particulières, etc.). Au plaisir d\'échanger avec vous ! Voir l\'annonce: index.php?controleur=annonce&methode=afficherAnnonce&id_annonce=23', '2026-02-19 12:17:12', 4, 7, 0),
(30, 'Bonjour ! J\'ai accepté votre candidature pour promener mon chien. Discutons ensemble des détails de la promenade (date, horaire, lieu de rendez-vous, instructions particulières, etc.). Au plaisir d\'échanger avec vous ! Voir l\'annonce: index.php?controleur=annonce&methode=afficherAnnonce&id_annonce=25', '2026-02-19 15:15:59', 4, 7, 0),
(31, 'tg', '2026-02-19 15:16:34', 5, 7, 0),
(32, 'toi tg', '2026-02-19 15:38:45', 5, 7, 0),
(33, 'chut', '2026-02-19 15:42:12', 4, 7, 0),
(34, 'chut', '2026-02-19 15:44:42', 5, 7, 0),
(35, 'chut', '2026-02-19 15:47:17', 4, 7, 0),
(36, 'tg', '2026-02-19 15:48:53', 5, 7, 0),
(37, 'Bonjour ! J\'ai accepté votre candidature pour promener mon chien. Discutons ensemble des détails de la promenade (date, horaire, lieu de rendez-vous, instructions particulières, etc.). Au plaisir d\'échanger avec vous ! Voir l\'annonce: index.php?controleur=annonce&methode=afficherAnnonce&id_annonce=26', '2026-02-19 16:08:08', 7, 8, 0),
(38, 'Bonjour ! J\'ai accepté votre candidature pour promener mon chien. Discutons ensemble des détails de la promenade (date, horaire, lieu de rendez-vous, instructions particulières, etc.). Au plaisir d\'échanger avec vous ! Voir l\'annonce: index.php?controleur=annonce&methode=afficherAnnonce&id_annonce=27', '2026-02-19 16:20:24', 7, 8, 0),
(39, 'comment ca se fais que ca marche', '2026-02-19 16:52:20', 5, 8, 0),
(40, 'Bonjour ! J\'ai accepté votre candidature pour promener mon chien. Discutons ensemble des détails de la promenade (date, horaire, lieu de rendez-vous, instructions particulières, etc.). Au plaisir d\'échanger avec vous ! Voir l\'annonce: index.php?controleur=annonce&methode=afficherAnnonce&id_annonce=29', '2026-02-20 12:00:54', 5, 7, 0),
(41, 'on va tester', '2026-02-22 13:12:37', 4, 7, 1),
(42, 'Bonjour ! J\'ai accepté votre candidature pour promener mon chien. Discutons ensemble des détails de la promenade (date, horaire, lieu de rendez-vous, instructions particulières, etc.). Au plaisir d\'échanger avec vous ! Voir l\'annonce: index.php?controleur=annonce&methode=afficherAnnonce&id_annonce=30', '2026-02-22 13:16:34', 5, 7, 0),
(43, 'ftnn', '2026-02-23 14:19:05', 4, 7, 0),
(44, 'Bonjour ! J\'ai accepté votre candidature pour promener mon chien. Discutons ensemble des détails de la promenade (date, horaire, lieu de rendez-vous, instructions particulières, etc.). Au plaisir d\'échanger avec vous ! Voir l\'annonce: index.php?controleur=annonce&methode=afficherAnnonce&id_annonce=35', '2026-02-23 18:16:07', 4, 7, 0),
(45, 'Bonjour ! J\'ai accepté votre candidature pour promener mon chien. Discutons ensemble des détails de la promenade (date, horaire, lieu de rendez-vous, instructions particulières, etc.). Au plaisir d\'échanger avec vous ! Voir l\'annonce: index.php?controleur=annonce&methode=afficherAnnonce&id_annonce=36', '2026-02-23 21:46:59', 5, 7, 0);

-- --------------------------------------------------------

--
-- Structure de la table `dog_notification`
--

CREATE TABLE `dog_notification` (
  `id_notification` int(11) NOT NULL,
  `id_utilisateur` int(11) NOT NULL,
  `titre` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `type` enum('candidature_soumise','candidature_reçue','candidature_acceptée','candidature_refusée','info') DEFAULT 'info',
  `id_annonce` int(11) DEFAULT NULL,
  `id_reponse` int(11) DEFAULT NULL,
  `id_promeneur` int(11) DEFAULT NULL,
  `lue` tinyint(1) DEFAULT 0,
  `date_creation` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `dog_notification`
--

INSERT INTO `dog_notification` (`id_notification`, `id_utilisateur`, `titre`, `message`, `type`, `id_annonce`, `id_reponse`, `id_promeneur`, `lue`, `date_creation`) VALUES
(80, 5, 'Nouveau message', 'Vous avez reçu un nouveau message de Victor', '', NULL, NULL, 4, 1, '2026-02-18 15:24:05'),
(84, 5, 'Nouveau message', 'Victor : retest notif', '', NULL, NULL, 4, 1, '2026-02-19 10:44:45'),
(86, 5, 'Nouveau message', 'Victor : notif', '', NULL, NULL, 4, 1, '2026-02-19 10:49:38'),
(107, 4, 'Nouveau message', 'Vous avez reçu un nouveau message de Noa ', '', NULL, NULL, 5, 1, '2026-02-19 14:16:34'),
(108, 4, 'Nouveau message', 'Vous avez reçu un nouveau message de Noa ', '', NULL, NULL, 5, 1, '2026-02-19 14:38:45'),
(109, 5, 'Nouveau message', 'Vous avez reçu un nouveau message de Victor', '', NULL, NULL, 4, 1, '2026-02-19 14:42:12'),
(110, 4, 'Nouveau message', 'Vous avez reçu un nouveau message de Noa ', '', NULL, NULL, 5, 1, '2026-02-19 14:44:42'),
(111, 5, 'Nouveau message', 'Vous avez reçu un nouveau message de Victor', '', NULL, NULL, 4, 1, '2026-02-19 14:47:17'),
(112, 4, 'Nouveau message', 'Vous avez reçu un nouveau message de Noa ', '', NULL, NULL, 5, 1, '2026-02-19 14:48:53'),
(116, 5, 'Candidature soumise', 'Votre candidature pour l\'annonce \"je test des trucs\" a été enregistrée avec succès. Le maître sera notifié et examinera votre candidature.', 'candidature_soumise', 27, 32, NULL, 1, '2026-02-19 15:19:49'),
(117, 7, 'Nouvelle candidature reçue', 'Noa  a postulé pour votre annonce \"je test des trucs\".', 'candidature_reçue', 27, 32, 5, 1, '2026-02-19 15:19:49'),
(118, 5, 'Candidature acceptée', 'Votre candidature pour l\'annonce \"je test des trucs\" a été acceptée. Une conversation a été créée pour discuter des détails de la promenade. Consultez vos messages.', 'candidature_acceptée', 27, 32, 5, 1, '2026-02-19 15:20:24'),
(119, 7, 'Nouveau message', 'Vous avez reçu un nouveau message de Noa ', '', NULL, NULL, 5, 1, '2026-02-19 15:52:20'),
(120, 4, 'Candidature soumise', 'Votre candidature pour l\'annonce \"Test Annonce/Promenade\" a été enregistrée avec succès. Le maître sera notifié et examinera votre candidature.', 'candidature_soumise', 28, 33, NULL, 1, '2026-02-20 10:54:33'),
(121, 5, 'Nouvelle candidature reçue', 'Victor a postulé pour votre annonce \"Test Annonce/Promenade\".', 'candidature_reçue', 28, 33, 4, 1, '2026-02-20 10:54:33'),
(122, 4, 'Candidature soumise', 'Votre candidature pour l\'annonce \"Test Annonce/Promenade\" a été enregistrée avec succès. Le maître sera notifié et examinera votre candidature.', 'candidature_soumise', 29, 34, NULL, 1, '2026-02-20 11:00:22'),
(123, 5, 'Nouvelle candidature reçue', 'Victor a postulé pour votre annonce \"Test Annonce/Promenade\".', 'candidature_reçue', 29, 34, 4, 1, '2026-02-20 11:00:22'),
(124, 4, 'Candidature acceptée', 'Votre candidature pour l\'annonce \"Test Annonce/Promenade\" a été acceptée. Une conversation a été créée pour discuter des détails de la promenade. Consultez vos messages.', 'candidature_acceptée', 29, 34, 4, 1, '2026-02-20 11:00:54'),
(125, 5, 'Nouveau message', 'Vous avez reçu un nouveau message de Victor', '', NULL, NULL, 4, 1, '2026-02-22 12:12:37'),
(126, 4, 'Candidature soumise', 'Votre candidature pour l\'annonce \"test  js corrigé \" a été enregistrée avec succès. Le maître sera notifié et examinera votre candidature.', 'candidature_soumise', 30, 35, NULL, 1, '2026-02-22 12:15:23'),
(127, 5, 'Nouvelle candidature reçue', 'Victor a postulé pour votre annonce \"test  js corrigé \".', 'candidature_reçue', 30, 35, 4, 1, '2026-02-22 12:15:23'),
(128, 4, 'Candidature acceptée', 'Votre candidature pour l\'annonce \"test  js corrigé \" a été acceptée. Une conversation a été créée pour discuter des détails de la promenade. Consultez vos messages.', 'candidature_acceptée', 30, 35, 4, 1, '2026-02-22 12:16:34'),
(133, 5, 'Nouveau message', 'Vous avez reçu un nouveau message de Victor le bdg', '', NULL, NULL, 4, 1, '2026-02-23 13:19:05'),
(136, 5, 'Candidature soumise', 'Votre candidature pour l\'annonce \"Promenade de Loukia\" a été enregistrée avec succès. Le maître sera notifié et examinera votre candidature.', 'candidature_soumise', 35, 39, NULL, 1, '2026-02-23 17:15:12'),
(137, 4, 'Nouvelle candidature reçue', 'Noa  a postulé pour votre annonce \"Promenade de Loukia\".', 'candidature_reçue', 35, 39, 5, 1, '2026-02-23 17:15:12'),
(138, 5, 'Candidature acceptée', 'Votre candidature pour l\'annonce \"Promenade de Loukia\" a été acceptée. Une conversation a été créée pour discuter des détails de la promenade. Consultez vos messages.', 'candidature_acceptée', 35, 39, 5, 1, '2026-02-23 17:16:07'),
(139, 4, 'Candidature soumise', 'Votre candidature pour l\'annonce \"Promenade de mon CHIEN BRICE\" a été enregistrée avec succès. Le maître sera notifié et examinera votre candidature.', 'candidature_soumise', 36, 40, NULL, 1, '2026-02-23 20:21:58'),
(140, 5, 'Nouvelle candidature reçue', 'Victor le bdg a postulé pour votre annonce \"Promenade de mon CHIEN BRICE\".', 'candidature_reçue', 36, 40, 4, 1, '2026-02-23 20:21:58'),
(141, 4, 'Candidature acceptée', 'Votre candidature pour l\'annonce \"Promenade de mon CHIEN BRICE\" a été acceptée. Une conversation a été créée pour discuter des détails de la promenade. Consultez vos messages.', 'candidature_acceptée', 36, 40, 4, 1, '2026-02-23 20:46:59'),
(142, 4, 'Annonce modifiée', 'Le maître a modifié l\'horaire dans l\'annonce \"Promenade de mon CHIEN BRICE\".', 'info', 36, NULL, 4, 1, '2026-02-23 20:48:04'),
(145, 4, 'Nouvel avis recu', 'Vous avez recu un nouvel avis pour \"Promenade de mon CHIEN BRICE\". Note: 3/5.', 'info', 36, NULL, 4, 1, '2026-02-23 21:06:55');

-- --------------------------------------------------------

--
-- Structure de la table `dog_repond`
--

CREATE TABLE `dog_repond` (
  `id_reponse` int(11) NOT NULL,
  `id_annonce` int(11) NOT NULL,
  `id_utilisateur` int(11) NOT NULL,
  `statut` enum('en_attente','acceptee','refusee') DEFAULT 'en_attente',
  `date_creation` timestamp NOT NULL DEFAULT current_timestamp(),
  `date_modification` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `dog_repond`
--

INSERT INTO `dog_repond` (`id_reponse`, `id_annonce`, `id_utilisateur`, `statut`, `date_creation`, `date_modification`) VALUES
(32, 27, 5, 'acceptee', '2026-02-19 15:19:49', '2026-02-19 15:20:24'),
(33, 28, 4, 'acceptee', '2026-02-20 10:54:33', '2026-02-20 10:55:01'),
(34, 29, 4, 'acceptee', '2026-02-20 11:00:22', '2026-02-20 11:00:54'),
(35, 30, 4, 'acceptee', '2026-02-22 12:15:23', '2026-02-22 12:16:34'),
(39, 35, 5, 'acceptee', '2026-02-23 17:15:12', '2026-02-23 17:16:07'),
(40, 36, 4, 'acceptee', '2026-02-23 20:21:58', '2026-02-23 20:46:59');

-- --------------------------------------------------------

--
-- Structure de la table `dog_utilisateur`
--

CREATE TABLE `dog_utilisateur` (
  `id_utilisateur` int(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `estMaitre` tinyint(1) NOT NULL,
  `estPromeneur` tinyint(1) NOT NULL,
  `adresse` varchar(50) DEFAULT NULL,
  `motDePasse` varchar(255) DEFAULT NULL,
  `pseudo` varchar(100) NOT NULL,
  `photoProfil` varchar(255) DEFAULT NULL,
  `numTelephone` varchar(50) DEFAULT NULL,
  `tentatives_echouees` int(11) DEFAULT 0,
  `date_dernier_echec_connexion` datetime DEFAULT NULL,
  `statut_compte` enum('actif','desactive') DEFAULT 'actif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `dog_utilisateur`
--

INSERT INTO `dog_utilisateur` (`id_utilisateur`, `email`, `estMaitre`, `estPromeneur`, `adresse`, `motDePasse`, `pseudo`, `photoProfil`, `numTelephone`, `tentatives_echouees`, `date_dernier_echec_connexion`, `statut_compte`) VALUES
(4, 'victor@gmail.com', 1, 1, '118 Chemin de Basoilar', '$2y$10$2BazQnxFFuzIYW.6UL8apuvxh2yRix6eoKv19mL1sryPogRl.91fq', 'Victor le bdg', '4_Victorlebdg.jpg', '0769951623', 0, NULL, 'actif'),
(5, 'noa@gmail.com', 1, 1, '118 Chemin de Basoilar', '$2y$10$ZNIchL/e5TfoBc5G/ROhheKDn.vJ7gzByBDZTzfP5qWYzC8ZXX.Em', 'Noa ', '1771339484_cfc747fcdedd.jpg', '0769951623', 0, NULL, 'actif'),
(6, 'promeneur@gmail.com', 0, 1, '118 Chemin de Basoilar', '$2y$10$CVkBakATKYADteuyQl2dfuyyeTtGbnRyE4x2jxWsuZC78av6ESbSy', 'Promeneur', NULL, '0769951623', 0, NULL, 'actif'),
(7, 'testeur1@gmail.com', 1, 1, '118 Chemin de Basoilar', '$2y$10$5pKALMONzE3vHCVUPwz78eR1L04O6GhUz7RmTBnDtV/NCqZ3zBkRq', 'Testeur1', NULL, '0769951623', 0, NULL, 'actif'),
(8, 'maitre@gmail.com', 1, 0, '118 Chemin de Basoilar', '$2y$10$cYiW/E3UqQ/ztPXFOJdRyuru3UZajs5qfOUCyur57aDl.amnj5SnO', 'Maitre', NULL, '0769951623', 0, NULL, 'actif');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `dog_annonce`
--
ALTER TABLE `dog_annonce`
  ADD PRIMARY KEY (`id_annonce`),
  ADD KEY `id_utilisateur` (`id_utilisateur`),
  ADD KEY `id_promeneur` (`id_promeneur`);

--
-- Index pour la table `dog_avis`
--
ALTER TABLE `dog_avis`
  ADD PRIMARY KEY (`id_avis`),
  ADD KEY `id_utilisateur` (`id_utilisateur`),
  ADD KEY `id_annonce` (`id_annonce`),
  ADD KEY `id_utilisateur_note` (`id_utilisateur_note`);

--
-- Index pour la table `dog_chien`
--
ALTER TABLE `dog_chien`
  ADD PRIMARY KEY (`id_chien`),
  ADD KEY `id_utilisateur` (`id_utilisateur`);

--
-- Index pour la table `dog_concerne`
--
ALTER TABLE `dog_concerne`
  ADD PRIMARY KEY (`id_chien`,`id_annonce`),
  ADD KEY `id_annonce` (`id_annonce`);

--
-- Index pour la table `dog_conversation`
--
ALTER TABLE `dog_conversation`
  ADD PRIMARY KEY (`id_conversation`);

--
-- Index pour la table `dog_creer`
--
ALTER TABLE `dog_creer`
  ADD PRIMARY KEY (`id_utilisateur`,`id_conversation`),
  ADD KEY `id_conversation` (`id_conversation`);

--
-- Index pour la table `dog_message`
--
ALTER TABLE `dog_message`
  ADD PRIMARY KEY (`id_message`),
  ADD KEY `id_utilisateur` (`id_utilisateur`),
  ADD KEY `id_conversation` (`id_conversation`);

--
-- Index pour la table `dog_notification`
--
ALTER TABLE `dog_notification`
  ADD PRIMARY KEY (`id_notification`),
  ADD KEY `idx_utilisateur` (`id_utilisateur`),
  ADD KEY `idx_non_lue` (`lue`),
  ADD KEY `fk_notif_annonce` (`id_annonce`),
  ADD KEY `fk_notif_reponse` (`id_reponse`),
  ADD KEY `fk_notif_promeneur` (`id_promeneur`);

--
-- Index pour la table `dog_repond`
--
ALTER TABLE `dog_repond`
  ADD PRIMARY KEY (`id_reponse`),
  ADD UNIQUE KEY `unique_reponse` (`id_annonce`,`id_utilisateur`),
  ADD KEY `fk_repond_user` (`id_utilisateur`);

--
-- Index pour la table `dog_utilisateur`
--
ALTER TABLE `dog_utilisateur`
  ADD PRIMARY KEY (`id_utilisateur`),
  ADD UNIQUE KEY `unique_email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `dog_annonce`
--
ALTER TABLE `dog_annonce`
  MODIFY `id_annonce` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT pour la table `dog_avis`
--
ALTER TABLE `dog_avis`
  MODIFY `id_avis` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `dog_chien`
--
ALTER TABLE `dog_chien`
  MODIFY `id_chien` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT pour la table `dog_conversation`
--
ALTER TABLE `dog_conversation`
  MODIFY `id_conversation` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `dog_message`
--
ALTER TABLE `dog_message`
  MODIFY `id_message` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT pour la table `dog_notification`
--
ALTER TABLE `dog_notification`
  MODIFY `id_notification` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=146;

--
-- AUTO_INCREMENT pour la table `dog_repond`
--
ALTER TABLE `dog_repond`
  MODIFY `id_reponse` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT pour la table `dog_utilisateur`
--
ALTER TABLE `dog_utilisateur`
  MODIFY `id_utilisateur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `dog_annonce`
--
ALTER TABLE `dog_annonce`
  ADD CONSTRAINT `fk_annonce_promeneur` FOREIGN KEY (`id_promeneur`) REFERENCES `dog_utilisateur` (`id_utilisateur`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_annonce_utilisateur` FOREIGN KEY (`id_utilisateur`) REFERENCES `dog_utilisateur` (`id_utilisateur`) ON DELETE SET NULL;

--
-- Contraintes pour la table `dog_avis`
--
ALTER TABLE `dog_avis`
  ADD CONSTRAINT `fk_avis_annonce` FOREIGN KEY (`id_annonce`) REFERENCES `dog_annonce` (`id_annonce`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_avis_user` FOREIGN KEY (`id_utilisateur`) REFERENCES `dog_utilisateur` (`id_utilisateur`) ON DELETE CASCADE;

--
-- Contraintes pour la table `dog_chien`
--
ALTER TABLE `dog_chien`
  ADD CONSTRAINT `fk_chien_utilisateur` FOREIGN KEY (`id_utilisateur`) REFERENCES `dog_utilisateur` (`id_utilisateur`) ON DELETE CASCADE;

--
-- Contraintes pour la table `dog_concerne`
--
ALTER TABLE `dog_concerne`
  ADD CONSTRAINT `fk_concerne_annonce` FOREIGN KEY (`id_annonce`) REFERENCES `dog_annonce` (`id_annonce`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_concerne_chien` FOREIGN KEY (`id_chien`) REFERENCES `dog_chien` (`id_chien`) ON DELETE CASCADE;

--
-- Contraintes pour la table `dog_creer`
--
ALTER TABLE `dog_creer`
  ADD CONSTRAINT `fk_creer_conv` FOREIGN KEY (`id_conversation`) REFERENCES `dog_conversation` (`id_conversation`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_creer_user` FOREIGN KEY (`id_utilisateur`) REFERENCES `dog_utilisateur` (`id_utilisateur`) ON DELETE CASCADE;

--
-- Contraintes pour la table `dog_message`
--
ALTER TABLE `dog_message`
  ADD CONSTRAINT `fk_message_conv` FOREIGN KEY (`id_conversation`) REFERENCES `dog_conversation` (`id_conversation`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_message_user` FOREIGN KEY (`id_utilisateur`) REFERENCES `dog_utilisateur` (`id_utilisateur`) ON DELETE CASCADE;

--
-- Contraintes pour la table `dog_notification`
--
ALTER TABLE `dog_notification`
  ADD CONSTRAINT `fk_notif_annonce` FOREIGN KEY (`id_annonce`) REFERENCES `dog_annonce` (`id_annonce`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_notif_promeneur` FOREIGN KEY (`id_promeneur`) REFERENCES `dog_utilisateur` (`id_utilisateur`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_notif_reponse` FOREIGN KEY (`id_reponse`) REFERENCES `dog_repond` (`id_reponse`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_notif_user` FOREIGN KEY (`id_utilisateur`) REFERENCES `dog_utilisateur` (`id_utilisateur`) ON DELETE CASCADE;

--
-- Contraintes pour la table `dog_repond`
--
ALTER TABLE `dog_repond`
  ADD CONSTRAINT `fk_repond_annonce` FOREIGN KEY (`id_annonce`) REFERENCES `dog_annonce` (`id_annonce`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_repond_user` FOREIGN KEY (`id_utilisateur`) REFERENCES `dog_utilisateur` (`id_utilisateur`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
