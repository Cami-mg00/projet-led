<?php

require_once 'managers/AbstractManager.php';

class ArticleManager extends AbstractManager {

    // Méthode pour récupérer tous les articles
    public function findAll(): array {
        $stmt = $this->db->query("SELECT * FROM article");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Méthode pour récupérer un article par son ID
    public function findOne(int $id): ?Article {
        $stmt = $this->db->prepare("SELECT * FROM article WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $articleData = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($articleData) {
            return new Article(
                $articleData['id'],
                $articleData['title'],
                $articleData['content'],
                $articleData['date'],
                $articleData['user_id'],
                $articleData['article_categories']
            );
        }
        return null;
    }

    // Méthode pour créer un nouvel article
    public function createArticle(string $title, string $content, string $date, int $user_id, string $article_categories): void {
        $stmt = $this->db->prepare("INSERT INTO article (title, content, date, user_id, article_categories) VALUES (:title, :content, :date, :user_id, :article_categories)");
        $stmt->execute([
            'title' => $title,
            'content' => $content,
            'date' => $date,
            'user_id' => $user_id,
            'article_categories' => $article_categories
        ]);
    }

    // Méthode pour mettre à jour un article
    public function updateArticle(Article $article): void {
        $stmt = $this->db->prepare("UPDATE article SET title = :title, content = :content, date = :date, article_categories = :article_categories WHERE id = :id");
        $stmt->execute([
            'id' => $article->getId(),
            'title' => $article->getTitle(),
            'content' => $article->getContent(),
            'date' => $article->getDate(),
            'article_categories' => $article->getArticleCategories()
        ]);
    }

    // Méthode pour supprimer un article
    public function deleteArticle(int $id): void {
        $stmt = $this->db->prepare("DELETE FROM article WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }
}

