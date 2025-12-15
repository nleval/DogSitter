<?php

require_once __DIR__ . '/../vendor/autoload.php';
use Symfony\Component\Yaml\Yaml;
// Charger le YAML
$config = Symfony\Component\Yaml\Yaml::parseFile(__DIR__ . '/../config/constantes.yaml');

// Définir les constantes 
defined('PREFIXE_TABLE') or define('PREFIXE_TABLE', $config['PREFIXE_TABLE']);
defined('MAX_CONNEXIONS_ECHOUEES') or define('MAX_CONNEXIONS_ECHOUEES', $config['max_connexions_echouees']);
defined('DELAI_ATTENTE_CONNEXION') or define('DELAI_ATTENTE_CONNEXION', $config['delai_attente_connexion']);

class UtilisateurDAO
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
        $sql = "SELECT * FROM " . PREFIXE_TABLE . "Utilisateur";
        $pdoStatement  = $this->pdo->prepare($sql);
        $pdoStatement ->execute();
        $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);
        $utilisateur = $pdoStatement->fetchAll();
        
        return $this->hydrateAll($utilisateur);
    }

    public function findById($id_utilisateur): ?Utilisateur
    {
        if ($id_utilisateur === null) {
            return null;
        }

        $sql = "SELECT * FROM " . PREFIXE_TABLE . "Utilisateur WHERE id_utilisateur = :id_utilisateur";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute([':id_utilisateur' => $id_utilisateur]);
        $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);
        $utilisateur = $pdoStatement->fetch();

        return $utilisateur ? $this->hydrate($utilisateur) : null;
    }

    private function hydrateAll(array $resul): array {
        $utilisateurListe = [];
        foreach ($resul as $ligne) {
            $utilisateurListe[] = $this->hydrate($ligne);
        }
        return $utilisateurListe;
    }

    private function hydrate(array $tableauAssoc): ?Utilisateur {
        $utilisateur = new Utilisateur();

        $utilisateur->setId($tableauAssoc['id_utilisateur'] ?? null);
        $utilisateur->setEmail($tableauAssoc['email'] ?? null);
        $utilisateur->setEstMaitre($tableauAssoc['estMaitre'] ?? null);
        $utilisateur->setEstPromeneur($tableauAssoc['estPromeneur'] ?? null);
        $utilisateur->setAdresse($tableauAssoc['adresse'] ?? null);
        $utilisateur->setMotDePasse($tableauAssoc['motDePasse'] ?? null);
        $utilisateur->setNom($tableauAssoc['nom'] ?? null);
        $utilisateur->setPrenom($tableauAssoc['prenom'] ?? null);
        $utilisateur->setNumTelephone($tableauAssoc['numTelephone'] ?? null);
        $utilisateur->setTentativesEchouees((int)($tableauAssoc['tentatives_echouees'] ?? 0));
        $utilisateur->setDateDernierEchecConnexion($tableauAssoc['date_dernier_echec_connexion'] ?? null);
        $utilisateur->setStatutCompte($tableauAssoc['statut_compte'] ?? 'actif');

        return $utilisateur;
    }


    public function supprimerUtilisateur($id_utilisateur): ?bool
    {
        $sql = "DELETE FROM " . PREFIXE_TABLE . "utilisateur WHERE id_utilisateur = :id_utilisateur";
        $pdoStatement = $this->pdo->prepare($sql);
        return $pdoStatement->execute([':id_utilisateur' => $id_utilisateur]);
    }

    public function emailExist(string $email): bool
{
    $sql = "SELECT COUNT(*) FROM " . PREFIXE_TABLE . "Utilisateur WHERE email = :email";
    $stmt = $this->pdo->prepare($sql);
    $stmt->bindValue(':email', $email);
    $stmt->execute();

    return $stmt->fetchColumn() > 0;
}

public function estRobuste(string $motDePasse): bool
{
    $regex = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/';

    // La fonction preg_match retourne 1 si le motif correspond, 0 sinon
    return preg_match($regex, $motDePasse) === 1;
}

public function inscription(Utilisateur $utilisateur): bool
{
    // Vérifier si le mdp est robuste
    if (!$this->estRobuste($utilisateur->getMotDePasse())) {
        throw new Exception("Le mot de passe n'est pas assez robuste.");
    }

    // Vérifier si l'email existe déjà
    if ($this->emailExist($utilisateur->getEmail())) {
        throw new Exception("L'email existe déjà.");
    }

    // Connexion avec la base de données
    $pdo = Bd::getInstance()->getConnexion();

    // Hacher le mot de passe avant de l'enregistrer
    $motDePasseHache = password_hash($utilisateur->getMotDePasse(), PASSWORD_BCRYPT);
    
    // Préparer et exécuter la requête d'insertion
    $sql = "INSERT INTO " . PREFIXE_TABLE . "Utilisateur (email, estMaitre, estPromeneur, adresse, motDePasse, nom, prenom, numTelephone) 
            VALUES (:email, :estMaitre, :estPromeneur, :adresse, :motDePasse, :nom, :prenom, :numTelephone)";
    
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([
        ':email'        => $utilisateur->getEmail(),
        ':estMaitre'    => $utilisateur->getEstMaitre(),
        ':estPromeneur' => $utilisateur->getEstPromeneur(),
        ':adresse'      => $utilisateur->getAdresse(),
        ':motDePasse'   => $motDePasseHache,
        ':nom'          => $utilisateur->getNom(),
        ':prenom'       => $utilisateur->getPrenom(),
        ':numTelephone' => $utilisateur->getNumTelephone()
    ]);

}

public function authentification(string $email, string $motDePasse): bool
{
    // Récupération utilisateur
    $utilisateur = $this->findByEmail($email);

    if (!$utilisateur) {
        return false; // Email inexistant
    }

    // Vérification si le compte est temporairement désactivé 
    $compteDesactive = ($utilisateur->getStatutCompte() === 'desactive');
    $derniereTentative = $utilisateur->getDateDernierEchecConnexion();

    if ($compteDesactive && $derniereTentative) {

        $tempsRestant = $this->tempsRestantAvantReactivationCompte($derniereTentative);

        if ($tempsRestant > 0) {
            // Toujours bloqué → on stoppe l’authentification
            throw new Exception("compte_desactive");
        }

        // Si le temps est expiré → on réactive le compte
        $this->reactiverCompte($utilisateur->getId());
        $utilisateur->setTentativesEchouees(0);
    }

    // Vérification du mot de passe 
    $mdpCorrect = password_verify($motDePasse, $utilisateur->getMotDePasse());

    if ($mdpCorrect) {

        // Si l'utilisateur avait des tentatives enregistrées → on les remet à 0
        if ($utilisateur->getTentativesEchouees() > 0) {
            $this->reinitialiserTentatives($utilisateur->getId());
        }

        return true;
    }

    // Mauvais mot de passe : on incrémente les tentatives 
    $this->incrementerTentatives($utilisateur);

    return false;
}

    /** Récupère un utilisateur par email */
    public function findByEmail(string $email): ?Utilisateur
    {
        $sql = "SELECT * FROM " . PREFIXE_TABLE . "Utilisateur WHERE email = :email";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':email' => $email]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $this->hydrate($result) : null;
    }

    /** Incrémente les tentatives échouées */
    public function incrementerTentatives(Utilisateur $utilisateur): void
    {
        $tentatives = $utilisateur->getTentativesEchouees() + 1;
        $utilisateur->setTentativesEchouees($tentatives);

        // On met à jour la date de la dernière tentative dans l'objet
        $dateNow = date('Y-m-d H:i:s');
        $utilisateur->setDateDernierEchecConnexion($dateNow);

        if ($tentatives >= MAX_CONNEXIONS_ECHOUEES) {
            $sql = "UPDATE " . PREFIXE_TABLE . "Utilisateur
                    SET tentatives_echouees = :tentatives, date_dernier_echec_connexion = :date, statut_compte = 'desactive'
                    WHERE id_utilisateur = :id";
            $utilisateur->setStatutCompte('desactive');
        } else {
            $sql = "UPDATE " . PREFIXE_TABLE . "Utilisateur
                    SET tentatives_echouees = :tentatives, date_dernier_echec_connexion = :date
                    WHERE id_utilisateur = :id";
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':tentatives' => $tentatives,
            ':date' => $dateNow,
            ':id' => $utilisateur->getId()
        ]);
    }

    /** Réinitialise les tentatives après succès de connexion */
    public function reinitialiserTentatives(int $id): void
    {
        $sql = "UPDATE " . PREFIXE_TABLE . "Utilisateur
                SET tentatives_echouees = 0, date_dernier_echec_connexion = NULL, statut_compte = 'actif'
                WHERE id_utilisateur = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
    }

    /** Réactive un compte désactivé */
    public function reactiverCompte(int $id): void
    {
        $sql = "UPDATE " . PREFIXE_TABLE . "Utilisateur
                SET statut_compte = 'actif', tentatives_echouees = 0, date_dernier_echec_connexion = NULL
                WHERE id_utilisateur = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
    }

    /** Calcule le temps restant avant réactivation */
    public function tempsRestantAvantReactivationCompte(string $dateDernierEchec): int
    {
        $tempsEcoule = time() - strtotime($dateDernierEchec);
        $tempsRestant = DELAI_ATTENTE_CONNEXION - $tempsEcoule;
        return $tempsRestant > 0 ? $tempsRestant : 0;
    }
}

