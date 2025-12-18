<?php
/**
 * @file controller_annonce.class.php
 * @author Léval Noah
 * @brief Classe représentant un utilisateur
 * @version 1.0
 * @date 2025-12-18
 */
class Utilisateur
{
    /**
     * @brief ?int $id_utilisateur Identifiant unique de l'utilisateur.
     */
    private ?int $id_utilisateur;

    /**
     * @brief ?string $email Adresse email de l'utilisateur.
     */
    private ?string $email;

    /**
     * @brief ?bool $estMaitre Indique si l'utilisateur est maître (propriétaire de chien).
     */
    private ?bool $estMaitre;

    /**
     * @brief ?bool $estPromeneur Indique si l'utilisateur est promeneur.
     */
    private ?bool $estPromeneur;

    /**
     * @brief ?string $adresse Adresse postale de l'utilisateur.
     */
    private ?string $adresse;

    /**
     * @brief ?string $motDePasse Mot de passe de l'utilisateur.
     */
    private ?string $motDePasse;

    /**
     * @brief ?string $numTelephone Numéro de téléphone de l'utilisateur.
     */
    private ?string $numTelephone;

    /**
     * @brief ?string $pseudo Pseudonyme de l'utilisateur.
     */
    private ?string $pseudo;

    /**
     * @brief ?string $photoProfil Chemin vers la photo de profil de l'utilisateur.
     */
    private ?string $photoProfil;

    /**
     * @brief ?int $tentativesEchouees Nombre de tentatives de connexion échouées.
     */
    private ?int $tentativesEchouees = 0;

    /**
     * @brief ?string $dateDernierEchecConnexion Date du dernier échec de connexion.
     */
    private ?string $dateDernierEchecConnexion = null;

    /**
     * @brief ?string $statutCompte Statut du compte utilisateur (actif, bloqué, etc.).
     */
    private ?string $statutCompte = 'actif';

    /**
     * @brief Constructeur de la classe Utilisateur.
     *
     * @param ?int    $id_utilisateur Identifiant de l'utilisateur.
     * @param ?string $email Adresse email.
     * @param ?bool   $estMaitre Indique si l'utilisateur est maître.
     * @param ?bool   $estPromeneur Indique si l'utilisateur est promeneur.
     * @param ?string $adresse Adresse postale.
     * @param ?string $motDePasse Mot de passe.
     * @param ?string $numTelephone Numéro de téléphone.
     * @param ?string $pseudo Pseudonyme.
     * @param ?string $photoProfil Photo de profil.
     */
    public function __construct(
        ?int $id_utilisateur = null,
        ?string $email = null,
        ?bool $estMaitre = null,
        ?bool $estPromeneur = null,
        ?string $adresse = null,
        ?string $motDePasse = null,
        ?string $numTelephone = null,
        ?string $pseudo = null,
        ?string $photoProfil = null
    ) {
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
    /**
     * @brief Récupère l'identifiant de l'utilisateur.
     *
     * @return ?int Identifiant de l'utilisateur.
     */
    public function getId() {
        return $this->id_utilisateur;
    }

    /**
     * @brief Définit l'identifiant de l'utilisateur.
     *
     * @param ?int $id_utilisateur Identifiant de l'utilisateur.
     */
    public function setId($id_utilisateur): void {
        $this->id_utilisateur = $id_utilisateur;
    }

    /**
     * @brief Récupère l'email de l'utilisateur.
     *
     * @return ?string Adresse email.
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * @brief Définit l'email de l'utilisateur.
     *
     * @param ?string $email Adresse email.
     */
    public function setEmail($email): void {
        $this->email = $email;
    }

    /**
     * @brief Indique si l'utilisateur est maître.
     *
     * @return ?bool Vrai si maître.
     */
    public function getEstMaitre() {
        return $this->estMaitre;
    }

    /**
     * @brief Définit le statut maître de l'utilisateur.
     *
     * @param ?bool $estMaitre Statut maître.
     */
    public function setEstMaitre($estMaitre): void {
        $this->estMaitre = $estMaitre;
    }

    /**
     * @brief Indique si l'utilisateur est promeneur.
     *
     * @return ?bool Vrai si promeneur.
     */
    public function getEstPromeneur() {
        return $this->estPromeneur;
    }

    /**
     * @brief Définit le statut promeneur de l'utilisateur.
     *
     * @param ?bool $estPromeneur Statut promeneur.
     */
    public function setEstPromeneur($estPromeneur): void {
        $this->estPromeneur = $estPromeneur;
    }

    /**
     * @brief Récupère l'adresse de l'utilisateur.
     *
     * @return ?string Adresse.
     */
    public function getAdresse() {
        return $this->adresse;
    }

    /**
     * @brief Définit l'adresse de l'utilisateur.
     *
     * @param ?string $adresse Adresse.
     */
    public function setAdresse($adresse): void {
        $this->adresse = $adresse;
    }

    /**
     * @brief Récupère le mot de passe de l'utilisateur.
     *
     * @return ?string Mot de passe.
     */
    public function getMotDePasse() {
        return $this->motDePasse;
    }

    /**
     * @brief Définit le mot de passe de l'utilisateur.
     *
     * @param ?string $motDePasse Mot de passe.
     */
    public function setMotDePasse($motDePasse): void {
        $this->motDePasse = $motDePasse;
    }

    /**
     * @brief Récupère le numéro de téléphone.
     *
     * @return ?string Numéro de téléphone.
     */
    public function getNumTelephone() {
        return $this->numTelephone;
    }

    /**
     * @brief Définit le numéro de téléphone.
     *
     * @param ?string $numTelephone Numéro de téléphone.
     */
    public function setNumTelephone($numTelephone): void {
        $this->numTelephone = $numTelephone;
    }

    /**
     * @brief Récupère le pseudo de l'utilisateur.
     *
     * @return ?string Pseudo.
     */
    public function getPseudo() {
        return $this->pseudo;
    }

    /**
     * @brief Définit le pseudo de l'utilisateur.
     *
     * @param ?string $pseudo Pseudo.
     */
    public function setPseudo($pseudo): void {
        $this->pseudo = $pseudo;
    }

    /**
     * @brief Récupère la photo de profil.
     *
     * @return ?string Chemin de la photo.
     */
    public function getPhotoProfil() {
        return $this->photoProfil;
    }

    /**
     * @brief Définit la photo de profil.
     *
     * @param ?string $photoProfil Photo de profil.
     */
    public function setPhotoProfil($photoProfil): void {
        $this->photoProfil = $photoProfil;
    }

    /**
     * @brief Récupère le nombre de tentatives de connexion échouées.
     *
     * @return int Nombre de tentatives échouées.
     */
    public function getTentativesEchouees(): int {
        return $this->tentativesEchouees ?? 0;
    }

    /**
     * @brief Définit le nombre de tentatives de connexion échouées.
     *
     * @param int $tentatives Nombre de tentatives.
     */
    public function setTentativesEchouees(int $tentatives): void {
        $this->tentativesEchouees = $tentatives;
    }

    /**
     * @brief Récupère la date du dernier échec de connexion.
     *
     * @return ?string Date du dernier échec.
     */
    public function getDateDernierEchecConnexion(): ?string {
        return $this->dateDernierEchecConnexion;
    }

    /**
     * @brief Définit la date du dernier échec de connexion.
     *
     * @param ?string $date Date du dernier échec.
     */
    public function setDateDernierEchecConnexion(?string $date): void {
        $this->dateDernierEchecConnexion = $date;
    }

    /**
     * @brief Récupère le statut du compte utilisateur.
     *
     * @return string Statut du compte.
     */
    public function getStatutCompte(): string {
        return $this->statutCompte ?? 'actif';
    }

    /**
     * @brief Définit le statut du compte utilisateur.
     *
     * @param string $statut Statut du compte.
     */
    public function setStatutCompte(string $statut): void {
        $this->statutCompte = $statut;
    }
}   