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

        // Récupérer le chien lié à cette promenade
        $chienDAO = new ChienDAO($this->getPDO()); // Assure-toi d'avoir un DAO pour les chiens
        $chien = $chienDAO->findById($promenade->getid_chien());

        // Récupérer le propriétaire
        $proprietaireDAO = new UtilisateurDAO($this->getPDO()); // Assure-toi d'avoir un DAO pour les propriétaires
        $proprietaire = $proprietaireDAO->findById($promenade->getid_proprietaire());

    
        //Récupérer l'annonce
        $annonceDAO = new AnnonceDAO($this->getPDO());
        $annonce = $annonceDAO->findById($promenade->getid_annonce()); // Il faut que cette méthode existe


        // Rendre la vue avec l'utilisateur
        $template = $this->getTwig()->load('promenade.html.twig');
        echo $template->render([
            'promenade' => $promenade,
            'chiens' => [$chien],       // on met le chien dans un tableau pour la boucle dans Twig
            'proprietaire' => $proprietaire,
            'annonce' => $annonce
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