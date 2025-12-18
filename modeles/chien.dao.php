<?php
/**
 * @file controller_annonce.class.php
 * @author Thyes Lilian
 * @brief Gestion de la base de donees pour les chiens
 * @version 1.0
 * @date 2025-12-18
 */
class ChienDAO{
    /**
     * @brief ?PDO $pdo Instance PDO pour accéder à la base de données.
     */
    private ?PDO $pdo;

    /**
     * @brief Constructeur du DAO Chien.
     *
     * @param ?PDO $pdo Instance PDO (optionnelle) pour la connexion à la base.
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
     * @brief Recherche un chien par son identifiant.
     *
     * @param ?int $id_chien Identifiant du chien.
     * @return ?Chien Objet Chien ou null si non trouvé.
     */
    public function findById(?int $id_chien): ?Chien
    {
        $stmt = $this->pdo->prepare("SELECT * FROM " . PREFIXE_TABLE . "Chien WHERE id_chien = :id_chien");
        $stmt->bindParam(':id_chien', $id_chien, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($row) {
            return new Chien(
                $row['id_chien'],
                $row['nom_chien'],
                $row['poids'],
                $row['taille'],
                $row['race'],
                $row['cheminPhoto'],
                $row['id_utilisateur']
            );            
        }
        return null;
    }

    /**
     * @brief Récupère tous les chiens.
     *
     * @return Chien[] Tableau d'objets Chien.
     */
    public function findAll(): array
    {
        $chiens = [];
        $pdoStatement = $this->pdo->prepare("SELECT * FROM " . PREFIXE_TABLE . "Chien");
        $pdoStatement->execute();
        $results = $pdoStatement->fetchAll(PDO::FETCH_ASSOC);

        foreach ($results as $row) {
            $chiens[] = new Chien(
                $row['id_chien'],
                $row['nom_chien'],
                $row['poids'],
                $row['taille'],
                $row['race'],
                $row['cheminPhoto'],
                $row['id_utilisateur']
            );
        }
        return $chiens;
    }

    /**
     * @brief Récupère tous les chiens liés à une annonce spécifique.
     *
     * @param int|string $id_annonce Identifiant de l'annonce.
     * @return Chien[] Tableau d'objets Chien.
     */
    public function findByAnnonce($id_annonce): array
    {
        $chiens = [];
        $sql = "SELECT c.* FROM " . PREFIXE_TABLE . "Chien c 
                JOIN " . PREFIXE_TABLE . "Concerne co ON c.id_chien = co.id_chien 
                WHERE co.id_annonce = :id_annonce";
                
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id_annonce', $id_annonce, PDO::PARAM_INT);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($results as $row) {
            $chiens[] = new Chien(
                $row['id_chien'],
                $row['nom_chien'],
                $row['poids'],
                $row['taille'],
                $row['race'],
                $row['cheminPhoto'],
                $row['id_utilisateur']
            );
        }
        return $chiens;
    }

    /**
     * @brief Récupère tous les chiens appartenant à un utilisateur.
     *
     * @param int $id_utilisateur Identifiant de l'utilisateur.
     * @return Chien[] Tableau d'objets Chien.
     */
    public function findByUtilisateur(int $id_utilisateur): array
    {
        $stmt = $this->pdo->prepare("
            SELECT *
            FROM " . PREFIXE_TABLE . "Chien
            WHERE id_utilisateur = :id_utilisateur
        ");

        $stmt->bindParam(':id_utilisateur', $id_utilisateur, PDO::PARAM_INT);
        $stmt->execute();

        $resultats = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $chiens = [];

        foreach ($resultats as $row) {
            $chiens[] = new Chien(
                $row['id_chien'],
                $row['nom_chien'],
                $row['poids'],
                $row['taille'],
                $row['race'],
                $row['cheminPhoto'],
                $row['id_utilisateur']
            );
        }

        return $chiens;
    }
}
?>
