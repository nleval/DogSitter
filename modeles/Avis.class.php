<?php

class Avis
{
    private ?int $id_avis;
    private ?string $note;
    private ?string $texte_commentaire;
    private ?int $id_utilisateur;
    private ?int $id_promenade;
    private ?int $id_utilisateur_note;

    public function __construct(
        ?int $id_avis = null, 
        $note = null, 
        $texte_commentaire = null, 
        ?int $id_utilisateur = null, 
        ?int $id_promenade = null, 
        ?int $id_utilisateur_note = null
    ){
        $this->id_avis = $id_avis;
        $this->note = $note;
        $this->texte_commentaire = $texte_commentaire;
        $this->id_utilisateur = $id_utilisateur;
        $this->id_promenade = $id_promenade;
        $this->id_utilisateur_note = $id_utilisateur_note;
    }

    //GETTEUR & SETTEUR
    // ID
    public function getId(): ?int {
        return $this->id_avis;
    }
    public function setId(?int $id_avis): void {
        $this->id_avis = $id_avis;
    }

    // Note
    public function getNote(): ?string {
        return $this->note;
    }
    public function setNote(?string $note): void {
        $this->note = $note;
    }

    // Texte commentaire
    public function getTexteCommentaire(): ?string {
        return $this->texte_commentaire;
    }
    public function setTexteCommentaire(?string $texte_commentaire): void {
        $this->texte_commentaire = $texte_commentaire;
    }

    // Id utilisateur
    public function getIdUtilisateur(): ?int {
        return $this->id_utilisateur;
    }
    public function setIdUtilisateur(?int $id_utilisateur): void {
        $this->id_utilisateur = $id_utilisateur;
    }

    // Id promenade
    public function getIdPromenade(): ?int {
        return $this->id_promenade;
    }
    public function setIdPromenade(?int $id_promenade): void {
        $this->id_promenade = $id_promenade;
    }

    // Id utilisateur note
    public function getIdUtilisateurNote(): ?int {
        return $this->id_utilisateur_note;
    }
    public function setIdUtilisateurNote(?int $id_utilisateur_note): void {
        $this->id_utilisateur_note = $id_utilisateur_note;
    }
}