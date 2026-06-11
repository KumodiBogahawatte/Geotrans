<?php
class ProductColor {
    private $conn;
    private $table = 'product_colors';
    private $images_table = 'color_images';

    public function __construct($db) {
        $this->conn = $db;
    }

    // Get all colors for a product
    public function getByProduct($product_id) {
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE product_id = :product_id AND is_active = 1 
                  ORDER BY display_order ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':product_id', $product_id);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get images for a specific color
    public function getColorImages($color_id) {
        $query = "SELECT * FROM " . $this->images_table . " 
                  WHERE color_id = :color_id 
                  ORDER BY display_order ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':color_id', $color_id);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Add a new color
    public function addColor($product_id, $color_name, $color_hex, $display_order = 0) {
        $query = "INSERT INTO " . $this->table . " 
                  (product_id, color_name, color_hex, display_order, is_active, created_at) 
                  VALUES (:product_id, :color_name, :color_hex, :display_order, 1, NOW())";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':product_id', $product_id);
        $stmt->bindParam(':color_name', $color_name);
        $stmt->bindParam(':color_hex', $color_hex);
        $stmt->bindParam(':display_order', $display_order);
        
        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    // Add image to color
    public function addColorImage($color_id, $image_url, $display_order = 0) {
        $query = "INSERT INTO " . $this->images_table . " 
                  (color_id, image_url, display_order) 
                  VALUES (:color_id, :image_url, :display_order)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':color_id', $color_id);
        $stmt->bindParam(':image_url', $image_url);
        $stmt->bindParam(':display_order', $display_order);
        
        return $stmt->execute();
    }

    // Delete color and its images
    public function deleteColor($color_id) {
        // Delete images first
        $query = "DELETE FROM " . $this->images_table . " WHERE color_id = :color_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':color_id', $color_id);
        $stmt->execute();

        // Delete color
        $query = "DELETE FROM " . $this->table . " WHERE color_id = :color_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':color_id', $color_id);
        return $stmt->execute();
    }

    // Delete color image
    public function deleteColorImage($image_id) {
        $query = "DELETE FROM " . $this->images_table . " WHERE image_id = :image_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':image_id', $image_id);
        return $stmt->execute();
    }

    // Update color info
    public function updateColor($color_id, $color_name, $color_hex) {
        $query = "UPDATE " . $this->table . " 
                  SET color_name = :color_name, color_hex = :color_hex, updated_at = NOW() 
                  WHERE color_id = :color_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':color_id', $color_id);
        $stmt->bindParam(':color_name', $color_name);
        $stmt->bindParam(':color_hex', $color_hex);
        
        return $stmt->execute();
    }

    // Get color by ID
    public function getById($color_id) {
        $query = "SELECT * FROM " . $this->table . " WHERE color_id = :color_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':color_id', $color_id);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
