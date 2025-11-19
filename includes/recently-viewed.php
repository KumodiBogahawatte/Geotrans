<?php
/**
 * Recently Viewed Products Tracker
 */

class RecentlyViewed {
    private $maxItems = 10;
    
    public function __construct() {
        if (!isset($_SESSION['recently_viewed'])) {
            $_SESSION['recently_viewed'] = [];
        }
    }
    
    /**
     * Add product to recently viewed
     */
    public function add($productId) {
        $productId = (int)$productId;
        
        // Remove if already exists
        $key = array_search($productId, $_SESSION['recently_viewed']);
        if ($key !== false) {
            unset($_SESSION['recently_viewed'][$key]);
        }
        
        // Add to beginning of array
        array_unshift($_SESSION['recently_viewed'], $productId);
        
        // Keep only max items
        $_SESSION['recently_viewed'] = array_slice($_SESSION['recently_viewed'], 0, $this->maxItems);
    }
    
    /**
     * Get recently viewed products
     */
    public function get($limit = null) {
        if ($limit) {
            return array_slice($_SESSION['recently_viewed'], 0, $limit);
        }
        return $_SESSION['recently_viewed'];
    }
    
    /**
     * Get recently viewed products with details
     */
    public function getWithDetails($limit = 5) {
        if (empty($_SESSION['recently_viewed'])) {
            return [];
        }
        
        require_once __DIR__ . '/../config/database.php';
        
        $database = new Database();
        $conn = $database->getConnection();
        
        $ids = $this->get($limit);
        $placeholders = str_repeat('?,', count($ids) - 1) . '?';
        
        $query = "SELECT p.product_id, p.product_name, p.product_slug, p.main_image, 
                  p.price, p.sale_price, c.category_name, b.brand_name
                  FROM products p
                  LEFT JOIN categories c ON p.category_id = c.category_id
                  LEFT JOIN brands b ON p.brand_id = b.brand_id
                  WHERE p.product_id IN ($placeholders) AND p.is_active = 1
                  ORDER BY FIELD(p.product_id, " . implode(',', $ids) . ")";
        
        $stmt = $conn->prepare($query);
        $stmt->execute($ids);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Clear recently viewed
     */
    public function clear() {
        $_SESSION['recently_viewed'] = [];
    }
}
?>
