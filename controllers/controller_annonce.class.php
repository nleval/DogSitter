<?php
/**
 * @file controller_annonce.class.php
 * @author Lalanne Victor
 * @brief Gère les opérations liées aux annonces
 * @version 1.0
 * @date 2025-12-18
 */
class ControllerAnnonce extends Controller
{
    
    /**
     * @brief Constructeur du contrôleur d'annonce.
     * @param \Twig\Environment $twig Moteur de templates Twig.
     * @param \Twig\Loader\FilesystemLoader $loader Chargeur de templates Twig.
     */
    public function __construct(\Twig\Environment $twig, \Twig\Loader\FilesystemLoader $loader)
    {
        parent::__construct($twig, $loader);
    }

    /**
     * @brief Afficher une annonce spécifique
     * @param int $id_annonce Identifiant de l'annonce à afficher
     */
    public function afficherAnnonce($id_annonce = null)
    {
        if (!isset($_SESSION['utilisateur'])) {
                header('Location: index.php?controleur=utilisateur&methode=authentification');
                exit();
            }

        // Vérifie si l'identifiant de l'annonce ($id_annonce) n'a pas été reçu en tant qu'argument
        if($id_annonce === null) {
            if (isset($_GET['id_annonce'])) {
                $id_annonce = (int) $_GET['id_annonce'];
            }
            else {
                 http_response_code(404);
                 $template = $this->getTwig()->load('404.html.twig');
                 echo $template->render(['message' => 'Annonce non trouvée.']);
                 return;           
                 }
        }

        // Récupérer une annonce spécifique depuis la base de données
        $managerAnnonce = new AnnonceDAO($this->getPDO());
        $annonce = $managerAnnonce->findById($id_annonce);

        $chienConcernes = [];
        $proprietaire = null;

        if($annonce !== null) {

            // Récupérer les chiens concernés par cette annonce
            $managerChien = new ChienDAO($this->getPDO());
            $chienConcernes = $managerChien->findByAnnonce($annonce->getIdAnnonce());

            $managerUtilisateur = new UtilisateurDAO($this->getPDO()); 
            $proprietaire = $managerUtilisateur->findById($annonce->getIdUtilisateur());
        }

        // Rendre la vue avec l'annonce
        $template = $this->getTwig()->load('annonce.html.twig');
        echo $template->render([
            'annonce' => $annonce,
            'chiens' => $chienConcernes,
            'proprietaire' => $proprietaire,
            'userConnecte' => $sessionUser
            
        ]);
    }

    /**
     * @brief Afficher toutes les annonces
     */
    public function afficherAllAnnonces()
    {    
        // Récupérer toutes les annonces depuis la base de données
        $managerAnnonce = new AnnonceDAO($this->getPDO());
        $annoncesListe = $managerAnnonce->findAll();

        $managerUtilisateur = new UtilisateurDAO($this->getPDO());
        $annoncesEnrichies = [];

        foreach ($annoncesListe as $annonce) {
            
            // Récupérer l'objet Utilisateur
            $utilisateur = $managerUtilisateur->findById($annonce->getIdUtilisateur());

            $annonce->setTelephone($utilisateur ? $utilisateur->getNumTelephone() : 'N/A');
            $annoncesEnrichies[] = $annonce;
        }
            
        // Rendre la vue avec la liste des annonces
        $template = $this->getTwig()->load('annonces.html.twig');
        echo $template->render([
            'annoncesListe' => $annoncesEnrichies
        ]);
        
    }

    /**
     * @brief Afficher toutes les annonces d’un utilisateur donné
     */
    public function afficherAnnoncesParUtilisateur()
    {
        if (!isset($_SESSION['utilisateur'])) {
                header('Location: index.php?controleur=utilisateur&methode=authentification');
                exit();
            }

        $managerAnnonce = new AnnonceDAO($this->getPDO());
        $id_utilisateur = $_GET['id_utilisateur'] ?? null;

        $annoncesListe = $managerAnnonce->findByUtilisateur($id_utilisateur);            

        $template = $this->getTwig()->load('annonces_par_utilisateur.html.twig');
        echo $template->render([
            'annoncesListe' => $annoncesListe,
            'id_utilisateur' => $id_utilisateur
        ]);
    }

    /**
     * @brief Creer une annonce
     */
    public function creerAnnonce()
    {
        if (!isset($_SESSION['utilisateur'])) {
                header('Location: index.php?controleur=utilisateur&methode=authentification');
                exit();
            }

        $utilisateurConnecte = unserialize($_SESSION['utilisateur']);
        $id_utilisateur = $utilisateurConnecte->getId();

        $managerUtilisateur = new UtilisateurDAO($this->getPDO());        
        $utilisateur = $managerUtilisateur->findById($id_utilisateur);

        if (!$utilisateur || !$utilisateur->getEstMaitre()) {
            http_response_code(403); 
            $template = $this->getTwig()->load('403.html.twig');
            echo $template->render(['message' => "Seuls les utilisateurs avec le rôle 'maître' peuvent créer des annonces."]);
            return;
        }

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
                $chiensUtilisateur = $managerChien->findByUtilisateur($id_utilisateur); 

                $template = $this->getTwig()->load('creer_annonce.html.twig');
                echo $template->render([
                    'erreurs' => $erreurs,
                    'donnees' => $_POST,          
                    'chiens' => $chiensUtilisateur
                ]);
                return;
            }


            $pdo = $this->getPDO();

                $annonce = new Annonce(
                null,                     // id_annonce (auto-increment)
                $titre,
                $datePromenade,
                $horaire,
                $status,
                $tarif,
                $description,
                $endroitPromenade,
                $duree,
                $id_utilisateur
                );


            // INSERT annonce
            $managerAnnonce = new AnnonceDAO($this->getPDO());
            $managerAnnonce->ajouterAnnonce($annonce);



            $id_annonce = $pdo->lastInsertId();

            // INSERT chiens associés
            if (!empty($chiens)) {
                $stmtChien = $pdo->prepare("
                    INSERT INTO " . PREFIXE_TABLE . "Concerne (id_annonce, id_chien)
                    VALUES (:id_annonce, :id_chien)
                ");

                foreach ($chiens as $id_chien) {
                    $stmtChien->execute([
                        ':id_annonce' => $id_annonce,
                        ':id_chien' => $id_chien
                    ]);
                }
            }

            // Redirection vers un popup de confirmation
            header('Location: index.php?controleur=annonce&methode=confirmationCreationAnnonce');
            exit();
            
        }

        // AFFICHAGE DU FORMULAIRE
        $managerChien = new ChienDAO($this->getPDO());
        $chiensUtilisateur = $managerChien->findByUtilisateur($id_utilisateur); 

        $template = $this->getTwig()->load('creer_annonce.html.twig');
        echo $template->render([
            'chiens' => $chiensUtilisateur,

        ]);
    }

    /**
     * @brief Confirme la création d'une annonce
     */
    public function confirmationCreationAnnonce()
    {
        $template = $this->getTwig()->load('confirmation_creation_annonce.html.twig');
        echo $template->render();

    }

    /**
     * @brief Supprimer une annonce
     */
    public function supprimerAnnonce()
    {
        if (!isset($_SESSION['utilisateur'])) {
                header('Location: index.php?controleur=utilisateur&methode=authentification');
                exit();
            }

        $id_annonce = $_GET['id_annonce'] ?? null;

        $managerAnnonce = new AnnonceDAO($this->getPDO());
        $annonce = $managerAnnonce->findById($id_annonce);

        if (!$annonce || $annonce->getIdUtilisateur() != $id_utilisateur) {
            $template = $this->getTwig()->load('403.html.twig');
            echo $template->render(['message' => "Vous n'êtes pas autorisé à supprimer cette annonce."]);
            return;
        }

        $managerAnnonce->supprimerAnnonce($id_annonce);

        header('Location: index.php?controleur=annonce&methode=afficherAnnoncesParUtilisateur');
        exit();
    }

    /**
     * @brief Modifier une annonce spécifique
     * @param int $id_annonce Identifiant de l'annonce à afficher
     */
    public function modifierAnnonce($id_annonce = null)
    {
        if (!isset($_SESSION['utilisateur'])) {
                header('Location: index.php?controleur=utilisateur&methode=authentification');
                exit();
            }

        // ---------- ID ANNONCE ----------
        if ($id_annonce === null) {
            $id_annonce = $_GET['id_annonce'] ?? null;
        }

        if (!$id_annonce) {
            http_response_code(404);
            echo $this->getTwig()->render('404.html.twig');
            return;
        }

        // ---------- ANNONCE ----------
        $managerAnnonce = new AnnonceDAO($this->getPDO());
        $annonce = $managerAnnonce->findById($id_annonce);

        if (!$annonce || $annonce->getIdUtilisateur() != $id_utilisateur) {
            http_response_code(403);
            echo $this->getTwig()->render('403.html.twig', [
                'message' => "Accès interdit."
            ]);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

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
                ]
            ];

            $validator = new Validator($regles);
            $valide = $validator->valider($_POST);
            $erreurs = $validator->getMessagesErreurs();

            if (!$valide) {
                echo $this->getTwig()->render('modifier_annonce.html.twig', [
                    'annonce' => $annonce,
                    'erreurs' => $erreurs,
                    'donnees' => $_POST
                ]);
                return;
            }

            $managerAnnonce->modifierChamp($id_annonce, 'titre', $_POST['titre']);
            $managerAnnonce->modifierChamp($id_annonce, 'datePromenade', $_POST['datePromenade']);
            $managerAnnonce->modifierChamp($id_annonce, 'horaire', $_POST['horaire']);
            $managerAnnonce->modifierChamp($id_annonce, 'duree', $_POST['duree']);
            $managerAnnonce->modifierChamp($id_annonce, 'tarif', $_POST['tarif']);
            $managerAnnonce->modifierChamp($id_annonce, 'endroitPromenade', $_POST['endroitPromenade']);
            $managerAnnonce->modifierChamp($id_annonce, 'description', $_POST['description']);

            header('Location: index.php?controleur=annonce&methode=afficherAnnonce&id_annonce=' . $id_annonce);
            exit();
        }

        echo $this->getTwig()->render('modifier_annonce.html.twig', [
            'annonce' => $annonce
        ]);
    }




}
