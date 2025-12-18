<?php
/**
 * @file Chien.class.php
 * @author Thiyes Lilian
 * @brief Classe représentant un chien
 * @version 1.0
 * @date 2025-12-18
 */
class Chien{
    /**
     * @brief ?int $id_chien Identifiant unique du chien.
     */
    private ?int $id_chien;

    /**
     * @brief ?string $nom_chien Nom du chien.
     */
    private ?string $nom_chien;

    /**
     * @brief ?string $poids Poids du chien.
     */
    private ?string $poids;

    /**
     * @brief ?string $taille Taille du chien.
     */
    private ?string $taille;

    /**
     * @brief ?string $race Race du chien.
     */
    private ?string $race;

    /**
     * @brief ?string $cheminPhoto Chemin vers la photo du chien.
     */
    private ?string $cheminPhoto;

    /**
     * @brief ?int $id_utilisateur Identifiant de l'utilisateur propriétaire du chien.
     */
    private ?int $id_utilisateur;

    /**
     * @brief Constructeur de la classe Chien.
     *
     * @param ?int    $id_chien Identifiant unique du chien.
     * @param ?string $nom_chien Nom du chien.
     * @param ?string $poids Poids du chien.
     * @param ?string $taille Taille du chien.
     * @param ?string $race Race du chien.
     * @param ?string $cheminPhoto Chemin vers la photo du chien.
     * @param ?int    $id_utilisateur Identifiant de l'utilisateur propriétaire.
     */
    public function __construct(
        ?int $id_chien = null,
        ?string $nom_chien = null,
        ?string $poids = null,
        ?string $taille = null,
        ?string $race = null,
        ?string $cheminPhoto = null,
        ?int $id_utilisateur = null
    ) {
        $this->id_chien = $id_chien;
        $this->nom_chien = $nom_chien;
        $this->poids = $poids;
        $this->taille = $taille;
        $this->race = $race;
        $this->cheminPhoto = $cheminPhoto;
        $this->id_utilisateur = $id_utilisateur;
    }

    /**
     * @brief Récupère l'identifiant du chien.
     *
     * @return ?int Identifiant du chien.
     */
    public function getId_Chien() {
        return $this->id_chien;
    }

    /**
     * @brief Définit l'identifiant du chien.
     *
     * @param int $id_chien Identifiant du chien.
     */
    public function setId_Chien(int $id_chien): void {
        $this->id_chien = $id_chien;
    }

    /**
     * @brief Récupère le nom du chien.
     *
     * @return ?string Nom du chien.
     */
    public function getNom_Chien() {
        return $this->nom_chien;
    }

    /**
     * @brief Définit le nom du chien.
     *
     * @param string $nom_chien Nom du chien.
     */
    public function setNom_Chien(string $nom_chien): void {
        $this->nom_chien = $nom_chien;
    }

    /**
     * @brief Récupère le poids du chien.
     *
     * @return ?string Poids du chien.
     */
    public function getPoids() {
        return $this->poids;
    }

    /**
     * @brief Définit le poids du chien.
     *
     * @param string $poids Poids du chien.
     */
    public function setPoids(string $poids): void {
        $this->poids = $poids;
    }

    /**
     * @brief Récupère la taille du chien.
     *
     * @return ?string Taille du chien.
     */
    public function getTaille() {
        return $this->taille;
    }

    /**
     * @brief Définit la taille du chien.
     *
     * @param string $taille Taille du chien.
     */
    public function setTaille(string $taille): void {
        $this->taille = $taille;
    }

    /**
     * @brief Récupère la race du chien.
     *
     * @return ?string Race du chien.
     */
    public function getRace() {
        return $this->race;
    }

    /**
     * @brief Définit la race du chien.
     *
     * @param string $race Race du chien.
     */
    public function setRace(string $race): void {
        $this->race = $race;
    }

    /**
     * @brief Récupère le chemin de la photo du chien.
     *
     * @return ?string Chemin de la photo.
     */
    public function getCheminPhoto() {
        return $this->cheminPhoto;
    }

    /**
     * @brief Définit le chemin de la photo du chien.
     *
     * @param string $cheminPhoto Chemin de la photo.
     */
    public function setCheminPhoto(string $cheminPhoto): void {
        $this->cheminPhoto = $cheminPhoto;
    }

    /**
     * @brief Récupère l'identifiant de l'utilisateur propriétaire.
     *
     * @return ?int Identifiant de l'utilisateur.
     */
    public function getid_Utilisateur() {
        return $this->id_utilisateur;
    }

    /**
     * @brief Définit l'identifiant de l'utilisateur propriétaire.
     *
     * @param int $id_utilisateur Identifiant de l'utilisateur.
     */
    public function setid_Utilisateur(int $id_utilisateur): void {
        $this->id_utilisateur = $id_utilisateur;
    }
}

?>