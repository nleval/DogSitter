<?php

class Avis
{
    private $id_avis;
    private $note;
    private $texte_commentaire;
    private $id_utilisateur;
    private $id_promenade;
    private $id_utilisateur_note;

    public function __construct($id_avis, $note, $texte_commentaire, $id_utilisateur, $id_promenade, $id_utilisateur_note)
    {
        $this->id_avis = $id_avis;
        $this->note = $note;
        $this->texte_commentaire = $texte_commentaire;
        $this->id_utilisateur = $id_utilisateur;
        $this->id_promenade = $id_promenade;
        $this->id_utilisateur_note = $id_utilisateur_note;
    }

    //GETTEUR & SETTEUR
    // ID
    public function getId() {
        return $this->id_avis;
    }
    public function setId($id_avis): void {
        $this->id_avis = $id_avis;
    }

    // Note
    public function getNote() {
        return $this->note;
    }
    public function setNote($note): void {
        $this->note = $note;
    }

    // Texte commentaire
    public function getTexteCommentaire() {
        return $this->texte_commentaire;
    }
    public function setTexteCommentaire($texte_commentaire): void {
        $this->texte_commentaire = $texte_commentaire;
    }

    // Id utilisateur
    public function getIdUtilisateur() {
        return $this->id_utilisateur;
    }
    public function setIdUtilisateur($id_utilisateur): void {
        $this->id_utilisateur = $id_utilisateur;
    }

    // Id promenade
    public function getIdPromenade() {
        return $this->id_promenade;
    }
    public function setIdPromenade($id_promenade): void {
        $this->id_promenade = $id_promenade;
    }

    // Id utilisateur note
    public function getIdUtilisateurNote() {
        return $this->id_utilisateur_note;
    }
    public function setIdUtilisateurNote($id_utilisateur_note): void {
        $this->id_utilisateur_note = $id_utilisateur_note;
    }
}