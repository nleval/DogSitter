<?php

//Ajout de l'autoload de composer
require_once 'vendor/autoload.php';

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

//Ajout des modèles
require_once 'modeles/Bd.class.php';
require_once 'modeles/Utilisateur.class.php';
require_once 'modeles/Utilisateur.dao.php';
require_once 'modeles/Avis.class.php';
require_once 'modeles/Avis.dao.php';
require_once 'modeles/Promenade.class.php';
require_once 'modeles/Promenade.dao.php';
require_once 'modeles/Annonce.class.php';
require_once 'modeles/Annonce.dao.php';
require_once 'modeles/chien.class.php';
require_once 'modeles/chien.dao.php';
