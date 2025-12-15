<?php

class Utilisateur
{
    private ?int $id_utilisateur;
    private ?string $email;
    private ?bool $estMaitre;
    private ?bool $estPromeneur;
    private ?string $adresse;
    private ?string $motDePasse;
    private ?string $numTelephone;
    private ?string $pseudo;
    private ?string $photoProfil;

    public function __construct(?int $id_utilisateur = null, ?string $email = null, ?bool $estMaitre = null, ?bool $estPromeneur = null, ?string $adresse = null, ?string $motDePasse = null, ?string $numTelephone = null, ?string $pseudo = null, ?string $photoProfil = null)
    {
        $this->id_utilisateur = $id_utilisateur;
        $this->email = $email;
        $this->estMaitre = $estMaitre;
        $this->estPromeneur = $estPromeneur;
        $this->adresse = $adresse;
        $this->motDePasse = $motDePasse;
        $this->numTelephone = $numTelephone;
        $this->pseudo = $pseudo;
        $this->photoProfil = $photoProfil;
    }

    //GETTEUR & SETTEUR
    // ID
    public function getId() {
        return $this->id_utilisateur;
    }
    public function setId($id_utilisateur): void {
        $this->id_utilisateur = $id_utilisateur;
    }

    // Email
    public function getEmail() {
        return $this->email;
    }
    public function setEmail($email): void {
        $this->email = $email;
    }

    // estMaitre
    public function getEstMaitre() {
        return $this->estMaitre;
    }
    public function setEstMaitre($estMaitre): void {
        $this->estMaitre = $estMaitre;
    }

    // estPromeneur
    public function getEstPromeneur() {
        return $this->estPromeneur;
    }
    public function setEstPromeneur($estPromeneur): void {
        $this->estPromeneur = $estPromeneur;
    }

    // Adresse
    public function getAdresse() {
        return $this->adresse;
    }
    public function setAdresse($adresse): void {
        $this->adresse = $adresse;
    }

    // Mot de passe
    public function getMotDePasse() {
        return $this->motDePasse;
    }
    public function setMotDePasse($motDePasse): void {
        $this->motDePasse = $motDePasse;
    }

    // Téléphone
    public function getNumTelephone() {
        return $this->numTelephone;
    }
    public function setNumTelephone($numTelephone): void {
        $this->numTelephone = $numTelephone;
    }

    // Pseudo
    public function getPseudo() {
        return $this->pseudo;
    }
    public function setPseudo($pseudo): void {
        $this->pseudo = $pseudo;
    }

    // PhotoProfil
    public function getPhotoProfil() {
        return $this->photoProfil;
    }
    public function setPhotoProfil($photoProfil): void {
        $this->photoProfil = $photoProfil;
    }
}