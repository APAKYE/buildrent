<?php
// app/models/UserModel.php

require_once APP_ROOT . '/config/database.php';

class UserModel {

    private PDO $db;

    public function __construct() {
        $this->db = getDB();
    }

    /** Find a user by email */
    public function findByEmail(string $email): ?array {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    /** Find a user by ID */
    public function findById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    /** Create a new user account */
    public function create(string $name, string $email, string $password, string $phone = ''): int {
        $hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
        $stmt = $this->db->prepare(
            "INSERT INTO users (name, email, password_hash, phone) VALUES (?, ?, ?, ?)"
        );
        $stmt->execute([$name, $email, $hash, $phone]);
        return (int) $this->db->lastInsertId();
    }

    /** Verify password against stored hash */
    public function verifyPassword(string $password, string $hash): bool {
        return password_verify($password, $hash);
    }

    /** Update profile info */
    public function updateProfile(int $id, string $name, string $phone, string $address): bool {
        $stmt = $this->db->prepare(
            "UPDATE users SET name = ?, phone = ?, address = ? WHERE id = ?"
        );
        return $stmt->execute([$name, $phone, $address, $id]);
    }

    /** Get all users (admin use) */
    public function getAll(): array {
        return $this->db->query("SELECT id, name, email, phone, role, created_at FROM users ORDER BY created_at DESC")->fetchAll();
    }
}
