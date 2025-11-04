<?php
    class Conversation {
        // Attributs de la conversation
        private ?string $idConversation;
        private ?date $dateCreation;
        
        // Constructeur de la classe Conversation
        public function __construct(?string $idConversation = null, ?date $dateCreation = null) {
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
        public function getDateCreation(): ?date {
            return $this->dateCreation;
        }
        public function setDateCreation(?date $dateCreation): void {
            $this->dateCreation = $dateCreation;
        }
    }
?>
