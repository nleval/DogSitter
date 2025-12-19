<?php
/**
 * @file controller_annonce.class.php
 * @author Pigeon Aymeric
 * @brief Classe gérant les conversations entre utilisateurs
 * @version 1.0
 * @date 2025-12-18
 */
class Conversation {
    /**
     * @brief ?int $idConversation Identifiant unique de la conversation.
     */
    private ?int $idConversation;

    /**
     * @brief ?string $dateCreation Date de création de la conversation.
     */
    private ?string $dateCreation;
        
    /**
     * @brief Constructeur de la classe Conversation.
     *
     * @param ?int    $idConversation Identifiant unique de la conversation.
     * @param ?string $dateCreation Date de création de la conversation.
     */
    public function __construct(?int $idConversation = null, ?string $dateCreation = null) {
        $this->idConversation = $idConversation;
        $this->dateCreation = $dateCreation;
    }
        
    /**
     * @brief Récupère l'identifiant de la conversation.
     *
     * @return ?int Identifiant de la conversation.
     */
    public function getIdConversation(): ?int {
        return $this->idConversation;
    }

    /**
     * @brief Définit l'identifiant de la conversation.
     *
     * @param ?int $idConversation Identifiant de la conversation.
     */
    public function setIdConversation(?int $idConversation): void {
        $this->idConversation = $idConversation;
    }

    /**
     * @brief Récupère la date de création de la conversation.
     *
     * @return ?string Date de création.
     */
    public function getDateCreation(): ?string {
        return $this->dateCreation;
    }

    /**
     * @brief Définit la date de création de la conversation.
     *
     * @param ?string $dateCreation Date de création.
     */
    public function setDateCreation(?string $dateCreation): void {
        $this->dateCreation = $dateCreation;
    }
}
?>
