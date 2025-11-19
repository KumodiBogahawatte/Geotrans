<?php
require_once __DIR__ . '/../config/database.php';

class User {
    private $conn;
    private $table = 'users';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Register new user
    public function register($email, $password, $first_name, $last_name, $phone = null) {
        $query = "INSERT INTO " . $this->table . " 
                  (email, password_hash, first_name, last_name, phone) 
                  VALUES (:email, :password, :first_name, :last_name, :phone)";
        
        $stmt = $this->conn->prepare($query);
        
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password_hash);
        $stmt->bindParam(':first_name', $first_name);
        $stmt->bindParam(':last_name', $last_name);
        $stmt->bindParam(':phone', $phone);
        
        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    // Login user
    public function login($email, $password) {
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE email = :email AND is_active = 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($password, $user['password_hash'])) {
            // Update last login
            $update_query = "UPDATE " . $this->table . " 
                            SET last_login = CURRENT_TIMESTAMP 
                            WHERE user_id = :user_id";
            $update_stmt = $this->conn->prepare($update_query);
            $update_stmt->bindParam(':user_id', $user['user_id'], PDO::PARAM_INT);
            $update_stmt->execute();
            
            // Remove password from returned data
            unset($user['password_hash']);
            return $user;
        }
        
        return false;
    }

    // Get user by ID
    public function getById($user_id) {
        $query = "SELECT user_id, email, first_name, last_name, phone, user_type, 
                  is_verified, created_at, last_login 
                  FROM " . $this->table . " 
                  WHERE user_id = :user_id AND is_active = 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Get user by ID with password hash (for password verification)
    public function getByIdWithPassword($user_id) {
        $query = "SELECT user_id, email, first_name, last_name, phone, user_type, 
                  password_hash, is_verified, created_at, last_login 
                  FROM " . $this->table . " 
                  WHERE user_id = :user_id AND is_active = 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Get user by email
    public function getByEmail($email) {
        $query = "SELECT * FROM " . $this->table . " WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Update user profile
    public function updateProfile($user_id, $data) {
        $query = "UPDATE " . $this->table . " 
                  SET first_name = :first_name, last_name = :last_name, 
                      phone = :phone, updated_at = CURRENT_TIMESTAMP 
                  WHERE user_id = :user_id";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':first_name', $data['first_name']);
        $stmt->bindParam(':last_name', $data['last_name']);
        $stmt->bindParam(':phone', $data['phone']);
        
        return $stmt->execute();
    }

    // Change password
    public function changePassword($user_id, $new_password) {
        $query = "UPDATE " . $this->table . " 
                  SET password_hash = :password, updated_at = CURRENT_TIMESTAMP 
                  WHERE user_id = :user_id";
        
        $stmt = $this->conn->prepare($query);
        
        $password_hash = password_hash($new_password, PASSWORD_DEFAULT);
        
        $stmt->bindParam(':password', $password_hash);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        
        return $stmt->execute();
    }

    // Get user addresses
    public function getAddresses($user_id) {
        $query = "SELECT * FROM user_addresses 
                  WHERE user_id = :user_id 
                  ORDER BY is_default DESC, created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Add address
    public function addAddress($user_id, $data) {
        $query = "INSERT INTO user_addresses 
                  (user_id, address_type, full_name, address_line1, address_line2, 
                   city, state, postal_code, country, phone, is_default) 
                  VALUES (:user_id, :type, :full_name, :address1, :address2, 
                   :city, :state, :postal, :country, :phone, :is_default)";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':type', $data['address_type']);
        $stmt->bindParam(':full_name', $data['full_name']);
        $stmt->bindParam(':address1', $data['address_line1']);
        $stmt->bindParam(':address2', $data['address_line2']);
        $stmt->bindParam(':city', $data['city']);
        $stmt->bindParam(':state', $data['state']);
        $stmt->bindParam(':postal', $data['postal_code']);
        $stmt->bindParam(':country', $data['country']);
        $stmt->bindParam(':phone', $data['phone']);
        $stmt->bindParam(':is_default', $data['is_default']);
        
        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    // Check if email exists
    public function emailExists($email) {
        $query = "SELECT user_id FROM " . $this->table . " WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }
}
?>
