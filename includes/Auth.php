<?php
require_once 'Database.php';

class Auth {
    private $conn;
    private $table = 'users';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function register($username, $email, $password) {
        try {
            // Validate input
            if (empty($username) || empty($email) || empty($password)) {
                return ['success' => false, 'message' => 'All fields are required'];
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return ['success' => false, 'message' => 'Invalid email format'];
            }

            if (strlen($password) < 6) {
                return ['success' => false, 'message' => 'Password must be at least 6 characters'];
            }

            // Check if user exists
            $stmt = $this->conn->prepare("SELECT id FROM {$this->table} WHERE username = ? OR email = ?");
            $stmt->execute([$username, $email]);
            
            if ($stmt->rowCount() > 0) {
                return ['success' => false, 'message' => 'Username or email already exists'];
            }

            // Create user
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $this->conn->prepare("INSERT INTO {$this->table} (username, email, password, created_at) VALUES (?, ?, ?, NOW())");
            $stmt->execute([$username, $email, $hashedPassword]);

            $user_id = $this->conn->lastInsertId();
            
            return [
                'success' => true, 
                'message' => 'Registration successful',
                'data' => [
                    'id' => $user_id,
                    'username' => $username,
                    'email' => $email
                ]
            ];
            
        } catch (PDOException $e) {
            error_log("Registration error: " . $e->getMessage());
            return ['success' => false, 'message' => 'Registration failed. Please try again.'];
        }
    }

    public function login($username, $password) {
        try {
            if (empty($username) || empty($password)) {
                return ['success' => false, 'message' => 'All fields are required'];
            }

            // Get user
            $stmt = $this->conn->prepare("SELECT id, username, email, password FROM {$this->table} WHERE username = ? OR email = ?");
            $stmt->execute([$username, $username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user || !password_verify($password, $user['password'])) {
                return ['success' => false, 'message' => 'Invalid credentials'];
            }

            return [
                'success' => true,
                'message' => 'Login successful',
                'data' => [
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'email' => $user['email']
                ]
            ];
            
        } catch (PDOException $e) {
            error_log("Login error: " . $e->getMessage());
            return ['success' => false, 'message' => 'Login failed. Please try again.'];
        }
    }
}
?>