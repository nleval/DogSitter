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

/**
 * Récupère les conversations auxquelles participe un utilisateur
 */
public function findByUtilisateur(int $idUtilisateur): array
{
    $conversations = [];

    $sql = "
        SELECT DISTINCT c.*
        FROM " . PREFIXE_TABLE . "Conversation c
        JOIN " . PREFIXE_TABLE . "Message m
            ON c.id_conversation = m.id_conversation
        WHERE m.id_utilisateur = :idUtilisateur
        ORDER BY c.date_creation DESC
    ";

    $stmt = $this->pdo->prepare($sql);
    $stmt->bindParam(':idUtilisateur', $idUtilisateur, PDO::PARAM_INT);
    $stmt->execute();

    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
        $conversations[] = new Conversation(
            $row['id_conversation'],
            $row['date_creation']
        );
    }

    return $conversations;
}

/**
 * @brief Crée une nouvelle conversation entre deux utilisateurs
 * @param int $userMain Identifiant du maître
 * @param int $userSecond Identifiant du promeneur
 * @return int|false ID de la conversation créée, false sinon
 */
public function createConversation(int $userMain, int $userSecond)
{
    try {
        // Vérifier si une conversation n'existe pas déjà entre les deux utilisateurs
        $stmtCheck = $this->pdo->prepare("
            SELECT c.id_conversation 
            FROM " . PREFIXE_TABLE . "Conversation c
            JOIN " . PREFIXE_TABLE . "Creer creer1 ON c.id_conversation = creer1.id_conversation
            JOIN " . PREFIXE_TABLE . "Creer creer2 ON c.id_conversation = creer2.id_conversation
            WHERE (creer1.id_utilisateur = :user1 AND creer2.id_utilisateur = :user2)
               OR (creer1.id_utilisateur = :user2 AND creer2.id_utilisateur = :user1)
            LIMIT 1
        ");
        
        $stmtCheck->execute([
            ':user1' => $userMain,
            ':user2' => $userSecond
        ]);
        
        if ($stmtCheck->rowCount() > 0) {
            $existing = $stmtCheck->fetch(PDO::FETCH_ASSOC);
            error_log("✓ Conversation already exists between users {$userMain} and {$userSecond}");
            return $existing['id_conversation'];
        }

        // Créer une nouvelle conversation
        $dateCreation = date('Y-m-d H:i:s');
        
        $stmt = $this->pdo->prepare("
            INSERT INTO " . PREFIXE_TABLE . "Conversation (date_creation)
            VALUES (:date_creation)
        ");
        
        if ($stmt->execute([':date_creation' => $dateCreation])) {
            $id_conversation = $this->pdo->lastInsertId();
            
            // Ajouter les deux utilisateurs à la conversation
            $stmtCreer = $this->pdo->prepare("
                INSERT INTO " . PREFIXE_TABLE . "Creer (id_utilisateur, id_conversation)
                VALUES (:id_utilisateur, :id_conversation)
            ");
            
            // Ajouter le maître
            $stmtCreer->execute([
                ':id_utilisateur' => $userMain,
                ':id_conversation' => $id_conversation
            ]);
            
            // Ajouter le promeneur
            $stmtCreer->execute([
                ':id_utilisateur' => $userSecond,
                ':id_conversation' => $id_conversation
            ]);
            
            error_log("✓ Conversation created: {$id_conversation} between users {$userMain} and {$userSecond}");
            return $id_conversation;
        }
        
        return false;
    } catch (PDOException $e) {
        error_log("❌ Error creating conversation: " . $e->getMessage());
        return false;
    }
}

}
?>
