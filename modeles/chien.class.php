<?php
class Chien{
    private ?int $id_chien;
    private ?string $nom_chien;
    private ?string $poids;
    private ?string $taille;
    private ?string $race;
    private ?string $cheminPhoto;
    private ?int $id_utilisateur;

    public function __construct(?int $id_chien=null, ?string $nom_chien=null, ?string $poids=null, ?string $taille=null, ?string $race=null, ?string $cheminPhoto=null, ?int $id_utilisateur=null){
        $this->id_chien=$id_chien;
        $this->nom_chien=$nom_chien;
        $this->poids=$poids;
        $this->taille=$taille;
        $this->race=$race;
        $this->cheminPhoto=$cheminPhoto;
        $this->id_utilisateur=$id_utilisateur;
    }

    public function getId_Chien(){
        return $this->id_chien;
    }
    public function setId_Chien(int $id_chien): void{
        $this->id_chien=$id_chien;
    }

    public function getNom_Chien(){
        return $this->nom_chien;
    }
    public function setNom_Chien(string $nom_chien): void{
        $this->nom_chien=$nom_chien;
    }

    public function getPoids(){
        return $this->poids;
    }
    public function setPoids(string $poids): void{
        $this->poids=$poids;
    }

    public function getTaille(){
        return $this->taille;
    }
    public function setTaille(string $taille): void{
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

    public function getid_Utilisateur(){
        return $this->id_utilisateur;
    }
    public function setid_Utilisateur(int $id_utilisateur): void{
        $this->id_utilisateur=$id_utilisateur;
    }
}

?>