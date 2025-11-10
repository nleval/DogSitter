<?php

class Promenade{
    private $id_promenade;
    private $statut;

    public function __construct($id_promenade,$statut) {
        $this-> id_promenade = $id_promenade;
        $this-> statut = $statut;
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
}