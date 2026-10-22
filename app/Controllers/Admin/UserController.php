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
    public function addUser(User $user): bool
    {
        try {
            $sql = "INSERT INTO users (username, password_hash, email, role, is_active) 
                VALUES (:username, :password_hash, :email, :role, :is_active)";
            $stmt = $this->conn->prepare($sql);
            $result = $stmt->execute([
                'username' => $user->getUsername(),
                'password_hash' => $user->getPasswordHash(),
                'email' => $user->getEmail(),
                'role' => $user->getRole(),
                'is_active' => $user->getIsActive() ? 1 : 0
            ]);
            return $result;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }
    public function updateUser(User $user): bool
    {
        $foundUser = $this->findById($user->getId());
        if (!$foundUser) {
            return false;
        }
        try {
            $sql = "UPDATE users 
                    SET username = :username, 
                        email = :email, 
                        role = :role, 
                        is_active = :is_active 
                    WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([
                'id' => $user->getId(),
                'username' => $user->getUsername(),
                'email' => $user->getEmail(),
                'role' => $user->getRole(),
                'is_active' => $user->getIsActive() ? 1 : 0
            ]);

        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }
    public function changePassword(int $id, string $newPassword): bool
    {
        if (trim($newPassword) === '') {
            return false;
        }

        $foundUser = $this->findById($id);
        if (!$foundUser) {
            return false;
        }

        try {
            $password_hash = password_hash($newPassword, PASSWORD_DEFAULT);
            $sql = "UPDATE users
                    SET password_hash = :password_hash
                    WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([
                'password_hash' => $password_hash,
                'id'            => $id
            ]);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }
}

?>