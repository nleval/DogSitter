<?php

/**
 * @class ControllerAvis
 * @extends parent<Controller>
 * @details Permet de gérer les actions liées aux pages concernant les avis
 */

class ControllerAvis extends Controller
{
    /**
     * @constructor ControllerAvis
     * @details Constructeur de la classe ControllerAvis
     * @param Twig\Environment $twig
     * @param Twig\Loader\FilesystemLoader $loader
     * @return void
     */
    public function __construct(\Twig\Environment $twig, \Twig\Loader\FilesystemLoader $loader) {
        parent::__construct($twig, $loader);
    }

    /**
     * @function afficherAvis
     * @details Fonction permettant d'afficher un avis dont l'ID est 1
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

        // Rendre la vue avec l'avis
        $template = $this->getTwig()->load('avis.html.twig');
        echo $template->render([
            'avis' => $avis
        ]);
    }

    /**
     * @function afficherAllAvis
     * @details Fonction permettant d'afficher tous les avis
     * @uses AvisDao
     * @uses getPDO
     * @uses findAll
     * @return void
     */
    public function afficherAllAvis()
    {
        // Récupérer tous les avis depuis la base de données
        $manageravis = new AvisDAO($this->getPDO());
        $avisListe = $manageravis->findAll();

        // Rendre la vue avec les avis
        $template = $this->getTwig()->load('avis.html.twig');
        echo $template->render([
            'avisListe' => $avisListe
        ]);
    }

    /**
     * @function afficherAvisParIdUtilisateurNote
     * @details Fonction permettant d'afficher tous les avis notant l'utilisateur dont l'ID est 2
     * @uses AvisDao
     * @uses getPDO
     * @uses findByIdUtilisateurNote
     * @return void
     */
    public function afficherAvisParIdUtilisateurNote($id_utilisateur_note = 2)
    {
        $manageravis = new AvisDAO($this->getPDO());
        $avisUtilisateurNote = $manageravis->findByIdUtilisateurNote($id_utilisateur_note);

        $template = $this->getTwig()->load('avis.html.twig');
        echo $template->render([
            'avisUtilisateurNote' => $avisUtilisateurNote,
            'id_utilisateur_note' => $id_utilisateur_note
        ]);
    }
}