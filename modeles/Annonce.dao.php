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
            $annonces[] = $this->hydrate($row);
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
                $row['id_annonce'],
                $row['titre'],
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
            $annonces[] = $this->hydrate($row);
        }

        return $annonces;
    }

    public function ajouterAnnonce(?Annonce $annonce): bool
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO " . PREFIXE_TABLE . "Annonce 
            (titre, datePromenade, horaire, status, tarif, description, endroitPromenade, duree, id_utilisateur)
            VALUES (:titre, :datePromenade, :horaire, :status, :tarif, :description, :endroitPromenade, :duree, :id_utilisateur)
        ");

        $reussite = $stmt->execute([
            ':titre' => $annonce->getTitre(),
            ':datePromenade' => $annonce->getDatePromenade(),
            ':horaire' => $annonce->getHoraire(),
            ':status' => $annonce->getStatus(),
            ':tarif' => $annonce->getTarif(),
            ':description' => $annonce->getDescription(),
            ':endroitPromenade' => $annonce->getEndroitPromenade(),
            ':duree' => $annonce->getDuree(),
            ':id_utilisateur' => $annonce->getIdUtilisateur()
        ]);

        return $reussite;
    }

    private function hydrate(array $data): Annonce
{
    return new Annonce(
        $data['id_annonce'] ?? null,
        $data['titre'] ?? null,
        $data['datePromenade'] ?? null,
        $data['horaire'] ?? null,
        $data['status'] ?? null,
        $data['tarif'] ?? null,
        $data['description'] ?? null,
        $data['endroitPromenade'] ?? null,
        $data['duree'] ?? null,
        $data['id_utilisateur'] ?? null
    );
}

public function supprimerAnnonce($id_annonce): bool
{
    $sql = "DELETE FROM " . PREFIXE_TABLE . "Annonce WHERE id_annonce = :id_annonce";
    $pdoStatement=$this->pdo->prepare($sql);
    return $pdoStatement->execute([':id_annonce' => $id_annonce]);

}
}
