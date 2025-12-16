<?php

class PromenadeDAO
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
        $promenades = [];
        $stmt = $this->pdo->prepare("SELECT * FROM " . PREFIXE_TABLE . "promenade");
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($results as $row) {
            $promenades[] = new Promenade(
                $row['id_promenade'],
                $row['statut'],
                $row['id_promeneur'],
                $row['id_chien'],
                $row['id_proprietaire'],
                $row['id_annonce']
            );
        }

        return $promenades;
    }

    public function findById($id_promenade): ?Promenade
    {
        $stmt = $this->pdo->prepare("SELECT * FROM " . PREFIXE_TABLE . "promenade WHERE id_promenade = :id_promenade");
        $stmt->bindParam(':id_promenade', $id_promenade, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new Promenade(
                $row['id_promenade'],
                $row['statut'],
                $row['id_promeneur'],
                $row['id_chien'],
                $row['id_proprietaire'],
                $row['id_annonce']
            );
        }

        return null;
    }
}