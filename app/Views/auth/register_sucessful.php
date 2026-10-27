<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$registeredUsername = $_SESSION['registered_username'] ?? $_GET['username'] ?? '';
$successMessage = $_SESSION['flash_success'] ?? 'Tài khoản của bạn đã được khởi tạo thành công!';

// Xoá flash data sau khi hiển thị
unset($_SESSION['registered_username'], $_SESSION['flash_success']);
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rentifia - Đăng ký thành công</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .bg-success-pattern {
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

        @keyframes checkmark-pop {
            0% {
                transform: scale(0.6);
                opacity: 0;
            }

            70% {
                transform: scale(1.15);
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        .animate-pop {
            animation: checkmark-pop 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
        }
    </style>
</head>

<body
    class="bg-success-pattern min-h-screen flex items-center justify-center p-4 sm:p-6 md:p-10 text-slate-800 antialiased">
    <!-- Khung Card chính chia 2 cột đồng bộ với login/register -->
    <div
        class="w-full max-w-5xl bg-white rounded-[2.5rem] shadow-2xl shadow-emerald-950/15 overflow-hidden grid grid-cols-1 lg:grid-cols-12 min-h-[640px]">

        <!-- Cột trái: Thông báo Đăng ký thành công & Hành động (6/12) -->
        <div class="lg:col-span-6 xl:col-span-6 p-8 sm:p-12 md:p-14 flex flex-col justify-between bg-white relative">
            <!-- Thanh trên cùng: Logo & Ngôn ngữ -->
            <div class="flex items-center justify-between mb-6 sm:mb-8">
                <!-- Logo -->
                <a href="/Rentifia/app/Views/index.php" class="flex items-center gap-2 group">
                    <span
                        class="text-2xl font-extrabold tracking-tight text-slate-900 group-hover:opacity-90 transition-opacity">
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

            <!-- Khu vực trung tâm: Icon chúc mừng + Nội dung thông báo -->
            <div class="max-w-md w-full mx-auto my-auto text-center py-4">
                <!-- Icon thành công với vòng sáng -->
                <div class="relative mx-auto mb-6 w-20 h-20 flex items-center justify-center animate-pop">
                    <div class="absolute inset-0 bg-emerald-100 rounded-full scale-110 animate-ping opacity-25"></div>
                    <div
                        class="w-20 h-20 bg-emerald-50 rounded-full ring-8 ring-emerald-50/70 flex items-center justify-center">
                        <div
                            class="w-14 h-14 bg-gradient-to-tr from-emerald-600 to-emerald-400 rounded-full flex items-center justify-center shadow-lg shadow-emerald-500/30 text-white">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Tiêu đề & Thông điệp -->
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight mb-2">
                    Đăng ký thành công!
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 font-medium leading-relaxed px-2 mb-6">
                    <?= htmlspecialchars($successMessage) ?><br>
                    Tài khoản của bạn đã sẵn sàng để truy cập nền tảng quản lý Rentifia.
                </p>

                <!-- Thẻ thông tin tài khoản (nếu có username) -->
                <?php if (!empty($registeredUsername)): ?>
                    <div
                        class="mb-6 p-4 bg-slate-50 border border-slate-100 rounded-2xl flex items-center justify-between text-left">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-700 font-bold flex items-center justify-center text-sm">
                                <?= strtoupper(substr(htmlspecialchars($registeredUsername), 0, 1)) ?>
                            </div>
                            <div>
                                <p class="text-xs text-slate-400 font-medium">Tên đăng nhập</p>
                                <p class="text-sm font-semibold text-slate-800"><?= htmlspecialchars($registeredUsername) ?>
                                </p>
                            </div>
                        </div>
                        <span
                            class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-50 border border-emerald-200/80 text-emerald-700 text-xs font-semibold rounded-full">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            Hoạt động
                        </span>
                    </div>
                <?php endif; ?>

                <!-- Thông báo tự động chuyển hướng -->
                <div
                    class="mb-6 p-3 bg-slate-50/80 border border-slate-100 rounded-2xl flex items-center justify-center gap-2 text-xs text-slate-600">
                    <svg class="w-4 h-4 text-emerald-500 flex-shrink-0 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                        </circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                    </svg>
                    <span>Tự động chuyển đến trang chủ <span id="countdown" class="font-bold text-emerald-600">5</span>
                        giây</span>
                    <button type="button" id="toggleRedirectBtn" onclick="toggleRedirect()"
                        class="text-slate-400 hover:text-slate-700 font-medium underline ml-1">
                        Dừng lại
                    </button>
                </div>

                <!-- Các nút điều hướng -->
                <div class="space-y-3">
                    <a href="/Rentifia/app/Views/auth/login.php"
                        class="w-full py-4 bg-slate-950 hover:bg-slate-800 text-white font-semibold text-sm rounded-2xl shadow-lg shadow-slate-950/20 transition-all active:scale-[0.99] flex items-center justify-center gap-2">
                        <span>Đăng nhập ngay</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>

                    <a href="/Rentifia/app/Views/index.html"
                        class="w-full py-3.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-sm rounded-2xl transition-all flex items-center justify-center gap-2">
                        <span>Về trang chủ Rentifia</span>
                    </a>
                </div>
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

        <!-- Cột phải: Banner hình ảnh & Kính mờ Glassmorphism (6/12) -->
        <div class="hidden lg:block lg:col-span-6 xl:col-span-6 relative bg-slate-100 overflow-hidden min-h-[640px]">
            <!-- Ảnh nền banner tương tự login/register -->
            <img src="/Rentifia/public/assets/images/login_banner.jpg" alt="Rentifia Modern Apartment Interior"
                class="absolute inset-0 w-full h-full object-cover object-center transform scale-105 hover:scale-100 transition-transform duration-1000 ease-out">

            <!-- Lớp phủ tương phản nhẹ -->
            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/60 via-slate-950/25 to-transparent"></div>

            <!-- Hộp Kính Mờ Glassmorphism nổi bật -->
            <div class="absolute inset-x-8 bottom-10 z-10">
                <div
                    class="glass-card rounded-[2rem] p-7 text-white shadow-2xl relative overflow-hidden backdrop-blur-xl">
                    <!-- Ánh sáng dịu nhẹ góc card -->
                    <div
                        class="absolute -top-10 -right-10 w-32 h-32 bg-emerald-400/20 rounded-full blur-2xl pointer-events-none">
                    </div>

                    <!-- Badge nổi bật phía trên tiêu đề -->
                    <span class="inline-block text-[11px] font-semibold text-emerald-300 uppercase tracking-wider mb-2">
                        Bắt đầu ngay hôm nay
                    </span>

                    <h2 class="text-lg font-bold tracking-tight text-white mb-3 leading-snug">
                        3 bước khởi tạo dễ dàng cùng Rentifia
                    </h2>

                    <!-- Hướng dẫn các bước tiếp theo -->
                    <ul class="space-y-2.5 text-xs text-white/85 font-medium">
                        <li class="flex items-start gap-2.5">
                            <span
                                class="w-5 h-5 rounded-full bg-emerald-500/30 text-emerald-300 flex items-center justify-center text-[11px] font-bold flex-shrink-0 mt-0.5">1</span>
                            <span><strong>Đăng nhập tài khoản:</strong> Truy cập hệ thống bằng tên đăng nhập và mật khẩu
                                vừa tạo.</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <span
                                class="w-5 h-5 rounded-full bg-emerald-500/30 text-emerald-300 flex items-center justify-center text-[11px] font-bold flex-shrink-0 mt-0.5">2</span>
                            <span><strong>Thiết lập cơ sở & phòng:</strong> Thêm thông tin nhà trọ, căn hộ và biểu phí
                                dịch vụ điện/nước.</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <span
                                class="w-5 h-5 rounded-full bg-emerald-500/30 text-emerald-300 flex items-center justify-center text-[11px] font-bold flex-shrink-0 mt-0.5">3</span>
                            <span><strong>Quản lý tự động hóa:</strong> Thêm khách thuê, tạo hợp đồng điện tử và xuất
                                hóa đơn định kỳ.</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

    </div>

    <!-- Script đếm ngược tự động chuyển hướng -->
    <script>
        let timeLeft = 5;
        let isPaused = false;
        const countdownEl = document.getElementById('countdown');
        const toggleBtn = document.getElementById('toggleRedirectBtn');

        const interval = setInterval(() => {
            if (isPaused) return;

            timeLeft--;
            if (countdownEl) {
                countdownEl.textContent = timeLeft;
            }

            if (timeLeft <= 0) {
                clearInterval(interval);
                window.location.href = '/Rentifia/app/Views/index.php';
            }
        }, 1000);

        function toggleRedirect() {
            isPaused = !isPaused;
            if (toggleBtn) {
                toggleBtn.textContent = isPaused ? 'Tiếp tục' : 'Dừng lại';
            }
        }
    </script>
</body>

</html>