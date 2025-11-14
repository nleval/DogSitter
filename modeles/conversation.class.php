<?php
    class Conversation {
        // Attributs de la conversation
        private ?int $idConversation;
        private ?string $dateCreation;
        
        // Constructeur de la classe Conversation
        public function __construct(?int $idConversation = null, ?string $dateCreation = null) {
            $this->idConversation = $idConversation;
            $this->dateCreation = $dateCreation;
        }
        
        //Encapsulation

        public function getIdConversation(): ?string {
            return $this->idConversation;
        }
        public function setIdConversation(?string $idConversation): void {
            $this->idConversation = $idConversation;
        }
        public function getDateCreation(): ?string {
            return $this->dateCreation;
        }
        public function setDateCreation(?string $dateCreation): void {
            $this->dateCreation = $dateCreation;
        }
    }
?>
