<?php
/**
 * @file controller.class.php
 * @author Léval Noah
 * @brief Gère les opérations liées aux annonces
 * @version 1.0
 * @date 2025-12-18
 */
class Controller{
    /** 
     * @brief PDO $pdo Instance de la classe PDO pour la gestion de la base de données.
     */
    private PDO $pdo;

     /** 
      * @brief \Twig\Loader\FilesystemLoader $loader Chargeur de templates Twig.
      */
    private \Twig\Loader\FilesystemLoader $loader;

     /** 
      * @brief \Twig\Environment $twig Moteur de templates Twig.
      */
    private \Twig\Environment $twig;  

     /** 
      * @brief ?array $get Données GET reçues.
      */
    private ?array $get = null;

     /** 
      * @brief ?array $post Données POST reçues.
     */
    private ?array $post = null;

    /**
     * @brief Constructeur du contrôleur
     * @param \Twig\Environment $twig Moteur de templates Twig.
     * @param \Twig\Loader\FilesystemLoader $loader Chargeur de templates Twig.
     */
    public function __construct(\Twig\Environment $twig, \Twig\Loader\FilesystemLoader $loader) {
        $db = Bd::getInstance();
        $this->pdo = $db->getConnexion();

        $this->loader = $loader;    
        $this->twig = $twig;

        if (isset($_GET) && !empty($_GET)){
            $this->get = $_GET;
        }
        if (isset($_POST) && !empty($_POST)){
            $this->post = $_POST;
        }
    }

    /**
     * @brief Appelle une méthode du contrôleur de manière dynamique.
     * @param string $methode Nom de la methode à appeler.
     */
    public function call(string $methode): mixed {
        if (!method_exists($this, $methode)){
            throw new Exception("La méthode $methode n'existe pas dans le controller " . __CLASS__ ); 
        }
        return $this->$methode();
    }
    


    /**
     * @brief Get the value of pdo
     */ 
    public function getPdo(): ?PDO
    {
        return $this->pdo;
    }

    /**
     * @brief Set the value of pdo
     * @param PDO $pdo Instance de la classe PDO pour la gestion de la base de données.
     */ 
    public function setPdo(?PDO $pdo):void
    {
        $this->pdo = $pdo;


    }

    /**
     * @brief Get the value of loader
     */ 
    public function getLoader(): \Twig\Loader\FilesystemLoader
    {
        return $this->loader;
    }

    /**
     * @brief Set the value of loader
     * @param \Twig\Loader\FilesystemLoader $loader Chargeur de templates Twig.
     */ 
    public function setLoader(\Twig\Loader\FilesystemLoader $loader) :void
    {
        $this->loader = $loader;

    }

    

    /**
     * @brief Get the value of twig
     */ 
    public function getTwig(): \Twig\Environment
    {
        return $this->twig;
    }

    /**
     * @brief Set the value of twig
     * @param \Twig\Environment $twig Moteur de templates Twig.
     */ 
    public function setTwig(\Twig\Environment $twig): void
    {
        $this->twig = $twig;

    }

    

    /**
     * @brief Get the value of get
     */ 
    public function getGet(): ?array
    {
        return $this->get;
    }

    /**
     * @brief Set the value of get
     * @param ?array $get Données GET reçues.
     */ 
    public function setGet(?array $get): void
    {
        $this->get = $get;

    }

    /**
     * @brief Get the value of post
     */ 
    public function getPost(): ?array
    {
        return $this->post;
    }

    /**
     * @brief Set the value of post
     * @param ?array $post Données POST reçues.
     */ 
    public function setPost(?array $post): void
    {
        $this->post = $post;


    }
}