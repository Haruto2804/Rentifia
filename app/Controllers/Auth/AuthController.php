<?php
namespace App\Controllers\Auth;

use App\Controllers\Admin\UserController;
use App\Model\User;
require_once(__DIR__ . "/../Admin/UserController.php");
require_once(__DIR__ . "/../../Models/User.php");
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
                $_SESSION['flash_error'] = "Tài khoản không tồn tại trong hệ thống!";
                header("Location: /Rentifia/app/Views/auth/login.php");
                exit;
            }

            if (!$user->getIsActive()) {
                $_SESSION['flash_error'] = "Tài khoản của bạn đang bị tạm khóa. Vui lòng liên hệ quản trị viên!";
                header("Location: /Rentifia/app/Views/auth/login.php");
                exit;
            }

            $result = password_verify($password, $user->getPasswordHash());
            if (!$result) {
                $_SESSION["flash_error"] = "Mật khẩu không chính xác. Vui lòng kiểm tra lại!";
                header("Location: /Rentifia/app/Views/auth/login.php");
                exit;
            }

            // 1. Khởi tạo session an toàn
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user->getId();
            $_SESSION['username'] = $user->getUsername();
            $_SESSION['email'] = $user->getEmail();
            $_SESSION['role'] = $user->getRole();
            $_SESSION['logged_in'] = true;

            // 2. Phân luồng điều hướng theo Role đến từng Dashboard chuyên biệt
            $redirectUrl = match ($user->getRole()) {
                'admin' => '/Rentifia/app/Views/admin/dashboard/index.php',
                'manager' => '/Rentifia/app/Views/manager/dashboard/index.php',
                'tenant' => '/Rentifia/app/Views/tenant/dashboard/index.php',
                default => '/Rentifia/app/Views/auth/login.php',
            };

            header("Location: " . $redirectUrl);
            exit;
        } catch (PDOException $e) {
            $_SESSION["flash_error"] = $e->getMessage();
            header("Location: /Rentifia/app/Views/auth/login.php");
            exit;
        }

    }
    public function registerAccount(): bool
    {
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $confirmPassword = trim($_POST['confirm_password'] ?? '');

        // 1. Kiểm tra các trường bắt buộc
        if (!$username || !$email || !$password || !$confirmPassword) {
            $_SESSION['flash_error'] = "Vui lòng điền đầy đủ tất cả các trường!";
            header("Location: /Rentifia/app/Views/auth/register.php");
            exit();
        }

        // 2. Kiểm tra định dạng email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['flash_error'] = "Địa chỉ email không hợp lệ!";
            header("Location: /Rentifia/app/Views/auth/register.php");
            exit();
        }

        // 3. Kiểm tra mật khẩu khớp nhau
        if ($password !== $confirmPassword) {
            $_SESSION['flash_error'] = "Mật khẩu xác nhận không khớp!";
            header("Location: /Rentifia/app/Views/auth/register.php");
            exit();
        }

        try {
            // 4. Kiểm tra tài khoản đã tồn tại chưa
            $foundUser = $this->userController->findByUsername($username);
            if ($foundUser) {
                $_SESSION['flash_error'] = "Tên tài khoản này đã tồn tại trong hệ thống!";
                header("Location: /Rentifia/app/Views/auth/register.php");
                exit();
            }

            // 5. Khởi tạo đối tượng User và mã hóa mật khẩu
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $newUser = new User();
            $newUser->setUsername($username);
            $newUser->setEmail($email);
            $newUser->setPasswordHash($passwordHash);
            $newUser->setRole('tenant');

            // 6. Lưu vào DB
            $success = $this->userController->addUser($newUser);
            if ($success) {
                $_SESSION['registered_username'] = $username;
                $_SESSION['flash_success'] = "Chúc mừng bạn đã đăng ký tài khoản thành công!";
                header("Location: /Rentifia/app/Views/auth/register_sucessful.php");
                exit();
            }

            $_SESSION['flash_error'] = "Đăng ký không thành công. Vui lòng thử lại!";
            header("Location: /Rentifia/app/Views/auth/register.php");
            exit();

        } catch (PDOException $e) {
            error_log($e->getMessage());
            $_SESSION['flash_error'] = "Lỗi hệ thống: " . $e->getMessage();
            header("Location: /Rentifia/app/Views/auth/register.php");
            exit();
        }
    }

    public function logout(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }
        session_destroy();

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['flash_success'] = "Bạn đã đăng xuất khỏi hệ thống thành công!";
        header("Location: /Rentifia/app/Views/auth/login.php");
        exit();
    }
}
?>