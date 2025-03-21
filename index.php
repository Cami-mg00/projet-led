<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit;
}

// À partir d'ici seulement, tu peux faire tes echo :
//echo "✅ Debug : Session démarrée<br>";
//echo "✅ Debug : Utilisateur connecté : " . $_SESSION['user_id'] . "<br>";

require_once 'config/autoload.php';
//echo "✅ Debug : autoload.php inclus<br>";

require_once 'config/router.php';
//echo "✅ Debug : router.php inclus<br>";

if (!class_exists('UserManager')) {
    die("❌ Erreur : La classe UserManager n'existe pas !");
}

try {
    $manager = new UserManager();
    echo "✅ Debug : Connexion UserManager réussie !<br>";
} catch (Exception $e) {
    die("❌ Erreur : " . $e->getMessage());
}
