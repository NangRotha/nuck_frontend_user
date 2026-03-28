<?php
// config.php — database connection via PDO

define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'nuck_blog');
define('DB_USER', 'root');
define('DB_PASS', ''); // Set your password, e.g., 'root' in some setups

define('DB_DSN', 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4');

function get_pdo(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        try {
            $pdo = new PDO(DB_DSN, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            // Avoid leaking DB credentials in error messages
            http_response_code(500);
            exit('Database connection failed. Please check config.php.');
        }
    }
    return $pdo;
}
