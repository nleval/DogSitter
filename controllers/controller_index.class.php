<?php
/**
 * @file controller_index.class.php
 * @brief Contrôleur pour la page d'accueil
 * @details Ce contrôleur gère l'affichage de la page d'accueil
 * @version 2.0
 * @date 2025-12-18
 * @author Léval Noah
 */
class ControllerIndex extends Controller {
    /**
     * @brief Constructeur du contrôleur de la page d'accueil
     * @param \Twig\Environment $twig Moteur de templates Twig.
     * @param \Twig\Loader\FilesystemLoader $loader Chargeur de templates Twig.
     */
    public function __construct(\Twig\Environment $twig, \Twig\Loader\FilesystemLoader $loader) {
        parent::__construct($twig, $loader);
    }

    /**
     * @brief Affiche la page d'accueil 
     * @return void
     */
    public function render() {
       $template = $this->getTwig()->load('index.html.twig');
       echo $template->render([
           'message' => 'Bienvenue sur la page d\'accueil !'
       ]);
    }
}
