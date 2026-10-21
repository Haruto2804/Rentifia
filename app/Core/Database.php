<?php

namespace App\Core;

use PDO;
use PDOException;

class Database
{
    // 1. Chỉ được khai báo thuộc tính ở đây (phải có private/protected/public)
    private static ?PDO $instance = null;

    // 2. Khóa hàm khởi tạo để không ai dùng "new Database()" được
    private function __construct()
    {
    }
    private function __clone()
    {
    }

    // 3. Toàn bộ logic gán biến và kết nối phải nằm TRONG HÀM này
    public static function getConnection(): PDO
    {
        if (self::$instance === null) {
            // Đưa các biến vào trong hàm -> Hoàn toàn hợp lệ!
            $host = $_ENV['DB_HOST'] ?? '127.0.0.1';
            $port = $_ENV['DB_PORT'] ?? '3306';
            $dbname = $_ENV['DB_DATABASE'] ?? 'qlnt';
            $username = $_ENV['DB_USERNAME'] ?? 'root';
            $password = $_ENV['DB_PASSWORD'] ?? '';
            $charset = $_ENV['DB_CHARSET'] ?? 'utf8mb4';

            $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset={$charset}";

            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];

            try {
                self::$instance = new PDO($dsn, $username, $password, $options);
            } catch (PDOException $e) {
                die("Lỗi kết nối CSDL: " . $e->getMessage());
            }
        }

        return self::$instance;
    }
}
