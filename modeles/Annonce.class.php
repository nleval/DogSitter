<?php

class Annonce
{
    private ?string $titre;
    private ?string $id_annonce;
    private ?string $datePromenade;
    private ?string $horaire;
    private ?string $status;
    private ?string $tarif;
    private ?string $description;
    private ?string $endroitPromenade;
    private ?int $duree;
    private ?string $id_utilisateur;

    public function __construct(
        ?string $titre = null,
        ?string $id_annonce = null,
        ?string $datePromenade = null,
        ?string $horaire = null,
        ?string $status = null,
        ?string $tarif = null,
        ?string $description = null,
        ?string $endroitPromenade = null,
        ?int $duree = null,
        ?string $id_utilisateur = null
    ) {
        $this->titre = $titre;
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
    public function getTitre(): ?string {
        return $this->titre;
    }
    public function setTitre(?string $titre): void {
        $this->titre = $titre;
    }

    public function getIdAnnonce(): ?string {
        return $this->id_annonce;
    }
    public function setIdAnnonce(?string $id_annonce): void {
        $this->id_annonce = $id_annonce;
    }

    public function getDatePromenade(): ?string {
        return $this->datePromenade;
    }
    public function setDatePromenade(?string $datePromenade): void {
        $this->datePromenade = $datePromenade;
    }

    public function getHoraire(): ?string {
        return $this->horaire;
    }
    public function setHoraire(?string $horaire): void {
        $this->horaire = $horaire;
    }

    public function getStatus(): ?string {
        return $this->status;
    }
    public function setStatus(?string $status): void {
        $this->status = $status;
    }

    public function getTarif(): ?string {
        return $this->tarif;
    }
    public function setTarif(?string $tarif): void {
        $this->tarif = $tarif;
    }

    public function getDescription(): ?string {
        return $this->description;
    }
    public function setDescription(?string $description): void {
        $this->description = $description;
    }

    public function getEndroitPromenade(): ?string {
        return $this->endroitPromenade;
    }
    public function setEndroitPromenade(?string $endroitPromenade): void {
        $this->endroitPromenade = $endroitPromenade;
    }

    public function getDuree(): ?int {
        return $this->duree;
    }
    public function setDuree(?int $duree): void {
        $this->duree = $duree;
    }

    public function getIdUtilisateur(): ?string {
        return $this->id_utilisateur;
    }
    public function setIdUtilisateur(?string $id_utilisateur): void {
        $this->id_utilisateur = $id_utilisateur;
    }
}
?>
