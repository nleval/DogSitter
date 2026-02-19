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
     * @brief ?string $titre Titre personnalisé de la conversation.
     */
    private ?string $titre = null;
    
    /**
     * @brief ?string $dernier_message Dernier message de la conversation (propriété dynamique)
     */
    public ?string $dernier_message = null;
    
    /**
     * @brief ?string $date_dernier_message Date du dernier message (propriété dynamique)
     */
    public ?string $date_dernier_message = null;
    
    /**
     * @brief ?Utilisateur $autre_utilisateur L'autre utilisateur de la conversation (propriété dynamique)
     */
    public ?Utilisateur $autre_utilisateur = null;
        
    /**
     * @brief Constructeur de la classe Conversation.
     *
     * @param ?int    $idConversation Identifiant unique de la conversation.
     * @param ?string $dateCreation Date de création de la conversation.
     */
    public function __construct(?int $idConversation = null, ?string $dateCreation = null, ?string $titre = null) {
        $this->idConversation = $idConversation;
        $this->dateCreation = $dateCreation;
        $this->titre = $titre;
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

    /**
     * @brief Récupère le titre personnalisé de la conversation.
     *
     * @return ?string Titre.
     */
    public function getTitre(): ?string {
        return $this->titre;
    }

    /**
     * @brief Définit le titre personnalisé de la conversation.
     *
     * @param ?string $titre Titre.
     */
    public function setTitre(?string $titre): void {
        $this->titre = $titre;
    }
}
?>
