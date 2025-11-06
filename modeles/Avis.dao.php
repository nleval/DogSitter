<?php

class AvisDAO
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
        $avis = [];
        $stmt = $this->pdo->prepare("SELECT * FROM " . PREFIXE_TABLE . "Avis");
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($results as $row) {
            $avis[] = new Avis(
                $row['id_avis'],
                $row['note'],
                $row['texte_commentaire'],
                $row['id_utilisateur'],
                $row['id_promenade'],
                $row['id_utilisateur_note']
            );
        }

        return $avis;
    }

    public function findById($id_avis): ?Avis
    {
        $stmt = $this->pdo->prepare("SELECT * FROM " . PREFIXE_TABLE . "Avis WHERE id_avis = :id_avis");
        $stmt->bindParam(':id_avis', $id_avis, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new Avis(
                $row['id_avis'],
                $row['note'],
                $row['texte_commentaire'],
                $row['id_utilisateur'],
                $row['id_promenade'],
                $row['id_utilisateur_note']
            );
        }

        return null;
    }
}