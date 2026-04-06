<?php
require_once __DIR__ . '/../config/database.php';

class Category {
    private $conn;
    private $table = 'categories';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Get all categories (parent-only list ordered by category_id after migrate_category_ids_home_order.sql)
    public function getAll($parent_only = false) {
        $query = "SELECT * FROM " . $this->table . " WHERE is_active = 1";
        if ($parent_only) {
            $query .= " AND parent_category_id IS NULL ORDER BY category_id ASC";
        } else {
            $query .= " ORDER BY display_order, category_name";
        }
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

    /**
     * Homepage “Categories” strip: top-level rows with category_id 1–10 (after migrate_category_ids_home_order.sql).
     */
    public function getHomeTopCategories() {
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE is_active = 1 AND parent_category_id IS NULL 
                  AND category_id BETWEEN 1 AND 10 
                  ORDER BY category_id ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Homepage categories: fixed order and display labels (matches migrate_category_ids_home_order.sql names).
     * Resolves each slot by primary slug, then optional alternate slugs.
     */
    public function getHomeCategoriesDisplay() {
        $order = [
            ['slug' => 'copiers-printers', 'label' => 'Copiers & Printers'],
            ['slug' => 'laptops', 'label' => 'Laptops'],
            ['slug' => 'desktops', 'label' => 'Desktops'],
            ['slug' => 'toners-cartridges', 'alt' => ['toners-cartriges'], 'label' => 'Toners & Cartriges'],
            ['slug' => 'monitors', 'label' => 'Monitors'],
            ['slug' => 'smart-boards', 'label' => 'Smart Boards'],
            ['slug' => 'projectors', 'label' => 'Projectors'],
            ['slug' => 'cash-counters', 'label' => 'Cash Counters'],
            ['slug' => 'accessories', 'label' => 'Accessories'],
            ['slug' => 'business-software', 'label' => 'Business Software'],
        ];

        $allSlugs = [];
        foreach ($order as $row) {
            $allSlugs[] = $row['slug'];
            if (!empty($row['alt'])) {
                $allSlugs = array_merge($allSlugs, $row['alt']);
            }
        }
        $allSlugs = array_unique($allSlugs);
        $placeholders = implode(',', array_fill(0, count($allSlugs), '?'));
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE is_active = 1 AND parent_category_id IS NULL 
                  AND category_slug IN ($placeholders)";
        $stmt = $this->conn->prepare($query);
        $stmt->execute(array_values($allSlugs));
        $bySlug = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $cat) {
            $bySlug[$cat['category_slug']] = $cat;
        }

        $out = [];
        foreach ($order as $def) {
            $cat = $bySlug[$def['slug']] ?? null;
            if (!$cat && !empty($def['alt'])) {
                foreach ($def['alt'] as $altSlug) {
                    if (!empty($bySlug[$altSlug])) {
                        $cat = $bySlug[$altSlug];
                        break;
                    }
                }
            }
            if ($cat) {
                $cat['display_name'] = $def['label'];
                $out[] = $cat;
            }
        }

        if (empty($out)) {
            $idLabels = [
                1 => 'Copiers & Printers',
                2 => 'Laptops',
                3 => 'Desktops',
                4 => 'Toners & Cartriges',
                5 => 'Monitors',
                6 => 'Smart Boards',
                7 => 'Projectors',
                8 => 'Cash Counters',
                9 => 'Accessories',
                10 => 'Business Software',
            ];
            foreach ($this->getHomeTopCategories() as $cat) {
                $id = (int)$cat['category_id'];
                if (isset($idLabels[$id])) {
                    $cat['display_name'] = $idLabels[$id];
                    $out[] = $cat;
                }
            }
        }

        return $out;
    }

    /**
     * Active product counts keyed by category_id (for homepage category grid).
     *
     * @param int[] $categoryIds
     * @return array<int, int>
     */
    public function getProductCountsByCategoryIds(array $categoryIds) {
        $categoryIds = array_values(array_filter(array_map('intval', $categoryIds)));
        if ($categoryIds === []) {
            return [];
        }
        $placeholders = implode(',', array_fill(0, count($categoryIds), '?'));
        $query = "SELECT category_id, COUNT(*) AS cnt FROM products
                  WHERE is_active = 1 AND category_id IN ($placeholders)
                  GROUP BY category_id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute($categoryIds);
        $out = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $out[(int) $row['category_id']] = (int) $row['cnt'];
        }
        return $out;
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
