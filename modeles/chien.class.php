<?php
class Chien{
    private ?int $id_chien;
    private ?string $nom_chien;
    private ?float $poids;
    private ?int $taille;
    private ?string $race;
    private ?string $cheminPhoto;
    private ?int $id_utilisateur;

    public function __construct(?int $id_chien=null, ?string $nom_chien=null, ?float $poids=null, ?int $taille=null, ?string $race=null, ?string $cheminPhoto=null, ?int $id_utilisateur=null){
        $this->id_chien=$id_chien;
        $this->nom_chien=$nom_chien;
        $this->poids=$poids;
        $this->taille=$taille;
        $this->race=$race;
        $this->cheminPhoto=$cheminPhoto;
        $this->id_utilisateur=$id_utilisateur;
    }

    public function getIdChien(){
        return $this->id_chien;
    }
    public function setIdChien(int $id_chien): void{
        $this->id_chien=$id_chien;
    }

    public function getNomChien(){
        return $this->nom_chien;
    }
    public function setNomChien(string $nom_chien): void{
        $this->nom_chien=$nom_chien;
    }

    public function getPoids(){
        return $this->poids;
    }
    public function setPoids(float $poids): void{
        $this->poids=$poids;
    }

    public function getTaille(){
        return $this->taille;
    }
    public function setTaille(int $taille): void{
        $this->taille=$taille;
    }

    public function getRace(){
        return $this->race;
    }
    public function setRace(string $race): void{
        $this->race=$race;
    }

    public function getCheminPhoto(){
        return $this->cheminPhoto;
    }
    public function setCheminPhoto(string $cheminPhoto): void{
        $this->cheminPhoto=$cheminPhoto;
    }

    public function getIdUtilisateur(){
        return $this->id_utilisateur;
    }
    public function setIdUtilisateur(int $id_utilisateur): void{
        $this->id_utilisateur=$id_utilisateur;
    }
}

?>