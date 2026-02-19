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
                $row['date_creation'],
                $row['titre'] ?? null
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
                $row['date_creation'],
                $row['titre'] ?? null
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
        SELECT DISTINCT 
            c.id_conversation,
            c.date_creation,
            c.titre,
            (SELECT m.contenu 
             FROM " . PREFIXE_TABLE . "Message m 
             WHERE m.id_conversation = c.id_conversation 
             ORDER BY m.DateHeureMessage DESC 
             LIMIT 1) as dernier_message,
            (SELECT m.DateHeureMessage 
             FROM " . PREFIXE_TABLE . "Message m 
             WHERE m.id_conversation = c.id_conversation 
             ORDER BY m.DateHeureMessage DESC 
             LIMIT 1) as date_dernier_message,
            (SELECT creer2.id_utilisateur
             FROM " . PREFIXE_TABLE . "Creer creer2
             WHERE creer2.id_conversation = c.id_conversation 
             AND creer2.id_utilisateur != :idUtilisateur
             LIMIT 1) as id_autre_utilisateur
        FROM " . PREFIXE_TABLE . "Conversation c
        JOIN " . PREFIXE_TABLE . "Creer creer
            ON c.id_conversation = creer.id_conversation
        WHERE creer.id_utilisateur = :idUtilisateur
        ORDER BY date_dernier_message DESC
    ";

    $stmt = $this->pdo->prepare($sql);
    $stmt->bindParam(':idUtilisateur', $idUtilisateur, PDO::PARAM_INT);
    $stmt->execute();

    // Récupérer les infos des autres utilisateurs
    $utilisateurDAO = new UtilisateurDAO($this->pdo);

    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
        $conversation = new Conversation(
            $row['id_conversation'],
            $row['date_creation'],
            $row['titre'] ?? null
        );
        
        // Ajouter les métadonnées supplémentaires
        $conversation->dernier_message = $row['dernier_message'];
        $conversation->date_dernier_message = $row['date_dernier_message'];
        
        // Récupérer les infos de l'autre utilisateur
        if ($row['id_autre_utilisateur']) {
            $autreUtilisateur = $utilisateurDAO->findById($row['id_autre_utilisateur']);
            $conversation->autre_utilisateur = $autreUtilisateur;
        }
        
        $conversations[] = $conversation;
    }

    return $conversations;
}

    /**
     * @brief Renommer une conversation.
     * @param int $idConversation
     * @param string $titre
     * @return bool
     */
    public function renommerConversation(int $idConversation, string $titre): bool
    {
        $stmt = $this->pdo->prepare(
            "UPDATE " . PREFIXE_TABLE . "Conversation SET titre = :titre WHERE id_conversation = :idConversation"
        );

        return $stmt->execute([
            ':titre' => $titre,
            ':idConversation' => $idConversation
        ]);
    }

    /**
     * @brief Supprimer une conversation et ses messages.
     * @param int $idConversation
     * @return bool
     */
    public function supprimerConversation(int $idConversation): bool
    {
        try {
            $this->pdo->beginTransaction();

            $stmtMessages = $this->pdo->prepare(
                "DELETE FROM " . PREFIXE_TABLE . "Message WHERE id_conversation = :idConversation"
            );
            $stmtMessages->execute([':idConversation' => $idConversation]);

            $stmtCreer = $this->pdo->prepare(
                "DELETE FROM " . PREFIXE_TABLE . "Creer WHERE id_conversation = :idConversation"
            );
            $stmtCreer->execute([':idConversation' => $idConversation]);

            $stmtConversation = $this->pdo->prepare(
                "DELETE FROM " . PREFIXE_TABLE . "Conversation WHERE id_conversation = :idConversation"
            );
            $stmtConversation->execute([':idConversation' => $idConversation]);

            $this->pdo->commit();
            return true;
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            error_log("❌ Error deleting conversation: " . $e->getMessage());
            return false;
        }
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

/**
 * @brief Créer une conversation avec un message initial automatique
 * @param int $userMain Identifiant du maître (émetteur)
 * @param int $userSecond Identifiant du promeneur
 * @param string $messageInitial Message initial de la conversation
 * @return int|false ID de la conversation créée, false sinon
 */
public function creerConversationAvecMessage(int $userMain, int $userSecond, string $messageInitial = "")
{
    try {
        // Créer ou récupérer la conversation
        $id_conversation = $this->createConversation($userMain, $userSecond);
        
        if (!$id_conversation) {
            return false;
        }
        
        // Si un message initial est fourni, l'ajouter
        if (!empty($messageInitial)) {
            $messageDAO = new MessageDAO($this->pdo);
            $messageDAO->creerMessage($userMain, $id_conversation, $messageInitial);
        }
        
        return $id_conversation;
    } catch (PDOException $e) {
        error_log("❌ Error creating conversation with message: " . $e->getMessage());
        return false;
    }
}

/**
 * @brief Vérifie si un utilisateur fait partie d'une conversation
 * @param int $id_conversation Identifiant de la conversation
 * @param int $id_utilisateur Identifiant de l'utilisateur
 * @return bool True si l'utilisateur fait partie de la conversation, false sinon
 */
public function utilisateurFaitPartieConversation(int $id_conversation, int $id_utilisateur): bool
{
    try {
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) 
            FROM " . PREFIXE_TABLE . "Creer 
            WHERE id_conversation = :id_conversation 
            AND id_utilisateur = :id_utilisateur
        ");
        
        $stmt->execute([
            ':id_conversation' => $id_conversation,
            ':id_utilisateur' => $id_utilisateur
        ]);
        
        return $stmt->fetchColumn() > 0;
    } catch (PDOException $e) {
        error_log("❌ Error checking user in conversation: " . $e->getMessage());
        return false;
    }
}

/**
 * @brief Récupère les IDs des participants d'une conversation
 * @param int $id_conversation Identifiant de la conversation
 * @return array Tableau des IDs des participants
 */
public function getParticipants(int $id_conversation): array
{
    try {
        $stmt = $this->pdo->prepare("
            SELECT id_utilisateur 
            FROM " . PREFIXE_TABLE . "Creer 
            WHERE id_conversation = :id_conversation
        ");
        
        $stmt->execute([':id_conversation' => $id_conversation]);
        
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    } catch (PDOException $e) {
        error_log("❌ Error getting conversation participants: " . $e->getMessage());
        return [];
    }
}

/**
 * @brief Récupère l'ID de l'autre participant d'une conversation (celui qui n'est pas l'utilisateur spécifié)
 * @param int $id_conversation Identifiant de la conversation
 * @param int $id_utilisateur Identifiant de l'utilisateur actuel
 * @return int|null ID de l'autre utilisateur ou null si non trouvé
 */
public function getAutreParticipant(int $id_conversation, int $id_utilisateur): ?int
{
    try {
        $stmt = $this->pdo->prepare("
            SELECT id_utilisateur 
            FROM " . PREFIXE_TABLE . "Creer 
            WHERE id_conversation = :id_conversation 
            AND id_utilisateur != :id_utilisateur
            LIMIT 1
        ");
        
        $stmt->execute([
            ':id_conversation' => $id_conversation,
            ':id_utilisateur' => $id_utilisateur
        ]);
        
        $result = $stmt->fetchColumn();
        return $result !== false ? (int)$result : null;
    } catch (PDOException $e) {
        error_log("❌ Error getting other participant: " . $e->getMessage());
        return null;
    }
}

}
?>
