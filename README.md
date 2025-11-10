Plan de Configuration Initiale de l'Application Web

Ce document décrit les étapes essentielles pour l'installation des dépendances et la configuration initiale de l'environnement de templating.

1. Installation des Dépendances Composer

Exécutez les commandes suivantes pour installer les dépendances du projet et les extensions Twig nécessaires :

Installation des dépendances principales du projet :
    
    composer install

  

Ajout de Twig en tant que moteur de templating :
    
    composer require twig/twig

  

Ajout de l'extension Twig Intl (pour les fonctionnalités d'internationalisation) :

    composer require twig/intl-extra

      

2. Renommage des Fichiers de Configuration

Procédez au renommage du fichier de configuration des templates pour une meilleure sémantique et organisation du projet :

Renommer templates.yaml en constantes.yaml :
Cette étape vise à clarifier la nature du fichier, le désignant comme un référentiel pour les constantes de l'application plutôt qu'exclusivement pour la configuration des templates.
