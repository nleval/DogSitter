<?php
/**
 * @file controller_message.class.php
 * @author Pigeon Aymeric
 * @brief Gère les opérations liées aux messages
 * @version 1.0
 * @date 2025-12-18
 */
class ControllerMessage extends Controller
{   
    /**
     * @brief Constructeur du contrôleur de messages
     * @param \Twig\Environment $twig Moteur de templates Twig.
     * @param \Twig\Loader\FilesystemLoader $loader Chargeur de templates Twig.
     */
    public function __construct(\Twig\Environment $twig, \Twig\Loader\FilesystemLoader $loader) {
        parent::__construct($twig, $loader);
    }

    /**
     * @brief Afficher un message spécifique
     * @param int $id_message Identifiant du message à afficher
     */
    public function afficherMessage()
    {
        // Récupérer un utilisateur spécifique depuis la base de données
        $managerMessage = new MessageDAO($this->getPDO());
        $message = $managerMessage->findById(1); // Exemple avec l'ID 1

        // Rendre la vue avec l'utilisateur
        $template = $this->getTwig()->load('message.html.twig');
        echo $template->render([
            'message' => $message
        ]);
    }

    /**
     * @brief Afficher tous les messages
     */
    public function afficherAllMessage()
    {
        // Récupérer tous les utilisateurs depuis la base de données
        $managerMessage = new MessageDAO($this->getPDO());
        $messageListe = $managerMessage->findAll();

        // Rendre la vue avec les utilisateurs
        $template = $this->getTwig()->load('message.html.twig');
        echo $template->render([
            'messageListe' => $messageListe
        ]);
    }

    /**
 * Afficher tous les messages d'une conversation
 */
public function afficherParConversation($id_conversation = null)
{
    /* ===============================
       Vérification utilisateur connecté
    =============================== */
    if (!isset($_SESSION['utilisateur'])) {
            header('Location: index.php?controleur=utilisateur&methode=authentification');
            exit();
        }

    /* ===============================
       Récupération id conversation
    =============================== */
    if ($id_conversation === null) {
        $id_conversation = isset($_GET['id_conversation'])
            ? (int) $_GET['id_conversation']
            : null;
    }

    if (!$id_conversation) {
        http_response_code(404);
        echo $this->getTwig()->render('404.html.twig', [
            'message' => 'Conversation non trouvée.'
        ]);
        return;
    }

    /* ===============================
       Récupération messages
    =============================== */
    $messageDAO = new MessageDAO($this->getPDO());
    $messageListe = $messageDAO->findByConversation($id_conversation);
    
    /* ===============================
       Récupération infos conversation
    =============================== */
    $conversationDAO = new ConversationDAO($this->getPDO());
    $conversation = $conversationDAO->findById($id_conversation);
    
    // Récupérer l'autre utilisateur de la conversation
    $utilisateurConnecte = unserialize($_SESSION['utilisateur']);
    $idUtilisateurConnecte = $utilisateurConnecte->getId();

    // Ouvrir une conversation = considérer les notifications de messages comme consultées
    $notificationDAO = new NotificationDAO($this->getPDO());
    $notificationDAO->marquerCommeLuesParType($idUtilisateurConnecte, 'nouveau_message');
    
    $utilisateurDAO = new UtilisateurDAO($this->getPDO());
    
    // Récupérer les deux participants
    $participants = $conversationDAO->getParticipants($id_conversation);
    
    error_log("DEBUG afficherParConversation - ID Conversation: {$id_conversation}");
    error_log("DEBUG Participants: " . json_encode($participants));
    error_log("DEBUG Utilisateur connecté ID: {$idUtilisateurConnecte}");
    
    // SÉCURITÉ : Vérifier que l'utilisateur connecté fait partie de la conversation
    if (!in_array($idUtilisateurConnecte, $participants)) {
        http_response_code(403);
        $template = $this->getTwig()->load('403.html.twig');
        echo $template->render(['message' => "Accès refusé : vous ne faites pas partie de cette conversation."]);
        return;
    }
    
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

    echo $this->getTwig()->render('conversation_active.html.twig', [
        'messageListe'   => $messageListe,
        'idConversation' => $id_conversation,
        'conversation' => $conversation,
        'autreUtilisateur' => $autreUtilisateur,
        'utilisateurConnecte' => $utilisateurConnecte
    ]);
}

/**
 * @brief Envoyer un message dans une conversation
 */
public function envoyerMessage()
{
    if (!isset($_SESSION['utilisateur'])) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Non authentifié']);
        exit();
    }

    $utilisateurConnecte = unserialize($_SESSION['utilisateur']);
    $idUtilisateurConnecte = $utilisateurConnecte->getId();
    
    $id_conversation = isset($_POST['id_conversation']) ? (int) $_POST['id_conversation'] : null;
    $contenu = isset($_POST['contenu']) ? trim($_POST['contenu']) : '';
    $edit_message_id = isset($_POST['edit_message_id']) ? (int) $_POST['edit_message_id'] : null;
    
    if (!$id_conversation || empty($contenu)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Paramètres manquants']);
        exit();
    }
    
    // Vérifier que l'utilisateur fait partie de la conversation
    $conversationDAO = new ConversationDAO($this->getPDO());
    if (!$conversationDAO->utilisateurFaitPartieConversation($id_conversation, $idUtilisateurConnecte)) {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'Accès refusé']);
        exit();
    }
    
    $messageDAO = new MessageDAO($this->getPDO());

    // Modifier un message existant si demandé
    if ($edit_message_id) {
        $message = $messageDAO->findById($edit_message_id);

        if (!$message || (int) $message->getIdUtilisateur() !== (int) $idUtilisateurConnecte) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Accès refusé']);
            exit();
        }

        if ((int) $message->getIdConversation() !== (int) $id_conversation) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Conversation invalide']);
            exit();
        }

        $messageDAO->modifierMessage($edit_message_id, $contenu);

        header("Location: index.php?controleur=message&methode=afficherParConversation&id_conversation={$id_conversation}");
        exit();
    }

    // Créer le message
    $id_message = $messageDAO->creerMessage($idUtilisateurConnecte, $id_conversation, $contenu);
    
    if ($id_message) {
        // Récupérer l'autre participant pour notification
        $conversationDAO = new ConversationDAO($this->getPDO());
        $id_destinataire = $conversationDAO->getAutreParticipant($id_conversation, $idUtilisateurConnecte);
        
        if ($id_destinataire) {
            $notificationDAO = new NotificationDAO($this->getPDO());
            $notificationDAO->creerNotification(
                $id_destinataire,
                'Nouveau message',
                "Vous avez reçu un nouveau message de " . $utilisateurConnecte->getPseudo(),
                'nouveau_message',
                null,
                null,
                $idUtilisateurConnecte
            );
        }
        
        // Rediriger vers la conversation
        header("Location: index.php?controleur=message&methode=afficherParConversation&id_conversation={$id_conversation}");
        exit();
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'envoi']);
        exit();
    }
}

/**
 * @brief Compte les messages non lus (notifications de type 'nouveau_message')
 * AJAX endpoint
 */
public function getUnreadMessageCount()
{
    if (!isset($_SESSION['utilisateur'])) {
        http_response_code(401);
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'count' => 0]);
        exit();
    }

    $utilisateurConnecte = unserialize($_SESSION['utilisateur']);
    $idUtilisateur = $utilisateurConnecte->getId();

    // Compter via le DAO (pas de SQL dans le contrôleur)
    $notificationDAO = new NotificationDAO($this->getPDO());
    $count = $notificationDAO->compterNonLuesParType($idUtilisateur, 'nouveau_message');

    header('Content-Type: application/json');
    echo json_encode([
        'success' => true,
        'count' => $count,
        'userId' => $idUtilisateur
    ]);
    exit();
}

/**
 * @brief Modifier un message (auteur uniquement)
 */
public function modifierMessage()
{
    if (!isset($_SESSION['utilisateur'])) {
        header('Location: index.php?controleur=utilisateur&methode=authentification');
        exit();
    }

    $utilisateurConnecte = unserialize($_SESSION['utilisateur']);
    $idUtilisateur = $utilisateurConnecte->getId();

    $id_message = isset($_POST['id_message']) ? (int) $_POST['id_message'] : null;
    $contenu = isset($_POST['contenu']) ? trim($_POST['contenu']) : '';

    if (!$id_message || $contenu === '') {
        http_response_code(400);
        echo "Parametres manquants.";
        return;
    }

    $messageDAO = new MessageDAO($this->getPDO());
    $message = $messageDAO->findById($id_message);

    if (!$message || (int) $message->getIdUtilisateur() !== (int) $idUtilisateur) {
        http_response_code(403);
        echo "Acces refuse.";
        return;
    }

    $messageDAO->modifierMessage($id_message, $contenu);

    $id_conversation = (int) $message->getIdConversation();
    header("Location: index.php?controleur=message&methode=afficherParConversation&id_conversation={$id_conversation}");
    exit();
}

/**
 * @brief Supprimer un message (auteur uniquement)
 */
public function supprimerMessage()
{
    if (!isset($_SESSION['utilisateur'])) {
        header('Location: index.php?controleur=utilisateur&methode=authentification');
        exit();
    }

    $utilisateurConnecte = unserialize($_SESSION['utilisateur']);
    $idUtilisateur = $utilisateurConnecte->getId();

    $id_message = isset($_POST['id_message']) ? (int) $_POST['id_message'] : null;

    if (!$id_message) {
        http_response_code(400);
        echo "Parametre manquant.";
        return;
    }

    $messageDAO = new MessageDAO($this->getPDO());
    $message = $messageDAO->findById($id_message);

    if (!$message || (int) $message->getIdUtilisateur() !== (int) $idUtilisateur) {
        http_response_code(403);
        echo "Acces refuse.";
        return;
    }

    $id_conversation = (int) $message->getIdConversation();
    $messageDAO->supprimerMessage($id_message);

    header("Location: index.php?controleur=message&methode=afficherParConversation&id_conversation={$id_conversation}");
    exit();
}

}