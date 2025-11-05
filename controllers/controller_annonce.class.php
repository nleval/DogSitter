<?php

class ControllerAnnonce extends Controller
{
    public function __construct(\Twig\Environment $twig, \Twig\Loader\FilesystemLoader $loader)
    {
        parent::__construct($twig, $loader);
    }

    /**git 
     * Afficher une annonce spécifique
     */
    public function afficherAnnonce($id_annonce = 'ANNONCE001')
    {
        // Récupérer une annonce spécifique depuis la base de données
        $managerAnnonce = new AnnonceDAO($this->getPDO());
        $annonce = $managerAnnonce->findById($id_annonce);

        // Rendre la vue avec l'annonce
        $template = $this->getTwig()->load('annonce.html.twig');
        echo $template->render([
            'annonce' => $annonce
        ]);
    }

    /**
     * Afficher toutes les annonces
     */
    public function afficherAllAnnonces()
    {
        // Récupérer toutes les annonces depuis la base de données
        $managerAnnonce = new AnnonceDAO($this->getPDO());
        $annoncesListe = $managerAnnonce->findAll();

        // Rendre la vue avec la liste des annonces
        $template = $this->getTwig()->load('annonces.html.twig');
        echo $template->render([
            'annoncesListe' => $annoncesListe
        ]);
    }

    /**
     * Afficher toutes les annonces d’un utilisateur donné
     */
    public function afficherAnnoncesParUtilisateur($id_utilisateur = 'USER003')
    {
        $managerAnnonce = new AnnonceDAO($this->getPDO());
        $annoncesListe = $managerAnnonce->findByUtilisateur($id_utilisateur);

        $template = $this->getTwig()->load('annonces.html.twig');
        echo $template->render([
            'annoncesListe' => $annoncesListe,
            'id_utilisateur' => $id_utilisateur
        ]);
    }
}
