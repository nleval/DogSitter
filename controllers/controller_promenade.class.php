<?php

class ControllerPromenade extends Controller
{
    public function __construct(\Twig\Environment $twig, \Twig\Loader\FilesystemLoader $loader) {
        parent::__construct($twig, $loader);
    }

    public function afficherPromenade()
    {
        // Récupérer une spécifique depuis la base de données
        $managerpromenade = new PromenadeDAO($this->getPDO());
        $promenade = $managerpromenade->findById(1); // Exemple avec l'ID 1

        // Rendre la vue avec l'utilisateur
        $template = $this->getTwig()->load('promenade.html.twig');
        echo $template->render([
            'promenade' => $promenade
        ]);
    }

    public function afficherAllPromenades()
    {
        // Récupérer tous les utilisateurs depuis la base de données
        $managerpromenade = new PromenadeDAO($this->getPDO());
        $promenadesListe = $managerpromenade->findAll();

        // Rendre la vue avec les utilisateurs
        $template = $this->getTwig()->load('promenade.html.twig');
        echo $template->render([
            'promenadesListe' => $promenadesListe
        ]);
    }
}