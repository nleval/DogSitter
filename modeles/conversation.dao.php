<?php
/**
 * @file conversation.dao.php
 * @author Pigeon Aymeric
 * @brief Gestion de la base de donees pour les conversations
 * @version 1.0
 * @date 2025-12-18
 */
class ConversationDAO
{
    /**
     * @brief ?PDO $pdo Instance PDO pour accéder à la base de données.
     */
    private ?PDO $pdo;

    /**
     * @brief Constructeur du DAO Conversation.
     *
     * @param ?PDO $pdo Connexion PDO (optionnelle).
     */
    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo;
    }

    /**
     * @brief Récupère l'objet PDO.
     *
     * @return ?PDO Connexion PDO.
     */
    public function getPdo(): ?PDO
    {
        return $this->pdo;
    }

    /**
     * @brief Définit l'objet PDO.
     *
     * @param ?PDO $pdo Connexion PDO.
     */
    public function setPdo(?PDO $pdo): void
    {
        $this->pdo = $pdo;
    }

    /**
     * @brief Récupère toutes les conversations.
     *
     * @return Conversation[] Tableau d'objets Conversation.
     */
    public function findAll(): array
    {
        $conversations = [];
        $stmt = $this->pdo->prepare("SELECT * FROM " . PREFIXE_TABLE . "Conversation");
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($results as $row) {
            $conversations[] = new Conversation(
                $row['id_conversation'],
                $row['date_creation']
            );
        }

        return $conversations;
    }

    /**
     * @brief Recherche une conversation par son identifiant.
     *
     * @param int|string $idConversation Identifiant de la conversation.
     * @return ?Conversation Objet Conversation ou null si non trouvé.
     */
    public function findById($idConversation): ?Conversation
    {
        $stmt = $this->pdo->prepare("SELECT * FROM " . PREFIXE_TABLE . "Conversation WHERE id_conversation = :idConversation");
        $stmt->bindParam(':idConversation', $idConversation, PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new Conversation(
                $row['id_conversation'],
                $row['date_creation']
            );
        }

        return null;
    }
}
?>
