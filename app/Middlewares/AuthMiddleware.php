<?php
namespace App\Middlewares;

class AuthMiddleware
{

    public static function requireRole(string|array $allowedRoles)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        // 1. Kiểm tra trạng thái đăng nhập
        if (empty($_SESSION['logged_in']) || empty($_SESSION['role'])) {
            $_SESSION['flash_error'] = "Vui lòng đăng nhập để truy cập trang này!";
            header("Location: /Rentifia/app/Views/auth/login.php");
            exit;
        }

        // 2. Chuẩn hóa danh sách các role được cấp quyền
        $roles = is_array($allowedRoles) ? $allowedRoles : [$allowedRoles];

        // 3. Kiểm tra xem role của user hiện tại có nằm trong danh sách được phép hay không
        if (!in_array($_SESSION['role'], $roles, true)) {
            header("Location: /Rentifia/app/Views/unauthorized.php");
            exit;
        }
    }

    /**
     * Dành cho các trang công khai như Login, Register:
     * Nếu user đã đăng nhập rồi thì tự động đưa về Dashboard phù hợp, không cho vào trang login nữa.
     */
    public static function requireGuest(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!empty($_SESSION['logged_in']) && !empty($_SESSION['role'])) {
            $redirectUrl = match ($_SESSION['role']) {
                'admin' => '/Rentifia/app/Views/admin/dashboard/index.php',
                'manager' => '/Rentifia/app/Views/manager/dashboard/index.php',
                'tenant' => '/Rentifia/app/Views/tenant/dashboard/index.php',
                default => null,
            };

            if ($redirectUrl) {
                header("Location: " . $redirectUrl);
                exit;
            }
        }
    }
}
