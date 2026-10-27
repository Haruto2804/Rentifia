<?php
namespace App\Controllers\Auth;

use App\Controllers\Admin\UserController;
use App\Model\User;
use App\Services\MailService;
require_once(__DIR__ . "/../Admin/UserController.php");
require_once(__DIR__ . "/../../Models/User.php");
require_once(__DIR__ . "/../../Services/MailService.php");
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
    /**
     * BƯỚC 1: Kiểm tra tính hợp lệ của Form đăng ký, sinh OTP và chuyển sang trang nhập OTP
     */
    public function initiateRegister(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $confirmPassword = trim($_POST['confirm_password'] ?? '');

        // Lưu lại thông tin cũ để người dùng không phải nhập lại nếu lỗi
        $_SESSION['old_input'] = [
            'username' => $username,
            'email' => $email
        ];

        // 1. Kiểm tra không được để trống
        if (!$username || !$email || !$password || !$confirmPassword) {
            $_SESSION['flash_error'] = "Vui lòng điền đầy đủ tất cả các trường!";
            header("Location: /Rentifia/app/Views/auth/register.php");
            exit();
        }

        // 2. Kiểm tra Regex Username (3-20 ký tự, chữ cái, số, gạch dưới)
        if (!preg_match('/^[a-zA-Z0-9_]{3,20}$/', $username)) {
            $_SESSION['flash_error'] = "Tên đăng nhập từ 3 - 20 ký tự, chỉ gồm chữ cái, chữ số và gạch dưới (_)!";
            header("Location: /Rentifia/app/Views/auth/register.php");
            exit();
        }

        // 3. Kiểm tra Regex Email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['flash_error'] = "Địa chỉ email không đúng định dạng!";
            header("Location: /Rentifia/app/Views/auth/register.php");
            exit();
        }

        // 4. Kiểm tra Mật khẩu tối thiểu 8 ký tự và Regex (ít nhất 1 chữ cái và 1 chữ số)
        if (strlen($password) < 8 || !preg_match('/^(?=.*[A-Za-z])(?=.*\d).{8,}$/', $password)) {
            $_SESSION['flash_error'] = "Mật khẩu tối thiểu 8 ký tự, phải bao gồm cả chữ cái và chữ số!";
            header("Location: /Rentifia/app/Views/auth/register.php");
            exit();
        }

        // 5. Kiểm tra mật khẩu xác nhận
        if ($password !== $confirmPassword) {
            $_SESSION['flash_error'] = "Mật khẩu xác nhận không khớp!";
            header("Location: /Rentifia/app/Views/auth/register.php");
            exit();
        }

        try {
            // 6. Kiểm tra Username đã tồn tại trong CSDL chưa
            $foundUser = $this->userController->findByUsername($username);
            if ($foundUser) {
                $_SESSION['flash_error'] = "Tên đăng nhập này đã có người sử dụng. Vui lòng chọn tên khác!";
                header("Location: /Rentifia/app/Views/auth/register.php");
                exit();
            }

            // 7. Kiểm tra Email đã tồn tại trong CSDL chưa
            $foundEmail = $this->userController->findByEmail($email);
            if ($foundEmail) {
                $_SESSION['flash_error'] = "Địa chỉ email này đã được sử dụng bởi một tài khoản khác!";
                header("Location: /Rentifia/app/Views/auth/register.php");
                exit();
            }

            // 8. Lưu thông tin đăng ký chờ xác thực vào Session (mật khẩu đã hash an toàn)
            $_SESSION['pending_register'] = [
                'username' => $username,
                'email' => $email,
                'password_hash' => password_hash($password, PASSWORD_DEFAULT),
                'role' => 'tenant'
            ];

            // 9. Sinh mã OTP 6 số ngẫu nhiên
            $otpCode = (string) random_int(100000, 999999);

            // 10. Lưu thông tin OTP vào Session (hạn 5 phút)
            $_SESSION['register_otp'] = [
                'email' => $email,
                'code' => $otpCode,
                'expires_at' => time() + 300, // 5 phút
                'last_sent_at' => time(),
                'attempts' => 0
            ];

            // 11. Gửi email xác thực
            $sent = MailService::sendOtp($email, $otpCode);
            if (!$sent) {
                $_SESSION['flash_error'] = "Không thể gửi mã xác thực tới email của bạn. Vui lòng kiểm tra lại địa chỉ email hoặc thử lại!";
                header("Location: /Rentifia/app/Views/auth/register.php");
                exit();
            }

            // Xóa session input cũ khi thành công
            unset($_SESSION['old_input']);

            // Chuyển hướng sang trang nhập mã OTP
            header("Location: /Rentifia/app/Views/auth/verify_otp.php");
            exit();

        } catch (PDOException $e) {
            error_log($e->getMessage());
            $_SESSION['flash_error'] = "Lỗi hệ thống: " . $e->getMessage();
            header("Location: /Rentifia/app/Views/auth/register.php");
            exit();
        }
    }

    /**
     * BƯỚC 2: Kiểm tra mã OTP và hoàn tất lưu tài khoản vào CSDL
     */
    public function verifyOtp(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['pending_register']) || empty($_SESSION['register_otp'])) {
            $_SESSION['flash_error'] = "Phiên đăng ký đã hết hạn. Vui lòng thực hiện lại từ đầu!";
            header("Location: /Rentifia/app/Views/auth/register.php");
            exit();
        }

        $otpInput = trim($_POST['otp'] ?? '');

        if (!$otpInput || strlen($otpInput) !== 6) {
            $_SESSION['flash_error'] = "Vui lòng nhập đầy đủ mã OTP gồm 6 chữ số!";
            header("Location: /Rentifia/app/Views/auth/verify_otp.php");
            exit();
        }

        // Kiểm tra hết hạn (5 phút)
        if (time() > $_SESSION['register_otp']['expires_at']) {
            $_SESSION['flash_error'] = "Mã xác thực OTP đã hết hạn. Vui lòng nhấn gửi lại mã mới!";
            header("Location: /Rentifia/app/Views/auth/verify_otp.php");
            exit();
        }

        // Kiểm tra số lần nhập sai
        if (($_SESSION['register_otp']['attempts'] ?? 0) >= 5) {
            unset($_SESSION['register_otp']);
            $_SESSION['flash_error'] = "Bạn đã nhập sai mã OTP quá 5 lần. Vui lòng yêu cầu gửi mã mới!";
            header("Location: /Rentifia/app/Views/auth/verify_otp.php");
            exit();
        }

        // So khớp mã OTP
        if ($_SESSION['register_otp']['code'] !== $otpInput) {
            $_SESSION['register_otp']['attempts'] = ($_SESSION['register_otp']['attempts'] ?? 0) + 1;
            $remaining = 5 - $_SESSION['register_otp']['attempts'];
            $_SESSION['flash_error'] = "Mã OTP không chính xác. Bạn còn {$remaining} lần thử!";
            header("Location: /Rentifia/app/Views/auth/verify_otp.php");
            exit();
        }

        // MÃ HỢP LỆ -> LƯU VÀO DATABASE
        $pending = $_SESSION['pending_register'];

        try {
            // Kiểm tra lại tính duy nhất trước khi insert
            if ($this->userController->findByUsername($pending['username'])) {
                $_SESSION['flash_error'] = "Tên đăng nhập này đã tồn tại trong hệ thống!";
                header("Location: /Rentifia/app/Views/auth/register.php");
                exit();
            }

            if ($this->userController->findByEmail($pending['email'])) {
                $_SESSION['flash_error'] = "Email này đã được đăng ký tài khoản khác!";
                header("Location: /Rentifia/app/Views/auth/register.php");
                exit();
            }

            $newUser = new User();
            $newUser->setUsername($pending['username']);
            $newUser->setEmail($pending['email']);
            $newUser->setPasswordHash($pending['password_hash']);
            $newUser->setRole($pending['role'] ?? 'tenant');

            $success = $this->userController->addUser($newUser);

            if ($success) {
                $registeredUsername = $pending['username'];
                // Dọn dẹp session tạm
                unset($_SESSION['pending_register'], $_SESSION['register_otp'], $_SESSION['old_input']);

                $_SESSION['registered_username'] = $registeredUsername;
                $_SESSION['flash_success'] = "Chúc mừng bạn đã xác thực email và đăng ký thành công!";
                header("Location: /Rentifia/app/Views/auth/register_sucessful.php");
                exit();
            }

            $_SESSION['flash_error'] = "Lỗi khi lưu tài khoản vào cơ sở dữ liệu. Vui lòng thử lại!";
            header("Location: /Rentifia/app/Views/auth/verify_otp.php");
            exit();

        } catch (PDOException $e) {
            error_log($e->getMessage());
            $_SESSION['flash_error'] = "Lỗi hệ thống: " . $e->getMessage();
            header("Location: /Rentifia/app/Views/auth/verify_otp.php");
            exit();
        }
    }

    /**
     * API gửi lại mã OTP (hỗ trợ cả AJAX JSON hoặc Form submit)
     */
    public function resendOtp(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) || (isset($_POST['format']) && $_POST['format'] === 'json');

        if (empty($_SESSION['pending_register']['email'])) {
            if ($isAjax) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(['success' => false, 'message' => 'Phiên đăng ký đã hết hạn. Vui lòng quay lại đăng ký lại!']);
                exit;
            }
            $_SESSION['flash_error'] = "Phiên đăng ký đã hết hạn. Vui lòng đăng ký lại!";
            header("Location: /Rentifia/app/Views/auth/register.php");
            exit();
        }

        $email = $_SESSION['pending_register']['email'];

        // Kiểm tra cooldown 60s
        if (isset($_SESSION['register_otp']['last_sent_at'])) {
            $timeElapsed = time() - $_SESSION['register_otp']['last_sent_at'];
            if ($timeElapsed < 60) {
                $waitSec = 60 - $timeElapsed;
                if ($isAjax) {
                    header('Content-Type: application/json; charset=utf-8');
                    echo json_encode(['success' => false, 'message' => "Vui lòng đợi {$waitSec} giây trước khi gửi lại mã!"]);
                    exit;
                }
                $_SESSION['flash_error'] = "Vui lòng đợi {$waitSec} giây trước khi yêu cầu gửi lại mã!";
                header("Location: /Rentifia/app/Views/auth/verify_otp.php");
                exit();
            }
        }

        // Sinh mã mới
        $otpCode = (string) random_int(100000, 999999);
        $_SESSION['register_otp'] = [
            'email' => $email,
            'code' => $otpCode,
            'expires_at' => time() + 300,
            'last_sent_at' => time(),
            'attempts' => 0
        ];

        $sent = MailService::sendOtp($email, $otpCode);

        if ($isAjax) {
            header('Content-Type: application/json; charset=utf-8');
            if ($sent) {
                echo json_encode(['success' => true, 'message' => 'Mã xác thực mới đã được gửi về email của bạn!']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Không thể gửi email lúc này. Vui lòng thử lại!']);
            }
            exit;
        }

        if ($sent) {
            $_SESSION['flash_info'] = "Mã xác thực mới đã được gửi tới email của bạn!";
        } else {
            $_SESSION['flash_error'] = "Không thể gửi email lúc này. Vui lòng thử lại!";
        }
        header("Location: /Rentifia/app/Views/auth/verify_otp.php");
        exit();
    }

    // Giữ hàm registerAccount làm bí danh để tương thích
    public function registerAccount(): bool
    {
        $this->initiateRegister();
        return true;
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