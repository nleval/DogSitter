<?php
/**
 * @file controller_utilisateur.class.php
 * @author Léval Noah
 * @brief Gère les opérations liées aux utilisateurs
 * @version 1.0
 * @date 2025-12-18
 */
class ControllerUtilisateur extends Controller
{    
    /**
     * @brief Constructeur du contrôleur d'utilisateur.
     * @param \Twig\Environment $twig Moteur de templates Twig.
     * @param \Twig\Loader\FilesystemLoader $loader Chargeur de templates Twig.
     */
    public function __construct(\Twig\Environment $twig, \Twig\Loader\FilesystemLoader $loader) {
        parent::__construct($twig, $loader);
    }

    /**
     * @brief Deconnecte l'utilsateur
     */
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

    /**
     * @brief Afficher l'utilisateur connecte
     */
    public function afficherTonUtilisateur()
    {
        // Vérifier que l'utilisateur est connecté
        if (!isset($_SESSION['utilisateur'])) {
                header('Location: index.php?controleur=utilisateur&methode=authentification');
                exit();
            }

        // Récupérer l'ID depuis la session (profil de l'utilisateur connecté)
        $utilisateurConnecte = unserialize($_SESSION['utilisateur']);
        $id_utilisateur = $utilisateurConnecte->getId();

        // Récupérer un utilisateur spécifique depuis la base de données
        $managerutilisateur = new UtilisateurDAO($this->getPDO());
        $utilisateur = $managerutilisateur->findById($id_utilisateur);

        // Rendre la vue avec l'utilisateur
        $template = $this->getTwig()->load('utilisateur.html.twig');
        echo $template->render([
            'utilisateur' => $utilisateur
        ]);
    }

    /**
     * @brief Afficher un utilisateur autre que soi-même
     * @param int $id_utilisateur Identifiant de l'utilisateur à afficher
     */
    public function afficherUtilisateur()
    {
        if (!isset($_SESSION['utilisateur'])) {
                header('Location: index.php?controleur=utilisateur&methode=authentification');
                exit();
            }

        // Récupérer l'ID de l'utilisateur depuis les paramètres GET
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

    /**
     * @brief Afficher tous les utilisateurs
     */
    public function afficherAllUtilisateurs()
    {
        if (!isset($_SESSION['utilisateur'])) {
                header('Location: index.php?controleur=utilisateur&methode=authentification');
                exit();
            }

        // Récupérer tous les utilisateurs depuis la base de données
        $managerutilisateur = new UtilisateurDAO($this->getPDO());
        $utilisateursListe = $managerutilisateur->findAll();

        // Rendre la vue avec les utilisateurs
        $template = $this->getTwig()->load('utilisateurs.html.twig');
        echo $template->render([
            'utilisateursListe' => $utilisateursListe
        ]);
    }


    /**
     * @brief Creer un utilisateur
     */
    public function ajouterUtilisateur()
    {
        if (!isset($_SESSION['utilisateur'])) {
                header('Location: index.php?controleur=utilisateur&methode=authentification');
                exit();
            }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Récupération des données du formulaire
            $donnees = [
                'pseudo'       => $_POST['pseudo'] ?? '',
                'email'        => $_POST['email'] ?? '',
                'adresse'      => $_POST['adresse'] ?? '',
                'motDePasse'   => $_POST['motDePasse'] ?? '',
                'numTelephone' => $_POST['numTelephone'] ?? '',
                'estMaitre'    => isset($_POST['estMaitre']) ? 1 : 0,
                'estPromeneur' => isset($_POST['estPromeneur']) ? 1 : 0
            ];

            // RÈGLES DE VALIDATION
            $regles = [
                'pseudo' => [
                    'obligatoire' => true,
                    'type' => 'string',
                    'longueur_min' => 2,
                    'longueur_max' => 30
                ],

                'email' => [
                    'obligatoire' => true,
                    'format' => FILTER_VALIDATE_EMAIL,  
                    'longueur_max' => 100
                ],

                'photoProfil' => [
                    'obligatoire' => false,
                    'type' => 'file',
                    'formats_acceptes' => ['image/jpeg', 'image/png', 'image/gif'],
                    'taille_max' => 2 * 1024 * 1024 // 2MB
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

            // TRAITEMENT DE L'UPLOAD DE LA PHOTO DE PROFIL (optionnelle)
            $photoProfilFilename = null;
            if (isset($_FILES['photoProfil']) && $_FILES['photoProfil']['error'] === UPLOAD_ERR_OK) {
                $tmpName = $_FILES['photoProfil']['tmp_name'];
                // Vérifier que c'est bien une image
                $imageInfo = @getimagesize($tmpName);
                if ($imageInfo === false) {
                    $erreurs[] = "Le fichier téléchargé n'est pas une image valide.";
                    $template = $this->getTwig()->load('formulaire_creerCompte.html.twig');
                    echo $template->render(['erreurs' => $erreurs, 'old' => $donnees]);
                    return;
                }

                $originalName = $_FILES['photoProfil']['name'];
                $extension = pathinfo($originalName, PATHINFO_EXTENSION);
                $safeName = time() . '_' . bin2hex(random_bytes(6)) . '.' . $extension;
                $uploadDir = __DIR__ . '/../images/utilisateur/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                $destination = $uploadDir . $safeName;
                if (!move_uploaded_file($tmpName, $destination)) {
                    $erreurs[] = "Impossible d'enregistrer la photo de profil.";
                    $template = $this->getTwig()->load('formulaire_creerCompte.html.twig');
                    echo $template->render(['erreurs' => $erreurs, 'old' => $donnees]);
                    return;
                }
                $photoProfilFilename = $safeName;
            }

            // CRÉATION DE L’OBJET UTILISATEUR (note : ordre des paramètres correspond à Utilisateur::__construct)
            $nouvelUtilisateur = new Utilisateur(
                null,
                $donnees['email'],
                $donnees['estMaitre'],
                $donnees['estPromeneur'],
                $donnees['adresse'],
                $donnees['motDePasse'],
                $donnees['numTelephone'],
                $donnees['pseudo'],
                $photoProfilFilename
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
       
    /**
     * @brief Supprime un utilisateur
     */
    public function supprimerUtilisateur()
    {
        if (!isset($_SESSION['utilisateur'])) {
                header('Location: index.php?controleur=utilisateur&methode=authentification');
                exit();
            }

        $id_utilisateur = $_GET['id_utilisateur'];

        // Supprimer l'utilisateur de la base de données
        $managerutilisateur = new UtilisateurDAO($this->getPDO());
        $managerutilisateur->supprimerUtilisateur($id_utilisateur);

        // Rediriger vers la liste des utilisateurs
        header('Location: index.php?action=afficherAllUtilisateurs');
        exit();
    }
       
    /**
     * @brief Affiche le formulaire de modification d'un utilisateur
     */
    public function afficherFormulaire()
    {
        if (!isset($_SESSION['utilisateur'])) {
                header('Location: index.php?controleur=utilisateur&methode=authentification');
                exit();
            }

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

    /**
     * @brief Affiche le formulaire de modification d'un utilisateur
     */
    public function afficherModif()
    {
        if (!isset($_SESSION['utilisateur'])) {
                header('Location: index.php?controleur=utilisateur&methode=authentification');
                exit();
            }
            
        // Afficher le formulaire de modification d'utilisateur
        $id_utilisateur = $_GET['id_utilisateur'] ?? 1;

        // Récupérer l'utilisateur depuis la base de données
        $managerutilisateur = new UtilisateurDAO($this->getPDO());
        $utilisateur = $managerutilisateur->findById($id_utilisateur);

        $template = $this->getTwig()->load('utilisateurModifier.html.twig');
        echo $template->render([
            'utilisateur' => $utilisateur
        ]);
    }
       
    /**
     * @brief Modifie l'email d'un utilisateur
     */
    public function modifierEmail()
    {
        if (isset($_SESSION['utilisateur'])) {
            $utilisateurConnecte = unserialize($_SESSION['utilisateur']);
            $this->getTwig()->addGlobal('utilisateurConnecte', $utilisateurConnecte);

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

                $id_utilisateur = $utilisateurConnecte->getId();
                
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
        else {
            // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
            header('Location: index.php?action=authentification');
            exit();
        }
    }
       
    /**
     * @brief Permet l'authentification d'un utilisateur
     */
    public function authentification()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupération des données du formulaire
            $donneesFormulaire = [
                'email' => htmlspecialchars($_POST['email'], ENT_QUOTES,) ?? null,
                'motDePasse' => htmlspecialchars($_POST['motDePasse'], ENT_QUOTES) ?? null,
            ];

            // Validation des données
            $regles = [];
            $validator = new Validator($regles);
            $erreurs = $validator->validerConnexion($donneesFormulaire);
            if ($erreurs) {
                $template = $this->getTwig()->load('connexion.html.twig');
                echo $template->render(['erreurs' => $erreurs]);
                return;
            }

            $mail = $donneesFormulaire['email'];
            $mdp = $donneesFormulaire['motDePasse'];
            $managerUtilisateur = new UtilisateurDao($this->getPdo());

            if(!$managerUtilisateur->estActif($mail) && $managerUtilisateur->emailExist($mail)){ 
                $erreurs[] = "Votre compte est desactivé";
                $template = $this->getTwig()->load('connexion.html.twig');
                echo $template->render(['erreurs' => $erreurs]);
                return;
            }

            $utilisateur = $managerUtilisateur->findByEmail($mail);
            if ($utilisateur && password_verify($mdp, $utilisateur->getMotDePasse())) {
                // $managerUtilisateur->verifierDerniereSauvegarde();
                $_SESSION['utilisateur'] = serialize($utilisateur);
                $this->getTwig()->addGlobal('utilisateurConnecte', $utilisateur);
                header("Location: index.php");
            } else {
                $template = $this->getTwig()->load('connexion.html.twig');
                $erreurs[] = "L'adresse mail ou le mot de passe est incorrect";
                echo $template->render(['erreurs' => $erreurs]);
            }
        }
        else {
            $template = $this->getTwig()->load('connexion.html.twig');
            echo $template->render();
        }
    }
    // public function authentification()
    // {
    //     $erreurs = [];

    //     // === Si formulaire envoyé ===
    //     if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    //         $email = trim($_POST['email'] ?? '');
    //         $motDePasse = $_POST['motDePasse'] ?? '';

    //         $regles = [
    //             'email' => [
    //                 'obligatoire' => true,
    //                 'format' => FILTER_VALIDATE_EMAIL,
    //                 'longueur_max' => 100
    //             ],
    //             'motDePasse' => [
    //                 'obligatoire' => true,
    //                 'type' => 'string',
    //                 'longueur_min' => 8,
    //                 'longueur_max' => 50
    //             ]
    //         ];

    //         $manager = new UtilisateurDAO($this->getPDO());

    //         try {
    //             // Tentative d’authentification
    //             if ($manager->authentification($email, $motDePasse)) {

    //                 // Récupération des données utilisateur
    //                 $utilisateur = $manager->findByEmail($email);

    //                 if ($utilisateur) {
    //                     // On évite de stocker le mot de passe en session
    //                     $utilisateur->setMotDePasse(null);

    //                     $_SESSION['user'] = [
    //                         'id_utilisateur' => $utilisateur->getId(),
    //                         'email' => $utilisateur->getEmail(),
    //                         'estMaitre' => $utilisateur->getEstMaitre(),
    //                         'estPromeneur' => $utilisateur->getEstPromeneur(),
    //                         'pseudo' => $utilisateur->getPseudo(),
    //                         'photoProfil' => $utilisateur->getPhotoProfil()
    //                     ];

    //                     header(
    //                         'Location: index.php'
    //                     );
    //                     exit();
    //                 }

    //             } else {
    //                 // Email ou mot de passe incorrect
    //                 $erreurs[] = "Email ou mot de passe incorrect.";
    //             }

    //         } catch (Exception $e) {

    //             // Gestion du cas où le compte est temporairement désactivé
    //             if ($e->getMessage() === "compte_desactive") {

    //                 $utilisateur = $manager->findByEmail($email);
    //                 $tempsDernierEchec = $utilisateur ? $utilisateur->getDateDernierEchecConnexion() : null;
    //                 $tempsRestant = $manager->tempsRestantAvantReactivationCompte($tempsDernierEchec);

    //                 $minutes = floor($tempsRestant / 60);
    //                 $secondes = $tempsRestant % 60;

    //                 $erreurs[] = "Votre compte est temporairement désactivé. 
    //                             Réessayez dans {$minutes} minutes et {$secondes} secondes.";
    //             } 
    //             else {
    //                 // Erreur inattendue
    //                 $erreurs[] = "Erreur inattendue : " . $e->getMessage();
    //             }
    //         }

    //         // Réaffichage du formulaire avec erreurs et valeurs précédentes
    //         $template = $this->getTwig()->load('formulaire_authentification.html.twig');
    //         echo $template->render([
    //             'erreurs' => $erreurs,
    //             'old' => ['email' => $email]
    //         ]);

    //         return;
    //     }

    //     // Si le formulaire n’est pas envoyé : affichage simple
    //     $success = null;

    //     if (isset($_GET['inscription']) && $_GET['inscription'] === 'success') {
    //         $success = 'Votre compte a bien été créé. Veuillez vous connecter.';
    //     }

    //     $template = $this->getTwig()->load('formulaire_authentification.html.twig');
    //     echo $template->render([
    //         'erreurs' => [],
    //         'success' => $success
    //     ]);
    // }
}