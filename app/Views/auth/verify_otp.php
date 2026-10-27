<?php

use App\Controllers\Auth\AuthController;
require_once(__DIR__ . "/../../Controllers/Auth/AuthController.php");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../Middlewares/AuthMiddleware.php';
\App\Middlewares\AuthMiddleware::requireGuest();

// Nếu không có phiên đăng ký đang chờ, chuyển hướng về trang đăng ký
if (empty($_SESSION['pending_register']) || empty($_SESSION['register_otp'])) {
    header("Location: /Rentifia/app/Views/auth/register.php");
    exit();
}

$pendingEmail = $_SESSION['pending_register']['email'] ?? '';
$pendingUsername = $_SESSION['pending_register']['username'] ?? '';

$errorMessage = $_SESSION['flash_error'] ?? '';
unset($_SESSION['flash_error']);

$infoMessage = $_SESSION['flash_info'] ?? '';
unset($_SESSION['flash_info']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $authController = new AuthController();

    if ($action === 'verify_otp') {
        $authController->verifyOtp();
    } elseif ($action === 'resend_otp') {
        $authController->resendOtp();
    }
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rentifia - Xác thực mã OTP</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@500;600;700;800&display=swap"
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

        /* Hiệu ứng nhấp nháy con trỏ Caret chuẩn shadcn/ui */
        @keyframes caret-blink {
            0%, 70%, 100% { opacity: 0; }
            20%, 50% { opacity: 1; }
        }
        .animate-caret-blink {
            animation: caret-blink 1.2s ease-out infinite;
        }
    </style>
</head>

<body
    class="bg-register-pattern min-h-screen flex items-center justify-center p-4 sm:p-6 md:p-10 text-slate-800 antialiased">
    <!-- Khung Card chính chia 2 cột đồng bộ giao diện toàn hệ thống -->
    <div
        class="w-full max-w-5xl bg-white rounded-[2.5rem] shadow-2xl shadow-emerald-950/15 overflow-hidden grid grid-cols-1 lg:grid-cols-12 min-h-[680px]">

        <!-- Cột trái: Giao diện nhập OTP shadcn/ui (6/12) -->
        <div class="lg:col-span-6 xl:col-span-6 p-8 sm:p-12 md:p-14 flex flex-col justify-between bg-white relative">
            <!-- Thanh trên cùng: Logo & Đổi ngôn ngữ -->
            <div class="flex items-center justify-between mb-6 sm:mb-8">
                <!-- Logo -->
                <a href="/Rentifia/app/Views/index.php" class="flex items-center gap-2 group">
                    <span class="text-2xl font-extrabold tracking-tight text-slate-900 group-hover:opacity-90 transition-opacity">
                        Rent<span class="text-emerald-500">ifia</span>
                    </span>
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                </a>

                <!-- Nút quay lại đăng ký -->
                <a href="/Rentifia/app/Views/auth/register.php"
                    class="flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-slate-800 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span>Quay lại</span>
                </a>
            </div>

            <!-- Khu vực trung tâm: Form nhập OTP phong cách shadcn/ui -->
            <div class="max-w-md w-full mx-auto my-auto text-center">
                <!-- Icon Huy hiệu phong thư bảo mật -->
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-emerald-50 border border-emerald-100 text-emerald-600 mb-5 shadow-sm">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>

                <!-- Tiêu đề & Thông điệp -->
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight mb-2">
                    Xác thực mã OTP
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 font-medium leading-relaxed mb-1">
                    Mã xác thực gồm 6 chữ số vừa được gửi đến email:
                </p>
                <div class="inline-flex items-center gap-1.5 px-3 py-1 bg-slate-100 text-slate-800 text-xs font-bold rounded-full mb-6">
                    <span><?= htmlspecialchars($pendingEmail) ?></span>
                    <a href="/Rentifia/app/Views/auth/register.php" class="text-emerald-600 hover:text-emerald-700 font-medium text-[11px] underline ml-1" title="Đổi email">Đổi</a>
                </div>

                <!-- Thông báo lỗi (nếu có) -->
                <?php if (!empty($errorMessage)): ?>
                    <div
                        class="mb-5 p-3 bg-rose-50 border border-rose-200 text-rose-700 text-xs rounded-2xl flex items-center justify-center gap-2">
                        <svg class="w-4 h-4 text-rose-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span><?= htmlspecialchars($errorMessage) ?></span>
                    </div>
                <?php endif; ?>

                <!-- Thông báo thông tin (nếu có) -->
                <?php if (!empty($infoMessage)): ?>
                    <div
                        class="mb-5 p-3 bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs rounded-2xl flex items-center justify-center gap-2">
                        <svg class="w-4 h-4 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span><?= htmlspecialchars($infoMessage) ?></span>
                    </div>
                <?php endif; ?>

                <!-- FORM NHẬP OTP (SHADCN/UI INPUT-OTP STYLE) -->
                <form id="otpForm" action="" method="POST" class="space-y-6">
                    <input type="hidden" name="action" value="verify_otp" />
                    <!-- Input ẩn chứa chuỗi 6 ký tự để submit form -->
                    <input type="hidden" name="otp" id="fullOtp" value="" />

                    <!-- KHỐI SLOTS SHADCN UI: 3 Ô - DẤU GẠCH NỐI - 3 Ô -->
                    <div class="flex items-center justify-center gap-2 sm:gap-3 py-2">
                        <!-- Nhóm 1: 3 Slot đầu -->
                        <div class="flex items-center rounded-2xl shadow-sm border border-slate-200 bg-slate-50/50 overflow-hidden divide-x divide-slate-200 focus-within:border-emerald-500 focus-within:ring-4 focus-within:ring-emerald-500/15 transition-all">
                            <input type="text" inputmode="numeric" maxlength="1" data-index="0"
                                class="otp-slot w-11 h-14 sm:w-12 sm:h-16 text-center font-mono text-2xl font-bold bg-transparent text-slate-900 focus:outline-none focus:bg-white transition-all select-none" />
                            <input type="text" inputmode="numeric" maxlength="1" data-index="1"
                                class="otp-slot w-11 h-14 sm:w-12 sm:h-16 text-center font-mono text-2xl font-bold bg-transparent text-slate-900 focus:outline-none focus:bg-white transition-all select-none" />
                            <input type="text" inputmode="numeric" maxlength="1" data-index="2"
                                class="otp-slot w-11 h-14 sm:w-12 sm:h-16 text-center font-mono text-2xl font-bold bg-transparent text-slate-900 focus:outline-none focus:bg-white transition-all select-none" />
                        </div>

                        <!-- Ký tự phân cách kiểu shadcn/ui InputOTPSeparator -->
                        <div class="text-slate-300 font-bold text-lg select-none px-0.5">
                            <svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M20 12H4" />
                            </svg>
                        </div>

                        <!-- Nhóm 2: 3 Slot sau -->
                        <div class="flex items-center rounded-2xl shadow-sm border border-slate-200 bg-slate-50/50 overflow-hidden divide-x divide-slate-200 focus-within:border-emerald-500 focus-within:ring-4 focus-within:ring-emerald-500/15 transition-all">
                            <input type="text" inputmode="numeric" maxlength="1" data-index="3"
                                class="otp-slot w-11 h-14 sm:w-12 sm:h-16 text-center font-mono text-2xl font-bold bg-transparent text-slate-900 focus:outline-none focus:bg-white transition-all select-none" />
                            <input type="text" inputmode="numeric" maxlength="1" data-index="4"
                                class="otp-slot w-11 h-14 sm:w-12 sm:h-16 text-center font-mono text-2xl font-bold bg-transparent text-slate-900 focus:outline-none focus:bg-white transition-all select-none" />
                            <input type="text" inputmode="numeric" maxlength="1" data-index="5"
                                class="otp-slot w-11 h-14 sm:w-12 sm:h-16 text-center font-mono text-2xl font-bold bg-transparent text-slate-900 focus:outline-none focus:bg-white transition-all select-none" />
                        </div>
                    </div>

                    <!-- Gợi ý hoặc thông báo trạng thái gửi lại mã -->
                    <p id="resend-status-msg" class="text-xs text-slate-500 font-medium"></p>

                    <!-- Nút Xác nhận -->
                    <button type="submit" id="btn-verify"
                        class="w-full py-4 bg-slate-950 hover:bg-slate-800 text-white font-semibold text-sm rounded-2xl shadow-lg shadow-slate-950/20 transition-all active:scale-[0.99] flex items-center justify-center gap-2">
                        <span id="btn-verify-text">Xác nhận tài khoản</span>
                        <svg id="btn-verify-spinner" class="hidden animate-spin w-4 h-4 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                        </svg>
                        <svg id="btn-verify-icon" class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </button>

                    <!-- Khu vực Gửi lại mã & Đếm ngược 60 giây -->
                    <div class="pt-2">
                        <p class="text-xs text-slate-500 font-medium">
                            Chưa nhận được mã?
                            <button type="button" id="btn-resend" onclick="handleResendOtp()"
                                class="text-emerald-600 hover:text-emerald-700 font-bold hover:underline transition-colors ml-1 inline-flex items-center gap-1">
                                <span id="resend-text">Gửi lại mã</span>
                                <span id="resend-timer" class="text-slate-400 font-normal"></span>
                            </button>
                        </p>
                    </div>
                </form>
            </div>

            <!-- Chân trang -->
            <div class="mt-6 text-center pt-2">
                <p class="text-xs text-slate-400 font-medium">
                    Mã xác thực có hiệu lực trong vòng 5 phút. Vui lòng bảo mật thông tin.
                </p>
            </div>
        </div>

        <!-- Cột phải: Banner đồng bộ thương hiệu (6/12) -->
        <div class="hidden lg:flex lg:col-span-6 xl:col-span-6 relative p-12 flex-col justify-end overflow-hidden">
            <div class="absolute inset-0 bg-cover bg-center transition-transform duration-700 hover:scale-105"
                style="background-image: url('/Rentifia/public/assets/images/register_building.jpg');">
            </div>

            <div
                class="absolute inset-0 bg-gradient-to-t from-slate-950/90 via-slate-950/40 to-transparent pointer-events-none">
            </div>

            <div class="relative z-10">
                <div
                    class="glass-card rounded-[2rem] p-7 text-white shadow-2xl relative overflow-hidden backdrop-blur-xl">
                    <div
                        class="absolute -top-10 -right-10 w-32 h-32 bg-emerald-400/20 rounded-full blur-2xl pointer-events-none">
                    </div>

                    <span class="inline-block text-[11px] font-semibold text-emerald-300 uppercase tracking-wider mb-2">
                        Bảo mật 2 lớp an toàn tuyệt đối
                    </span>

                    <h2 class="text-lg font-bold tracking-tight text-white mb-3 leading-snug">
                        Xác minh tài khoản chính chủ qua Email để kích hoạt toàn bộ tính năng
                    </h2>

                    <ul class="space-y-2 text-xs text-white/80 font-medium">
                        <li class="flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                            Nhận thông báo thanh toán tiền phòng hàng tháng tự động
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                            Bảo vệ dữ liệu hợp đồng điện tử và giấy tờ cá nhân
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                            Khôi phục tài khoản nhanh chóng khi quên mật khẩu
                        </li>
                    </ul>
                </div>
            </div>
        </div>

    </div>

    <!-- SCRIPT SHADCN UI INPUT-OTP INTERACTION & TIMER -->
    <script>
        const slots = document.querySelectorAll('.otp-slot');
        const fullOtpInput = document.getElementById('fullOtp');
        const otpForm = document.getElementById('otpForm');

        // 1. Tự động focus vào ô đầu tiên khi mở trang
        window.addEventListener('DOMContentLoaded', () => {
            if (slots.length > 0) {
                slots[0].focus();
            }
            startCountdown(60);
        });

        // 2. Logic xử lý nhập liệu, nhảy ô, backspace và copy/paste chuẩn shadcn/ui
        slots.forEach((slot, index) => {
            // Khi gõ phím
            slot.addEventListener('input', (e) => {
                const val = e.target.value.replace(/[^0-9]/g, ''); // Chỉ cho phép số 0-9
                slot.value = val ? val[val.length - 1] : '';

                syncFullOtp();

                // Nếu có giá trị và chưa phải ô cuối thì nhảy sang ô kế tiếp
                if (slot.value && index < slots.length - 1) {
                    slots[index + 1].focus();
                }
            });

            // Khi nhấn phím điều hướng hoặc Backspace
            slot.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace') {
                    if (!slot.value && index > 0) {
                        // Nếu ô hiện tại rỗng, quay lại ô trước và xóa nó
                        slots[index - 1].focus();
                        slots[index - 1].value = '';
                        syncFullOtp();
                        e.preventDefault();
                    } else {
                        slot.value = '';
                        syncFullOtp();
                    }
                } else if (e.key === 'ArrowLeft' && index > 0) {
                    slots[index - 1].focus();
                } else if (e.key === 'ArrowRight' && index < slots.length - 1) {
                    slots[index + 1].focus();
                }
            });

            // Click vào ô sẽ tự động bôi đen chữ để dễ thay thế
            slot.addEventListener('focus', () => {
                slot.select();
            });

            // Xử lý sự kiện Paste (Dán mã 6 số)
            slot.addEventListener('paste', (e) => {
                e.preventDefault();
                const pastedData = (e.clipboardData || window.clipboardData).getData('text').replace(/[^0-9]/g, '').trim();

                if (!pastedData) return;

                // Điền lần lượt các số vào các slot
                for (let i = 0; i < slots.length; i++) {
                    if (pastedData[i]) {
                        slots[i].value = pastedData[i];
                    }
                }

                syncFullOtp();

                // Focus vào ô tiếp theo sau ký tự dán cuối cùng
                const targetIndex = Math.min(pastedData.length, slots.length - 1);
                slots[targetIndex].focus();

                // Nếu dán đủ 6 số, tự động submit form sau 200ms để tối ưu UX
                if (pastedData.length >= 6) {
                    setTimeout(() => {
                        triggerSubmit();
                    }, 200);
                }
            });
        });

        // 3. Đồng bộ giá trị 6 ô vào input ẩn
        function syncFullOtp() {
            let code = '';
            slots.forEach(s => code += s.value);
            fullOtpInput.value = code;

            // Nếu đủ 6 số, đổi màu nổi bật nút xác nhận
            const btn = document.getElementById('btn-verify');
            if (code.length === 6) {
                btn.classList.add('bg-emerald-600', 'hover:bg-emerald-700');
                btn.classList.remove('bg-slate-950', 'hover:bg-slate-800');
            } else {
                btn.classList.remove('bg-emerald-600', 'hover:bg-emerald-700');
                btn.classList.add('bg-slate-950', 'hover:bg-slate-800');
            }
        }

        // 4. Submit form
        function triggerSubmit() {
            syncFullOtp();
            if (fullOtpInput.value.length === 6) {
                const btn = document.getElementById('btn-verify');
                const btnText = document.getElementById('btn-verify-text');
                const spinner = document.getElementById('btn-verify-spinner');
                const icon = document.getElementById('btn-verify-icon');

                btn.disabled = true;
                btn.classList.add('opacity-80', 'cursor-not-allowed');
                spinner.classList.remove('hidden');
                icon.classList.add('hidden');
                btnText.innerText = "Đang kích hoạt tài khoản...";

                otpForm.submit();
            }
        }

        otpForm.addEventListener('submit', (e) => {
            syncFullOtp();
            if (fullOtpInput.value.length !== 6) {
                e.preventDefault();
                alert('Vui lòng nhập đầy đủ 6 chữ số mã xác thực OTP!');
                // Focus vào ô rỗng đầu tiên
                for (let i = 0; i < slots.length; i++) {
                    if (!slots[i].value) {
                        slots[i].focus();
                        break;
                    }
                }
                return false;
            }

            const btn = document.getElementById('btn-verify');
            const btnText = document.getElementById('btn-verify-text');
            const spinner = document.getElementById('btn-verify-spinner');
            const icon = document.getElementById('btn-verify-icon');

            btn.disabled = true;
            btn.classList.add('opacity-80', 'cursor-not-allowed');
            spinner.classList.remove('hidden');
            icon.classList.add('hidden');
            btnText.innerText = "Đang kích hoạt tài khoản...";
        });

        // 5. Đếm ngược gửi lại mã OTP (60s)
        let countdownTimer = null;

        function startCountdown(seconds) {
            const btnResend = document.getElementById('btn-resend');
            const timerSpan = document.getElementById('resend-timer');
            const textSpan = document.getElementById('resend-text');

            btnResend.disabled = true;
            btnResend.classList.add('opacity-50', 'cursor-not-allowed');
            textSpan.innerText = "Gửi lại sau";

            let remaining = seconds;
            timerSpan.innerText = `(${remaining}s)`;

            if (countdownTimer) clearInterval(countdownTimer);
            countdownTimer = setInterval(() => {
                remaining--;
                if (remaining > 0) {
                    timerSpan.innerText = `(${remaining}s)`;
                } else {
                    clearInterval(countdownTimer);
                    btnResend.disabled = false;
                    btnResend.classList.remove('opacity-50', 'cursor-not-allowed');
                    textSpan.innerText = "Gửi lại mã ngay";
                    timerSpan.innerText = "";
                }
            }, 1000);
        }

        // 6. Xử lý gửi lại mã OTP qua AJAX
        async function handleResendOtp() {
            const btnResend = document.getElementById('btn-resend');
            const textSpan = document.getElementById('resend-text');
            const statusMsg = document.getElementById('resend-status-msg');

            btnResend.disabled = true;
            textSpan.innerText = "Đang gửi...";
            statusMsg.className = "text-xs text-slate-500 font-medium block animate-pulse";
            statusMsg.innerText = "Hệ thống đang phát mã OTP mới tới email của bạn...";

            try {
                const formData = new FormData();
                formData.append('action', 'resend_otp');
                formData.append('format', 'json');

                const response = await fetch('', {
                    method: 'POST',
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    statusMsg.className = "text-xs text-emerald-600 font-semibold block";
                    statusMsg.innerText = "✓ " + data.message;
                    // Reset lại các ô nhập OTP
                    slots.forEach(s => s.value = '');
                    syncFullOtp();
                    slots[0].focus();
                    startCountdown(60);
                } else {
                    statusMsg.className = "text-xs text-rose-500 font-semibold block";
                    statusMsg.innerText = "✕ " + data.message;
                    textSpan.innerText = "Gửi lại mã ngay";
                    btnResend.disabled = false;
                }
            } catch (err) {
                statusMsg.className = "text-xs text-rose-500 font-semibold block";
                statusMsg.innerText = "✕ Lỗi kết nối khi gửi lại mã. Vui lòng thử lại!";
                textSpan.innerText = "Gửi lại mã ngay";
                btnResend.disabled = false;
            }
        }
    </script>
</body>

</html>
