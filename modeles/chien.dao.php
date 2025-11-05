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

    public function find($id){

    }

    public function findAll(){

    }
}
?>