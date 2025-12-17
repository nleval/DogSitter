<?php
declare(strict_types=1);

class Promenade
{
    private int $id_promenade;
    private string $statut;
    private int $id_promeneur;
    private int $id_chien;
    private int $id_proprietaire;
    private int $id_annonce;

    public function __construct(
        int $id_promenade,
        string $statut,
        int $id_promeneur,
        int $id_chien,
        int $id_proprietaire,
        int $id_annonce
    ) {
        $this->id_promenade   = $id_promenade;
        $this->statut         = $statut;
        $this->id_promeneur   = $id_promeneur;
        $this->id_chien       = $id_chien;
        $this->id_proprietaire= $id_proprietaire;
        $this->id_annonce     = $id_annonce;
    }

    // ID PROMENADE
    public function getid_promenade(): int
    {
        return $this->id_promenade;
    }

    public function setid_promenade(int $id_promenade): void
    {
        $this->id_promenade = $id_promenade;
    }

    // STATUT
    public function getStatut(): string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): void
    {
        $this->statut = $statut;
    }

    // ID PROMENEUR
    public function getid_promeneur(): int
    {
        return $this->id_promeneur;
    }

    public function setid_promeneur(int $id_promeneur): void
    {
        $this->id_promeneur = $id_promeneur;
    }

    // ID CHIEN
    public function getid_chien(): int
    {
        return $this->id_chien;
    }

    public function setid_chien(int $id_chien): void
    {
        $this->id_chien = $id_chien;
    }

    // ID PROPRIETAIRE
    public function getid_proprietaire(): int
    {
        return $this->id_proprietaire;
    }

    public function setid_proprietaire(int $id_proprietaire): void
    {
        $this->id_proprietaire = $id_proprietaire;
    }

    // ID ANNONCE
    public function getid_annonce(): int
    {
        return $this->id_annonce;
    }

    public function setid_annonce(int $id_annonce): void
    {
        $this->id_annonce = $id_annonce;
    }
}
