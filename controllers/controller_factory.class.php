<?php
/**
 * @file controller_factory.class.php
 * @author Léval Noah
 * @brief Factory pour les contrôleurs
 * @details Cette classe permet de créer un contrôleur en fonction de son nom
 * @version 1.0
 * @date 2025-12-18
 */
class ControllerFactory
{
    /**
     * @brief Permet de récupérer une instance de contrôleur en fonction de son nom
     * @param string $controleur Nom du contrôleur à instancier.
     * @param \Twig\Environment $twig Moteur de templates Twig.
     * @param \Twig\Loader\FilesystemLoader $loader Chargeur de templates Twig.
     */
    public static function getController($controleur, \Twig\Loader\FilesystemLoader $loader, \Twig\Environment $twig)
    {
        $controllerName="Controller".ucfirst($controleur);
        
        if (!class_exists($controllerName)) {
            throw new Exception("Le controleur $controllerName n'existe pas");
        }
        return new $controllerName($twig, $loader);

    }
}