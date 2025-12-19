<?php
/**
 * @file controller_chien.class.php
 * @author Thyes Lilian
 * @brief Gère les opérations liées aux chiens
 * @version 1.0
 * @date 2025-12-18
 */
class ControllerChien extends Controller
{
    /**
     * @brief Constructeur du contrôleur de chien.
     * @param \Twig\Environment $twig Moteur de templates Twig.
     * @param \Twig\Loader\FilesystemLoader $loader Chargeur de templates Twig.
     */
    public function __construct(\Twig\Environment $twig, \Twig\Loader\FilesystemLoader $loader) {
        parent::__construct($twig, $loader);
    }

    /**
     * @brief Afficher un chien spécifique
     * @param int $id_chien Identifiant du chien à afficher
     */
    public function afficherChien()
    {
        // Récupérer un chien spécifique depuis la base de données
        $managerchien = new ChienDAO($this->getPDO());
        $id_chien = isset($_GET['id_chien']) ? (int)$_GET['id_chien'] : null;
        $chien = $managerchien->findById($id_chien); // Exemple avec l'ID 1

        $managerutilisateur = new UtilisateurDAO($this->getPDO());
        $propriétaire = $managerutilisateur->findById($chien->getId_Utilisateur()); // Exemple avec l'ID 1
        
        

        // Rendre la vue avec le chien
        $template = $this->getTwig()->load('chien.html.twig');
        echo $template->render([
            'chien' => $chien,
            'proprietaire' => $propriétaire
        ]);
    }

    /**
     * @brief Afficher tous les chiens
     */
    public function afficherAllChiens()
    {
        // Récupérer tous les chiens depuis la base de données
        $managerchien = new ChienDAO($this->getPDO());
        $chiensListe = $managerchien->findAll();

        // Rendre la vue avec les chiens
        $template = $this->getTwig()->load('chiens.html.twig');
        echo $template->render([
            'chiensListe' => $chiensListe
        ]);
    }
}

?>