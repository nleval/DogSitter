<?php
/**
 * @file controller_annonce.class.php
 * @author Lalanne Victor
 * @brief Gestion de la base de donnees pour les annonces
 * @version 1.0
 * @date 2025-12-18
 */
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
                $row['id_utilisateur'],
                isset($row['id_promeneur']) ? (int) $row['id_promeneur'] : null,
                $row['statut_promenade'] ?? null
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
            $data['id_utilisateur'] ?? null,
            isset($data['id_promeneur']) ? (int) $data['id_promeneur'] : null,
            $data['statut_promenade'] ?? null
        );
    }

    /**
 * @brief Enregistre la réponse d'un utilisateur à une annonce
 * @param int $id_annonce Identifiant de l'annonce
 * @param int $id_utilisateur Identifiant de l'utilisateur
 * @return int|string ID de réponse si succès, message d'erreur sinon
 */
public function repondreAnnonce(int $id_annonce, int $id_utilisateur)
{
    try {
        // Vérifier si l'utilisateur a déjà répondu
        $stmtCheck = $this->pdo->prepare("
            SELECT * FROM " . PREFIXE_TABLE . "Repond 
            WHERE id_annonce = :id_annonce AND id_utilisateur = :id_utilisateur
        ");
        $stmtCheck->execute([
            ':id_annonce' => $id_annonce,
            ':id_utilisateur' => $id_utilisateur
        ]);

        if ($stmtCheck->rowCount() > 0) {
            return "Vous avez déjà répondu à cette annonce.";
        }

        // Insérer la réponse
        $stmtInsert = $this->pdo->prepare("
            INSERT INTO " . PREFIXE_TABLE . "Repond (id_annonce, id_utilisateur)
            VALUES (:id_annonce, :id_utilisateur)
        ");
        $stmtInsert->execute([
            ':id_annonce' => $id_annonce,
            ':id_utilisateur' => $id_utilisateur
        ]);

        return $this->pdo->lastInsertId();

    } catch (PDOException $e) {
        return "Erreur lors de la réponse à l'annonce : " . $e->getMessage();
    }
}

/**
 * @brief Récupère toutes les réponses (candidatures) pour les annonces d'un utilisateur
 * @param int $id_utilisateur Identifiant du maître
 * @return array Tableau de réponses avec infos utilisateur et annonce
 */
public function getCandidaturesPourUtilisateur(int $id_utilisateur): array
{
    $sql = "
        SELECT a.id_annonce, a.titre, r.id_utilisateur AS id_candidat, u.pseudo, u.email, r.statut, r.date_creation
        FROM " . PREFIXE_TABLE . "Annonce a
        INNER JOIN " . PREFIXE_TABLE . "Repond r ON a.id_annonce = r.id_annonce
        INNER JOIN " . PREFIXE_TABLE . "Utilisateur u ON r.id_utilisateur = u.id_utilisateur
        WHERE a.id_utilisateur = :id_utilisateur AND r.statut = 'en_attente'
        ORDER BY r.date_modification DESC, a.id_annonce
    ";

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':id_utilisateur' => $id_utilisateur]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * @brief Récupère toutes les candidatures soumises par un utilisateur (promeneur)
 * @param int $id_utilisateur Identifiant du promeneur
 * @return array Tableau des candidatures soumises
 */
public function getCandidaturesBySubmittedBy(int $id_utilisateur): array
{
    $sql = "
        SELECT a.id_annonce, a.titre, a.datePromenade, a.horaire, a.tarif, 
               a.endroitPromenade, a.description, u.pseudo AS nom_maitre, u.email, r.statut
        FROM " . PREFIXE_TABLE . "Annonce a
        INNER JOIN " . PREFIXE_TABLE . "Repond r ON a.id_annonce = r.id_annonce
        INNER JOIN " . PREFIXE_TABLE . "Utilisateur u ON a.id_utilisateur = u.id_utilisateur
        WHERE r.id_utilisateur = :id_utilisateur AND r.statut = 'en_attente'
        ORDER BY a.datePromenade DESC
    ";

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':id_utilisateur' => $id_utilisateur]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * @brief Accepte une candidature
 * @param int $id_annonce Identifiant de l'annonce
 * @param int $id_candidat Identifiant du candidat
 * @return int|false ID de réponse si succès, false sinon
 */
public function accepterCandidature(int $id_annonce, int $id_candidat)
{
    try {
        // Vérifier que la candidature existe et récupérer son ID
        $stmtCheck = $this->pdo->prepare("
            SELECT id_reponse FROM " . PREFIXE_TABLE . "Repond 
            WHERE id_annonce = :id_annonce AND id_utilisateur = :id_utilisateur
        ");
        $stmtCheck->execute([
            ':id_annonce' => $id_annonce,
            ':id_utilisateur' => $id_candidat
        ]);

        $reponse = $stmtCheck->fetch(PDO::FETCH_ASSOC);
        if (!$reponse) {
            return false;
        }

        $id_reponse = $reponse['id_reponse'];

        // Mettre à jour le statut à 'acceptee'
        $stmt = $this->pdo->prepare("
            UPDATE " . PREFIXE_TABLE . "Repond 
            SET statut = 'acceptee'
            WHERE id_annonce = :id_annonce AND id_utilisateur = :id_utilisateur
        ");
        
        if ($stmt->execute([
            ':id_annonce' => $id_annonce,
            ':id_utilisateur' => $id_candidat
        ])) {
            return $id_reponse;
        }
        return false;
    } catch (PDOException $e) {
        return false;
    }
}

/**
 * @brief Refuse une candidature
 * @param int $id_annonce Identifiant de l'annonce
 * @param int $id_candidat Identifiant du candidat
 * @return int|false ID de réponse si succès, false sinon
 */
public function refuserCandidature(int $id_annonce, int $id_candidat)
{
    try {
        // Récupérer l'ID de la réponse
        $stmtCheck = $this->pdo->prepare("
            SELECT id_reponse FROM " . PREFIXE_TABLE . "Repond 
            WHERE id_annonce = :id_annonce AND id_utilisateur = :id_utilisateur
        ");
        $stmtCheck->execute([
            ':id_annonce' => $id_annonce,
            ':id_utilisateur' => $id_candidat
        ]);

        $reponse = $stmtCheck->fetch(PDO::FETCH_ASSOC);
        if (!$reponse) {
            return false;
        }

        $id_reponse = $reponse['id_reponse'];

        // Mettre à jour le statut à 'refusee'
        $stmt = $this->pdo->prepare("
            UPDATE " . PREFIXE_TABLE . "Repond 
            SET statut = 'refusee'
            WHERE id_annonce = :id_annonce AND id_utilisateur = :id_utilisateur
        ");
        
        if ($stmt->execute([
            ':id_annonce' => $id_annonce,
            ':id_utilisateur' => $id_candidat
        ])) {
            return $id_reponse;
        }
        return false;
    } catch (PDOException $e) {
        return false;
    }
}

/**
 * @brief Récupère l'ID du candidat accepté pour une annonce
 * @param int $id_annonce Identifiant de l'annonce
 * @return int|null ID du candidat accepté ou null
 */
public function getCandidatAccepte(int $id_annonce): ?int
{
    $stmt = $this->pdo->prepare("
        SELECT id_utilisateur
        FROM " . PREFIXE_TABLE . "Repond
        WHERE id_annonce = :id_annonce
          AND statut IN ('acceptee', 'acceptée')
        ORDER BY id_reponse DESC
        LIMIT 1
    ");

    $stmt->execute([':id_annonce' => $id_annonce]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row && isset($row['id_utilisateur'])) {
        return (int) $row['id_utilisateur'];
    }

    return null;
}

/**
 * @brief Récupère les annonces/promenades d'un promeneur
 * @param int $id_promeneur Identifiant du promeneur
 * @return Annonce[] Tableau d'annonces associees
 */
public function findByPromeneur(int $id_promeneur): array
{
    $annonces = [];
    $stmt = $this->pdo->prepare("
        SELECT * FROM " . PREFIXE_TABLE . "Annonce
        WHERE id_promeneur = :id_promeneur
        ORDER BY datePromenade DESC
    ");

    $stmt->execute([':id_promeneur' => $id_promeneur]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($results as $row) {
        $annonces[] = $this->hydrate($row);
    }

    return $annonces;
}

/**
 * @brief Associe un promeneur a une annonce et initialise le statut de promenade
 * @param int $id_annonce Identifiant de l'annonce
 * @param int $id_promeneur Identifiant du promeneur
 * @param string $statut_promenade Statut de la promenade
 * @return bool Succès de la mise a jour
 */
public function assignerPromeneur(int $id_annonce, int $id_promeneur, string $statut_promenade = 'a_venir'): bool
{
    $stmt = $this->pdo->prepare("
        UPDATE " . PREFIXE_TABLE . "Annonce
        SET id_promeneur = :id_promeneur,
            statut_promenade = :statut_promenade
        WHERE id_annonce = :id_annonce
    ");
    $ok = $stmt->execute([
        ':id_promeneur' => $id_promeneur,
        ':statut_promenade' => $statut_promenade,
        ':id_annonce' => $id_annonce
    ]);

    if (!$ok) {
        $error = $stmt->errorInfo();
        error_log("❌ assignerPromeneur SQL error: " . implode(' | ', $error));
    }

    return $ok;
}

/**
 * @brief Met a jour le statut de promenade d'une annonce
 * @param int $id_annonce Identifiant de l'annonce
 * @param string $statut Nouveau statut
 * @return bool Succes de la mise a jour
 */
public function mettreAJourStatutPromenade(int $id_annonce, string $statut): bool
{
    $stmt = $this->pdo->prepare("
        UPDATE " . PREFIXE_TABLE . "Annonce
        SET statut_promenade = :statut
        WHERE id_annonce = :id_annonce
    ");

    return $stmt->execute([
        ':statut' => $statut,
        ':id_annonce' => $id_annonce
    ]);
}

/**
 * @brief Marque une promenade comme terminee et archive l'annonce associee
 * @param int $id_annonce Identifiant de l'annonce
 * @return bool Succes de la mise a jour
 */
public function marquerPromenadeTermineeEtArchiverAnnonce(int $id_annonce): bool
{
    $stmt = $this->pdo->prepare(" 
        UPDATE " . PREFIXE_TABLE . "Annonce
        SET statut_promenade = 'terminee',
            status = 'archivee'
        WHERE id_annonce = :id_annonce
    ");

    return $stmt->execute([
        ':id_annonce' => $id_annonce
    ]);
}

/**
 * @brief Archive automatiquement les annonces/promenades dont la date est depassee
 * @return int Nombre d'annonces basculees en archivees
 */
public function archiverPromenadesDepassees(): int
{
    $stmt = $this->pdo->prepare("
        UPDATE " . PREFIXE_TABLE . "Annonce
        SET status = 'archivee',
            statut_promenade = CASE
                WHEN id_promeneur IS NOT NULL THEN 'archivee'
                ELSE statut_promenade
            END
        WHERE STR_TO_DATE(CONCAT(datePromenade, ' ', horaire), '%Y-%m-%d %H:%i') < NOW()
          AND LOWER(status) <> 'archivee'
    ");

    $stmt->execute();
    return $stmt->rowCount();
}

/**
 * @brief Annule/supprime une candidature soumise par un promeneur
 * @param int $id_annonce Identifiant de l'annonce
 * @param int $id_utilisateur Identifiant de l'utilisateur
 * @return bool Succès de l'annulation
 */
public function supprimerCandidature(int $id_annonce, int $id_utilisateur): bool
{
    try {
        $stmt = $this->pdo->prepare("
            DELETE FROM " . PREFIXE_TABLE . "Repond 
            WHERE id_annonce = :id_annonce AND id_utilisateur = :id_utilisateur
        ");
        return $stmt->execute([
            ':id_annonce' => $id_annonce,
            ':id_utilisateur' => $id_utilisateur
        ]);
    } catch (PDOException $e) {
        return false;
    }
}

/**
 * @brief Récupère les promenades acceptées d'un promeneur (candidatures acceptées complètes)
 * @param int $id_utilisateur Identifiant du promeneur
 * @return array Tableau des promenades acceptées avec tous les détails
 */
public function getMesPromenades(int $id_utilisateur): array
{
    $sql = "
        SELECT 
            a.id_annonce, 
            a.titre, 
            a.datePromenade, 
            a.horaire, 
            a.tarif, 
            a.endroitPromenade, 
            a.description,
            a.status,
            a.statut_promenade,
            u.id_utilisateur AS id_maitre,
            u.pseudo AS nom_maitre, 
            u.email AS email_maitre,
            u.numTelephone AS telephone_maitre,
            c.id_chien,
            c.nom_chien,
            c.race,
            c.poids,
            c.taille,
            co.id_annonce AS id_annonce_concerne
        FROM " . PREFIXE_TABLE . "Annonce a
        INNER JOIN " . PREFIXE_TABLE . "Utilisateur u ON a.id_utilisateur = u.id_utilisateur
        LEFT JOIN " . PREFIXE_TABLE . "Concerne co ON a.id_annonce = co.id_annonce
        LEFT JOIN " . PREFIXE_TABLE . "Chien c ON co.id_chien = c.id_chien
        WHERE a.id_promeneur = :id_utilisateur
        ORDER BY a.datePromenade ASC
    ";

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':id_utilisateur' => $id_utilisateur]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


}