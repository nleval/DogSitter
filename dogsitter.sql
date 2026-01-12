-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : mysql-5.7
-- Généré le : lun. 12 jan. 2026 à 07:54
-- Version du serveur : 5.7.28
-- Version de PHP : 8.0.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `apigeon001_pro`
--

-- --------------------------------------------------------

--
-- Structure de la table `dog_Annonce`
--

CREATE TABLE `dog_Annonce` (
  `id_annonce` int(11) NOT NULL,
  `datePromenade` varchar(50) NOT NULL,
  `horaire` varchar(50) NOT NULL,
  `status` varchar(50) DEFAULT NULL,
  `tarif` varchar(50) DEFAULT NULL,
  `description` varchar(500) DEFAULT NULL,
  `endroitPromenade` varchar(50) DEFAULT NULL,
  `duree` int(11) NOT NULL,
  `id_utilisateur` int(11) DEFAULT NULL,
  `titre` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `dog_Annonce`
--

INSERT INTO `dog_Annonce` (`id_annonce`, `datePromenade`, `horaire`, `status`, `tarif`, `description`, `endroitPromenade`, `duree`, `id_utilisateur`, `titre`) VALUES
(1, '2025-12-01', '09:00', 'Disponible', '20', 'Promenade matinale au parc', 'Parc Montsouris', 1, 1, 'Balade Parc Montsouris'),
(2, '2025-12-02', '14:30', 'Disponible', '18', 'Balade tranquille autour du lac', 'Lac Daumesnil', 1, 2, 'Balade Lac Daumesnil'),
(3, '2025-12-03', '11:00', 'Disponible', '25', 'Sortie sportive pour chien énergique', 'Bois de Vincennes', 2, 3, 'Sortie sportive Bois de Vincennes'),
(4, '2025-12-04', '16:00', 'Disponible', '22', 'Promenade détente en zone ombragée', 'Parc de Sceaux', 1, 4, 'Promenade Parc de Sceaux'),
(5, '2025-12-05', '08:30', 'Disponible', '30', 'Longue balade pour chiens actifs', 'Forêt de Meudon', 3, 5, 'Longue balade Forêt Meudon'),
(6, '2025-12-06', '15:00', 'Disponible', '19', 'Sortie idéale pour chiens sociables', 'Jardin des Plantes', 1, 6, 'Balade Jardin des Plantes'),
(7, '2025-12-07', '10:30', 'Disponible', '24', 'Balade au bord de la rivière', 'Quais de Seine', 2, 7, 'Balade Quais de Seine'),
(8, '2025-12-08', '13:00', 'Disponible', '20', 'Sortie pour chiens seniors', 'Parc Georges Brassens', 1, 8, 'Balade Parc Georges Brassens'),
(9, '2025-12-09', '09:45', 'Disponible', '28', 'Exploration d’un grand parc avec activités', 'Parc de la Villette', 2, 9, 'Exploration Parc Villette'),
(10, '2025-12-10', '17:00', 'Disponible', '18', 'Courte promenade après le travail', 'Square Saint-Lambert', 1, 10, 'Balade Square Saint-Lambert'),
(11, '2025-12-18', '10:00', 'Disponible', '20', 'AA', 'Mouguerre, skate parc', 60, NULL, 'Promenade de Loukia'),
(13, '2025-12-04', '19:45', 'Disponible', '-1', '', '', 15, NULL, 'Promenade pour mon chien '),
(14, '2025-12-04', '19:45', 'Disponible', '-1', '', '', 15, NULL, 'Promenade pour mon chien '),
(15, '2025-12-11', '12:00', 'Disponible', '-1', '', '', 15, NULL, 'Promenade de Loukia'),
(16, '2025-12-19', '12:00', 'Disponible', '11.97', '', 'Je sais pas', 15, NULL, 'Promenade de Loukia'),
(17, '2025-12-19', '12:00', 'Disponible', '11.97', '', 'Je sais pas', 15, NULL, 'Promenade de Loukia'),
(18, '2025-12-14', '10:00', 'Disponible', '23', '', 'Mouguerre', 60, 20, 'Promenade de Loukia'),
(19, '2025-12-15', '11:11', 'Disponible', '30', '', 'Iut de Bayonne', 15, 20, 'Promenade pour mon chien Gonzague'),
(20, '2025-12-19', '00:00', 'Disponible', '10', '', 'Mouguerre, skate parc', 15, 20, 'vkdvknvkf '),
(21, '2025-12-28', '10:00', 'Disponible', '14.98', '', 'Mouguerre', 30, 20, 'Promenade de Loukia'),
(22, '2025-12-25', '19:11', 'Disponible', '1', '', 'Iut de Bayonne', 15, 20, 'Promenade pour mon chien '),
(23, '2025-12-25', '19:11', 'Disponible', '1', '', 'Iut de Bayonne', 15, 20, 'Promenade pour mon chien '),
(24, '2025-12-19', '12:12', 'Disponible', '11.99', '', '', 15, 20, 'jfirjfVFBRB BBBVF');

-- --------------------------------------------------------

--
-- Structure de la table `dog_Avis`
--

CREATE TABLE `dog_Avis` (
  `id_avis` int(11) NOT NULL,
  `note` varchar(50) NOT NULL,
  `texte_commentaire` varchar(50) DEFAULT NULL,
  `id_utilisateur` int(11) DEFAULT NULL,
  `id_promenade` int(11) DEFAULT NULL,
  `id_utilisateur_note` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `dog_Avis`
--

INSERT INTO `dog_Avis` (`id_avis`, `note`, `texte_commentaire`, `id_utilisateur`, `id_promenade`, `id_utilisateur_note`) VALUES
(1, '5', 'Super promenade !', 1, 1, 2),
(2, '4', 'Bien mais trop courte.', 2, 2, 1),
(3, '5', 'Mon chien a adoré.', 3, 3, 4),
(4, '4', 'Très agréable.', 4, 4, 5),
(5, '5', 'Promeneur très gentil.', 5, 5, 6),
(6, '0', 'Le promeneur à perdu mon chien ! >:(', 31, 4, 1);

-- --------------------------------------------------------

--
-- Structure de la table `dog_Chien`
--

CREATE TABLE `dog_Chien` (
  `id_chien` int(11) NOT NULL,
  `nom_chien` varchar(50) NOT NULL,
  `poids` varchar(50) DEFAULT NULL,
  `taille` varchar(50) DEFAULT NULL,
  `race` varchar(50) DEFAULT NULL,
  `cheminPhoto` varchar(50) DEFAULT NULL,
  `id_utilisateur` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `dog_Chien`
--

INSERT INTO `dog_Chien` (`id_chien`, `nom_chien`, `poids`, `taille`, `race`, `cheminPhoto`, `id_utilisateur`) VALUES
(1, 'Loukia', '25 kg', 'Moyen', 'Golden Retriever', 'images/chien/loukia.jpg', 1),
(2, 'Bella', '28 kg', 'Moyen', 'Labrador', 'images/chien/bella.jpg', 2),
(3, 'Max', '12 kg', 'Petit', 'Beagle', 'images/chien/max.jpg', 3),
(4, 'Rocky', '8 kg', 'Petit', 'Shih Tzu', 'images/chien/rocky.jpg', 4),
(5, 'Léo', '18 kg', 'Moyen', 'Cocker', 'images/chien/leo.jpg', 5),
(6, 'Ruby', '22 kg', 'Moyen', 'Border Collie', 'images/chien/ruby.jpg', 6),
(7, 'Charlie', '5 kg', 'Très Petit', 'Chihuahua', 'images/chien/charlie.jpg', 7),
(8, 'Stella', '40 kg', 'Très Grand', 'Dogue Allemand', 'images/chien/stella.jpg', 8),
(9, 'Rex', '30 kg', 'Grand', 'Berger Allemand', 'images/chien/rex.jpg', 9),
(10, 'Milo', '10 kg', 'Petit', 'Jack Russell', 'images/chien/milo.jpg', 10);

-- --------------------------------------------------------

--
-- Structure de la table `dog_Concerne`
--

CREATE TABLE `dog_Concerne` (
  `id_chien` int(11) NOT NULL,
  `id_annonce` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `dog_Concerne`
--

INSERT INTO `dog_Concerne` (`id_chien`, `id_annonce`) VALUES
(1, 0),
(9, 0),
(10, 0),
(1, 1),
(2, 2),
(3, 3),
(4, 4),
(5, 5),
(6, 6),
(7, 7),
(8, 8),
(9, 9),
(10, 10),
(10, 11),
(1, 12),
(1, 15),
(1, 16),
(1, 17),
(1, 18),
(1, 19),
(1, 20),
(1, 21),
(1, 22),
(1, 23),
(1, 24);

-- --------------------------------------------------------

--
-- Structure de la table `dog_Conversation`
--

CREATE TABLE `dog_Conversation` (
  `id_conversation` int(11) NOT NULL,
  `date_creation` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `dog_Conversation`
--

INSERT INTO `dog_Conversation` (`id_conversation`, `date_creation`) VALUES
(1, '2025-11-01'),
(2, '2025-11-02'),
(3, '2025-11-03');

-- --------------------------------------------------------

--
-- Structure de la table `dog_Creer`
--

CREATE TABLE `dog_Creer` (
  `id_utilisateur` int(11) NOT NULL,
  `id_conversation` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `dog_Creer`
--

INSERT INTO `dog_Creer` (`id_utilisateur`, `id_conversation`) VALUES
(1, 1),
(2, 2),
(3, 3);

-- --------------------------------------------------------

--
-- Structure de la table `dog_Message`
--

CREATE TABLE `dog_Message` (
  `id_message` int(11) NOT NULL,
  `contenu` varchar(500) NOT NULL,
  `DateHeureMessage` datetime NOT NULL,
  `id_utilisateur` int(11) DEFAULT NULL,
  `id_conversation` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `dog_Message`
--

INSERT INTO `dog_Message` (`id_message`, `contenu`, `DateHeureMessage`, `id_utilisateur`, `id_conversation`) VALUES
(1, 'Bonjour, je suis intéressé par la promenade.', '2025-11-01 10:00:00', 1, 1),
(2, 'Je vous confirme pour demain.', '2025-11-01 10:05:00', 2, 1),
(3, 'Bonjour, disponible pour le samedi ?', '2025-11-02 09:30:00', 2, 2),
(4, 'Oui, c’est parfait.', '2025-11-02 09:35:00', 1, 2),
(5, 'ouais', '2026-01-12 07:30:25', 30, 1);

-- --------------------------------------------------------

--
-- Structure de la table `dog_Participe`
--

CREATE TABLE `dog_Participe` (
  `id_chien` int(11) NOT NULL,
  `id_promenade` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `dog_Participe`
--

INSERT INTO `dog_Participe` (`id_chien`, `id_promenade`) VALUES
(1, 1),
(2, 2),
(3, 3),
(4, 4),
(5, 5),
(6, 6),
(7, 7),
(8, 8),
(9, 9),
(10, 10);

-- --------------------------------------------------------

--
-- Structure de la table `dog_Promenade`
--

CREATE TABLE `dog_Promenade` (
  `id_promenade` int(11) NOT NULL,
  `statut` varchar(50) NOT NULL,
  `id_chien` int(11) NOT NULL,
  `id_promeneur` int(11) NOT NULL,
  `id_proprietaire` int(11) NOT NULL,
  `id_annonce` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `dog_Promenade`
--

INSERT INTO `dog_Promenade` (`id_promenade`, `statut`, `id_chien`, `id_promeneur`, `id_proprietaire`, `id_annonce`) VALUES
(1, 'Active', 7, 16, 6, 3),
(2, 'Terminé', 3, 11, 14, 9),
(3, 'En cours', 5, 10, 19, 10),
(4, 'Active', 6, 11, 14, 23),
(5, 'En cours', 5, 18, 1, 12),
(6, 'Active', 1, 7, 4, 16),
(7, 'Terminé', 10, 6, 7, 18),
(8, 'Active', 8, 21, 23, 16),
(9, 'En cours', 2, 22, 24, 4),
(10, 'Active', 2, 22, 21, 17);

-- --------------------------------------------------------

--
-- Structure de la table `dog_Répond`
--

CREATE TABLE `dog_Répond` (
  `id_annonce` int(11) NOT NULL,
  `id_utilisateur` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `dog_Répond`
--

INSERT INTO `dog_Répond` (`id_annonce`, `id_utilisateur`) VALUES
(1, 1),
(2, 2),
(3, 3),
(4, 4),
(5, 5);

-- --------------------------------------------------------

--
-- Structure de la table `dog_Utilisateur`
--

CREATE TABLE `dog_Utilisateur` (
  `id_utilisateur` int(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `estMaitre` tinyint(1) NOT NULL,
  `estPromeneur` tinyint(1) NOT NULL,
  `adresse` varchar(50) DEFAULT NULL,
  `motDePasse` varchar(255) DEFAULT NULL,
  `pseudo` varchar(100) NOT NULL,
  `photoProfil` varchar(255) DEFAULT NULL,
  `numTelephone` varchar(50) DEFAULT NULL,
  `tentatives_echouees` int(11) NOT NULL DEFAULT '0',
  `date_dernier_echec_connexion` datetime DEFAULT NULL,
  `statut_compte` enum('actif','desactive') NOT NULL DEFAULT 'actif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `dog_Utilisateur`
--

INSERT INTO `dog_Utilisateur` (`id_utilisateur`, `email`, `estMaitre`, `estPromeneur`, `adresse`, `motDePasse`, `pseudo`, `photoProfil`, `numTelephone`, `tentatives_echouees`, `date_dernier_echec_connexion`, `statut_compte`) VALUES
(1, 'jean.dupont@example.com', 1, 1, '123 Rue A, Paris', 'mdp1', 'Dupont_Jean', NULL, '0601020304', 1, '2026-01-12 08:13:43', 'actif'),
(2, 'sophie.martin@example.com', 0, 1, '45 Rue B, Lyon', 'mdp2', 'Martin_Sophie', NULL, '0602030405', 0, NULL, 'actif'),
(3, 'paul.lemoine@example.com', 1, 0, '78 Rue C, Marseille', 'mdp3', 'Lemoine_Paul', NULL, '0603040506', 0, NULL, 'actif'),
(4, 'claire.leroy@example.com', 1, 1, '12 Rue D, Lille', 'mdp4', 'Leroy_Claire', NULL, '0604050607', 0, NULL, 'actif'),
(5, 'marie.fabre@example.com', 0, 1, '34 Rue E, Nantes', 'mdp5', 'Fabre_Marie', NULL, '0605060708', 0, NULL, 'actif'),
(6, 'lucas.giraud@example.com', 1, 1, '56 Rue F, Bordeaux', 'mdp6', 'Giraud_Lucas', NULL, '0606070809', 0, NULL, 'actif'),
(7, 'emma.robin@example.com', 1, 0, '78 Rue G, Toulouse', 'mdp7', 'Robin_Emma', NULL, '0607080910', 0, NULL, 'actif'),
(8, 'adam.moreau@example.com', 0, 1, '90 Rue H, Strasbourg', 'mdp8', 'Moreau_Adam', NULL, '0608091011', 0, NULL, 'actif'),
(9, 'julie.bertrand@example.com', 1, 1, '21 Rue I, Nice', 'mdp9', 'Bertrand_Julie', NULL, '0609101112', 0, NULL, 'actif'),
(10, 'thomas.durand@example.com', 1, 1, '32 Rue J, Rennes', 'mdp10', 'Durand_Thomas', NULL, '0610111213', 0, NULL, 'actif'),
(11, 'viclalanne64@gmail.com', 1, 0, '118 Chemin de Basoilar', '12345678', 'Lalanne_Victor', NULL, '0769951623', 3, '2025-12-13 19:45:19', 'desactive'),
(12, 'victor64lalanne@gmail.com', 1, 0, '118 Chemin de Basoilar', '$2y$10$YMxbFHD9ojmOZV3byC7nxuqeTLe0gXASAy4M80FzSui', 'Lalanne_Victor', NULL, '0769951623', 3, '2025-12-12 12:30:33', 'desactive'),
(13, 'vefvio@gmail.com', 1, 0, '118 Chemin de Basoilar', '$2y$10$H..NK33pN4h/6wxyvOz1pO6.ODTgqEbrDzvMWxMd0MM', 'Lalanne_Victor', NULL, '5569951623', 0, NULL, 'actif'),
(14, 'vlalanne@gmail.com', 1, 0, '118 Chemin de Basoilar', '$2y$10$O4Z8RkYvX2fUc1VfA.iNkerCExqgkFh0B4r0q9rDU8B', 'Lalanne_Victor', NULL, '0769951623', 0, NULL, 'actif'),
(15, 'jesaispas@gmail.com', 1, 0, '118 Chemin de Basoilar', '$2y$10$khcsaLRV1SFMd0Q8h5hepue7qJEpenTwKi9gnvJOmQr', 'Lalanne_Victor', NULL, '0769951623', 3, '2025-12-12 12:30:21', 'desactive'),
(16, 'a@gmail.com', 1, 0, '118 Chemin de Basoilar', '$2y$10$1Gsus0BKlcBfxd1AnejJpO1rzeGtyY5pApC7oUl.NHr', 'Lalanne_Victor', NULL, '0769951623', 1, '2025-12-12 11:42:28', 'actif'),
(17, 'b@gmail.com', 0, 1, '118 Chemin de Basoilar', '$2y$10$yuHAPKMdPxUBwA2ldlP3wOhIF6qScGhxFSR4nkczDqu', 'Lalanne_Victor', NULL, '0769951623', 0, NULL, 'actif'),
(18, 'c@gmail.com', 1, 0, '118 Chemin de Basoilar', '$2y$10$bQpPT9StbFvWgWVg8N0JQ.kLzBu2VVFtqb8ao2fgsu8', 'Lalanne_Victor', NULL, '0769951623', 0, NULL, 'actif'),
(19, 'vlalanne001@gmail.com', 1, 0, '118 Chemin de Basoilar', '$2y$10$VVvzd61bZe5zw2b.q3.Fj.NlTzPV66OcppB1vd05NH/', 'Lalanne_Victor', NULL, '0769951623', 3, '2025-12-12 12:57:28', 'desactive'),
(20, 'd@gmail.com', 1, 0, '118 Chemin de Basoilar', '$2y$10$9VAnbM3xwZR9GepztwuJM.am/N8hnzVx7QNrcfkqPZ50FlWa9hfzG', 'Lalanne_Victor', NULL, '0769951623', 0, NULL, 'actif'),
(21, 'v@gmail.com', 0, 1, '118 Chemin de Basoilar', '$2y$10$C/.d3k4iYYT43Fl4i6IDRumCvmwNI54Jc9I/BZYZHPnQqSoPBMKU.', 'Lalanne_Victor', NULL, '0769951623', 3, '2025-12-12 21:52:47', 'desactive'),
(22, 'vl@gmail.com', 0, 1, '118 Chemin de Basoilar', '$2y$10$l9n8AaciI8prFJBej.C9QO2z0WNyKxefIOk8dW0aJsNg4OaSXWua.', 'Lalanne_Victor', NULL, '0769951623', 1, '2025-12-16 16:03:15', 'actif'),
(23, 'vico@gmail.com', 1, 1, '118 Chemin de Basoilar', '$2y$10$Uf8jEqNfxTOgszhTMo33Bu24821PXbDwi6v7msdmgZm1pMjG/LJfW', 'Lalanne_Victor', NULL, '0769951623', 3, '2025-12-13 00:07:07', 'desactive'),
(24, 'all@gmail.com', 1, 1, '118 Chemin de Basoilar', '$2y$10$K3015VQeF.G3sCJHdHatDur3O9AXU7Y9qJuTNKdK0N0JUpeXK5ov.', 'Lalanne_Victor', NULL, '0769951623', 0, NULL, 'actif'),
(25, 'vicolalanne@gmail.com', 1, 0, '118 Chemin de Basoilar', '$2y$10$WPUviskXRx9zjj5fDFle1OghmVQdIQWbziFpqZqBljH/6uFDQmE3m', 'Vico', NULL, '0769951623', 3, '2025-12-15 14:03:23', 'desactive'),
(26, 'oui.non@oui.fr', 1, 1, '24 Chemin d\'Arancette', '$2y$12$Wc1v1bhYLzOLOw0I9MnZge/sot1bsL.jYzmHACz1rKTwJwGxKpyo2', 'Oui', NULL, '0102030405', 1, '2026-01-12 08:08:23', 'actif'),
(27, 'r@gmail.com', 1, 1, 'LIEU DIT FAUREILLE', '$2y$12$jykAMOrcAghOwE8FiS.YSuCi5gUGNEf7IvYmGpBF3Xcnh.hyXqkLm', 'robinboiss', NULL, '0621093184', 0, NULL, 'actif'),
(28, 'robin@gmail.com', 1, 1, 'LIEU DIT FAUREILLE', '$2y$12$B9G.F7oPTF6e64EchNHoxuG5sTIp9/fSpnDfMpAupYjPQZLMZa5r2', 'robinho', NULL, '0621093184', 0, NULL, 'actif'),
(29, 'oui@oui.fr', 1, 1, 'aeg eza e', '$2y$12$tK7BHrviOqUHDKACxPWciu3fAVxKneNk30BB65FvELhCCPy6GDF.C', 'aze', NULL, '0102030405', 0, NULL, 'actif'),
(30, 'oui@oui.com', 1, 1, '6 CHEMIN DES GRANDES TERRES', '$2y$12$z64xVoSuudP7TsXYXpFbEee72tcSNHNHQfd4xTZFA5lnIED6QreMG', 'Oui', NULL, '0621093184', 0, NULL, 'actif'),
(31, 'jcampistr001@iutbayonne.univ-pau.fr', 1, 1, 'Non', '$2y$12$xSN/zq81ZxEW8Y6Sbj6CEudySHBzxZlZUY.axyaHq99VBU4yViFC.', 'Campistron_Julian', '1768202804_35a8a37f9897.jpg', '0420690420', 0, NULL, 'actif');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `dog_Annonce`
--
ALTER TABLE `dog_Annonce`
  ADD PRIMARY KEY (`id_annonce`),
  ADD KEY `id_utilisateur` (`id_utilisateur`);

--
-- Index pour la table `dog_Avis`
--
ALTER TABLE `dog_Avis`
  ADD PRIMARY KEY (`id_avis`),
  ADD KEY `id_utilisateur` (`id_utilisateur`),
  ADD KEY `id_promenade` (`id_promenade`),
  ADD KEY `id_utilisateur_note` (`id_utilisateur_note`);

--
-- Index pour la table `dog_Chien`
--
ALTER TABLE `dog_Chien`
  ADD PRIMARY KEY (`id_chien`),
  ADD KEY `id_utilisateur` (`id_utilisateur`);

--
-- Index pour la table `dog_Concerne`
--
ALTER TABLE `dog_Concerne`
  ADD PRIMARY KEY (`id_chien`,`id_annonce`),
  ADD KEY `id_annonce` (`id_annonce`);

--
-- Index pour la table `dog_Conversation`
--
ALTER TABLE `dog_Conversation`
  ADD PRIMARY KEY (`id_conversation`);

--
-- Index pour la table `dog_Creer`
--
ALTER TABLE `dog_Creer`
  ADD PRIMARY KEY (`id_utilisateur`,`id_conversation`),
  ADD KEY `id_conversation` (`id_conversation`);

--
-- Index pour la table `dog_Message`
--
ALTER TABLE `dog_Message`
  ADD PRIMARY KEY (`id_message`),
  ADD KEY `id_utilisateur` (`id_utilisateur`),
  ADD KEY `id_conversation` (`id_conversation`);

--
-- Index pour la table `dog_Participe`
--
ALTER TABLE `dog_Participe`
  ADD PRIMARY KEY (`id_chien`,`id_promenade`),
  ADD KEY `id_promenade` (`id_promenade`);

--
-- Index pour la table `dog_Promenade`
--
ALTER TABLE `dog_Promenade`
  ADD PRIMARY KEY (`id_promenade`),
  ADD KEY `id_chien` (`id_chien`),
  ADD KEY `id_promeneur` (`id_promeneur`),
  ADD KEY `id_proprietaire` (`id_proprietaire`),
  ADD KEY `id_annonce` (`id_annonce`);

--
-- Index pour la table `dog_Répond`
--
ALTER TABLE `dog_Répond`
  ADD PRIMARY KEY (`id_annonce`,`id_utilisateur`),
  ADD KEY `id_utilisateur` (`id_utilisateur`);

--
-- Index pour la table `dog_Utilisateur`
--
ALTER TABLE `dog_Utilisateur`
  ADD PRIMARY KEY (`id_utilisateur`),
  ADD UNIQUE KEY `unique_email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `dog_Annonce`
--
ALTER TABLE `dog_Annonce`
  MODIFY `id_annonce` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT pour la table `dog_Utilisateur`
--
ALTER TABLE `dog_Utilisateur`
  MODIFY `id_utilisateur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
