<?php

class ControllerChien extends Controller
{
    public function __construct(\Twig\Environment $twig, \Twig\Loader\FilesystemLoader $loader) {
        parent::__construct($twig, $loader);
    }

    public function afficherChien()
    {
        // Récupérer un chien spécifique depuis la base de données
        $managerchien = new ChienDAO($this->getPDO());
        $chien = $managerchien->find(1); // Exemple avec l'ID 1

        // Rendre la vue avec le chien
        $template = $this->getTwig()->load('chiens.html.twig');
        echo $template->render([
            'chien' => $chien
        ]);
    }

    public function afficherAllChiens()
    {
        // Récupérer tous les chiens depuis la base de données
        $managerchien = new ChienDAO($this->getPDO());
        $chiensListe = $managerchien->findAll();

        // Rendre la vue avec les chiens
        $template = $this->getTwig()->load('chiens.html.twig');
        echo $template->render([
            'chiensListe' => $chiensListe
        ]);
    }
}

?>