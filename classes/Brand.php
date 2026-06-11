<?php
require_once __DIR__ . '/../config/database.php';

class Brand {
    private $conn;
    private $table = 'brands';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Get all brands
    public function getAll() {
        $query = "SELECT * FROM " . $this->table . " WHERE is_active = 1 ORDER BY brand_name";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get brand by ID or slug
    public function getById($id) {
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE (brand_id = :id OR brand_slug = :slug) AND is_active = 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':slug', $id);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Get popular brands (with product count)
    public function getPopular($limit = 10) {
        $query = "SELECT b.*, COUNT(p.product_id) as product_count 
                  FROM " . $this->table . " b
                  LEFT JOIN products p ON b.brand_id = p.brand_id AND p.is_active = 1
                  WHERE b.is_active = 1
                  GROUP BY b.brand_id
                  HAVING product_count > 0
                  ORDER BY product_count ASC
                  LIMIT :limit";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get all active brands that have active products in a category
    public function getByCategory($category_id) {
        $query = "SELECT b.*, COUNT(p.product_id) AS product_count
                  FROM " . $this->table . " b
                  INNER JOIN products p ON b.brand_id = p.brand_id
                  WHERE b.is_active = 1
                    AND p.is_active = 1
                    AND p.category_id = :category_id
                  GROUP BY b.brand_id
                  ORDER BY b.brand_name ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
