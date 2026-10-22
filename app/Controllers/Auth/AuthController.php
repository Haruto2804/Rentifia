<?php
namespace App\Controllers\Auth;

use App\Controllers\Admin\UserController;
require_once(__DIR__ . "/../Admin/UserController.php");
use PDOException;
class AuthController
{
    private UserController $userController;
    public function __construct()
    {

        $this->userController = new UserController();
    }
    public function showLoginForm()
    {
        require_once __DIR__ . '/../../Views/auth/login.php';
    }
    public function login()
    {

        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');
        if (!$username || !$password) {
            $_SESSION['flash_error'] = "Username và Password không được để trống!!";
            header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/Rentifia/app/Views/auth/login.php'));
            exit;
        }
        try {

            $user = $this->userController->findByUsername($username);
            if (!$user) {
                $_SESSION['flash_error'] = "Không có user trong hệ thống !!!";
                header("Location: /Rentifia/app/Views/auth/login.php");
                exit;
            }
            $result = password_verify($password, $user->getPasswordHash());
            if (!$result) {
                $_SESSION["flash_error"] = "Tên tài khoản hoặc username sai! Kiểm tra lại!";
                header("Location: /Rentifia/app/Views/auth/login.php");
                exit;
            }
            header("Location: /Rentifia/app/Views/index.html");
            exit;
        } catch (PDOException $e) {
            $_SESSION["flash_error"] = $e->getMessage();
            header("Location: /Rentifia/app/Views/auth/login.php");
            exit;
        }

    }
}
?>