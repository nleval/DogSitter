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

            $managerUtilisateur = new UtilisateurDAO($this->getPDO()); // Assurez-vous que le DAO existe
            $proprietaire = $managerUtilisateur->findById($annonce->getIdUtilisateur());
        }

        // Rendre la vue avec l'annonce
        $template = $this->getTwig()->load('annonce.html.twig');
        echo $template->render([
            'annonce' => $annonce,
            'chiens' => $chienConcernes,
            'proprietaire' => $proprietaire
            
        ]);
    }

    /**
     * Afficher toutes les annonces
     */
    public function afficherAllAnnonces(){
    
        // Récupérer toutes les annonces depuis la base de données
        $managerAnnonce = new AnnonceDAO($this->getPDO());
        $annoncesListe = $managerAnnonce->findAll();

        $managerUtilisateur = new UtilisateurDAO($this->getPDO());
        $annoncesEnrichies = [];
        foreach ($annoncesListe as $annonce) {
            $idUtilisateur = $annonce->getIdUtilisateur(); 
            
            // Récupérer l'objet Utilisateur
            $utilisateur = $managerUtilisateur->findById($idUtilisateur);

            // Ajouter le numéro de téléphone à l'objet Annonce
            if ($utilisateur !== null) {
                // Créer une nouvelle propriété 'telephone' sur l'objet Annonce pour Twig
                $annonce->telephone = $utilisateur->getNumTelephone();
            } else {
                $annonce->telephone = 'N/A'; // Valeur par défaut si l'utilisateur n'est pas trouvé
            }

                $annoncesEnrichies[] = $annonce;
        }
            
            
        // Rendre la vue avec la liste des annonces
        $template = $this->getTwig()->load('annonces.html.twig');
        echo $template->render([
            'annoncesListe' => $annoncesEnrichies
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
