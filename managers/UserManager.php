<?php
require_once __DIR__ . '/../managers/AbstractManager.php';
// Ajouter cette ligne au début de ton fichier UserManager.php
require_once __DIR__ . '/../models/User.php';

class UserManager extends AbstractManager
{

    // Récupérer tous les utilisateurs
    public function findAll(): array
    {
        $stmt = $this->db->query("SELECT * FROM users");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupérer un utilisateur par son ID
    public function findOne($param)
    {
        // Vérifie si le paramètre est un entier (ID) ou une chaîne de caractères (email)
        if (is_numeric($param)) {
            // Recherche par ID
            $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id");
            $stmt->execute(['id' => $param]);
        } else {
            // Recherche par email
            $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email");
            $stmt->execute(['email' => $param]);
        }

        $userData = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($userData) {
            // Retourne un objet User avec les données récupérées
            return new User(
                $userData['id'],
                $userData['email'],
                $userData['first_name'],
                $userData['last_name'],
                $userData['password'],
                $userData['role']
            );
        }

        return null;  // Aucun utilisateur trouvé
    }

    // Créer un nouvel utilisateur
    public function createUser($firstName, $lastName, $email, $role, $password)
    {
        $stmt = $this->db->prepare("
            INSERT INTO users (first_name, last_name, email, role, password) 
            VALUES (:first_name, :last_name, :email, :role, :password)
        ");

        $stmt->execute([
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $email,
            'role' => $role,
            'password' => $password,
        ]);

    }

    public function emailExists(string $email): bool
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        return $stmt->fetchColumn() > 0;
    }

    // Mettre à jour les informations d'un utilisateur
    public function updateUser($id, $firstName, $lastName, $email, $role, $password)
    {
        $query = "UPDATE users SET 
          first_name = :first_name, 
          last_name = :last_name, 
          email = :email, 
          password = :password, 
          role = :role 
          WHERE id = :id";

        $stmt = $this->db->prepare($query);

        // Bind des paramètres
        $params = [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $email,
            'password' => $password,
            'role' => $role,
            'id' => $id
        ];

        return $stmt->execute($params);
    }

// Supprimer un utilisateur
    public function deleteUser($id)
    {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}