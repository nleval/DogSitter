<?php

/**
 * @file controller_newsletter.class.php
 * @author Noah LÉVAL
 * @brief Controleur de la newsletter 
 * 
 * @version 1.0
 * @date 14/01/2026
 */
class ControllerNewsletter extends Controller
{

    /**
     * @brief Constructeur de la classe ControllerNewsletter
     *
     * @param \Twig\Environment $twig Envrironnement twig
     * @param \Twig\Loader\FilesystemLoader $loader loader de fichiers twig
     */
    public function __construct(\Twig\Environment $twig, \Twig\Loader\FilesystemLoader $loader)
    {
        parent::__construct($twig, $loader);
    }

    public function afficher()
    {
        $template = $this->getTwig()->load('newsletter.html.twig');
        echo $template->render(['newsletter' => 'Inscrivez vous à la neswletter de DogSitter !']);
    }
}