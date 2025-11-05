<?php

class Annonce
{
    private $id_annonce;
    private $datePromenade;
    private $horaire;
    private $status;
    private $tarif;
    private $description;
    private $endroitPromenade;
    private $duree;
    private $id_utilisateur;

    public function __construct($id_annonce, $datePromenade, $horaire, $status, $tarif, $description, $endroitPromenade, $duree, $id_utilisateur)
    {
        $this->id_annonce = $id_annonce;
        $this->datePromenade = $datePromenade;
        $this->horaire = $horaire;
        $this->status = $status;
        $this->tarif = $tarif;
        $this->description = $description;
        $this->endroitPromenade = $endroitPromenade;
        $this->duree = $duree;
        $this->id_utilisateur = $id_utilisateur;
    }

    // GETTERS & SETTERS

    // ID Annonce
    public function getIdAnnonce() {
        return $this->id_annonce;
    }
    public function setIdAnnonce($id_annonce): void {
        $this->id_annonce = $id_annonce;
    }

    // Date promenade
    public function getDatePromenade() {
        return $this->datePromenade;
    }
    public function setDatePromenade($datePromenade): void {
        $this->datePromenade = $datePromenade;
    }

    // Horaire
    public function getHoraire() {
        return $this->horaire;
    }
    public function setHoraire($horaire): void {
        $this->horaire = $horaire;
    }

    // Status
    public function getStatus() {
        return $this->status;
    }
    public function setStatus($status): void {
        $this->status = $status;
    }

    // Tarif
    public function getTarif() {
        return $this->tarif;
    }
    public function setTarif($tarif): void {
        $this->tarif = $tarif;
    }

    // Description
    public function getDescription() {
        return $this->description;
    }
    public function setDescription($description): void {
        $this->description = $description;
    }

    // Endroit promenade
    public function getEndroitPromenade() {
        return $this->endroitPromenade;
    }
    public function setEndroitPromenade($endroitPromenade): void {
        $this->endroitPromenade = $endroitPromenade;
    }

    // Durée
    public function getDuree() {
        return $this->duree;
    }
    public function setDuree($duree): void {
        $this->duree = $duree;
    }

    // ID Utilisateur (clé étrangère)
    public function getIdUtilisateur() {
        return $this->id_utilisateur;
    }
    public function setIdUtilisateur($id_utilisateur): void {
        $this->id_utilisateur = $id_utilisateur;
    }
}
