<?php
/**
 * @file Promenade.class.php
 * @author DogSitter Team
 * @brief Classe modèle représentant une promenade
 * @version 1.0
 * @date 2025-02-17
 */

/**
 * @class Promenade
 * @brief Classe représentant une promenade de chien
 */
class Promenade
{
    /**
     * @var int Identifiant unique de la promenade
     */
    private ?int $id_promenade;

    /**
     * @var int|null Identifiant du chien
     */
    private ?int $id_chien;

    /**
     * @var int Identifiant du promeneur
     */
    private ?int $id_promeneur;

    /**
     * @var int Identifiant du propriétaire du chien
     */
    private ?int $id_proprietaire;

    /**
     * @var int|null Identifiant de l'annonce associée
     */
    private ?int $id_annonce;

    /**
     * @var DateTime|null Date et heure de la promenade
     */
    private ?\DateTime $date_promenade;

    /**
     * @var string Statut de la promenade (en_cours, terminee, archivee, annulee)
     */
    private string $statut;

    // ============================================
    // Propriétés publiques pour enrichissement
    // ============================================

    /**
     * @var object|null Objet Annonce associé à la promenade
     */
    public ?object $annonce = null;

    /**
     * @var object|null Objet Utilisateur du maître (propriétaire du chien)
     */
    public ?object $maitre = null;

    /**
     * @var object|null Objet Utilisateur du promeneur
     */
    public ?object $promeneur = null;

    /**
     * @var object|null Objet Chien associé à la promenade
     */
    public ?object $chien = null;

    /**
     * @var array Tableau de promenades (utilisé pour agrégation)
     */
    public array $promenades = [];

    /**
     * @brief Constructeur de la classe Promenade
     * 
     * @param int $id_promenade Identifiant unique de la promenade
     * @param int|null $id_chien Identifiant du chien
     * @param int $id_promeneur Identifiant du promeneur
     * @param int $id_proprietaire Identifiant du propriétaire
     * @param int|null $id_annonce Identifiant de l'annonce
     * @param DateTime|string|null $date_promenade Date et heure de la promenade
     * @param string $statut Statut de la promenade
     */
    public function __construct(
        ?int $id_promenade = null,
        ?int $id_chien = null,
        ?int $id_promeneur = null,
        ?int $id_proprietaire = null,
        ?int $id_annonce = null,
        $date_promenade = null,
        ?string $statut = 'en_attente'
    ) {
        $this->id_promenade = $id_promenade;
        $this->id_chien = $id_chien;
        $this->id_promeneur = $id_promeneur;
        $this->id_proprietaire = $id_proprietaire;
        $this->id_annonce = $id_annonce;
        
        // Gestion de la date en tant que DateTime ou string
        if ($date_promenade instanceof \DateTime) {
            $this->date_promenade = $date_promenade;
        } elseif (is_string($date_promenade) && !empty($date_promenade)) {
            try {
                $this->date_promenade = new \DateTime($date_promenade);
            } catch (Exception $e) {
                $this->date_promenade = null;
            }
        } else {
            $this->date_promenade = null;
        }
        
        $this->statut = $statut ?? 'en_attente';
    }

    // ============================================
    // Getters
    // ============================================

    /**
     * @brief Récupère l'identifiant de la promenade
     * @return int|null Identifiant de la promenade
     */
    public function getId(): ?int
    {
        return $this->id_promenade;
    }

    /**
     * @brief Récupère l'identifiant de la promenade (alias)
     * @return int|null Identifiant de la promenade
     */
    public function getId_promenade(): ?int
    {
        return $this->id_promenade;
    }

    /**
     * @brief Récupère l'identifiant du chien (alias)
     * @return int|null Identifiant du chien
     */
    public function getId_chien(): ?int
    {
        return $this->id_chien;
    }

    /**
     * @brief Récupère l'identifiant du promeneur (alias)
     * @return int|null Identifiant du promeneur
     */
    public function getId_promeneur(): ?int
    {
        return $this->id_promeneur;
    }

    /**
     * @brief Récupère l'identifiant du propriétaire (alias)
     * @return int|null Identifiant du propriétaire
     */
    public function getId_proprietaire(): ?int
    {
        return $this->id_proprietaire;
    }

    /**
     * @brief Récupère l'identifiant de l'annonce (alias)
     * @return int|null Identifiant de l'annonce
     */
    public function getId_annonce(): ?int
    {
        return $this->id_annonce;
    }

    /**
     * @brief Récupère la date et heure de la promenade (alias)
     * @return DateTime|null Date et heure de la promenade
     */
    public function getDate_promenade(): ?\DateTime
    {
        return $this->date_promenade;
    }

    /**
     * @brief Récupère le statut de la promenade
     * @return string Statut de la promenade
     */
    public function getStatut(): string
    {
        return $this->statut;
    }


    // ============================================
    // Setters
    // ============================================

    /**
     * @brief Définit l'identifiant de la promenade
     * @param int $id_promenade Identifiant de la promenade
     */
    public function setId_promenade(int $id_promenade): void
    {
        $this->id_promenade = $id_promenade;
    }

    /**
     * @brief Définit l'identifiant du chien
     * @param int|null $id_chien Identifiant du chien
     */
    public function setId_chien(?int $id_chien): void
    {
        $this->id_chien = $id_chien;
    }

    /**
     * @brief Définit l'identifiant du promeneur
     * @param int $id_promeneur Identifiant du promeneur
     */
    public function setId_promeneur(int $id_promeneur): void
    {
        $this->id_promeneur = $id_promeneur;
    }

    /**
     * @brief Définit l'identifiant du propriétaire
     * @param int $id_proprietaire Identifiant du propriétaire
     */
    public function setId_proprietaire(int $id_proprietaire): void
    {
        $this->id_proprietaire = $id_proprietaire;
    }

    /**
     * @brief Définit l'identifiant de l'annonce
     * @param int|null $id_annonce Identifiant de l'annonce
     */
    public function setId_annonce(?int $id_annonce): void
    {
        $this->id_annonce = $id_annonce;
    }

    /**
     * @brief Définit la date et heure de la promenade
     * @param DateTime|string|null $date_promenade Date et heure de la promenade
     */
    public function setDate_promenade($date_promenade): void
    {
        if ($date_promenade instanceof \DateTime) {
            $this->date_promenade = $date_promenade;
        } elseif (is_string($date_promenade) && !empty($date_promenade)) {
            try {
                $this->date_promenade = new \DateTime($date_promenade);
            } catch (Exception $e) {
                $this->date_promenade = null;
            }
        } else {
            $this->date_promenade = null;
        }
    }

    /**
     * @brief Définit le statut de la promenade
     * @param string $statut Statut de la promenade
     */
    public function setStatut(string $statut): void
    {
        $this->statut = $statut;
    }
}
?>
