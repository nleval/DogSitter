DROP DATABASE IF EXISTS dogsitter;
CREATE DATABASE dogsitter CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE dogsitter;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;

-- =====================================
-- TABLE UTILISATEUR
-- =====================================

CREATE TABLE dog_Utilisateur (
  id_utilisateur INT NOT NULL AUTO_INCREMENT,
  email VARCHAR(50) NOT NULL,
  estMaitre TINYINT(1) NOT NULL,
  estPromeneur TINYINT(1) NOT NULL,
  adresse VARCHAR(50),
  motDePasse VARCHAR(255),
  pseudo VARCHAR(100) NOT NULL,
  photoProfil VARCHAR(255),
  numTelephone VARCHAR(50),
  tentatives_echouees INT DEFAULT 0,
  date_dernier_echec_connexion DATETIME,
  statut_compte ENUM('actif','desactive') DEFAULT 'actif',
  PRIMARY KEY (id_utilisateur),
  UNIQUE KEY unique_email (email)
) ENGINE=InnoDB;


-- =====================================
-- TABLE CHIEN
-- =====================================

CREATE TABLE dog_Chien (
  id_chien INT NOT NULL AUTO_INCREMENT,
  nom_chien VARCHAR(50) NOT NULL,
  poids VARCHAR(50),
  taille VARCHAR(50),
  race VARCHAR(50),
  cheminPhoto VARCHAR(50),
  id_utilisateur INT,
  PRIMARY KEY (id_chien),
  KEY (id_utilisateur),
  CONSTRAINT fk_chien_utilisateur
    FOREIGN KEY (id_utilisateur)
    REFERENCES dog_Utilisateur(id_utilisateur)
    ON DELETE CASCADE
) ENGINE=InnoDB;


-- =====================================
-- TABLE ANNONCE
-- =====================================

CREATE TABLE dog_Annonce (
  id_annonce INT NOT NULL AUTO_INCREMENT,
  datePromenade VARCHAR(50) NOT NULL,
  horaire VARCHAR(50) NOT NULL,
  status VARCHAR(50),
  tarif VARCHAR(50),
  description VARCHAR(500),
  endroitPromenade VARCHAR(50),
  duree INT NOT NULL,
  id_utilisateur INT,
  titre VARCHAR(255) NOT NULL DEFAULT '',
  PRIMARY KEY (id_annonce),
  KEY (id_utilisateur),
  CONSTRAINT fk_annonce_utilisateur
    FOREIGN KEY (id_utilisateur)
    REFERENCES dog_Utilisateur(id_utilisateur)
    ON DELETE SET NULL
) ENGINE=InnoDB;


-- =====================================
-- TABLE CONVERSATION
-- =====================================

CREATE TABLE dog_Conversation (
  id_conversation INT NOT NULL AUTO_INCREMENT,
  date_creation VARCHAR(50) NOT NULL,
  PRIMARY KEY (id_conversation)
) ENGINE=InnoDB;


-- =====================================
-- TABLE CREER
-- =====================================

CREATE TABLE dog_Creer (
  id_utilisateur INT NOT NULL,
  id_conversation INT NOT NULL,
  PRIMARY KEY (id_utilisateur,id_conversation),
  KEY (id_conversation),
  CONSTRAINT fk_creer_user
    FOREIGN KEY (id_utilisateur)
    REFERENCES dog_Utilisateur(id_utilisateur)
    ON DELETE CASCADE,
  CONSTRAINT fk_creer_conv
    FOREIGN KEY (id_conversation)
    REFERENCES dog_Conversation(id_conversation)
    ON DELETE CASCADE
) ENGINE=InnoDB;


-- =====================================
-- TABLE MESSAGE
-- =====================================

CREATE TABLE dog_Message (
  id_message INT NOT NULL AUTO_INCREMENT,
  contenu VARCHAR(500) NOT NULL,
  DateHeureMessage DATETIME NOT NULL,
  id_utilisateur INT,
  id_conversation INT,
  PRIMARY KEY (id_message),
  KEY (id_utilisateur),
  KEY (id_conversation),
  CONSTRAINT fk_message_user
    FOREIGN KEY (id_utilisateur)
    REFERENCES dog_Utilisateur(id_utilisateur)
    ON DELETE CASCADE,
  CONSTRAINT fk_message_conv
    FOREIGN KEY (id_conversation)
    REFERENCES dog_Conversation(id_conversation)
    ON DELETE CASCADE
) ENGINE=InnoDB;


-- =====================================
-- TABLE PROMENADE (MANQUANTE → AJOUTÉE)
-- =====================================

CREATE TABLE dog_Promenade (
  id_promenade INT NOT NULL AUTO_INCREMENT,
  id_chien INT,
  id_promeneur INT,
  id_proprietaire INT,
  id_annonce INT,
  date_promenade DATETIME,
  statut VARCHAR(50),
  PRIMARY KEY (id_promenade),
  KEY (id_chien),
  KEY (id_promeneur),
  KEY (id_proprietaire),
  KEY (id_annonce),

  CONSTRAINT fk_promenade_chien
    FOREIGN KEY (id_chien)
    REFERENCES dog_Chien(id_chien)
    ON DELETE SET NULL,

  CONSTRAINT fk_promenade_promeneur
    FOREIGN KEY (id_promeneur)
    REFERENCES dog_Utilisateur(id_utilisateur)
    ON DELETE SET NULL,

  CONSTRAINT fk_promenade_proprietaire
    FOREIGN KEY (id_proprietaire)
    REFERENCES dog_Utilisateur(id_utilisateur)
    ON DELETE SET NULL,

  CONSTRAINT fk_promenade_annonce
    FOREIGN KEY (id_annonce)
    REFERENCES dog_Annonce(id_annonce)
    ON DELETE SET NULL

) ENGINE=InnoDB;


-- =====================================
-- TABLE PARTICIPE
-- =====================================

CREATE TABLE dog_Participe (
  id_chien INT NOT NULL,
  id_promenade INT NOT NULL,
  PRIMARY KEY (id_chien,id_promenade),
  KEY (id_promenade),
  CONSTRAINT fk_participe_chien
    FOREIGN KEY (id_chien)
    REFERENCES dog_Chien(id_chien)
    ON DELETE CASCADE,
  CONSTRAINT fk_participe_promenade
    FOREIGN KEY (id_promenade)
    REFERENCES dog_Promenade(id_promenade)
    ON DELETE CASCADE
) ENGINE=InnoDB;


-- =====================================
-- TABLE CONCERNE
-- =====================================

CREATE TABLE dog_Concerne (
  id_chien INT NOT NULL,
  id_annonce INT NOT NULL,
  PRIMARY KEY (id_chien,id_annonce),
  KEY (id_annonce),
  CONSTRAINT fk_concerne_chien
    FOREIGN KEY (id_chien)
    REFERENCES dog_Chien(id_chien)
    ON DELETE CASCADE,
  CONSTRAINT fk_concerne_annonce
    FOREIGN KEY (id_annonce)
    REFERENCES dog_Annonce(id_annonce)
    ON DELETE CASCADE
) ENGINE=InnoDB;


-- =====================================
-- TABLE AVIS
-- =====================================

CREATE TABLE dog_Avis (
  id_avis INT NOT NULL AUTO_INCREMENT,
  note VARCHAR(50) NOT NULL,
  texte_commentaire VARCHAR(50),
  id_utilisateur INT,
  id_promenade INT,
  id_utilisateur_note INT,
  PRIMARY KEY (id_avis),
  KEY (id_utilisateur),
  KEY (id_promenade),
  KEY (id_utilisateur_note),

  CONSTRAINT fk_avis_user
    FOREIGN KEY (id_utilisateur)
    REFERENCES dog_Utilisateur(id_utilisateur)
    ON DELETE CASCADE,

  CONSTRAINT fk_avis_promenade
    FOREIGN KEY (id_promenade)
    REFERENCES dog_Promenade(id_promenade)
    ON DELETE CASCADE

) ENGINE=InnoDB;


-- =====================================
-- TABLE REPOND (CORRIGÉE)
-- =====================================

CREATE TABLE `dog_Repond` (
  `id_reponse` INT AUTO_INCREMENT PRIMARY KEY,
  `id_annonce` INT NOT NULL,
  `id_utilisateur` INT NOT NULL,
  `statut` ENUM('en_attente','acceptee','refusee') DEFAULT 'en_attente',
  `date_creation` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `date_modification` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  UNIQUE KEY `unique_reponse` (`id_annonce`,`id_utilisateur`),

  CONSTRAINT `fk_repond_annonce`
    FOREIGN KEY (`id_annonce`)
    REFERENCES `dog_Annonce`(`id_annonce`)
    ON DELETE CASCADE,

  CONSTRAINT `fk_repond_user`
    FOREIGN KEY (`id_utilisateur`)
    REFERENCES `dog_Utilisateur`(`id_utilisateur`)
    ON DELETE CASCADE

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- =====================================
-- TABLE NOTIFICATION
-- =====================================

CREATE TABLE `dog_Notification` (
  `id_notification` INT AUTO_INCREMENT PRIMARY KEY,
  `id_utilisateur` INT NOT NULL,
  `titre` VARCHAR(255) NOT NULL,
  `message` TEXT NOT NULL,
  `type` ENUM('candidature_soumise','candidature_reçue','candidature_acceptée','candidature_refusée','info') DEFAULT 'info',
  `id_annonce` INT,
  `id_reponse` INT,
  `id_promeneur` INT,
  `lue` TINYINT(1) DEFAULT 0,
  `date_creation` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  INDEX `idx_utilisateur` (`id_utilisateur`),
  INDEX `idx_non_lue` (`lue`),

  CONSTRAINT `fk_notif_user`
    FOREIGN KEY (`id_utilisateur`)
    REFERENCES `dog_Utilisateur`(`id_utilisateur`)
    ON DELETE CASCADE,

  CONSTRAINT `fk_notif_annonce`
    FOREIGN KEY (`id_annonce`)
    REFERENCES `dog_Annonce`(`id_annonce`)
    ON DELETE CASCADE,

  CONSTRAINT `fk_notif_reponse`
    FOREIGN KEY (`id_reponse`)
    REFERENCES `dog_Repond`(`id_reponse`)
    ON DELETE CASCADE,

  CONSTRAINT `fk_notif_promeneur`
    FOREIGN KEY (`id_promeneur`)
    REFERENCES `dog_Utilisateur`(`id_utilisateur`)
    ON DELETE CASCADE

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


COMMIT;
