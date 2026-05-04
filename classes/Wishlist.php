<?php
require_once __DIR__ . '/../config/database.php';

class Wishlist {
    private $conn;
    private $table = 'wishlist';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Add item to wishlist
    public function add($user_id, $product_id) {
        // Check if already exists
        if ($this->exists($user_id, $product_id)) {
            return false;
        }

        $query = "INSERT INTO " . $this->table . " (user_id, product_id) VALUES (:user_id, :product_id)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Remove item from wishlist
    public function remove($user_id, $product_id) {
        $query = "DELETE FROM " . $this->table . " WHERE user_id = :user_id AND product_id = :product_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Check if item exists in wishlist
    public function exists($user_id, $product_id) {
        $query = "SELECT COUNT(*) as count FROM " . $this->table . " 
                  WHERE user_id = :user_id AND product_id = :product_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    }

    // Get wishlist items
    public function getItems($user_id) {
        $query = "SELECT w.*, p.product_name, p.product_slug, p.price, p.sale_price, 
                  p.main_image, p.stock_quantity,
                  COALESCE(AVG(r.rating), 0) as rating,
                  COUNT(r.review_id) as review_count,
                  c.category_name, b.brand_name,
                  CASE 
                      WHEN p.sale_price > 0 AND p.sale_price < p.price 
                      THEN ROUND(((p.price - p.sale_price) / p.price) * 100) 
                      ELSE 0 
                  END as discount_percentage
                  FROM " . $this->table . " w
                  INNER JOIN products p ON w.product_id = p.product_id
                  LEFT JOIN categories c ON p.category_id = c.category_id
                  LEFT JOIN brands b ON p.brand_id = b.brand_id
                  LEFT JOIN product_reviews r ON p.product_id = r.product_id AND r.is_approved = 1
                  WHERE w.user_id = :user_id AND p.is_active = 1
                  GROUP BY w.wishlist_id, p.product_id
                  ORDER BY w.added_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get wishlist count
    public function getCount($user_id) {
        $query = "SELECT COUNT(*) as total FROM " . $this->table . " WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    // Clear wishlist
    public function clear($user_id) {
        $query = "DELETE FROM " . $this->table . " WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /** @return array<int, true> product_id => true for quick isset() checks */
    public function getProductIdSet($user_id) {
        if (!$user_id) {
            return [];
        }
        $query = "SELECT product_id FROM " . $this->table . " WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $set = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $set[(int) $row['product_id']] = true;
        }
        return $set;
    }
}
