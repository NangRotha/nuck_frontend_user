<?php
// /Applications/XAMPP/xamppfiles/htdocs/NUCKs/includes/erasmus_config.php
session_start();

class ErasmusDatabase {
    private $host = "localhost";
    private $db_name = "nuck_blog";
    private $username = "root";
    private $password = "";
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, 
                                  $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->exec("set names utf8mb4");
        } catch(PDOException $exception) {
            error_log("Connection error: " . $exception->getMessage());
            return null;
        }
        return $this->conn;
    }
}

// Helper functions
function getCurrentLanguage() {
    $uri = $_SERVER['REQUEST_URI'];
    if (strpos($uri, '/km/') !== false) {
        return 'kh';
    }
    return 'en';
}

function truncateText($text, $length = 150) {
    if (strlen($text) <= $length) return $text;
    return substr($text, 0, $length) . '...';
}

function formatDate($date, $lang = 'en') {
    if (!$date) return '';
    $timestamp = strtotime($date);
    if ($lang == 'kh') {
        $khmerMonths = ['មករា', 'កុម្ភៈ', 'មីនា', 'មេសា', 'ឧសភា', 'មិថុនា', 'កក្កដា', 'សីហា', 'កញ្ញា', 'តុលា', 'វិច្ឆិកា', 'ធ្នូ'];
        return date('d', $timestamp) . ' ' . $khmerMonths[date('n', $timestamp)-1] . ' ' . (date('Y', $timestamp)+543);
    }
    return date('d M Y', $timestamp);
}

function getProjectData() {
    $database = new ErasmusDatabase();
    $db = $database->getConnection();
    if (!$db) return null;
    
    try {
        $query = "SELECT * FROM erasmus_projects WHERE id = 1 LIMIT 1";
        $stmt = $db->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error fetching project data: " . $e->getMessage());
        return null;
    }
}

function getRecentPosts($limit = 3) {
    $database = new ErasmusDatabase();
    $db = $database->getConnection();
    if (!$db) return [];
    
    try {
        $query = "SELECT * FROM erasmus_blogs 
                  WHERE status = 'published' 
                  ORDER BY published_at DESC 
                  LIMIT :limit";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error fetching recent posts: " . $e->getMessage());
        return [];
    }
}

function getPostBySlug($slug) {
    $database = new ErasmusDatabase();
    $db = $database->getConnection();
    if (!$db) return null;
    
    try {
        // Update view count
        $updateQuery = "UPDATE erasmus_blogs SET views = views + 1 WHERE slug = :slug";
        $updateStmt = $db->prepare($updateQuery);
        $updateStmt->bindParam(':slug', $slug);
        $updateStmt->execute();
        
        $query = "SELECT * FROM erasmus_blogs WHERE slug = :slug AND status = 'published' LIMIT 1";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':slug', $slug);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error fetching post by slug: " . $e->getMessage());
        return null;
    }
}

function getPartners() {
    $database = new ErasmusDatabase();
    $db = $database->getConnection();
    if (!$db) return [];
    
    try {
        $query = "SELECT * FROM erasmus_partners WHERE project_id = 1 ORDER BY order_position ASC";
        $stmt = $db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error fetching partners: " . $e->getMessage());
        return [];
    }
}

function getUpdates() {
    $database = new ErasmusDatabase();
    $db = $database->getConnection();
    if (!$db) return [];
    
    try {
        $query = "SELECT * FROM erasmus_updates WHERE project_id = 1 ORDER BY update_date DESC LIMIT 5";
        $stmt = $db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error fetching updates: " . $e->getMessage());
        return [];
    }
}

function getCategories() {
    $database = new ErasmusDatabase();
    $db = $database->getConnection();
    if (!$db) return [];
    
    try {
        $query = "SELECT category, COUNT(*) as count FROM erasmus_blogs WHERE status = 'published' GROUP BY category";
        $stmt = $db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error fetching categories: " . $e->getMessage());
        return [];
    }
}

function getRelatedPosts($category, $current_id, $limit = 3) {
    $database = new ErasmusDatabase();
    $db = $database->getConnection();
    if (!$db) return [];
    
    try {
        $query = "SELECT * FROM erasmus_blogs 
                  WHERE status = 'published' AND category = :category AND id != :current_id 
                  ORDER BY published_at DESC LIMIT :limit";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':category', $category);
        $stmt->bindParam(':current_id', $current_id);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error fetching related posts: " . $e->getMessage());
        return [];
    }
}

function getComments($blog_id) {
    $database = new ErasmusDatabase();
    $db = $database->getConnection();
    if (!$db) return [];
    
    try {
        $query = "SELECT * FROM erasmus_comments WHERE blog_id = :blog_id AND is_approved = 1 ORDER BY created_at DESC";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':blog_id', $blog_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error fetching comments: " . $e->getMessage());
        return [];
    }
}

function addComment($blog_id, $name, $email, $comment) {
    $database = new ErasmusDatabase();
    $db = $database->getConnection();
    if (!$db) return false;
    
    try {
        $query = "INSERT INTO erasmus_comments (blog_id, name, email, comment) VALUES (:blog_id, :name, :email, :comment)";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':blog_id', $blog_id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':comment', $comment);
        return $stmt->execute();
    } catch (PDOException $e) {
        error_log("Error adding comment: " . $e->getMessage());
        return false;
    }
}

function getAllPosts($page = 1, $limit = 6) {
    $database = new ErasmusDatabase();
    $db = $database->getConnection();
    if (!$db) return ['posts' => [], 'totalPages' => 1];
    
    $offset = ($page - 1) * $limit;
    
    try {
        $totalQuery = "SELECT COUNT(*) as total FROM erasmus_blogs WHERE status = 'published'";
        $totalStmt = $db->prepare($totalQuery);
        $totalStmt->execute();
        $total = $totalStmt->fetch(PDO::FETCH_ASSOC)['total'];
        $totalPages = ceil($total / $limit);
        
        $query = "SELECT * FROM erasmus_blogs 
                  WHERE status = 'published' 
                  ORDER BY published_at DESC 
                  LIMIT :limit OFFSET :offset";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return ['posts' => $posts, 'totalPages' => $totalPages];
    } catch (PDOException $e) {
        error_log("Error fetching all posts: " . $e->getMessage());
        return ['posts' => [], 'totalPages' => 1];
    }
}

// Safe output function
function safe($data) {
    return htmlspecialchars($data ?? '', ENT_QUOTES, 'UTF-8');
}
?>