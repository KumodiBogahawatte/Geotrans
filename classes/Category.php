<?php
require_once __DIR__ . '/../config/database.php';

class Category {
    private $conn;
    private $table = 'categories';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Get all categories
    public function getAll($parent_only = false) {
        $query = "SELECT * FROM " . $this->table . " WHERE is_active = 1";
        
        if ($parent_only) {
            $query .= " AND parent_category_id IS NULL";
        }
        
        $query .= " ORDER BY display_order, category_name";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get category by ID or slug
    public function getById($id) {
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE (category_id = :id OR category_slug = :slug) AND is_active = 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':slug', $id);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Get subcategories
    public function getSubcategories($parent_id) {
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE parent_category_id = :parent_id AND is_active = 1
                  ORDER BY display_order, category_name";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':parent_id', $parent_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get popular categories (with product count)
    public function getPopular($limit = 10) {
        $query = "SELECT c.*, COUNT(p.product_id) as product_count 
                  FROM " . $this->table . " c
                  LEFT JOIN products p ON c.category_id = p.category_id AND p.is_active = 1
                  WHERE c.is_active = 1 AND c.parent_category_id IS NULL
                  GROUP BY c.category_id
                  HAVING product_count > 0
                  ORDER BY product_count DESC
                  LIMIT :limit";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Create category (Admin)
    public function create($data) {
        $query = "INSERT INTO " . $this->table . " 
                  (category_name, category_slug, category_description, category_image, parent_category_id)
                  VALUES (:name, :slug, :description, :image, :parent_id)";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':name', $data['category_name']);
        $stmt->bindParam(':slug', $data['category_slug']);
        $stmt->bindParam(':description', $data['category_description']);
        $stmt->bindParam(':image', $data['category_image']);
        $stmt->bindParam(':parent_id', $data['parent_category_id']);
        
        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    // Update category (Admin)
    public function update($category_id, $data) {
        $query = "UPDATE " . $this->table . " 
                  SET category_name = :name, category_slug = :slug, 
                      category_description = :description, category_image = :image,
                      parent_category_id = :parent_id, updated_at = CURRENT_TIMESTAMP
                  WHERE category_id = :category_id";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
        $stmt->bindParam(':name', $data['category_name']);
        $stmt->bindParam(':slug', $data['category_slug']);
        $stmt->bindParam(':description', $data['category_description']);
        $stmt->bindParam(':image', $data['category_image']);
        $stmt->bindParam(':parent_id', $data['parent_category_id']);
        
        return $stmt->execute();
    }

    // Delete category (Admin)
    public function delete($category_id) {
        $query = "UPDATE " . $this->table . " SET is_active = 0 WHERE category_id = :category_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
?>
