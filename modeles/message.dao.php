<?php

class MessageDAO
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
