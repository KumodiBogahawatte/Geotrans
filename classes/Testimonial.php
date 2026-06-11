<?php
class Testimonial {
    private $conn;
    private $table_name = "testimonials";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Get all testimonials with filters
    public function getAll($status = 'all', $search = '') {
        $query = "SELECT * FROM " . $this->table_name . " WHERE 1=1";
        
        if ($status === 'active') {
            $query .= " AND is_active = 1";
        } elseif ($status === 'inactive') {
            $query .= " AND is_active = 0";
        }
        
        if (!empty($search)) {
            $query .= " AND (customer_name LIKE :search OR customer_role LIKE :search OR feedback_text LIKE :search)";
        }
        
        $query .= " ORDER BY display_order ASC, created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        
        if (!empty($search)) {
            $search_term = "%{$search}%";
            $stmt->bindParam(":search", $search_term);
        }
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get active testimonials for frontend
    public function getActive($limit = null) {
        $query = "SELECT * FROM " . $this->table_name . " 
                  WHERE is_active = 1 
                  ORDER BY display_order ASC, created_at DESC";
        
        if ($limit) {
            $query .= " LIMIT :limit";
        }
        
        $stmt = $this->conn->prepare($query);
        
        if ($limit) {
            $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
        }
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get single testimonial
    public function getById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE testimonial_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Create new testimonial
    public function create($data) {
        $query = "INSERT INTO " . $this->table_name . " 
                  (user_id, customer_name, customer_role, customer_image, rating, feedback_text, feedback_date, is_active, is_verified, display_order) 
                  VALUES (:user_id, :customer_name, :customer_role, :customer_image, :rating, :feedback_text, :feedback_date, :is_active, :is_verified, :display_order)";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":user_id", $data['user_id']);
        $stmt->bindParam(":customer_name", $data['customer_name']);
        $stmt->bindParam(":customer_role", $data['customer_role']);
        $stmt->bindParam(":customer_image", $data['customer_image']);
        $stmt->bindParam(":rating", $data['rating']);
        $stmt->bindParam(":feedback_text", $data['feedback_text']);
        $stmt->bindParam(":feedback_date", $data['feedback_date']);
        $stmt->bindParam(":is_active", $data['is_active']);
        $stmt->bindParam(":is_verified", $data['is_verified']);
        $stmt->bindParam(":display_order", $data['display_order']);
        
        return $stmt->execute();
    }

    // Update testimonial
    public function update($id, $data) {
        $query = "UPDATE " . $this->table_name . " 
                  SET customer_name = :customer_name,
                      customer_role = :customer_role,
                      rating = :rating,
                      feedback_text = :feedback_text,
                      feedback_date = :feedback_date,
                      is_active = :is_active,
                      is_verified = :is_verified,
                      display_order = :display_order
                  WHERE testimonial_id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":customer_name", $data['customer_name']);
        $stmt->bindParam(":customer_role", $data['customer_role']);
        $stmt->bindParam(":rating", $data['rating']);
        $stmt->bindParam(":feedback_text", $data['feedback_text']);
        $stmt->bindParam(":feedback_date", $data['feedback_date']);
        $stmt->bindParam(":is_active", $data['is_active']);
        $stmt->bindParam(":is_verified", $data['is_verified']);
        $stmt->bindParam(":display_order", $data['display_order']);
        $stmt->bindParam(":id", $id);
        
        return $stmt->execute();
    }

    // Delete testimonial
    public function delete($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE testimonial_id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$id]);
    }

    // Toggle active status
    public function toggleStatus($id) {
        $query = "UPDATE " . $this->table_name . " 
                  SET is_active = NOT is_active 
                  WHERE testimonial_id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$id]);
    }

    // Get max display order
    public function getMaxDisplayOrder() {
        $query = "SELECT MAX(display_order) as max_order FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['max_order'] ?? 0;
    }
}
?>
