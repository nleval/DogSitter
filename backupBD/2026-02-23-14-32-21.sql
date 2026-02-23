-- Sauvegarde complète des tables préfixées par dog_ dans dogsitter
-- Générée le 2026-02-23-14-32-21

-- Structure de la table dog_utilisateur
CREATE TABLE `dog_utilisateur` (
  `id_utilisateur` int NOT NULL AUTO_INCREMENT,
  `email` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `estMaitre` tinyint(1) NOT NULL,
  `estPromeneur` tinyint(1) NOT NULL,
  `adresse` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `motDePasse` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pseudo` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `photoProfil` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `numTelephone` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tentatives_echouees` int DEFAULT '0',
  `date_dernier_echec_connexion` datetime DEFAULT NULL,
  `statut_compte` enum('actif','desactive') COLLATE utf8mb4_general_ci DEFAULT 'actif',
  PRIMARY KEY (`id_utilisateur`),
  UNIQUE KEY `unique_email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Données de la table dog_utilisateur
INSERT INTO dog_utilisateur VALUES ('4','victor@gmail.com','1','1','118 Chemin de Basoilar','$2y$10$2BazQnxFFuzIYW.6UL8apuvxh2yRix6eoKv19mL1sryPogRl.91fq','Victor le bdg','4_Victor.jpg','0769951623','0',NULL,'actif');
INSERT INTO dog_utilisateur VALUES ('5','noa@gmail.com','1','1','118 Chemin de Basoilar','$2y$10$ZNIchL/e5TfoBc5G/ROhheKDn.vJ7gzByBDZTzfP5qWYzC8ZXX.Em','Noa ','1771339484_cfc747fcdedd.jpg','0769951623','0',NULL,'actif');
INSERT INTO dog_utilisateur VALUES ('6','promeneur@gmail.com','0','1','118 Chemin de Basoilar','$2y$10$CVkBakATKYADteuyQl2dfuyyeTtGbnRyE4x2jxWsuZC78av6ESbSy','Promeneur',NULL,'0769951623','0',NULL,'actif');
INSERT INTO dog_utilisateur VALUES ('7','testeur1@gmail.com','1','1','118 Chemin de Basoilar','$2y$10$5pKALMONzE3vHCVUPwz78eR1L04O6GhUz7RmTBnDtV/NCqZ3zBkRq','Testeur1',NULL,'0769951623','0',NULL,'actif');
INSERT INTO dog_utilisateur VALUES ('8','maitre@gmail.com','1','0','118 Chemin de Basoilar','$2y$10$cYiW/E3UqQ/ztPXFOJdRyuru3UZajs5qfOUCyur57aDl.amnj5SnO','Maitre',NULL,'0769951623','0',NULL,'actif');

-- Structure de la table dog_annonce
CREATE TABLE `dog_annonce` (
  `id_annonce` int NOT NULL AUTO_INCREMENT,
  `datePromenade` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `horaire` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `status` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tarif` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `description` varchar(500) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `endroitPromenade` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `duree` int NOT NULL,
  `id_utilisateur` int DEFAULT NULL,
  `id_promeneur` int DEFAULT NULL,
  `statut_promenade` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `titre` varchar(255) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id_annonce`),
  KEY `id_utilisateur` (`id_utilisateur`),
  KEY `id_promeneur` (`id_promeneur`),
  CONSTRAINT `fk_annonce_promeneur` FOREIGN KEY (`id_promeneur`) REFERENCES `dog_utilisateur` (`id_utilisateur`) ON DELETE SET NULL,
  CONSTRAINT `fk_annonce_utilisateur` FOREIGN KEY (`id_utilisateur`) REFERENCES `dog_utilisateur` (`id_utilisateur`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Données de la table dog_annonce
INSERT INTO dog_annonce VALUES ('27','2026-02-20','20:00','Indisponible','20','','','60','7',NULL,'a_venir','je test des trucs');
INSERT INTO dog_annonce VALUES ('28','2026-02-21','20:00','Indisponible','20','','','60','5',NULL,NULL,'Test Annonce/Promenade');
INSERT INTO dog_annonce VALUES ('29','2026-02-21','20:00','Indisponible','20','','','60','5','4','archivee','Test Annonce/Promenade');
INSERT INTO dog_annonce VALUES ('30','2026-02-23','20:00','Indisponible','19.98','','Mouguerre, skate parc','30','5','4','a_venir','test  js corrigé ');
INSERT INTO dog_annonce VALUES ('32','2026-02-25','20:00','active','19.99','','','60','4',NULL,NULL,'Promenade matinale ');
INSERT INTO dog_annonce VALUES ('33','2026-02-24','20:00','active','19.99','','','60','5',NULL,NULL,'Avec peyooooo');
INSERT INTO dog_annonce VALUES ('34','2026-02-25','20:00','active','20','','','60','4',NULL,NULL,'Promenade pour mon chien Gonzague');

-- Structure de la table dog_avis
CREATE TABLE `dog_avis` (
  `id_avis` int NOT NULL AUTO_INCREMENT,
  `note` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `texte_commentaire` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_utilisateur` int DEFAULT NULL,
  `id_annonce` int DEFAULT NULL,
  `id_utilisateur_note` int DEFAULT NULL,
  PRIMARY KEY (`id_avis`),
  KEY `id_utilisateur` (`id_utilisateur`),
  KEY `id_annonce` (`id_annonce`),
  KEY `id_utilisateur_note` (`id_utilisateur_note`),
  CONSTRAINT `fk_avis_annonce` FOREIGN KEY (`id_annonce`) REFERENCES `dog_annonce` (`id_annonce`) ON DELETE CASCADE,
  CONSTRAINT `fk_avis_user` FOREIGN KEY (`id_utilisateur`) REFERENCES `dog_utilisateur` (`id_utilisateur`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Données de la table dog_avis

-- Structure de la table dog_chien
CREATE TABLE `dog_chien` (
  `id_chien` int NOT NULL AUTO_INCREMENT,
  `nom_chien` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `poids` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `taille` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `race` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `cheminPhoto` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_utilisateur` int DEFAULT NULL,
  PRIMARY KEY (`id_chien`),
  KEY `id_utilisateur` (`id_utilisateur`),
  CONSTRAINT `fk_chien_utilisateur` FOREIGN KEY (`id_utilisateur`) REFERENCES `dog_utilisateur` (`id_utilisateur`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Données de la table dog_chien
INSERT INTO dog_chien VALUES ('8','Loukia','25','Grand','Golden Retrievere','chien_4_1771339035.jpg','4');
INSERT INTO dog_chien VALUES ('13','Loukia','35','Grand','Golden Retriever','','7');
INSERT INTO dog_chien VALUES ('14','Loukia','35','Grand','Golden Retriver','','5');
INSERT INTO dog_chien VALUES ('15','Karim','2.8','Très petit','Arabe','chien_5_1771843815.jpg','5');

-- Structure de la table dog_concerne
CREATE TABLE `dog_concerne` (
  `id_chien` int NOT NULL,
  `id_annonce` int NOT NULL,
  PRIMARY KEY (`id_chien`,`id_annonce`),
  KEY `id_annonce` (`id_annonce`),
  CONSTRAINT `fk_concerne_annonce` FOREIGN KEY (`id_annonce`) REFERENCES `dog_annonce` (`id_annonce`) ON DELETE CASCADE,
  CONSTRAINT `fk_concerne_chien` FOREIGN KEY (`id_chien`) REFERENCES `dog_chien` (`id_chien`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Données de la table dog_concerne
INSERT INTO dog_concerne VALUES ('13','27');
INSERT INTO dog_concerne VALUES ('14','28');
INSERT INTO dog_concerne VALUES ('14','29');
INSERT INTO dog_concerne VALUES ('14','30');
INSERT INTO dog_concerne VALUES ('8','32');
INSERT INTO dog_concerne VALUES ('14','33');
INSERT INTO dog_concerne VALUES ('15','33');
INSERT INTO dog_concerne VALUES ('8','34');

-- Structure de la table dog_conversation
CREATE TABLE `dog_conversation` (
  `id_conversation` int NOT NULL AUTO_INCREMENT,
  `date_creation` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `titre` varchar(80) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id_conversation`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Données de la table dog_conversation
INSERT INTO dog_conversation VALUES ('7','2026-02-18 16:36:56',NULL);
INSERT INTO dog_conversation VALUES ('8','2026-02-19 16:07:14',NULL);

-- Structure de la table dog_creer
CREATE TABLE `dog_creer` (
  `id_utilisateur` int NOT NULL,
  `id_conversation` int NOT NULL,
  PRIMARY KEY (`id_utilisateur`,`id_conversation`),
  KEY `id_conversation` (`id_conversation`),
  CONSTRAINT `fk_creer_conv` FOREIGN KEY (`id_conversation`) REFERENCES `dog_conversation` (`id_conversation`) ON DELETE CASCADE,
  CONSTRAINT `fk_creer_user` FOREIGN KEY (`id_utilisateur`) REFERENCES `dog_utilisateur` (`id_utilisateur`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Données de la table dog_creer
INSERT INTO dog_creer VALUES ('4','7');
INSERT INTO dog_creer VALUES ('5','7');
INSERT INTO dog_creer VALUES ('5','8');
INSERT INTO dog_creer VALUES ('7','8');

-- Structure de la table dog_dernieresave
CREATE TABLE `dog_dernieresave` (
  `id` int NOT NULL AUTO_INCREMENT,
  `date_save` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Données de la table dog_dernieresave
INSERT INTO dog_dernieresave VALUES ('1','2026-02-23 14:24:03');
INSERT INTO dog_dernieresave VALUES ('2','2026-02-23 14:25:37');

-- Structure de la table dog_message
CREATE TABLE `dog_message` (
  `id_message` int NOT NULL AUTO_INCREMENT,
  `contenu` varchar(500) COLLATE utf8mb4_general_ci NOT NULL,
  `DateHeureMessage` datetime NOT NULL,
  `id_utilisateur` int DEFAULT NULL,
  `id_conversation` int DEFAULT NULL,
  `est_modifie` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_message`),
  KEY `id_utilisateur` (`id_utilisateur`),
  KEY `id_conversation` (`id_conversation`),
  CONSTRAINT `fk_message_conv` FOREIGN KEY (`id_conversation`) REFERENCES `dog_conversation` (`id_conversation`) ON DELETE CASCADE,
  CONSTRAINT `fk_message_user` FOREIGN KEY (`id_utilisateur`) REFERENCES `dog_utilisateur` (`id_utilisateur`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Données de la table dog_message
INSERT INTO dog_message VALUES ('24','test notifications','2026-02-19 11:39:35','5','7','0');
INSERT INTO dog_message VALUES ('25','retest notif','2026-02-19 11:44:45','4','7','0');
INSERT INTO dog_message VALUES ('26','notif','2026-02-19 11:46:35','5','7','0');
INSERT INTO dog_message VALUES ('27','notif','2026-02-19 11:49:38','4','7','0');
INSERT INTO dog_message VALUES ('28','re test','2026-02-19 11:51:40','5','7','0');
INSERT INTO dog_message VALUES ('29','Bonjour ! J''ai accepté votre candidature pour promener mon chien. Discutons ensemble des détails de la promenade (date, horaire, lieu de rendez-vous, instructions particulières, etc.). Au plaisir d''échanger avec vous ! Voir l''annonce: index.php?controleur=annonce&methode=afficherAnnonce&id_annonce=23','2026-02-19 12:17:12','4','7','0');
INSERT INTO dog_message VALUES ('30','Bonjour ! J''ai accepté votre candidature pour promener mon chien. Discutons ensemble des détails de la promenade (date, horaire, lieu de rendez-vous, instructions particulières, etc.). Au plaisir d''échanger avec vous ! Voir l''annonce: index.php?controleur=annonce&methode=afficherAnnonce&id_annonce=25','2026-02-19 15:15:59','4','7','0');
INSERT INTO dog_message VALUES ('31','tg','2026-02-19 15:16:34','5','7','0');
INSERT INTO dog_message VALUES ('32','toi tg','2026-02-19 15:38:45','5','7','0');
INSERT INTO dog_message VALUES ('33','chut','2026-02-19 15:42:12','4','7','0');
INSERT INTO dog_message VALUES ('34','chut','2026-02-19 15:44:42','5','7','0');
INSERT INTO dog_message VALUES ('35','chut','2026-02-19 15:47:17','4','7','0');
INSERT INTO dog_message VALUES ('36','tg','2026-02-19 15:48:53','5','7','0');
INSERT INTO dog_message VALUES ('37','Bonjour ! J''ai accepté votre candidature pour promener mon chien. Discutons ensemble des détails de la promenade (date, horaire, lieu de rendez-vous, instructions particulières, etc.). Au plaisir d''échanger avec vous ! Voir l''annonce: index.php?controleur=annonce&methode=afficherAnnonce&id_annonce=26','2026-02-19 16:08:08','7','8','0');
INSERT INTO dog_message VALUES ('38','Bonjour ! J''ai accepté votre candidature pour promener mon chien. Discutons ensemble des détails de la promenade (date, horaire, lieu de rendez-vous, instructions particulières, etc.). Au plaisir d''échanger avec vous ! Voir l''annonce: index.php?controleur=annonce&methode=afficherAnnonce&id_annonce=27','2026-02-19 16:20:24','7','8','0');
INSERT INTO dog_message VALUES ('39','comment ca se fais que ca marche','2026-02-19 16:52:20','5','8','0');
INSERT INTO dog_message VALUES ('40','Bonjour ! J''ai accepté votre candidature pour promener mon chien. Discutons ensemble des détails de la promenade (date, horaire, lieu de rendez-vous, instructions particulières, etc.). Au plaisir d''échanger avec vous ! Voir l''annonce: index.php?controleur=annonce&methode=afficherAnnonce&id_annonce=29','2026-02-20 12:00:54','5','7','0');
INSERT INTO dog_message VALUES ('41','on va tester','2026-02-22 13:12:37','4','7','1');
INSERT INTO dog_message VALUES ('42','Bonjour ! J''ai accepté votre candidature pour promener mon chien. Discutons ensemble des détails de la promenade (date, horaire, lieu de rendez-vous, instructions particulières, etc.). Au plaisir d''échanger avec vous ! Voir l''annonce: index.php?controleur=annonce&methode=afficherAnnonce&id_annonce=30','2026-02-22 13:16:34','5','7','0');
INSERT INTO dog_message VALUES ('43','ftnn','2026-02-23 14:19:05','4','7','0');

-- Structure de la table dog_repond
CREATE TABLE `dog_repond` (
  `id_reponse` int NOT NULL AUTO_INCREMENT,
  `id_annonce` int NOT NULL,
  `id_utilisateur` int NOT NULL,
  `statut` enum('en_attente','acceptee','refusee') COLLATE utf8mb4_general_ci DEFAULT 'en_attente',
  `date_creation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_modification` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_reponse`),
  UNIQUE KEY `unique_reponse` (`id_annonce`,`id_utilisateur`),
  KEY `fk_repond_user` (`id_utilisateur`),
  CONSTRAINT `fk_repond_annonce` FOREIGN KEY (`id_annonce`) REFERENCES `dog_annonce` (`id_annonce`) ON DELETE CASCADE,
  CONSTRAINT `fk_repond_user` FOREIGN KEY (`id_utilisateur`) REFERENCES `dog_utilisateur` (`id_utilisateur`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Données de la table dog_repond
INSERT INTO dog_repond VALUES ('32','27','5','acceptee','2026-02-19 16:19:49','2026-02-19 16:20:24');
INSERT INTO dog_repond VALUES ('33','28','4','acceptee','2026-02-20 11:54:33','2026-02-20 11:55:01');
INSERT INTO dog_repond VALUES ('34','29','4','acceptee','2026-02-20 12:00:22','2026-02-20 12:00:54');
INSERT INTO dog_repond VALUES ('35','30','4','acceptee','2026-02-22 13:15:23','2026-02-22 13:16:34');
INSERT INTO dog_repond VALUES ('38','32','5','en_attente','2026-02-23 14:19:58','2026-02-23 14:19:58');

-- Structure de la table dog_notification
CREATE TABLE `dog_notification` (
  `id_notification` int NOT NULL AUTO_INCREMENT,
  `id_utilisateur` int NOT NULL,
  `titre` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `message` text COLLATE utf8mb4_general_ci NOT NULL,
  `type` enum('candidature_soumise','candidature_reçue','candidature_acceptée','candidature_refusée','info') COLLATE utf8mb4_general_ci DEFAULT 'info',
  `id_annonce` int DEFAULT NULL,
  `id_reponse` int DEFAULT NULL,
  `id_promeneur` int DEFAULT NULL,
  `lue` tinyint(1) DEFAULT '0',
  `date_creation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_notification`),
  KEY `idx_utilisateur` (`id_utilisateur`),
  KEY `idx_non_lue` (`lue`),
  KEY `fk_notif_annonce` (`id_annonce`),
  KEY `fk_notif_reponse` (`id_reponse`),
  KEY `fk_notif_promeneur` (`id_promeneur`),
  CONSTRAINT `fk_notif_annonce` FOREIGN KEY (`id_annonce`) REFERENCES `dog_annonce` (`id_annonce`) ON DELETE CASCADE,
  CONSTRAINT `fk_notif_promeneur` FOREIGN KEY (`id_promeneur`) REFERENCES `dog_utilisateur` (`id_utilisateur`) ON DELETE CASCADE,
  CONSTRAINT `fk_notif_reponse` FOREIGN KEY (`id_reponse`) REFERENCES `dog_repond` (`id_reponse`) ON DELETE CASCADE,
  CONSTRAINT `fk_notif_user` FOREIGN KEY (`id_utilisateur`) REFERENCES `dog_utilisateur` (`id_utilisateur`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=136 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Données de la table dog_notification
INSERT INTO dog_notification VALUES ('80','5','Nouveau message','Vous avez reçu un nouveau message de Victor','',NULL,NULL,'4','1','2026-02-18 16:24:05');
INSERT INTO dog_notification VALUES ('84','5','Nouveau message','Victor : retest notif','',NULL,NULL,'4','1','2026-02-19 11:44:45');
INSERT INTO dog_notification VALUES ('86','5','Nouveau message','Victor : notif','',NULL,NULL,'4','1','2026-02-19 11:49:38');
INSERT INTO dog_notification VALUES ('107','4','Nouveau message','Vous avez reçu un nouveau message de Noa ','',NULL,NULL,'5','1','2026-02-19 15:16:34');
INSERT INTO dog_notification VALUES ('108','4','Nouveau message','Vous avez reçu un nouveau message de Noa ','',NULL,NULL,'5','1','2026-02-19 15:38:45');
INSERT INTO dog_notification VALUES ('109','5','Nouveau message','Vous avez reçu un nouveau message de Victor','',NULL,NULL,'4','1','2026-02-19 15:42:12');
INSERT INTO dog_notification VALUES ('110','4','Nouveau message','Vous avez reçu un nouveau message de Noa ','',NULL,NULL,'5','1','2026-02-19 15:44:42');
INSERT INTO dog_notification VALUES ('111','5','Nouveau message','Vous avez reçu un nouveau message de Victor','',NULL,NULL,'4','1','2026-02-19 15:47:17');
INSERT INTO dog_notification VALUES ('112','4','Nouveau message','Vous avez reçu un nouveau message de Noa ','',NULL,NULL,'5','1','2026-02-19 15:48:53');
INSERT INTO dog_notification VALUES ('116','5','Candidature soumise','Votre candidature pour l''annonce "je test des trucs" a été enregistrée avec succès. Le maître sera notifié et examinera votre candidature.','candidature_soumise','27','32',NULL,'1','2026-02-19 16:19:49');
INSERT INTO dog_notification VALUES ('117','7','Nouvelle candidature reçue','Noa  a postulé pour votre annonce "je test des trucs".','candidature_reçue','27','32','5','1','2026-02-19 16:19:49');
INSERT INTO dog_notification VALUES ('118','5','Candidature acceptée','Votre candidature pour l''annonce "je test des trucs" a été acceptée. Une conversation a été créée pour discuter des détails de la promenade. Consultez vos messages.','candidature_acceptée','27','32','5','1','2026-02-19 16:20:24');
INSERT INTO dog_notification VALUES ('119','7','Nouveau message','Vous avez reçu un nouveau message de Noa ','',NULL,NULL,'5','1','2026-02-19 16:52:20');
INSERT INTO dog_notification VALUES ('120','4','Candidature soumise','Votre candidature pour l''annonce "Test Annonce/Promenade" a été enregistrée avec succès. Le maître sera notifié et examinera votre candidature.','candidature_soumise','28','33',NULL,'1','2026-02-20 11:54:33');
INSERT INTO dog_notification VALUES ('121','5','Nouvelle candidature reçue','Victor a postulé pour votre annonce "Test Annonce/Promenade".','candidature_reçue','28','33','4','1','2026-02-20 11:54:33');
INSERT INTO dog_notification VALUES ('122','4','Candidature soumise','Votre candidature pour l''annonce "Test Annonce/Promenade" a été enregistrée avec succès. Le maître sera notifié et examinera votre candidature.','candidature_soumise','29','34',NULL,'1','2026-02-20 12:00:22');
INSERT INTO dog_notification VALUES ('123','5','Nouvelle candidature reçue','Victor a postulé pour votre annonce "Test Annonce/Promenade".','candidature_reçue','29','34','4','1','2026-02-20 12:00:22');
INSERT INTO dog_notification VALUES ('124','4','Candidature acceptée','Votre candidature pour l''annonce "Test Annonce/Promenade" a été acceptée. Une conversation a été créée pour discuter des détails de la promenade. Consultez vos messages.','candidature_acceptée','29','34','4','1','2026-02-20 12:00:54');
INSERT INTO dog_notification VALUES ('125','5','Nouveau message','Vous avez reçu un nouveau message de Victor','',NULL,NULL,'4','1','2026-02-22 13:12:37');
INSERT INTO dog_notification VALUES ('126','4','Candidature soumise','Votre candidature pour l''annonce "test  js corrigé " a été enregistrée avec succès. Le maître sera notifié et examinera votre candidature.','candidature_soumise','30','35',NULL,'1','2026-02-22 13:15:23');
INSERT INTO dog_notification VALUES ('127','5','Nouvelle candidature reçue','Victor a postulé pour votre annonce "test  js corrigé ".','candidature_reçue','30','35','4','1','2026-02-22 13:15:23');
INSERT INTO dog_notification VALUES ('128','4','Candidature acceptée','Votre candidature pour l''annonce "test  js corrigé " a été acceptée. Une conversation a été créée pour discuter des détails de la promenade. Consultez vos messages.','candidature_acceptée','30','35','4','1','2026-02-22 13:16:34');
INSERT INTO dog_notification VALUES ('133','5','Nouveau message','Vous avez reçu un nouveau message de Victor le bdg','',NULL,NULL,'4','1','2026-02-23 14:19:05');
INSERT INTO dog_notification VALUES ('134','5','Candidature soumise','Votre candidature pour l''annonce "Promenade matinale " a été enregistrée avec succès. Le maître sera notifié et examinera votre candidature.','candidature_soumise','32','38',NULL,'1','2026-02-23 14:19:58');
INSERT INTO dog_notification VALUES ('135','4','Nouvelle candidature reçue','Noa  a postulé pour votre annonce "Promenade matinale ".','candidature_reçue','32','38','5','0','2026-02-23 14:19:58');

