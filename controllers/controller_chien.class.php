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
            $nom_chien = $_POST['nom_chien'] ?? '';
            $race = $_POST['race'] ?? '';
            $taille = $_POST['taille'] ?? '';
            $poids = $_POST['poids'] ?? '';

            // Validation basique
            if (empty($nom_chien) || empty($race)) {
                $erreur = "Le nom et la race sont obligatoires.";
                $template = $this->getTwig()->load('ajouter_chien.html.twig');
                echo $template->render(['erreur' => $erreur]);
                return;
            }

            // Traitement de la photo
            $cheminPhoto = '';
            if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
                $fichier = $_FILES['photo'];
                
                // Vérifier le type de fichier
                $mimeTypes = ['image/jpeg', 'image/png', 'image/gif'];
                if (!in_array($fichier['type'], $mimeTypes)) {
                    $erreur = "Le format de fichier n'est pas accepté (JPEG, PNG, GIF uniquement).";
                    $template = $this->getTwig()->load('ajouter_chien.html.twig');
                    echo $template->render(['erreur' => $erreur]);
                    return;
                }

                // Vérifier la taille du fichier (max 2MB)
                if ($fichier['size'] > 2 * 1024 * 1024) {
                    $erreur = "Le fichier est trop volumineux (maximum 2MB).";
                    $template = $this->getTwig()->load('ajouter_chien.html.twig');
                    echo $template->render(['erreur' => $erreur]);
                    return;
                }

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
                    $erreur = "Erreur lors de l'upload du fichier.";
                    $template = $this->getTwig()->load('ajouter_chien.html.twig');
                    echo $template->render(['erreur' => $erreur]);
                    return;
                }
            }

            // Créer un nouvel objet Chien
            $chien = new Chien(
                null,
                $nom_chien,
                $poids,
                $taille,
                $race,
                $cheminPhoto,
                $id_utilisateur
            );

            // Sauvegarder dans la base de données
            $chiensDAO = new ChienDAO($this->getPDO());
            if ($chiensDAO->create($chien)) {
                header('Location: index.php?controleur=utilisateur&methode=afficherTonUtilisateur');
                exit();
            } else {
                $erreur = "Erreur lors de l'ajout du chien.";
                $template = $this->getTwig()->load('ajouter_chien.html.twig');
                echo $template->render(['erreur' => $erreur]);
            }
        } else {
            header('Location: index.php?controleur=chien&methode=afficherFormulaire');
            exit();
        }
    }
}

?>