<?php
class Database {
    private $host;
    private $port;
    private $db_name;
    private $username;
    private $password;
    public $conn;

    public function __construct() {
        // Load environment variables from .env file
        $env_vars = $this->loadEnv();
        
        $this->host = $env_vars['DB_HOST'] ?? '127.0.0.1';
        $this->port = $env_vars['DB_PORT'] ?? '3307';
        $this->db_name = $env_vars['DB_NAME'] ?? 'myblogapp_db';
        $this->username = $env_vars['DB_USER'] ?? 'root';
        $this->password = $env_vars['DB_PASS'] ?? '';
    }

    private function loadEnv() {
        $env_vars = [];
        
        if (!file_exists('.env')) {
            return $env_vars;
        }

        $lines = file('.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0) continue; // Skip comments
            
            if (strpos($line, '=') !== false) {
                list($key, $value) = explode('=', $line, 2);
                $key = trim($key);
                $value = trim($value);
                
                // Remove quotes if present
                $value = trim($value, '"\'');
                
                $env_vars[$key] = $value;
            }
        }
        
        return $env_vars;
    }

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . 
                ";port=" . $this->port . 
                ";dbname=" . $this->db_name . 
                ";charset=utf8mb4",
                $this->username, 
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch(PDOException $exception) {
            error_log("Database connection error: " . $exception->getMessage());
            throw new Exception("Database connection failed: " . $exception->getMessage());
        }
        return $this->conn;
    }
}
?>