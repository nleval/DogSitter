<?php

// Ajout du code commun à toutes les pages
require_once 'include.php';

// Récupération des paramètres GET
$controllerName = isset($_GET['controleur']) ? $_GET['controleur'] : '';
$methode = isset($_GET['methode']) ? $_GET['methode'] : '';

// Page d'accueil par défaut
if ($controllerName == '' && $methode == '') {
    $controllerName = 'index';
    $methode = 'render';
}

// Création du contrôleur
$controller = ControllerFactory::getController($controllerName, $loader, $twig);

// Appel de la méthode
$controller->call($methode);
?>
