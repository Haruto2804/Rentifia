<?php

use App\Controllers\Auth\AuthController;
require_once(__DIR__ . "/../../Controllers/Auth/AuthController.php");
// Bật session để nhận các thông báo nếu sau này gắn logic
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$errorMessage = $_SESSION['flash_error'] ?? '';
unset($_SESSION['flash_error']);
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'register') {
    $authController = new AuthController();
    $authController->registerAccount();
    unset($_SESSION['flash_error']);
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rentifia - Đăng ký tài khoản</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .bg-register-pattern {
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
    class="bg-register-pattern min-h-screen flex items-center justify-center p-4 sm:p-6 md:p-10 text-slate-800 antialiased">
    <!-- Khung Card chính chia 2 cột -->
    <div
        class="w-full max-w-5xl bg-white rounded-[2.5rem] shadow-2xl shadow-emerald-950/15 overflow-hidden grid grid-cols-1 lg:grid-cols-12 min-h-[680px]">

        <!-- Cột trái: Form Đăng ký (6/12) -->
        <div class="lg:col-span-6 xl:col-span-6 p-8 sm:p-12 md:p-14 flex flex-col justify-between bg-white relative">
            <!-- Thanh trên cùng: Logo & Ngôn ngữ -->
            <div class="flex items-center justify-between mb-6 sm:mb-8">
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
                <div class="text-center mb-6">
                    <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight mb-2">
                        Tạo tài khoản
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-500 font-medium leading-relaxed px-4">
                        Bắt đầu trải nghiệm nền tảng quản lý phòng trọ & căn hộ thông minh Rentifia.
                    </p>
                </div>

                <!-- Hiển thị thông báo lỗi nếu có -->
                <?php if (!empty($errorMessage)): ?>
                    <div
                        class="mb-4 p-3.5 bg-rose-50 border border-rose-200 text-rose-700 text-xs rounded-2xl flex items-center gap-2.5">
                        <svg class="w-4 h-4 text-rose-500 flex-shrink-0" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span><?= htmlspecialchars($errorMessage) ?></span>
                    </div>
                <?php endif; ?>

                <!-- Hiển thị thông báo thành công nếu có -->
                <?php if (!empty($successMessage)): ?>
                    <div
                        class="mb-4 p-3.5 bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs rounded-2xl flex items-center gap-2.5">
                        <svg class="w-4 h-4 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span><?= htmlspecialchars($successMessage) ?></span>
                    </div>
                <?php endif; ?>

                <!-- Form đăng ký -->
                <form action="" method="POST" class="space-y-3.5">
                    <input type="hidden" name="action" value="register" />

                    <!-- Tên đăng nhập -->
                    <div>
                        <label for="username" class="sr-only">Tên đăng nhập</label>
                        <input type="text" name="username" id="username" required placeholder="Tên đăng nhập..."
                            class="w-full px-5 py-3.5 bg-slate-100/80 border-0 rounded-2xl text-sm font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-emerald-500/20 transition-all">
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="sr-only">Email</label>
                        <input type="email" name="email" id="email" required placeholder="Địa chỉ email..."
                            class="w-full px-5 py-3.5 bg-slate-100/80 border-0 rounded-2xl text-sm font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-emerald-500/20 transition-all">
                    </div>

                    <!-- Mật khẩu -->
                    <div class="relative">
                        <label for="password" class="sr-only">Mật khẩu</label>
                        <input type="password" name="password" id="password" required placeholder="Mật khẩu..."
                            class="w-full pl-5 pr-12 py-3.5 bg-slate-100/80 border-0 rounded-2xl text-sm font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-emerald-500/20 transition-all">

                        <!-- Toggle ẩn hiện mật khẩu -->
                        <button type="button" onclick="togglePasswordVisibility('password', 'eye-icon-pass')"
                            class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-slate-600 transition-colors">
                            <svg id="eye-icon-pass" class="w-5 h-5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>

                    <!-- Xác nhận mật khẩu -->
                    <div class="relative">
                        <label for="confirm_password" class="sr-only">Xác nhận mật khẩu</label>
                        <input type="password" name="confirm_password" id="confirm_password" required
                            placeholder="Nhập lại mật khẩu..."
                            class="w-full pl-5 pr-12 py-3.5 bg-slate-100/80 border-0 rounded-2xl text-sm font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-emerald-500/20 transition-all">

                        <!-- Toggle ẩn hiện xác nhận mật khẩu -->
                        <button type="button" onclick="togglePasswordVisibility('confirm_password', 'eye-icon-confirm')"
                            class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-slate-600 transition-colors">
                            <svg id="eye-icon-confirm" class="w-5 h-5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>

                    <!-- Điều khoản & chính sách -->
                    <div class="flex items-start gap-2 pt-1">
                        <input type="checkbox" id="terms" name="terms" required
                            class="mt-1 w-4 h-4 text-emerald-600 border-slate-300 rounded focus:ring-emerald-500">
                        <label for="terms" class="text-xs text-slate-600 leading-snug">
                            Tôi đồng ý với <a href="#" class="text-emerald-600 font-semibold hover:underline">Điều khoản
                                dịch vụ</a> và <a href="#" class="text-emerald-600 font-semibold hover:underline">Chính
                                sách bảo mật</a> của Rentifia.
                        </label>
                    </div>

                    <!-- Nút Đăng ký -->
                    <div class="pt-2">
                        <button type="submit"
                            class="w-full py-4 bg-slate-950 hover:bg-slate-800 text-white font-semibold text-sm rounded-2xl shadow-lg shadow-slate-950/20 transition-all active:scale-[0.99] flex items-center justify-center gap-2">
                            <span>Đăng ký tài khoản</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </button>
                    </div>

                    <!-- Chuyển hướng đến Đăng nhập -->
                    <div class="text-center pt-2">
                        <p class="text-xs text-slate-500 font-medium">
                            Đã có tài khoản?
                            <a href="/Rentifia/app/Views/auth/login.php"
                                class="text-emerald-600 hover:text-emerald-700 font-semibold hover:underline transition-colors">
                                Đăng nhập ngay
                            </a>
                        </p>
                    </div>
                </form>
            </div>

            <!-- Chân trang: Liên hệ hỗ trợ -->
            <div class="mt-6 text-center pt-2">
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
        <div class="hidden lg:block lg:col-span-6 xl:col-span-6 relative bg-slate-100 overflow-hidden min-h-[680px]">
            <!-- Ảnh nền căn hộ có cây xanh và ánh nắng -->
            <img src="/Rentifia/public/assets/images/login_banner.jpg" alt="Rentifia Modern Apartment Interior"
                class="absolute inset-0 w-full h-full object-cover object-center transform scale-105 hover:scale-100 transition-transform duration-1000 ease-out">

            <!-- Lớp phủ tương phản nhẹ -->
            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/50 via-slate-950/20 to-transparent"></div>

            <!-- Hộp Kính Mờ Glassmorphism nổi bật -->
            <div class="absolute inset-x-8 bottom-10 z-10">
                <div
                    class="glass-card rounded-[2rem] p-7 text-white shadow-2xl relative overflow-hidden backdrop-blur-xl">
                    <!-- Ánh sáng dịu nhẹ -->
                    <div
                        class="absolute -top-10 -right-10 w-32 h-32 bg-emerald-400/20 rounded-full blur-2xl pointer-events-none">
                    </div>

                    <!-- Badge nổi bật phía trên tiêu đề -->
                    <span class="inline-block text-[11px] font-semibold text-emerald-300 uppercase tracking-wider mb-2">
                        Bắt đầu dễ dàng – Quản lý thảnh thơi
                    </span>

                    <h2 class="text-lg font-bold tracking-tight text-white mb-3 leading-snug">
                        Gia nhập Rentifia để số hóa toàn diện quy trình vận hành phòng trọ & căn hộ
                    </h2>

                    <!-- Điểm nổi bật -->
                    <ul class="space-y-2 text-xs text-white/80 font-medium">
                        <li class="flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                            Quản lý hợp đồng & người thuê phòng tức thì
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                            Chốt chỉ số điện/nước kèm ảnh đối chứng thực tế
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                            Tự động hóa hóa đơn và thanh toán nhanh qua VietQR
                        </li>
                    </ul>
                </div>
            </div>
        </div>

    </div>

    <!-- Script ẩn/hiện mật khẩu -->
    <script>
        function togglePasswordVisibility(inputId, iconId) {
            const passwordInput = document.getElementById(inputId);
            const eyeIcon = document.getElementById(iconId);

            if (!passwordInput || !eyeIcon) return;

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