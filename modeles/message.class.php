<?php
    class Message {
        // Attributs d'un message
        private ?string $idMessage;
        private ?string $contenu;
        private ?string $dateHeureMessage;
        private ?string $idUtilisateur;
        private ?string $idConversation;
        
        // Constructeur de la classe Message
        public function __construct(?string $idMessage = null, ?string $contenu = null, ?string $dateHeureMessage = null, ?string $idUtilisateur = null, ?string $idConversation = null
        ) {
            $this->idMessage = $idMessage;
            $this->contenu = $contenu;
            $this->dateHeureMessage = $dateHeureMessage;
            $this->idUtilisateur = $idUtilisateur;
            $this->idConversation = $idConversation;
        }

        //Encapsulation

        public function getIdMessage(): ?string {
            return $this->idMessage;
        }
        public function setIdMessage(?string $idMessage): void {
            $this->idMessage = $idMessage;
        }
        public function getContenu(): ?string {
            return $this->contenu;
        }
        public function setContenu(?string $contenu): void {
            $this->contenu = $contenu;
        }
        public function getDateHeureMessage(): ?string {
            return $this->dateHeureMessage;
        }
        public function setDateHeureMessage(?string $dateHeureMessage): void {
            $this->dateHeureMessage = $dateHeureMessage;
        }
        public function getIdUtilisateur(): ?string {
            return $this->idUtilisateur;
        }
        public function setIdUtilisateur(?string $idUtilisateur): void {
            $this->idUtilisateur = $idUtilisateur;
        }
        public function getIdConversation(): ?string {
            return $this->idConversation;
        }
        public function setIdConversation(?string $idConversation): void {
            $this->idConversation = $idConversation;
        }
    }
?>
