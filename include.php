<?php

//Ajout de l'autoload de composer
require_once 'vendor/autoload.php';

// Charger la classe Utilisateur avant d'ouvrir la session pour éviter des __PHP_Incomplete_Class lors de l'unserialize()
require_once 'modeles/Utilisateur.class.php';

// Démarrage de la session (disponible à travers toutes les pages)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Si jamais la session contient un objet incomplet (provenant d'une ancienne version), on le nettoie.
if (isset($_SESSION['user']) && is_object($_SESSION['user']) && get_class($_SESSION['user']) === '__PHP_Incomplete_Class') {
    unset($_SESSION['user']);
}

//Récupération des constantes
require_once 'config/init.php';

//Ajout du code pour initialiser twig
require_once 'config/twig.php';

//Ajout des contrôleurs
require_once 'controllers/controller_factory.class.php';
require_once 'controllers/controller.class.php';
require_once 'controllers/controller_index.class.php';
require_once 'controllers/controller_utilisateur.class.php';
require_once 'controllers/controller_avis.class.php';
require_once 'controllers/controller_promenade.class.php';
require_once 'controllers/controller_annonce.class.php';
require_once 'controllers/controller_chien.class.php';
require_once 'controllers/controller_message.class.php';
require_once 'controllers/controller_conversation.class.php';

//Ajout des modèles
require_once 'modeles/Bd.class.php';
require_once 'modeles/Utilisateur.dao.php';
require_once 'modeles/conversation.class.php';
require_once 'modeles/conversation.dao.php';
require_once 'modeles/message.class.php';
require_once 'modeles/message.dao.php';
require_once 'modeles/Avis.class.php';
require_once 'modeles/Avis.dao.php';
require_once 'modeles/Promenade.class.php';
require_once 'modeles/Promenade.dao.php';
require_once 'modeles/Annonce.class.php';
require_once 'modeles/Annonce.dao.php';
require_once 'modeles/conversation.class.php';
require_once 'modeles/conversation.dao.php';
require_once 'modeles/message.class.php';
require_once 'modeles/message.dao.php';
require_once 'modeles/chien.class.php';
require_once 'modeles/chien.dao.php';
require_once 'modeles/validator.class.php';

