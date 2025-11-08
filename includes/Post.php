<?php
require_once 'Database.php';
class Post {
    private $conn;
    private $table = 'blog_posts';

    private function createPostsTable() {
        try {
            $query = "CREATE TABLE IF NOT EXISTS {$this->table} (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `user_id` int(11) NOT NULL,
                `title` varchar(255) NOT NULL,
                `content` text NOT NULL,
                `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
                `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
                PRIMARY KEY (`id`),
                KEY `user_id` (`user_id`),
                CONSTRAINT `{$this->table}_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
            
            $this->conn->exec($query);
            error_log("Blog posts table created successfully");
            return true;
        } catch (PDOException $e) {
            error_log("Failed to create blog posts table: " . $e->getMessage());
            return false;
        }
    }

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function create($userId, $title, $content) {
        try {
            // Validate inputs
            if (empty($userId)) {
                error_log("Create post error: User ID is empty");
                return ['success' => false, 'message' => 'User ID is required'];
            }
            if (empty($title) || empty($content)) {
                error_log("Create post error: Title or content is empty");
                return ['success' => false, 'message' => 'Title and content are required'];
            }

            // Check if table exists
            $tableCheck = $this->conn->query("SHOW TABLES LIKE '{$this->table}'");
            if ($tableCheck->rowCount() === 0) {
                // Create table if it doesn't exist
                $this->createPostsTable();
            }

            $stmt = $this->conn->prepare(
                "INSERT INTO {$this->table} (user_id, title, content, created_at) VALUES (?, ?, ?, NOW())"
            );
            
            $stmt->execute([$userId, $title, $content]);
            $postId = $this->conn->lastInsertId();

            return [
                'success' => true, 
                'message' => 'Post created successfully',
                'data' => ['post_id' => $postId]
            ];
        } catch (PDOException $e) {
            error_log("Database error in create post: " . $e->getMessage());
            error_log("SQL State: " . $e->errorInfo[0]);
            error_log("Error Code: " . $e->errorInfo[1]);
            error_log("Error Message: " . $e->errorInfo[2]);
            error_log("Create post error: " . $e->getMessage());
            return ['success' => false, 'message' => 'Failed to create post'];
        }
    }

    public function getAllPosts($page = 1, $limit = 10) {
        try {
            $offset = ($page - 1) * $limit;

            $stmt = $this->conn->prepare(
                "SELECT p.*, u.username as author_name 
                 FROM {$this->table} p 
                 JOIN users u ON p.user_id = u.id 
                 ORDER BY p.created_at DESC 
                 LIMIT ? OFFSET ?"
            );
            
            $stmt->bindValue(1, $limit, PDO::PARAM_INT);
            $stmt->bindValue(2, $offset, PDO::PARAM_INT);
            $stmt->execute();
            $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Format posts
            foreach ($posts as &$post) {
                $post['excerpt'] = $this->generateExcerpt($post['content']);
                $post['formatted_date'] = date('F j, Y', strtotime($post['created_at']));
            }

            // Get total count
            $countStmt = $this->conn->query("SELECT COUNT(*) FROM {$this->table}");
            $totalPosts = $countStmt->fetchColumn();

            return [
                'success' => true,
                'data' => [
                    'posts' => $posts,
                    'total' => $totalPosts,
                    'pages' => ceil($totalPosts / $limit),
                    'current_page' => $page
                ]
            ];
        } catch (PDOException $e) {
            error_log("Get posts error: " . $e->getMessage());
            return ['success' => false, 'message' => 'Failed to fetch posts'];
        }
    }

    public function getPost($id) {
        try {
            $stmt = $this->conn->prepare(
                "SELECT p.*, u.username as author_name 
                 FROM {$this->table} p 
                 JOIN users u ON p.user_id = u.id 
                 WHERE p.id = ?"
            );
            
            $stmt->execute([$id]);
            $post = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$post) {
                return ['success' => false, 'message' => 'Post not found'];
            }

            $post['formatted_date'] = date('F j, Y', strtotime($post['created_at']));
            if ($post['updated_at']) {
                $post['formatted_updated'] = date('F j, Y', strtotime($post['updated_at']));
            }

            return ['success' => true, 'data' => $post];
        } catch (PDOException $e) {
            error_log("Get post error: " . $e->getMessage());
            return ['success' => false, 'message' => 'Failed to fetch post'];
        }
    }

    public function getUserPosts($userId) {
        try {
            $stmt = $this->conn->prepare(
                "SELECT * FROM {$this->table} 
                 WHERE user_id = ? 
                 ORDER BY created_at DESC"
            );
            
            $stmt->execute([$userId]);
            $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($posts as &$post) {
                $post['excerpt'] = $this->generateExcerpt($post['content']);
                $post['formatted_date'] = date('F j, Y', strtotime($post['created_at']));
            }

            return ['success' => true, 'data' => $posts];
        } catch (PDOException $e) {
            error_log("Get user posts error: " . $e->getMessage());
            return ['success' => false, 'message' => 'Failed to fetch your posts'];
        }
    }

    public function update($id, $userId, $title, $content) {
        try {
            // Verify ownership
            $stmt = $this->conn->prepare("SELECT user_id FROM {$this->table} WHERE id = ?");
            $stmt->execute([$id]);
            $post = $stmt->fetch();

            if (!$post) {
                return ['success' => false, 'message' => 'Post not found'];
            }

            if ($post['user_id'] != $userId) {
                return ['success' => false, 'message' => 'Unauthorized to edit this post'];
            }

            $stmt = $this->conn->prepare(
                "UPDATE {$this->table} 
                 SET title = ?, content = ?, updated_at = NOW() 
                 WHERE id = ?"
            );
            
            $stmt->execute([$title, $content, $id]);

            return ['success' => true, 'message' => 'Post updated successfully'];
        } catch (PDOException $e) {
            error_log("Update post error: " . $e->getMessage());
            return ['success' => false, 'message' => 'Failed to update post'];
        }
    }

    public function delete($id, $userId) {
        try {
            // Verify ownership
            $stmt = $this->conn->prepare("SELECT user_id FROM {$this->table} WHERE id = ?");
            $stmt->execute([$id]);
            $post = $stmt->fetch();

            if (!$post) {
                return ['success' => false, 'message' => 'Post not found'];
            }

            if ($post['user_id'] != $userId) {
                return ['success' => false, 'message' => 'Unauthorized to delete this post'];
            }

            $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE id = ?");
            $stmt->execute([$id]);

            return ['success' => true, 'message' => 'Post deleted successfully'];
        } catch (PDOException $e) {
            error_log("Delete post error: " . $e->getMessage());
            return ['success' => false, 'message' => 'Failed to delete post'];
        }
    }

    private function generateExcerpt($content, $length = 150) {
        $content = strip_tags($content);
        if (strlen($content) > $length) {
            $content = substr($content, 0, $length) . '...';
        }
        return $content;
    }
}
?>