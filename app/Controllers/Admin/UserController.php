<?php
namespace App\Controllers\Admin;

use App\Core\Database;
use App\Model\User;
use Exception;
use PDO;
use PDOException;
class UserController
{
    private ?PDO $conn = null;
    private User $user;

    public function __construct()
    {
        $this->conn = Database::getConnection();
        $this->user = new User();
    }

    public function getAll(): array
    {
        try {
            $stmt = $this->conn->query("SELECT * FROM users");
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $data ?: [];
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    public function findById(int $id): ?array
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM users WHERE id = :id LIMIT 1");
            $stmt->execute(['id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return null;
        }
    }
    public function deleteById(int $id): bool
    {
        $foundUser = $this->findById($id);
        if (!$foundUser) {
            return false;
        }
        try {
            $stmt = $this->conn->prepare("DELETE FROM users WHERE id = :id");
            return $stmt->execute(['id' => $id]);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }
}

?>