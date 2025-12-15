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
        $managerutilisateur->supprimerUtilisateur($id_utilisateur);

        // Rediriger vers la liste des utilisateurs
        header('Location: index.php?action=afficherAllUtilisateurs');
        exit();
    }

    public function afficherFormulaire()
    {
        // Afficher le formulaire d'ajout d'utilisateur
        $id_utilisateur = 1;

        // Récupérer l'utilisateur depuis la base de données
        $managerutilisateur = new UtilisateurDAO($this->getPDO());
        $utilisateur = $managerutilisateur->findById($id_utilisateur);

        $template = $this->getTwig()->load('utilisateurModifier.html.twig');
        echo $template->render([
            'utilisateur' => $utilisateur
        ]);
    }

    public function modifierEmail()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

             $regles = [
                'email' => [
                'obligatoire' => true,
                'type' => 'string',
                'longueur_min' => 5,
                'longueur_max' => 255,
                'format' => FILTER_VALIDATE_EMAIL
                ]
            ];

            $managerutilisateur = new UtilisateurDAO($this->getPDO());

            $id_utilisateur = 1;
            $nouvelEmail = $_POST['email'];
            $donnes = ['email' => $nouvelEmail];

            $validator = new Validator($regles);
            $valide = $validator->valider($donnes);

            if (!$valide) {
                $messagesErreurs = $validator->getMessagesErreurs();
                // Rendre la vue avec les erreurs
                $template = $this->getTwig()->load('utilisateurModifier.html.twig');
                echo $template->render([
                    'messagesErreurs' => $messagesErreurs,
                    'utilisateur' => ($managerutilisateur->findById($id_utilisateur))
                ]);
                return;
            }

            // Mettre à jour l'email de l'utilisateur dans la base de données
            $managerutilisateur->modifierChamp($id_utilisateur, 'email', $nouvelEmail);

            // Rediriger vers la page de l'utilisateur
            header('Location: index.php?action=afficherUtilisateur&id_utilisateur=' . $id_utilisateur);
            exit();
        }
    }
}