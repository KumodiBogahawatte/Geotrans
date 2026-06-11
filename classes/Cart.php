<?php
require_once __DIR__ . '/../config/database.php';

class Cart {
    private $conn;
    private $table = 'cart';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Get cart items
    public function getItems($user_id = null, $session_id = null) {
        $query = "SELECT c.*, p.product_name, p.product_slug, p.price, p.sale_price, 
                  p.main_image, p.stock_quantity, b.brand_name
                  FROM " . $this->table . " c
                  INNER JOIN products p ON c.product_id = p.product_id
                  LEFT JOIN brands b ON p.brand_id = b.brand_id
                  WHERE ";
        
        if ($user_id) {
            $query .= "c.user_id = :user_id";
        } else {
            $query .= "c.session_id = :session_id";
        }
        
        $stmt = $this->conn->prepare($query);
        
        if ($user_id) {
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        } else {
            $stmt->bindParam(':session_id', $session_id);
        }
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get cart count
    public function getCount($user_id = null, $session_id = null) {
        $query = "SELECT SUM(quantity) as total FROM " . $this->table . " WHERE ";
        
        if ($user_id) {
            $query .= "user_id = :user_id";
        } else {
            $query .= "session_id = :session_id";
        }
        
        $stmt = $this->conn->prepare($query);
        
        if ($user_id) {
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        } else {
            $stmt->bindParam(':session_id', $session_id);
        }
        
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'] ? $row['total'] : 0;
    }

    // Add item to cart
    public function addItem($product_id, $quantity = 1, $user_id = null, $session_id = null) {
        // Check if item already exists
        $check_query = "SELECT cart_id, quantity FROM " . $this->table . " 
                        WHERE product_id = :product_id AND ";
        
        if ($user_id) {
            $check_query .= "user_id = :user_id";
        } else {
            $check_query .= "session_id = :session_id";
        }
        
        $stmt = $this->conn->prepare($check_query);
        $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
        
        if ($user_id) {
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        } else {
            $stmt->bindParam(':session_id', $session_id);
        }
        
        $stmt->execute();
        $existing = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($existing) {
            // Update quantity
            $new_quantity = $existing['quantity'] + $quantity;
            $update_query = "UPDATE " . $this->table . " 
                            SET quantity = :quantity, updated_at = CURRENT_TIMESTAMP 
                            WHERE cart_id = :cart_id";
            $stmt = $this->conn->prepare($update_query);
            $stmt->bindParam(':quantity', $new_quantity, PDO::PARAM_INT);
            $stmt->bindParam(':cart_id', $existing['cart_id'], PDO::PARAM_INT);
            return $stmt->execute();
        } else {
            // Insert new item
            $insert_query = "INSERT INTO " . $this->table . " 
                            (product_id, quantity, user_id, session_id) 
                            VALUES (:product_id, :quantity, :user_id, :session_id)";
            $stmt = $this->conn->prepare($insert_query);
            $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
            $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':session_id', $session_id);
            return $stmt->execute();
        }
    }

    // Update cart item quantity
    public function updateQuantity($cart_id, $quantity) {
        $query = "UPDATE " . $this->table . " 
                  SET quantity = :quantity, updated_at = CURRENT_TIMESTAMP 
                  WHERE cart_id = :cart_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
        $stmt->bindParam(':cart_id', $cart_id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Remove item from cart
    public function removeItem($cart_id) {
        $query = "DELETE FROM " . $this->table . " WHERE cart_id = :cart_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':cart_id', $cart_id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Clear cart
    public function clearCart($user_id = null, $session_id = null) {
        $query = "DELETE FROM " . $this->table . " WHERE ";
        
        if ($user_id) {
            $query .= "user_id = :user_id";
        } else {
            $query .= "session_id = :session_id";
        }
        
        $stmt = $this->conn->prepare($query);
        
        if ($user_id) {
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        } else {
            $stmt->bindParam(':session_id', $session_id);
        }
        
        return $stmt->execute();
    }

    // Merge session cart to user cart (after login)
    public function mergeCart($user_id, $session_id) {
        $query = "UPDATE " . $this->table . " 
                  SET user_id = :user_id, session_id = NULL 
                  WHERE session_id = :session_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':session_id', $session_id);
        return $stmt->execute();
    }

    // Get cart total
    public function getCartTotal($user_id = null, $session_id = null) {
        $query = "SELECT SUM(
                    c.quantity * 
                    CASE WHEN p.sale_price IS NOT NULL AND p.sale_price > 0 
                    THEN p.sale_price ELSE p.price END
                  ) as total
                  FROM " . $this->table . " c
                  INNER JOIN products p ON c.product_id = p.product_id
                  WHERE ";
        
        if ($user_id) {
            $query .= "c.user_id = :user_id";
        } else {
            $query .= "c.session_id = :session_id";
        }
        
        $stmt = $this->conn->prepare($query);
        
        if ($user_id) {
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        } else {
            $stmt->bindParam(':session_id', $session_id);
        }
        
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'] ? $row['total'] : 0;
    }
}
?>
