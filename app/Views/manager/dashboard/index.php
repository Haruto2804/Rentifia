<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Giữ session nếu có, hoặc tạo dữ liệu mẫu nếu truy cập trực tiếp
$currentUsername = $_SESSION['username'] ?? 'chuongbut_manager';
$currentRole     = $_SESSION['role'] ?? 'manager';
$currentEmail    = $_SESSION['email'] ?? 'chuongbut.manager@rentifia.vn';
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rentifia - Bảng Điều Hành Quản Lý Cơ Sở</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        forest: {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            200: '#bbf7d0',
                            500: '#22c55e',
                            600: '#16a34a',
                            700: '#15803d',
                            800: '#166534',
                            900: '#14532d',
                            950: '#0d3820', // Màu xanh đậm chủ đạo theo mẫu thiết kế
                        },
                        app: {
                            bg: '#F3F4F6',
                            card: '#FFFFFF',
                            border: '#E5E7EB',
                            textDark: '#111827',
                            textMuted: '#6B7280',
                            tagGreen: '#EAF7EE',
                            tagOrange: '#FFF7ED',
                            tagYellow: '#FEFCE8'
                        }
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'Inter', 'system-ui', 'sans-serif'],
                        mono: ['"JetBrains Mono"', 'monospace']
                    },
                    boxShadow: {
                        'soft': '0 2px 10px rgba(0, 0, 0, 0.03), 0 10px 30px rgba(0, 0, 0, 0.04)',
                        'card': '0 4px 20px -2px rgba(0, 0, 0, 0.05)',
                    }
                }
            }
        }
    </script>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #F1F3F5;
        }

        /* Thanh cuộn tinh tế */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #F1F3F5;
        }
        ::-webkit-scrollbar-thumb {
            background: #CBD5E1;
            border-radius: 9999px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94A3B8;
        }

        /* Họa tiết gạch sọc cho cột biểu đồ và gauge như ảnh mẫu */
        .pattern-striped {
            background-image: repeating-linear-gradient(
                45deg,
                rgba(20, 83, 45, 0.18),
                rgba(20, 83, 45, 0.18) 4px,
                transparent 4px,
                transparent 8px
            );
        }

        .pattern-striped-light {
            background-image: repeating-linear-gradient(
                45deg,
                rgba(148, 163, 184, 0.35),
                rgba(148, 163, 184, 0.35) 4px,
                transparent 4px,
                transparent 8px
            );
        }

        /* Nền lượn sóng 3D cho widget Đồng hồ ca trực */
        .wave-bg {
            background: radial-gradient(circle at 80% 20%, #15803d 0%, #0d3820 60%, #052012 100%);
            position: relative;
            overflow: hidden;
        }
        .wave-bg::after {
            content: '';
            position: absolute;
            inset: 0;
            background-image: radial-gradient(rgba(34, 197, 94, 0.25) 1px, transparent 1px);
            background-size: 14px 14px;
            opacity: 0.6;
        }
    </style>
</head>

<body class="text-slate-800 antialiased p-3 sm:p-5 lg:p-6 min-h-screen flex flex-col justify-between">

    <!-- KHUNG CHÍNH (WRAPPER CARD THEO PHONG CÁCH BO GÓC LỚN NHƯ ẢNH) -->
    <div class="bg-white rounded-[2rem] sm:rounded-[2.5rem] shadow-soft border border-slate-100/80 flex flex-col lg:flex-row overflow-hidden w-full max-w-[1680px] mx-auto min-h-[92vh]">

        <!-- ================= 1. SIDEBAR TRÁI ================= -->
        <!-- Mobile Sidebar Overlay (ẩn mặc định trên mobile) -->
        <div id="sidebarBackdrop" onclick="toggleSidebar()" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-40 lg:hidden hidden transition-opacity"></div>

        <aside id="mainSidebar" class="fixed lg:static inset-y-0 left-0 w-72 bg-white z-50 lg:z-auto border-r border-slate-100 flex flex-col justify-between p-6 transition-transform duration-300 -translate-x-full lg:translate-x-0">
            <!-- Top Section: Brand & Nav Links -->
            <div class="space-y-8">
                <!-- Brand Logo -->
                <div class="flex items-center justify-between">
                    <a href="/Rentifia/app/Views/manager/dashboard/index.php" class="flex items-center gap-3 group">
                        <div class="w-10 h-10 rounded-2xl bg-forest-950 flex items-center justify-center text-white shadow-md shadow-forest-950/20 group-hover:scale-105 transition-transform">
                            <!-- Logo Icon -->
                            <svg class="w-5 h-5 text-forest-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                                <polyline points="9 22 9 12 15 12 15 22"/>
                            </svg>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-xl font-bold tracking-tight text-slate-900">Rentifia</span>
                            <span class="text-[10px] font-semibold uppercase tracking-wider text-forest-700 bg-forest-50 px-1.5 py-0.5 rounded-md self-start">Manager Portal</span>
                        </div>
                    </a>
                    <!-- Nút đóng sidebar trên mobile -->
                    <button onclick="toggleSidebar()" class="lg:hidden p-1.5 text-slate-400 hover:text-slate-600 rounded-lg hover:bg-slate-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <!-- Menu Chính -->
                <div class="space-y-1">
                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400 px-3 mb-2">Thực đơn quản lý</p>
                    
                    <!-- Dashboard (Active) -->
                    <a href="/Rentifia/app/Views/manager/dashboard/index.php" class="flex items-center justify-between px-3.5 py-3 rounded-2xl bg-forest-50/80 text-forest-950 font-semibold text-sm transition-all group">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-xl bg-forest-950 text-white flex items-center justify-center shadow-sm">
                                <svg class="w-4 h-4 text-forest-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                            </div>
                            <span>Bảng điều hành</span>
                        </div>
                        <span class="w-2 h-2 rounded-full bg-forest-600"></span>
                    </a>

                    <!-- Sơ đồ phòng -->
                    <a href="/Rentifia/app/Views/manager/rooms/index.php" class="flex items-center justify-between px-3.5 py-3 rounded-2xl text-slate-600 hover:bg-slate-50 hover:text-slate-900 font-medium text-sm transition-all group">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-slate-400 group-hover:text-forest-700 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                            <span>Sơ đồ phòng</span>
                        </div>
                        <span class="px-2 py-0.5 text-[10px] font-bold rounded-full bg-emerald-50 text-emerald-700 border border-emerald-100">4 trống</span>
                    </a>

                    <!-- Hợp đồng lưu trú -->
                    <a href="/Rentifia/app/Views/manager/contracts/index.php" class="flex items-center gap-3 px-3.5 py-3 rounded-2xl text-slate-600 hover:bg-slate-50 hover:text-slate-900 font-medium text-sm transition-all group">
                        <svg class="w-5 h-5 text-slate-400 group-hover:text-forest-700 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        <span>Hợp đồng</span>
                    </a>

                    <!-- Chốt Điện / Nước -->
                    <a href="/Rentifia/app/Views/manager/meters/index.php" class="flex items-center justify-between px-3.5 py-3 rounded-2xl text-slate-600 hover:bg-slate-50 hover:text-slate-900 font-medium text-sm transition-all group">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-slate-400 group-hover:text-forest-700 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            <span>Chốt Điện / Nước</span>
                        </div>
                        <span class="px-2 py-0.5 text-[10px] font-bold rounded-full bg-amber-50 text-amber-700 border border-amber-100">Kỳ T10</span>
                    </a>

                    <!-- Hóa đơn & Thu tiền -->
                    <a href="/Rentifia/app/Views/manager/invoices/index.php" class="flex items-center gap-3 px-3.5 py-3 rounded-2xl text-slate-600 hover:bg-slate-50 hover:text-slate-900 font-medium text-sm transition-all group">
                        <svg class="w-5 h-5 text-slate-400 group-hover:text-forest-700 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        <span>Hóa đơn & VietQR</span>
                    </a>

                    <!-- Cư dân & Khách thuê -->
                    <a href="/Rentifia/app/Views/manager/tenants/index.php" class="flex items-center gap-3 px-3.5 py-3 rounded-2xl text-slate-600 hover:bg-slate-50 hover:text-slate-900 font-medium text-sm transition-all group">
                        <svg class="w-5 h-5 text-slate-400 group-hover:text-forest-700 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        <span>Cư dân phòng</span>
                    </a>
                </div>

                <!-- Mục Cài đặt & Chung -->
                <div class="space-y-1 pt-2 border-t border-slate-100">
                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400 px-3 mb-2">Hệ thống</p>
                    <a href="#" class="flex items-center gap-3 px-3.5 py-2.5 rounded-2xl text-slate-600 hover:bg-slate-50 hover:text-slate-900 font-medium text-sm transition-all">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        <span>Cấu hình chi nhánh</span>
                    </a>
                    <a href="/Rentifia/app/Views/auth/login.php" class="flex items-center gap-3 px-3.5 py-2.5 rounded-2xl text-rose-600 hover:bg-rose-50 font-medium text-sm transition-all">
                        <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        <span>Đăng xuất</span>
                    </a>
                </div>
            </div>

            <!-- Bottom Section: App Download Promo Box (Y hệt mẫu ảnh) -->
            <div class="mt-8 rounded-3xl p-5 bg-gradient-to-br from-forest-950 via-slate-900 to-forest-900 text-white relative overflow-hidden shadow-lg shadow-forest-950/20">
                <!-- Vòng sóng trang trí -->
                <div class="absolute -right-6 -bottom-6 w-28 h-28 bg-forest-500/20 rounded-full blur-xl pointer-events-none"></div>
                <div class="w-8 h-8 rounded-xl bg-white/10 flex items-center justify-center text-forest-300 mb-3 backdrop-blur-sm">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="5" y="2" width="14" height="20" rx="2" ry="2"></rect><line x1="12" y1="18" x2="12.01" y2="18"></line></svg>
                </div>
                <h4 class="text-sm font-bold text-white mb-1 leading-snug">Rentifia Mobile App</h4>
                <p class="text-[11px] text-white/70 mb-3">Quét số điện nước offline & chụp ảnh công tơ nhanh chóng.</p>
                <button class="w-full py-2 bg-forest-600 hover:bg-forest-500 text-white font-semibold text-xs rounded-xl transition-all shadow-sm">
                    Tải ứng dụng
                </button>
            </div>
        </aside>

        <!-- ================= 2. VÙNG NỘI DUNG CHÍNH (RIGHT MAIN CONTENT) ================= -->
        <main class="flex-1 flex flex-col bg-[#F7F8FA] min-w-0 overflow-y-auto">

            <!-- TOPBAR HEADER (SEARCH + SHORTCUT + ACTIONS + USER INFO) -->
            <header class="h-20 px-6 sm:px-8 border-b border-slate-200/70 bg-white/80 backdrop-blur-md flex items-center justify-between sticky top-0 z-30">
                <div class="flex items-center gap-3 flex-1 max-w-xl">
                    <!-- Mobile Menu Button -->
                    <button onclick="toggleSidebar()" class="lg:hidden p-2 text-slate-500 hover:text-slate-800 hover:bg-slate-100 rounded-xl transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>

                    <!-- Search Input (Giống ảnh mẫu có phím tắt ⌘F / Ctrl+K) -->
                    <div class="relative w-full">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <input type="text" placeholder="Tìm số phòng, tên cư dân, số điện thoại hoặc mã HĐ..." class="w-full pl-10 pr-12 py-2.5 bg-slate-50 hover:bg-slate-100/80 focus:bg-white text-xs sm:text-sm rounded-full border border-slate-200/80 focus:border-forest-600 focus:outline-none focus:ring-4 focus:ring-forest-500/10 transition-all">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <span class="text-[11px] font-mono text-slate-400 bg-white px-1.5 py-0.5 rounded-md border border-slate-200 shadow-2xs">Ctrl K</span>
                        </div>
                    </div>
                </div>

                <!-- Right Topbar: Notifications & User Profile -->
                <div class="flex items-center gap-3 sm:gap-4 pl-4">
                    <!-- Nút Hộp thư -->
                    <button class="w-10 h-10 rounded-full border border-slate-200 bg-white text-slate-600 hover:bg-slate-50 flex items-center justify-center transition-colors relative">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </button>

                    <!-- Nút Chuông thông báo có chấm đỏ -->
                    <button class="w-10 h-10 rounded-full border border-slate-200 bg-white text-slate-600 hover:bg-slate-50 flex items-center justify-center transition-colors relative">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                        <span class="absolute top-2 right-2 w-2 h-2 rounded-full bg-rose-500 ring-2 ring-white"></span>
                    </button>

                    <!-- User Profile Avatar & Info -->
                    <div class="flex items-center gap-3 pl-2 sm:border-l border-slate-200">
                        <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&q=80&w=120" alt="Avatar" class="w-10 h-10 rounded-full object-cover ring-2 ring-forest-500/30">
                        <div class="hidden sm:flex flex-col text-left">
                            <span class="text-sm font-bold text-slate-900 leading-tight"><?= htmlspecialchars($currentUsername) ?></span>
                            <span class="text-[11px] text-slate-400 font-medium"><?= htmlspecialchars($currentEmail) ?></span>
                        </div>
                    </div>
                </div>
            </header>

            <!-- NỘI DUNG DASHBOARD CHÍNH -->
            <div class="p-5 sm:p-8 space-y-6 max-w-7xl">

                <!-- 1. TIÊU ĐỀ DASHBOARD & CÁC NÚT HÀNH ĐỘNG CHÍNH -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Bảng Điều Hành</h1>
                        <p class="text-xs sm:text-sm text-slate-500 font-normal mt-0.5">Theo dõi tỷ lệ lấp đầy, chốt số điện nước và hóa đơn lưu trú trực quan.</p>
                    </div>
                    <!-- Nút hành động chuẩn form ảnh mẫu: Nút xanh đậm + Nút trắng viền -->
                    <div class="flex items-center gap-3">
                        <a href="/Rentifia/app/Views/manager/contracts/create.php" class="inline-flex items-center gap-2 px-5 py-3 rounded-full bg-forest-950 hover:bg-forest-900 text-white font-semibold text-xs sm:text-sm shadow-md shadow-forest-950/15 transition-all active:scale-98">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                            <span>Lập Hợp Đồng</span>
                        </a>
                        <a href="/Rentifia/app/Views/manager/meters/index.php" class="inline-flex items-center gap-2 px-4 py-3 rounded-full bg-white hover:bg-slate-50 text-slate-800 font-semibold text-xs sm:text-sm border border-slate-300/80 shadow-2xs transition-all">
                            <span>Chốt Điện Nước</span>
                        </a>
                    </div>
                </div>

                <!-- 2. HÀNG 4 THẺ THỐNG KÊ (KPI STATS CARDS) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
                    
                    <!-- THẺ 1: Màu xanh đậm Forest Gradient (Đặc trưng theo mẫu) -->
                    <div class="bg-gradient-to-br from-forest-950 to-forest-900 text-white rounded-3xl p-6 relative overflow-hidden shadow-card flex flex-col justify-between group hover:shadow-lg transition-shadow">
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-xs font-semibold text-white/80">Tổng Số Phòng</span>
                            <div class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center text-white group-hover:scale-110 transition-transform">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 17l9.2-9.2M17 17V8H8"></path></svg>
                            </div>
                        </div>
                        <div>
                            <div class="text-4xl font-extrabold tracking-tight mb-2">24</div>
                            <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-white/15 text-[11px] font-medium text-emerald-300">
                                <span>↑ 4 phòng</span>
                                <span class="text-white/60">so với tháng trước</span>
                            </div>
                        </div>
                    </div>

                    <!-- THẺ 2: Phòng Đang Thuê (Trắng) -->
                    <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-card flex flex-col justify-between group hover:shadow-lg transition-shadow">
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-xs font-semibold text-slate-500">Phòng Đang Thuê</span>
                            <div class="w-8 h-8 rounded-full border border-slate-200 flex items-center justify-center text-slate-600 group-hover:scale-110 transition-transform">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 17l9.2-9.2M17 17V8H8"></path></svg>
                            </div>
                        </div>
                        <div>
                            <div class="text-4xl font-extrabold text-slate-900 tracking-tight mb-2">18</div>
                            <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-50 text-[11px] font-semibold text-emerald-700">
                                <span>75%</span>
                                <span class="text-slate-500 font-normal">tỷ lệ lấp đầy</span>
                            </div>
                        </div>
                    </div>

                    <!-- THẺ 3: Chờ Chốt Điện Nước (Trắng) -->
                    <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-card flex flex-col justify-between group hover:shadow-lg transition-shadow">
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-xs font-semibold text-slate-500">Chờ Chốt Điện/Nước</span>
                            <div class="w-8 h-8 rounded-full border border-slate-200 flex items-center justify-center text-slate-600 group-hover:scale-110 transition-transform">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 17l9.2-9.2M17 17V8H8"></path></svg>
                            </div>
                        </div>
                        <div>
                            <div class="text-4xl font-extrabold text-slate-900 tracking-tight mb-2">12</div>
                            <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-amber-50 text-[11px] font-semibold text-amber-700">
                                <span>Kỳ T10</span>
                                <span class="text-slate-500 font-normal">hạn ngày 25</span>
                            </div>
                        </div>
                    </div>

                    <!-- THẺ 4: Hóa Đơn Quá Hạn (Trắng) -->
                    <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-card flex flex-col justify-between group hover:shadow-lg transition-shadow">
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-xs font-semibold text-slate-500">Hóa Đơn Quá Hạn</span>
                            <div class="w-8 h-8 rounded-full border border-slate-200 flex items-center justify-center text-slate-600 group-hover:scale-110 transition-transform">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 17l9.2-9.2M17 17V8H8"></path></svg>
                            </div>
                        </div>
                        <div>
                            <div class="text-4xl font-extrabold text-slate-900 tracking-tight mb-2">2</div>
                            <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-rose-50 text-[11px] font-semibold text-rose-700">
                                <span>5.400.000đ</span>
                                <span class="text-slate-500 font-normal">công nợ</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3. HÀNG GIỮA: BIỂU ĐỒ SẢN LƯỢNG + LỜI NHẮC CƠ SỞ + CÁC ĐẦU VIỆC (3 CỘT THEO ẢNH MẪU) -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">

                    <!-- CỘT 1: BIỂU ĐỒ SẢN LƯỢNG ĐIỆN THEO TUẦN (PROJECT ANALYTICS) -->
                    <div class="lg:col-span-5 bg-white rounded-3xl p-6 border border-slate-100 shadow-card flex flex-col justify-between">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h3 class="text-base font-bold text-slate-900">Sản Lượng Điện Tuần</h3>
                                <p class="text-xs text-slate-400">Chỉ số tiêu thụ kWh toàn tòa nhà</p>
                            </div>
                            <span class="text-xs font-bold px-3 py-1 bg-forest-50 text-forest-700 rounded-full border border-forest-100">74% Đỉnh</span>
                        </div>

                        <!-- Cột biểu đồ hình viên thuốc bo tròn (Stylized Capsules) -->
                        <div class="flex items-end justify-between h-48 px-2 pt-6">
                            <!-- CN -->
                            <div class="flex flex-col items-center gap-2">
                                <div class="w-9 h-32 rounded-full pattern-striped-light bg-slate-100 border border-slate-200"></div>
                                <span class="text-xs font-semibold text-slate-400">CN</span>
                            </div>
                            <!-- T2 -->
                            <div class="flex flex-col items-center gap-2">
                                <div class="w-9 h-36 rounded-full bg-forest-800"></div>
                                <span class="text-xs font-semibold text-slate-400">T2</span>
                            </div>
                            <!-- T3 (Có tooltip tỷ lệ) -->
                            <div class="flex flex-col items-center gap-2 relative">
                                <div class="absolute -top-6 px-1.5 py-0.5 rounded bg-slate-900 text-[10px] font-bold text-white">74%</div>
                                <div class="w-9 h-28 rounded-full bg-forest-500"></div>
                                <span class="text-xs font-semibold text-slate-400">T3</span>
                            </div>
                            <!-- T4 (Cao nhất - Dark Forest) -->
                            <div class="flex flex-col items-center gap-2">
                                <div class="w-9 h-44 rounded-full bg-forest-950"></div>
                                <span class="text-xs font-semibold text-slate-400">T4</span>
                            </div>
                            <!-- T5 -->
                            <div class="flex flex-col items-center gap-2">
                                <div class="w-9 h-24 rounded-full pattern-striped-light bg-slate-100 border border-slate-200"></div>
                                <span class="text-xs font-semibold text-slate-400">T5</span>
                            </div>
                            <!-- T6 -->
                            <div class="flex flex-col items-center gap-2">
                                <div class="w-9 h-36 rounded-full pattern-striped-light bg-slate-100 border border-slate-200"></div>
                                <span class="text-xs font-semibold text-slate-400">T6</span>
                            </div>
                            <!-- T7 -->
                            <div class="flex flex-col items-center gap-2">
                                <div class="w-9 h-28 rounded-full pattern-striped-light bg-slate-100 border border-slate-200"></div>
                                <span class="text-xs font-semibold text-slate-400">T7</span>
                            </div>
                        </div>
                    </div>

                    <!-- CỘT 2: LỜI NHẮC CÔNG VIỆC TRỌNG TÂM (REMINDERS CARD) -->
                    <div class="lg:col-span-4 bg-white rounded-3xl p-6 border border-slate-100 shadow-card flex flex-col justify-between">
                        <div>
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Lời nhắc công việc</span>
                            <h3 class="text-lg font-extrabold text-slate-900 mt-2 leading-snug">Chốt chỉ số Điện/Nước T10 Chi nhánh Landmark</h3>
                            <p class="text-xs text-slate-500 mt-1 font-mono">Thời gian: 08:00 AM - 17:00 PM (25/10)</p>
                            
                            <div class="mt-4 p-3.5 bg-forest-50/70 rounded-2xl border border-forest-100 text-xs text-forest-900 space-y-1.5">
                                <div class="flex items-center gap-2 font-semibold">
                                    <span class="w-1.5 h-1.5 rounded-full bg-forest-600"></span>
                                    <span>Đã chốt: Tầng 1, Tầng 2 (12 phòng)</span>
                                </div>
                                <div class="flex items-center gap-2 text-forest-700">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                    <span>Còn lại: Tầng 3, Tầng 4 (12 phòng)</span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6">
                            <a href="/Rentifia/app/Views/manager/meters/index.php" class="w-full py-3.5 rounded-2xl bg-forest-950 hover:bg-forest-900 text-white font-semibold text-xs sm:text-sm flex items-center justify-center gap-2 transition-all shadow-md shadow-forest-950/15">
                                <svg class="w-4 h-4 text-forest-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                <span>Bắt Đầu Ghi Số Nhanh</span>
                            </a>
                        </div>
                    </div>

                    <!-- CỘT 3: DANH SÁCH ĐẦU VIỆC CẦN LÀM (PROJECTS LIST) -->
                    <div class="lg:col-span-3 bg-white rounded-3xl p-6 border border-slate-100 shadow-card flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-base font-bold text-slate-900">Việc Cần Xử Lý</h3>
                                <button class="text-xs font-bold text-forest-800 hover:text-forest-950 flex items-center gap-1 bg-forest-50 px-2.5 py-1 rounded-full">+ Mới</button>
                            </div>

                            <div class="space-y-3.5">
                                <!-- Việc 1 -->
                                <div class="flex items-start gap-3">
                                    <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0 mt-0.5">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </div>
                                    <div>
                                        <h4 class="text-xs font-bold text-slate-900">Ký gia hạn HĐ P.301</h4>
                                        <p class="text-[11px] text-slate-400">Hạn chót: 26/10/2026</p>
                                    </div>
                                </div>

                                <!-- Việc 2 -->
                                <div class="flex items-start gap-3">
                                    <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center flex-shrink-0 mt-0.5">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                    </div>
                                    <div>
                                        <h4 class="text-xs font-bold text-slate-900">Sửa vòi nước P.204</h4>
                                        <p class="text-[11px] text-slate-400">Cư dân báo qua app</p>
                                    </div>
                                </div>

                                <!-- Việc 3 -->
                                <div class="flex items-start gap-3">
                                    <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0 mt-0.5">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    </div>
                                    <div>
                                        <h4 class="text-xs font-bold text-slate-900">Xác nhận VietQR P.105</h4>
                                        <p class="text-[11px] text-slate-400">Đã nhận 4.500.000đ</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 4. HÀNG DƯỚI: CƯ DÂN GẦN ĐÂY + TIẾN ĐỘ THU PHÍ + ĐỒNG HỒ CA TRỰC -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">

                    <!-- CỘT 1: CƯ DÂN PHÒNG GẦN ĐÂY (TEAM COLLABORATION STYLE) -->
                    <div class="lg:col-span-5 bg-white rounded-3xl p-6 border border-slate-100 shadow-card">
                        <div class="flex items-center justify-between mb-5">
                            <div>
                                <h3 class="text-base font-bold text-slate-900">Cư Dân Nhận Phòng Gần Đây</h3>
                                <p class="text-xs text-slate-400">Hợp đồng kích hoạt trong tháng này</p>
                            </div>
                            <button class="text-xs font-bold text-forest-800 hover:text-forest-950 flex items-center gap-1 bg-forest-50 px-3 py-1.5 rounded-full border border-forest-100">
                                + Thêm Khách
                            </button>
                        </div>

                        <div class="space-y-4">
                            <!-- Khách 1 -->
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&q=80&w=100" alt="Avatar" class="w-9 h-9 rounded-full object-cover">
                                    <div>
                                        <h4 class="text-xs font-bold text-slate-900">Nguyễn Nhật Hoàng</h4>
                                        <p class="text-[11px] text-slate-400 font-medium">Phòng P.201 • HĐ 12 tháng</p>
                                    </div>
                                </div>
                                <span class="px-2.5 py-1 text-[10px] font-semibold rounded-full bg-emerald-50 text-emerald-700 border border-emerald-100">Đã bàn giao</span>
                            </div>

                            <!-- Khách 2 -->
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&q=80&w=100" alt="Avatar" class="w-9 h-9 rounded-full object-cover">
                                    <div>
                                        <h4 class="text-xs font-bold text-slate-900">Lê Quang Bảo</h4>
                                        <p class="text-[11px] text-slate-400 font-medium">Phòng P.305 • HĐ 6 tháng</p>
                                    </div>
                                </div>
                                <span class="px-2.5 py-1 text-[10px] font-semibold rounded-full bg-amber-50 text-amber-700 border border-amber-100">Đang ở</span>
                            </div>

                            <!-- Khách 3 -->
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&q=80&w=100" alt="Avatar" class="w-9 h-9 rounded-full object-cover">
                                    <div>
                                        <h4 class="text-xs font-bold text-slate-900">Trần Quốc Đạt</h4>
                                        <p class="text-[11px] text-slate-400 font-medium">Phòng P.102 • Mới đặt cọc</p>
                                    </div>
                                </div>
                                <span class="px-2.5 py-1 text-[10px] font-semibold rounded-full bg-blue-50 text-blue-700 border border-blue-100">Chờ nhận phòng</span>
                            </div>
                        </div>
                    </div>

                    <!-- CỘT 2: TIẾN ĐỘ THU TIỀN THÁNG 10 (GAUGE PROGRESS STYLE) -->
                    <div class="lg:col-span-4 bg-white rounded-3xl p-6 border border-slate-100 shadow-card flex flex-col justify-between">
                        <div>
                            <h3 class="text-base font-bold text-slate-900">Tiến Độ Thu Tiền Tháng 10</h3>
                            <p class="text-xs text-slate-400">Tổng công nợ thu qua VietQR & tiền mặt</p>
                        </div>

                        <!-- Cung tròn tiến độ (Gauge Arc) giống 41% trong mẫu ảnh -->
                        <div class="flex flex-col items-center justify-center my-4">
                            <div class="relative w-44 h-24 overflow-hidden flex items-end justify-center">
                                <!-- Vòng nền xám cong -->
                                <div class="absolute top-0 w-44 h-44 rounded-full border-[18px] border-slate-100 border-b-transparent border-l-transparent"></div>
                                <!-- Đoạn màu xanh đậm Forest (đại diện 68%) -->
                                <div class="absolute top-0 w-44 h-44 rounded-full border-[18px] border-forest-950 border-b-transparent border-r-transparent transform -rotate-45"></div>
                                <!-- Đoạn họa tiết sọc -->
                                <div class="absolute top-0 w-44 h-44 rounded-full border-[18px] border-emerald-400/40 border-t-transparent border-r-transparent transform rotate-45"></div>
                                <!-- Số % chính giữa -->
                                <div class="text-center pb-1">
                                    <span class="text-3xl font-extrabold text-slate-900 tracking-tight">68%</span>
                                    <span class="block text-[10px] font-medium text-slate-400 uppercase tracking-wider">Đã thu tiền</span>
                                </div>
                            </div>
                        </div>

                        <!-- Chú thích Legend -->
                        <div class="flex items-center justify-center gap-4 text-xs font-semibold text-slate-600 pt-2 border-t border-slate-100">
                            <div class="flex items-center gap-1.5">
                                <span class="w-2.5 h-2.5 rounded-full bg-forest-950"></span>
                                <span>Đã thanh toán</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-400"></span>
                                <span>Đang chờ</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <span class="w-2.5 h-2.5 rounded-full bg-slate-300"></span>
                                <span>Quá hạn</span>
                            </div>
                        </div>
                    </div>

                    <!-- CỘT 3: ĐỒNG HỒ CA TRỰC & GẠCH NỢ NHANH (TIME TRACKER WIDGET) -->
                    <div class="lg:col-span-3 wave-bg rounded-3xl p-6 text-white flex flex-col justify-between shadow-card relative">
                        <div class="relative z-10">
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-xs font-semibold uppercase tracking-wider text-emerald-300">Ca Trực Hiện Tại</span>
                                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                            </div>
                            <p class="text-xs text-white/70">Quản lý: <?= htmlspecialchars($currentUsername) ?></p>
                        </div>

                        <!-- Giờ điện tử to nổi bật -->
                        <div class="relative z-10 my-4 text-center">
                            <div id="liveClock" class="text-3xl sm:text-4xl font-mono font-bold tracking-wider text-white drop-shadow-md">
                                18:35:46
                            </div>
                            <p class="text-[11px] text-emerald-200/80 mt-1">Đang hoạt động trong ca trực chiều</p>
                        </div>

                        <!-- Nút điều khiển ca trực -->
                        <div class="relative z-10 flex items-center justify-center gap-3">
                            <button title="Tạm dừng ca trực" class="w-10 h-10 rounded-full bg-white/15 hover:bg-white/25 flex items-center justify-center transition-all text-white backdrop-blur-sm">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/></svg>
                            </button>
                            <button title="Kết thúc ca & bàn giao" class="w-10 h-10 rounded-full bg-rose-500 hover:bg-rose-600 flex items-center justify-center transition-all text-white shadow-md shadow-rose-900/30">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M6 6h12v12H6z"/></svg>
                            </button>
                        </div>
                    </div>
                </div>

            </div>

            <!-- FOOTER BẢNG ĐIỀU HÀNH -->
            <footer class="p-6 text-center text-xs text-slate-400 border-t border-slate-200/60 mt-auto bg-white/40">
                Rentifia Management System • Thiết kế chuẩn UI/UX Responsive cho Quản lý tòa nhà
            </footer>
        </main>
    </div>

    <!-- SCRIPT ĐIỀU KHIỂN SIDEBAR & ĐỒNG HỒ CA TRỰC -->
    <script>
        // 1. Toggle Sidebar trên Mobile
        function toggleSidebar() {
            const sidebar = document.getElementById('mainSidebar');
            const backdrop = document.getElementById('sidebarBackdrop');
            const isHidden = sidebar.classList.contains('-translate-x-full');

            if (isHidden) {
                sidebar.classList.remove('-translate-x-full');
                backdrop.classList.remove('hidden');
            } else {
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.add('hidden');
            }
        }

        // 2. Đồng hồ số thời gian thực (Live Clock)
        function updateClock() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            const clockEl = document.getElementById('liveClock');
            if (clockEl) {
                clockEl.textContent = `${hours}:${minutes}:${seconds}`;
            }
        }
        setInterval(updateClock, 1000);
        updateClock();
    </script>
</body>

</html>
