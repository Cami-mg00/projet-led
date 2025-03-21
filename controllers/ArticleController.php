<?php

require_once __DIR__ . '/../managers/ArticleManager.php';

class ArticleController {

    // Afficher la liste des articles
    public function list() {
        $articleManager = new ArticleManager();
        $articles = $articleManager->findAll();  // Récupérer tous les articles
        require __DIR__ . '/../templates/articles/list.phtml';
        // Afficher la liste dans le template
    }

    // Afficher le formulaire de création d'un article
    public function create() {
        require __DIR__ . '/../templates/articles/create.phtml';
        // Afficher le formulaire de création
    }

    // Enregistrer un nouvel article
    public function store() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            // Vérification des données du formulaire
            $title = $_POST['title'];
            $content = $_POST['content'];
            $date = $_POST['date'];
            $article_categories = $_POST['article_categories'];
            $user_id = $_SESSION['user_id'];  // Récupérer l'ID de l'utilisateur connecté

            // Créer un nouvel article
            $articleManager = new ArticleManager();
            $articleManager->createArticle($title, $content, $date, $user_id, $article_categories);

            // Rediriger vers la liste des articles après la création
            header("Location: /index.php?controller=article&action=list");
            exit;
        }
    }

    // Afficher le formulaire de modification d'un article
    public function update() {
        if (!isset($_GET['id'])) {
            die("Erreur : aucun ID d'article spécifié.");
        }

        $articleManager = new ArticleManager();
        $article = $articleManager->findOne((int)$_GET['id']);  // Récupérer l'article par son ID

        if (!$article) {
            die("Article non trouvé.");
        }

        // Si le formulaire a été soumis
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            // Récupérer les données du formulaire
            $article->setTitle($_POST['title']);
            $article->setContent($_POST['content']);
            $article->setDate($_POST['date']);
            $article->setArticleCategories($_POST['article_categories']);

            // Mettre à jour l'article
            $articleManager->updateArticle($article);

            // Rediriger vers la liste des articles après la mise à jour
            header("Location: /index.php?controller=article&action=list");
            exit;
        }

        // Afficher le formulaire de modification avec les données de l'article
        require __DIR__ . '/../templates/articles/update.phtml';
    }

    // Supprimer un article
    public function delete() {
        if (!isset($_GET['id'])) {
            die("Erreur : aucun ID d'article spécifié.");
        }

        $articleManager = new ArticleManager();
        $articleManager->deleteArticle((int)$_GET['id']);  // Supprimer l'article par son ID

        // Rediriger vers la liste des articles après la suppression
        header("Location: /index.php?controller=article&action=list");
        exit;
    }

}

