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
        session_destroy();
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

        // Récupérer les chiens de l'utilisateur
        $chiensDAO = new ChienDAO($this->getPDO());
        $chiens = $chiensDAO->findByUtilisateur($id_utilisateur);

        $managerAvis = new AvisDAO($this->getPDO());
        $statsAvis = $managerAvis->getStatsParUtilisateurNote($id_utilisateur);

        // Rendre la vue avec l'utilisateur et ses chiens
        $template = $this->getTwig()->load('utilisateur.html.twig');
        echo $template->render([
            'utilisateur' => $utilisateur,
            'chiens' => $chiens,
            'statsAvis' => $statsAvis
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
        $id_utilisateur = isset($_GET['id_utilisateur']) ? (int) $_GET['id_utilisateur'] : 0;

        // Récupérer un utilisateur spécifique depuis la base de données
        $managerutilisateur = new UtilisateurDAO($this->getPDO());
        $utilisateur = $managerutilisateur->findById($id_utilisateur);

        // Récupérer les chiens de l'utilisateur
        $chiensDAO = new ChienDAO($this->getPDO());
        $chiens = $chiensDAO->findByUtilisateur($id_utilisateur);

        $managerAvis = new AvisDAO($this->getPDO());
        $statsAvis = $managerAvis->getStatsParUtilisateurNote((int) $id_utilisateur);
        
        // Vérifier si c'est un profil public (pas le sien)
        $utilisateurConnecte = unserialize($_SESSION['utilisateur']);
        $est_profil_public = ($utilisateurConnecte->getId() != $id_utilisateur);

        // Rendre la vue avec l'utilisateur et ses chiens
        $template = $this->getTwig()->load('utilisateur.html.twig');
        echo $template->render([
            'utilisateur' => $utilisateur,
            'chiens' => $chiens,
            'statsAvis' => $statsAvis,
            'est_profil_public' => $est_profil_public
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

        $managerAvis = new AvisDAO($this->getPDO());
        $statsParUtilisateur = [];

        foreach ($utilisateursListe as $utilisateur) {
            $statsParUtilisateur[$utilisateur->getId()] = $managerAvis->getStatsParUtilisateurNote($utilisateur->getId());
        }

        // Rendre la vue avec les utilisateurs
        $template = $this->getTwig()->load('utilisateurs.html.twig');
        echo $template->render([
            'utilisateursListe' => $utilisateursListe,
            'statsParUtilisateur' => $statsParUtilisateur
        ]);
    }


    /**
     * @brief Creer un utilisateur
     */
    public function ajouterUtilisateur()
    {
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
                    'longueur_min' => 5,
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
                    'longueur_max' => 120,
                    'pattern' => '/^[^,]+,\s*[A-Za-zÀ-ÿ][A-Za-zÀ-ÿ\s\'’\-\.]{1,}$/u'
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
                    'longueur_min' => 10,
                    'longueur_max' => 10,
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
                $template = $this->getTwig()->load('inscription.html.twig');
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
                    $template = $this->getTwig()->load('inscription.html.twig');
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
                    $template = $this->getTwig()->load('inscription.html.twig');
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

                $template = $this->getTwig()->load('inscription.html.twig');
                echo $template->render([
                    'erreurs' => $erreurs,
                    'old' => $donnees
                ]);
                return;
            }
        }

        $template = $this->getTwig()->load('inscription.html.twig');
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

        $utilisateurConnecte = unserialize($_SESSION['utilisateur']);
        $id_utilisateur_connecte = $utilisateurConnecte->getId();
        
        $id_utilisateur = $_GET['id_utilisateur'] ?? null;

        // SÉCURITÉ : Vérifier que l'utilisateur ne peut supprimer que son propre compte
        if (!$id_utilisateur || $id_utilisateur != $id_utilisateur_connecte) {
            http_response_code(403);
            $template = $this->getTwig()->load('403.html.twig');
            echo $template->render(['message' => "Accès refusé : vous ne pouvez supprimer que votre propre compte."]);
            return;
        }

        // Supprimer l'utilisateur de la base de données
        $managerutilisateur = new UtilisateurDAO($this->getPDO());
        $managerutilisateur->supprimerUtilisateur($id_utilisateur);
        
        // Détruire la session
        session_destroy();

        // Rediriger vers l'accueil
        header('Location: index.php');
        exit();
    }
       
    /**
     * @brief Affiche le formulaire d'inscription
     */
    public function afficherInscription()
    {
        $template = $this->getTwig()->load('inscription.html.twig');
        echo $template->render();
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
        $id_utilisateur = $_GET['id_utilisateur'] ?? null;
        
        // Vérifier que l'utilisateur connecté modifie bien son propre profil
        $utilisateurConnecte = unserialize($_SESSION['utilisateur']);
        if (!$id_utilisateur || $id_utilisateur != $utilisateurConnecte->getId()) {
            http_response_code(403);
            $template = $this->getTwig()->load('403.html.twig');
            echo $template->render(['message' => "Accès refusé : vous ne pouvez modifier que votre propre profil."]);
            return;
        }

        // Récupérer l'utilisateur depuis la base de données
        $managerutilisateur = new UtilisateurDAO($this->getPDO());
        $utilisateur = $managerutilisateur->findById($id_utilisateur);

        $template = $this->getTwig()->load('utilisateurModifier.html.twig');
        echo $template->render([
            'utilisateur' => $utilisateur
        ]);
    }
       
    /**
     * @brief Modifie l'ensemble du profil utilisateur via un formulaire unique
     */
    public function modifierProfil()
    {
        if (!isset($_SESSION['utilisateur'])) {
            header('Location: index.php?controleur=utilisateur&methode=authentification');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controleur=utilisateur&methode=afficherTonUtilisateur');
            exit();
        }

        $managerutilisateur = new UtilisateurDAO($this->getPDO());
        $utilisateurConnecte = unserialize($_SESSION['utilisateur']);
        $id_utilisateur = $utilisateurConnecte->getId();

        $utilisateurCourant = $managerutilisateur->findById($id_utilisateur);

        $email = trim($_POST['email'] ?? (string) $utilisateurCourant->getEmail());
        $pseudo = trim($_POST['pseudo'] ?? (string) $utilisateurCourant->getPseudo());
        $numTelephone = trim($_POST['numTelephone'] ?? (string) ($utilisateurCourant->getNumTelephone() ?? ''));
        $adresse = trim($_POST['adresse'] ?? (string) ($utilisateurCourant->getAdresse() ?? ''));
        $motDePasse = trim($_POST['motDePasse'] ?? '');
        $estMaitre = isset($_POST['estMaitre']) ? 1 : 0;
        $estPromeneur = isset($_POST['estPromeneur']) ? 1 : 0;

        $regles = [
            'email' => [
                'obligatoire' => true,
                'type' => 'string',
                'longueur_min' => 5,
                'longueur_max' => 100,
                'format' => FILTER_VALIDATE_EMAIL,
            ],
            'pseudo' => [
                'obligatoire' => true,
                'type' => 'string',
                'longueur_min' => 2,
                'longueur_max' => 30,
            ],
            'numTelephone' => [
                'obligatoire' => false,
                'type' => 'string',
                'longueur_min' => 10,
                'longueur_max' => 10,
                'pattern' => '/^0[1-9](\d{2}){4}$/',
            ],
            'adresse' => [
                'obligatoire' => false,
                'type' => 'string',
                'longueur_min' => 5,
                'longueur_max' => 120,
                'pattern' => '/^[^,]+,\s*[A-Za-zÀ-ÿ][A-Za-zÀ-ÿ\s\'’\-\.]{1,}$/u',
            ],
            'motDePasse' => [
                'obligatoire' => false,
                'type' => 'string',
                'longueur_min' => 8,
                'longueur_max' => 50,
            ],
        ];

        $validator = new Validator($regles);
        $donnees = [
            'email' => $email,
            'pseudo' => $pseudo,
            'numTelephone' => $numTelephone,
            'adresse' => $adresse,
            'motDePasse' => $motDePasse,
        ];

        $validator->valider($donnees);
        $messagesErreurs = $validator->getMessagesErreurs();

        if (!$estMaitre && !$estPromeneur) {
            $messagesErreurs[] = "Vous devez sélectionner au moins un rôle (maître ou/et promeneur).";
        }

        if ($motDePasse !== '' && !$managerutilisateur->estRobuste($motDePasse)) {
            $messagesErreurs[] = "Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial.";
        }

        $utilisateurEmail = $managerutilisateur->findByEmail($email);
        if ($utilisateurEmail !== null && (int)$utilisateurEmail->getId() !== (int)$id_utilisateur) {
            $messagesErreurs[] = "Cette adresse email est déjà utilisée. Veuillez en choisir une autre.";
        }

        if (isset($_FILES['photo']) && $_FILES['photo']['error'] !== UPLOAD_ERR_NO_FILE) {
            if ($_FILES['photo']['error'] !== 0) {
                $messagesErreurs[] = "Erreur lors de l'envoi de la photo.";
            } else {
                $validator = new Validator([]);
                $messagesPhoto = [];
                $photoValide = $validator->validerUploadEtPhoto($_FILES['photo'], $messagesPhoto);
                if (!$photoValide) {
                    $messagesErreurs = array_merge($messagesErreurs, $messagesPhoto);
                }
            }
        }

        if (!empty($messagesErreurs)) {
            $utilisateurAffichage = $managerutilisateur->findById($id_utilisateur);
            if ($utilisateurAffichage) {
                $utilisateurAffichage->setEmail($email);
                $utilisateurAffichage->setPseudo($pseudo);
                $utilisateurAffichage->setNumTelephone($numTelephone);
                $utilisateurAffichage->setAdresse($adresse);
                $utilisateurAffichage->setEstMaitre($estMaitre);
                $utilisateurAffichage->setEstPromeneur($estPromeneur);
            }

            $template = $this->getTwig()->load('utilisateurModifier.html.twig');
            echo $template->render([
                'messagesErreurs' => $messagesErreurs,
                'utilisateur' => $utilisateurAffichage
            ]);
            return;
        }

        try {
            $managerutilisateur->modifierChamp($id_utilisateur, 'email', $email);
            $managerutilisateur->modifierChamp($id_utilisateur, 'pseudo', $pseudo);
            $managerutilisateur->modifierChamp($id_utilisateur, 'numTelephone', $numTelephone);
            $managerutilisateur->modifierChamp($id_utilisateur, 'adresse', $adresse);
            $managerutilisateur->modifierChamp($id_utilisateur, 'estMaitre', $estMaitre);
            $managerutilisateur->modifierChamp($id_utilisateur, 'estPromeneur', $estPromeneur);

            if ($motDePasse !== '') {
                $motDePasseHache = password_hash($motDePasse, PASSWORD_BCRYPT);
                $managerutilisateur->modifierChamp($id_utilisateur, 'motDePasse', $motDePasseHache);
            }

            if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
                $userPseudo = preg_replace('/[^a-zA-Z0-9_-]/', '', $pseudo);
                $fileExtension = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
                $uploadDir = 'images/utilisateur/';
                $fileName = $id_utilisateur . '_' . $userPseudo . '.' . $fileExtension;
                $filePath = $uploadDir . $fileName;

                $anciennePhoto = glob($uploadDir . $id_utilisateur . '_*.{jpg,jpeg,png,gif}', GLOB_BRACE);
                foreach ($anciennePhoto as $fichier) {
                    if (is_file($fichier)) {
                        unlink($fichier);
                    }
                }

                if (move_uploaded_file($_FILES['photo']['tmp_name'], $filePath)) {
                    $managerutilisateur->modifierChamp($id_utilisateur, 'photoProfil', $fileName);
                    $utilisateurConnecte->setPhotoProfil($fileName);
                }
            }
        } catch (PDOException $e) {
            if ((string)$e->getCode() === '23000') {
                $messagesErreurs[] = "Cette adresse email est déjà utilisée. Veuillez en choisir une autre.";
            } else {
                $messagesErreurs[] = "Une erreur inattendue est survenue lors de la mise à jour du profil.";
            }

            $utilisateurAffichage = $managerutilisateur->findById($id_utilisateur);
            if ($utilisateurAffichage) {
                $utilisateurAffichage->setEmail($email);
                $utilisateurAffichage->setPseudo($pseudo);
                $utilisateurAffichage->setNumTelephone($numTelephone);
                $utilisateurAffichage->setAdresse($adresse);
                $utilisateurAffichage->setEstMaitre($estMaitre);
                $utilisateurAffichage->setEstPromeneur($estPromeneur);
            }

            $template = $this->getTwig()->load('utilisateurModifier.html.twig');
            echo $template->render([
                'messagesErreurs' => $messagesErreurs,
                'utilisateur' => $utilisateurAffichage
            ]);
            return;
        }

        $utilisateurConnecte->setEmail($email);
        $utilisateurConnecte->setPseudo($pseudo);
        $utilisateurConnecte->setNumTelephone($numTelephone);
        $utilisateurConnecte->setAdresse($adresse);
        $utilisateurConnecte->setEstMaitre($estMaitre);
        $utilisateurConnecte->setEstPromeneur($estPromeneur);
        $_SESSION['utilisateur'] = serialize($utilisateurConnecte);

        header('Location: index.php?controleur=utilisateur&methode=afficherTonUtilisateur');
        exit();
    }
    
    /**
     * @brief Affiche tous les avis reçus par un promeneur
     */
    public function afficherAvisPromeneur()
    {
        // Récupérer l'ID du promeneur à afficher
        $id_promeneur = isset($_GET['id_utilisateur']) ? (int) $_GET['id_utilisateur'] : null;
        
        if (!$id_promeneur) {
            http_response_code(404);
            echo $this->getTwig()->render('404.html.twig', ['message' => 'Utilisateur non trouvé.']);
            return;
        }
        
        // Récupérer l'utilisateur
        $managerUtilisateur = new UtilisateurDAO($this->getPDO());
        $utilisateur = $managerUtilisateur->findById($id_promeneur);
        
        if (!$utilisateur) {
            http_response_code(404);
            echo $this->getTwig()->render('404.html.twig', ['message' => 'Utilisateur non trouvé.']);
            return;
        }
        
        // Vérifier que c'est un promeneur
        if (!$utilisateur->getEstPromeneur()) {
            http_response_code(404);
            echo $this->getTwig()->render('404.html.twig', ['message' => 'Utilisateur non trouvé.']);
            return;
        }
        
        // Récupérer tous les avis
        $managerAvis = new AvisDAO($this->getPDO());
        $avis = $managerAvis->trouverParIdUtilisateurNote($id_promeneur);
        $stats = $managerAvis->getStatsParUtilisateurNote($id_promeneur);
        
        // Récupérer les infos des auteurs des avis
        foreach ($avis as $review) {
            $review->auteur = $managerUtilisateur->findById($review->getIdUtilisateur());
        }
        
        // Rendre la vue
        echo $this->getTwig()->render('avis_promeneur.html.twig', [
            'utilisateur' => $utilisateur,
            'avis' => $avis,
            'stats' => $stats
        ]);
    }
       
    /**
     * @brief Permet l'authentification d'un utilisateur
     */
    public function authentification()
    {
        $template = $this->getTwig()->load('connexion.html.twig');

        // Si redirection après inscription
        $success = null;
        if (isset($_GET['inscription']) && $_GET['inscription'] === 'success') {
            $success = 'Votre compte a été créé avec succès. Vous pouvez vous connecter.';
        }

        // Si formulaire envoyé
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $motDePasse = $_POST['motDePasse'] ?? '';

            // Validation légère côté contrôleur
            $validator = new Validator([]);
            $erreurs = $validator->validerConnexion(['email' => $email, 'motDePasse' => $motDePasse]);

            $manager = new UtilisateurDAO($this->getPDO());

            if ($erreurs) {
                echo $template->render(['erreurs' => $erreurs, 'old' => ['email' => $email]] + ($success ? ['success' => $success] : []));
                return;
            }

            try {
                // utilise la logique du DAO qui gère tentatives et désactivation
                if ($manager->authentification($email, $motDePasse)) {
                    $utilisateur = $manager->findByEmail($email);
                    if ($utilisateur) {
                        $utilisateur->setMotDePasse(null);
                        $_SESSION['utilisateur'] = serialize($utilisateur);
                        $this->getTwig()->addGlobal('utilisateurConnecte', $utilisateur);
                        header('Location: index.php');
                        exit();
                    }
                } else {
                    $erreurs[] = "L'adresse mail ou le mot de passe est incorrect";
                }
            } catch (Exception $e) {
                if ($e->getMessage() === 'compte_desactive') {
                    $utilisateur = $manager->findByEmail($email);
                    $tempsDernierEchec = $utilisateur ? $utilisateur->getDateDernierEchecConnexion() : null;
                    $tempsRestant = $manager->tempsRestantAvantReactivationCompte($tempsDernierEchec);
                    $minutes = floor($tempsRestant / 60);
                    $secondes = $tempsRestant % 60;
                    $erreurs[] = "Votre compte est temporairement désactivé. Réessayez dans {$minutes} minutes et {$secondes} secondes.";
                } else {
                    $erreurs[] = "Erreur inattendue : " . $e->getMessage();
                }
            }

            echo $template->render(['erreurs' => $erreurs, 'old' => ['email' => $email], 'success' => $success]);
            return;
        }

        // GET : affichage du formulaire (éventuellement message de succès d'inscription)
        echo $template->render($success ? ['success' => $success] : []);
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