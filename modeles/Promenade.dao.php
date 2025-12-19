<?php
/**
 * @file promenade.dao.php
 * @author Boisseau Robin
 * @brief Gestion de la base de donees pour les promenades
 * @version 1.0
 * @date 2025-12-18
 */
class PromenadeDAO
{
    /**
     * @brief ?PDO $pdo Instance PDO pour accéder à la base de données.
     */
    private ?PDO $pdo;

    /**
     * @brief Constructeur du DAO Promenade.
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
     * @brief Récupère toutes les promenades.
     *
     * @return Promenade[] Tableau d'objets Promenade.
     */
    public function findAll(): array
    {
        $promenades = [];
        $stmt = $this->pdo->prepare("SELECT * FROM " . PREFIXE_TABLE . "Promenade");
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

    /**
     * @brief Recherche une promenade par son identifiant.
     *
     * @param int|string $id_promenade Identifiant de la promenade.
     * @return ?Promenade Objet Promenade ou null si non trouvé.
     */
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