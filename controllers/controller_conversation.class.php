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
        // Récupérer une conversation spécifique depuis la base de données
        $managerconversation = new ConversationDAO($this->getPDO());
        $conversation = $managerconversation->findById(1); // Exemple avec l'ID 1

        // Rendre la vue avec la conversation
        $template = $this->getTwig()->load('conversation.html.twig');
        echo $template->render([
            'conversation' => $conversation
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
    if (!isset($_SESSION['id_utilisateur'])) {
        header("Location: ?controleur=Index&methode=render");
        exit;
    }

    $idUtilisateur = $_SESSION['id_utilisateur'];

    $managerConversation = new ConversationDAO($this->getPdo());
    $conversationListe = $managerConversation->findByUtilisateur($idUtilisateur);

    echo $this->getTwig()->render('messages.html.twig', [
        'conversationListe' => $conversationListe
    ]);
}


}