<?php

class User {
    private ?int $id;
    private string $first_name;
    private string $last_name;
    private string $email;
    private string $password;
    private string $role;

    private static array $validRoles = ['admin', 'user'];

    public function __construct(?int $id, string $email, string $first_name, string $last_name, string $password, string $role) {
        $this->id = $id;
        $this->setEmail($email);
        $this->setFirstName($first_name);
        $this->setLastName($last_name);
        $this->setPassword($password);
        $this->setRole($role);
    }

    // Getters
    public function getId(): ?int {
        return $this->id;
    }

    public function getFirstName(): string {
        return $this->first_name;
    }

    public function getLastName(): string {
        return $this->last_name;
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function getPassword(): string {
        return $this->password;
    }

    public function getRole(): string {
        return $this->role;
    }

    // Setters avec validation
    public function setId(int $id): void {
        $this->id = $id;
    }

    public function setFirstName(string $first_name): void {
        $this->first_name = trim($first_name);
    }

    public function setLastName(string $last_name): void {
        $this->last_name = trim($last_name);
    }

    public function setEmail(string $email): void {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException("L'email fourni n'est pas valide.");
        }
        $this->email = $email;
    }

    public function setPassword(string $password): void {
        // Si un mot de passe est fourni, on le hache
        if (!password_get_info($password)['algo']) {
            $this->password = password_hash($password, PASSWORD_BCRYPT);
        } else {
            $this->password = $password;
        }
    }

    public function verifyPassword(string $plainPassword): bool {
        return password_verify($plainPassword, $this->password);
    }

    public function setRole(string $role): void {
        $validRoles = ['admin', 'user'];
        if (!in_array($role, self::$validRoles)) {
            throw new InvalidArgumentException("Rôle invalide. Les rôles autorisés sont : " . implode(", ", self::$validRoles));
        }
        $this->role = $role;
    }
}
