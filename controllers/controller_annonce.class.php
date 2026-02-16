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

            $sessionUser = unserialize($_SESSION['utilisateur']);


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

        $acceptedCandidatId = null;

        if($annonce !== null) {

            // Récupérer les chiens concernés par cette annonce
            $managerChien = new ChienDAO($this->getPDO());
            $chienConcernes = $managerChien->findByAnnonce($annonce->getIdAnnonce());

            $managerUtilisateur = new UtilisateurDAO($this->getPDO()); 
            $proprietaire = $managerUtilisateur->findById($annonce->getIdUtilisateur());

            $acceptedCandidatId = $managerAnnonce->getCandidatAccepte($annonce->getIdAnnonce());
        }

        // Rendre la vue avec l'annonce
        $template = $this->getTwig()->load('annonce.html.twig');
        
        $avisPromenade = [];
        $idPromenade = null;

        if ($annonce !== null && $acceptedCandidatId) {
            $idPromenade = $managerAnnonce->getPromenadeIdByAnnonceAndPromeneur(
                $annonce->getIdAnnonce(),
                $acceptedCandidatId
            );

            if ($idPromenade) {
                $managerAvis = new AvisDAO($this->getPDO());
                $avisPromenade = $managerAvis->trouverParIdPromenade($idPromenade);
            }
        }
        
        echo $template->render([
            'annonce' => $annonce,
            'chiens' => $chienConcernes,
            'proprietaire' => $proprietaire,
            'userConnecte' => $sessionUser,
            'reponse' => $_GET['reponse'] ?? null,
            'avisPromenade' => $avisPromenade,
            'acceptedCandidatId' => $acceptedCandidatId
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
            header('Location: index.php?controleur=Annonce&methode=confirmationCreationAnnonce');
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

        $utilisateurConnecte = unserialize($_SESSION['utilisateur']);
        $id_utilisateur = $utilisateurConnecte->getId();

        $id_annonce = $_GET['id_annonce'] ?? null;

        $managerAnnonce = new AnnonceDAO($this->getPDO());
        $annonce = $managerAnnonce->findById($id_annonce);

        if (!$annonce || $annonce->getIdUtilisateur() != $id_utilisateur) {
            $template = $this->getTwig()->load('403.html.twig');
            echo $template->render(['message' => "Vous n'êtes pas autorisé à supprimer cette annonce."]);
            return;
        }

        $managerAnnonce->supprimerAnnonce($id_annonce);

        header('Location: index.php?controleur=Annonce&methode=afficherAnnoncesParUtilisateur&id_utilisateur=' . $id_utilisateur);
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

        $utilisateurConnecte = unserialize($_SESSION['utilisateur']);
        $id_utilisateur = $utilisateurConnecte->getId();

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

    /**
 * @brief Permet à un utilisateur de répondre à une annonce
 * @param int $id_annonce Identifiant de l'annonce
 */
public function repondreAnnonce($id_annonce = null)
{
    if (!isset($_SESSION['utilisateur'])) {
        header('Location: index.php?controleur=utilisateur&methode=authentification');
        exit();
    }

    $sessionUser = unserialize($_SESSION['utilisateur']);
    $id_utilisateur = $sessionUser->getId();

    // Vérifier que l'utilisateur est promeneur
    if (!$sessionUser->getEstPromeneur()) {
        http_response_code(403);
        echo $this->getTwig()->render('403.html.twig', [
            'message' => "Seuls les promeneurs peuvent postuler aux annonces."
        ]);
        return;
    }

    // Récupérer l'id de l'annonce depuis GET si non fourni
    if ($id_annonce === null) {
        $id_annonce = $_GET['id_annonce'] ?? null;
    }

    if (!$id_annonce) {
        http_response_code(404);
        echo $this->getTwig()->render('404.html.twig', ['message' => 'Annonce non trouvée.']);
        return;
    }

    // Vérifier que l'annonce existe
    $managerAnnonce = new AnnonceDAO($this->getPDO());
    $annonce = $managerAnnonce->findById($id_annonce);

    if (!$annonce) {
        http_response_code(404);
        echo $this->getTwig()->render('404.html.twig', ['message' => 'Annonce non trouvée.']);
        return;
    }

    // VÉRIFICATION IMPORTANTE: Vérifier que l'utilisateur n'est pas le propriétaire de l'annonce
    if ($annonce->getIdUtilisateur() == $id_utilisateur) {
        http_response_code(403);
        echo $this->getTwig()->render('403.html.twig', [
            'message' => "Vous ne pouvez pas répondre à votre propre annonce."
        ]);
        return;
    }

    // VÉRIFICATION: Vérifier que l'annonce est disponible
    if ($annonce->getStatus() !== 'Disponible') {
        http_response_code(403);
        echo $this->getTwig()->render('403.html.twig', [
            'message' => "Cette annonce n'est plus disponible. Un maître a déjà accepté une candidature."
        ]);
        return;
    }

    // Appel à la DAO pour enregistrer la réponse
    $resultat = $managerAnnonce->repondreAnnonce($id_annonce, $id_utilisateur);

    // Gestion du résultat
    if (is_numeric($resultat)) {
        // Succès - $resultat contient l'id_reponse
        $id_reponse = $resultat;
        
        // 1. CRÉER UNE NOTIFICATION POUR LE PROMENEUR (validation de sa candidature)
        $managerNotification = new NotificationDAO($this->getPDO());
        $managerNotification->creerNotification(
            $id_utilisateur,
            'Candidature soumise',
            "Votre candidature pour l'annonce \"{$annonce->getTitre()}\" a été enregistrée avec succès. Le maître sera notifié et examinera votre candidature.",
            'candidature_soumise',
            $id_annonce,
            $id_reponse
        );

        // 2. CRÉER UNE NOTIFICATION POUR LE MAÎTRE (nouvelle candidature)
        $managerNotification->creerNotification(
            $annonce->getIdUtilisateur(),
            'Nouvelle candidature reçue',
            "{$sessionUser->getPseudo()} a postulé pour votre annonce \"{$annonce->getTitre()}\".",
            'candidature_reçue',
            $id_annonce,
            $id_reponse,
            $id_utilisateur
        );

        // Redirection vers l'annonce avec confirmation
        header('Location: index.php?controleur=annonce&methode=afficherAnnonce&id_annonce=' . $id_annonce . '&reponse=ok');
        exit();
    } else {
        // Réafficher l'annonce avec le message d'erreur
        $managerChien = new ChienDAO($this->getPDO());
        $chienConcernes = $managerChien->findByAnnonce($annonce->getIdAnnonce());

        $managerUtilisateur = new UtilisateurDAO($this->getPDO());
        $proprietaire = $managerUtilisateur->findById($annonce->getIdUtilisateur());
        
        $template = $this->getTwig()->load('annonce.html.twig');
        echo $template->render([
            'annonce' => $annonce,
            'chiens' => $chienConcernes,
            'proprietaire' => $proprietaire,
            'erreur' => $resultat,
            'userConnecte' => $sessionUser
        ]);
    }
}


/**
 * @brief Affiche toutes les candidatures pour les annonces du maître connecté
 */
public function voirCandidatures()
{
    if (!isset($_SESSION['utilisateur'])) {
        header('Location: index.php?controleur=utilisateur&methode=authentification');
        exit();
    }

    $sessionUser = unserialize($_SESSION['utilisateur']);

    // Vérifier que l'utilisateur est bien un maître
    if (!$sessionUser->getEstMaitre()) {
        http_response_code(403);
        echo $this->getTwig()->render('403.html.twig', [
            'message' => "Seuls les maîtres peuvent voir les candidatures."
        ]);
        return;
    }

    $managerAnnonce = new AnnonceDAO($this->getPDO());
    $candidatures = $managerAnnonce->getCandidaturesPourUtilisateur($sessionUser->getId());

    // Rendu Twig
    $template = $this->getTwig()->load('candidatures.html.twig');
    echo $template->render([
        'candidatures' => $candidatures,
        'userConnecte' => $sessionUser
    ]);
}

/**
 * @brief Affiche toutes les candidatures soumises par le promeneur connecté
 */
public function verMesCandidatures()
{
    if (!isset($_SESSION['utilisateur'])) {
        header('Location: index.php?controleur=utilisateur&methode=authentification');
        exit();
    }

    $sessionUser = unserialize($_SESSION['utilisateur']);

    // Vérifier que l'utilisateur est bien un promeneur
    if (!$sessionUser->getEstPromeneur()) {
        http_response_code(403);
        echo $this->getTwig()->render('403.html.twig', [
            'message' => "Seuls les promeneurs peuvent voir leurs candidatures."
        ]);
        return;
    }

    $managerAnnonce = new AnnonceDAO($this->getPDO());
    $candidatures = $managerAnnonce->getCandidaturesBySubmittedBy($sessionUser->getId());

    // Rendu Twig
    $template = $this->getTwig()->load('mes_candidatures.html.twig');
    echo $template->render([
        'candidatures' => $candidatures,
        'userConnecte' => $sessionUser
    ]);
}

/**
 * @brief Accepter une candidature à une annonce
 * @param int $id_annonce Identifiant de l'annonce
 * @param int $id_candidat Identifiant du candidat à accepter
 */
public function accepterCandidature()
{
    header('Content-Type: application/json');
    
    if (!isset($_SESSION['utilisateur'])) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => "Authentification requise."]);
        exit();
    }

    $sessionUser = unserialize($_SESSION['utilisateur']);

    // Vérifier que l'utilisateur est bien un maître
    if (!$sessionUser->getEstMaitre()) {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => "Seuls les maîtres peuvent accepter les candidatures."]);
        exit();
    }

    $id_annonce = $_POST['id_annonce'] ?? null;
    $id_candidat = $_POST['id_candidat'] ?? null;

    if (!$id_annonce || !$id_candidat) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => "Paramètres manquants."]);
        exit();
    }

    $managerAnnonce = new AnnonceDAO($this->getPDO());
    $annonce = $managerAnnonce->findById($id_annonce);

    // Vérifier que l'annonce appartient à l'utilisateur
    if (!$annonce || $annonce->getIdUtilisateur() != $sessionUser->getId()) {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => "Vous n'êtes pas autorisé à accepter cette candidature."]);
        exit();
    }

    // Appel à la méthode de la DAO pour accepter la candidature
    $id_reponse = $managerAnnonce->accepterCandidature($id_annonce, $id_candidat);

    if ($id_reponse) {
        // MARQUER L'ANNONCE COMME INDISPONIBLE
        $managerAnnonce->modifierChamp($id_annonce, 'status', 'Indisponible');
        
        // CRÉER UNE CONVERSATION AUTOMATIQUEMENT
        $managerConversation = new ConversationDAO($this->getPDO());
        $id_conversation = $managerConversation->createConversation($sessionUser->getId(), $id_candidat);
        
        // CRÉER UNE NOTIFICATION POUR LE PROMENEUR
        $managerNotification = new NotificationDAO($this->getPDO());
        
        // Récupérer les infos du promeneur pour le message
        $managerUtilisateur = new UtilisateurDAO($this->getPDO());
        $promeneur = $managerUtilisateur->findById($id_candidat);
        
        $notificationMessage = "Votre candidature pour l'annonce \"{$annonce->getTitre()}\" a été acceptée. Une conversation a été créée pour discuter des détails de la promenade. Consultez vos messages.";
        
        $managerNotification->creerNotification(
            $id_candidat,
            'Candidature acceptée',
            $notificationMessage,
            'candidature_acceptée',
            $id_annonce,
            $id_reponse,
            $id_candidat
        );

        error_log("✓ Candidature acceptée: Annonce {$id_annonce} - Candidat {$id_candidat} - Conversation {$id_conversation}");

        http_response_code(200);
        echo json_encode(['success' => true, 'message' => "Candidature acceptée avec succès.", 'conversation_id' => $id_conversation]);
        exit();
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => "Erreur lors de l'acceptation de la candidature."]);
        exit();
    }
}

    /**
     * @brief Refuser une candidature à une annonce
     * @param int $id_annonce Identifiant de l'annonce
     * @param int $id_candidat Identifiant du candidat à refuser
     */
    public function refuserCandidature()
    {
        header('Content-Type: application/json');
        
        if (!isset($_SESSION['utilisateur'])) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => "Authentification requise."]);
            exit();
        }

        $sessionUser = unserialize($_SESSION['utilisateur']);

        // Vérifier que l'utilisateur est bien un maître
        if (!$sessionUser->getEstMaitre()) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => "Seuls les maîtres peuvent refuser les candidatures."]);
            exit();
        }

        $id_annonce = $_POST['id_annonce'] ?? null;
        $id_candidat = $_POST['id_candidat'] ?? null;

        if (!$id_annonce || !$id_candidat) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => "Paramètres manquants."]);
            exit();
        }

        $managerAnnonce = new AnnonceDAO($this->getPDO());
        $annonce = $managerAnnonce->findById($id_annonce);

        // Vérifier que l'annonce appartient à l'utilisateur
        if (!$annonce || $annonce->getIdUtilisateur() != $sessionUser->getId()) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => "Vous n'êtes pas autorisé à refuser cette candidature."]);
            exit();
        }

        // Appel à la méthode de la DAO pour refuser la candidature
        $id_reponse = $managerAnnonce->refuserCandidature($id_annonce, $id_candidat);

        if ($id_reponse) {
            // CRÉER UNE NOTIFICATION POUR LE PROMENEUR
            $managerNotification = new NotificationDAO($this->getPDO());
            
            // Récupérer les infos du promeneur pour le message
            $managerUtilisateur = new UtilisateurDAO($this->getPDO());
            $promeneur = $managerUtilisateur->findById($id_candidat);
            
            $managerNotification->creerNotification(
                $id_candidat,
                'Candidature refusée',
                "Votre candidature pour l'annonce \"{$annonce->getTitre()}\" n'a pas été retenue cette fois-ci. D'autres annonces correspondant à votre profil seront bientôt disponibles.",
                'candidature_refusée',
                $id_annonce,
                $id_reponse,
                $id_candidat
            );

            http_response_code(200);
            echo json_encode(['success' => true, 'message' => "Candidature refusée avec succès."]);
            exit();
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => "Erreur lors du refus de la candidature."]);
            exit();
        }
    }

    /**
     * @brief Annuler une candidature
     */
    public function annulerCandidature()
    {
        if (!isset($_SESSION['utilisateur'])) {
            header('Location: index.php?controleur=utilisateur&methode=authentification');
            exit();
        }

        $sessionUser = unserialize($_SESSION['utilisateur']);

        // Vérifier que l'utilisateur est bien un promeneur
        if (!$sessionUser->getEstPromeneur()) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => "Seuls les promeneurs peuvent annuler leurs candidatures."]);
            exit();
        }

        $id_annonce = $_POST['id_annonce'] ?? $_GET['id_annonce'] ?? null;

        if (!$id_annonce) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => "Identifiant de l'annonce manquant."]);
            exit();
        }

        $managerAnnonce = new AnnonceDAO($this->getPDO());
        
        // Appel à la méthode de la DAO pour annuler la candidature
        $resultat = $managerAnnonce->supprimerCandidature($id_annonce, $sessionUser->getId());

        if ($resultat) {
            header('Location: index.php?controleur=annonce&methode=verMesCandidatures&success=Candidature%20annulée');
            exit();
        } else {
            http_response_code(500);
            echo $this->getTwig()->render('403.html.twig', [
                'message' => "Erreur lors de l'annulation de la candidature."
            ]);
        }
}

/**
 * @brief Vérifie s'il y a des candidatures nouvelles pour l'utilisateur maître
 * Méthode AJAX pour le système de notifications en temps réel
 */
public function checkNewCandidatures()
{
    // Vérifier que l'utilisateur est connecté et est maître
    if (!isset($_SESSION['utilisateur'])) {
        http_response_code(401);
        echo json_encode(['success' => false, 'candidatures' => []]);
        exit();
    }

    $sessionUser = unserialize($_SESSION['utilisateur']);

    if (!$sessionUser->getEstMaitre()) {
        http_response_code(403);
        echo json_encode(['success' => false, 'candidatures' => []]);
        exit();
    }

    // Récupérer toutes les candidatures pour l'utilisateur
    $managerAnnonce = new AnnonceDAO($this->getPDO());
    $candidatures = $managerAnnonce->getCandidaturesPourUtilisateur($sessionUser->getId());

    // Formatez les candidatures pour la réponse (ils sont déjà des arrays)
    $formattedCandidatures = [];
    foreach ($candidatures as $c) {
        $formattedCandidatures[] = [
            'id_annonce' => isset($c['id_annonce']) ? $c['id_annonce'] : '',
            'id_candidat' => isset($c['id_candidat']) ? $c['id_candidat'] : '',
            'pseudo' => isset($c['pseudo']) ? $c['pseudo'] : 'Candidat',
            'titre' => isset($c['titre']) ? $c['titre'] : 'Annonce'
        ];
    }

    // Répondre avec les données
    header('Content-Type: application/json');
    echo json_encode([
        'success' => true,
        'candidatures' => $formattedCandidatures,
        'count' => count($formattedCandidatures),
        'timestamp' => time()
    ]);
    exit();
}

/**
 * @brief Récupère les notifications pour le promeneur/maître actuel
 * AJAX endpoint
 */
public function getNotifications()
{
    if (!isset($_SESSION['utilisateur'])) {
        http_response_code(401);
        echo json_encode(['success' => false, 'notifications' => []]);
        exit();
    }

    $sessionUser = unserialize($_SESSION['utilisateur']);
    $managerNotification = new NotificationDAO($this->getPDO());

    // Récupérer les notifications non-lues
    $notifications = $managerNotification->getNotifications($sessionUser->getId(), true);

    error_log("📬 Controller getNotifications pour user " . $sessionUser->getId() . ": " . count($notifications) . " notif(s)");

    header('Content-Type: application/json');
    echo json_encode([
        'success' => true,
        'notifications' => $notifications,
        'count' => count($notifications),
        'userId' => $sessionUser->getId()
    ]);
    exit();
}

/**
 * @brief Marque une notification comme lue
 * AJAX endpoint
 */
public function markNotificationAsRead()
{
    if (!isset($_SESSION['utilisateur'])) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Non authentifié']);
        exit();
    }

    $id_notification = $_POST['id_notification'] ?? null;

    if (!$id_notification) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Paramètre manquant']);
        exit();
    }

    $managerNotification = new NotificationDAO($this->getPDO());
    $result = $managerNotification->marquerCommeLue($id_notification);

    header('Content-Type: application/json');
    echo json_encode([
        'success' => $result,
        'message' => $result ? 'Marquée comme lue' : 'Erreur'
    ]);
    exit();
}

/**
 * @brief Récupère toutes les notifications de l'utilisateur
 * AJAX endpoint
 */
public function getAllNotifications()
{
    if (!isset($_SESSION['utilisateur'])) {
        http_response_code(401);
        echo json_encode(['success' => false, 'notifications' => []]);
        exit();
    }

    $sessionUser = unserialize($_SESSION['utilisateur']);
    $managerNotification = new NotificationDAO($this->getPDO());

    // Récupérer TOUTES les notifications (pas seulement les non-lues)
    $notifications = $managerNotification->getNotifications($sessionUser->getId(), false);

    header('Content-Type: application/json');
    echo json_encode([
        'success' => true,
        'notifications' => $notifications,
        'count' => count($notifications),
        'userId' => $sessionUser->getId()
    ]);
    exit();
}

/**
 * @brief Supprime une notification
 * AJAX endpoint
 */
public function supprimerNotification()
{
    if (!isset($_SESSION['utilisateur'])) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Non authentifié']);
        exit();
    }

    $id_notification = $_POST['id_notification'] ?? null;

    if (!$id_notification) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Paramètre manquant']);
        exit();
    }

    $managerNotification = new NotificationDAO($this->getPDO());
    $result = $managerNotification->supprimerNotification($id_notification);

    header('Content-Type: application/json');
    echo json_encode([
        'success' => $result,
        'message' => $result ? 'Supprimée avec succès' : 'Erreur'
    ]);
    exit();
}

/**
 * @brief Affiche la page des notifications
 */
public function afficherNotifications()
{
    if (!isset($_SESSION['utilisateur'])) {
        header('Location: index.php?controleur=utilisateur&methode=authentification');
        exit();
    }

    $template = $this->getTwig()->load('notifications.html.twig');
    echo $template->render();
}

/**
 * @brief Affiche les promenades acceptées du promeneur
 */
public function verMesPromenades()
{
    if (!isset($_SESSION['utilisateur'])) {
        header('Location: index.php?controleur=utilisateur&methode=authentification');
        exit();
    }

    $sessionUser = unserialize($_SESSION['utilisateur']);

    // Vérifier que l'utilisateur est bien un promeneur
    if (!$sessionUser->getEstPromeneur()) {
        http_response_code(403);
        echo $this->getTwig()->render('403.html.twig', [
            'message' => "Seuls les promeneurs peuvent voir leurs promenades."
        ]);
        return;
    }

    $managerAnnonce = new AnnonceDAO($this->getPDO());
    $promenades = $managerAnnonce->getMesPromenades($sessionUser->getId());

    // Rendu Twig
    $template = $this->getTwig()->load('mes_promenades.html.twig');
    echo $template->render([
        'promenades' => $promenades,
        'userConnecte' => $sessionUser
    ]);
}


}
