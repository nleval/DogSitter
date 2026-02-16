<?php
/**
 * @file controller_annonce.class.php
 * @author Campistron Julian
 * @brief Gestion de la base de donnees pour les avis
 * @version 1.0
 * @date 2025-12-18
 */

require_once __DIR__ . '/../vendor/autoload.php';
use Symfony\Component\Yaml\Yaml;
// Charger le YAML
$config = Symfony\Component\Yaml\Yaml::parseFile(__DIR__ . '/../config/constantes.yaml');

// Définir les constantes 
defined('PREFIXE_TABLE') or define('PREFIXE_TABLE', $config['PREFIXE_TABLE']);

/**
 * @Class AvisDAO
 * @details Permet de lier la classe Avis à la base de données
 */
class AvisDAO
{
    /**
     * @var PDO|null
     */
    private ?PDO $pdo;

    /**
     * @constructor CategorieDao
     * @param PDO|null $pdo
     * @return void
     */
    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo;
    }

    /**
     * @function getPDO
     * @return PDO|null
     */
    public function getPdo(): ?PDO
    {
        return $this->pdo;
    }

    /**
     * @function setPDO 
     * @param PDO|null $pdo
     * @return void
     */
    public function setPdo(?PDO $pdo): void
    {
        $this->pdo = $pdo;
    }

    /**
     * @function trouverTous
     * @details Cette fonction permet de récupérer tous les avis en base de données
     * @uses hydrateAll
     * @return array
     */
    public function trouverTous(): array
    {
        $sql = "SELECT * FROM " . PREFIXE_TABLE . "Avis";
        $pdoStatement  = $this->pdo->prepare($sql);
        $pdoStatement ->execute();
        $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);
        $avis = $pdoStatement->fetchAll();
        
        return $this->hydrateAll($avis);
    }

    /**
     * @function trouverParId
     * @details Cette fonction permet de récupérer l'avis en base de données dont l'ID est donné en paramètre
     * @param Avis|null $id_avis
     * @return array
     */
    public function trouverParId($id_avis): ?Avis
    {
        if ($id_avis === null) {
            return null;
        }

        $sql = "SELECT * FROM " . PREFIXE_TABLE . "Avis WHERE id_avis = :id_avis";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute([':id_avis' => $id_avis]);
        $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);
        $avis = $pdoStatement->fetch();

        return $avis ? $this->hydrate($avis) : null;
    }

    /**
     * @function trouverParIdUtilisateurNote
     * @details Cette fonction permet de récupérer tous les avis en base de données dont l'ID de l'utilisateur noté est donné en paramètre
     * @param int $id_utilisateur_note
     * @return array
     */
    public function trouverParIdUtilisateurNote($id_utilisateur_note): array
    {
        $sql = "SELECT * FROM " . PREFIXE_TABLE . "Avis WHERE id_utilisateur_note =".$id_utilisateur_note;
        $pdoStatement  = $this->pdo->prepare($sql);
        $pdoStatement ->execute();
        $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);
        $avis = $pdoStatement->fetchAll();
        
        return $this->hydrateAll($avis);
    }

    /**
     * @function trouverParIdPromenade
     * @details Cette fonction permet de récupérer tous les avis en base de données d'une promenade
     * @param int $id_promenade
     * @return array
     */
    public function trouverParIdPromenade($id_promenade): array
    {
        $sql = "SELECT * FROM " . PREFIXE_TABLE . "Avis WHERE id_promenade = :id_promenade";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute([':id_promenade' => $id_promenade]);
        $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);
        $avis = $pdoStatement->fetchAll();

        return $this->hydrateAll($avis);
    }

    /**
     * @function getStatsParUtilisateurNote
     * @details Retourne la moyenne et le nombre d'avis pour un utilisateur note
     * @param int $id_utilisateur_note
     * @return array{moyenne: float, total: int}
     */
    public function getStatsParUtilisateurNote(int $id_utilisateur_note): array
    {
        $sql = "SELECT AVG(CAST(note AS DECIMAL(10,2))) AS moyenne, COUNT(*) AS total
                FROM " . PREFIXE_TABLE . "Avis
                WHERE id_utilisateur_note = :id_utilisateur_note";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id_utilisateur_note' => $id_utilisateur_note]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return [
            'moyenne' => isset($row['moyenne']) ? (float) $row['moyenne'] : 0.0,
            'total' => isset($row['total']) ? (int) $row['total'] : 0
        ];
    }

    /**
     * function hydrateAll
     * @details Cette fonction permet d'hydrater un tableau de données en objets Avis
     * @param array $result
     * @uses hydrate
     * @return array
     */
    private function hydrateAll(array $result): array {
        $avisListe = [];
        foreach ($result as $ligne) {
            $avisListe[] = $this->hydrate($ligne);
        }
        return $avisListe;
    }

    /**
     * @function hydrate
     * @details Cette fonction permet d'hydrater un avis en base de données
     * @param array $tableauAssoc
     * @return Avis
     */
    private function hydrate(array $tableauAssoc): ?Avis {
        $avis = new Avis();

        $avis->setId($tableauAssoc['id_avis'] ?? null);
        $avis->setNote($tableauAssoc['note'] ?? null);
        $avis->setTexteCommentaire($tableauAssoc['texte_commentaire'] ?? null);
        $avis->setIdUtilisateur($tableauAssoc['id_utilisateur'] ?? null);
        $avis->setIdPromenade($tableauAssoc['id_promenade'] ?? null);
        $avis->setIdUtilisateurNote($tableauAssoc['id_utilisateur_note'] ?? null);

        return $avis;
    }

    /**
     * @brief Ajoute un nouvel avis en base.
     *
     * @param ?Avis $avis Objet Avis à insérer.
     * @return bool Succès de l'insertion.
     */
    public function ajouter(?Avis $avis): bool
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO " . PREFIXE_TABLE . "Avis 
            (note, texte_commentaire, id_utilisateur, id_promenade, id_utilisateur_note)
            VALUES (:note, :texte_commentaire, :id_utilisateur, :id_promenade, :id_utilisateur_note)
        ");

        return $stmt->execute([
            ':note' => $avis->getNote(),
            ':texte_commentaire' => $avis->getTexteCommentaire(),
            ':id_utilisateur' => $avis->getIdUtilisateur(),
            ':id_promenade' => $avis->getIdPromenade(),
            ':id_utilisateur_note' => $avis->getIdUtilisateurNote(),
        ]);
    }
}