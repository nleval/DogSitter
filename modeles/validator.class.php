<?php
/**
 * @file controller_annonce.class.php
 * @author Lalanne Victor
 * @brief Gere la validation des données de formulaire
 * @version 1.0
 * @date 2025-12-18
 */
class Validator
{
    /**
     * @brief array $regles Règles de validation pour chaque champ du formulaire.
     */
    private array $regles;

    /**
     * @brief array $messagesErreurs Messages d'erreur générés lors de la validation.
     */
    private array $messagesErreurs = [];

    /**
     * @brief Constructeur de la classe Validator 
     * @param array $regles : règles de validation
     */
    public function __construct(array $regles)
    {
        $this->regles = $regles;
    }

    /**
     * @brief Valider les données d'un formulaire avec les règles de validation
     * @param array $donnees : données du formulaire
     * @return bool : true si les données sont valides, false sinon
     */
    public function valider(array $donnees): bool
    {
        $this->messagesErreurs = [];
        $toutValide = true;

        foreach ($this->regles as $champ => $reglesChamp) {
            $valeur = $donnees[$champ] ?? null;

            if (!$this->validerChamp($champ, $valeur, $reglesChamp)) {
                $toutValide = false;
            }
        }

        return $toutValide;
    }

    /**
     * @brief Valider un champ d'un formulaire
     * @param string $champ : nom du champ
     * @param mixed $valeur : valeur du champ
     * @param array $regleChamp : règles de validation du champ
     * @return bool : true si le champ est valide, false sinon
     */
    private function validerChamp(string $champ, mixed $valeur, array $regles): bool
    {
        $ok = true;

        // Si obligatoire et vide → erreur directe
        if (!empty($regles['obligatoire']) && empty($valeur)) {
            $this->messagesErreurs[] = "Le champ $champ est obligatoire.";
            return false;
        }

        // Si non obligatoire et vide → rien à vérifier
        if (empty($valeur) && empty($regles['obligatoire'])) {
            return true;
        }

        // Vérification des autres règles
        foreach ($regles as $regle => $parametre) {

            switch ($regle) {

                case 'type':
                    if ($parametre === 'string' && !is_string($valeur)) {
                        $this->messagesErreurs[] = "Le champ $champ doit être une chaîne.";
                        $ok = false;
                    }
                    if ($parametre === 'integer' && !filter_var($valeur, FILTER_VALIDATE_INT)) {
                        $this->messagesErreurs[] = "Le champ $champ doit être un entier.";
                        $ok = false;
                    }
                    if ($parametre === 'numeric' && !is_numeric($valeur)) {
                        $this->messagesErreurs[] = "Le champ $champ doit être numérique.";
                        $ok = false;
                    }
                    break;

                case 'longueur_min':
                    if (strlen($valeur) < $parametre) {
                        $this->messagesErreurs[] = "Le champ $champ doit faire au moins $parametre caractères.";
                        $ok = false;
                    }
                    break;

                case 'longueur_max':
                    if (strlen($valeur) > $parametre) {
                        $this->messagesErreurs[] = "Le champ $champ ne doit pas dépasser $parametre caractères.";
                        $ok = false;
                    }
                    break;

                case 'longueur_exacte':
                    if (strlen($valeur) !== $parametre) {
                        $this->messagesErreurs[] = "Le champ $champ doit faire exactement $parametre caractères.";
                        $ok = false;
                    }
                    break;

                case 'format':
                    if (is_string($parametre) && !preg_match($parametre, $valeur)) {
                        $this->messagesErreurs[] = "Le format du champ $champ est invalide.";
                        $ok = false;
                    }
                    if ($parametre === FILTER_VALIDATE_EMAIL && !filter_var($valeur, FILTER_VALIDATE_EMAIL)) {
                        $this->messagesErreurs[] = "L'adresse email n'est pas valide.";
                        $ok = false;
                    }
                    if ($parametre === FILTER_VALIDATE_URL && !filter_var($valeur, FILTER_VALIDATE_URL)) {
                        $this->messagesErreurs[] = "L'URL n'est pas valide.";
                        $ok = false;
                    }
                    break;

                case 'plage_min':
                    if ($valeur < $parametre) {
                        $this->messagesErreurs[] = "La valeur de $champ doit être au minimum $parametre.";
                        $ok = false;
                    }
                    break;

                case 'plage_max':
                    if ($valeur > $parametre) {
                        $this->messagesErreurs[] = "La valeur de $champ ne doit pas dépasser $parametre.";
                        $ok = false;
                    }
                    break;
            }
        }

        return $ok;
    }

    /** 
     * @brief Obtenir les messages d'erreurs
     * @return array
     */
    public function getMessagesErreurs(): array
    {
        return $this->messagesErreurs;
    }

    public function validerConnexion($donnees)
    {
        $erreurs = [];

        // Validation de l'email
        if (empty($donnees['email'])) {
            $erreurs['email'] = "'email est requis.";
        } elseif (!filter_var($donnees['email'], FILTER_VALIDATE_EMAIL)) {
            $erreurs['email'] = "'email n'est pas valide.";
        }
    
        // Validation du mot de passe
        if (empty($donnees['motDePasse'])) {
            $erreurs['motDePasse'] = "Le mot de passe est requis.";
        }
    
        return $erreurs;
    }
}