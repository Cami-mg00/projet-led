<?php

class Article {
    private ?int $id;
    private string $title;
    private string $content;
    private string $date;
    private int $user_id;
    private string $article_categories;

    // Constructeur
    public function __construct(?int $id, string $title, string $content, string $date, int $user_id, string $article_categories) {
        $this->id = $id;
        $this->title = $title;
        $this->content = $content;
        $this->date = $date;
        $this->user_id = $user_id;
        $this->article_categories = $article_categories;
    }

    // Getters
    public function getId(): ?int {
        return $this->id;
    }

    public function getTitle(): string {
        return $this->title;
    }

    public function getContent(): string {
        return $this->content;
    }

    public function getDate(): string {
        return $this->date;
    }

    public function getUserId(): int {
        return $this->user_id;
    }

    public function getArticleCategories(): string {
        return $this->article_categories;
    }

    // Setters
    public function setTitle(string $title): void {
        $this->title = $title;
    }

    public function setContent(string $content): void {
        $this->content = $content;
    }

    public function setDate(string $date): void {
        $this->date = $date;
    }

    public function setUserId(int $user_id): void {
        $this->user_id = $user_id;
    }

    public function setArticleCategories(string $article_categories): void {
        $this->article_categories = $article_categories;
    }
}

?>
