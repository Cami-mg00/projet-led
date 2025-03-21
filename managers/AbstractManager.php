<?php
abstract class AbstractManager {
    protected PDO $db; // Déclaration explicite du type pour $db

    public function __construct() {
        $host = 'db'; // Adresse du serveur de la base de données
        $dbname = 'camillemounier_led'; // Nom de la base
        $username = 'camillemounier'; // Identifiant
        $password = 'root'; // Mot de passe

        try {
            $this->db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password); // Initialisation correcte de $db
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Erreur de connexion : " . $e->getMessage());
        }
    }
}
