<?php

use App\Controllers\Auth\AuthController;
require_once(__DIR__ . "/../../Controllers/Auth/AuthController.php");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../Middlewares/AuthMiddleware.php';
\App\Middlewares\AuthMiddleware::requireGuest();

$errorMessage = $_SESSION['flash_error'] ?? '';
unset($_SESSION['flash_error']);

$successMessage = $_SESSION['flash_success'] ?? '';
unset($_SESSION['flash_success']);

$oldInput = $_SESSION['old_input'] ?? [];
unset($_SESSION['old_input']);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'register') {
    $authController = new AuthController();
    $authController->initiateRegister();
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
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap"
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
                <a href="/Rentifia/app/Views/index.php" class="flex items-center gap-2 group">
                    <span class="text-2xl font-extrabold tracking-tight text-slate-900 group-hover:opacity-90 transition-opacity">
                        Rent<span class="text-emerald-500">ifia</span>
                    </span>
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                </a>

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

                <!-- Hiển thị thông báo lỗi từ Session nếu có -->
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

                <!-- Form đăng ký -->
                <form id="registerForm" action="" method="POST" class="space-y-4" novalidate onsubmit="return validateRegisterForm(event)">
                    <input type="hidden" name="action" value="register" />

                    <!-- 1. Tên đăng nhập -->
                    <div>
                        <label for="username" class="sr-only">Tên đăng nhập</label>
                        <input type="text" name="username" id="username" required
                            value="<?= htmlspecialchars($oldInput['username'] ?? '') ?>"
                            placeholder="Tên đăng nhập (3-20 ký tự, vd: hoang_nam99)..."
                            class="w-full px-5 py-3.5 bg-slate-100/80 border border-transparent rounded-2xl text-sm font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all">
                        <p id="username-error-msg" class="hidden text-xs text-rose-500 font-medium px-2 pt-1"></p>
                    </div>

                    <!-- 2. Email -->
                    <div>
                        <label for="email" class="sr-only">Email</label>
                        <input type="email" name="email" id="email" required
                            value="<?= htmlspecialchars($oldInput['email'] ?? '') ?>"
                            placeholder="Địa chỉ email (vd: nam@example.com)..."
                            class="w-full px-5 py-3.5 bg-slate-100/80 border border-transparent rounded-2xl text-sm font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all">
                        <p id="email-error-msg" class="hidden text-xs text-rose-500 font-medium px-2 pt-1"></p>
                    </div>

                    <!-- 3. Mật khẩu & Thanh đánh giá độ mạnh -->
                    <div>
                        <div class="relative">
                            <label for="password" class="sr-only">Mật khẩu</label>
                            <input type="password" name="password" id="password" required
                                placeholder="Mật khẩu (tối thiểu 8 ký tự, có chữ & số)..."
                                class="w-full pl-5 pr-12 py-3.5 bg-slate-100/80 border border-transparent rounded-2xl text-sm font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all">

                            <!-- Toggle ẩn/hiện mật khẩu -->
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

                        <!-- THANH ĐÁNH GIÁ ĐỘ MẠNH MẬT KHẨU (PASSWORD STRENGTH METER) -->
                        <div id="password-strength-box" class="mt-2.5 px-1 space-y-1.5 transition-all">
                            <!-- 4 vạch chỉ báo -->
                            <div class="grid grid-cols-4 gap-1.5 h-1.5 w-full">
                                <div id="bar-1" class="h-full rounded-full bg-slate-200 transition-colors duration-300"></div>
                                <div id="bar-2" class="h-full rounded-full bg-slate-200 transition-colors duration-300"></div>
                                <div id="bar-3" class="h-full rounded-full bg-slate-200 transition-colors duration-300"></div>
                                <div id="bar-4" class="h-full rounded-full bg-slate-200 transition-colors duration-300"></div>
                            </div>
                            <!-- Nhãn văn bản & Gợi ý -->
                            <div class="flex items-center justify-between text-[11px] font-semibold">
                                <span id="strength-text" class="text-slate-400">Độ mạnh: Chưa nhập</span>
                                <span id="strength-hint" class="text-slate-400 font-normal">Tối thiểu 8 ký tự</span>
                            </div>
                        </div>

                        <!-- Thông báo lỗi mật khẩu -->
                        <p id="password-error-msg" class="hidden text-xs text-rose-500 font-medium px-2 pt-1"></p>
                    </div>

                    <!-- 4. Xác nhận mật khẩu -->
                    <div>
                        <div class="relative">
                            <label for="confirm_password" class="sr-only">Xác nhận mật khẩu</label>
                            <input type="password" name="confirm_password" id="confirm_password" required
                                placeholder="Nhập lại mật khẩu..."
                                class="w-full pl-5 pr-12 py-3.5 bg-slate-100/80 border border-transparent rounded-2xl text-sm font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all">

                            <!-- Toggle ẩn/hiện xác nhận mật khẩu -->
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
                        <p id="confirm-error-msg" class="hidden text-xs text-rose-500 font-medium px-2 pt-1"></p>
                    </div>

                    <!-- Điều khoản & chính sách -->
                    <div class="flex items-start gap-2 pt-1">
                        <input type="checkbox" id="terms" name="terms" required
                            class="mt-1 w-4 h-4 text-emerald-600 border-slate-300 rounded focus:ring-emerald-500 cursor-pointer">
                        <label for="terms" class="text-xs text-slate-600 leading-snug cursor-pointer select-none">
                            Tôi đồng ý với <a href="#" class="text-emerald-600 font-semibold hover:underline">Điều khoản
                                dịch vụ</a> và <a href="#" class="text-emerald-600 font-semibold hover:underline">Chính
                                sách bảo mật</a> của Rentifia.
                        </label>
                    </div>

                    <!-- Nút Đăng ký tài khoản (chuyển sang bước OTP) -->
                    <div class="pt-2">
                        <button type="submit" id="btn-submit"
                            class="w-full py-4 bg-slate-950 hover:bg-slate-800 text-white font-semibold text-sm rounded-2xl shadow-lg shadow-slate-950/20 transition-all active:scale-[0.99] flex items-center justify-center gap-2">
                            <span id="btn-submit-text">Đăng ký tài khoản</span>
                            <svg id="btn-submit-spinner" class="hidden animate-spin w-4 h-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                            </svg>
                            <svg id="btn-submit-arrow" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
            </div>
        </div>

        <!-- Cột phải: Banner hình ảnh & Điểm nổi bật (6/12) -->
        <div class="hidden lg:flex lg:col-span-6 xl:col-span-6 relative p-12 flex-col justify-end overflow-hidden">
            <!-- Hình nền minh họa -->
            <div class="absolute inset-0 bg-cover bg-center transition-transform duration-700 hover:scale-105"
                style="background-image: url('/Rentifia/public/assets/images/register_building.jpg');">
            </div>

            <!-- Lớp phủ Gradient mờ tối dần về đáy -->
            <div
                class="absolute inset-0 bg-gradient-to-t from-slate-950/90 via-slate-950/40 to-transparent pointer-events-none">
            </div>

            <!-- Nội dung nổi bật trên kính -->
            <div class="relative z-10">
                <div
                    class="glass-card rounded-[2rem] p-7 text-white shadow-2xl relative overflow-hidden backdrop-blur-xl">
                    <div
                        class="absolute -top-10 -right-10 w-32 h-32 bg-emerald-400/20 rounded-full blur-2xl pointer-events-none">
                    </div>

                    <span class="inline-block text-[11px] font-semibold text-emerald-300 uppercase tracking-wider mb-2">
                        Bắt đầu dễ dàng – Quản lý thảnh thơi
                    </span>

                    <h2 class="text-lg font-bold tracking-tight text-white mb-3 leading-snug">
                        Gia nhập Rentifia để số hóa toàn diện quy trình vận hành phòng trọ & căn hộ
                    </h2>

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

    <!-- Script tương tác, Regex Validation & Password Strength Meter -->
    <script>
        // 1. Hàm bật/tắt hiển thị mật khẩu
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

        // 2. HÀM KIỂM TRA ĐỘ MẠNH YẾU CỦA MẬT KHẨU
        function evaluatePasswordStrength(password) {
            if (!password || password.length === 0) {
                return { score: 0, text: 'Chưa nhập', color: 'text-slate-400', bgClass: 'bg-slate-200', hint: 'Tối thiểu 8 ký tự' };
            }

            let score = 0;
            const hasMinLen = password.length >= 8;
            const hasLower = /[a-z]/.test(password);
            const hasUpper = /[A-Z]/.test(password);
            const hasNumber = /[0-9]/.test(password);
            const hasSpecial = /[^A-Za-z0-9]/.test(password);

            if (hasMinLen) score++;
            if (hasLower && hasUpper) score++;
            if (hasNumber) score++;
            if (hasSpecial) score++;

            // Dưới 8 ký tự mặc định là Yếu
            if (!hasMinLen) {
                return {
                    score: 1,
                    text: 'Yếu',
                    color: 'text-rose-500',
                    bgClass: 'bg-rose-500',
                    hint: `Cần thêm ít nhất ${8 - password.length} ký tự`
                };
            }

            switch (score) {
                case 1:
                    return { score: 1, text: 'Yếu', color: 'text-rose-500', bgClass: 'bg-rose-500', hint: 'Nên có chữ hoa, thường & số' };
                case 2:
                    return { score: 2, text: 'Trung bình', color: 'text-amber-500', bgClass: 'bg-amber-500', hint: 'Thêm chữ hoa hoặc ký tự đặc biệt' };
                case 3:
                    return { score: 3, text: 'Khá mạnh', color: 'text-sky-500', bgClass: 'bg-sky-500', hint: 'Thêm ký tự đặc biệt (@, #, $)' };
                case 4:
                default:
                    return { score: 4, text: 'Rất mạnh', color: 'text-emerald-500', bgClass: 'bg-emerald-500', hint: 'Mật khẩu đạt độ bảo mật tối đa' };
            }
        }

        // 3. Cập nhật giao diện thanh độ mạnh mật khẩu theo thời gian thực
        const passwordInput = document.getElementById('password');
        const bar1 = document.getElementById('bar-1');
        const bar2 = document.getElementById('bar-2');
        const bar3 = document.getElementById('bar-3');
        const bar4 = document.getElementById('bar-4');
        const strengthText = document.getElementById('strength-text');
        const strengthHint = document.getElementById('strength-hint');

        passwordInput.addEventListener('input', function () {
            const val = this.value;
            const result = evaluatePasswordStrength(val);

            // Cập nhật nhãn
            strengthText.innerText = `Độ mạnh: ${result.text}`;
            strengthText.className = `${result.color} transition-colors`;
            strengthHint.innerText = result.hint;

            // Reset tất cả các vạch về mặc định xám
            [bar1, bar2, bar3, bar4].forEach(b => {
                b.className = 'h-full rounded-full bg-slate-200 transition-colors duration-300';
            });

            // Tô màu theo số điểm score
            if (result.score >= 1) bar1.className = `h-full rounded-full ${result.bgClass} transition-colors duration-300`;
            if (result.score >= 2) bar2.className = `h-full rounded-full ${result.bgClass} transition-colors duration-300`;
            if (result.score >= 3) bar3.className = `h-full rounded-full ${result.bgClass} transition-colors duration-300`;
            if (result.score >= 4) bar4.className = `h-full rounded-full ${result.bgClass} transition-colors duration-300`;

            // Xóa thông báo lỗi cũ nếu đã đạt yêu cầu
            const pwdRegex = /^(?=.*[A-Za-z])(?=.*\d).{8,}$/;
            if (pwdRegex.test(val)) {
                hideError('password-error-msg');
            }
        });

        // 4. Các Regex và hàm xác thực biểu thức chính quy phía Client
        const REGEX_PATTERNS = {
            // Tên đăng nhập: 3-20 ký tự, chỉ chữ cái, chữ số và dấu gạch dưới
            username: /^[a-zA-Z0-9_]{3,20}$/,
            // Email: chuẩn RFC cơ bản
            email: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
            // Mật khẩu: tối thiểu 8 ký tự, bao gồm ít nhất 1 chữ cái và 1 chữ số
            password: /^(?=.*[A-Za-z])(?=.*\d).{8,}$/
        };

        function showError(elementId, message) {
            const el = document.getElementById(elementId);
            if (el) {
                el.innerText = message;
                el.classList.remove('hidden');
            }
        }

        function hideError(elementId) {
            const el = document.getElementById(elementId);
            if (el) {
                el.classList.add('hidden');
                el.innerText = '';
            }
        }

        // Bắt sự kiện gõ phím để xóa lỗi tức thì
        document.getElementById('username').addEventListener('input', function () {
            if (REGEX_PATTERNS.username.test(this.value.trim())) {
                hideError('username-error-msg');
            }
        });

        document.getElementById('email').addEventListener('input', function () {
            if (REGEX_PATTERNS.email.test(this.value.trim())) {
                hideError('email-error-msg');
            }
        });

        document.getElementById('confirm_password').addEventListener('input', function () {
            if (this.value === passwordInput.value) {
                hideError('confirm-error-msg');
            }
        });

        // 5. Hàm Validate toàn bộ form trước khi gửi sang bước OTP
        function validateRegisterForm(e) {
            let isValid = true;

            const username = document.getElementById('username').value.trim();
            const email = document.getElementById('email').value.trim();
            const password = passwordInput.value;
            const confirmPassword = document.getElementById('confirm_password').value;

            // Kiểm tra Username bằng Regex
            if (!username) {
                showError('username-error-msg', '⚠️ Vui lòng nhập tên đăng nhập!');
                isValid = false;
            } else if (!REGEX_PATTERNS.username.test(username)) {
                showError('username-error-msg', '⚠️ Tên đăng nhập từ 3 - 20 ký tự, chỉ gồm chữ, số và dấu gạch dưới (_)!');
                isValid = false;
            } else {
                hideError('username-error-msg');
            }

            // Kiểm tra Email bằng Regex
            if (!email) {
                showError('email-error-msg', '⚠️ Vui lòng nhập địa chỉ email!');
                isValid = false;
            } else if (!REGEX_PATTERNS.email.test(email)) {
                showError('email-error-msg', '⚠️ Định dạng email không hợp lệ (ví dụ: nam@domain.com)!');
                isValid = false;
            } else {
                hideError('email-error-msg');
            }

            // Kiểm tra Mật khẩu tối thiểu 8 ký tự và Regex
            if (!password) {
                showError('password-error-msg', '⚠️ Vui lòng nhập mật khẩu!');
                isValid = false;
            } else if (password.length < 8) {
                showError('password-error-msg', '⚠️ Mật khẩu phải có tối thiểu 8 ký tự!');
                isValid = false;
            } else if (!REGEX_PATTERNS.password.test(password)) {
                showError('password-error-msg', '⚠️ Mật khẩu phải bao gồm cả chữ cái và chữ số!');
                isValid = false;
            } else {
                hideError('password-error-msg');
            }

            // Kiểm tra Xác nhận mật khẩu khớp nhau
            if (!confirmPassword) {
                showError('confirm-error-msg', '⚠️ Vui lòng nhập lại mật khẩu xác nhận!');
                isValid = false;
            } else if (password !== confirmPassword) {
                showError('confirm-error-msg', '⚠️ Mật khẩu xác nhận không khớp với mật khẩu trên!');
                isValid = false;
            } else {
                hideError('confirm-error-msg');
            }

            if (!isValid) {
                e.preventDefault();
                return false;
            }

            // Bật hiệu ứng loading trên nút Submit
            const btn = document.getElementById('btn-submit');
            const btnText = document.getElementById('btn-submit-text');
            const spinner = document.getElementById('btn-submit-spinner');
            const arrow = document.getElementById('btn-submit-arrow');

            btn.disabled = true;
            btn.classList.add('opacity-80', 'cursor-not-allowed');
            spinner.classList.remove('hidden');
            arrow.classList.add('hidden');
            btnText.innerText = "Đang kiểm tra & gửi mã OTP...";

            return true;
        }
    </script>
</body>

</html>