<?php
/**
 * @file controller_annonce.class.php
 * @author Boisseu Robin
 * @brief Classe représentant une promenade.
 * @version 1.0
 * @date 2025-12-18
 */
class Promenade
{
    /**
     * @brief int $id_promenade Identifiant unique de la promenade.
     */
    private int $id_promenade;

    /**
     * @brief string $statut Statut de la promenade (en cours, terminée, annulée, etc.).
     */
    private string $statut;

    /**
     * @brief int $id_promeneur Identifiant du promeneur.
     */
    private int $id_promeneur;

    /**
     * @brief int $id_chien Identifiant du chien concerné par la promenade.
     */
    private int $id_chien;

    /**
     * @brief int $id_proprietaire Identifiant du propriétaire du chien.
     */
    private int $id_proprietaire;

    /**
     * @brief int $id_annonce Identifiant de l'annonce liée à la promenade.
     */
    private int $id_annonce;

    /**
     * @brief Constructeur de la classe Promenade.
     *
     * @param int $id_promenade Identifiant unique de la promenade.
     * @param string $statut Statut de la promenade.
     * @param int $id_promeneur Identifiant du promeneur.
     * @param int $id_chien Identifiant du chien.
     * @param int $id_proprietaire Identifiant du propriétaire.
     * @param int $id_annonce Identifiant de l'annonce associée.
     */
    public function __construct(
        int $id_promenade,
        string $statut,
        int $id_promeneur,
        int $id_chien,
        int $id_proprietaire,
        int $id_annonce
    ) {
        $this->id_promenade    = $id_promenade;
        $this->statut          = $statut;
        $this->id_promeneur    = $id_promeneur;
        $this->id_chien        = $id_chien;
        $this->id_proprietaire = $id_proprietaire;
        $this->id_annonce      = $id_annonce;
    }

    /**
     * @brief Récupère l'identifiant de la promenade.
     *
     * @return int Identifiant de la promenade.
     */
    public function getid_promenade(): int {
        return $this->id_promenade;
    }

    /**
     * @brief Définit l'identifiant de la promenade.
     *
     * @param int $id_promenade Identifiant de la promenade.
     */
    public function setid_promenade(int $id_promenade): void {
        $this->id_promenade = $id_promenade;
    }

    /**
     * @brief Récupère le statut de la promenade.
     *
     * @return string Statut de la promenade.
     */
    public function getStatut(): string {
        return $this->statut;
    }

    /**
     * @brief Définit le statut de la promenade.
     *
     * @param string $statut Statut de la promenade.
     */
    public function setStatut(string $statut): void {
        $this->statut = $statut;
    }

    /**
     * @brief Récupère l'identifiant du promeneur.
     *
     * @return int Identifiant du promeneur.
     */
    public function getid_promeneur(): int {
        return $this->id_promeneur;
    }

    /**
     * @brief Définit l'identifiant du promeneur.
     *
     * @param int $id_promeneur Identifiant du promeneur.
     */
    public function setid_promeneur(int $id_promeneur): void {
        $this->id_promeneur = $id_promeneur;
    }

    /**
     * @brief Récupère l'identifiant du chien.
     *
     * @return int Identifiant du chien.
     */
    public function getid_chien(): int {
        return $this->id_chien;
    }

    /**
     * @brief Définit l'identifiant du chien.
     *
     * @param int $id_chien Identifiant du chien.
     */
    public function setid_chien(int $id_chien): void {
        $this->id_chien = $id_chien;
    }

    /**
     * @brief Récupère l'identifiant du propriétaire.
     *
     * @return int Identifiant du propriétaire.
     */
    public function getid_proprietaire(): int {
        return $this->id_proprietaire;
    }

    /**
     * @brief Définit l'identifiant du propriétaire.
     *
     * @param int $id_proprietaire Identifiant du propriétaire.
     */
    public function setid_proprietaire(int $id_proprietaire): void {
        $this->id_proprietaire = $id_proprietaire;
    }

    /**
     * @brief Récupère l'identifiant de l'annonce.
     *
     * @return int Identifiant de l'annonce.
     */
    public function getid_annonce(): int {
        return $this->id_annonce;
    }

    /**
     * @brief Définit l'identifiant de l'annonce.
     *
     * @param int $id_annonce Identifiant de l'annonce.
     */
    public function setid_annonce(int $id_annonce): void {
        $this->id_annonce = $id_annonce;
    }
}
