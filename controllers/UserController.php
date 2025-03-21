<?php
require_once __DIR__ . '/../managers/UserManager.php';

class UserController {

    // Affiche la liste des utilisateurs
    public function list() {
        $userManager = new UserManager();
        $users = $userManager->findAll();
        require __DIR__ . '/../templates/users/list.phtml';
    }

    // Affiche le formulaire de création d'utilisateur
    public function create() {
        require __DIR__ . '/../templates/users/create.phtml';
    }

    // Gère la création d'un nouvel utilisateur
    public function store() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            // Récupération et nettoyage des données
            $firstName = trim($_POST['first_name']);
            $lastName = trim($_POST['last_name']);
            $email = trim($_POST['email']);
            $password = $_POST['password'];
            $confirmPassword = $_POST['confirm_password'];
            $role = $_POST['role'] ?? 'user';

            // Vérifier que tous les champs sont remplis
            if (empty($firstName) || empty($lastName) || empty($email) || empty($password) || empty($confirmPassword) || empty($role)) {
                die("Erreur : Tous les champs doivent être remplis.");
            }

            // Vérifier la validité de l'email
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                die("Erreur : L'email n'est pas valide.");
            }

            // Vérifier que les mots de passe correspondent
            if ($password !== $confirmPassword) {
                die("Erreur : Les mots de passe ne correspondent pas.");
            }

            // Vérifier la force du mot de passe avec une regex
            if (!preg_match('/^(?=.*[A-Z])(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{8,}$/', $password)) {
                die("Erreur : Le mot de passe doit contenir au moins 8 caractères, une majuscule et un caractère spécial.");
            }

            // Hacher le mot de passe avant insertion
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            $userManager = new UserManager();

            // Vérifie si l'email existe déjà
            if ($userManager->emailExists($email)) {
                die("Erreur : Un compte avec cette adresse e-mail existe déjà.");
            }

            // Création de l'utilisateur
            $userManager = new UserManager();
            $userManager->createUser(
                $firstName,
                $lastName,
                $email,
                $role,
                $hashedPassword
            );

            // Redirection après la création du nouvel utilisateur
            header("Location: /index.php?controller=user&action=list");
            exit;
        }
    }



    // Affiche le formulaire de mise à jour d'un utilisateur
    public function update(): void
    {
        // Vérifie si un ID est passé dans l'URL pour l'édition
        if (!isset($_GET['id'])) {
            die("Erreur : aucun ID spécifié.");
        }

        // Utilise la méthode findOne() pour récupérer l'utilisateur avec l'ID
        $userManager = new UserManager();
        $user = $userManager->findOne((int)$_GET['id']);  // Utilisation de l'ID

        if (!$user) {
            die("Utilisateur non trouvé.");
        }

        // Si le formulaire a été soumis
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            // Validation des champs
            if (empty($_POST['first_name']) || empty($_POST['last_name']) || empty($_POST['email']) || empty($_POST['role'])) {
                die("Tous les champs doivent être remplis.");
            }

            // Mettre à jour l'utilisateur avec les nouvelles données
            $user->setFirstName($_POST['first_name']);
            $user->setLastName($_POST['last_name']);
            $user->setEmail($_POST['email']);
            $user->setRole($_POST['role']);

            // Si un mot de passe est fourni, on le met à jour
            if (!empty($_POST['password'])) {
                $user->setPassword($_POST['password']);
            }

            // Sauvegarder l'utilisateur modifié dans la base de données
            $userManager->updateUser($user);

            // Rediriger vers la liste des utilisateurs après la mise à jour
            header("Location: index.php?controller=user&action=list");
            exit;
        }

        // Passer les données de l'utilisateur à la vue
        $template = "./templates/users/update.phtml";
        $title = "Modification de l'utilisateur";

        // Charger la vue avec les données de l'utilisateur
        require "./templates/layout.phtml";
    }


    // Supprimer un utilisateur
    public function delete(): void
    {
        if (!isset($_GET['id'])) {
            die("Erreur : aucun ID spécifié.");
        }

        $userManager = new UserManager();
        $userManager->deleteUser((int)$_GET['id']);

        // Redirection après suppression
        header("Location: index.php?controller=user&action=list");
        exit;
    }

}
