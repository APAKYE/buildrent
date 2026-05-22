<?php
// app/models/OrderModel.php

require_once APP_ROOT . '/config/database.php';

class OrderModel {

    private PDO $db;

    public function __construct() {
        $this->db = getDB();
    }

    /** Generate unique order reference */
    private function generateReference(): string {
        return 'BR-' . date('Y') . '-' . str_pad((string)rand(1, 99999), 5, '0', STR_PAD_LEFT);
    }

    /**
     * Place a new order atomically (order + items in one transaction)
     * $cartItems: array of ['product_id','type','quantity','unit_price','rental_start','rental_end']
     */
    public function placeOrder(int $userId, array $cartItems, array $delivery): int {
        $this->db->beginTransaction();
        try {
            // Calculate total
            $total = 0;
            foreach ($cartItems as $item) {
                $total += $item['line_total'];
            }

            // Generate unique reference
            do {
                $ref = $this->generateReference();
                $check = $this->db->prepare("SELECT id FROM orders WHERE reference = ?");
                $check->execute([$ref]);
            } while ($check->fetch());

            // Insert order
            $stmt = $this->db->prepare(
                "INSERT INTO orders (user_id, reference, total_amount, delivery_name, delivery_address, notes)
                 VALUES (?, ?, ?, ?, ?, ?)"
            );
            $stmt->execute([
                $userId,
                $ref,
                $total,
                $delivery['name'],
                $delivery['address'],
                $delivery['notes'] ?? null,
            ]);
            $orderId = (int) $this->db->lastInsertId();

            // Insert order items
            $itemStmt = $this->db->prepare(
                "INSERT INTO order_items
                 (order_id, product_id, type, quantity, unit_price, rental_start, rental_end, rental_days, line_total)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
            );
            foreach ($cartItems as $item) {
                $rentalDays = null;
                if ($item['type'] === 'rent' && !empty($item['rental_start']) && !empty($item['rental_end'])) {
                    $start = new DateTime($item['rental_start']);
                    $end   = new DateTime($item['rental_end']);
                    $rentalDays = (int) $start->diff($end)->days;
                }
                $itemStmt->execute([
                    $orderId,
                    $item['product_id'],
                    $item['type'],
                    $item['quantity'],
                    $item['unit_price'],
                    $item['rental_start'] ?? null,
                    $item['rental_end']   ?? null,
                    $rentalDays,
                    $item['line_total'],
                ]);
            }

            $this->db->commit();
            return $orderId;

        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Order placement failed: " . $e->getMessage());
            throw $e;
        }
    }

    /** Get order by ID with items */
    public function findById(int $id): ?array {
        $stmt = $this->db->prepare(
            "SELECT o.*, u.name AS user_name, u.email AS user_email
             FROM orders o JOIN users u ON o.user_id = u.id
             WHERE o.id = ? LIMIT 1"
        );
        $stmt->execute([$id]);
        $order = $stmt->fetch();
        if (!$order) return null;
        $order['items'] = $this->getOrderItems($id);
        return $order;
    }

    /** Get order by reference */
    public function findByReference(string $ref): ?array {
        $stmt = $this->db->prepare("SELECT * FROM orders WHERE reference = ? LIMIT 1");
        $stmt->execute([$ref]);
        $order = $stmt->fetch();
        if (!$order) return null;
        $order['items'] = $this->getOrderItems($order['id']);
        return $order;
    }

    /** Get items for an order */
    public function getOrderItems(int $orderId): array {
        $stmt = $this->db->prepare(
            "SELECT oi.*, p.name AS product_name, p.image_url
             FROM order_items oi
             JOIN products p ON oi.product_id = p.id
             WHERE oi.order_id = ?"
        );
        $stmt->execute([$orderId]);
        return $stmt->fetchAll();
    }

    /** Get all orders for a user */
    public function getByUser(int $userId): array {
        $stmt = $this->db->prepare(
            "SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC"
        );
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    /** Admin: get all orders */
    public function getAll(string $status = ''): array {
        $sql = "SELECT o.*, u.name AS user_name FROM orders o JOIN users u ON o.user_id = u.id";
        $params = [];
        if ($status) {
            $sql .= " WHERE o.status = ?";
            $params[] = $status;
        }
        $sql .= " ORDER BY o.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /** Admin: update order status */
    public function updateStatus(int $id, string $status): bool {
        $allowed = ['pending','confirmed','fulfilled','cancelled','returned'];
        if (!in_array($status, $allowed)) return false;
        $stmt = $this->db->prepare("UPDATE orders SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }
}
