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
    public function afficherAnnonce($id_annonce = null)
    {
        // Vérifie si l'identifiant de l'annonce ($id_annonce) n'a pas été reçu en tant qu'argument
        if(is_null($id_annonce) && isset($_GET['id_annonce'])) {

            // Si l'ID est manquant dans les arguments, on le récupère manuellement depuis les paramètres de la requête HTTP (URL).
            $id_annonce = filter_var($_GET['id_annonce'], FILTER_SANITIZE_NUMBER_INT);
        }
        // Récupérer une annonce spécifique depuis la base de données
        $managerAnnonce = new AnnonceDAO($this->getPDO());
        $annonce = $managerAnnonce->findById($id_annonce);

        $chienConcernes = [];
        if($annonce !== null) {

            $annonceId = $annonce->getIdAnnonce();
            // Récupérer les chiens concernés par cette annonce
            $managerChien = new ChienDAO($this->getPDO());
            $chienConcernes = $managerChien->findByAnnonce($annonceId);
        }

        // Rendre la vue avec l'annonce
        $template = $this->getTwig()->load('annonce.html.twig');
        echo $template->render([
            'annonce' => $annonce,
            'chiens' => $chienConcernes
            
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
    public function afficherAnnoncesParUtilisateur($id_utilisateur = 2)
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
