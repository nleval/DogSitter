<?php

class ControllerMessage extends Controller
{
    public function __construct(\Twig\Environment $twig, \Twig\Loader\FilesystemLoader $loader) {
        parent::__construct($twig, $loader);
    }

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
}