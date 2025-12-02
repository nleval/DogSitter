<?php

class ControllerUtilisateur extends Controller
{
    public function __construct(\Twig\Environment $twig, \Twig\Loader\FilesystemLoader $loader) {
        parent::__construct($twig, $loader);
    }

    public function afficherUtilisateur()
    {
        $id_utilisateur = $_GET['id_utilisateur'];

        // Récupérer un utilisateur spécifique depuis la base de données
        $managerutilisateur = new UtilisateurDAO($this->getPDO());
        $utilisateur = $managerutilisateur->findById($id_utilisateur);

        // Rendre la vue avec l'utilisateur
        $template = $this->getTwig()->load('utilisateur.html.twig');
        echo $template->render([
            'utilisateur' => $utilisateur
        ]);
    }

    public function afficherAllUtilisateurs()
    {
        // Récupérer tous les utilisateurs depuis la base de données
        $managerutilisateur = new UtilisateurDAO($this->getPDO());
        $utilisateursListe = $managerutilisateur->findAll();

        // Rendre la vue avec les utilisateurs
        $template = $this->getTwig()->load('utilisateurs.html.twig');
        echo $template->render([
            'utilisateursListe' => $utilisateursListe
        ]);
    }

    public function ajouterUtilisateur()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupérer les données du formulaire
            $email        = $_POST['email'];
            $estMaitre    = $_POST['estMaitre'];
            $estPromeneur = $_POST['estPromeneur'];
            $adresse      = $_POST['adresse'];
            $motDePasse   = $_POST['motDePasse'];
            $nom          = $_POST['nom'];
            $prenom       = $_POST['prenom'];
            $numTelephone = $_POST['numTelephone'];

            // Créer un nouvel utilisateur
            $nouvelUtilisateur = new Utilisateur(null, $email, $estMaitre, $estPromeneur, $adresse, $motDePasse, $nom, $prenom, $numTelephone);

            // Enregistrer l'utilisateur dans la base de données
            $managerutilisateur = new UtilisateurDAO($this->getPDO());
            $managerutilisateur->ajouterUtilisateur($nouvelUtilisateur);

            // Rediriger vers la liste des utilisateurs
            header('Location: index.php?action=afficherAllUtilisateurs');
            exit();
        } else {
            // Afficher le formulaire d'ajout d'utilisateur
            $template = $this->getTwig()->load('ajouter_utilisateur.html.twig');
            echo $template->render();
        }
    }

    public function supprimerUtilisateur()
    {
        $id_utilisateur = $_GET['id_utilisateur'];

        // Supprimer l'utilisateur de la base de données
        $managerutilisateur = new UtilisateurDAO($this->getPDO());
        $managerutilisateur->delete($id_utilisateur);

        // Rediriger vers la liste des utilisateurs
        header('Location: index.php?action=afficherAllUtilisateurs');
        exit();
    }

    public function modifierUtilisateur()
    {
        $id_utilisateur = $_GET['id_utilisateur'];
        $managerutilisateur = new UtilisateurDAO($this->getPDO());

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupérer les données du formulaire
            $nom = $_POST['nom'];
            $email = $_POST['email'];

            // Mettre à jour l'utilisateur
            $utilisateurModifie = new Utilisateur($id_utilisateur, $nom, $email);
            $managerutilisateur->update($utilisateurModifie);

            // Rediriger vers la liste des utilisateurs
            header('Location: index.php?action=afficherAllUtilisateurs');
            exit();
        } else {
            // Récupérer l'utilisateur à modifier
            $utilisateur = $managerutilisateur->findById($id_utilisateur);

            // Afficher le formulaire de modification avec les données existantes
            $template = $this->getTwig()->load('modifier_utilisateur.html.twig');
            echo $template->render([
                'utilisateur' => $utilisateur
            ]);
        }
    }
}