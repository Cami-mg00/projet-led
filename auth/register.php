<?php
session_start();

// Vérification si l'utilisateur est déjà connecté
if (isset($_SESSION['user_id'])) {
    header("Location: index.php"); // Redirection si déjà connecté
    exit;
}

// Traitement du formulaire d'inscription
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    require_once '../managers/abstractManager.php';

    // Récupération des données du formulaire
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Vérification si l'email est déjà pris
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $existingUser = $stmt->fetch();

    if ($existingUser) {
        $error = "L'email est déjà utilisé.";
    } else {
        // Hachage du mot de passe avant l'enregistrement
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Insertion du nouvel utilisateur dans la base de données
        $stmt = $pdo->prepare("INSERT INTO users (email, password, role) VALUES (:email, :password, :role)");
        $stmt->execute([
            'email' => $email,
            'password' => $hashedPassword,
            'role' => $role
        ]);

        // Rediriger vers la page de connexion après l'inscription
        header("Location: login.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
</head>

<script>
    document.querySelector("form").addEventListener("submit", function(event) {
        let password = document.getElementById("password").value;
        let confirmPassword = document.getElementById("confirm_password").value;

        // Regex pour valider le mot de passe (8 caractères, 1 majuscule, 1 caractère spécial)
        let passwordRegex = /^(?=.*[A-Z])(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{8,}$/;

        if (!passwordRegex.test(password)) {
            alert("Le mot de passe doit contenir au moins 8 caractères, une majuscule et un caractère spécial.");
            event.preventDefault(); // Annule l'envoi du formulaire
            return;
        }

        if (password !== confirmPassword) {
            alert("Les mots de passe ne correspondent pas !");
            event.preventDefault(); // Annule l'envoi du formulaire
        }
    });
</script>

<body>
<h1>Inscription</h1>
<?php if (isset($error)) : ?>
    <p style="color: red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>
<form method="POST" action="register.php">
    <label for="email">Email :</label>
    <input type="email" name="email" required>
    <br>
    <label for="password">Mot de passe :</label>
    <input type="password" name="password" required>
    <br>
    <label for="confirm_password">Confirmer le mot de passe :</label>
    <input type="password" name="confirm_password" id="confirm_password" required>
    <br>
    <label for="role">Rôle :</label>
    <select name="role" required>
        <option value="user">Utilisateur</option>
        <option value="admin">Administrateur</option>
    </select>
    <br>
    <button type="submit">S'inscrire</button>
</form>
</body>
</html>
