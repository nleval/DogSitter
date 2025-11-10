<?php

class ControllerConversation extends Controller
{
    public function __construct(\Twig\Environment $twig, \Twig\Loader\FilesystemLoader $loader) {
        parent::__construct($twig, $loader);
    }

    public function afficherConversation()
    {
        // Récupérer un utilisateur spécifique depuis la base de données
        $managerconversation = new ConversationDAO($this->getPDO());
        $conversation = $managerconversation->findById(1); // Exemple avec l'ID 1

        // Rendre la vue avec l'utilisateur
        $template = $this->getTwig()->load('conversation.html.twig');
        echo $template->render([
            'conversation' => $conversation
        ]);
    }

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
}