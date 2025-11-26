<?php

class AnnonceDAO
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

    /**
     * Récupère toutes les annonces
     */
    public function findAll(): array
    {
        $annonces = [];
        $stmt = $this->pdo->prepare("SELECT * FROM " . PREFIXE_TABLE . "Annonce");
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($results as $row) {
            $annonces[] = new Annonce(
                $row['titre'],
                $row['id_annonce'],
                $row['datePromenade'],
                $row['horaire'],
                $row['status'],
                $row['tarif'],
                $row['description'],
                $row['endroitPromenade'],
                $row['duree'],
                $row['id_utilisateur']
            );
        }

        return $annonces;
    }

    /**
     * Recherche une annonce par son ID
     */
    public function findById($id_annonce): ?Annonce
    {
        $stmt = $this->pdo->prepare("SELECT * FROM " . PREFIXE_TABLE . "Annonce WHERE id_annonce = :id_annonce");
        $stmt->bindParam(':id_annonce', $id_annonce);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new Annonce(
                $row['titre'],
                $row['id_annonce'],
                $row['datePromenade'],
                $row['horaire'],
                $row['status'],
                $row['tarif'],
                $row['description'],
                $row['endroitPromenade'],
                $row['duree'],
                $row['id_utilisateur']
            );
        }

        return null;
    }

    /**
     * Récupère toutes les annonces d’un utilisateur donné
     */
    public function findByUtilisateur($id_utilisateur): array
    {
        $annonces = [];
        $stmt = $this->pdo->prepare("SELECT * FROM " . PREFIXE_TABLE . "Annonce WHERE id_utilisateur = :id_utilisateur");
        $stmt->bindParam(':id_utilisateur', $id_utilisateur, PDO::PARAM_STR);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($results as $row) {
            $annonces[] = new Annonce(
                $row['titre'],
                $row['id_annonce'],
                $row['datePromenade'],
                $row['horaire'],
                $row['status'],
                $row['tarif'],
                $row['description'],
                $row['endroitPromenade'],
                $row['duree'],
                $row['id_utilisateur']
            );
        }

        return $annonces;
    }
}
