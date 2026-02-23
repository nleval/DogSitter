<?php

/**
 * @file include.php
 * @brief Fichier d'initialisation centralisé - charge tous les includes nécessaires
 */

// Autoload Composer
require_once 'vendor/autoload.php';

// Charger Utilisateur avant la session pour éviter des erreurs d'unserialization
require_once 'modeles/Utilisateur.class.php';

// Démarrage de la session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Nettoyage de session si objet incomplet
if (isset($_SESSION['user']) && is_object($_SESSION['user']) && get_class($_SESSION['user']) === '__PHP_Incomplete_Class') {
    unset($_SESSION['user']);
}

// === CONFIGURATION ===
require_once 'config/init.php';
require_once 'config/twig.php';

// === CONTRÔLEURS ===
require_once 'controllers/controller_factory.class.php';
require_once 'controllers/controller.class.php';
require_once 'controllers/controller_index.class.php';
require_once 'controllers/controller_utilisateur.class.php';
require_once 'controllers/controller_avis.class.php';
require_once 'controllers/controller_annonce.class.php';
require_once 'controllers/controller_chien.class.php';
require_once 'controllers/controller_message.class.php';
require_once 'controllers/controller_conversation.class.php';
require_once 'controllers/controller_newsletter.class.php';

// === MODÈLES - Classes ===
require_once 'modeles/Bd.class.php';
require_once 'modeles/Utilisateur.class.php';
require_once 'modeles/Conversation.class.php';
require_once 'modeles/Message.class.php';
require_once 'modeles/Avis.class.php';
require_once 'modeles/Annonce.class.php';
require_once 'modeles/Chien.class.php';

// === MODÈLES - DAO ===
require_once 'modeles/Utilisateur.dao.php';
require_once 'modeles/Conversation.dao.php';
require_once 'modeles/Message.dao.php';
require_once 'modeles/Avis.dao.php';
require_once 'modeles/Annonce.dao.php';
require_once 'modeles/Chien.dao.php';
require_once 'modeles/Notification.dao.php';

// === UTILITAIRES ===
require_once 'modeles/Validator.class.php';