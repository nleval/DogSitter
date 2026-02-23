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

    /**
     * @brief Afficher le formulaire d'ajout de chien
     */
    public function afficherFormulaire()
    {
        // Vérifier que l'utilisateur est connecté
        if (!isset($_SESSION['utilisateur'])) {
            header('Location: index.php?controleur=utilisateur&methode=authentification');
            exit();
        }

        // Rendre la vue avec le formulaire
        $template = $this->getTwig()->load('ajouter_chien.html.twig');
        echo $template->render([]);
    }

    /**
     * @brief Ajouter un chien
     */
    public function ajouterChien()
    {
        // Vérifier que l'utilisateur est connecté
        if (!isset($_SESSION['utilisateur'])) {
            header('Location: index.php?controleur=utilisateur&methode=authentification');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupérer l'ID utilisateur de la session
            $utilisateurConnecte = unserialize($_SESSION['utilisateur']);
            $id_utilisateur = $utilisateurConnecte->getId();

            // Récupération des données du formulaire
            $donnees = [
                'nom_chien' => trim($_POST['nom_chien'] ?? ''),
                'race' => trim($_POST['race'] ?? ''),
                'taille' => trim($_POST['taille'] ?? ''),
                'poids' => trim($_POST['poids'] ?? '')
            ];

            $regles = [
                'nom_chien' => [
                    'obligatoire' => true,
                    'type' => 'string',
                    'longueur_min' => 2,
                    'longueur_max' => 50,
                    'pattern' => '/^[A-Za-zÀ-ÿ0-9\'\-\s]+$/u'
                ],
                'race' => [
                    'obligatoire' => true,
                    'type' => 'string',
                    'longueur_min' => 2,
                    'longueur_max' => 80,
                    'pattern' => '/^[A-Za-zÀ-ÿ0-9\'\-\s]+$/u'
                ],
                'taille' => [
                    'obligatoire' => false,
                    'type' => 'string',
                    'pattern' => '/^(Très petit|Petit|Moyen|Grand|Très grand)?$/'
                ],
                'poids' => [
                    'obligatoire' => false,
                    'type' => 'numeric',
                    'plage_min' => 0.1,
                    'plage_max' => 120
                ]
            ];

            $validator = new Validator($regles);
            $valide = $validator->valider($donnees);
            $erreurs = $validator->getMessagesErreurs();

            if (isset($_FILES['photo']) && $_FILES['photo']['error'] !== UPLOAD_ERR_NO_FILE) {
                if (!$validator->validerUploadEtPhoto($_FILES['photo'], $erreurs)) {
                    $valide = false;
                }
            }

            if (!$valide) {
                $template = $this->getTwig()->load('ajouter_chien.html.twig');
                echo $template->render([
                    'erreurs' => $erreurs,
                    'old' => $donnees
                ]);
                return;
            }

            // Traitement de la photo
            $cheminPhoto = '';
            if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
                $fichier = $_FILES['photo'];
                
                // Créer le répertoire s'il n'existe pas
                $uploadDir = 'images/chiens/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                // Générer un nom de fichier unique
                $extension = pathinfo($fichier['name'], PATHINFO_EXTENSION);
                $cheminPhoto = 'chien_' . $id_utilisateur . '_' . time() . '.' . $extension;
                $uploadPath = $uploadDir . $cheminPhoto;

                // Déplacer le fichier
                if (!move_uploaded_file($fichier['tmp_name'], $uploadPath)) {
                    $template = $this->getTwig()->load('ajouter_chien.html.twig');
                    echo $template->render([
                        'erreurs' => ["Erreur lors de l'upload du fichier."],
                        'old' => $donnees
                    ]);
                    return;
                }
            }

            // Créer un nouvel objet Chien
            $chien = new Chien(
                null,
                $donnees['nom_chien'],
                $donnees['poids'] !== '' ? $donnees['poids'] : null,
                $donnees['taille'] !== '' ? $donnees['taille'] : null,
                $donnees['race'],
                $cheminPhoto,
                $id_utilisateur
            );

            // Sauvegarder dans la base de données
            $chiensDAO = new ChienDAO($this->getPDO());
            if ($chiensDAO->creer($chien)) {
                header('Location: index.php?controleur=utilisateur&methode=afficherTonUtilisateur');
                exit();
            } else {
                $template = $this->getTwig()->load('ajouter_chien.html.twig');
                echo $template->render([
                    'erreurs' => ["Erreur lors de l'ajout du chien."],
                    'old' => $donnees
                ]);
            }
        } else {
            header('Location: index.php?controleur=chien&methode=afficherFormulaire');
            exit();
        }
    }

    /**
     * @brief Afficher le formulaire de modification de chien
     */
    public function afficherFormulaireModification()
    {
        if (!isset($_SESSION['utilisateur'])) {
            header('Location: index.php?controleur=utilisateur&methode=authentification');
            exit();
        }

        $id_chien = isset($_GET['id_chien']) ? (int) $_GET['id_chien'] : null;
        if (!$id_chien) {
            header('Location: index.php?controleur=utilisateur&methode=afficherTonUtilisateur');
            exit();
        }

        $utilisateurConnecte = unserialize($_SESSION['utilisateur']);
        $managerChien = new ChienDAO($this->getPDO());
        $chien = $managerChien->findById($id_chien);

        if (!$chien || $chien->getid_Utilisateur() !== $utilisateurConnecte->getId()) {
            http_response_code(403);
            $template = $this->getTwig()->load('403.html.twig');
            echo $template->render(['message' => "Acces refuse."]);
            return;
        }

        $template = $this->getTwig()->load('ajouter_chien.html.twig');
        echo $template->render([
            'chien' => $chien,
            'mode' => 'edit'
        ]);
    }

    /**
     * @brief Modifier un chien
     */
    public function modifierChien()
    {
        if (!isset($_SESSION['utilisateur'])) {
            header('Location: index.php?controleur=utilisateur&methode=authentification');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controleur=utilisateur&methode=afficherTonUtilisateur');
            exit();
        }

        $id_chien = isset($_GET['id_chien']) ? (int) $_GET['id_chien'] : null;
        if (!$id_chien) {
            header('Location: index.php?controleur=utilisateur&methode=afficherTonUtilisateur');
            exit();
        }

        $utilisateurConnecte = unserialize($_SESSION['utilisateur']);
        $id_utilisateur = $utilisateurConnecte->getId();

        $managerChien = new ChienDAO($this->getPDO());
        $chienExistant = $managerChien->findById($id_chien);

        if (!$chienExistant || $chienExistant->getid_Utilisateur() !== $id_utilisateur) {
            http_response_code(403);
            $template = $this->getTwig()->load('403.html.twig');
            echo $template->render(['message' => "Acces refuse."]);
            return;
        }

        $donnees = [
            'nom_chien' => trim($_POST['nom_chien'] ?? ''),
            'race' => trim($_POST['race'] ?? ''),
            'taille' => trim($_POST['taille'] ?? ''),
            'poids' => trim($_POST['poids'] ?? '')
        ];

        $regles = [
            'nom_chien' => [
                'obligatoire' => true,
                'type' => 'string',
                'longueur_min' => 2,
                'longueur_max' => 50,
                'pattern' => '/^[A-Za-zÀ-ÿ0-9\'\-\s]+$/u'
            ],
            'race' => [
                'obligatoire' => true,
                'type' => 'string',
                'longueur_min' => 2,
                'longueur_max' => 80,
                'pattern' => '/^[A-Za-zÀ-ÿ0-9\'\-\s]+$/u'
            ],
            'taille' => [
                'obligatoire' => false,
                'type' => 'string',
                'pattern' => '/^(Très petit|Petit|Moyen|Grand|Très grand)?$/'
            ],
            'poids' => [
                'obligatoire' => false,
                'type' => 'numeric',
                'plage_min' => 0.1,
                'plage_max' => 120
            ]
        ];

        $validator = new Validator($regles);
        $valide = $validator->valider($donnees);
        $erreurs = $validator->getMessagesErreurs();

        if (isset($_FILES['photo']) && $_FILES['photo']['error'] !== UPLOAD_ERR_NO_FILE) {
            if (!$validator->validerUploadEtPhoto($_FILES['photo'], $erreurs)) {
                $valide = false;
            }
        }

        if (!$valide) {
            $template = $this->getTwig()->load('ajouter_chien.html.twig');
            echo $template->render([
                'erreurs' => $erreurs,
                'chien' => $chienExistant,
                'old' => $donnees,
                'mode' => 'edit'
            ]);
            return;
        }

        $cheminPhoto = $chienExistant->getCheminPhoto() ?? '';
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $fichier = $_FILES['photo'];

            $uploadDir = 'images/chiens/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $extension = pathinfo($fichier['name'], PATHINFO_EXTENSION);
            $nouveauNom = 'chien_' . $id_utilisateur . '_' . time() . '.' . $extension;
            $uploadPath = $uploadDir . $nouveauNom;

            if (!move_uploaded_file($fichier['tmp_name'], $uploadPath)) {
                $template = $this->getTwig()->load('ajouter_chien.html.twig');
                echo $template->render([
                    'erreurs' => ["Erreur lors de l'upload du fichier."],
                    'chien' => $chienExistant,
                    'old' => $donnees,
                    'mode' => 'edit'
                ]);
                return;
            }

            if (!empty($cheminPhoto)) {
                $ancien = $uploadDir . $cheminPhoto;
                if (is_file($ancien)) {
                    @unlink($ancien);
                }
            }

            $cheminPhoto = $nouveauNom;
        }

        $chien = new Chien(
            $id_chien,
            $donnees['nom_chien'],
            $donnees['poids'] !== '' ? $donnees['poids'] : null,
            $donnees['taille'] !== '' ? $donnees['taille'] : null,
            $donnees['race'],
            $cheminPhoto,
            $id_utilisateur
        );

        if ($managerChien->mettreAJour($chien)) {
            header('Location: index.php?controleur=utilisateur&methode=afficherTonUtilisateur');
            exit();
        }

        $template = $this->getTwig()->load('ajouter_chien.html.twig');
        echo $template->render([
            'erreurs' => ["Erreur lors de la modification du chien."],
            'chien' => $chienExistant,
            'old' => $donnees,
            'mode' => 'edit'
        ]);
    }

    /**
     * @brief Supprimer un chien
     */
    public function supprimerChien()
    {
        if (!isset($_SESSION['utilisateur'])) {
            header('Location: index.php?controleur=utilisateur&methode=authentification');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controleur=utilisateur&methode=afficherTonUtilisateur');
            exit();
        }

        $id_chien = isset($_POST['id_chien']) ? (int) $_POST['id_chien'] : null;
        if (!$id_chien) {
            header('Location: index.php?controleur=utilisateur&methode=afficherTonUtilisateur');
            exit();
        }

        $utilisateurConnecte = unserialize($_SESSION['utilisateur']);
        $id_utilisateur = $utilisateurConnecte->getId();

        $managerChien = new ChienDAO($this->getPDO());
        $chien = $managerChien->findById($id_chien);

        if (!$chien || $chien->getid_Utilisateur() !== $id_utilisateur) {
            http_response_code(403);
            $template = $this->getTwig()->load('403.html.twig');
            echo $template->render(['message' => "Acces refuse."]);
            return;
        }

        if ($managerChien->supprimerParIdEtUtilisateur($id_chien, $id_utilisateur)) {
            $cheminPhoto = $chien->getCheminPhoto();
            if (!empty($cheminPhoto)) {
                $filePath = 'images/chiens/' . $cheminPhoto;
                if (is_file($filePath)) {
                    @unlink($filePath);
                }
            }
        }

        header('Location: index.php?controleur=utilisateur&methode=afficherTonUtilisateur');
        exit();
    }
}

?>