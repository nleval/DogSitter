<?php

class ControllerChien extends Controller
{
    public function __construct(\Twig\Environment $twig, \Twig\Loader\FilesystemLoader $loader) {
        parent::__construct($twig, $loader);
    }

    public function afficherChien()
    {
        // Récupérer un chien spécifique depuis la base de données
        $managerchien = new ChienDAO($this->getPDO());
        $id_chien = isset($_GET['id_chien']) ? (int)$_GET['id_chien'] : null;
        $chien = $managerchien->findById($id_chien); // Exemple avec l'ID 1

        $managerutilisateur = new AvisDAO($this->getPDO());
        $propriétaire = $managerutilisateur->findById(1); // Exemple avec l'ID 1
        var_dump($propriétaire);
        

        // Rendre la vue avec le chien
        $template = $this->getTwig()->load('chien.html.twig');
        echo $template->render([
            'chien' => $chien,
            'proprietaire' => $propriétaire
        ]);
    }

    public function afficherAllChiens()
    {
        // Récupérer tous les chiens depuis la base de données
        $managerchien = new ChienDAO($this->getPDO());
        $chiensListe = $managerchien->findAll();

        // Rendre la vue avec les chiens
        $template = $this->getTwig()->load('chien.html.twig');
        echo $template->render([
            'chiensListe' => $chiensListe
        ]);
    }
}

?>