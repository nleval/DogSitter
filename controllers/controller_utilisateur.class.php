<?php

class ControllerUtilisateur extends Controller
{
    public function __construct(\Twig\Environment $twig, \Twig\Loader\FilesystemLoader $loader) {
        parent::__construct($twig, $loader);
    }

    public function afficherUtilisateur()
    {
        // Récupérer un utilisateur spécifique depuis la base de données
        $managerutilisateur = new UtilisateurDAO($this->getPDO());
        $utilisateur = $managerutilisateur->findById(1); // Exemple avec l'ID 1

        // Rendre la vue avec l'utilisateur
        $template = $this->getTwig()->load('utilisateurs.html.twig');
        echo $template->render([
            'utilisateur' => $utilisateur
        ]);
    }

    public function afficherAllUtilisateurs()
    {
        // Récupérer tous les utilisateurs depuis la base de données
        $managerutilisateur = new UtilisateurDAO($this->getPDO());
        $utilisateursListe = $managerutilisateur->findAll();

        // Rendre la vue avec les utilisateurs
        $template = $this->getTwig()->load('utilisateurs.html.twig');
        echo $template->render([
            'utilisateursListe' => $utilisateursListe
        ]);
    }
}