<?php
/**
 * @file controller_promenade.class.php
 * @author DogSitter Team
 * @brief Gère les opérations liées aux promenades.
 * @version 1.0
 * @date 2025-02-17
 */

class ControllerPromenade extends Controller
{
    /**
     * @brief Constructeur du contrôleur de promenade.
     * @param \Twig\Environment $twig Moteur de templates Twig.
     * @param \Twig\Loader\FilesystemLoader $loader Chargeur de templates Twig.
     */
    public function __construct(\Twig\Environment $twig, \Twig\Loader\FilesystemLoader $loader)
    {
        parent::__construct($twig, $loader);
    }

    /**
     * @brief Afficher les promenades du promeneur connecté (filtrées par statut)
     */
    public function mesPromenades()
    {
        // Vérifier que l'utilisateur est connecté
        if (!isset($_SESSION['utilisateur'])) {
            header('Location: index.php?controleur=utilisateur&methode=authentification');
            exit();
        }

        $utilisateurConnecte = unserialize($_SESSION['utilisateur']);
        $idUtilisateurConnecte = $utilisateurConnecte->getId();
        
        // Vérifier que l'utilisateur est un promeneur
        if (!$utilisateurConnecte->getEstPromeneur()) {
            http_response_code(403);
            echo $this->getTwig()->render('403.html.twig', ['message' => 'Seuls les promeneurs peuvent accéder à cette page.']);
            return;
        }

        // Récupérer le filtre de statut depuis GET
        $statut = isset($_GET['statut']) ? (string) $_GET['statut'] : 'a_venir';

        $managerPromenade = new PromenadeDAO($this->getPDO());
        
        // Archiver automatiquement les promenades terminées dépassées
        $managerPromenade->archiverPromenadesDépassées();
        
        // Récupérer les promenades selon le statut
        $promenades = [];
        $title = '';
        
        // Récupérer toutes les promenades du promeneur
        $toutesLesPromenades = $managerPromenade->findByPromeneur($idUtilisateurConnecte);
        
        switch ($statut) {
            case 'a_venir':
                // Promenades pas encore commencées (date future, not archivee et not annulee)
                $now = new DateTime();
                $promenades = array_filter($toutesLesPromenades, function($p) use ($now) {
                    $datePromenade = $p->getDate_promenade();
                    return $datePromenade && $datePromenade > $now && 
                           $p->getStatut() !== 'archivee' && 
                           $p->getStatut() !== 'annulee';
                });
                $title = 'Promenades à venir';
                break;
                
            case 'en_cours':
                // Promenades en cours (date passée, not archivee et not annulee, not terminee)
                $now = new DateTime();
                $promenades = array_filter($toutesLesPromenades, function($p) use ($now) {
                    $datePromenade = $p->getDate_promenade();
                    return $datePromenade && $datePromenade <= $now && 
                           $p->getStatut() !== 'archivee' && 
                           $p->getStatut() !== 'annulee' &&
                           $p->getStatut() !== 'terminee';
                });
                $title = 'Promenades en cours';
                break;
                
            case 'archivee':
                $promenades = array_filter($toutesLesPromenades, fn($p) => $p->getStatut() === 'archivee');
                $title = 'Promenades archivées';
                break;

            case 'terminee':
                $promenades = array_filter($toutesLesPromenades, fn($p) => $p->getStatut() === 'terminee');
                $title = 'Promenades terminées';
                break;
                
            default:
                $statut = 'a_venir';
                $now = new DateTime();
                $promenades = array_filter($toutesLesPromenades, function($p) use ($now) {
                    $datePromenade = $p->getDate_promenade();
                    return $datePromenade && $datePromenade > $now && 
                           $p->getStatut() !== 'archivee' && 
                           $p->getStatut() !== 'annulee';
                });
                $title = 'Mes promenades';
        }

        // Enrichir les promenades avec les infos de l'annonce et du maître
        $managerAnnonce = new AnnonceDAO($this->getPDO());
        $managerUtilisateur = new UtilisateurDAO($this->getPDO());
        
        foreach ($promenades as $promenade) {
            if ($promenade->getId_annonce()) {
                $promenade->annonce = $managerAnnonce->findById($promenade->getId_annonce());
            }
            if ($promenade->getId_proprietaire()) {
                $promenade->maitre = $managerUtilisateur->findById($promenade->getId_proprietaire());
            }
        }

        // Rendre la vue
        echo $this->getTwig()->render('promenades_liste.html.twig', [
            'promenades' => array_values($promenades),
            'statut' => $statut,
            'title' => $title,
            'utilisateurConnecte' => $utilisateurConnecte
        ]);
    }

    /**
     * @brief Afficher les archives d'annonces du maître (annonces archivées)
     */
    public function archivesAnnonces()
    {
        // Rediriger vers afficherAnnoncesParUtilisateur avec filtre archivee
        if (!isset($_SESSION['utilisateur'])) {
            header('Location: index.php?controleur=utilisateur&methode=authentification');
            exit();
        }

        $utilisateurConnecte = unserialize($_SESSION['utilisateur']);
        header('Location: index.php?controleur=Annonce&methode=afficherAnnoncesParUtilisateur&id_utilisateur=' . $utilisateurConnecte->getId() . '&statut=archivee');
        exit();
    }

    /**
     * @brief Marquer une promenade comme terminée
     */
    public function marquerTerminee()
    {
        // Vérifier que l'utilisateur est connecté
        if (!isset($_SESSION['utilisateur'])) {
            header('Location: index.php?controleur=utilisateur&methode=authentification');
            exit();
        }

        $utilisateurConnecte = unserialize($_SESSION['utilisateur']);
        $idUtilisateurConnecte = $utilisateurConnecte->getId();
        
        // Récupérer l'ID de la promenade
        $id_promenade = isset($_GET['id_promenade']) 
            ? (int) $_GET['id_promenade'] 
            : (isset($_POST['id_promenade']) ? (int) $_POST['id_promenade'] : null);
        
        if (!$id_promenade) {
            header('HTTP/1.0 400 Bad Request');
            echo json_encode(['success' => false, 'message' => 'ID promenade manquant']);
            exit();
        }
        
        // Récupérer la promenade
        $managerPromenade = new PromenadeDAO($this->getPDO());
        $promenade = $managerPromenade->findById($id_promenade);
        
        if (!$promenade) {
            header('HTTP/1.0 404 Not Found');
            echo json_encode(['success' => false, 'message' => 'Promenade non trouvée']);
            exit();
        }
        
        // SÉCURITÉ : Vérifier que l'utilisateur est le promeneur
        if ($promenade->getId_promeneur() != $idUtilisateurConnecte) {
            header('HTTP/1.0 403 Forbidden');
            echo json_encode(['success' => false, 'message' => 'Non autorisé']);
            exit();
        }
        
        // Marquer comme terminée
        $success = $managerPromenade->marquerTerminee($id_promenade);
        
        if ($success) {
            header('Location: index.php?controleur=promenade&methode=mesPromenades&statut=terminee');
        } else {
            header('HTTP/1.0 500 Internal Server Error');
            echo json_encode(['success' => false, 'message' => 'Erreur lors de la mise à jour']);
        }
        exit();
    }

    /**
     * @brief Afficher une promenade spécifique
     */
    public function afficherPromenade()
    {
        // Vérifier que l'utilisateur est connecté
        if (!isset($_SESSION['utilisateur'])) {
            header('Location: index.php?controleur=utilisateur&methode=authentification');
            exit();
        }

        $utilisateurConnecte = unserialize($_SESSION['utilisateur']);
        $idUtilisateurConnecte = $utilisateurConnecte->getId();
        
        $id_promenade = isset($_GET['id_promenade']) ? (int) $_GET['id_promenade'] : null;
        
        if (!$id_promenade) {
            http_response_code(404);
            echo $this->getTwig()->render('404.html.twig', ['message' => 'Promenade non trouvée.']);
            return;
        }
        
        $managerPromenade = new PromenadeDAO($this->getPDO());
        $promenade = $managerPromenade->findById($id_promenade);
        
        if (!$promenade) {
            http_response_code(404);
            echo $this->getTwig()->render('404.html.twig', ['message' => 'Promenade non trouvée.']);
            return;
        }
        
        // SÉCURITÉ : Vérifier que l'utilisateur est impliqué
        if ($promenade->getId_promeneur() != $idUtilisateurConnecte && 
            $promenade->getId_proprietaire() != $idUtilisateurConnecte) {
            http_response_code(403);
            echo $this->getTwig()->render('403.html.twig', ['message' => 'Non autorisé']);
            return;
        }

        // Enrichir la promenade
        $managerAnnonce = new AnnonceDAO($this->getPDO());
        $managerUtilisateur = new UtilisateurDAO($this->getPDO());
        
        if ($promenade->getId_annonce()) {
            $promenade->annonce = $managerAnnonce->findById($promenade->getId_annonce());
        }
        if ($promenade->getId_proprietaire()) {
            $promenade->maitre = $managerUtilisateur->findById($promenade->getId_proprietaire());
        }
        if ($promenade->getId_promeneur()) {
            $promenade->promeneur = $managerUtilisateur->findById($promenade->getId_promeneur());
        }

        // Rendre la vue détaillée
        echo $this->getTwig()->render('promenade_details.html.twig', [
            'promenade' => $promenade,
            'utilisateurConnecte' => $utilisateurConnecte
        ]);
    }
}

