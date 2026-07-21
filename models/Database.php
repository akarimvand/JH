<?php
/**
 * کلاس اتصال به پایگاه داده
 * سیستم مدیریت باشگاه ورزشی
 */

class Database {
    private static $instance = null;
    private $connection;
    
    private function __construct() {
        $config = require __DIR__ . '/../config/database.php';
        
        try {
            $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['database']};charset={$config['charset']}";
            $this->connection = new PDO($dsn, $config['username'], $config['password'], $config['options']);
            
            // تنظیم Collation برای پشتیبانی از فارسی
            $this->connection->exec("SET NAMES {$config['charset']} COLLATE {$config['collation']}");
        } catch (PDOException $e) {
            die("خطا در اتصال به پایگاه داده: " . $e->getMessage());
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->connection;
    }
    
    // جلوگیری از کلون کردن
    private function __clone() {}
    
    // جلوگیری از unserialize
    public function __wakeup() {
        throw new Exception("Cannot unserialize singleton");
    }
}

/**
 * دریافت نمونه اتصال به دیتابیس
 * @return PDO
 */
function db() {
    return Database::getInstance()->getConnection();
}
