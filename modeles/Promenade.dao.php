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
    $promenades = []; // 1. On initialise un tableau vide
    $stmt = $this->pdo->prepare("SELECT * FROM " . PREFIXE_TABLE . "Promenade");
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($results as $row) {
        // Attention à l'ordre des paramètres ici (comme vu précédemment)
        $promenades[] = new Promenade(
            $row['id_promenade'],
            $row['statut'],
            $row['id_promeneur'], 
            $row['id_chien'],     
            $row['id_proprietaire'],
            $row['id_annonce']
        );
    }

    return $promenades; // 2. IMPORTANNNT : Il faut retourner le tableau ici !
}

    public function findById($id_promenade): ?Promenade
    {
        $stmt = $this->pdo->prepare("SELECT * FROM " . PREFIXE_TABLE . "Promenade WHERE id_promenade = :id_promenade");
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