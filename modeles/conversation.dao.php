<?php

class ConversationDAO
{
    private ?PDO $pdo;

    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo;
    }

    public function getPdo(): ?PDO
    {
        return $this->pdo;
    }

    public function setPdo(?PDO $pdo): void
    {
        $this->pdo = $pdo;
    }

    public function findAll(): array
    {
        $conversations = [];
        $stmt = $this->pdo->prepare("SELECT * FROM " . PREFIXE_TABLE . "Conversation");
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($results as $row) {
            $conversations[] = new Conversation(
                $row['idConversation'],
                $row['dateCreation']
            );
        }

        return $conversations;
    }

    public function findById($idConversation): ?Conversation
    {
        $stmt = $this->pdo->prepare("SELECT * FROM " . PREFIXE_TABLE . "Conversation WHERE idConversation = :idConversation");
        $stmt->bindParam(':idConversation', $idConversation, PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new Conversation(
                $row['idConversation'],
                $row['dateCreation']
            );
        }

        return null;
    }
}
?>
