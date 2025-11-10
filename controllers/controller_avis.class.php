<?php

class ControllerAvis extends Controller
{
    public function __construct(\Twig\Environment $twig, \Twig\Loader\FilesystemLoader $loader) {
        parent::__construct($twig, $loader);
    }

    public function afficherAvis()
    {
        // Récupérer un avis spécifique depuis la base de données
        $manageravis = new AvisDAO($this->getPDO());
        $avis = $manageravis->findById(1); // Exemple avec l'ID 1

        // Rendre la vue avec l'avis
        $template = $this->getTwig()->load('avis.html.twig');
        echo $template->render([
            'avis' => $avis
        ]);
    }

    public function afficherAllAvis()
    {
        // Récupérer tous les avis depuis la base de données
        $manageravis = new AvisDAO($this->getPDO());
        $avisListe = $manageravis->findAll();

        // Rendre la vue avec les avis
        $template = $this->getTwig()->load('avis.html.twig');
        echo $template->render([
            'avisListe' => $avisListe
        ]);
    }
}