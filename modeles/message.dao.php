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
                $row['id_conversation']
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
                $row['id_conversation']
            );
        }

        return null;
    }
}
?>
