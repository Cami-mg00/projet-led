<?php

class Event {
    private int $id;
    private string $name;
    private string $date;
    private string $location;
    private string $category;
    private int $createdBy;

    public function __construct(int $id, string $name, string $date, string $location, string $category, int $createdBy)
    {
        $this->id = $id;
        $this->name = $name;
        $this->date = $date;
        $this->location = $location;
        $this->category = $category;
        $this->createdBy = $createdBy;
    }

    // Getters
    public function getId(): int {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getDate(): string {
        return $this->date;
    }

    public function getLocation(): string {
        return $this->location;
    }

    public function getCategory(): string {
        return $this->category;
    }

    public function getCreatedBy(): int {
        return $this->createdBy;
    }
}
