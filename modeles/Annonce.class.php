<?php
/**
 * @file Annonce.class.php
 * @author Lalanne Victor
 * @brief Classe Annonce représentant une annonce de promenade de chien.
 * @version 1.0
 * @date 2025-12-18
 */
class Annonce
{
    /**
     * @brief ?string $id_annonce Identifiant unique de l'annonce.
     */
    private ?string $id_annonce;

    /**
     * @brief ?string $titre Titre de l'annonce.
     */
    private ?string $titre;

    /**
     * @brief ?string $datePromenade Date prévue pour la promenade.
     */
    private ?string $datePromenade;

    /**
     * @brief ?string $horaire Horaire de la promenade.
     */
    private ?string $horaire;

    /**
     * @brief ?string $status Statut de l'annonce (active, inactive, etc.).
     */
    private ?string $status;

    /**
     * @brief ?string $tarif Tarif appliqué pour la promenade.
     */
    private ?string $tarif;

    /**
     * @brief ?string $description Description détaillée de l'annonce.
     */
    private ?string $description;

    /**
     * @brief ?string $endroitPromenade Lieu où se déroule la promenade.
     */
    private ?string $endroitPromenade;

    /**
     * @brief ?int $duree Durée de la promenade en minutes.
     */
    private ?int $duree;

    /**
     * @brief ?string $id_utilisateur Identifiant de l'utilisateur ayant créé l'annonce.
     */
    private ?string $id_utilisateur;

    /**
     * @brief ?int $id_promeneur Identifiant du promeneur accepte.
     */
    private ?int $id_promeneur;

    /**
     * @brief ?string $statut_promenade Statut de la promenade associee.
     */
    private ?string $statut_promenade;

    /**
     * @brief ?string $telephone Téléphone de l'utilisateur ayant créé l'annonce.
     */
    private ?string $telephone;

    /**
     * @brief array $promenades Tableau des promenades associées à cette annonce.
     */
    public array $promenades = [];

    /**
     * @brief Constructeur de la classe Annonce.
     *
     * @param ?string $id_annonce Identifiant unique de l'annonce.
     * @param ?string $titre Titre de l'annonce.
     * @param ?string $datePromenade Date prévue pour la promenade.
     * @param ?string $horaire Horaire de la promenade.
     * @param ?string $status Statut de l'annonce (active, inactive, etc.).
     * @param ?string $tarif Tarif appliqué pour la promenade.
     * @param ?string $description Description détaillée de l'annonce.
     * @param ?string $endroitPromenade Lieu où se déroule la promenade.
     * @param ?int    $duree Durée de la promenade en minutes.
     * @param ?string $id_utilisateur Identifiant de l'utilisateur ayant créé l'annonce.
     * @param ?string $telephone Téléphone de l'utilisateur ayant créé l'annonce.
     */
    public function __construct(
        ?string $id_annonce = null,
        ?string $titre = null,
        ?string $datePromenade = null,
        ?string $horaire = null,
        ?string $status = null,
        ?string $tarif = null,
        ?string $description = null,
        ?string $endroitPromenade = null,
        ?int $duree = null,
        ?string $id_utilisateur = null,
        ?int $id_promeneur = null,
        ?string $statut_promenade = null,
        ?string $telephone = null
    ) {
        $this->id_annonce = $id_annonce;
        $this->titre = $titre;
        $this->datePromenade = $datePromenade;
        $this->horaire = $horaire;
        $this->status = $status;
        $this->tarif = $tarif;
        $this->description = $description;
        $this->endroitPromenade = $endroitPromenade;
        $this->duree = $duree;
        $this->id_utilisateur = $id_utilisateur;
        $this->id_promeneur = $id_promeneur;
        $this->statut_promenade = $statut_promenade;
        $this->telephone = $telephone;
    }

    // GETTERS & SETTERS
    /**
     * @brief Récupère le titre de l'annonce.
     *
     * @return ?string Titre de l'annonce.
     */
    public function getTitre(): ?string {
        return $this->titre;
    }

    /**
     * @brief Définit le titre de l'annonce.
     *
     * @param ?string $titre Titre de l'annonce.
     */
    public function setTitre(?string $titre): void {
        $this->titre = $titre;
    }

    /**
     * @brief Récupère l'identifiant de l'annonce.
     *
     * @return ?string Identifiant unique de l'annonce.
     */
    public function getIdAnnonce(): ?string {
        return $this->id_annonce;
    }

    /**
     * @brief Définit l'identifiant de l'annonce.
     *
     * @param ?string $id_annonce Identifiant unique de l'annonce.
     */
    public function setIdAnnonce(?string $id_annonce): void {
        $this->id_annonce = $id_annonce;
    }

    /**
     * @brief Récupère la date de la promenade.
     *
     * @return ?string Date prévue pour la promenade.
     */
    public function getDatePromenade(): ?string {
        return $this->datePromenade;
    }

    /**
     * @brief Définit la date de la promenade.
     *
     * @param ?string $datePromenade Date prévue pour la promenade.
     */
    public function setDatePromenade(?string $datePromenade): void {
        $this->datePromenade = $datePromenade;
    }

    /**
     * @brief Récupère l'horaire de la promenade.
     *
     * @return ?string Horaire de la promenade.
     */
    public function getHoraire(): ?string {
        return $this->horaire;
    }

    /**
     * @brief Définit l'horaire de la promenade.
     *
     * @param ?string $horaire Horaire de la promenade.
     */
    public function setHoraire(?string $horaire): void {
        $this->horaire = $horaire;
    }

    /**
     * @brief Récupère le statut de l'annonce.
     *
     * @return ?string Statut de l'annonce.
     */
    public function getStatus(): ?string {
        return $this->status;
    }

    /**
     * @brief Définit le statut de l'annonce.
     *
     * @param ?string $status Statut de l'annonce.
     */
    public function setStatus(?string $status): void {
        $this->status = $status;
    }

    /**
     * @brief Récupère le tarif de la promenade.
     *
     * @return ?string Tarif de la promenade.
     */
    public function getTarif(): ?string {
        return $this->tarif;
    }

    /**
     * @brief Définit le tarif de la promenade.
     *
     * @param ?string $tarif Tarif de la promenade.
     */
    public function setTarif(?string $tarif): void {
        $this->tarif = $tarif;
    }

    /**
     * @brief Récupère la description de l'annonce.
     *
     * @return ?string Description de l'annonce.
     */
    public function getDescription(): ?string {
        return $this->description;
    }

    /**
     * @brief Définit la description de l'annonce.
     *
     * @param ?string $description Description de l'annonce.
     */
    public function setDescription(?string $description): void {
        $this->description = $description;
    }

    /**
     * @brief Récupère le lieu de la promenade.
     *
     * @return ?string Lieu de la promenade.
     */
    public function getEndroitPromenade(): ?string {
        return $this->endroitPromenade;
    }

    /**
     * @brief Définit le lieu de la promenade.
     *
     * @param ?string $endroitPromenade Lieu de la promenade.
     */
    public function setEndroitPromenade(?string $endroitPromenade): void {
        $this->endroitPromenade = $endroitPromenade;
    }

    /**
     * @brief Récupère la durée de la promenade.
     *
     * @return ?int Durée de la promenade en minutes.
     */
    public function getDuree(): ?int {
        return $this->duree;
    }

    /**
     * @brief Définit la durée de la promenade.
     *
     * @param ?int $duree Durée de la promenade en minutes.
     */
    public function setDuree(?int $duree): void {
        $this->duree = $duree;
    }

    /**
     * @brief Récupère l'identifiant de l'utilisateur.
     *
     * @return ?string Identifiant de l'utilisateur.
     */
    public function getIdUtilisateur(): ?string {
        return $this->id_utilisateur;
    }

    /**
     * @brief Définit l'identifiant de l'utilisateur.
     *
     * @param ?string $id_utilisateur Identifiant de l'utilisateur.
     */
    public function setIdUtilisateur(?string $id_utilisateur): void {
        $this->id_utilisateur = $id_utilisateur;
    }

    /**
     * @brief Recupere l'identifiant du promeneur accepte.
     *
     * @return ?int Identifiant du promeneur.
     */
    public function getIdPromeneur(): ?int {
        return $this->id_promeneur;
    }

    /**
     * @brief Definit l'identifiant du promeneur accepte.
     *
     * @param ?int $id_promeneur Identifiant du promeneur.
     */
    public function setIdPromeneur(?int $id_promeneur): void {
        $this->id_promeneur = $id_promeneur;
    }

    /**
     * @brief Recupere le statut de la promenade.
     *
     * @return ?string Statut de la promenade.
     */
    public function getStatutPromenade(): ?string {
        return $this->statut_promenade;
    }

    /**
     * @brief Definit le statut de la promenade.
     *
     * @param ?string $statut_promenade Statut de la promenade.
     */
    public function setStatutPromenade(?string $statut_promenade): void {
        $this->statut_promenade = $statut_promenade;
    }

    /**
     * @brief Récupère le téléphone de l'utilisateur.
     *
     * @return ?string Téléphone de l'utilisateur.
     */
    public function getTelephone(): ?string {
        return $this->telephone;
    }

    /**
     * @brief Définit le téléphone de l'utilisateur.
     *
     * @param ?string $telephone Téléphone de l'utilisateur.
     */
    public function setTelephone(?string $telephone): void {
        $this->telephone = $telephone;
    }

}
?>
