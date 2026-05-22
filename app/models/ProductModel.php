<?php
// app/models/ProductModel.php

require_once APP_ROOT . '/config/database.php';

class ProductModel {

    private PDO $db;

    public function __construct() {
        $this->db = getDB();
    }

    /** Get all available products with category name */
    public function getAll(int $page = 1, int $limit = ITEMS_PER_PAGE): array {
        $offset = ($page - 1) * $limit;
        $stmt = $this->db->prepare(
            "SELECT p.*, c.name AS category_name
             FROM products p
             JOIN categories c ON p.category_id = c.id
             WHERE p.is_available = 1
             ORDER BY p.created_at DESC
             LIMIT ? OFFSET ?"
        );
        $stmt->execute([$limit, $offset]);
        return $stmt->fetchAll();
    }

    /** Count total available products (for pagination) */
    public function countAll(string $search = '', int $categoryId = 0): int {
        $sql = "SELECT COUNT(*) FROM products p WHERE p.is_available = 1";
        $params = [];
        if ($search) {
            $sql .= " AND (p.name LIKE ? OR p.description LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }
        if ($categoryId) {
            $sql .= " AND p.category_id = ?";
            $params[] = $categoryId;
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn();
    }

    /** Search & filter products */
    public function search(string $search = '', int $categoryId = 0, int $page = 1): array {
        $limit  = ITEMS_PER_PAGE;
        $offset = ($page - 1) * $limit;
        $sql    = "SELECT p.*, c.name AS category_name
                   FROM products p
                   JOIN categories c ON p.category_id = c.id
                   WHERE p.is_available = 1";
        $params = [];
        if ($search) {
            $sql .= " AND (p.name LIKE ? OR p.description LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }
        if ($categoryId) {
            $sql .= " AND p.category_id = ?";
            $params[] = $categoryId;
        }
        $sql .= " ORDER BY p.name ASC LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /** Get single product by ID */
    public function findById(int $id): ?array {
        $stmt = $this->db->prepare(
            "SELECT p.*, c.name AS category_name
             FROM products p
             JOIN categories c ON p.category_id = c.id
             WHERE p.id = ? LIMIT 1"
        );
        $stmt->execute([$id]);
        $product = $stmt->fetch();
        return $product ?: null;
    }

    /** Get all categories */
    public function getCategories(): array {
        return $this->db->query("SELECT * FROM categories ORDER BY name")->fetchAll();
    }

    /** Admin: get ALL products including unavailable */
    public function getAllAdmin(): array {
        return $this->db->query(
            "SELECT p.*, c.name AS category_name
             FROM products p
             JOIN categories c ON p.category_id = c.id
             ORDER BY p.created_at DESC"
        )->fetchAll();
    }

    /** Admin: create product */
    public function create(array $data): int {
        $stmt = $this->db->prepare(
            "INSERT INTO products (category_id, name, description, buy_price, rent_price_day, stock, image_url)
             VALUES (?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->execute([
            $data['category_id'],
            $data['name'],
            $data['description'],
            $data['buy_price']      ?: null,
            $data['rent_price_day'] ?: null,
            $data['stock'],
            $data['image_url'] ?? 'default.jpg',
        ]);
        return (int) $this->db->lastInsertId();
    }

    /** Admin: update product */
    public function update(int $id, array $data): bool {
        $stmt = $this->db->prepare(
            "UPDATE products
             SET category_id = ?, name = ?, description = ?,
                 buy_price = ?, rent_price_day = ?, stock = ?,
                 image_url = ?, is_available = ?
             WHERE id = ?"
        );
        return $stmt->execute([
            $data['category_id'],
            $data['name'],
            $data['description'],
            $data['buy_price']      ?: null,
            $data['rent_price_day'] ?: null,
            $data['stock'],
            $data['image_url'] ?? 'default.jpg',
            $data['is_available'] ?? 1,
            $id,
        ]);
    }

    /** Admin: soft-delete (mark unavailable) */
    public function delete(int $id): bool {
        $stmt = $this->db->prepare("UPDATE products SET is_available = 0 WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /** Admin: update prices only */
    public function updatePrices(int $id, ?float $buyPrice, ?float $rentPrice): bool {
        $stmt = $this->db->prepare(
            "UPDATE products SET buy_price = ?, rent_price_day = ? WHERE id = ?"
        );
        return $stmt->execute([$buyPrice, $rentPrice, $id]);
    }
}
