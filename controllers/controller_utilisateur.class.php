<?php

class ControllerUtilisateur extends Controller
{
    public function __construct(\Twig\Environment $twig, \Twig\Loader\FilesystemLoader $loader) {
        parent::__construct($twig, $loader);
    }

    public function deconnexion()
    {
        // Détruire la session et rediriger
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_unset();
            session_destroy();
        }
        header('Location: index.php');
        exit();
    }

    public function afficherUtilisateur()
    {
        // Vérifier que l'utilisateur est connecté
        if (!isset($_SESSION['user'])) {
                header('Location: index.php?controleur=utilisateur&methode=authentification');
                exit();
            }

        // Récupérer l'ID depuis la session (profil de l'utilisateur connecté)
        $sessionUser = $_SESSION['user'];

        // Si l'utilisateur en session est un tableau
        if (is_array($sessionUser)) {
            $id_utilisateur = $sessionUser['id_utilisateur'] ?? null;
            
        // Sinon, si l'utilisateur en session est un objet avec la méthode getId()
        } elseif (is_object($sessionUser) && method_exists($sessionUser, 'getId')) {
            $id_utilisateur = $sessionUser->getId();
        } else {
            // Invalide -> redirection vers l'authentification
            header('Location: index.php?controleur=utilisateur&methode=authentification');
            exit();
        }

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

        // Récupération des données du formulaire
        $donnees = [
            'nom'          => $_POST['nom'] ?? '',
            'prenom'       => $_POST['prenom'] ?? '',
            'email'        => $_POST['email'] ?? '',
            'adresse'      => $_POST['adresse'] ?? '',
            'motDePasse'   => $_POST['motDePasse'] ?? '',
            'numTelephone' => $_POST['numTelephone'] ?? '',
            'estMaitre'    => isset($_POST['estMaitre']) ? 1 : 0,
            'estPromeneur' => isset($_POST['estPromeneur']) ? 1 : 0
        ];

        // RÈGLES DE VALIDATION
       $regles = [
    'nom' => [
        'obligatoire' => true,
        'type' => 'string',
        'longueur_min' => 2,
        'longueur_max' => 70
    ],

    'prenom' => [
        'obligatoire' => true,
        'type' => 'string',
        'longueur_min' => 2,
        'longueur_max' => 70
    ],

    'email' => [
        'obligatoire' => true,
        'format' => FILTER_VALIDATE_EMAIL,  
        'longueur_max' => 255
    ],

    'adresse' => [
        'obligatoire' => true,
        'type' => 'string',
        'longueur_min' => 5,
        'longueur_max' => 120
    ],

    'motDePasse' => [
        'obligatoire' => true,
        'type' => 'string',
        'longueur_min' => 8,
        'longueur_max' => 50
    ],

    'numTelephone' => [
        'obligatoire' => true,
        'type' => 'string',
        'format' => '/^0[1-9](\d{2}){4}$/'
    ],

    'estMaitre' => [
        'obligatoire' => false,   // checkbox non obligatoire
        'type' => 'numeric'       
    ],

    'estPromeneur' => [
        'obligatoire' => false,
        'type' => 'numeric'
    ]
];

    

        $validator = new Validator($regles);
        $valide = $validator->valider($donnees);
        $erreurs = $validator->getMessagesErreurs();

        // VALIDATION SPÉCIALE : au moins un rôle
        if (!$donnees['estMaitre'] && !$donnees['estPromeneur']) {
            $erreurs[] = "Vous devez sélectionner au moins un rôle (maître ou/et promeneur).";
            $valide = false;
        }

        // SI ERREURS on réaffichage le formulaire
        if (!$valide) {
            $template = $this->getTwig()->load('formulaire_creerCompte.html.twig');
            echo $template->render([
                'erreurs' => $erreurs,
                'old' => $donnees
            ]);
            return;
        }

        // CRÉATION DE L’OBJET UTILISATEUR
        $nouvelUtilisateur = new Utilisateur(
            null,
            $donnees['email'],
            $donnees['estMaitre'],
            $donnees['estPromeneur'],
            $donnees['adresse'],
            $donnees['motDePasse'],
            $donnees['nom'],
            $donnees['prenom'],
            $donnees['numTelephone']
        );

        // ENREGISTREMENT EN BDD
        $manager = new UtilisateurDAO($this->getPDO());

        try{

            $manager->inscription($nouvelUtilisateur);
            // Ne pas connecter automatiquement l'utilisateur : le rediriger vers la page d'authentification
            header('Location: index.php?controleur=utilisateur&methode=authentification&inscription=success');
            exit();
        }
        catch (Exception $e) {
            $erreurs = [];

            // Switch sur le message de l'exception
            switch ($e->getMessage()) {
                case 'Le mot de passe n\'est pas assez robuste.':
                    $erreurs[] = "Votre mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial.";
                    break;

                case 'L\'email existe déjà.':
                    $erreurs[] = "Cette adresse email est déjà utilisée. Veuillez en choisir une autre.";
                    break;

                default:
                    $erreurs[] = "Une erreur inattendue est survenue : " . $e->getMessage();
                    break;
            }

            $template = $this->getTwig()->load('formulaire_creerCompte.html.twig');
            echo $template->render([
                'erreurs' => $erreurs,
                'old' => $donnees
            ]);
            return;
        }
    }

    $template = $this->getTwig()->load('formulaire_creerCompte.html.twig');
    echo $template->render();
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

    public function authentification()
{
    $erreurs = [];

    // === Si formulaire envoyé ===
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $email = trim($_POST['email'] ?? '');
        $motDePasse = $_POST['motDePasse'] ?? '';

        $manager = new UtilisateurDAO($this->getPDO());

        try {
            // Tentative d’authentification
            if ($manager->authentification($email, $motDePasse)) {

                // Récupération des données utilisateur
                $utilisateur = $manager->findByEmail($email);

                if ($utilisateur) {
                    // On évite de stocker le mot de passe en session
                    $utilisateur->setMotDePasse(null);

                    $_SESSION['user'] = [
                        'id_utilisateur' => $utilisateur->getId(),
                        'email' => $utilisateur->getEmail(),
                        'estMaitre' => $utilisateur->getEstMaitre(),
                        'estPromeneur' => $utilisateur->getEstPromeneur(),
                        'prenom' => $utilisateur->getPrenom(),
                        'nom' => $utilisateur->getNom()
                    ];

                    header(
                        'Location: index.php?controleur=utilisateur&methode=afficherUtilisateur'
                    );
                    exit();
                }

            } else {
                // Email ou mot de passe incorrect
                $erreurs[] = "Email ou mot de passe incorrect.";
            }

        } catch (Exception $e) {

            // Gestion du cas où le compte est temporairement désactivé
            if ($e->getMessage() === "compte_desactive") {

                $utilisateur = $manager->findByEmail($email);
                $tempsDernierEchec = $utilisateur ? $utilisateur->getDateDernierEchecConnexion() : null;
                $tempsRestant = $manager->tempsRestantAvantReactivationCompte($tempsDernierEchec);

                $minutes = floor($tempsRestant / 60);
                $secondes = $tempsRestant % 60;

                $erreurs[] = "Votre compte est temporairement désactivé. 
                              Réessayez dans {$minutes} minutes et {$secondes} secondes.";
            } 
            else {
                // Erreur inattendue
                $erreurs[] = "Erreur inattendue : " . $e->getMessage();
            }
        }

        // Réaffichage du formulaire avec erreurs et valeurs précédentes
        $template = $this->getTwig()->load('formulaire_authentification.html.twig');
        echo $template->render([
            'erreurs' => $erreurs,
            'old' => ['email' => $email]
        ]);

        return;
    }

    // Si le formulaire n’est pas envoyé : affichage simple
    $success = null;

    if (isset($_GET['inscription']) && $_GET['inscription'] === 'success') {
        $success = 'Votre compte a bien été créé. Veuillez vous connecter.';
    }

    $template = $this->getTwig()->load('formulaire_authentification.html.twig');
    echo $template->render([
        'erreurs' => [],
        'success' => $success
    ]);
}
}