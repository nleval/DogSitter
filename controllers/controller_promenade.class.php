<?php
/**
 * @file controller_promenade.class.php
 * @author Boisseau Robin
 * @brief Gère les opérations liées aux promenades
 * @version 1.0
 * @date 2025-12-18
 */
class ControllerPromenade extends Controller
{    
    /**
     * @brief Constructeur du contrôleur de promenades.
     * @param \Twig\Environment $twig Moteur de templates Twig.
     * @param \Twig\Loader\FilesystemLoader $loader Chargeur de templates Twig.
     */
    public function __construct(\Twig\Environment $twig, \Twig\Loader\FilesystemLoader $loader)
    {
        parent::__construct($twig, $loader);
    }

    /**
     * @brief Afficher une promenade spécifique
     * @param int $id_promenade Identifiant de la promenade à afficher
     */
    public function afficherPromenade($id_promenade = null)
    {

        $sessionUser = $_SESSION['user'] ?? null;
        $id_utilisateur = null;

        if (is_array($sessionUser)) {
            $id_utilisateur = $sessionUser['id_utilisateur'] ?? null;
        } elseif (is_object($sessionUser) && method_exists($sessionUser, 'getId')) {
            $id_utilisateur = $sessionUser->getId();
        }

        if (!$id_utilisateur) {
            header('Location: index.php?controleur=utilisateur&methode=authentification');
            exit;
        }

        if ($id_promenade === null) {
            $id_promenade = isset($_GET['id_promenade']) ? (int)$_GET['id_promenade'] : null;
        }

        if (!$id_promenade) {
            http_response_code(404);
            echo $this->getTwig()->render('404.html.twig', [
                'message' => 'Promenade non trouvée.'
            ]);
            return;
        }

        /* ===============================
           Récupération des données
        =============================== */
        $promenadeDAO  = new PromenadeDAO($this->getPDO());
        $annonceDAO    = new AnnonceDAO($this->getPDO());
        $chienDAO      = new ChienDAO($this->getPDO());
        $utilisateurDAO = new UtilisateurDAO($this->getPDO());

        $promenade = $promenadeDAO->findById($id_promenade);

        if (!$promenade) {
            http_response_code(404);
            echo $this->getTwig()->render('404.html.twig', [
                'message' => 'Promenade introuvable.'
            ]);
            return;
        }

        $annonce = $annonceDAO->findById($promenade->getid_annonce());

        if (!$annonce) {
            http_response_code(404);
            echo $this->getTwig()->render('404.html.twig', [
                'message' => 'Annonce introuvable.'
            ]);
            return;
        }

        $chiens = $chienDAO->findByAnnonce($promenade->getid_chien());
        $proprietaire = $utilisateurDAO->findById($promenade->getid_proprietaire());
        echo $this->getTwig()->render('promenade.html.twig', [
            'promenade'   => $promenade,
            'annonce'     => $annonce,
            'chiens'      => $chiens,
            'proprietaire'=> $proprietaire
        ]);
    }

    /**
     * @brief Afficher toutes les promenades
     */
    public function afficherAllPromenades()
    {
        $promenadeDAO   = new PromenadeDAO($this->getPDO());
        $chienDAO       = new ChienDAO($this->getPDO());
        $utilisateurDAO = new UtilisateurDAO($this->getPDO());
        $annonceDAO     = new AnnonceDAO($this->getPDO());

        $promenades = $promenadeDAO->findAll();
        $listeComplete = [];

        foreach ($promenades as $promenade) {

            $annonce = $annonceDAO->findById($promenade->getid_annonce());
            $chien = $chienDAO->findById($promenade->getid_chien());
            $proprietaire = $utilisateurDAO->findById($promenade->getid_proprietaire());

            $listeComplete[] = [
                'promenade'   => $promenade,
                'annonce'     => $annonce,
                'chien'       => $chien,
                'proprietaire'=> $proprietaire
            ];
        }

        echo $this->getTwig()->render('promenades.html.twig', [
            'listePromenades' => $listeComplete
        ]);
    }
}
