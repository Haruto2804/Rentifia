<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Thiết lập mã HTTP 401/403
$isLoggedIn = !empty($_SESSION['logged_in']);
http_response_code($isLoggedIn ? 403 : 401);

$currentRole = $_SESSION['role'] ?? 'guest';
$currentUsername = $_SESSION['username'] ?? '';

// Đường dẫn quay về theo Role nếu đã đăng nhập
$dashboardUrl = match ($currentRole) {
    'admin'   => '/Rentifia/app/Views/admin/dashboard/index.php',
    'manager' => '/Rentifia/app/Views/manager/dashboard/index.php',
    'tenant'  => '/Rentifia/app/Views/tenant/dashboard/index.php',
    default   => '/Rentifia/app/Views/auth/login.php',
};
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hold on, You are not allowed here | Rentifia</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #1a2332;
        }

        .card-sky-gradient {
            background: linear-gradient(180deg, #5e9fcf 0%, #7db4db 32%, #a3cbe5 68%, #cedae1 100%);
        }

        .btn-shadow {
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.12), 0 1px 3px rgba(0, 0, 0, 0.08);
        }

        .btn-shadow:hover {
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.18), 0 2px 6px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center p-4 sm:p-6 antialiased select-none">

    <!-- KHUNG CHÍNH (CARD GIỐNG HỆT MOCKUP) -->
    <div class="relative w-full max-w-[860px] rounded-[32px] overflow-hidden shadow-2xl card-sky-gradient text-slate-800 transition-all">

        <!-- LOGO BRAND Ở GÓC TRÊN BÊN TRÁI -->
        <div class="absolute top-6 left-6 sm:left-9 flex items-center gap-2.5 z-20">
            <div class="w-8 h-8 rounded-full bg-white/20 backdrop-blur-md flex items-center justify-center border border-white/30 shadow-sm">
                <!-- Icon biểu tượng tương tự mockup -->
                <svg class="w-4 h-4 text-white" viewBox="0 0 24 24" fill="currentColor">
                    <path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.53 1.032 1.53 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd" />
                </svg>
            </div>
            <span class="text-white font-extrabold text-lg tracking-tight drop-shadow-sm">Rentifia</span>
        </div>

        <!-- HÌNH ẢNH MINH HỌA (GIANT 401 & 2 QUÁI VẬT 3D ĐỘC ĐÁO) -->
        <div class="relative w-full pt-8 sm:pt-10 px-4 sm:px-8 flex justify-center overflow-hidden">
            <img src="/Rentifia/public/assets/images/unauthorized_monsters.jpg"
                alt="401 - Hold on, You are not allowed here"
                class="w-full max-w-[760px] h-auto object-contain rounded-2xl pointer-events-none shadow-md">
        </div>

        <!-- PHẦN TIÊU ĐỀ & NÚT HÀNH ĐỘNG DƯỚI CARD -->
        <div class="px-6 pb-9 sm:pb-11 pt-1 text-center relative z-20">
            <!-- Tiêu đề màu xanh đậm chuẩn thiết kế -->
            <h1 class="text-[#09154a] font-extrabold text-2xl sm:text-3xl md:text-[33px] tracking-tight mb-2">
                Hold on, You are not allowed here
            </h1>

            <!-- Phụ đề -->
            <p class="text-[#516474] font-medium text-sm sm:text-base mb-6">
                Bạn không có quyền truy cập vào khu vực này. Vui lòng quay lại trang chủ!
            </p>

            <!-- Nút bấm quay lại trang chủ Views/index.php -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                <a href="/Rentifia/app/Views/index.php"
                    class="btn-shadow inline-flex items-center gap-2.5 px-6 py-2.5 rounded-full bg-white text-[#2d3748] font-bold text-sm hover:scale-105 active:scale-95 transition-all duration-200">
                    <!-- Icon mũi tên quay lại hình tròn phong cách ảnh mẫu -->
                    <span class="w-5 h-5 rounded-full bg-[#09154a] text-white flex items-center justify-center">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </span>
                    <span>Quay lại</span>
                </a>

                <!-- Nếu đã đăng nhập, vẫn hỗ trợ nút về Dashboard của role đó và Đăng xuất -->
                <?php if ($isLoggedIn && $currentRole !== 'guest'): ?>
                    <a href="<?= $dashboardUrl ?>"
                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-white/40 hover:bg-white/60 text-[#09154a] font-bold text-xs backdrop-blur-sm border border-white/60 transition-all hover:scale-105">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span>Về trang <?= ucfirst($currentRole) ?></span>
                    </a>
                    <a href="/Rentifia/app/Views/auth/logout.php"
                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold text-xs border border-rose-200 transition-all hover:scale-105"
                        title="Đăng xuất để đổi tài khoản">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span>Đăng xuất</span>
                    </a>
                <?php endif; ?>
            </div>

            <!-- Ghi chú vai trò hiện tại (nếu có đăng nhập) -->
            <?php if ($isLoggedIn && $currentRole !== 'guest'): ?>
                <p class="text-[11px] text-[#55697a] mt-4 font-medium">
                    Tài khoản hiện tại: <strong class="text-[#09154a]"><?= htmlspecialchars($currentUsername) ?></strong> • Vai trò: <span class="uppercase font-bold text-amber-700"><?= htmlspecialchars($currentRole) ?></span>
                </p>
            <?php endif; ?>
        </div>

    </div>

</body>

</html>
