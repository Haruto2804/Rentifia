<?php
// 1. Phải bật session thì mới đọc được $_SESSION
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

use App\Controllers\Auth\AuthController;

require_once __DIR__ . '/../../Controllers/Auth/AuthController.php';

$errorMessage = $_SESSION['flash_error'] ?? '';
unset($_SESSION['flash_error']); // Lấy xong thì xóa đi để F5 không bị hiện lại

if ($_SERVER['REQUEST_METHOD'] == 'POST' && ($_POST['action'] ?? '') === 'login') {
    $authController = new AuthController();
    $authController->login();
}
?>


<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rentifia - Đăng nhập hệ thống</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .bg-login-pattern {
            background-color: #e5eee8;
            background-image: url('/Rentifia/public/assets/images/login_bg.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.22);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.35);
        }
    </style>
</head>

<body
    class="bg-login-pattern min-h-screen flex items-center justify-center p-4 sm:p-6 md:p-10 text-slate-800 antialiased">
    <!-- Khung Card chính chia 2 cột -->
    <div
        class="w-full max-w-5xl bg-white rounded-[2.5rem] shadow-2xl shadow-emerald-950/15 overflow-hidden grid grid-cols-1 lg:grid-cols-12 min-h-[640px]">

        <!-- Cột trái: Form Đăng nhập (6/12) -->
        <div class="lg:col-span-6 xl:col-span-6 p-8 sm:p-12 md:p-14 flex flex-col justify-between bg-white relative">
            <!-- Thanh trên cùng: Logo & Ngôn ngữ -->
            <div class="flex items-center justify-between mb-8 sm:mb-12">
                <!-- Logo -->
                <div class="flex items-center gap-2">
                    <span class="text-2xl font-extrabold tracking-tight text-slate-900">
                        Rent<span class="text-emerald-500">ifia</span>
                    </span>
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                </div>

                <!-- Đổi ngôn ngữ -->
                <div
                    class="flex items-center gap-1.5 text-xs font-semibold text-slate-600 bg-slate-50 px-3 py-1.5 rounded-full border border-slate-100 hover:bg-slate-100 transition-colors cursor-pointer">
                    <span class="text-sm">🇻🇳</span>
                    <span>Vi</span>
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>
            </div>

            <!-- Khu vực chính: Tiêu đề & Form -->
            <div class="max-w-md w-full mx-auto my-auto">
                <div class="text-center mb-8">
                    <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight mb-2">
                        Hello!
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-500 font-medium leading-relaxed px-4">
                        Để đăng nhập vào tài khoản, vui lòng nhập Email/Tên đăng nhập và Mật khẩu của bạn.
                    </p>
                </div>

                <!-- Hiển thị thông báo lỗi nếu có -->
                <?php if (isset($errorMessage) && !empty($errorMessage)): ?>
                    <div
                        class="mb-5 p-3.5 bg-rose-50 border border-rose-200 text-rose-700 text-xs rounded-2xl flex items-center gap-2.5">
                        <svg class="w-4 h-4 text-rose-500 flex-shrink-0" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>
                            <?= htmlspecialchars($errorMessage) ?>
                        </span>
                    </div>
                <?php endif; ?>

                <!-- Form đăng nhập -->
                <form action="" method="POST" class="space-y-4">
                    <input type="hidden" name="action" value="login" />
                    <!-- Tên đăng nhập hoặc Email -->
                    <div>
                        <label for="username" class="sr-only">Tên đăng nhập hoặc Email</label>
                        <input type="text" name="username" id="username" required
                            value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
                            placeholder="Tên đăng nhập hoặc Email..."
                            class="w-full px-5 py-4 bg-slate-100/80 border-0 rounded-2xl text-sm font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-emerald-500/20 transition-all">
                    </div>

                    <!-- Mật khẩu -->
                    <div class="relative">
                        <label for="password" class="sr-only">Mật khẩu</label>
                        <input type="password" name="password" id="password" required placeholder="Mật khẩu của bạn..."
                            class="w-full pl-5 pr-12 py-4 bg-slate-100/80 border-0 rounded-2xl text-sm font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-emerald-500/20 transition-all">

                        <!-- Toggle ẩn hiện mật khẩu -->
                        <button type="button" onclick="togglePasswordVisibility()"
                            class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-slate-600 transition-colors">
                            <svg id="eye-icon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                    <!-- Quên mật khẩu link -->
                    <div class="flex items-center justify-start pt-1">
                        <a href="#"
                            class="text-xs font-semibold text-emerald-600 hover:text-emerald-700 transition-colors">
                            Quên mật khẩu?
                        </a>
                    </div>

                    <!-- Nút Đăng nhập -->
                    <div class="pt-2">
                        <button type="submit"
                            class="w-full py-4 bg-slate-950 hover:bg-slate-800 text-white font-semibold text-sm rounded-2xl shadow-lg shadow-slate-950/20 transition-all active:scale-[0.99] flex items-center justify-center gap-2">
                            <span>Đăng nhập</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Chân trang: Liên hệ hỗ trợ -->
            <div class="mt-8 text-center pt-4">
                <p class="text-xs text-slate-500 font-medium">
                    Cần hỗ trợ vận hành? Liên hệ chúng tôi tại
                    <a href="mailto:support@rentifia.vn" class="text-emerald-600 hover:underline font-semibold">
                        support@rentifia.vn
                    </a>
                </p>
                <p class="text-[11px] text-slate-400 mt-2 font-mono">
                    All rights reserved rentifia tech 2026
                </p>
            </div>
        </div>

        <!-- Cột phải: Hình ảnh & Kính mờ Glassmorphism (6/12) -->
        <div class="hidden lg:block lg:col-span-6 xl:col-span-6 relative bg-slate-100 overflow-hidden min-h-[640px]">
            <!-- Ảnh nền căn hộ có cây xanh và ánh nắng -->
            <img src="/Rentifia/public/assets/images/login_banner.jpg" alt="Rentifia Modern Apartment Interior"
                class="absolute inset-0 w-full h-full object-cover object-center transform scale-105 hover:scale-100 transition-transform duration-1000 ease-out">

            <!-- Lớp phủ tương phản nhẹ -->
            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/40 via-transparent to-transparent"></div>

            <!-- Hộp Kính Mờ Glassmorphism nổi bật -->
            <div class="absolute inset-x-8 bottom-12 z-10">
                <div
                    class="glass-card rounded-[2rem] p-7 text-white shadow-2xl relative overflow-hidden backdrop-blur-xl">
                    <!-- Ánh sáng dịu nhẹ -->
                    <div
                        class="absolute -top-10 -right-10 w-32 h-32 bg-emerald-400/20 rounded-full blur-2xl pointer-events-none">
                    </div>

                    <!-- Vòng xoay động (Indicator) -->
                    <div class="w-8 h-8 rounded-full border-2 border-white/60 border-t-transparent animate-spin mb-4">
                    </div>

                    <!-- Tiêu đề -->
                    <!-- Dạng badge nổi bật phía trên tiêu đề -->
                    <span class="inline-block text-[11px] font-semibold text-emerald-300 uppercase tracking-wider mb-1">
                        Vận hành tự động – Quản lý thảnh thơi
                    </span>

                    <h2 class="text-lg font-bold tracking-tight text-white mb-2 leading-snug">
                        Hệ thống vận hành thông minh chuỗi nhà trọ & căn hộ dịch vụ
                    </h2>
                </div>
            </div>
        </div>

    </div>

    <!-- Script ẩn/hiện mật khẩu -->
    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                `;
            } else {
                passwordInput.type = 'password';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                `;
            }
        }
    </script>
</body>

</html>