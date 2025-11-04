<?php

class ControllerIndex extends Controller {

    public function __construct(\Twig\Environment $twig, \Twig\Loader\FilesystemLoader $loader) {
        parent::__construct($twig, $loader);
    }

    public function render() {
       $template = $this->getTwig()->load('index.html.twig');
       echo $template->render([
           'message' => 'Bienvenue sur la page d\'accueil !'
       ]);
    }
}
