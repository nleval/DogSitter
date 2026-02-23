<?php
/**
 * @file message.dao.php
 * @author Pigeon Aymeric
 * @brief Gestion de la base de donees pour les messages
 * @version 1.0
 * @date 2025-12-18
 */
class MessageDAO
{
    /**
     * @brief ?PDO $pdo Instance PDO pour accéder à la base de données.
     */
    private ?PDO $pdo;

    /**
     * @brief Constructeur du DAO Message.
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
     * @brief Récupère tous les messages.
     *
     * @return Message[] Tableau d'objets Message.
     */
    public function findAll(): array
    {
        $messages = [];
        $stmt = $this->pdo->prepare("SELECT * FROM " . PREFIXE_TABLE . "Message");
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($results as $row) {
            $messages[] = new Message(
                $row['id_message'],
                $row['contenu'],
                $row['DateHeureMessage'],
                $row['id_utilisateur'],
                $row['id_conversation'],
                $row['est_modifie'] ?? 0
            );
        }

        return $messages;
    }

    /**
     * @brief Recherche un message par son identifiant.
     *
     * @param int|string $idMessage Identifiant du message.
     * @return ?Message Objet Message ou null si non trouvé.
     */
    public function findById($idMessage): ?Message
    {
        $stmt = $this->pdo->prepare("SELECT * FROM " . PREFIXE_TABLE . "Message WHERE id_message = :idMessage");
        $stmt->bindParam(':idMessage', $idMessage, PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new Message(
                $row['id_message'],
                $row['contenu'],
                $row['DateHeureMessage'],
                $row['id_utilisateur'],
                $row['id_conversation'],
                $row['est_modifie'] ?? 0
            );
        }

        return null;
    }

    /**
     * @brief Récupère tous les messages d'une conversation
     *
     * @param int $idConversation Identifiant de la conversation
     * @return Message[] Tableau d'objets Message
     */
    public function findByConversation(int $idConversation): array
    {
        $messages = [];
        $stmt = $this->pdo->prepare("
            SELECT * FROM " . PREFIXE_TABLE . "Message 
            WHERE id_conversation = :id_conversation 
            ORDER BY DateHeureMessage ASC
        ");
        $stmt->execute([':id_conversation' => $idConversation]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($results as $row) {
            $messages[] = new Message(
                $row['id_message'],
                $row['contenu'],
                $row['DateHeureMessage'],
                $row['id_utilisateur'],
                $row['id_conversation'],
                $row['est_modifie'] ?? 0
            );
        }

        return $messages;
    }

    /**
     * @brief Créer un nouveau message
     *
     * @param int $idUtilisateur Identifiant de l'utilisateur émetteur
     * @param int $idConversation Identifiant de la conversation
     * @param string $contenu Contenu du message
     * @return int|false ID du message créé, false sinon
     */
    public function creerMessage(int $idUtilisateur, int $idConversation, string $contenu)
    {
        try {
            $dateHeure = date('Y-m-d H:i:s');
            
            $stmt = $this->pdo->prepare("
                INSERT INTO " . PREFIXE_TABLE . "Message (contenu, DateHeureMessage, id_utilisateur, id_conversation, est_modifie)
                VALUES (:contenu, :date_heure, :id_utilisateur, :id_conversation, 0)
            ");
            
            if ($stmt->execute([
                ':contenu' => $contenu,
                ':date_heure' => $dateHeure,
                ':id_utilisateur' => $idUtilisateur,
                ':id_conversation' => $idConversation
            ])) {
                return $this->pdo->lastInsertId();
            }
            
            return false;
        } catch (PDOException $e) {
            error_log("❌ Error creating message: " . $e->getMessage());
            return false;
        }
    }

    /**
     * @brief Mettre à jour le contenu d'un message.
     * @param int $idMessage
     * @param string $contenu
     * @return bool
     */
    public function modifierMessage(int $idMessage, string $contenu): bool
    {
        $stmt = $this->pdo->prepare(
            "UPDATE " . PREFIXE_TABLE . "Message SET contenu = :contenu, est_modifie = 1 WHERE id_message = :idMessage"
        );

        return $stmt->execute([
            ':contenu' => $contenu,
            ':idMessage' => $idMessage
        ]);
    }

    /**
     * @brief Supprimer un message.
     * @param int $idMessage
     * @return bool
     */
    public function supprimerMessage(int $idMessage): bool
    {
        $stmt = $this->pdo->prepare(
            "DELETE FROM " . PREFIXE_TABLE . "Message WHERE id_message = :idMessage"
        );

        return $stmt->execute([':idMessage' => $idMessage]);
    }
}
?>
