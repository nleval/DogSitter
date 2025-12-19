<?php
/**
 * @file Bd.class.php
 * @author Léval Noah
 * @brief Classe de gestion de la base de données (singleton)
 * @version 1.0
 * @date 2025-12-18
 */
class Bd{
    /**
     * @brief ?Bd $instance Instance unique de la classe Bd (Singleton).
     */
    private static ?Bd $instance = null;

    /**
     * @brief ?PDO $pdo Instance de connexion à la base de données.
     */
    private ?PDO $pdo;

    /**
     * @brief Constructeur privé de la classe Bd.
     *
     * Initialise la connexion PDO à la base de données
     * et configure le mode d'erreur.
     */
    private function __construct() {
        $this->pdo = new PDO(
            'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8',
            DB_USER,
            DB_PASS
        );
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /**
     * @brief Retourne l'instance unique de la classe Bd.
     *
     * Implémente le pattern Singleton pour garantir
     * une seule connexion à la base de données.
     *
     * @return Bd Instance unique de Bd.
     */
    public static function getInstance(): Bd {
        if (self::$instance === null) {
            self::$instance = new Bd();
        }
        return self::$instance;
    }

    /**
     * @brief Retourne la connexion PDO à la base de données.
     *
     * @return PDO Connexion active à la base de données.
     */
    public function getConnexion(): PDO {
        return $this->pdo;
    }

}