<?php

class Promenade{
    private $id_promenade;
    private $statut;
    private $id_promeneur;
    private $id_chien;
    private $id_proprietaire;
    private $id_annonce;

    public function __construct($id_promenade,$statut,$id_promeneur,$id_chien,$id_proprietaire,$id_annonce) {
        $this-> id_promenade = $id_promenade;
        $this-> statut = $statut;
        $this-> id_promeneur = $id_promeneur;
        $this-> id_chien = $id_chien;
        $this-> id_proprietaire = $id_proprietaire;
        $this-> id_annonce = $id_annonce;
    }

    // ID
    public function getid_promenade() {
        return $this->id_promenade;
    }
    public function setid_promenade($id_promenade): void {
        $this->id_promenade = $id_promenade;
    }

    // Statut
    public function getStatut() {
        return $this->statut;
    }
    public function setStatut($statut): void {
        $this->statut = $statut;
    }

    // ID_CHIEN
    public function getid_chien() {
        return $this->id_chien;
    }
    public function setid_chien($id_chien): void {
        $this->id_chien = $id_chien;
    }

    // ID_PROMENEUR
    public function getid_promeneur() {
        return $this->id_promeneur;
    }
    public function setid_promeneur($id_promeneur): void {
        $this->id_promeneur = $id_promeneur;
    }


    // ID_PROPRIETAIRE
    public function getid_proprietaire() {
        return $this->id_proprietaire;
    }
    public function setid_prprietaire($id_proprietaire): void {
        $this->id_proprietaire = $id_proprietaire;
    }

    // ID_ANNONCE
    public function getid_annonce(){
        return $this -> id_annonce;
    }
    public function setid_annonce($id_annonce):void{
        $this->id_annonce = $id_annonce;
    }
    

}