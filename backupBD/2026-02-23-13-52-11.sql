-- Sauvegarde complète des tables préfixées par dog_ dans dogsitter
-- Générée le 2026-02-23-13-52-11

-- Structure de la table dog_annonce
CREATE TABLE `dog_annonce` (
  `id_annonce` int NOT NULL AUTO_INCREMENT,
  `datePromenade` varchar(50) NOT NULL,
  `horaire` varchar(50) NOT NULL,
  `status` varchar(50) DEFAULT NULL,
  `tarif` varchar(50) DEFAULT NULL,
  `description` varchar(500) DEFAULT NULL,
  `endroitPromenade` varchar(50) DEFAULT NULL,
  `duree` int NOT NULL,
  `id_utilisateur` int DEFAULT NULL,
  `titre` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id_annonce`),
  KEY `id_utilisateur` (`id_utilisateur`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Données de la table dog_annonce
INSERT INTO dog_annonce VALUES ('1','2025-12-01','09:00','Disponible','20','Promenade matinale au parc','Parc Montsouris','1','1','Balade Parc Montsouris');
INSERT INTO dog_annonce VALUES ('2','2025-12-02','14:30','Disponible','18','Balade tranquille autour du lac','Lac Daumesnil','1','2','Balade Lac Daumesnil');
INSERT INTO dog_annonce VALUES ('3','2025-12-03','11:00','Disponible','25','Sortie sportive pour chien énergique','Bois de Vincennes','2','3','Sortie sportive Bois de Vincennes');
INSERT INTO dog_annonce VALUES ('4','2025-12-04','16:00','Disponible','22','Promenade détente en zone ombragée','Parc de Sceaux','1','4','Promenade Parc de Sceaux');
INSERT INTO dog_annonce VALUES ('5','2025-12-05','08:30','Disponible','30','Longue balade pour chiens actifs','Forêt de Meudon','3','5','Longue balade Forêt Meudon');
INSERT INTO dog_annonce VALUES ('6','2025-12-06','15:00','Disponible','19','Sortie idéale pour chiens sociables','Jardin des Plantes','1','6','Balade Jardin des Plantes');
INSERT INTO dog_annonce VALUES ('7','2025-12-07','10:30','Disponible','24','Balade au bord de la rivière','Quais de Seine','2','7','Balade Quais de Seine');
INSERT INTO dog_annonce VALUES ('8','2025-12-08','13:00','Disponible','20','Sortie pour chiens seniors','Parc Georges Brassens','1','8','Balade Parc Georges Brassens');
INSERT INTO dog_annonce VALUES ('9','2025-12-09','09:45','Disponible','28','Exploration d’un grand parc avec activités','Parc de la Villette','2','9','Exploration Parc Villette');
INSERT INTO dog_annonce VALUES ('10','2025-12-10','17:00','Disponible','18','Courte promenade après le travail','Square Saint-Lambert','1','10','Balade Square Saint-Lambert');
INSERT INTO dog_annonce VALUES ('11','2025-12-18','10:00','Disponible','20','AA','Mouguerre, skate parc','60',NULL,'Promenade de Loukia');
INSERT INTO dog_annonce VALUES ('13','2025-12-04','19:45','Disponible','-1','','','15',NULL,'Promenade pour mon chien ');
INSERT INTO dog_annonce VALUES ('14','2025-12-04','19:45','Disponible','-1','','','15',NULL,'Promenade pour mon chien ');
INSERT INTO dog_annonce VALUES ('15','2025-12-11','12:00','Disponible','-1','','','15',NULL,'Promenade de Loukia');
INSERT INTO dog_annonce VALUES ('16','2025-12-19','12:00','Disponible','11.97','','Je sais pas','15',NULL,'Promenade de Loukia');
INSERT INTO dog_annonce VALUES ('17','2025-12-19','12:00','Disponible','11.97','','Je sais pas','15',NULL,'Promenade de Loukia');
INSERT INTO dog_annonce VALUES ('18','2025-12-14','10:00','Disponible','23','','Mouguerre','60','20','Promenade de Loukia');
INSERT INTO dog_annonce VALUES ('19','2025-12-15','11:11','Disponible','30','','Iut de Bayonne','15','20','Promenade pour mon chien Gonzague');
INSERT INTO dog_annonce VALUES ('20','2025-12-19','00:00','Disponible','10','','Mouguerre, skate parc','15','20','vkdvknvkf ');
INSERT INTO dog_annonce VALUES ('21','2025-12-28','10:00','Disponible','14.98','','Mouguerre','30','20','Promenade de Loukia');
INSERT INTO dog_annonce VALUES ('22','2025-12-25','19:11','Disponible','1','','Iut de Bayonne','15','20','Promenade pour mon chien ');
INSERT INTO dog_annonce VALUES ('23','2025-12-25','19:11','Disponible','1','','Iut de Bayonne','15','20','Promenade pour mon chien ');
INSERT INTO dog_annonce VALUES ('24','2025-12-19','12:12','Disponible','11.99','','','15','20','jfirjfVFBRB BBBVF');
INSERT INTO dog_annonce VALUES ('25','2026-01-15','12:00','Disponible','25','                                        oui
                                    ','chemin blanc','30','33','chemin blanc avec ceter');
INSERT INTO dog_annonce VALUES ('26','2026-01-15','12:00','Disponible','20','ioghe haui                                        
                                    ','parc du bois','15','35','Promenade golden');
INSERT INTO dog_annonce VALUES ('27','2026-01-14','20:00','Disponible','20','oui         ','Parc du bois','30','36','promenade milo');
INSERT INTO dog_annonce VALUES ('28','2026-01-30','12:50','Disponible','20','                                        fygyuijok
                                    ','cfrtyghuio','30','35','gfcvhbjcghiçàuhcgfioàîgyjuoià)p$hygj');
INSERT INTO dog_annonce VALUES ('29','2026-01-30','12:02','Disponible','20','                                       grzrgrz 
                                    ','rzzg','30','35','frgetrhyhgzgety');

-- Structure de la table dog_avis
CREATE TABLE `dog_avis` (
  `id_avis` int NOT NULL,
  `note` varchar(50) NOT NULL,
  `texte_commentaire` varchar(50) DEFAULT NULL,
  `id_utilisateur` int DEFAULT NULL,
  `id_promenade` int DEFAULT NULL,
  `id_utilisateur_note` int DEFAULT NULL,
  PRIMARY KEY (`id_avis`),
  KEY `id_utilisateur` (`id_utilisateur`),
  KEY `id_promenade` (`id_promenade`),
  KEY `id_utilisateur_note` (`id_utilisateur_note`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Données de la table dog_avis
INSERT INTO dog_avis VALUES ('1','5','Super promenade !','1','1','2');
INSERT INTO dog_avis VALUES ('2','4','Bien mais trop courte.','2','2','1');
INSERT INTO dog_avis VALUES ('3','5','Mon chien a adoré.','3','3','4');
INSERT INTO dog_avis VALUES ('4','4','Très agréable.','4','4','5');
INSERT INTO dog_avis VALUES ('5','5','Promeneur très gentil.','5','5','6');
INSERT INTO dog_avis VALUES ('6','0','Le promeneur à perdu mon chien ! >:(','31','4','1');

-- Structure de la table dog_chien
CREATE TABLE `dog_chien` (
  `id_chien` int NOT NULL,
  `nom_chien` varchar(50) NOT NULL,
  `poids` varchar(50) DEFAULT NULL,
  `taille` varchar(50) DEFAULT NULL,
  `race` varchar(50) DEFAULT NULL,
  `cheminPhoto` varchar(50) DEFAULT NULL,
  `id_utilisateur` int DEFAULT NULL,
  PRIMARY KEY (`id_chien`),
  KEY `id_utilisateur` (`id_utilisateur`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Données de la table dog_chien
INSERT INTO dog_chien VALUES ('1','Loukia','25 kg','Moyen','Golden Retriever','images/chien/loukia.jpg','1');
INSERT INTO dog_chien VALUES ('2','Bella','28 kg','Moyen','Labrador','images/chien/bella.jpg','2');
INSERT INTO dog_chien VALUES ('3','Max','12 kg','Petit','Beagle','images/chien/max.jpg','3');
INSERT INTO dog_chien VALUES ('4','Rocky','8 kg','Petit','Shih Tzu','images/chien/rocky.jpg','4');
INSERT INTO dog_chien VALUES ('5','Léo','18 kg','Moyen','Cocker','images/chien/leo.jpg','5');
INSERT INTO dog_chien VALUES ('6','Ruby','22 kg','Moyen','Border Collie','images/chien/ruby.jpg','6');
INSERT INTO dog_chien VALUES ('7','Charlie','5 kg','Très Petit','Chihuahua','images/chien/charlie.jpg','7');
INSERT INTO dog_chien VALUES ('8','Stella','40 kg','Très Grand','Dogue Allemand','images/chien/stella.jpg','8');
INSERT INTO dog_chien VALUES ('9','Rex','30 kg','Grand','Berger Allemand','images/chien/rex.jpg','35');
INSERT INTO dog_chien VALUES ('10','Milo','10 kg','Petit','Jack Russell','images/chien/milo.jpg','36');

-- Structure de la table dog_concerne
CREATE TABLE `dog_concerne` (
  `id_chien` int NOT NULL,
  `id_annonce` int NOT NULL,
  PRIMARY KEY (`id_chien`,`id_annonce`),
  KEY `id_annonce` (`id_annonce`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Données de la table dog_concerne
INSERT INTO dog_concerne VALUES ('1','0');
INSERT INTO dog_concerne VALUES ('9','0');
INSERT INTO dog_concerne VALUES ('10','0');
INSERT INTO dog_concerne VALUES ('1','1');
INSERT INTO dog_concerne VALUES ('2','2');
INSERT INTO dog_concerne VALUES ('3','3');
INSERT INTO dog_concerne VALUES ('4','4');
INSERT INTO dog_concerne VALUES ('5','5');
INSERT INTO dog_concerne VALUES ('6','6');
INSERT INTO dog_concerne VALUES ('7','7');
INSERT INTO dog_concerne VALUES ('8','8');
INSERT INTO dog_concerne VALUES ('9','9');
INSERT INTO dog_concerne VALUES ('10','10');
INSERT INTO dog_concerne VALUES ('10','11');
INSERT INTO dog_concerne VALUES ('1','12');
INSERT INTO dog_concerne VALUES ('1','15');
INSERT INTO dog_concerne VALUES ('1','16');
INSERT INTO dog_concerne VALUES ('1','17');
INSERT INTO dog_concerne VALUES ('1','18');
INSERT INTO dog_concerne VALUES ('1','19');
INSERT INTO dog_concerne VALUES ('1','20');
INSERT INTO dog_concerne VALUES ('1','21');
INSERT INTO dog_concerne VALUES ('1','22');
INSERT INTO dog_concerne VALUES ('1','23');
INSERT INTO dog_concerne VALUES ('1','24');
INSERT INTO dog_concerne VALUES ('10','25');
INSERT INTO dog_concerne VALUES ('9','26');
INSERT INTO dog_concerne VALUES ('10','27');
INSERT INTO dog_concerne VALUES ('9','28');
INSERT INTO dog_concerne VALUES ('9','29');

-- Structure de la table dog_conversation
CREATE TABLE `dog_conversation` (
  `id_conversation` int NOT NULL,
  `date_creation` varchar(50) NOT NULL,
  PRIMARY KEY (`id_conversation`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Données de la table dog_conversation
INSERT INTO dog_conversation VALUES ('1','2025-11-01');
INSERT INTO dog_conversation VALUES ('2','2025-11-02');
INSERT INTO dog_conversation VALUES ('3','2025-11-03');

-- Structure de la table dog_creer
CREATE TABLE `dog_creer` (
  `id_utilisateur` int NOT NULL,
  `id_conversation` int NOT NULL,
  PRIMARY KEY (`id_utilisateur`,`id_conversation`),
  KEY `id_conversation` (`id_conversation`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Données de la table dog_creer
INSERT INTO dog_creer VALUES ('1','1');
INSERT INTO dog_creer VALUES ('2','2');
INSERT INTO dog_creer VALUES ('3','3');

-- Structure de la table dog_message
CREATE TABLE `dog_message` (
  `id_message` int NOT NULL,
  `contenu` varchar(500) NOT NULL,
  `DateHeureMessage` datetime NOT NULL,
  `id_utilisateur` int DEFAULT NULL,
  `id_conversation` int DEFAULT NULL,
  PRIMARY KEY (`id_message`),
  KEY `id_utilisateur` (`id_utilisateur`),
  KEY `id_conversation` (`id_conversation`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Données de la table dog_message
INSERT INTO dog_message VALUES ('1','Bonjour, je suis intéressé par la promenade.','2025-11-01 10:00:00','1','1');
INSERT INTO dog_message VALUES ('2','Je vous confirme pour demain.','2025-11-01 10:05:00','2','1');
INSERT INTO dog_message VALUES ('3','Bonjour, disponible pour le samedi ?','2025-11-02 09:30:00','2','2');
INSERT INTO dog_message VALUES ('4','Oui, c’est parfait.','2025-11-02 09:35:00','1','2');
INSERT INTO dog_message VALUES ('5','ouais','2026-01-12 07:30:25','30','1');

-- Structure de la table dog_participe
CREATE TABLE `dog_participe` (
  `id_chien` int NOT NULL,
  `id_promenade` int NOT NULL,
  PRIMARY KEY (`id_chien`,`id_promenade`),
  KEY `id_promenade` (`id_promenade`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Données de la table dog_participe
INSERT INTO dog_participe VALUES ('1','1');
INSERT INTO dog_participe VALUES ('2','2');
INSERT INTO dog_participe VALUES ('3','3');
INSERT INTO dog_participe VALUES ('4','4');
INSERT INTO dog_participe VALUES ('5','5');
INSERT INTO dog_participe VALUES ('6','6');
INSERT INTO dog_participe VALUES ('7','7');
INSERT INTO dog_participe VALUES ('8','8');
INSERT INTO dog_participe VALUES ('9','9');
INSERT INTO dog_participe VALUES ('10','10');

-- Structure de la table dog_promenade
CREATE TABLE `dog_promenade` (
  `id_promenade` int NOT NULL,
  `statut` varchar(50) NOT NULL,
  `id_chien` int NOT NULL,
  `id_promeneur` int NOT NULL,
  `id_proprietaire` int NOT NULL,
  `id_annonce` int NOT NULL,
  PRIMARY KEY (`id_promenade`),
  KEY `id_chien` (`id_chien`),
  KEY `id_promeneur` (`id_promeneur`),
  KEY `id_proprietaire` (`id_proprietaire`),
  KEY `id_annonce` (`id_annonce`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Données de la table dog_promenade
INSERT INTO dog_promenade VALUES ('1','Active','7','16','6','3');
INSERT INTO dog_promenade VALUES ('2','Terminé','3','11','14','9');
INSERT INTO dog_promenade VALUES ('3','En cours','5','10','19','10');
INSERT INTO dog_promenade VALUES ('4','Active','6','11','14','23');
INSERT INTO dog_promenade VALUES ('5','En cours','5','18','1','12');
INSERT INTO dog_promenade VALUES ('6','Active','1','7','4','16');
INSERT INTO dog_promenade VALUES ('7','Terminé','10','6','7','18');
INSERT INTO dog_promenade VALUES ('8','Active','8','21','23','16');
INSERT INTO dog_promenade VALUES ('9','En cours','2','22','24','4');
INSERT INTO dog_promenade VALUES ('10','Active','2','22','21','17');

-- Structure de la table dog_répond
CREATE TABLE `dog_répond` (
  `id_annonce` int NOT NULL,
  `id_utilisateur` int NOT NULL,
  PRIMARY KEY (`id_annonce`,`id_utilisateur`),
  KEY `id_utilisateur` (`id_utilisateur`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Données de la table dog_répond
INSERT INTO dog_répond VALUES ('1','1');
INSERT INTO dog_répond VALUES ('2','2');
INSERT INTO dog_répond VALUES ('3','3');
INSERT INTO dog_répond VALUES ('4','4');
INSERT INTO dog_répond VALUES ('5','5');

-- Structure de la table dog_utilisateur
CREATE TABLE `dog_utilisateur` (
  `id_utilisateur` int NOT NULL AUTO_INCREMENT,
  `email` varchar(50) NOT NULL,
  `estMaitre` tinyint(1) NOT NULL,
  `estPromeneur` tinyint(1) NOT NULL,
  `adresse` varchar(50) DEFAULT NULL,
  `motDePasse` varchar(255) DEFAULT NULL,
  `pseudo` varchar(100) NOT NULL,
  `photoProfil` varchar(255) DEFAULT NULL,
  `numTelephone` varchar(50) DEFAULT NULL,
  `tentatives_echouees` int NOT NULL DEFAULT '0',
  `date_dernier_echec_connexion` datetime DEFAULT NULL,
  `statut_compte` enum('actif','desactive') NOT NULL DEFAULT 'actif',
  PRIMARY KEY (`id_utilisateur`),
  UNIQUE KEY `unique_email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Données de la table dog_utilisateur
INSERT INTO dog_utilisateur VALUES ('1','dupont.jean@example.com','1','1','123 Rue A, Paris','mdp1','Dupont_Jean',NULL,'0601020304','1','2026-01-12 08:13:43','actif');
INSERT INTO dog_utilisateur VALUES ('2','sophie.martin@example.com','0','1','45 Rue B, Lyon','mdp2','Martin_Sophie',NULL,'0602030405','0',NULL,'actif');
INSERT INTO dog_utilisateur VALUES ('3','paul.lemoine@example.com','1','0','78 Rue C, Marseille','mdp3','Lemoine_Paul',NULL,'0603040506','0',NULL,'actif');
INSERT INTO dog_utilisateur VALUES ('4','claire.leroy@example.com','1','1','12 Rue D, Lille','mdp4','Leroy_Claire',NULL,'0604050607','0',NULL,'actif');
INSERT INTO dog_utilisateur VALUES ('5','marie.fabre@example.com','0','1','34 Rue E, Nantes','mdp5','Fabre_Marie',NULL,'0605060708','0',NULL,'actif');
INSERT INTO dog_utilisateur VALUES ('6','lucas.giraud@example.com','1','1','56 Rue F, Bordeaux','mdp6','Giraud_Lucas',NULL,'0606070809','0',NULL,'actif');
INSERT INTO dog_utilisateur VALUES ('7','emma.robin@example.com','1','0','78 Rue G, Toulouse','mdp7','Robin_Emma',NULL,'0607080910','0',NULL,'actif');
INSERT INTO dog_utilisateur VALUES ('8','adam.moreau@example.com','0','1','90 Rue H, Strasbourg','mdp8','Moreau_Adam',NULL,'0608091011','0',NULL,'actif');
INSERT INTO dog_utilisateur VALUES ('9','julie.bertrand@example.com','1','1','21 Rue I, Nice','mdp9','Bertrand_Julie',NULL,'0609101112','0',NULL,'actif');
INSERT INTO dog_utilisateur VALUES ('10','thomas.durand@example.com','1','1','32 Rue J, Rennes','mdp10','Durand_Thomas',NULL,'0610111213','0',NULL,'actif');
INSERT INTO dog_utilisateur VALUES ('11','viclalanne64@gmail.com','1','0','118 Chemin de Basoilar','12345678','Lalanne_Victor',NULL,'0769951623','3','2025-12-13 19:45:19','desactive');
INSERT INTO dog_utilisateur VALUES ('12','victor64lalanne@gmail.com','1','0','118 Chemin de Basoilar','$2y$10$YMxbFHD9ojmOZV3byC7nxuqeTLe0gXASAy4M80FzSui','Lalanne_Victor',NULL,'0769951623','3','2025-12-12 12:30:33','desactive');
INSERT INTO dog_utilisateur VALUES ('13','vefvio@gmail.com','1','0','118 Chemin de Basoilar','$2y$10$H..NK33pN4h/6wxyvOz1pO6.ODTgqEbrDzvMWxMd0MM','Lalanne_Victor',NULL,'5569951623','0',NULL,'actif');
INSERT INTO dog_utilisateur VALUES ('14','vlalanne@gmail.com','1','0','118 Chemin de Basoilar','$2y$10$O4Z8RkYvX2fUc1VfA.iNkerCExqgkFh0B4r0q9rDU8B','Lalanne_Victor',NULL,'0769951623','0',NULL,'actif');
INSERT INTO dog_utilisateur VALUES ('15','jesaispas@gmail.com','1','0','118 Chemin de Basoilar','$2y$10$khcsaLRV1SFMd0Q8h5hepue7qJEpenTwKi9gnvJOmQr','Lalanne_Victor',NULL,'0769951623','3','2025-12-12 12:30:21','desactive');
INSERT INTO dog_utilisateur VALUES ('16','a@gmail.com','1','0','118 Chemin de Basoilar','$2y$10$1Gsus0BKlcBfxd1AnejJpO1rzeGtyY5pApC7oUl.NHr','Lalanne_Victor',NULL,'0769951623','1','2025-12-12 11:42:28','actif');
INSERT INTO dog_utilisateur VALUES ('17','b@gmail.com','0','1','118 Chemin de Basoilar','$2y$10$yuHAPKMdPxUBwA2ldlP3wOhIF6qScGhxFSR4nkczDqu','Lalanne_Victor',NULL,'0769951623','0',NULL,'actif');
INSERT INTO dog_utilisateur VALUES ('18','c@gmail.com','1','0','118 Chemin de Basoilar','$2y$10$bQpPT9StbFvWgWVg8N0JQ.kLzBu2VVFtqb8ao2fgsu8','Lalanne_Victor',NULL,'0769951623','0',NULL,'actif');
INSERT INTO dog_utilisateur VALUES ('19','vlalanne001@gmail.com','1','0','118 Chemin de Basoilar','$2y$10$VVvzd61bZe5zw2b.q3.Fj.NlTzPV66OcppB1vd05NH/','Lalanne_Victor',NULL,'0769951623','3','2025-12-12 12:57:28','desactive');
INSERT INTO dog_utilisateur VALUES ('20','d@gmail.com','1','0','118 Chemin de Basoilar','$2y$10$9VAnbM3xwZR9GepztwuJM.am/N8hnzVx7QNrcfkqPZ50FlWa9hfzG','Lalanne_Victor',NULL,'0769951623','0',NULL,'actif');
INSERT INTO dog_utilisateur VALUES ('21','v@gmail.com','0','1','118 Chemin de Basoilar','$2y$10$C/.d3k4iYYT43Fl4i6IDRumCvmwNI54Jc9I/BZYZHPnQqSoPBMKU.','Lalanne_Victor',NULL,'0769951623','3','2025-12-12 21:52:47','desactive');
INSERT INTO dog_utilisateur VALUES ('22','vl@gmail.com','0','1','118 Chemin de Basoilar','$2y$10$l9n8AaciI8prFJBej.C9QO2z0WNyKxefIOk8dW0aJsNg4OaSXWua.','Lalanne_Victor',NULL,'0769951623','1','2025-12-16 16:03:15','actif');
INSERT INTO dog_utilisateur VALUES ('23','vico@gmail.com','1','1','118 Chemin de Basoilar','$2y$10$Uf8jEqNfxTOgszhTMo33Bu24821PXbDwi6v7msdmgZm1pMjG/LJfW','Lalanne_Victor',NULL,'0769951623','3','2025-12-13 00:07:07','desactive');
INSERT INTO dog_utilisateur VALUES ('24','all@gmail.com','1','1','118 Chemin de Basoilar','$2y$10$K3015VQeF.G3sCJHdHatDur3O9AXU7Y9qJuTNKdK0N0JUpeXK5ov.','Lalanne_Victor',NULL,'0769951623','0',NULL,'actif');
INSERT INTO dog_utilisateur VALUES ('25','vicolalanne@gmail.com','1','0','118 Chemin de Basoilar','$2y$10$WPUviskXRx9zjj5fDFle1OghmVQdIQWbziFpqZqBljH/6uFDQmE3m','Vico',NULL,'0769951623','3','2025-12-15 14:03:23','desactive');
INSERT INTO dog_utilisateur VALUES ('26','oui.non@oui.fr','1','1','24 Chemin d''Arancette','$2y$12$Wc1v1bhYLzOLOw0I9MnZge/sot1bsL.jYzmHACz1rKTwJwGxKpyo2','Oui',NULL,'0102030405','1','2026-01-12 08:08:23','actif');
INSERT INTO dog_utilisateur VALUES ('27','r@gmail.com','1','1','LIEU DIT FAUREILLE','$2y$12$jykAMOrcAghOwE8FiS.YSuCi5gUGNEf7IvYmGpBF3Xcnh.hyXqkLm','robinboiss',NULL,'0621093184','0',NULL,'actif');
INSERT INTO dog_utilisateur VALUES ('28','robin@gmail.com','1','1','LIEU DIT FAUREILLE','$2y$12$B9G.F7oPTF6e64EchNHoxuG5sTIp9/fSpnDfMpAupYjPQZLMZa5r2','robinho',NULL,'0621093184','0',NULL,'actif');
INSERT INTO dog_utilisateur VALUES ('29','oui@oui.fr','1','1','aeg eza e','$2y$12$tK7BHrviOqUHDKACxPWciu3fAVxKneNk30BB65FvELhCCPy6GDF.C','aze',NULL,'0102030405','0',NULL,'actif');
INSERT INTO dog_utilisateur VALUES ('30','oui@oui.com','1','1','6 CHEMIN DES GRANDES TERRES','$2y$12$z64xVoSuudP7TsXYXpFbEee72tcSNHNHQfd4xTZFA5lnIED6QreMG','Oui',NULL,'0621093184','0',NULL,'actif');
INSERT INTO dog_utilisateur VALUES ('31','jcampistr001@iutbayonne.univ-pau.fr','1','1','Non','$2y$12$xSN/zq81ZxEW8Y6Sbj6CEudySHBzxZlZUY.axyaHq99VBU4yViFC.','Campistron_Julian',NULL,'0420690420','0',NULL,'actif');
INSERT INTO dog_utilisateur VALUES ('32','noah.leval@gmail.com','0','1','paris, trust','$2y$10$U97r2tICwD.dgybn60rRyOlnstu8G1LkJ8KEz53Y4IgtNadNPvNVe','noah promeneur',NULL,'0606060606','0',NULL,'actif');
INSERT INTO dog_utilisateur VALUES ('33','noah.leval33@gmail.com','1','0','paris, re trust','$2y$10$loIOcWeBWqinmM0GdELgXeeyu7ett6avbjxc93/8oG5LiPWnjJK4.','noah maitre',NULL,'0606060607','0',NULL,'actif');
INSERT INTO dog_utilisateur VALUES ('35','noah@example.com','1','1','tjr paris trust','$2y$10$y6bw.NcGr75HioMvvZTxve6FmLqpedDP2kT2I8dtEnbEhO.QUCzUm','noah les deux','35_noahlesdeux.png','0606060608','0',NULL,'actif');

