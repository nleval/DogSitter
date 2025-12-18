<?php

class AnnonceDAO
{
    /**
     * @brief ?PDO $pdo Instance PDO pour la connexion à la base de données.
     */
    private ?PDO $pdo;

    /**
     * @brief Constructeur du DAO Annonce.
     *
     * @param ?PDO $pdo Instance PDO (optionnelle).
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
     * @brief Récupère toutes les annonces.
     *
     * @return Annonce[] Tableau d'objets Annonce.
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
     * @brief Recherche une annonce par son identifiant.
     *
     * @param int|string $id_annonce Identifiant de l'annonce.
     * @return ?Annonce Objet Annonce ou null si non trouvé.
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
     * @brief Récupère toutes les annonces d’un utilisateur donné.
     *
     * @param int|string $id_utilisateur Identifiant de l'utilisateur.
     * @return Annonce[] Tableau d'objets Annonce.
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

    /**
     * @brief Ajoute une nouvelle annonce en base.
     *
     * @param ?Annonce $annonce Objet Annonce à insérer.
     * @return bool Succès de l'insertion.
     */
    public function ajouterAnnonce(?Annonce $annonce): bool
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO " . PREFIXE_TABLE . "Annonce 
            (titre, datePromenade, horaire, status, tarif, description, endroitPromenade, duree, id_utilisateur)
            VALUES (:titre, :datePromenade, :horaire, :status, :tarif, :description, :endroitPromenade, :duree, :id_utilisateur)
        ");

        return $stmt->execute([
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
    }

    /**
     * @brief Supprime une annonce par son identifiant.
     *
     * @param int|string $id_annonce Identifiant de l'annonce.
     * @return bool Succès de la suppression.
     */
    public function supprimerAnnonce($id_annonce): bool
    {
        $sql = "DELETE FROM " . PREFIXE_TABLE . "Annonce WHERE id_annonce = :id_annonce";
        $pdoStatement = $this->pdo->prepare($sql);
        return $pdoStatement->execute([':id_annonce' => $id_annonce]);
    }

    /**
     * @brief Modifie un champ spécifique d'une annonce.
     *
     * @param int|string $id_annonce Identifiant de l'annonce.
     * @param string $champ Nom du champ à modifier.
     * @param mixed $nouvelleValeur Nouvelle valeur à appliquer.
     * @return bool|null Succès de la modification ou null si erreur.
     */
    public function modifierChamp($id_annonce, $champ, $nouvelleValeur): ?bool
    {
        $sql = "UPDATE " . PREFIXE_TABLE . "annonce SET $champ = :nouvelleValeur WHERE id_annonce = :id_annonce";
        $pdoStatement = $this->pdo->prepare($sql);
        return $pdoStatement->execute([
            ':nouvelleValeur' => $nouvelleValeur,
            ':id_annonce' => $id_annonce
        ]);
    }

    /**
     * @brief Transforme un tableau associatif en objet Annonce.
     *
     * @param array $data Données de la base.
     * @return Annonce Objet Annonce hydraté.
     */
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
}