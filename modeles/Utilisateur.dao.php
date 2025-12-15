<?php

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
        if ($id_utilisateur === null && isset($_GET['id_utilisateur'])) {
            $id_utilisateur = (int) $_GET['id_utilisateur'];
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
        $utilisateur->setNumTelephone($tableauAssoc['numTelephone'] ?? null);
        $utilisateur->setPseudo($tableauAssoc['pseudo'] ?? null);
        $utilisateur->setPhotoProfil($tableauAssoc['photoProfil'] ?? null);

        return $utilisateur;
    }

    public function ajouterUtilisateur(?Utilisateur $utilisateur): ?bool {
        $sql = "INSERT INTO " . PREFIXE_TABLE . "utilisateur 
                (email, estMaitre, estPromeneur, adresse, motDePasse, numTelephone, pseudo, photoProfil) 
                VALUES 
                (:email, :estMaitre, :estPromeneur, :adresse, :motDePasse, :numTelephone, :pseudo, :photoProfil)";

        $pdoStatement = $this->pdo->prepare($sql);

        $reussite = $pdoStatement->execute([
            'email'        => $utilisateur->getEmail(),
            'estMaitre'    => $utilisateur->getEstMaitre(),
            'estPromeneur' => $utilisateur->getEstPromeneur(),
            'adresse'      => $utilisateur->getAdresse(),
            'motDePasse'   => $utilisateur->getMotDePasse(),
            'numTelephone' => $utilisateur->getNumTelephone(), 
            'pseudo'       => $utilisateur->getPseudo(),
            'photoProfil'  => $utilisateur->getPhotoProfil(),
        ]);

    return $reussite;
    }

    public function supprimerUtilisateur($id_utilisateur): ?bool
    {
        $sql = "DELETE FROM " . PREFIXE_TABLE . "utilisateur WHERE id_utilisateur = :id_utilisateur";
        $pdoStatement = $this->pdo->prepare($sql);
        return $pdoStatement->execute([':id_utilisateur' => $id_utilisateur]);
    }

    public function modifierChamp($id_utilisateur, $champ, $nouvelleValeur): ?bool
    {
        $sql = "UPDATE " . PREFIXE_TABLE . "utilisateur SET $champ = :nouvelleValeur WHERE id_utilisateur = :id_utilisateur";
        $pdoStatement = $this->pdo->prepare($sql);
        return $pdoStatement->execute([
            ':nouvelleValeur' => $nouvelleValeur,
            ':id_utilisateur' => $id_utilisateur
        ]);
    }

}