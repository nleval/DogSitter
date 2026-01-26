<?php

/**
 * @file controller_avis.class.php
 * @class ControllerAvis
 * @extends parent<Controller>
 * @brief Permet de gérer les actions liées aux pages concernant les avis
 * @author Campistron Julian
 * @version 1.0
 * @date 2025-12-19
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
        $avis = $manageravis->findById(1); // Exemple avec l'ID 1
        
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
     * @function afficherAllAvis
     * @brief Fonction permettant d'afficher tous les avis
     * @uses AvisDao
     * @uses getPDO
     * @uses findAll
     * @return void
     */
    public function afficherAllAvis()
    {
        $managerAvis = new AvisDAO($this->getPDO());
        $managerUtilisateur = new UtilisateurDAO($this->getPDO());

        $avisListe = $managerAvis->findAll();
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

        $avisUtilisateurNote = $managerAvis->findByIdUtilisateurNote($id_utilisateur_note);
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

        if (!$utilisateurConnecte || !$utilisateurConnecte->getEstPromeneur()) {
            http_response_code(403); 
            $template = $this->getTwig()->load('403.html.twig');
            echo $template->render(['message' => "Seuls les utilisateurs avec le rôle 'promeneur' peuvent ajouter un avis."]);
            return;
        }

        //Un if() qui contient la logique de si on a répondu au formulaire de la page en dessous


        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $note = $_POST['note'] ?? null;
            $texte_commentaire = $_POST['commentaire'] ?? null;

            $regles = [
                'note' => [
                    'obligatoire' => true,
                    'type' => 'numeric',
                    'plage_min' => 1
                    //MAX => 5
                ],
                'commentaire' => [
                    'obligatoire' => false,
                    'type' => 'string',
                    'longueur_max' => 50
                ]
            ];

            $validator = new Validator($regles);
            $valide = $validator->valider($_POST);
            $erreurs = $validator->getMessagesErreurs();
        
            // SI ERREURS → on réaffiche le formulaire
        
            if (!$valide) {
                $template = $this->getTwig()->load('avis.html.twig');
                echo $template->render([
                    'erreurs' => $erreurs,
                    'donnees' => $_POST,
                ]);
                return;
            }
            


            $pdo = $this->getPDO();

                $avis = new Avis(
                null,                     // id_avis (auto-increment)
                $note,
                $texte_commentaire,
                $id_utilisateur,
                $id_promenade,
                $id_utilisateur_note
                );


        // INSERT avis
            $managerAvis = new AVisDAO($this->getPDO());
            $managerAvis->ajouterAvis($avis);

            $id_avis = $pdo->lastInsertId();

            // Redirection vers un popup de confirmation
            header('Location: index.php?controleur=avis&methode=confirmationCreationAvis');
            exit();
            
        }

        $template = $this->getTwig()->load('ajouter_avis.html.twig');
        echo $template->render();
    }

    /**
     * @brief Confirme la création d'un avis
     */
    public function confirmationCreationAvis()
    {
        $template = $this->getTwig()->load('confirmation_creation_avis.html.twig');
        echo $template->render();

    }

}