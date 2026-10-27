<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$isLoggedIn = !empty($_SESSION['logged_in']);
$currentRole = $_SESSION['role'] ?? 'guest';
$currentUsername = $_SESSION['username'] ?? '';

$dashboardUrl = match ($currentRole) {
    'admin' => '/Rentifia/app/Views/admin/dashboard/index.php',
    'manager' => '/Rentifia/app/Views/manager/dashboard/index.php',
    'tenant' => '/Rentifia/app/Views/tenant/dashboard/index.php',
    default => '/Rentifia/app/Views/auth/login.php',
};
?>
<!DOCTYPE html>
<html lang="vi" class="scroll-smooth dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rentifia - Hệ Thống Căn Hộ Dịch Vụ & Thuê Trọ Thông Minh 4.0</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        dark: {
                            base: '#08090d',
                            surface: '#0f1118',
                            card: '#141722',
                            border: '#1f2433',
                        },
                        amber: {
                            400: '#fbbf24',
                            500: '#f59e0b',
                            600: '#d97706',
                        },
                        emerald: {
                            400: '#34d399',
                            500: '#10b981',
                            600: '#059669',
                        }
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        mono: ['"JetBrains Mono"', 'monospace'],
                    }
                }
            }
        }
    </script>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #08090d;
        }

        /* Ambient Glow & Grid Pattern từ cảm hứng video Showreel */
        .ambient-radial-glow {
            background-image:
                radial-gradient(circle at 50% 10%, rgba(245, 158, 11, 0.12) 0%, transparent 60%),
                radial-gradient(circle at 85% 30%, rgba(16, 185, 129, 0.08) 0%, transparent 50%),
                radial-gradient(circle at 15% 50%, rgba(139, 92, 246, 0.08) 0%, transparent 50%);
        }

        .wireframe-grid {
            background-size: 40px 40px;
            background-image:
                linear-gradient(to right, rgba(255, 255, 255, 0.025) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(255, 255, 255, 0.025) 1px, transparent 1px);
        }

        .glass-panel {
            background: rgba(20, 23, 34, 0.7);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        .glass-panel-hover:hover {
            border-color: rgba(245, 158, 11, 0.3);
            box-shadow: 0 10px 30px -10px rgba(245, 158, 11, 0.15);
        }

        /* Ẩn scrollbar thanh filter danh mục */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>

<body
    class="bg-[#08090d] text-slate-100 antialiased selection:bg-amber-500 selection:text-slate-950 min-h-screen flex flex-col">

    <!-- 1. TOP NAVBAR (GLASSMORPHISM VỚI BRAND RENTIFIA) -->
    <header
        class="fixed top-0 inset-x-0 z-50 bg-[#08090d]/80 backdrop-blur-xl border-b border-white/[0.06] transition-all">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <!-- Brand Logo -->
            <a href="/Rentifia/app/Views/index.php" class="flex items-center gap-3 group">
                <div class="relative w-9 h-9 flex items-center justify-center">
                    <span
                        class="w-3 h-3 rounded-md bg-amber-500 absolute -top-0.5 -left-0.5 shadow-sm shadow-amber-500/50 group-hover:scale-110 transition-transform"></span>
                    <span
                        class="w-3 h-3 rounded-md bg-emerald-400 absolute -top-0.5 -right-0.5 shadow-sm shadow-emerald-400/50 group-hover:scale-110 transition-transform"></span>
                    <span
                        class="w-3 h-3 rounded-md bg-emerald-400 absolute -bottom-0.5 -left-0.5 shadow-sm shadow-emerald-400/50 group-hover:scale-110 transition-transform"></span>
                    <span
                        class="w-3 h-3 rounded-md bg-amber-500 absolute -bottom-0.5 -right-0.5 shadow-sm shadow-amber-500/50 group-hover:scale-110 transition-transform"></span>
                </div>
                <div class="flex flex-col">
                    <span class="text-xl font-extrabold tracking-tight text-white leading-none">
                        Rent<span class="text-amber-400">ifia</span>
                    </span>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Smart Living
                        Space</span>
                </div>
            </a>

            <!-- Menu links -->
            <nav
                class="hidden md:flex items-center gap-8 text-xs font-semibold uppercase tracking-wider text-slate-300">
                <a href="#hero-trust" class="hover:text-amber-400 transition-colors">Trang chủ</a>
                <a href="#room-bento" class="hover:text-amber-400 transition-colors">Phòng Trống</a>
                <a href="#interactive-showcase" class="hover:text-amber-400 transition-colors">Nền Tảng 4.0</a>
                <a href="#features-value" class="hover:text-amber-400 transition-colors">Tiện Ích</a>
                <a href="#contact-cta" class="hover:text-amber-400 transition-colors">Liên hệ</a>
            </nav>

            <!-- User Auth Buttons -->
            <div class="flex items-center gap-3">
                <?php if ($isLoggedIn): ?>
                    <a href="<?= $dashboardUrl ?>"
                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-slate-950 font-bold text-xs tracking-wide shadow-lg shadow-amber-500/20 active:scale-95 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <rect x="3" y="3" width="7" height="7" rx="1.5" stroke-width="2" />
                            <rect x="14" y="3" width="7" height="7" rx="1.5" stroke-width="2" />
                            <rect x="14" y="14" width="7" height="7" rx="1.5" stroke-width="2" />
                            <rect x="3" y="14" width="7" height="7" rx="1.5" stroke-width="2" />
                        </svg>
                        <span>Dashboard (<?= ucfirst($currentRole) ?>)</span>
                    </a>
                    <a href="/Rentifia/app/Views/auth/logout.php" title="Đăng xuất khỏi hệ thống"
                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full border border-white/10 hover:border-rose-500/40 text-slate-300 hover:text-rose-400 text-xs font-semibold hover:bg-rose-500/10 transition-all">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span class="hidden sm:inline">Đăng xuất</span>
                    </a>
                <?php else: ?>
                    <a href="/Rentifia/app/Views/auth/login.php"
                        class="hidden sm:inline-flex items-center gap-2 text-xs font-bold text-slate-300 hover:text-white px-4 py-2 rounded-full transition-colors">
                        Đăng nhập
                    </a>
                    <a href="/Rentifia/app/Views/auth/register.php"
                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-slate-950 font-bold text-xs tracking-wide shadow-lg shadow-amber-500/20 active:scale-95 transition-all">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                clip-rule="evenodd" />
                        </svg>
                        <span>Đăng Ký Thuê Phòng</span>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <!-- 2. HERO SECTION & TRUST-BUILDING STATS (CẢM HỨNG BINANCE TRUST + TARS.AI HERO) -->
    <section id="hero-trust" class="pt-36 sm:pt-44 pb-20 relative wireframe-grid ambient-radial-glow overflow-hidden">
        <!-- Floating 3D/Tech Glow Orbs -->
        <div
            class="absolute top-1/4 left-10 w-24 h-24 rounded-full bg-amber-500/10 blur-2xl animate-pulse pointer-events-none">
        </div>
        <div class="absolute top-1/3 right-12 w-32 h-32 rounded-full bg-emerald-500/10 blur-3xl pointer-events-none">
        </div>

        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
            <!-- Header Badge (giống badge "Web Design" trong video) -->
            <div
                class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/[0.04] border border-white/10 backdrop-blur-md mb-8 shadow-sm">
                <span class="w-2 h-2 rounded-full bg-amber-400 animate-ping"></span>
                <span class="text-xs font-bold tracking-wider uppercase text-slate-200">
                    Hệ Thống Quản Trị Chuỗi Căn Hộ 4.0
                </span>
                <span
                    class="text-[10px] px-2 py-0.5 rounded-full bg-amber-500/20 text-amber-300 font-mono font-bold">VER
                    2026</span>
            </div>

            <!-- Con số uy tín khổng lồ (Cảm hứng mẫu 5: Binance "175M USERS TRUST US") -->
            <div class="space-y-3 mb-6">
                <h2
                    class="text-4xl sm:text-6xl lg:text-7xl font-black tracking-tight text-amber-400 font-mono uppercase drop-shadow-[0_0_35px_rgba(245,158,11,0.25)]">
                    2,500+ CƯ DÂN
                </h2>
                <h1
                    class="text-3xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight text-white uppercase leading-tight">
                    TIN TƯỞNG ĐỒNG HÀNH CÙNG RENTIFIA
                </h1>
            </div>

            <p class="max-w-2xl mx-auto text-sm sm:text-base text-slate-400 font-normal leading-relaxed mb-10">
                Trải nghiệm lưu trú minh bạch tuyệt đối: hợp đồng ký số, chốt số điện nước bằng ảnh thật định kỳ, thanh
                toán VietQR gạch nợ tức thì và ra vào vân tay tự do 24/7.
            </p>

            <!-- Ô nhập SĐT lấy báo giá & phòng trống nhanh (Cảm hứng thanh Lead Gen của Binance) -->
            <div class="max-w-xl mx-auto mb-10">
                <form onsubmit="handleQuickConsult(event)"
                    class="glass-panel p-2 rounded-full flex flex-col sm:flex-row items-center gap-2 shadow-2xl">
                    <div class="flex-1 flex items-center gap-3 pl-4 w-full">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                        <input type="text" id="phoneInput" placeholder="Nhập SĐT hoặc Zalo để nhận danh sách phòng..."
                            required
                            class="w-full bg-transparent text-xs sm:text-sm text-white placeholder-slate-500 focus:outline-none">
                    </div>
                    <button type="submit"
                        class="w-full sm:w-auto px-7 py-3.5 rounded-full bg-gradient-to-r from-amber-400 to-amber-500 hover:from-amber-300 hover:to-amber-400 text-slate-950 font-bold text-xs uppercase tracking-wider transition-all shadow-md shadow-amber-500/20 active:scale-95 whitespace-nowrap">
                        Nhận Báo Giá Ngay
                    </button>
                </form>
                <p id="consultMsg" class="text-xs text-emerald-400 font-medium mt-3 hidden">
                    ✓ Đã nhận thông tin! Chuyên viên Rentifia sẽ gửi ảnh và video phòng qua Zalo ngay.
                </p>
            </div>

            <!-- Cặp nút CTA kép nổi bật (Cảm hứng mẫu 4: Tars.ai) -->
            <div class="flex flex-wrap items-center justify-center gap-4 mb-16">
                <a href="#room-bento"
                    class="px-8 py-4 rounded-full bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold text-xs uppercase tracking-wider transition-all shadow-lg shadow-amber-500/25 active:scale-95 flex items-center gap-2">
                    <span>Xem Phòng Trống Có Sẵn</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </a>
                <a href="/Rentifia/app/Views/auth/login.php"
                    class="px-8 py-4 rounded-full bg-white/[0.05] hover:bg-white/[0.1] text-white font-bold text-xs uppercase tracking-wider border border-white/10 transition-all flex items-center gap-2">
                    <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                    </svg>
                    <span>Cổng Cư Dân Tra Cứu</span>
                </a>
            </div>

            <!-- Thanh 4 chỉ số thống kê minh bạch -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 max-w-4xl mx-auto pt-8 border-t border-white/[0.08]">
                <div class="glass-panel p-4 rounded-2xl text-center">
                    <p class="text-2xl sm:text-3xl font-extrabold text-white font-mono">200+</p>
                    <p class="text-[11px] text-slate-400 font-medium uppercase mt-1">Căn hộ / Phòng studio</p>
                </div>
                <div class="glass-panel p-4 rounded-2xl text-center">
                    <p class="text-2xl sm:text-3xl font-extrabold text-emerald-400 font-mono">98.5%</p>
                    <p class="text-[11px] text-slate-400 font-medium uppercase mt-1">Tỷ lệ hài lòng</p>
                </div>
                <div class="glass-panel p-4 rounded-2xl text-center">
                    <p class="text-2xl sm:text-3xl font-extrabold text-amber-400 font-mono">3s</p>
                    <p class="text-[11px] text-slate-400 font-medium uppercase mt-1">Gạch nợ VietQR</p>
                </div>
                <div class="glass-panel p-4 rounded-2xl text-center">
                    <p class="text-2xl sm:text-3xl font-extrabold text-purple-400 font-mono">100%</p>
                    <p class="text-[11px] text-slate-400 font-medium uppercase mt-1">Ảnh chụp điện nước</p>
                </div>
            </div>
        </div>
    </section>

    <!-- 3. INTERACTIVE SHOWCASE TABS (CẢM HỨNG MẪU 1: BINANCE DESKTOP | LITE | PRO TABS) -->
    <section id="interactive-showcase" class="py-24 bg-[#0a0c12] border-y border-white/[0.06] relative">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-12">
                <span
                    class="text-xs font-bold uppercase tracking-widest text-amber-400 bg-amber-500/10 px-3.5 py-1.5 rounded-full border border-amber-500/20">
                    Trải Nghiệm Đa Nền Tảng
                </span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight mt-4">
                    Vận Hành Mượt Mà. Mọi Lúc, Mọi Nơi.
                </h2>
                <p class="text-sm text-slate-400 mt-2">
                    Rentifia tối ưu 3 cổng tương tác chuyên biệt cho cư dân, ban quản lý và chủ chuỗi nhà trọ.
                </p>
            </div>

            <!-- Tab Switcher có thanh line vàng chạy dưới (y hệt mẫu Binance) -->
            <div class="flex justify-center border-b border-white/[0.08] mb-12 max-w-lg mx-auto">
                <button type="button" onclick="switchShowcaseTab('tenant')" id="tab-tenant-btn"
                    class="tab-btn flex-1 pb-4 text-xs sm:text-sm font-bold uppercase tracking-wider text-amber-400 border-b-2 border-amber-400 transition-all">
                    Cổng Cư Dân
                </button>
                <button type="button" onclick="switchShowcaseTab('manager')" id="tab-manager-btn"
                    class="tab-btn flex-1 pb-4 text-xs sm:text-sm font-bold uppercase tracking-wider text-slate-400 border-b-2 border-transparent hover:text-slate-200 transition-all">
                    Quản Lý Cơ Sở
                </button>
                <button type="button" onclick="switchShowcaseTab('admin')" id="tab-admin-btn"
                    class="tab-btn flex-1 pb-4 text-xs sm:text-sm font-bold uppercase tracking-wider text-slate-400 border-b-2 border-transparent hover:text-slate-200 transition-all">
                    Chủ Đầu Tư
                </button>
            </div>

            <!-- Nội dung Tab 1: Cổng Cư Dân -->
            <div id="content-tenant" class="showcase-content">
                <div
                    class="glass-panel rounded-3xl p-6 sm:p-10 border border-white/10 grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                    <div class="lg:col-span-6 space-y-5 text-left">
                        <span
                            class="px-3 py-1 rounded-full text-xs font-mono font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">TENANT
                            PORTAL</span>
                        <h3 class="text-2xl font-bold text-white">Tra Cứu Tiền Phòng & Điện Nước Minh Bạch</h3>
                        <p class="text-sm text-slate-300 leading-relaxed">
                            Cư dân có thể xem chi tiết tiền phòng từng tháng kèm hình ảnh chụp trực tiếp công tơ điện
                            nước. Thanh toán chỉ với 1 cú quét mã VietQR tự động, không cần chụp bill gửi quản lý.
                        </p>
                        <ul class="space-y-3 text-xs sm:text-sm text-slate-300 font-medium">
                            <li class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                                Hóa đơn điện tử đính kèm ảnh công tơ thực tế
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                                VietQR động tự điền số tiền và mã phòng
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                                Gửi yêu cầu sửa chữa thiết bị 24/7 trực tiếp
                            </li>
                        </ul>
                        <div class="pt-2">
                            <a href="/Rentifia/app/Views/tenant/dashboard/index.php"
                                class="inline-flex items-center gap-2 text-xs font-bold text-amber-400 hover:text-amber-300 uppercase tracking-wider">
                                <span>Khám phá Dashboard Cư Dân</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </a>
                        </div>
                    </div>
                    <div class="lg:col-span-6">
                        <div
                            class="rounded-2xl overflow-hidden border border-white/10 shadow-2xl bg-slate-900 aspect-[16/10]">
                            <img src="https://images.unsplash.com/photo-1556742049-0a67c5574f73?auto=format&fit=crop&w=800&q=80"
                                alt="Cư dân thanh toán VietQR" class="w-full h-full object-cover">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Nội dung Tab 2: Quản Lý Cơ Sở -->
            <div id="content-manager" class="showcase-content hidden">
                <div
                    class="glass-panel rounded-3xl p-6 sm:p-10 border border-white/10 grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                    <div class="lg:col-span-6 space-y-5 text-left">
                        <span
                            class="px-3 py-1 rounded-full text-xs font-mono font-bold bg-amber-500/10 text-amber-400 border border-amber-500/20">MANAGER
                            PORTAL</span>
                        <h3 class="text-2xl font-bold text-white">Chốt Số Điện Nước Cực Nhanh & Lập Hợp Đồng</h3>
                        <p class="text-sm text-slate-300 leading-relaxed">
                            Quản lý cầm điện thoại chụp ảnh công tơ điện nước từng tầng. Hệ thống tự động tính toán số
                            tiền và gửi thông báo cho cư dân trong 30 giây.
                        </p>
                        <ul class="space-y-3 text-xs sm:text-sm text-slate-300 font-medium">
                            <li class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                                Chụp & tải ảnh chỉ số trực tiếp lên cloud lưu trữ
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                                Báo cáo danh sách phòng chưa thanh toán để nhắc nhở
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                                Quản lý thông tin cư dân, xe máy và hợp đồng
                            </li>
                        </ul>
                        <div class="pt-2">
                            <a href="/Rentifia/app/Views/manager/dashboard/index.php"
                                class="inline-flex items-center gap-2 text-xs font-bold text-amber-400 hover:text-amber-300 uppercase tracking-wider">
                                <span>Xem Dashboard Quản Lý Cơ Sở</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </a>
                        </div>
                    </div>
                    <div class="lg:col-span-6">
                        <div
                            class="rounded-2xl overflow-hidden border border-white/10 shadow-2xl bg-slate-900 aspect-[16/10]">
                            <img src="https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&w=800&q=80"
                                alt="Bảng điều hành quản lý" class="w-full h-full object-cover">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Nội dung Tab 3: Chủ Đầu Tư (Admin) -->
            <div id="content-admin" class="showcase-content hidden">
                <div
                    class="glass-panel rounded-3xl p-6 sm:p-10 border border-white/10 grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                    <div class="lg:col-span-6 space-y-5 text-left">
                        <span
                            class="px-3 py-1 rounded-full text-xs font-mono font-bold bg-purple-500/10 text-purple-400 border border-purple-500/20">ADMIN
                            PORTAL</span>
                        <h3 class="text-2xl font-bold text-white">Quản Trị Chuỗi & Báo Cáo Dòng Tiền Tự Động</h3>
                        <p class="text-sm text-slate-300 leading-relaxed">
                            Nắm bắt toàn cảnh doanh thu thực thu, công nợ tồn đọng và tỷ lệ lấp đầy phòng của toàn bộ
                            chuỗi cơ sở trong một màn hình điều hành duy nhất.
                        </p>
                        <ul class="space-y-3 text-xs sm:text-sm text-slate-300 font-medium">
                            <li class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-purple-400"></span>
                                Biểu đồ dòng tiền thu chi minh bạch theo thời gian thực
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-purple-400"></span>
                                Sơ đồ 200 phòng phân theo tầng và trạng thái lấp đầy
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-purple-400"></span>
                                Phân quyền nhân sự quản lý chi nhánh linh hoạt
                            </li>
                        </ul>
                        <div class="pt-2">
                            <a href="/Rentifia/app/Views/admin/dashboard/index.php"
                                class="inline-flex items-center gap-2 text-xs font-bold text-amber-400 hover:text-amber-300 uppercase tracking-wider">
                                <span>Xem Dashboard Chủ Đầu Tư</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </a>
                        </div>
                    </div>
                    <div class="lg:col-span-6">
                        <div
                            class="rounded-2xl overflow-hidden border border-white/10 shadow-2xl bg-slate-900 aspect-[16/10]">
                            <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?auto=format&fit=crop&w=800&q=80"
                                alt="Báo cáo phân tích tài chính" class="w-full h-full object-cover">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 4. POLYMARKET-INSPIRED FILTER & BENTO ROOMS GRID (CẢM HỨNG MẪU 2: BENTO CARDS + PILL FILTERS) -->
    <section id="room-bento" class="py-24 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-8 gap-4">
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span class="text-xs font-bold uppercase tracking-wider text-emerald-400 font-mono">LIVE
                        INVENTORY</span>
                </div>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight">
                    Danh Sách Căn Hộ Sẵn Sàng Dọn Vào Ở
                </h2>
                <p class="text-xs sm:text-sm text-slate-400 mt-1">
                    Cập nhật tình trạng phòng trống thực tế theo thời gian thực tại các chi nhánh Rentifia
                </p>
            </div>
            <div class="text-xs text-slate-400 font-mono">
                Hiển thị <span id="roomCount" class="text-amber-400 font-bold">4</span> phòng phù hợp
            </div>
        </div>

        <!-- Thanh bộ lọc dạng Category Pills nằm ngang (Cảm hứng Polymarket) -->
        <div class="flex items-center gap-2 overflow-x-auto no-scrollbar pb-6 mb-8 border-b border-white/[0.06]">
            <button type="button" onclick="filterRooms('all', this)"
                class="filter-pill px-4 py-2 rounded-full text-xs font-bold whitespace-nowrap bg-amber-500 text-slate-950 transition-all shadow-sm">
                Tất cả phòng (4)
            </button>
            <button type="button" onclick="filterRooms('available', this)"
                class="filter-pill px-4 py-2 rounded-full text-xs font-bold whitespace-nowrap bg-white/[0.04] text-slate-300 hover:bg-white/[0.08] hover:text-white border border-white/[0.08] transition-all">
                Đang trống dọn ngay
            </button>
            <button type="button" onclick="filterRooms('studio', this)"
                class="filter-pill px-4 py-2 rounded-full text-xs font-bold whitespace-nowrap bg-white/[0.04] text-slate-300 hover:bg-white/[0.08] hover:text-white border border-white/[0.08] transition-all">
                Studio ban công
            </button>
            <button type="button" onclick="filterRooms('duplex', this)"
                class="filter-pill px-4 py-2 rounded-full text-xs font-bold whitespace-nowrap bg-white/[0.04] text-slate-300 hover:bg-white/[0.08] hover:text-white border border-white/[0.08] transition-all">
                Duplex gác cao
            </button>
            <button type="button" onclick="filterRooms('1pn', this)"
                class="filter-pill px-4 py-2 rounded-full text-xs font-bold whitespace-nowrap bg-white/[0.04] text-slate-300 hover:bg-white/[0.08] hover:text-white border border-white/[0.08] transition-all">
                1 Phòng ngủ riêng
            </button>
            <button type="button" onclick="filterRooms('q7', this)"
                class="filter-pill px-4 py-2 rounded-full text-xs font-bold whitespace-nowrap bg-white/[0.04] text-slate-300 hover:bg-white/[0.08] hover:text-white border border-white/[0.08] transition-all">
                Chi nhánh Q7
            </button>
            <button type="button" onclick="filterRooms('binhthanh', this)"
                class="filter-pill px-4 py-2 rounded-full text-xs font-bold whitespace-nowrap bg-white/[0.04] text-slate-300 hover:bg-white/[0.08] hover:text-white border border-white/[0.08] transition-all">
                Bình Thạnh
            </button>
        </div>

        <!-- Lưới 4 Cột Bento Grid (Polymarket Layout) -->
        <div id="roomsContainer" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

            <!-- Card 1: Studio Q7 -->
            <div class="room-card glass-panel rounded-3xl overflow-hidden glass-panel-hover transition-all flex flex-col justify-between group"
                data-category="available studio q7">
                <div>
                    <!-- Image with Badge -->
                    <div class="relative aspect-[16/11] overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?auto=format&fit=crop&w=700&q=80"
                            alt="Phòng Studio Q7"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <span
                            class="absolute top-3 left-3 px-2.5 py-1 rounded-full text-[11px] font-bold uppercase bg-emerald-500 text-slate-950 shadow-md">
                            Đang Trống
                        </span>
                        <span
                            class="absolute bottom-3 right-3 px-2.5 py-0.5 rounded-md text-[11px] font-mono font-bold bg-black/60 text-white backdrop-blur-md">
                            28 m² • Tầng 2
                        </span>
                    </div>

                    <!-- Room Info -->
                    <div class="p-5">
                        <p class="text-[11px] font-bold uppercase text-amber-400 font-mono">Q7 • Him Lam</p>
                        <h3
                            class="text-base font-bold text-white group-hover:text-amber-400 transition-colors mt-1 line-clamp-1">
                            Studio Scandinavian Ban Công
                        </h3>

                        <!-- Utilities -->
                        <div class="flex flex-wrap gap-1.5 mt-3">
                            <span
                                class="px-2 py-0.5 rounded-md text-[10px] bg-white/[0.05] text-slate-300 border border-white/5">Máy
                                lạnh</span>
                            <span
                                class="px-2 py-0.5 rounded-md text-[10px] bg-white/[0.05] text-slate-300 border border-white/5">Tủ
                                lạnh 2 cánh</span>
                            <span
                                class="px-2 py-0.5 rounded-md text-[10px] bg-white/[0.05] text-slate-300 border border-white/5">Khóa
                                vân tay</span>
                        </div>
                    </div>
                </div>

                <!-- Card Bottom (Giá & 2 Nút CTA) -->
                <div class="p-5 pt-0">
                    <div class="flex items-baseline justify-between mb-4 border-t border-white/[0.06] pt-3">
                        <span class="text-xs text-slate-400 font-medium">Giá thuê:</span>
                        <div class="text-right">
                            <span class="text-lg font-black text-amber-400 font-mono">5.500.000</span>
                            <span class="text-[11px] text-slate-400"> đ/tháng</span>
                        </div>
                    </div>
                    <!-- 2 Nút CTA rõ ràng (Xanh / Đen như mẫu Polymarket) -->
                    <div class="grid grid-cols-2 gap-2">
                        <a href="https://zalo.me" target="_blank"
                            class="py-2.5 rounded-xl bg-emerald-500/15 hover:bg-emerald-500 text-emerald-400 hover:text-slate-950 font-bold text-xs text-center border border-emerald-500/30 transition-all">
                            Giữ Phòng
                        </a>
                        <a href="/Rentifia/app/Views/auth/login.php"
                            class="py-2.5 rounded-xl bg-white/[0.06] hover:bg-white/10 text-slate-200 font-bold text-xs text-center border border-white/10 transition-all">
                            Chi Tiết
                        </a>
                    </div>
                </div>
            </div>

            <!-- Card 2: Duplex Bình Thạnh -->
            <div class="room-card glass-panel rounded-3xl overflow-hidden glass-panel-hover transition-all flex flex-col justify-between group"
                data-category="available duplex binhthanh">
                <div>
                    <div class="relative aspect-[16/11] overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?auto=format&fit=crop&w=700&q=80"
                            alt="Duplex Bình Thạnh"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <span
                            class="absolute top-3 left-3 px-2.5 py-1 rounded-full text-[11px] font-bold uppercase bg-amber-500 text-slate-950 shadow-md">
                            Phòng Hot
                        </span>
                        <span
                            class="absolute bottom-3 right-3 px-2.5 py-0.5 rounded-md text-[11px] font-mono font-bold bg-black/60 text-white backdrop-blur-md">
                            32 m² • Tầng 3
                        </span>
                    </div>

                    <div class="p-5">
                        <p class="text-[11px] font-bold uppercase text-amber-400 font-mono">Bình Thạnh • Đường D2</p>
                        <h3
                            class="text-base font-bold text-white group-hover:text-amber-400 transition-colors mt-1 line-clamp-1">
                            Duplex Gác Cao Không Đụng Đầu
                        </h3>

                        <div class="flex flex-wrap gap-1.5 mt-3">
                            <span
                                class="px-2 py-0.5 rounded-md text-[10px] bg-white/[0.05] text-slate-300 border border-white/5">Gác
                                cao 2m</span>
                            <span
                                class="px-2 py-0.5 rounded-md text-[10px] bg-white/[0.05] text-slate-300 border border-white/5">Máy
                                giặt riêng</span>
                            <span
                                class="px-2 py-0.5 rounded-md text-[10px] bg-white/[0.05] text-slate-300 border border-white/5">Cửa
                                sổ trời</span>
                        </div>
                    </div>
                </div>

                <div class="p-5 pt-0">
                    <div class="flex items-baseline justify-between mb-4 border-t border-white/[0.06] pt-3">
                        <span class="text-xs text-slate-400 font-medium">Giá thuê:</span>
                        <div class="text-right">
                            <span class="text-lg font-black text-amber-400 font-mono">6.200.000</span>
                            <span class="text-[11px] text-slate-400"> đ/tháng</span>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <a href="https://zalo.me" target="_blank"
                            class="py-2.5 rounded-xl bg-emerald-500/15 hover:bg-emerald-500 text-emerald-400 hover:text-slate-950 font-bold text-xs text-center border border-emerald-500/30 transition-all">
                            Giữ Phòng
                        </a>
                        <a href="/Rentifia/app/Views/auth/login.php"
                            class="py-2.5 rounded-xl bg-white/[0.06] hover:bg-white/10 text-slate-200 font-bold text-xs text-center border border-white/10 transition-all">
                            Chi Tiết
                        </a>
                    </div>
                </div>
            </div>

            <!-- Card 3: 1PN Phú Nhuận -->
            <div class="room-card glass-panel rounded-3xl overflow-hidden glass-panel-hover transition-all flex flex-col justify-between group"
                data-category="available 1pn">
                <div>
                    <div class="relative aspect-[16/11] overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1493809842364-78817add7ffb?auto=format&fit=crop&w=700&q=80"
                            alt="1 Phòng ngủ Phú Nhuận"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <span
                            class="absolute top-3 left-3 px-2.5 py-1 rounded-full text-[11px] font-bold uppercase bg-purple-500 text-white shadow-md">
                            Mới 100%
                        </span>
                        <span
                            class="absolute bottom-3 right-3 px-2.5 py-0.5 rounded-md text-[11px] font-mono font-bold bg-black/60 text-white backdrop-blur-md">
                            38 m² • Tầng 4
                        </span>
                    </div>

                    <div class="p-5">
                        <p class="text-[11px] font-bold uppercase text-amber-400 font-mono">Phú Nhuận • Phan Xích Long
                        </p>
                        <h3
                            class="text-base font-bold text-white group-hover:text-amber-400 transition-colors mt-1 line-clamp-1">
                            1 Phòng Ngủ Riêng Biệt View Cây Xanh
                        </h3>

                        <div class="flex flex-wrap gap-1.5 mt-3">
                            <span
                                class="px-2 py-0.5 rounded-md text-[10px] bg-white/[0.05] text-slate-300 border border-white/5">Bếp
                                riêng</span>
                            <span
                                class="px-2 py-0.5 rounded-md text-[10px] bg-white/[0.05] text-slate-300 border border-white/5">Smart
                                TV 50"</span>
                            <span
                                class="px-2 py-0.5 rounded-md text-[10px] bg-white/[0.05] text-slate-300 border border-white/5">Sofa
                                da cao cấp</span>
                        </div>
                    </div>
                </div>

                <div class="p-5 pt-0">
                    <div class="flex items-baseline justify-between mb-4 border-t border-white/[0.06] pt-3">
                        <span class="text-xs text-slate-400 font-medium">Giá thuê:</span>
                        <div class="text-right">
                            <span class="text-lg font-black text-amber-400 font-mono">7.800.000</span>
                            <span class="text-[11px] text-slate-400"> đ/tháng</span>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <a href="https://zalo.me" target="_blank"
                            class="py-2.5 rounded-xl bg-emerald-500/15 hover:bg-emerald-500 text-emerald-400 hover:text-slate-950 font-bold text-xs text-center border border-emerald-500/30 transition-all">
                            Giữ Phòng
                        </a>
                        <a href="/Rentifia/app/Views/auth/login.php"
                            class="py-2.5 rounded-xl bg-white/[0.06] hover:bg-white/10 text-slate-200 font-bold text-xs text-center border border-white/10 transition-all">
                            Chi Tiết
                        </a>
                    </div>
                </div>
            </div>

            <!-- Card 4: Studio Thủ Đức -->
            <div class="room-card glass-panel rounded-3xl overflow-hidden glass-panel-hover transition-all flex flex-col justify-between group"
                data-category="available studio">
                <div>
                    <div class="relative aspect-[16/11] overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?auto=format&fit=crop&w=700&q=80"
                            alt="Studio Thủ Đức"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <span
                            class="absolute top-3 left-3 px-2.5 py-1 rounded-full text-[11px] font-bold uppercase bg-emerald-500 text-slate-950 shadow-md">
                            Đang Trống
                        </span>
                        <span
                            class="absolute bottom-3 right-3 px-2.5 py-0.5 rounded-md text-[11px] font-mono font-bold bg-black/60 text-white backdrop-blur-md">
                            26 m² • Tầng 1
                        </span>
                    </div>

                    <div class="p-5">
                        <p class="text-[11px] font-bold uppercase text-amber-400 font-mono">Thủ Đức • Gần ĐHQG</p>
                        <h3
                            class="text-base font-bold text-white group-hover:text-amber-400 transition-colors mt-1 line-clamp-1">
                            Studio Tối Giản Cho Sinh Viên / IT
                        </h3>

                        <div class="flex flex-wrap gap-1.5 mt-3">
                            <span
                                class="px-2 py-0.5 rounded-md text-[10px] bg-white/[0.05] text-slate-300 border border-white/5">Wi-Fi
                                6 riêng</span>
                            <span
                                class="px-2 py-0.5 rounded-md text-[10px] bg-white/[0.05] text-slate-300 border border-white/5">Bàn
                                làm việc</span>
                            <span
                                class="px-2 py-0.5 rounded-md text-[10px] bg-white/[0.05] text-slate-300 border border-white/5">Giờ
                                giấc tự do</span>
                        </div>
                    </div>
                </div>

                <div class="p-5 pt-0">
                    <div class="flex items-baseline justify-between mb-4 border-t border-white/[0.06] pt-3">
                        <span class="text-xs text-slate-400 font-medium">Giá thuê:</span>
                        <div class="text-right">
                            <span class="text-lg font-black text-amber-400 font-mono">4.500.000</span>
                            <span class="text-[11px] text-slate-400"> đ/tháng</span>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <a href="https://zalo.me" target="_blank"
                            class="py-2.5 rounded-xl bg-emerald-500/15 hover:bg-emerald-500 text-emerald-400 hover:text-slate-950 font-bold text-xs text-center border border-emerald-500/30 transition-all">
                            Giữ Phòng
                        </a>
                        <a href="/Rentifia/app/Views/auth/login.php"
                            class="py-2.5 rounded-xl bg-white/[0.06] hover:bg-white/10 text-slate-200 font-bold text-xs text-center border border-white/10 transition-all">
                            Chi Tiết
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- 5. 4 TRỤ CỘT GIÁ TRỊ CỐT LÕI (VALUE PROPOSITIONS) -->
    <section id="features-value" class="py-20 bg-[#090b10] border-t border-white/[0.06]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <span
                    class="text-xs font-bold uppercase tracking-widest text-emerald-400 bg-emerald-500/10 px-3 py-1 rounded-full border border-emerald-500/20">
                    Sự Khác Biệt Tại Rentifia
                </span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight mt-3">
                    Xóa Bỏ Mọi Nỗi Lo Thuê Trọ Truyền Thống
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Feature 1 -->
                <div class="glass-panel p-8 rounded-3xl hover:border-amber-400/40 transition-all group">
                    <div
                        class="w-12 h-12 rounded-2xl bg-amber-500/10 text-amber-400 flex items-center justify-center text-xl mb-6 group-hover:scale-110 transition-transform">
                        📸
                    </div>
                    <h3 class="text-base font-bold text-white mb-2">Chốt Điện Nước Kèm Ảnh</h3>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        Định kỳ ngày 25 hàng tháng, quản lý chụp ảnh mặt đồng hồ làm bằng chứng đối chiếu trực tiếp trên
                        hệ thống. Không bao giờ lo bị tính gian số.
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="glass-panel p-8 rounded-3xl hover:border-emerald-400/40 transition-all group">
                    <div
                        class="w-12 h-12 rounded-2xl bg-emerald-500/10 text-emerald-400 flex items-center justify-center text-xl mb-6 group-hover:scale-110 transition-transform">
                        ⚡
                    </div>
                    <h3 class="text-base font-bold text-white mb-2">Gạch Nợ VietQR Trong 3s</h3>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        Mã VietQR động tự sinh chính xác từng đồng theo hóa đơn. Quét là thanh toán xong, hệ thống tự
                        động gạch nợ mà không cần chụp gửi ủy nhiệm chi.
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="glass-panel p-8 rounded-3xl hover:border-purple-400/40 transition-all group">
                    <div
                        class="w-12 h-12 rounded-2xl bg-purple-500/10 text-purple-400 flex items-center justify-center text-xl mb-6 group-hover:scale-110 transition-transform">
                        📑
                    </div>
                    <h3 class="text-base font-bold text-white mb-2">Hợp Đồng Số Hóa Chuẩn</h3>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        Điều khoản thuê phòng, danh mục thiết bị bàn giao và tiền cọc được lưu trữ an toàn, tra cứu hợp
                        đồng trực tuyến bất cứ lúc nào trên điện thoại.
                    </p>
                </div>

                <!-- Feature 4 -->
                <div class="glass-panel p-8 rounded-3xl hover:border-blue-400/40 transition-all group">
                    <div
                        class="w-12 h-12 rounded-2xl bg-blue-500/10 text-blue-400 flex items-center justify-center text-xl mb-6 group-hover:scale-110 transition-transform">
                        🔒
                    </div>
                    <h3 class="text-base font-bold text-white mb-2">An Ninh Khóa Vân Tay 24/7</h3>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        Cửa ra vào vân tay độc lập, hệ thống camera AI giám sát khu vực chung và giờ giấc hoàn toàn tự
                        do cho mọi cư dân.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- 6. CONTACT & CALL TO ACTION BANNER (CẢM HỨNG CTA MẠNH TARS.AI) -->
    <section id="contact-cta" class="py-20 relative wireframe-grid">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div
                class="glass-panel rounded-[2.5rem] p-8 sm:p-14 border border-amber-500/20 relative overflow-hidden text-center">
                <!-- Amber Radial Glow -->
                <div
                    class="absolute -top-24 left-1/2 -translate-x-1/2 w-96 h-96 bg-amber-500/15 rounded-full blur-3xl pointer-events-none">
                </div>

                <div class="relative z-10 max-w-2xl mx-auto space-y-4">
                    <span
                        class="text-xs font-bold uppercase tracking-widest text-amber-400 bg-amber-500/10 px-3.5 py-1.5 rounded-full border border-amber-500/20">
                        Đặt Lịch Ngay Hôm Nay
                    </span>
                    <h2 class="text-3xl sm:text-5xl font-extrabold text-white tracking-tight">
                        Sẵn Sàng Trải Nghiệm Không Gian Sống Chuẩn 4.0?
                    </h2>
                    <p class="text-sm text-slate-300">
                        Đội ngũ quản lý Rentifia sẵn sàng hỗ trợ bạn xem phòng trực tiếp hoặc qua video call ngay trong
                        ngày.
                    </p>
                    <div class="pt-4 flex flex-wrap items-center justify-center gap-4">
                        <a href="tel:0912345678"
                            class="px-8 py-4 rounded-full bg-gradient-to-r from-amber-400 to-amber-500 hover:from-amber-300 hover:to-amber-400 text-slate-950 font-extrabold text-xs uppercase tracking-wider transition-all shadow-xl shadow-amber-500/20 flex items-center gap-2 active:scale-95">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 4V3z" />
                            </svg>
                            <span>Gọi Hotline: 0912.345.678</span>
                        </a>
                        <a href="/Rentifia/app/Views/auth/login.php"
                            class="px-8 py-4 rounded-full bg-white/[0.06] hover:bg-white/[0.12] text-white font-bold text-xs uppercase tracking-wider border border-white/10 transition-all flex items-center gap-2">
                            <span>Đăng Nhập Cổng Dịch Vụ</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 7. FOOTER -->
    <footer class="mt-auto py-12 bg-[#06070a] border-t border-white/[0.06] text-xs text-slate-400">
        <div
            class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="flex items-center gap-3">
                <span class="text-base font-extrabold text-white">Rent<span class="text-amber-400">ifia</span></span>
                <span class="text-slate-600">|</span>
                <span>Hệ thống căn hộ dịch vụ cao cấp thế hệ mới</span>
            </div>
            <div class="flex items-center gap-6">
                <span>Chi nhánh: Q7 • Bình Thạnh • Phú Nhuận • Thủ Đức</span>
            </div>
            <p class="text-slate-500 font-mono">
                ©
                <?= date('Y') ?> Rentifia VN. All rights reserved.
            </p>
        </div>
    </footer>

    <!-- INTERACTIVE JAVASCRIPT LOGIC -->
    <script>
        // 1. Chuyển đổi Showcase Tabs (Cảm hứng Binance Tabs)
        function switchShowcaseTab(tabName) {
            const tabs = ['tenant', 'manager', 'admin'];
            tabs.forEach(t => {
                const btn = document.getElementById(`tab-${t}-btn`);
                const content = document.getElementById(`content-${t}`);
                if (t === tabName) {
                    btn.classList.remove('text-slate-400', 'border-transparent');
                    btn.classList.add('text-amber-400', 'border-amber-400');
                    content.classList.remove('hidden');
                } else {
                    btn.classList.remove('text-amber-400', 'border-amber-400');
                    btn.classList.add('text-slate-400', 'border-transparent');
                    content.classList.add('hidden');
                }
            });
        }

        // 2. Lọc danh sách phòng theo Category Pills (Cảm hứng Polymarket)
        function filterRooms(category, clickedBtn) {
            // Đổi active style cho nút filter
            document.querySelectorAll('.filter-pill').forEach(btn => {
                btn.classList.remove('bg-amber-500', 'text-slate-950');
                btn.classList.add('bg-white/[0.04]', 'text-slate-300', 'border', 'border-white/[0.08]');
            });
            clickedBtn.classList.remove('bg-white/[0.04]', 'text-slate-300', 'border', 'border-white/[0.08]');
            clickedBtn.classList.add('bg-amber-500', 'text-slate-950');

            // Lọc cards
            const cards = document.querySelectorAll('.room-card');
            let visibleCount = 0;
            cards.forEach(card => {
                const cardCats = card.getAttribute('data-category');
                if (category === 'all' || cardCats.includes(category)) {
                    card.classList.remove('hidden');
                    visibleCount++;
                } else {
                    card.classList.add('hidden');
                }
            });
            document.getElementById('roomCount').innerText = visibleCount;
        }

        // 3. Xử lý form nhận tư vấn nhanh
        function handleQuickConsult(e) {
            e.preventDefault();
            const phone = document.getElementById('phoneInput').value;
            if (phone) {
                document.getElementById('consultMsg').classList.remove('hidden');
                document.getElementById('phoneInput').value = '';
            }
        }
    </script>

</body>

</html>