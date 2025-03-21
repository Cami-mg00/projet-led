<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: auth/login.php"); // Redirection si déjà connectée
    exit;
}

require_once '../managers/UserManager.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Récupération des données du formulaire
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Utilisation de UserManager pour récupérer l'utilisateur
    $userManager = new UserManager();
    $user = $userManager->findOne($email);

    if ($user && password_verify($password, $user->getPassword())) {
        // Si le mot de passe est correct
        $_SESSION['user_id'] = $user->getId();
        $_SESSION['role'] = $user->getRole();

        // Redirection propre
        header("Location: /index.php");
        exit;
    } else {
        $error = "Email ou mot de passe incorrect.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
</head>
<body>
<h1>Connexion</h1>
<?php if (isset($error)) : ?>
    <p style="color: red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>
<form method="POST" action="login.php">
    <label for="email">Email :</label>
    <input type="email" name="email" required>
    <br>
    <label for="password">Mot de passe :</label>
    <input type="password" name="password" required>
    <br>
    <button type="submit">Se connecter</button>
</form>
</body>
</html>
