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


public function creerAnnonce()
{
    // A DECOMMENTER QUAND LA GESTION DES SESSIONS SERA EN PLACE

    // session_start();
    // $id_utilisateur = $_SESSION['id_utilisateur'] ?? null;

    // if (!$id_utilisateur) {
    //     die("Vous devez être connecté pour créer une annonce.");
    // }

    // $managerUtilisateur = new UtilisateurDAO($this->getPDO());
    // $utilisateur = $managerUtilisateur->findById($id_utilisateur);

    // if(!$utilisateur || !$utilisateur->getEstMaitre()) {
    //     die("Seuls les utilisateurs avec le rôle 'maître' peuvent créer des annonces.");
    // }

    // FORMULAIRE ENVOYÉ
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $titre = $_POST['titre'] ?? null;
        $datePromenade = $_POST['datePromenade'] ?? null;
        $horaire = $_POST['horaire'] ?? null;
        $status = $_POST['status'] ?? 'Disponible';
        $tarif = $_POST['tarif'] ?? null;
        $description = $_POST['description'] ?? null;
        $endroitPromenade = $_POST['endroitPromenade'] ?? null;
        $duree = $_POST['duree'] ?? null;
        $chiens = $_POST['chiens'] ?? [];


        $regles = [
            'titre' => [
                'obligatoire' => true,
                'type' => 'string',
                'longueur_min' => 10,
                'longueur_max' => 100
            ],
            'datePromenade' => [
                'obligatoire' => true,
                'format' => '/^\d{4}-\d{2}-\d{2}$/'
            ],
            'horaire' => [
                'obligatoire' => true,
                'format' => '/^\d{2}:\d{2}$/'
            ],
            'duree' => [
                'obligatoire' => true,
                'type' => 'numeric',
                'plage_min' => 15
            ],
            'tarif' => [
                'obligatoire' => true,
                'type' => 'numeric',
                'plage_min' => 1
            ],
            'endroitPromenade' => [
                'obligatoire' => false,
                'type' => 'string',
                'longueur_max' => 255
            ],
            'description' => [
                'obligatoire' => false,
                'type' => 'string',
                'longueur_max' => 500
            ],
            'chiens' => [
                'obligatoire' => false    // géré manuellement ensuite
            ]
        ];

        $validator = new Validator($regles);
        $valide = $validator->valider($_POST);
        $erreurs = $validator->getMessagesErreurs();

        // Validation manuelle des chiens
        if (empty($chiens) || !is_array($chiens)) {
            $erreurs[] = "Vous devez sélectionner au moins un chien.";
            $valide = false;
        }
     
          // SI ERREURS → on réaffiche le formulaire
     
        if (!$valide) {
            $managerChien = new ChienDAO($this->getPDO());
            $chiensUtilisateur = $managerChien->findAll(); // À remplacer par une méthode filtrant par utilisateur quand la connexion sera implémentée

            $template = $this->getTwig()->load('creer_annonce.html.twig');
            echo $template->render([
                'erreurs' => $erreurs,
                'donnees' => $_POST,          
                'chiens' => $chiensUtilisateur
            ]);
            return;
        }


        $pdo = $this->getPDO();

        // INSERT annonce
        $stmt = $pdo->prepare("
            INSERT INTO " . PREFIXE_TABLE . "Annonce 
            (titre, datePromenade, horaire, status, tarif, description, endroitPromenade, duree, id_utilisateur)
            VALUES (:titre, :datePromenade, :horaire, :status, :tarif, :description, :endroitPromenade, :duree, :id_utilisateur)
        ");

        $stmt->execute([
            ':titre' => $titre,
            ':datePromenade' => $datePromenade,
            ':horaire' => $horaire,
            ':status' => $status,
            ':tarif' => $tarif,
            ':description' => $description,
            ':endroitPromenade' => $endroitPromenade,
            ':duree' => $duree,
            // ':id_utilisateur' => $id_utilisateur  // A DECOMMENTER QUAND LA GESTION DES SESSIONS SERA EN PLACE
        ]);

        $id_annonce = $pdo->lastInsertId();

        // INSERT chiens associés
        if (!empty($chiens)) {
            $stmtChien = $pdo->prepare("
                INSERT INTO " . PREFIXE_TABLE . "concerne (id_annonce, id_chien)
                VALUES (:id_annonce, :id_chien)
            ");

            foreach ($chiens as $id_chien) {
                $stmtChien->execute([
                    ':id_annonce' => $id_annonce,
                    ':id_chien' => $id_chien
                ]);
            }
        }

        echo "Annonce créée avec succès.";
        return;
    }

    // AFFICHAGE DU FORMULAIRE
    $managerChien = new ChienDAO($this->getPDO());
    $chiensUtilisateur = $managerChien->findAll(); // À remplacer par une méthode filtrant par utilisateur quand la connexion sera implémentée

    $template = $this->getTwig()->load('creer_annonce.html.twig');
    echo $template->render([
        'chiens' => $chiensUtilisateur,

    ]);
}

}



