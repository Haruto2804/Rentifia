<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$currentUsername = $_SESSION['username'] ?? 'nhathoang_tenent';
$currentRole     = $_SESSION['role'] ?? 'tenant';
$currentEmail    = $_SESSION['email'] ?? 'nhathoang.tenant@rentifia.vn';
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rentifia - Cổng Thông Tin Cư Dân Lưu Trú</title>
    
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
                            950: '#0d3820',
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
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-thumb {
            background: #CBD5E1;
            border-radius: 9999px;
        }
    </style>
</head>

<body class="text-slate-800 antialiased p-3 sm:p-5 lg:p-6 min-h-screen flex flex-col justify-between">

    <div class="bg-white rounded-[2rem] sm:rounded-[2.5rem] shadow-soft border border-slate-100/80 flex flex-col lg:flex-row overflow-hidden w-full max-w-[1680px] mx-auto min-h-[92vh]">

        <!-- SIDEBAR TRÁI CỦA CƯ DÂN -->
        <aside class="w-full lg:w-72 bg-white border-r border-slate-100 flex flex-col justify-between p-6">
            <div class="space-y-8">
                <!-- Brand Logo -->
                <div class="flex items-center justify-between">
                    <a href="/Rentifia/app/Views/tenant/dashboard/index.php" class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-forest-950 flex items-center justify-center text-white shadow-md shadow-forest-950/20">
                            <svg class="w-5 h-5 text-forest-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-xl font-bold tracking-tight text-slate-900">Rentifia</span>
                            <span class="text-[10px] font-semibold uppercase tracking-wider text-emerald-700 bg-emerald-50 px-1.5 py-0.5 rounded-md self-start">Cư Dân Lưu Trú</span>
                        </div>
                    </a>
                </div>

                <!-- Navigation -->
                <div class="space-y-1">
                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400 px-3 mb-2">Tiện ích cư dân</p>

                    <!-- Trang chủ Portal -->
                    <a href="/Rentifia/app/Views/tenant/dashboard/index.php" class="flex items-center justify-between px-3.5 py-3 rounded-2xl bg-forest-50/80 text-forest-950 font-semibold text-sm">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-xl bg-forest-950 text-white flex items-center justify-center">
                                <svg class="w-4 h-4 text-forest-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
                            </div>
                            <span>Phòng của tôi</span>
                        </div>
                        <span class="w-2 h-2 rounded-full bg-forest-600"></span>
                    </a>

                    <!-- Hóa đơn & Thanh toán -->
                    <a href="#" class="flex items-center justify-between px-3.5 py-3 rounded-2xl text-slate-600 hover:bg-slate-50 font-medium text-sm">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z"/></svg>
                            <span>Hóa đơn tháng</span>
                        </div>
                        <span class="px-2 py-0.5 text-[10px] font-bold rounded-full bg-rose-50 text-rose-700">Chưa đóng</span>
                    </a>

                    <!-- Hợp đồng phòng -->
                    <a href="#" class="flex items-center gap-3 px-3.5 py-3 rounded-2xl text-slate-600 hover:bg-slate-50 font-medium text-sm">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <span>Hợp đồng lưu trú</span>
                    </a>

                    <!-- Báo hỏng / Sự cố -->
                    <a href="#" class="flex items-center justify-between px-3.5 py-3 rounded-2xl text-slate-600 hover:bg-slate-50 font-medium text-sm">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            <span>Gửi báo hỏng</span>
                        </div>
                    </a>
                </div>

                <div class="space-y-1 pt-2 border-t border-slate-100">
                    <a href="/Rentifia/app/Views/auth/login.php" class="flex items-center gap-3 px-3.5 py-2.5 rounded-2xl text-rose-600 hover:bg-rose-50 font-medium text-sm">
                        <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        <span>Đăng xuất</span>
                    </a>
                </div>
            </div>

            <!-- Hotline Chủ Nhà Card -->
            <div class="mt-8 rounded-3xl p-5 bg-slate-50 border border-slate-200/70 text-slate-700">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Hỗ trợ khẩn cấp</span>
                <p class="text-xs font-semibold text-slate-900">Ban Quản Lý Tòa Nhà</p>
                <p class="text-sm font-mono font-bold text-forest-700 mt-1">📞 0988.123.456</p>
            </div>
        </aside>

        <!-- MAIN CONTENT CƯ DÂN -->
        <main class="flex-1 flex flex-col bg-[#F7F8FA] min-w-0 overflow-y-auto">
            <!-- Topbar -->
            <header class="h-20 px-6 sm:px-8 border-b border-slate-200/70 bg-white/80 backdrop-blur-md flex items-center justify-between sticky top-0 z-30">
                <div>
                    <h2 class="text-base sm:text-lg font-bold text-slate-900">Xin chào, <?= htmlspecialchars($currentUsername) ?> 👋</h2>
                    <p class="text-xs text-slate-400">Phòng 301 • Tòa Sunshine Center • Tầng 3</p>
                </div>
                <div class="flex items-center gap-3">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-emerald-50 text-emerald-700 text-xs font-semibold border border-emerald-200/60">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        Đang cư trú hợp lệ
                    </span>
                </div>
            </header>

            <!-- Dashboard Content -->
            <div class="p-5 sm:p-8 space-y-6 max-w-7xl">
                <!-- THẺ HÓA ĐƠN THÁNG 10 NỔI BẬT NHẤT (CÓ VIETQR THANH TOÁN) -->
                <div class="bg-gradient-to-br from-forest-950 via-slate-900 to-forest-900 rounded-3xl p-6 sm:p-8 text-white relative overflow-hidden shadow-card">
                    <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                        <div class="space-y-2">
                            <span class="inline-block px-3 py-1 rounded-full bg-white/10 text-emerald-300 text-xs font-semibold uppercase tracking-wider">Hóa đơn kỳ Tháng 10/2026</span>
                            <div class="flex items-baseline gap-2">
                                <span class="text-3xl sm:text-4xl font-extrabold tracking-tight">3.450.000</span>
                                <span class="text-lg text-white/70 font-semibold">VNĐ</span>
                            </div>
                            <p class="text-xs text-white/70">Bao gồm: Tiền phòng (3.000.000đ) + Tiền Điện (350.000đ) + Tiền Nước (100.000đ)</p>
                            <p class="text-xs text-amber-300 font-medium">⚠️ Hạn thanh toán: Trước 17:00 ngày 28/10/2026</p>
                        </div>
                        <div class="flex flex-col sm:flex-row items-center gap-3">
                            <button class="w-full sm:w-auto px-6 py-3.5 rounded-2xl bg-forest-500 hover:bg-forest-400 text-slate-950 font-bold text-sm shadow-lg shadow-forest-500/25 flex items-center justify-center gap-2 transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                                <span>Quét Mã VietQR Thanh Toán</span>
                            </button>
                            <button class="w-full sm:w-auto px-5 py-3.5 rounded-2xl bg-white/10 hover:bg-white/20 text-white font-semibold text-sm border border-white/15 transition-all">
                                Xem Phiếu Báo Tiền
                            </button>
                        </div>
                    </div>
                </div>

                <!-- 3 THẺ CHỈ SỐ: ĐIỆN, NƯỚC, TIỀN CỌC -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <!-- Điện -->
                    <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-card">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-xs font-semibold text-slate-500">Chỉ số Điện T10</span>
                            <span class="w-7 h-7 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-xs">⚡</span>
                        </div>
                        <div class="text-3xl font-extrabold text-slate-900">124 <span class="text-sm font-normal text-slate-400">kWh</span></div>
                        <div class="mt-2 text-xs text-slate-500">Số cũ: 1.250 ➔ Số mới: 1.374</div>
                        <a href="#" class="mt-3 inline-block text-xs font-semibold text-forest-700 hover:underline">Xem ảnh chụp công tơ đối chứng →</a>
                    </div>

                    <!-- Nước -->
                    <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-card">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-xs font-semibold text-slate-500">Chỉ số Nước T10</span>
                            <span class="w-7 h-7 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-xs">💧</span>
                        </div>
                        <div class="text-3xl font-extrabold text-slate-900">8 <span class="text-sm font-normal text-slate-400">m³</span></div>
                        <div class="mt-2 text-xs text-slate-500">Số cũ: 82 ➔ Số mới: 90</div>
                        <a href="#" class="mt-3 inline-block text-xs font-semibold text-forest-700 hover:underline">Xem ảnh chụp đồng hồ nước →</a>
                    </div>

                    <!-- Hợp đồng & Cọc -->
                    <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-card">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-xs font-semibold text-slate-500">Tiền cọc giữ chỗ</span>
                            <span class="w-7 h-7 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-xs">🛡️</span>
                        </div>
                        <div class="text-3xl font-extrabold text-slate-900">3.000.000 <span class="text-sm font-normal text-slate-400">đ</span></div>
                        <div class="mt-2 text-xs text-emerald-700 font-medium">Bảo lưu đến hết hạn HĐ (15/04/2027)</div>
                        <a href="#" class="mt-3 inline-block text-xs font-semibold text-forest-700 hover:underline">Tải hợp đồng điện tử PDF →</a>
                    </div>
                </div>

                <!-- BÁO HỎNG & THÀNH VIÊN TRONG PHÒNG -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
                    <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-card">
                        <h3 class="text-base font-bold text-slate-900 mb-1">Thành Viên Cùng Phòng (P.301)</h3>
                        <p class="text-xs text-slate-400 mb-4">Danh sách cư dân đăng ký tạm trú</p>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between p-3 rounded-2xl bg-slate-50 border border-slate-100">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-forest-950 text-white flex items-center justify-center text-xs font-bold">H</div>
                                    <div>
                                        <p class="text-xs font-bold text-slate-900">Nguyễn Nhật Hoàng (Bạn)</p>
                                        <p class="text-[11px] text-slate-400">Người ký hợp đồng chính</p>
                                    </div>
                                </div>
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800">Đại diện</span>
                            </div>
                            <div class="flex items-center justify-between p-3 rounded-2xl bg-slate-50 border border-slate-100">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-slate-300 text-slate-700 flex items-center justify-center text-xs font-bold">B</div>
                                    <div>
                                        <p class="text-xs font-bold text-slate-900">Trần Gia Bảo</p>
                                        <p class="text-[11px] text-slate-400">Thành viên ở ghép</p>
                                    </div>
                                </div>
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-slate-200 text-slate-700">Thành viên</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-card flex flex-col justify-between">
                        <div>
                            <h3 class="text-base font-bold text-slate-900 mb-1">Gửi Yêu Cầu Báo Hỏng / Khiếu Nại</h3>
                            <p class="text-xs text-slate-400 mb-4">Ban quản lý sẽ phản hồi trong vòng 2 giờ</p>
                            <textarea rows="3" placeholder="Nhập sự cố phòng ở (ví dụ: máy lạnh chảy nước, vòi sen hỏng...)" class="w-full p-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-xs focus:outline-none focus:border-forest-600 focus:bg-white transition-all"></textarea>
                        </div>
                        <div class="mt-4 flex justify-end">
                            <button class="px-5 py-2.5 rounded-2xl bg-forest-950 hover:bg-forest-900 text-white font-semibold text-xs shadow-md shadow-forest-950/15">
                                Gửi ban quản lý
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>

</html>
