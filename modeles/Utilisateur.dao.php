<?php
/**
 * @file utilisateur.dao.php
 * @author Léval Noah
 * @brief Gestion de la base de donees pour les utilisateurs
 * @version 1.0
 * @date 2025-12-18
 */

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
    /**
     * @brief ?PDO $pdo Instance PDO pour la connexion à la base de données.
     */
    private ?PDO $pdo;    

    /**
     * @brief Constructeur du DAO.
     * @param ?PDO $pdo Connexion PDO optionnelle.
     */
    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo;
    }

    /**
     * @brief Récupère l'objet PDO.
     * @return ?PDO
     */
    public function getPdo(): ?PDO
    {
        return $this->pdo;
    }

    /**
     * @brief Définit l'objet PDO.
     * @param ?PDO $pdo
     */
    public function setPdo(?PDO $pdo): void
    {
        $this->pdo = $pdo;
    }

    /**
     * @brief Récupère tous les utilisateurs.
     * @return Utilisateur[] Tableau d'objets Utilisateur.
     */
    public function findAll(): array
    {
        $sql = "SELECT * FROM " . PREFIXE_TABLE . "Utilisateur";
        $stmt  = $this->pdo->prepare($sql);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $utilisateurs = $stmt->fetchAll();
        
        return $this->hydrateAll($utilisateurs);
    }

    /**
     * @brief Récupère un utilisateur par son ID.
     * @param int $id_utilisateur
     * @return ?Utilisateur
     */
    public function findById($id_utilisateur): ?Utilisateur
    {
        if ($id_utilisateur === null) {
            return null;
        }

        $sql = "SELECT * FROM " . PREFIXE_TABLE . "Utilisateur WHERE id_utilisateur = :id_utilisateur";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id_utilisateur' => $id_utilisateur]);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $utilisateur = $stmt->fetch();

        return $utilisateur ? $this->hydrate($utilisateur) : null;
    }

    /**
     * @brief Hydrate un tableau de résultats en objets Utilisateur.
     * @param array $resul
     * @return Utilisateur[]
     */
    private function hydrateAll(array $resul): array
    {
        $utilisateurListe = [];
        foreach ($resul as $ligne) {
            $utilisateurListe[] = $this->hydrate($ligne);
        }
        return $utilisateurListe;
    }

    /**
     * @brief Hydrate un tableau associatif en objet Utilisateur.
     * @param array $tableauAssoc
     * @return ?Utilisateur
     */
    private function hydrate(array $tableauAssoc): ?Utilisateur
    {
        $utilisateur = new Utilisateur();
        $utilisateur->setId($tableauAssoc['id_utilisateur'] ?? null);
        $utilisateur->setEmail($tableauAssoc['email'] ?? null);
        $utilisateur->setEstMaitre($tableauAssoc['estMaitre'] ?? null);
        $utilisateur->setEstPromeneur($tableauAssoc['estPromeneur'] ?? null);
        $utilisateur->setAdresse($tableauAssoc['adresse'] ?? null);
        $utilisateur->setMotDePasse($tableauAssoc['motDePasse'] ?? null);
        $utilisateur->setNumTelephone($tableauAssoc['numTelephone'] ?? null);
        $utilisateur->setPseudo($tableauAssoc['pseudo'] ?? null);
        $utilisateur->setPhotoProfil($tableauAssoc['photoProfil'] ?? null);
        $utilisateur->setTentativesEchouees((int)($tableauAssoc['tentatives_echouees'] ?? 0));
        $utilisateur->setDateDernierEchecConnexion($tableauAssoc['date_dernier_echec_connexion'] ?? null);
        $utilisateur->setStatutCompte($tableauAssoc['statut_compte'] ?? 'actif');

        return $utilisateur;
    }

    /**
     * @brief Ajoute un nouvel utilisateur dans la base.
     * @param Utilisateur $utilisateur
     * @return ?bool
     */
    public function ajouterUtilisateur(?Utilisateur $utilisateur): ?bool
    {
        $sql = "INSERT INTO " . PREFIXE_TABLE . "Utilisateur 
                (email, estMaitre, estPromeneur, adresse, motDePasse, numTelephone, pseudo, photoProfil) 
                VALUES (:email, :estMaitre, :estPromeneur, :adresse, :motDePasse, :numTelephone, :pseudo, :photoProfil)";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'email'        => $utilisateur->getEmail(),
            'estMaitre'    => $utilisateur->getEstMaitre(),
            'estPromeneur' => $utilisateur->getEstPromeneur(),
            'adresse'      => $utilisateur->getAdresse(),
            'motDePasse'   => $utilisateur->getMotDePasse(),
            'numTelephone' => $utilisateur->getNumTelephone(), 
            'pseudo'       => $utilisateur->getPseudo(),
            'photoProfil'  => $utilisateur->getPhotoProfil(),
        ]);
    }

    /**
     * @brief Supprime un utilisateur par ID.
     * @param int $id_utilisateur
     * @return ?bool
     */
    public function supprimerUtilisateur($id_utilisateur): ?bool
    {
        $sql = "DELETE FROM " . PREFIXE_TABLE . "Utilisateur WHERE id_utilisateur = :id_utilisateur";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id_utilisateur' => $id_utilisateur]);
    }

    /**
     * @brief Vérifie si un email existe déjà.
     * @param string $email
     * @return bool
     */
    public function emailExiste(string $email): bool
    {
        $sql = "SELECT COUNT(*) FROM " . PREFIXE_TABLE . "Utilisateur WHERE email = :email";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    public function estActif(string $mail): bool
    {
        $sql = "SELECT statut_compte FROM " . PREFIXE_TABLE . "Utilisateur WHERE email = :mail";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':mail', $mail);
        $stmt->execute();
        $status = $stmt->fetchColumn();
        return $status === 'actif';
    }

    /**
     * @brief Vérifie si un mot de passe est robuste.
     * @param string $motDePasse
     * @return bool
     */
    public function estRobuste(string $motDePasse): bool
    {
        $regex = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/';
        return preg_match($regex, $motDePasse) === 1;
    }

    /**
     * @brief Inscription d'un utilisateur avec vérification email et robustesse mot de passe.
     * @param Utilisateur $utilisateur
     * @return bool
     * @throws Exception
     */
    public function inscription(Utilisateur $utilisateur): bool
    {
        if (!$this->estRobuste($utilisateur->getMotDePasse())) {
            throw new Exception("Le mot de passe n'est pas assez robuste.");
        }
        if ($this->emailExiste($utilisateur->getEmail())) {
            throw new Exception("L'email existe déjà.");
        }

        $pdo = Bd::getInstance()->getConnexion();
        $motDePasseHache = password_hash($utilisateur->getMotDePasse(), PASSWORD_BCRYPT);

        $sql = "INSERT INTO " . PREFIXE_TABLE . "Utilisateur 
                (email, estMaitre, estPromeneur, adresse, motDePasse, numTelephone, pseudo, photoProfil) 
                VALUES (:email, :estMaitre, :estPromeneur, :adresse, :motDePasse, :numTelephone, :pseudo, :photoProfil)";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            ':email'        => $utilisateur->getEmail(),
            ':estMaitre'    => $utilisateur->getEstMaitre(),
            ':estPromeneur' => $utilisateur->getEstPromeneur(),
            ':adresse'      => $utilisateur->getAdresse(),
            ':motDePasse'   => $motDePasseHache,
            ':numTelephone' => $utilisateur->getNumTelephone(),
            ':pseudo'       => $utilisateur->getPseudo(),
            ':photoProfil'  => $utilisateur->getPhotoProfil()
        ]);
    }

    /**
     * @brief Authentification d'un utilisateur.
     * @param string $email
     * @param string $motDePasse
     * @return bool
     * @throws Exception
     */
    public function authentification(string $email, string $motDePasse): bool
    {
        $utilisateur = $this->findByEmail($email);
        if (!$utilisateur) return false;

        $compteDesactive = ($utilisateur->getStatutCompte() === 'desactive');
        $derniereTentative = $utilisateur->getDateDernierEchecConnexion();

        if ($compteDesactive && $derniereTentative) {
            $tempsRestant = $this->tempsRestantAvantReactivationCompte($derniereTentative);
            if ($tempsRestant > 0) throw new Exception("compte_desactive");
            $this->reactiverCompte($utilisateur->getId());
            $utilisateur->setTentativesEchouees(0);
        }

        if (password_verify($motDePasse, $utilisateur->getMotDePasse())) {
            if ($utilisateur->getTentativesEchouees() > 0) $this->reinitialiserTentatives($utilisateur->getId());
            return true;
        }

        $this->incrementerTentatives($utilisateur);
        return false;
    }

    /**
     * @brief Récupère un utilisateur par email.
     * @param string $email
     * @return ?Utilisateur
     */
    public function findByEmail(string $email): ?Utilisateur
    {
        $sql = "SELECT * FROM " . PREFIXE_TABLE . "Utilisateur WHERE email = :email";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':email' => $email]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $this->hydrate($result) : null;
    }

    /**
     * @brief Incrémente les tentatives échouées et désactive le compte si nécessaire.
     * @param Utilisateur $utilisateur
     */
    public function incrementerTentatives(Utilisateur $utilisateur): void
    {
        $tentatives = $utilisateur->getTentativesEchouees() + 1;
        $utilisateur->setTentativesEchouees($tentatives);
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
            ':date'       => $dateNow,
            ':id'         => $utilisateur->getId()
        ]);
    }

    /**
     * @brief Réinitialise les tentatives après succès de connexion.
     * @param int $id
     */
    public function reinitialiserTentatives(int $id): void
    {
        $sql = "UPDATE " . PREFIXE_TABLE . "Utilisateur
                SET tentatives_echouees = 0, date_dernier_echec_connexion = NULL, statut_compte = 'actif'
                WHERE id_utilisateur = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
    }

    /**
     * @brief Réactive un compte désactivé.
     * @param int $id
     */
    public function reactiverCompte(int $id): void
    {
        $sql = "UPDATE " . PREFIXE_TABLE . "Utilisateur
                SET statut_compte = 'actif', tentatives_echouees = 0, date_dernier_echec_connexion = NULL
                WHERE id_utilisateur = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
    }

    /**
     * @brief Calcule le temps restant avant réactivation du compte.
     * @param string $dateDernierEchec
     * @return int Temps restant en secondes
     */
    public function tempsRestantAvantReactivationCompte(string $dateDernierEchec): int
    {
        $tempsEcoule = time() - strtotime($dateDernierEchec);
        $tempsRestant = DELAI_ATTENTE_CONNEXION - $tempsEcoule;
        return $tempsRestant > 0 ? $tempsRestant : 0;
    }

    /**
     * @brief Modifie un champ spécifique d'un utilisateur.
     * @param int $id_utilisateur
     * @param string $champ
     * @param mixed $nouvelleValeur
     * @return ?bool
     */
    public function modifierChamp($id_utilisateur, $champ, $nouvelleValeur): ?bool
    {
        $sql = "UPDATE " . PREFIXE_TABLE . "Utilisateur SET $champ = :nouvelleValeur WHERE id_utilisateur = :id_utilisateur";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':nouvelleValeur' => $nouvelleValeur,
            ':id_utilisateur' => $id_utilisateur
        ]);
    }
}

   


