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

    echo $this->getTwig()->render('messages.html.twig', [
        'messageListe'   => $messageListe,
        'idConversation' => $id_conversation
    ]);
}

}