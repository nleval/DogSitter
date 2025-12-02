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
        $utilisateurs = [];
        $stmt = $this->pdo->prepare("SELECT * FROM " . PREFIXE_TABLE . "Utilisateur");
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($results as $row) {
            $utilisateurs[] = new Utilisateur(
                $row['id_utilisateur'],
                $row['email'],
                $row['estMaitre'],
                $row['estPromeneur'],
                $row['adresse'],
                $row['motDePasse'],
                $row['nom'],
                $row['prenom'],
                $row['numTelephone']
            );
        }

        return $utilisateurs;
    }

    public function findById($id_utilisateur): ?Utilisateur
    {
        $stmt = $this->pdo->prepare("SELECT * FROM " . PREFIXE_TABLE . "Utilisateur WHERE id_utilisateur = :id_utilisateur");
        $stmt->bindParam(':id_utilisateur', $id_utilisateur, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new Utilisateur(
                $row['id_utilisateur'],
                $row['email'],
                $row['estMaitre'],
                $row['estPromeneur'],
                $row['adresse'],
                $row['motDePasse'],
                $row['nom'],
                $row['prenom'],
                $row['numTelephone']
            );
        }

        return null;
    }

    public function ajouterUtilisateur(?Utilisateur $utilisateur): ?bool {
        $sql = "INSERT INTO " . PREFIXE_TABLE . "utilisateur 
                (email, estMaitre, estPromeneur, adresse, motDePasse, nom, prenom, numTelephone) 
                VALUES 
                (:email, :estMaitre, :estPromeneur, :adresse, :motDePasse, :nom, :prenom, :numTelephone)";

        $pdoStatement = $this->pdo->prepare($sql);

        $reussite = $pdoStatement->execute([
            'email'        => $utilisateur->getEmail(),
            'estMaitre'    => $utilisateur->getEstMaitre(),
            'estPromeneur' => $utilisateur->getEstPromeneur(),
            'adresse'      => $utilisateur->getAdresse(),
            'motDePasse'   => $utilisateur->getMotDePasse(),
            'nom'          => $utilisateur->getNom(),
            'prenom'       => $utilisateur->getPrenom(),
            'numTelephone' => $utilisateur->getNumTelephone()
        ]);

    return $reussite;
}

}