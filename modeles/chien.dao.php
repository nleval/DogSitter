<?php
class ChienDAO{
    private ?PDO $pdo;

    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo;
    }

    public function getPdo(): ?PDO{
        return $this->pdo;
    }
    public function setPdo(?PDO $pdo): void{
        $this->pdo = $pdo;
    }

    public function findById(?int $id_chien): ?Chien{
        $stmt = $this->pdo->prepare("SELECT * FROM " . PREFIXE_TABLE . "chien WHERE id_chien = :id_chien");
        $stmt->bindParam(':id_chien', $id_chien, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row){
            return new Chien(
                $row['id_chien'],
                $row['nom_chien'],
                $row['poids'],
                $row['taille'],
                $row['race'],
                $row['cheminPhoto'],
                $row['id_utilisateur']
            );            
        }
        return null;
    }

    public function findAll(){
        $chiens = [];
        $pdoStatement = $this->pdo->prepare("SELECT * FROM " . PREFIXE_TABLE . "chien");
        $pdoStatement->execute();
        $results = $pdoStatement->fetchAll(PDO::FETCH_ASSOC);
        foreach ($results as $row) {
            $chiens[] = new Chien(
                $row['id_chien'],
                $row['nom_chien'],
                $row['poids'],
                $row['taille'],
                $row['race'],
                $row['cheminPhoto'],
                $row['id_utilisateur']
            );
        }
        return $chiens;
    }

    public function findByAnnonce($id_annonce): array
{
    $chiens = [];
    // Jointure entre dog_chien (c) et dog_concerne (co) pour filtrer par id_annonce
    $sql = "SELECT c.* FROM " . PREFIXE_TABLE . "chien c 
            JOIN " . PREFIXE_TABLE . "concerne co ON c.id_chien = co.id_chien 
            WHERE co.id_annonce = :id_annonce";
            
    $stmt = $this->pdo->prepare($sql);
    $stmt->bindParam(':id_annonce', $id_annonce, PDO::PARAM_INT);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Hydratation des objets Chien
    foreach ($results as $row) {
        $chiens[] = new Chien(
            $row['id_chien'],
            $row['nom_chien'],
            $row['poids'],
            $row['taille'],
            $row['race'],
            $row['cheminPhoto'],
            $row['id_utilisateur']
        );
    }
    return $chiens;
}

public function findByUtilisateur(int $id_utilisateur): array
{
    $stmt = $this->pdo->prepare("
        SELECT *
        FROM " . PREFIXE_TABLE . "chien
        WHERE id_utilisateur = :id_utilisateur
    ");

    $stmt->bindParam(':id_utilisateur', $id_utilisateur, PDO::PARAM_INT);
    $stmt->execute();

    $resultats = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $chiens = [];

    foreach ($resultats as $row) {
        $chiens[] = new Chien(
            $row['id_chien'],
            $row['nom_chien'],
            $row['poids'],
            $row['taille'],
            $row['race'],
            $row['cheminPhoto'],
            $row['id_utilisateur']
        );
    }

    return $chiens;
}

}
?>
