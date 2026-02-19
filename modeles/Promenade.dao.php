<?php
/**
 * @file Promenade.dao.php
 * @author DogSitter Team
 * @brief Classe DAO pour gérer les opérations de base de données sur les promenades
 * @version 1.0
 * @date 2025-02-17
 */

/**
 * @class PromenadeDAO
 * @brief Classe d'accès aux données pour les promenades
 */
class PromenadeDAO
{
    /**
     * @var PDO Connexion à la base de données
     */
    private $pdo;

    /**
     * @brief Constructeur du DAO
     * @param PDO $pdo Objet de connexion PDO
     */
    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // ============================================
    // CREATE : Créer une promenade
    // ============================================

    /**
     * @brief Insère une nouvelle promenade dans la base de données
     * @param Promenade $promenade Objet promenade à insérer
     * @return int|bool ID de la promenade créée ou false en cas d'erreur
     */
    public function create(Promenade $promenade)
    {
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO " . PREFIXE_TABLE . "Promenade
                (id_chien, id_promeneur, id_proprietaire, id_annonce, date_promenade, statut)
                VALUES
                (:id_chien, :id_promeneur, :id_proprietaire, :id_annonce, :date_promenade, :statut)
            ");

            $dateStr = null;
            if ($promenade->getDate_promenade() instanceof \DateTime) {
                $dateStr = $promenade->getDate_promenade()->format('Y-m-d H:i:s');
            }

            $ok = $stmt->execute([
                ':id_chien' => $promenade->getId_chien(),
                ':id_promeneur' => $promenade->getId_promeneur(),
                ':id_proprietaire' => $promenade->getId_proprietaire(),
                ':id_annonce' => $promenade->getId_annonce(),
                ':date_promenade' => $dateStr,
                ':statut' => $promenade->getStatut()
            ]);

            if ($ok) {
                return (int) $this->pdo->lastInsertId();
            }
        } catch (PDOException $e) {
            return false;
        }

        return false;
    }

    // ============================================
    // READ : Lire les promenades
    // ============================================

    /**
     * @brief Récupère une promenade par son ID
     * @param int $id_promenade Identifiant de la promenade
     * @return Promenade|null Objet promenade ou null si non trouvée
     */
    public function findById($id_promenade)
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT * FROM " . PREFIXE_TABLE . "Promenade
                WHERE id_promenade = :id_promenade
            ");

            $stmt->execute([':id_promenade' => $id_promenade]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                return $this->hydrate($row);
            }
        } catch (PDOException $e) {
            return null;
        }

        return null;
    }

    /**
     * @brief Récupère toutes les promenades
     * @return array Tableau d'objets Promenade
     */
    public function findAll()
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM " . PREFIXE_TABLE . "Promenade");
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $promenades = [];
            foreach ($rows as $row) {
                $promenades[] = $this->hydrate($row);
            }

            return $promenades;
        } catch (PDOException $e) {
            return [];
        }
    }

    /**
     * @brief Récupère les promenades d'un promeneur
     * @param int $id_promeneur Identifiant du promeneur
     * @return array Tableau d'objets Promenade
     */
    public function findByPromeneur($id_promeneur)
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT * FROM " . PREFIXE_TABLE . "Promenade
                WHERE id_promeneur = :id_promeneur
                ORDER BY date_promenade DESC
            ");

            $stmt->execute([':id_promeneur' => $id_promeneur]);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $promenades = [];
            foreach ($rows as $row) {
                $promenades[] = $this->hydrate($row);
            }

            return $promenades;
        } catch (PDOException $e) {
            return [];
        }
    }

    /**
     * @brief Récupère les promenades d'un propriétaire
     * @param int $id_proprietaire Identifiant du propriétaire
     * @return array Tableau d'objets Promenade
     */
    public function findByProprietaire($id_proprietaire)
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT * FROM " . PREFIXE_TABLE . "Promenade
                WHERE id_proprietaire = :id_proprietaire
                ORDER BY date_promenade DESC
            ");

            $stmt->execute([':id_proprietaire' => $id_proprietaire]);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $promenades = [];
            foreach ($rows as $row) {
                $promenades[] = $this->hydrate($row);
            }

            return $promenades;
        } catch (PDOException $e) {
            return [];
        }
    }

    /**
     * @brief Récupère les promenades d'une annonce
     * @param int $id_annonce Identifiant de l'annonce
     * @return array Tableau d'objets Promenade
     */
    public function findByAnnonce($id_annonce)
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT * FROM " . PREFIXE_TABLE . "Promenade
                WHERE id_annonce = :id_annonce
                ORDER BY date_promenade DESC
            ");

            $stmt->execute([':id_annonce' => $id_annonce]);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $promenades = [];
            foreach ($rows as $row) {
                $promenades[] = $this->hydrate($row);
            }

            return $promenades;
        } catch (PDOException $e) {
            return [];
        }
    }

    /**
     * @brief Récupère les promenades avec un statut spécifique
     * @param string $statut Statut à rechercher
     * @return array Tableau d'objets Promenade
     */
    public function findByStatut($statut)
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT * FROM " . PREFIXE_TABLE . "Promenade
                WHERE statut = :statut
                ORDER BY date_promenade DESC
            ");

            $stmt->execute([':statut' => $statut]);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $promenades = [];
            foreach ($rows as $row) {
                $promenades[] = $this->hydrate($row);
            }

            return $promenades;
        } catch (PDOException $e) {
            return [];
        }
    }

    // ============================================
    // UPDATE : Mettre à jour une promenade
    // ============================================

    /**
     * @brief Met à jour une promenade
     * @param Promenade $promenade Objet promenade avec les données mises à jour
     * @return bool Succès de la mise à jour
     */
    public function update(Promenade $promenade)
    {
        try {
            $stmt = $this->pdo->prepare("
                UPDATE " . PREFIXE_TABLE . "Promenade
                SET 
                    id_chien = :id_chien,
                    id_promeneur = :id_promeneur,
                    id_proprietaire = :id_proprietaire,
                    id_annonce = :id_annonce,
                    date_promenade = :date_promenade,
                    statut = :statut
                WHERE id_promenade = :id_promenade
            ");

            $dateStr = null;
            if ($promenade->getDate_promenade() instanceof \DateTime) {
                $dateStr = $promenade->getDate_promenade()->format('Y-m-d H:i:s');
            }

            return $stmt->execute([
                ':id_chien' => $promenade->getId_chien(),
                ':id_promeneur' => $promenade->getId_promeneur(),
                ':id_proprietaire' => $promenade->getId_proprietaire(),
                ':id_annonce' => $promenade->getId_annonce(),
                ':date_promenade' => $dateStr,
                ':statut' => $promenade->getStatut(),
                ':id_promenade' => $promenade->getId_promenade()
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * @brief Met à jour le statut d'une promenade
     * @param int $id_promenade Identifiant de la promenade
     * @param string $nouveau_statut Nouveau statut
     * @return bool Succès de la mise à jour
     */
    public function updateStatut($id_promenade, $nouveau_statut)
    {
        try {
            $stmt = $this->pdo->prepare("
                UPDATE " . PREFIXE_TABLE . "Promenade
                SET statut = :statut
                WHERE id_promenade = :id_promenade
            ");

            return $stmt->execute([
                ':statut' => $nouveau_statut,
                ':id_promenade' => $id_promenade
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * @brief Marque une promenade comme terminée
     * @param int $id_promenade Identifiant de la promenade
     * @return bool Succès de la mise à jour
     */
    public function marquerTerminee($id_promenade)
    {
        return $this->updateStatut($id_promenade, 'terminee');
    }

    /**
     * @brief Marque une promenade comme en cours
     * @param int $id_promenade Identifiant de la promenade
     * @return bool Succès de la mise à jour
     */
    public function marquerEnCours($id_promenade)
    {
        return $this->updateStatut($id_promenade, 'en_cours');
    }

    /**
     * @brief Marque une promenade comme annulée
     * @param int $id_promenade Identifiant de la promenade
     * @return bool Succès de la mise à jour
     */
    public function marquerAnnulee($id_promenade)
    {
        return $this->updateStatut($id_promenade, 'annulee');
    }

    /**
     * @brief Marque une promenade comme archivée et archive l'annonce si toutes ses promenades sont archivées
     * @param int $id_promenade Identifiant de la promenade
     * @return bool Succès de la mise à jour
     */
    public function marquerArchivee($id_promenade)
    {
        // Archiver la promenade
        $success = $this->updateStatut($id_promenade, 'archivee');
        
        if ($success) {
            // Récupérer l'annonce associée
            $promenade = $this->findByID($id_promenade);
            if ($promenade && $promenade->getId_annonce()) {
                $this->archiverAnnoncesSiCompletes($promenade->getId_annonce());
            }
        }
        
        return $success;
    }

    /**
     * @brief Archive une annonce si toutes ses promenades sont archivées ou terminées
     * @param int $id_annonce Identifiant de l'annonce
     * @return bool Succès de la mise à jour
     */
    private function archiverAnnoncesSiCompletes($id_annonce)
    {
        try {
            // Vérifier que toutes les promenades de l'annonce sont archivées
            $stmt = $this->pdo->prepare("
                SELECT COUNT(*) as total,
                       SUM(CASE WHEN statut = 'archivee' THEN 1 ELSE 0 END) as archivees
                FROM " . PREFIXE_TABLE . "Promenade
                WHERE id_annonce = :id_annonce
            ");
            $stmt->execute([':id_annonce' => $id_annonce]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Si toutes les promenades sont archivées, archiver l'annonce
            if ($result && $result['total'] > 0 && $result['total'] == $result['archivees']) {
                $updateStmt = $this->pdo->prepare("
                    UPDATE " . PREFIXE_TABLE . "Annonce
                    SET status = 'archivee'
                    WHERE id_annonce = :id_annonce
                ");
                return $updateStmt->execute([':id_annonce' => $id_annonce]);
            }
        } catch (PDOException $e) {
            return false;
        }
        return false;
    }

    /**
     * @brief Archive automatiquement les promenades terminées dont la date est dépassée
     * @return int Nombre de promenades archivées
     */
    public function archiverPromenadesDépassées()
    {
        try {
            // Récupérer les promenades qui doivent être archivées
            $stmt = $this->pdo->prepare("
                SELECT id_promenade, id_annonce
                FROM " . PREFIXE_TABLE . "Promenade
                WHERE statut = 'terminee' 
                AND date_promenade < NOW()
            ");
            $stmt->execute();
            $promenades = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $count = 0;
            $annoncesIds = [];
            
            // Archiver chaque promenade
            foreach ($promenades as $promenade) {
                $updateStmt = $this->pdo->prepare("
                    UPDATE " . PREFIXE_TABLE . "Promenade
                    SET statut = 'archivee'
                    WHERE id_promenade = :id_promenade
                ");
                
                if ($updateStmt->execute([':id_promenade' => $promenade['id_promenade']])) {
                    $count++;
                    if ($promenade['id_annonce']) {
                        $annoncesIds[] = $promenade['id_annonce'];
                    }
                }
            }
            
            // Archiver les annonces dont toutes les promenades sont archivées
            foreach (array_unique($annoncesIds) as $id_annonce) {
                $this->archiverAnnoncesSiCompletes($id_annonce);
            }
            
            return $count;
        } catch (PDOException $e) {
            return 0;
        }
    }

    // ============================================
    // DELETE : Supprimer une promenade
    // ============================================

    /**
     * @brief Supprime une promenade
     * @param int $id_promenade Identifiant de la promenade à supprimer
     * @return bool Succès de la suppression
     */
    public function delete($id_promenade)
    {
        try {
            $stmt = $this->pdo->prepare("
                DELETE FROM " . PREFIXE_TABLE . "Promenade
                WHERE id_promenade = :id_promenade
            ");

            return $stmt->execute([':id_promenade' => $id_promenade]);
        } catch (PDOException $e) {
            return false;
        }
    }

    // ============================================
    // HELPER : Hydratation des objets
    // ============================================

    /**
     * @brief Convertit un tableau associatif en objet Promenade
     * @param array $row Tableau associatif de données
     * @return Promenade Objet promenade hydraté
     */
    private function hydrate($row)
    {
        $promenade = new Promenade();

        if (isset($row['id_promenade'])) {
            $promenade->setId_promenade((int) $row['id_promenade']);
        }
        if (isset($row['id_chien'])) {
            $promenade->setId_chien($row['id_chien'] ? (int) $row['id_chien'] : null);
        }
        if (isset($row['id_promeneur'])) {
            $promenade->setId_promeneur((int) $row['id_promeneur']);
        }
        if (isset($row['id_proprietaire'])) {
            $promenade->setId_proprietaire((int) $row['id_proprietaire']);
        }
        if (isset($row['id_annonce'])) {
            $promenade->setId_annonce($row['id_annonce'] ? (int) $row['id_annonce'] : null);
        }
        if (isset($row['date_promenade'])) {
            $promenade->setDate_promenade($row['date_promenade']);
        }
        if (isset($row['statut'])) {
            $promenade->setStatut($row['statut']);
        }

        return $promenade;
    }
}
?>
