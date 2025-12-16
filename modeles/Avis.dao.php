<?php

require_once __DIR__ . '/../vendor/autoload.php';
use Symfony\Component\Yaml\Yaml;
// Charger le YAML
$config = Symfony\Component\Yaml\Yaml::parseFile(__DIR__ . '/../config/constantes.yaml');

// Définir les constantes 
defined('PREFIXE_TABLE') or define('PREFIXE_TABLE', $config['PREFIXE_TABLE']);

class AvisDAO
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
        $sql = "SELECT * FROM " . PREFIXE_TABLE . "Avis";
        $pdoStatement  = $this->pdo->prepare($sql);
        $pdoStatement ->execute();
        $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);
        $avis = $pdoStatement->fetchAll();
        
        return $this->hydrateAll($avis);
    }

    public function findById($id_avis): ?Avis
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

    public function findByIdUtilisateurNote($id_utilisateur_note): array
    {
        $sql = "SELECT * FROM " . PREFIXE_TABLE . "Avis WHERE id_utilisateur_note =".$id_utilisateur_note;
        $pdoStatement  = $this->pdo->prepare($sql);
        $pdoStatement ->execute();
        $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);
        $avis = $pdoStatement->fetchAll();
        
        return $this->hydrateAll($avis);
    }

    private function hydrateAll(array $result): array {
        $avisListe = [];
        foreach ($result as $ligne) {
            $avisListe[] = $this->hydrate($ligne);
        }
        return $avisListe;
    }

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
}