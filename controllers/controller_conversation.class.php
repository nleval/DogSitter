<?php
/**
 * @file controller_conversation.class.php
 * @author Pigeon Aymeric
 * @brief Gère les opérations liées aux conversations.
 * @version 1.0
 * @date 2025-12-18
 */
class ControllerConversation extends Controller
{
    /**
     * @brief Constructeur du contrôleur de conversation.
     * @param \Twig\Environment $twig Moteur de templates Twig.
     * @param \Twig\Loader\FilesystemLoader $loader Chargeur de templates Twig.
     */
    public function __construct(\Twig\Environment $twig, \Twig\Loader\FilesystemLoader $loader) {
        parent::__construct($twig, $loader);
    }

    /**
     * @brief Afficher une conversation spécifique
     * @param int $id_conversation Identifiant de la conversation à afficher
     */
    public function afficherConversation()
    {
        // Vérifier que l'utilisateur est connecté
        if (!isset($_SESSION['utilisateur'])) {
            header('Location: index.php?controleur=utilisateur&methode=authentification');
            exit();
        }

        $utilisateurConnecte = unserialize($_SESSION['utilisateur']);
        $idUtilisateurConnecte = $utilisateurConnecte->getId();
        
        // Récupérer l'ID de la conversation depuis GET
        $id_conversation = isset($_GET['id_conversation']) ? (int) $_GET['id_conversation'] : null;
        
        if (!$id_conversation) {
            http_response_code(404);
            echo $this->getTwig()->render('404.html.twig', ['message' => 'Conversation non trouvée.']);
            return;
        }
        
        // Récupérer la conversation
        $managerconversation = new ConversationDAO($this->getPDO());
        $conversation = $managerconversation->findById($id_conversation);
        
        if (!$conversation) {
            http_response_code(404);
            echo $this->getTwig()->render('404.html.twig', ['message' => 'Conversation non trouvée.']);
            return;
        }
        
        // SÉCURITÉ : Vérifier que l'utilisateur fait partie de la conversation
        if (!$managerconversation->utilisateurFaitPartieConversation($id_conversation, $idUtilisateurConnecte)) {
            http_response_code(403);
            $template = $this->getTwig()->load('403.html.twig');
            echo $template->render(['message' => "Accès refusé : vous ne faites pas partie de cette conversation."]);
            return;
        }

        // Récupérer les messages de la conversation
        $messageDAO = new MessageDAO($this->getPDO());
        $messageListe = $messageDAO->findByConversation($id_conversation);
        
        // Récupérer l'autre utilisateur de la conversation
        $utilisateurDAO = new UtilisateurDAO($this->getPDO());
        $participants = $managerconversation->getParticipants($id_conversation);
        
        $autreUtilisateur = null;
        foreach ($participants as $id_participant) {
            if ($id_participant != $idUtilisateurConnecte) {
                $autreUtilisateur = $utilisateurDAO->findById($id_participant);
                break;
            }
        }
        
        // Enrichir les messages avec les infos utilisateurs
        foreach ($messageListe as $message) {
            $message->utilisateur = $utilisateurDAO->findById($message->getIdUtilisateur());
        }

        // Rendre la vue avec la conversation
        echo $this->getTwig()->render('conversation_active.html.twig', [
            'conversation' => $conversation,
            'messageListe' => $messageListe,
            'idConversation' => $id_conversation,
            'autreUtilisateur' => $autreUtilisateur,
            'utilisateurConnecte' => $utilisateurConnecte
        ]);
    }

    /**
     * @brief Afficher une conversation spécifique
     */
    public function afficherAllConversation()
    {
        // Récupérer tous les utilisateurs depuis la base de données
        $managerconversation = new ConversationDAO($this->getPDO());
        $conversationListe = $managerconversation->findAll();

        // Rendre la vue avec les utilisateurs
        $template = $this->getTwig()->load('conversation.html.twig');
        echo $template->render([
            'conversationListe' => $conversationListe
        ]);
    }

    public function afficherMesConversations()
    {
        if (!isset($_SESSION['utilisateur'])) {
                header('Location: index.php?controleur=utilisateur&methode=authentification');
                exit();
            }

        $utilisateurConnecte = unserialize($_SESSION['utilisateur']);
        $idUtilisateur = $utilisateurConnecte->getId();

        // Ouvrir la messagerie = considérer les notifications de messages comme consultées
        $notificationDAO = new NotificationDAO($this->getPDO());
        $notificationDAO->marquerCommeLuesParType($idUtilisateur, 'nouveau_message');

        $managerConversation = new ConversationDAO($this->getPdo());
        $conversationListe = $managerConversation->findByUtilisateur($idUtilisateur);
    
        echo $this->getTwig()->render('messages.html.twig', [
            'conversationListe' => $conversationListe
        ]);
    }

    /**
     * @brief Créer une conversation avec un utilisateur et rediriger vers la page de messages
     */
    public function creerConversationAvecUtilisateur()
    {
        if (!isset($_SESSION['utilisateur'])) {
            header('Location: index.php?controleur=utilisateur&methode=authentification');
            exit();
        }

        $utilisateurConnecte = unserialize($_SESSION['utilisateur']);
        $idUtilisateurConnecte = $utilisateurConnecte->getId();
        
        $idAutreUtilisateur = isset($_GET['id_utilisateur']) ? (int) $_GET['id_utilisateur'] : null;
        
        if (!$idAutreUtilisateur) {
            http_response_code(400);
            echo "Utilisateur cible non spécifié.";
            exit();
        }
        
        // Créer ou récupérer la conversation
        $managerConversation = new ConversationDAO($this->getPdo());
        $id_conversation = $managerConversation->creerConversation($idUtilisateurConnecte, $idAutreUtilisateur);
        
        if ($id_conversation) {
            // Rediriger vers la conversation
            header("Location: index.php?controleur=message&methode=afficherParConversation&id_conversation={$id_conversation}");
            exit();
        } else {
            http_response_code(500);
            echo "Erreur lors de la création de la conversation.";
            exit();
        }
    }

    /**
     * @brief Renommer une conversation
     */
    public function renommerConversation()
    {
        if (!isset($_SESSION['utilisateur'])) {
            header('Location: index.php?controleur=utilisateur&methode=authentification');
            exit();
        }

        $utilisateurConnecte = unserialize($_SESSION['utilisateur']);
        $idUtilisateur = $utilisateurConnecte->getId();

        $id_conversation = isset($_POST['id_conversation']) ? (int) $_POST['id_conversation'] : null;
        $titre = isset($_POST['titre']) ? trim($_POST['titre']) : '';

        if (!$id_conversation || $titre === '') {
            http_response_code(400);
            echo "Parametres manquants.";
            return;
        }

        $longueurTitre = mb_strlen($titre);
        if ($longueurTitre < 2 || $longueurTitre > 80) {
            http_response_code(400);
            echo "Le titre doit contenir entre 2 et 80 caractères.";
            return;
        }

        $conversationDAO = new ConversationDAO($this->getPdo());
        if (!$conversationDAO->utilisateurFaitPartieConversation($id_conversation, $idUtilisateur)) {
            http_response_code(403);
            echo "Acces refuse.";
            return;
        }

        $conversationDAO->renommerConversation($id_conversation, $titre);

        header("Location: index.php?controleur=conversation&methode=afficherMesConversations");
        exit();
    }

    /**
     * @brief Supprimer une conversation
     */
    public function supprimerConversation()
    {
        if (!isset($_SESSION['utilisateur'])) {
            header('Location: index.php?controleur=utilisateur&methode=authentification');
            exit();
        }

        $utilisateurConnecte = unserialize($_SESSION['utilisateur']);
        $idUtilisateur = $utilisateurConnecte->getId();

        $id_conversation = isset($_POST['id_conversation']) ? (int) $_POST['id_conversation'] : null;

        if (!$id_conversation) {
            http_response_code(400);
            echo "Parametre manquant.";
            return;
        }

        $conversationDAO = new ConversationDAO($this->getPdo());
        if (!$conversationDAO->utilisateurFaitPartieConversation($id_conversation, $idUtilisateur)) {
            http_response_code(403);
            echo "Acces refuse.";
            return;
        }

        $conversationDAO->supprimerConversation($id_conversation);

        header("Location: index.php?controleur=conversation&methode=afficherMesConversations");
        exit();
    }


}