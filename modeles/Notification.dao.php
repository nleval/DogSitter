<?php
/**
 * @file Notification.dao.php
 * @author DogSynergie Dev Team
 * @brief Gère les opérations liées aux notifications utilisateur
 * @version 1.0
 * @date 2026-02-16
 */

class NotificationDAO {
    private PDO $pdo;

    /**
     * @brief Constructeur
     * @param PDO $pdo Instance PDO
     */
    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    /**
     * @brief Crée une nouvelle notification
     * @param int $id_utilisateur Identifiant de l'utilisateur destinataire
     * @param string $titre Titre de la notification
     * @param string $message Message de la notification
     * @param string $type Type: 'candidature_soumise', 'candidature_reçue', 'candidature_acceptée', 'candidature_refusée', 'info'
     * @param int|null $id_annonce Identifiant de l'annonce (optionnel)
     * @param int|null $id_reponse Identifiant de la réponse (optionnel)
     * @param int|null $id_promeneur Identifiant du promeneur (optionnel)
     * @return bool Succès de l'insertion
     */
    public function creerNotification(
        int $id_utilisateur,
        string $titre,
        string $message,
        string $type = 'info',
        ?int $id_annonce = null,
        ?int $id_reponse = null,
        ?int $id_promeneur = null
    ): bool {
        try {
            $sql = "
                INSERT INTO " . PREFIXE_TABLE . "Notification 
                (id_utilisateur, titre, message, type, id_annonce, id_reponse, id_promeneur, lue)
                VALUES (:id_utilisateur, :titre, :message, :type, :id_annonce, :id_reponse, :id_promeneur, 0)
            ";

            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute([
                ':id_utilisateur' => $id_utilisateur,
                ':titre' => $titre,
                ':message' => $message,
                ':type' => $type,
                ':id_annonce' => $id_annonce,
                ':id_reponse' => $id_reponse,
                ':id_promeneur' => $id_promeneur
            ]);
            
            if ($result) {
                error_log("✓ Notification créée pour utilisateur {$id_utilisateur}: {$titre}");
            }
            return $result;
        } catch (PDOException $e) {
            error_log("❌ Erreur NotificationDAO::creerNotification - " . $e->getMessage());
            return false;
        }
    }

    /**
     * @brief Récupère toutes les notifications d'un utilisateur
     * @param int $id_utilisateur Identifiant de l'utilisateur
     * @param bool $non_lues_seulement Si true, retourne seulement les non-lues
     * @return array Tableau des notifications
     */
    public function getNotifications(int $id_utilisateur, bool $non_lues_seulement = false): array {
        try {
            $sql = "
                SELECT n.id_notification, n.titre, n.message, n.type, n.id_annonce, n.id_reponse, 
                       n.id_promeneur, n.lue, n.date_creation, a.titre as annonce_titre
                FROM " . PREFIXE_TABLE . "Notification n
                LEFT JOIN " . PREFIXE_TABLE . "Annonce a ON n.id_annonce = a.id_annonce
                WHERE n.id_utilisateur = :id_utilisateur
            ";

            if ($non_lues_seulement) {
                $sql .= " AND n.lue = 0";
            }

            $sql .= " ORDER BY n.date_creation DESC LIMIT 50";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id_utilisateur' => $id_utilisateur]);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            error_log("📬 getNotifications pour user {$id_utilisateur}: " . count($results) . " notification(s)");
            
            return $results;
        } catch (PDOException $e) {
            error_log("❌ Erreur NotificationDAO::getNotifications - " . $e->getMessage());
            return [];
        }
    }

    /**
     * @brief Compte les notifications non-lues
     * @param int $id_utilisateur Identifiant de l'utilisateur
     * @return int Nombre de notifications non-lues
     */
    public function compterNonLues(int $id_utilisateur): int {
        try {
            $sql = "
                SELECT COUNT(*) as count
                FROM " . PREFIXE_TABLE . "Notification
                WHERE id_utilisateur = :id_utilisateur AND lue = 0
            ";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id_utilisateur' => $id_utilisateur]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['count'] ?? 0;
        } catch (PDOException $e) {
            error_log("Erreur NotificationDAO::compterNonLues - " . $e->getMessage());
            return 0;
        }
    }

    /**
     * @brief Marque une notification comme lue
     * @param int $id_notification Identifiant de la notification
     * @return bool Succès de la mise à jour
     */
    public function marquerCommeLue(int $id_notification): bool {
        try {
            $sql = "
                UPDATE " . PREFIXE_TABLE . "Notification
                SET lue = 1
                WHERE id_notification = :id_notification
            ";

            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([':id_notification' => $id_notification]);
        } catch (PDOException $e) {
            error_log("Erreur NotificationDAO::marquerCommeLue - " . $e->getMessage());
            return false;
        }
    }

    /**
     * @brief Marque toutes les notifications comme lues
     * @param int $id_utilisateur Identifiant de l'utilisateur
     * @return bool Succès de la mise à jour
     */
    public function marquerTousCommeLue(int $id_utilisateur): bool {
        try {
            $sql = "
                UPDATE " . PREFIXE_TABLE . "Notification
                SET lue = 1
                WHERE id_utilisateur = :id_utilisateur AND lue = 0
            ";

            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([':id_utilisateur' => $id_utilisateur]);
        } catch (PDOException $e) {
            error_log("Erreur NotificationDAO::marquerTousCommeLue - " . $e->getMessage());
            return false;
        }
    }

    /**
     * @brief Supprime une notification
     * @param int $id_notification Identifiant de la notification
     * @return bool Succès de la suppression
     */
    public function supprimerNotification(int $id_notification): bool {
        try {
            $sql = "
                DELETE FROM " . PREFIXE_TABLE . "Notification
                WHERE id_notification = :id_notification
            ";

            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([':id_notification' => $id_notification]);
        } catch (PDOException $e) {
            error_log("Erreur NotificationDAO::supprimerNotification - " . $e->getMessage());
            return false;
        }
    }
}
?>
