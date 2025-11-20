<?php
require_once __DIR__ . '/../config/database.php';

class Order {
    private $conn;
    private $table = 'orders';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Create new order
    public function create($data) {
        try {
            $this->conn->beginTransaction();
            
            // Generate order number
            $order_number = 'GEO-' . date('Ymd') . '-' . strtoupper(substr(md5(uniqid()), 0, 6));
            
            // Insert order
            $query = "INSERT INTO " . $this->table . " 
                      (order_number, user_id, guest_email, subtotal, discount_amount, 
                       shipping_cost, tax_amount, total_amount, payment_method, 
                       payment_status, order_status, shipping_address_id, billing_address_id, ip_address) 
                      VALUES (:order_number, :user_id, :guest_email, :subtotal, :discount, 
                       :shipping, :tax, :total, :payment_method, 
                       :payment_status, :order_status, :shipping_address, :billing_address, :ip)";
            
            $stmt = $this->conn->prepare($query);
            
            $stmt->bindParam(':order_number', $order_number);
            $stmt->bindParam(':user_id', $data['user_id'], PDO::PARAM_INT);
            $stmt->bindParam(':guest_email', $data['guest_email']);
            $stmt->bindParam(':subtotal', $data['subtotal']);
            $stmt->bindParam(':discount', $data['discount_amount']);
            $stmt->bindParam(':shipping', $data['shipping_cost']);
            $stmt->bindParam(':tax', $data['tax_amount']);
            $stmt->bindParam(':total', $data['total_amount']);
            $stmt->bindParam(':payment_method', $data['payment_method']);
            $stmt->bindParam(':payment_status', $data['payment_status']);
            $stmt->bindParam(':order_status', $data['order_status']);
            $stmt->bindParam(':shipping_address', $data['shipping_address_id']);
            $stmt->bindParam(':billing_address', $data['billing_address_id']);
            $stmt->bindParam(':ip', $_SERVER['REMOTE_ADDR']);
            
            $stmt->execute();
            $order_id = $this->conn->lastInsertId();
            
            // Insert order items
            if (!empty($data['items'])) {
                $item_query = "INSERT INTO order_items 
                               (order_id, product_id, product_name, product_sku, quantity, unit_price, subtotal) 
                               VALUES (:order_id, :product_id, :product_name, :sku, :quantity, :unit_price, :subtotal)";
                $item_stmt = $this->conn->prepare($item_query);
                
                foreach ($data['items'] as $item) {
                    $item_stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
                    $item_stmt->bindParam(':product_id', $item['product_id'], PDO::PARAM_INT);
                    $item_stmt->bindParam(':product_name', $item['product_name']);
                    $item_stmt->bindParam(':sku', $item['sku']);
                    $item_stmt->bindParam(':quantity', $item['quantity'], PDO::PARAM_INT);
                    $item_stmt->bindParam(':unit_price', $item['unit_price']);
                    $item_stmt->bindParam(':subtotal', $item['subtotal']);
                    $item_stmt->execute();
                    
                    // Update product stock
                    $update_stock = "UPDATE products SET stock_quantity = stock_quantity - :quantity 
                                    WHERE product_id = :product_id";
                    $stock_stmt = $this->conn->prepare($update_stock);
                    $stock_stmt->bindParam(':quantity', $item['quantity'], PDO::PARAM_INT);
                    $stock_stmt->bindParam(':product_id', $item['product_id'], PDO::PARAM_INT);
                    $stock_stmt->execute();
                }
            }
            
            // Insert order status history
            $history_query = "INSERT INTO order_status_history 
                             (order_id, new_status, comment) 
                             VALUES (:order_id, :status, :comment)";
            $history_stmt = $this->conn->prepare($history_query);
            $history_stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
            $history_stmt->bindParam(':status', $data['order_status']);
            $comment = 'Order placed';
            $history_stmt->bindParam(':comment', $comment);
            $history_stmt->execute();
            
            $this->conn->commit();
            return $order_id;
            
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    // Get order by ID or order number
    public function getById($id) {
        $query = "SELECT o.*, 
                  sa.full_name as shipping_name, sa.address_line1 as shipping_address1, 
                  sa.address_line2 as shipping_address2, sa.city as shipping_city, 
                  sa.state as shipping_state, sa.postal_code as shipping_postal, 
                  sa.phone as shipping_phone
                  FROM " . $this->table . " o
                  LEFT JOIN user_addresses sa ON o.shipping_address_id = sa.address_id
                  WHERE o.order_id = :id OR o.order_number = :order_number";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':order_number', $id);
        $stmt->execute();
        
        $order = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Fetch order items
        if ($order) {
            $order['items'] = $this->getOrderItems($order['order_id']);
        }
        
        return $order;
    }

    // Get order by ID with full details (for admin)
    public function getOrderById($order_id) {
        $query = "SELECT o.*, 
                  u.first_name, u.last_name, u.email, u.phone,
                  sa.full_name as shipping_name, 
                  sa.address_line1 as shipping_address_line1, 
                  sa.address_line2 as shipping_address_line2, 
                  sa.city as shipping_city, 
                  sa.state as shipping_state, 
                  sa.postal_code as shipping_postal_code,
                  sa.country as shipping_country,
                  sa.phone as shipping_phone
                  FROM " . $this->table . " o
                  LEFT JOIN users u ON o.user_id = u.user_id
                  LEFT JOIN user_addresses sa ON o.shipping_address_id = sa.address_id
                  WHERE o.order_id = :order_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Get order items
    public function getOrderItems($order_id) {
        $query = "SELECT oi.*, p.main_image 
                  FROM order_items oi
                  LEFT JOIN products p ON oi.product_id = p.product_id
                  WHERE oi.order_id = :order_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get user orders
    public function getUserOrders($user_id, $page = 1, $per_page = 10) {
        $offset = ($page - 1) * $per_page;
        
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE user_id = :user_id 
                  ORDER BY created_at DESC 
                  LIMIT :limit OFFSET :offset";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $per_page, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get all orders by user ID (alias for getUserOrders without pagination)
    public function getByUserId($user_id) {
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE user_id = :user_id 
                  ORDER BY created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Update order status
    public function updateStatus($order_id, $new_status, $comment = '', $created_by = null) {
        try {
            $this->conn->beginTransaction();
            
            // Get old status
            $get_query = "SELECT order_status FROM " . $this->table . " WHERE order_id = :order_id";
            $get_stmt = $this->conn->prepare($get_query);
            $get_stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
            $get_stmt->execute();
            $old_status = $get_stmt->fetchColumn();
            
            // Update order status
            $update_query = "UPDATE " . $this->table . " 
                            SET order_status = :status, updated_at = CURRENT_TIMESTAMP 
                            WHERE order_id = :order_id";
            $update_stmt = $this->conn->prepare($update_query);
            $update_stmt->bindParam(':status', $new_status);
            $update_stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
            $update_stmt->execute();
            
            // Insert status history
            $history_query = "INSERT INTO order_status_history 
                             (order_id, old_status, new_status, comment, created_by) 
                             VALUES (:order_id, :old_status, :new_status, :comment, :created_by)";
            $history_stmt = $this->conn->prepare($history_query);
            $history_stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
            $history_stmt->bindParam(':old_status', $old_status);
            $history_stmt->bindParam(':new_status', $new_status);
            $history_stmt->bindParam(':comment', $comment);
            $history_stmt->bindParam(':created_by', $created_by, PDO::PARAM_INT);
            $history_stmt->execute();
            
            $this->conn->commit();
            return true;
            
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    // Get all orders (Admin)
    public function getAll($page = 1, $per_page = 20, $filters = []) {
        $offset = ($page - 1) * $per_page;
        
        $query = "SELECT o.*, u.first_name, u.last_name, u.email 
                  FROM " . $this->table . " o
                  LEFT JOIN users u ON o.user_id = u.user_id
                  WHERE 1=1";
        
        if (!empty($filters['status'])) {
            $query .= " AND o.order_status = :status";
        }
        if (!empty($filters['payment_status'])) {
            $query .= " AND o.payment_status = :payment_status";
        }
        if (!empty($filters['search'])) {
            $query .= " AND (o.order_number LIKE :search OR u.email LIKE :search)";
        }
        
        $query .= " ORDER BY o.created_at DESC LIMIT :limit OFFSET :offset";
        
        $stmt = $this->conn->prepare($query);
        
        if (!empty($filters['status'])) {
            $stmt->bindParam(':status', $filters['status']);
        }
        if (!empty($filters['payment_status'])) {
            $stmt->bindParam(':payment_status', $filters['payment_status']);
        }
        if (!empty($filters['search'])) {
            $search_term = '%' . $filters['search'] . '%';
            $stmt->bindParam(':search', $search_term);
        }
        
        $stmt->bindParam(':limit', $per_page, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
