<?php
require_once __DIR__ . '/../config/database.php';

class Product {
    private $conn;
    private $table = 'products';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Get all products with pagination
    public function getAll($page = 1, $per_page = 20, $filters = []) {
        $offset = ($page - 1) * $per_page;
        
        $query = "SELECT p.*, c.category_name, b.brand_name,
                  COALESCE(AVG(r.rating), 0) as avg_rating,
                  COUNT(r.review_id) as review_count
                  FROM " . $this->table . " p
                  LEFT JOIN categories c ON p.category_id = c.category_id
                  LEFT JOIN brands b ON p.brand_id = b.brand_id
                  LEFT JOIN product_reviews r ON p.product_id = r.product_id AND r.is_approved = 1
                  WHERE p.is_active = 1";
        
        // Apply filters
        if (!empty($filters['category_id'])) {
            $query .= " AND p.category_id = :category_id";
        }
        if (!empty($filters['brand_id'])) {
            $query .= " AND p.brand_id = :brand_id";
        }
        if (!empty($filters['min_price'])) {
            $query .= " AND p.sale_price >= :min_price";
        }
        if (!empty($filters['max_price'])) {
            $query .= " AND p.sale_price <= :max_price";
        }
        if (!empty($filters['search'])) {
            $query .= " AND (p.product_name LIKE :search OR p.description LIKE :search)";
        }
        if (!empty($filters['is_featured'])) {
            $query .= " AND p.is_featured = 1";
        }
        if (!empty($filters['is_bestseller'])) {
            $query .= " AND p.is_bestseller = 1";
        }
        if (!empty($filters['is_new_arrival'])) {
            $query .= " AND p.is_new_arrival = 1";
        }
        
        // Group by product
        $query .= " GROUP BY p.product_id";
        
        // Sorting
        $sort = isset($filters['sort']) ? $filters['sort'] : 'newest';
        switch ($sort) {
            case 'price_low':
                $query .= " ORDER BY p.sale_price ASC";
                break;
            case 'price_high':
                $query .= " ORDER BY p.sale_price DESC";
                break;
            case 'popular':
                $query .= " ORDER BY p.sold_count DESC";
                break;
            case 'rating':
                $query .= " ORDER BY avg_rating DESC";
                break;
            default:
                $query .= " ORDER BY p.created_at DESC";
        }
        
        $query .= " LIMIT :limit OFFSET :offset";
        
        $stmt = $this->conn->prepare($query);
        
        // Bind filters
        if (!empty($filters['category_id'])) {
            $stmt->bindParam(':category_id', $filters['category_id'], PDO::PARAM_INT);
        }
        if (!empty($filters['brand_id'])) {
            $stmt->bindParam(':brand_id', $filters['brand_id'], PDO::PARAM_INT);
        }
        if (!empty($filters['min_price'])) {
            $stmt->bindParam(':min_price', $filters['min_price']);
        }
        if (!empty($filters['max_price'])) {
            $stmt->bindParam(':max_price', $filters['max_price']);
        }
        if (!empty($filters['search'])) {
            $search_term = '%' . $filters['search'] . '%';
            $stmt->bindParam(':search', $search_term);
        }
        
        $stmt->bindParam(':limit', $per_page, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        
        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Get total count
        $total = $this->getCount($filters);
        $total_pages = ceil($total / $per_page);
        
        return [
            'products' => $products,
            'total' => $total,
            'total_pages' => $total_pages,
            'current_page' => $page
        ];
    }

    // Get total count for pagination
    public function getCount($filters = []) {
        $query = "SELECT COUNT(*) as total FROM " . $this->table . " WHERE is_active = 1";
        
        if (!empty($filters['category_id'])) {
            $query .= " AND category_id = :category_id";
        }
        if (!empty($filters['brand_id'])) {
            $query .= " AND brand_id = :brand_id";
        }
        if (!empty($filters['min_price'])) {
            $query .= " AND sale_price >= :min_price";
        }
        if (!empty($filters['max_price'])) {
            $query .= " AND sale_price <= :max_price";
        }
        if (!empty($filters['search'])) {
            $query .= " AND (product_name LIKE :search OR description LIKE :search)";
        }
        
        $stmt = $this->conn->prepare($query);
        
        if (!empty($filters['category_id'])) {
            $stmt->bindParam(':category_id', $filters['category_id'], PDO::PARAM_INT);
        }
        if (!empty($filters['brand_id'])) {
            $stmt->bindParam(':brand_id', $filters['brand_id'], PDO::PARAM_INT);
        }
        if (!empty($filters['min_price'])) {
            $stmt->bindParam(':min_price', $filters['min_price']);
        }
        if (!empty($filters['max_price'])) {
            $stmt->bindParam(':max_price', $filters['max_price']);
        }
        if (!empty($filters['search'])) {
            $search_term = '%' . $filters['search'] . '%';
            $stmt->bindParam(':search', $search_term);
        }
        
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }

    // Get single product by ID or slug
    public function getById($id) {
        $query = "SELECT p.*, c.category_name, c.category_slug, b.brand_name, b.brand_slug,
                  COALESCE(AVG(r.rating), 0) as rating,
                  COUNT(r.review_id) as review_count
                  FROM " . $this->table . " p
                  LEFT JOIN categories c ON p.category_id = c.category_id
                  LEFT JOIN brands b ON p.brand_id = b.brand_id
                  LEFT JOIN product_reviews r ON p.product_id = r.product_id AND r.is_approved = 1
                  WHERE (p.product_id = :id OR p.product_slug = :slug) AND p.is_active = 1
                  GROUP BY p.product_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':slug', $id);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Get product images
    public function getImages($product_id) {
        $query = "SELECT * FROM product_images WHERE product_id = :product_id ORDER BY display_order";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get product specifications
    public function getSpecifications($product_id) {
        $query = "SELECT * FROM product_specifications WHERE product_id = :product_id ORDER BY display_order";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get product reviews
    public function getReviews($product_id) {
        $query = "SELECT r.*, u.first_name, u.last_name 
                  FROM product_reviews r
                  LEFT JOIN users u ON r.user_id = u.user_id
                  WHERE r.product_id = :product_id AND r.is_approved = 1
                  ORDER BY r.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get featured products
    public function getFeatured($limit = 10) {
        $query = "SELECT p.*, c.category_name, b.brand_name,
                  COALESCE(AVG(r.rating), 0) as avg_rating,
                  COUNT(r.review_id) as review_count
                  FROM " . $this->table . " p
                  LEFT JOIN categories c ON p.category_id = c.category_id
                  LEFT JOIN brands b ON p.brand_id = b.brand_id
                  LEFT JOIN product_reviews r ON p.product_id = r.product_id AND r.is_approved = 1
                  WHERE p.is_featured = 1 AND p.is_active = 1
                  GROUP BY p.product_id
                  ORDER BY p.created_at DESC
                  LIMIT :limit";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get bestseller products
    public function getBestsellers($limit = 10) {
        $query = "SELECT p.*, c.category_name, b.brand_name,
                  COALESCE(AVG(r.rating), 0) as avg_rating,
                  COUNT(r.review_id) as review_count
                  FROM " . $this->table . " p
                  LEFT JOIN categories c ON p.category_id = c.category_id
                  LEFT JOIN brands b ON p.brand_id = b.brand_id
                  LEFT JOIN product_reviews r ON p.product_id = r.product_id AND r.is_approved = 1
                  WHERE p.is_bestseller = 1 AND p.is_active = 1
                  GROUP BY p.product_id
                  ORDER BY p.sold_count DESC
                  LIMIT :limit";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get new arrivals
    public function getNewArrivals($limit = 10) {
        $query = "SELECT p.*, c.category_name, b.brand_name,
                  COALESCE(AVG(r.rating), 0) as avg_rating,
                  COUNT(r.review_id) as review_count
                  FROM " . $this->table . " p
                  LEFT JOIN categories c ON p.category_id = c.category_id
                  LEFT JOIN brands b ON p.brand_id = b.brand_id
                  LEFT JOIN product_reviews r ON p.product_id = r.product_id AND r.is_approved = 1
                  WHERE p.is_new_arrival = 1 AND p.is_active = 1
                  GROUP BY p.product_id
                  ORDER BY p.created_at DESC
                  LIMIT :limit";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get related products
    public function getRelated($product_id, $category_id, $limit = 5) {
        $query = "SELECT p.*, c.category_name, b.brand_name,
                  COALESCE(AVG(r.rating), 0) as avg_rating,
                  COUNT(r.review_id) as review_count
                  FROM " . $this->table . " p
                  LEFT JOIN categories c ON p.category_id = c.category_id
                  LEFT JOIN brands b ON p.brand_id = b.brand_id
                  LEFT JOIN product_reviews r ON p.product_id = r.product_id AND r.is_approved = 1
                  WHERE p.category_id = :category_id 
                  AND p.product_id != :product_id 
                  AND p.is_active = 1
                  GROUP BY p.product_id
                  ORDER BY p.sold_count DESC
                  LIMIT :limit";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
        $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Increment view count
    public function incrementViewCount($product_id) {
        $query = "UPDATE " . $this->table . " SET view_count = view_count + 1 WHERE product_id = :product_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Search products
    public function search($search_term, $limit = 10, $category_id = 0) {
        $query = "SELECT p.*, c.category_name, b.brand_name 
                  FROM " . $this->table . " p
                  LEFT JOIN categories c ON p.category_id = c.category_id
                  LEFT JOIN brands b ON p.brand_id = b.brand_id
                  WHERE (p.product_name LIKE :search 
                  OR p.description LIKE :search 
                  OR p.short_description LIKE :search
                  OR b.brand_name LIKE :search
                  OR c.category_name LIKE :search)
                  AND p.is_active = 1";
        
        // Add category filter if specified
        if ($category_id > 0) {
            $query .= " AND p.category_id = :category_id";
        }
        
        $query .= " ORDER BY p.product_name LIMIT :limit";
        
        $stmt = $this->conn->prepare($query);
        $search_param = '%' . $search_term . '%';
        $stmt->bindParam(':search', $search_param);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        
        if ($category_id > 0) {
            $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
        }
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Create product (Admin)
    public function create($data) {
        $query = "INSERT INTO " . $this->table . " 
                  (product_name, product_slug, category_id, brand_id, sku, description, 
                   short_description, price, sale_price, stock_quantity, main_image)
                  VALUES (:name, :slug, :category_id, :brand_id, :sku, :description, 
                   :short_description, :price, :sale_price, :stock, :image)";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':name', $data['product_name']);
        $stmt->bindParam(':slug', $data['product_slug']);
        $stmt->bindParam(':category_id', $data['category_id']);
        $stmt->bindParam(':brand_id', $data['brand_id']);
        $stmt->bindParam(':sku', $data['sku']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':short_description', $data['short_description']);
        $stmt->bindParam(':price', $data['price']);
        $stmt->bindParam(':sale_price', $data['sale_price']);
        $stmt->bindParam(':stock', $data['stock_quantity']);
        $stmt->bindParam(':image', $data['main_image']);
        
        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    // Update product (Admin)
    public function update($product_id, $data) {
        $query = "UPDATE " . $this->table . " 
                  SET product_name = :name, product_slug = :slug, category_id = :category_id, 
                      brand_id = :brand_id, sku = :sku, description = :description, 
                      short_description = :short_description, price = :price, 
                      sale_price = :sale_price, stock_quantity = :stock, 
                      main_image = :image, updated_at = CURRENT_TIMESTAMP
                  WHERE product_id = :product_id";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
        $stmt->bindParam(':name', $data['product_name']);
        $stmt->bindParam(':slug', $data['product_slug']);
        $stmt->bindParam(':category_id', $data['category_id']);
        $stmt->bindParam(':brand_id', $data['brand_id']);
        $stmt->bindParam(':sku', $data['sku']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':short_description', $data['short_description']);
        $stmt->bindParam(':price', $data['price']);
        $stmt->bindParam(':sale_price', $data['sale_price']);
        $stmt->bindParam(':stock', $data['stock_quantity']);
        $stmt->bindParam(':image', $data['main_image']);
        
        return $stmt->execute();
    }

    // Delete product (Admin)
    public function delete($product_id) {
        $query = "UPDATE " . $this->table . " SET is_active = 0 WHERE product_id = :product_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
?>
