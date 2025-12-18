<?php
/**
 * @file controller_annonce.class.php
 * @author Pigeon Aymeric
 * @brief Classe représentant un message.
 * @version 1.0
 * @date 2025-12-18
 */
class Message {
    /**
     * @brief ?string $idMessage Identifiant unique du message.
     */
    private ?string $idMessage;

    /**
     * @brief ?string $contenu Contenu du message.
     */
    private ?string $contenu;

    /**
     * @brief ?string $dateHeureMessage Date et heure d'envoi du message.
     */
    private ?string $dateHeureMessage;

    /**
     * @brief ?string $idUtilisateur Identifiant de l'utilisateur auteur du message.
     */
    private ?string $idUtilisateur;

    /**
     * @brief ?string $idConversation Identifiant de la conversation associée.
     */
    private ?string $idConversation;
        
    /**
     * @brief Constructeur de la classe Message.
     *
     * @param ?string $idMessage Identifiant unique du message.
     * @param ?string $contenu Contenu du message.
     * @param ?string $dateHeureMessage Date et heure d'envoi du message.
     * @param ?string $idUtilisateur Identifiant de l'utilisateur auteur.
     * @param ?string $idConversation Identifiant de la conversation.
     */
    public function __construct(
        ?string $idMessage = null,
        ?string $contenu = null,
        ?string $dateHeureMessage = null,
        ?string $idUtilisateur = null,
        ?string $idConversation = null
    ) {
        $this->idMessage = $idMessage;
        $this->contenu = $contenu;
        $this->dateHeureMessage = $dateHeureMessage;
        $this->idUtilisateur = $idUtilisateur;
        $this->idConversation = $idConversation;
    }

    /**
     * @brief Récupère l'identifiant du message.
     *
     * @return ?string Identifiant du message.
     */
    public function getIdMessage(): ?string {
        return $this->idMessage;
    }

    /**
     * @brief Définit l'identifiant du message.
     *
     * @param ?string $idMessage Identifiant du message.
     */
    public function setIdMessage(?string $idMessage): void {
        $this->idMessage = $idMessage;
    }

    /**
     * @brief Récupère le contenu du message.
     *
     * @return ?string Contenu du message.
     */
    public function getContenu(): ?string {
        return $this->contenu;
    }

    /**
     * @brief Définit le contenu du message.
     *
     * @param ?string $contenu Contenu du message.
     */
    public function setContenu(?string $contenu): void {
        $this->contenu = $contenu;
    }

    /**
     * @brief Récupère la date et l'heure du message.
     *
     * @return ?string Date et heure du message.
     */
    public function getDateHeureMessage(): ?string {
        return $this->dateHeureMessage;
    }

    /**
     * @brief Définit la date et l'heure du message.
     *
     * @param ?string $dateHeureMessage Date et heure du message.
     */
    public function setDateHeureMessage(?string $dateHeureMessage): void {
        $this->dateHeureMessage = $dateHeureMessage;
    }

    /**
     * @brief Récupère l'identifiant de l'utilisateur auteur du message.
     *
     * @return ?string Identifiant de l'utilisateur.
     */
    public function getIdUtilisateur(): ?string {
        return $this->idUtilisateur;
    }

    /**
     * @brief Définit l'identifiant de l'utilisateur auteur du message.
     *
     * @param ?string $idUtilisateur Identifiant de l'utilisateur.
     */
    public function setIdUtilisateur(?string $idUtilisateur): void {
        $this->idUtilisateur = $idUtilisateur;
    }

    /**
     * @brief Récupère l'identifiant de la conversation associée.
     *
     * @return ?string Identifiant de la conversation.
     */
    public function getIdConversation(): ?string {
        return $this->idConversation;
    }

    /**
     * @brief Définit l'identifiant de la conversation associée.
     *
     * @param ?string $idConversation Identifiant de la conversation.
     */
    public function setIdConversation(?string $idConversation): void {
        $this->idConversation = $idConversation;
    }
}
?>
