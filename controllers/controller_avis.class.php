<?php

/**
 * @file controller_avis.class.php
 * @class ControllerAvis
 * @extends parent<Controller>
 * @brief Permet de gérer les actions liées aux pages concernant les avis
 * @author Campistron Julian
 * @version 1.2
 * @date 2026-01-26
 */

class ControllerAvis extends Controller
{
    /**
     * @constructor ControllerAvis
     * @brief Constructeur de la classe ControllerAvis
     * @param Twig\Environment $twig
     * @param Twig\Loader\FilesystemLoader $loader
     * @return void
     */
    public function __construct(\Twig\Environment $twig, \Twig\Loader\FilesystemLoader $loader) {
        parent::__construct($twig, $loader);
    }

    /**
    * @function afficherAvis
     * @brief Fonction permettant d'afficher un avis dont l'ID est 1
     * @uses AvisDao
     * @uses getPDO
     * @uses findById
     * @return void
     */
    public function afficherAvis()
    {
        // Récupérer un avis spécifique depuis la base de données
        $manageravis = new AvisDAO($this->getPDO());
        $avis = $manageravis->trouverParId(1); // Exemple avec l'ID 1
        
        // Vérifier que l'avis existe
        if (!$avis) {
            echo "Avis introuvable.";
            return;
        }
        
        // Récupérer l'utilisateur ayant publié l'avis
        $managerUtilisateur = new UtilisateurDAO($this->getPDO()); 
        $proprietaire = $managerUtilisateur->findById($avis->getIdUtilisateur());

        // Rendre la vue avec l'avis
        $template = $this->getTwig()->load('avis.html.twig');
        echo $template->render([
            'avis' => $avis,
            'proprietaire' => $proprietaire
        ]);
    }

    /**
    * @function afficherTousAvis
    * @brief Fonction permettant d'afficher tous les avis
     * @uses AvisDao
     * @uses getPDO
     * @uses findAll
     * @return void
     */
    public function afficherTousAvis()
    {
        $managerAvis = new AvisDAO($this->getPDO());
        $managerUtilisateur = new UtilisateurDAO($this->getPDO());

        $avisListe = $managerAvis->trouverTous();
        $avisEnrichis = [];

        foreach ($avisListe as $avis) {
            $proprietaire = $managerUtilisateur->findById($avis->getIdUtilisateur());

            $avisEnrichis[] = [
                'avis' => $avis,
                'proprietaire' => $proprietaire
            ];
        }

        $template = $this->getTwig()->load('avis.html.twig');
        echo $template->render([
            'avisListe' => $avisEnrichis
        ]);
    }


    /**
     * @function afficherAvisParIdUtilisateurNote
     * @brief Fonction permettant d'afficher tous les avis notant l'utilisateur dont l'ID est 2
     * @uses AvisDao
     * @uses getPDO
     * @uses findByIdUtilisateurNote
     * @return void
     */
    public function afficherAvisParIdUtilisateurNote($id_utilisateur_note = 2)
    {
        $managerAvis = new AvisDAO($this->getPDO());
        $managerUtilisateur = new UtilisateurDAO($this->getPDO());

        $avisUtilisateurNote = $managerAvis->trouverParIdUtilisateurNote($id_utilisateur_note);
        $avisEnrichis = [];

        foreach ($avisUtilisateurNote as $avis) {
            $proprietaire = $managerUtilisateur->findById($avis->getIdUtilisateur());

            $avisEnrichis[] = [
                'avis' => $avis,
                'proprietaire' => $proprietaire
            ];
        }

        $template = $this->getTwig()->load('avis.html.twig');
        echo $template->render([
            'avisUtilisateurNote' => $avisEnrichis,
            'id_utilisateur_note' => $id_utilisateur_note
        ]);
    }

    /**
     * @function creerAvis
     * @brief Creer un avis
     */
    public function creerAvis()
    {
        // Vérifier que l'utilisateur est connecté
        if (!isset($_SESSION['utilisateur'])) {
                header('Location: index.php?controleur=utilisateur&methode=authentification');
                exit();
            }

        $utilisateurConnecte = unserialize($_SESSION['utilisateur']);
        $id_utilisateur = $utilisateurConnecte->getId();

        $managerUtilisateur = new UtilisateurDAO($this->getPDO());
        $utilisateurConnecte = $managerUtilisateur->findById($id_utilisateur);

        if (!$utilisateurConnecte || !$utilisateurConnecte->getEstMaitre()) {
            http_response_code(403); 
            $template = $this->getTwig()->load('403.html.twig');
            echo $template->render(['message' => "Seuls les utilisateurs avec le rôle 'maitre' peuvent ajouter un avis."]);
            return;
        }

        // Récupérer l'ID de l'utilisateur à noter et l'ID de l'annonce depuis GET ou POST
        $id_utilisateur_note = $_GET['id_utilisateur_note'] ?? $_POST['id_utilisateur_note'] ?? null;
        $id_annonce = $_GET['id_annonce'] ?? $_POST['id_annonce'] ?? null;

        $managerAnnonce = new AnnonceDAO($this->getPDO());

        if (empty($id_annonce)) {
            $template = $this->getTwig()->load('ajouter_avis.html.twig');
            echo $template->render([
                'erreurs' => ["Annonce introuvable pour l'avis."],
                'id_utilisateur_note' => $id_utilisateur_note,
                'id_annonce' => $id_annonce
            ]);
            return;
        }

        $annonce = $managerAnnonce->findById((int) $id_annonce);
        if (!$annonce) {
            $template = $this->getTwig()->load('ajouter_avis.html.twig');
            echo $template->render([
                'erreurs' => ["Annonce introuvable pour l'avis."],
                'id_utilisateur_note' => $id_utilisateur_note,
                'id_annonce' => $id_annonce
            ]);
            return;
        }

        if ((int) $annonce->getIdUtilisateur() !== (int) $utilisateurConnecte->getId()) {
            http_response_code(403);
            $template = $this->getTwig()->load('403.html.twig');
            echo $template->render(['message' => "Seul le proprietaire de l'annonce peut laisser un avis."]);
            return;
        }

        $acceptedCandidatId = $annonce->getIdPromeneur();
        if (!$acceptedCandidatId) {
            $acceptedCandidatId = $managerAnnonce->getCandidatAccepte((int) $id_annonce);
        }

        if (!$acceptedCandidatId) {
            $template = $this->getTwig()->load('ajouter_avis.html.twig');
            echo $template->render([
                'erreurs' => ["Aucun promeneur accepte pour cette annonce."],
                'id_utilisateur_note' => $id_utilisateur_note,
                'id_annonce' => $id_annonce
            ]);
            return;
        }

        if (empty($id_utilisateur_note)) {
            $id_utilisateur_note = $acceptedCandidatId;
        } elseif ((int) $id_utilisateur_note !== (int) $acceptedCandidatId) {
            $template = $this->getTwig()->load('ajouter_avis.html.twig');
            echo $template->render([
                'erreurs' => ["Utilisateur note invalide pour cette annonce."],
                'id_utilisateur_note' => $id_utilisateur_note,
                'id_annonce' => $id_annonce
            ]);
            return;
        }

        // Vérifier que la promenade est terminée
        if ($annonce && !$this->peutLaisserAvisPourAnnonce($annonce)) {
            $template = $this->getTwig()->load('ajouter_avis.html.twig');
            echo $template->render([
                'erreurs' => ["Cette promenade n'a pas encore eu lieu. Vous pourrez laisser un avis après qu'elle soit terminée."],
                'id_utilisateur_note' => $id_utilisateur_note,
                'id_annonce' => $id_annonce
            ]);
            return;
        }
        

        //Un if() qui contient la logique de si on a répondu au formulaire de la page en dessous


        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $note = $_POST['note'] ?? null;
            $texte_commentaire = $_POST['commentaire'] ?? null;
            $erreurs = [];

            // Vérifier à nouveau que la promenade est terminée
            if ($annonce && !$this->peutLaisserAvisPourAnnonce($annonce)) {
                $erreurs[] = "Cette promenade n'a pas encore eu lieu. Vous ne pouvez pas laisser d'avis.";
            }

            if (!empty($erreurs)) {
                $template = $this->getTwig()->load('ajouter_avis.html.twig');
                echo $template->render([
                    'erreurs' => $erreurs,
                    'donnees' => $_POST,
                    'id_utilisateur_note' => $id_utilisateur_note,
                    'id_annonce' => $id_annonce
                ]);
                return;
            }
            
            $regles = [
                'note' => [
                    'obligatoire' => true,
                    'type' => 'numeric',
                    'plage_min' => 1,
                    'plage_max' => 5
                ],
                'commentaire' => [
                    'obligatoire' => false,
                    'type' => 'string',
                    'longueur_max' => 500
                ]
            ];

            $validator = new Validator($regles);
            $valide = $validator->valider($_POST);
            $erreurs = $validator->getMessagesErreurs();
        
            // SI ERREURS → on réaffiche le formulaire
        
            if (!$valide) {
                $template = $this->getTwig()->load('ajouter_avis.html.twig');
                echo $template->render([
                    'erreurs' => $erreurs,
                    'donnees' => $_POST,
                    'id_utilisateur_note' => $id_utilisateur_note,
                    'id_annonce' => $id_annonce
                ]);
                return;
            }

            // Vérifier que les IDs sont présents
            if (empty($id_utilisateur_note)) {
                $erreurs[] = "Impossible de déterminer l'utilisateur à noter.";
            }

            if (!empty($erreurs)) {
                $template = $this->getTwig()->load('ajouter_avis.html.twig');
                echo $template->render([
                    'erreurs' => $erreurs,
                    'donnees' => $_POST,
                    'id_utilisateur_note' => $id_utilisateur_note,
                    'id_annonce' => $id_annonce
                ]);
                return;
            }

            $pdo = $this->getPDO();

            // Créer l'avis avec les données
            $avis = new Avis(
                null,                     // id_avis (auto-increment)
                $note,
                $texte_commentaire,
                $id_utilisateur,          // Qui a écrit l'avis
                (int) $id_annonce,        // ID de l'annonce (promenade)
                $id_utilisateur_note      // ID de l'utilisateur noté
            );

            // INSERT avis
            $managerAvis = new AvisDAO($this->getPDO());
            $managerAvis->ajouter($avis);

            $id_avis = $pdo->lastInsertId();

            // Notification au promeneur note
            $managerNotification = new NotificationDAO($this->getPDO());
            $titreAnnonce = $annonce ? $annonce->getTitre() : 'votre promenade';
            $messageNotif = "Vous avez recu un nouvel avis pour \"{$titreAnnonce}\". Note: {$note}/5.";
            $managerNotification->creerNotification(
                (int) $id_utilisateur_note,
                'Nouvel avis recu',
                $messageNotif,
                'info',
                (int) $id_annonce,
                null,
                (int) $id_utilisateur_note
            );

            // Redirection vers un popup de confirmation
            header('Location: index.php?controleur=Avis&methode=confirmationCreationAvis');
            exit();
            
        }

        // Affiche le formulaire pour créer un avis
        $template = $this->getTwig()->load('ajouter_avis.html.twig');
        echo $template->render([
            'id_utilisateur_note' => $id_utilisateur_note,
            'id_annonce' => $id_annonce
        ]);
    }

    /**
     * @brief Confirme la création d'un avis
     */
    public function confirmationCreationAvis()
    {
        $template = $this->getTwig()->load('confirmation_creation_avis.html.twig');
        echo $template->render();

    }

    /**
     * @brief Modifier un avis spécifique
     * @param int $id_avis Identifiant de l'avis à afficher
     */
    public function modifierAvis($id_avis = null)
    {
        if (!isset($_SESSION['utilisateur'])) {
                header('Location: index.php?controleur=utilisateur&methode=authentification');
                exit();
            }

        // ---------- ID AVIS ----------
        if ($id_avis === null) {
            $id_avis = $_GET['id_avis'] ?? null;
        }

        if (!$id_avis) {
            http_response_code(404);
            echo $this->getTwig()->render('404.html.twig');
            return;
        }

        // ---------- AVIS ----------
        $managerAvis = new AvisDAO($this->getPDO());
        $avis = $managerAvis->trouverParId($id_avis);

        $utilisateurConnecte = unserialize($_SESSION['utilisateur']);
        $id_utilisateur = $utilisateurConnecte->getId();

        if (!$avis || $avis->getIdUtilisateur() != $id_utilisateur) {
            http_response_code(403);
            echo $this->getTwig()->render('403.html.twig', [
                'message' => "Accès interdit."
            ]);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $regles = [
                'note' => [
                    'obligatoire' => true,
                    'type' => 'numeric',
                    'plage_min' => 1,
                    'plage_max' => 5
                ],
                'texte_commentaire' => [
                    'obligatoire' => false,
                    'type' => 'string',
                    'longueur_max' => 50
                ]
            ];

            $validator = new Validator($regles);
            $valide = $validator->valider($_POST);
            $erreurs = $validator->getMessagesErreurs();

            if (!$valide) {
                echo $this->getTwig()->render('modifier_avis.html.twig', [
                    'avis' => $avis,
                    'erreurs' => $erreurs,
                    'donnees' => $_POST
                ]);
                return;
            }

            $managerAvis->modifierChamp($id_avis, 'note', $_POST['note']);
            $managerAvis->modifierChamp($id_avis, 'texte_commentaire', $_POST['texte_commentaire']);

            //header('Location: index.php?controleur=avis&methode=afficherAvis&id_annonce=' . $id_annonce); //A IMPLEMENTER QUAND CE SERA PLUS STATIQUE
            header('Location: index.php?controleur=avis&methode=afficherAvis');
            exit();
        }

        echo $this->getTwig()->render('modifier_avis.html.twig', [
            'avis' => $avis
        ]);
    }

    private function peutLaisserAvisPourAnnonce(Annonce $annonce): bool
    {
        if (strtolower((string) $annonce->getStatus()) === 'archivee') {
            return true;
        }

        $statutPromenade = strtolower((string) $annonce->getStatutPromenade());
        if ($statutPromenade === 'terminee' || $statutPromenade === 'archivee') {
            return true;
        }

        try {
            $walkDate = new DateTime((string) $annonce->getDatePromenade());
            $today = new DateTime('today');
            return $walkDate <= $today;
        } catch (Exception $e) {
            return false;
        }
    }

}