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
        $stmt = $this->pdo->prepare("SELECT * FROM " . PREFIXE_TABLE . "Chien WHERE id_chien = :id_chien");
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
            
<<<<<<< HEAD
=======
   
>>>>>>> bd7f2461d36bfc0914d1eb0182c277318fc48dfb
            
        }
        return null;
    }

    public function findAll(){
        $chiens = [];
        $pdoStatement = $this->pdo->prepare("SELECT * FROM " . PREFIXE_TABLE . "Chien");
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
<<<<<<< HEAD
=======
            
>>>>>>> bd7f2461d36bfc0914d1eb0182c277318fc48dfb
        }
        return $chiens;
    }
}
?>
