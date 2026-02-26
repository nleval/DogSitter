<?php
/**
 * @file Avis.class.php
 * @author Campistron Julian
 * @brief Classe représentant un avis
 * @version 1.0
 * @date 2025-12-18
 */
class Avis
{
    /**
     * @brief Auteur de l'avis (objet Utilisateur)
     * @var Utilisateur|null
     */
    public $auteur = null;
    /**
     * @brief Identifiant de l'avis
     * @var int|null
     */
    private ?int $id_avis;

    /**
     * @brief Note de l'avis
     * @var string|null
     */
    private ?string $note;

    /**
     * @brief Commentaire de l'avis
     * @var string|null
     */
    private ?string $texte_commentaire;

    /**
     * @brief Identifiant de l'utilisateur ayant posté l'avis
     * @var int|null
     */
    private ?int $id_utilisateur;

    /**
     * @brief Identifiant de l'annonce (promenade)
     * @var int|null
     */
    private ?int $id_annonce;

    /**
     * @brief Identifiant de l'utilisateur noté par l'avis
     * @var int|null
     */
    private ?int $id_utilisateur_note;

    /**
     * @constructor Constructeur de la classe Categorie
     * @details Ce constructeur permet de créer une nouvelle catégorie
     * @param int|null $id_avis
     * @param string|null $note
     * @param string|null $texte_commentaire
     * @param int|null $id_utilisateur
    * @param int|null $id_annonce
     * @param int|null $id_utilisateur_note
     * @return void
     */
    public function __construct(
        ?int $id_avis = null, 
        $note = null, 
        $texte_commentaire = null, 
        ?int $id_utilisateur = null, 
        ?int $id_annonce = null, 
        ?int $id_utilisateur_note = null
    ){
        $this->id_avis = $id_avis;
        $this->note = $note;
        $this->texte_commentaire = $texte_commentaire;
        $this->id_utilisateur = $id_utilisateur;
        $this->id_annonce = $id_annonce;
        $this->id_utilisateur_note = $id_utilisateur_note;
    }

    //ENCAPSULATION
    
    /**
     * @function getId
     * @details Cette fonction permet de récupérer l'identifiant de l'avis
     * @return int|null
     */
    public function getId(): ?int {
        return $this->id_avis;
    }

    /**
     * @function setId
     * @details Cette fonction permet de définir l'identifiant de l'avis
     * @param int|null $id_avis
     * @return void
     */
    public function setId(?int $id_avis): void {
        $this->id_avis = $id_avis;
    }

    /**
     * @function getNote
     * @details Cette fonction permet de récupérer la note de l'avis
     * @return string|null
     */
    public function getNote(): ?string {
        return $this->note;
    }

    /**
     * @function setNote
     * @details Cette fonction permet de définir la note de l'avis
     * @param string|null $note
     * @return void
     */
    public function setNote(?string $note): void {
        $this->note = $note;
    }

    /**
     * @function getTexteCommentaire
     * @details Cette fonction permet de récupérer le commentaire l'avis
     * @return string|null
     */
    public function getTexteCommentaire(): ?string {
        return $this->texte_commentaire;
    }

    /**
     * @function setTexteCommentaire
     * @details Cette fonction permet de définir le commentaire de l'avis
     * @param string|null $texte_commentaire
     * @return void
     */
    public function setTexteCommentaire(?string $texte_commentaire): void {
        $this->texte_commentaire = $texte_commentaire;
    }

    /**
     * @function getIdUtilisateur
     * @details Cette fonction permet de récupérer l'identifiant de l'utilisateur ayant posté l'avis
     * @return int|null
     */
    public function getIdUtilisateur(): ?int {
        return $this->id_utilisateur;
    }

    /**
     * @function setIdUtilisateur
     * @details Cette fonction permet de définir l'identifiant de l'utilisateur ayant posté l'avis
     * @param int|null $id_utilisateur
     * @return void
     */
    public function setIdUtilisateur(?int $id_utilisateur): void {
        $this->id_utilisateur = $id_utilisateur;
    }

    /**
    * @function getIdAnnonce
    * @details Cette fonction permet de récupérer l'identifiant de l'annonce concernée par l'avis
     * @return int|null
     */
    public function getIdAnnonce(): ?int {
        return $this->id_annonce;
    }

    /**
    * @function setIdAnnonce
    * @details Cette fonction permet de définir l'identifiant de l'annonce concernée par l'avis
    * @param int|null $id_annonce
     * @return void
     */
    public function setIdAnnonce(?int $id_annonce): void {
        $this->id_annonce = $id_annonce;
    }

    /**
     * @function getIdUtilisateurNote
     * @details Cette fonction permet de récupérer l'identifiant de l'utilisateur étant noté par l'avis
     * @return int|null
     */
    public function getIdUtilisateurNote(): ?int {
        return $this->id_utilisateur_note;
    }

    /**
     * @function setIdUtilisateurNote
     * @details Cette fonction permet de définir l'identifiant de l'utilisateur étant noté par l'avis
     * @param int|null $id_utilisateur_note
     * @return void
     */
    public function setIdUtilisateurNote(?int $id_utilisateur_note): void {
        $this->id_utilisateur_note = $id_utilisateur_note;
    }
}