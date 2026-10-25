<?php
require_once __DIR__ . '/../../../Middlewares/AuthMiddleware.php';
\App\Middlewares\AuthMiddleware::requireRole('admin');

$currentUsername = $_SESSION['username'] ?? 'Admin';
$currentRole     = $_SESSION['role'] ?? 'admin';
$currentEmail    = $_SESSION['email'] ?? 'admin@rentifia.vn';
?>
<!DOCTYPE html>
<html lang="vi" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rentifia - Bảng Điều Hành Quản Trị Chuỗi Nhà Trọ & Căn Hộ</title>
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
                            sidebar: '#0c0d13',
                            card: '#12131b',
                            cardHover: '#171822',
                            border: 'rgba(255, 255, 255, 0.07)',
                            muted: '#94a3b8',
                            pill: '#161722'
                        },
                        rentifia: {
                            50: '#ecfdf5',
                            100: '#d1fae5',
                            500: '#10b981',
                            600: '#059669',
                            700: '#047857',
                        },
                        accent: {
                            purple: '#8b5cf6',
                            purpleGlow: '#a855f7',
                            emerald: '#10b981',
                            amber: '#f59e0b',
                            rose: '#ef4444'
                        }
                    },
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'Inter', 'sans-serif'],
                        mono: ['JetBrains Mono', 'monospace']
                    }
                }
            }
        }
    </script>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        /* Ambient violet/emerald glow cho Dark Mode */
        .ambient-glow {
            position: fixed;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 860px;
            height: 400px;
            background: radial-gradient(circle, rgba(139, 92, 246, 0.12) 0%, rgba(16, 185, 129, 0.05) 45%, transparent 70%);
            pointer-events-none;
            z-index: 0;
        }

        .dark .ambient-glow {
            display: block;
        }

        html:not(.dark) .ambient-glow {
            display: none;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        .dark ::-webkit-scrollbar-track {
            background: #08090d;
        }

        .dark ::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.12);
            border-radius: 9999px;
        }

        html:not(.dark) ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        html:not(.dark) ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 9999px;
        }

        /* Tooltip Card */
        .tooltip-card {
            box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.5), 0 0 15px rgba(139, 92, 246, 0.25);
        }
    </style>
</head>

<body
    class="bg-slate-50 text-slate-900 dark:bg-[#08090d] dark:text-slate-100 min-h-screen flex flex-col antialiased selection:bg-purple-600 selection:text-white">

    <!-- Ánh sáng nền ambient glow (chỉ hiện trong Dark Mode) -->
    <div class="ambient-glow"></div>

    <div class="relative z-10 flex min-h-screen">

        <!-- ============================================================== -->
        <!-- SIDEBAR (MENU ĐIỀU HƯỚNG QUẢN TRỊ RENTIFIA)                     -->
        <!-- ============================================================== -->
        <aside
            class="w-64 flex-shrink-0 bg-white dark:bg-[#0c0d13] border-r border-slate-200 dark:border-white/[0.06] flex flex-col justify-between p-5 min-h-screen transition-colors duration-300">

            <!-- Phần trên: Logo & Menu chức năng chuẩn theo README -->
            <div>
                <!-- Brand / Logo Rentifia -->
                <div
                    class="flex items-center justify-between pb-6 mb-2 border-b border-slate-100 dark:border-white/[0.04]">
                    <a href="/Rentifia/app/Views/index.html" class="flex items-center gap-2.5 group">
                        <div class="relative w-8 h-8 flex items-center justify-center">
                            <span
                                class="w-2.5 h-2.5 rounded-sm bg-purple-500 absolute -top-0.5 -left-0.5 shadow-sm shadow-purple-500/50"></span>
                            <span
                                class="w-2.5 h-2.5 rounded-sm bg-emerald-400 absolute -top-0.5 -right-0.5 shadow-sm shadow-emerald-400/40"></span>
                            <span
                                class="w-2.5 h-2.5 rounded-sm bg-emerald-400 absolute -bottom-0.5 -left-0.5 shadow-sm shadow-emerald-400/40"></span>
                            <span
                                class="w-2.5 h-2.5 rounded-sm bg-purple-500 absolute -bottom-0.5 -right-0.5 shadow-sm shadow-purple-500/50"></span>
                        </div>
                        <div class="leading-tight">
                            <span class="text-lg font-extrabold tracking-tight text-slate-900 dark:text-white">
                                Rent<span class="text-emerald-500">ifia</span>
                            </span>
                            <span
                                class="block text-[10px] font-semibold text-purple-600 dark:text-purple-400 uppercase tracking-wider">Admin
                                Portal</span>
                        </div>
                    </a>

                    <!-- Nút thu gọn « -->
                    <button type="button" title="Thu gọn Sidebar"
                        class="text-slate-400 hover:text-slate-600 dark:hover:text-white text-xs p-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-white/[0.05] transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                        </svg>
                    </button>
                </div>

                <!-- QUẢN LÝ VẬN HÀNH (MAIN MENU) -->
                <div class="mt-5">
                    <p
                        class="text-[11px] font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500 px-3 mb-2">
                        Vận hành cốt lõi
                    </p>
                    <nav class="space-y-1">
                        <!-- Active Item: Dashboard -->
                        <a href="/Rentifia/app/Views/admin/dashboard/index.php"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-2xl bg-purple-500/10 dark:bg-white/[0.08] text-purple-700 dark:text-white border border-purple-500/20 dark:border-white/[0.08] font-semibold text-xs transition-all shadow-sm">
                            <svg class="w-4 h-4 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <rect x="3" y="3" width="7" height="7" rx="2" stroke-width="2" />
                                <rect x="14" y="3" width="7" height="7" rx="2" stroke-width="2" />
                                <rect x="14" y="14" width="7" height="7" rx="2" stroke-width="2" />
                                <rect x="3" y="14" width="7" height="7" rx="2" stroke-width="2" />
                            </svg>
                            <span>Dashboard Điều Hành</span>
                        </a>

                        <!-- Sơ đồ phòng & Tòa nhà -->
                        <a href="#"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-2xl text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-white/[0.04] text-xs font-medium transition-colors">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            <span>Tòa nhà & Sơ đồ phòng</span>
                        </a>

                        <!-- Hợp đồng thuê lưu trú -->
                        <a href="#"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-2xl text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-white/[0.04] text-xs font-medium transition-colors">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span>Hợp đồng lưu trú</span>
                            <span
                                class="ml-auto px-1.5 py-0.5 rounded-full text-[10px] font-bold bg-purple-100 text-purple-700 dark:bg-purple-500/20 dark:text-purple-300">185</span>
                        </a>

                        <!-- Chốt chỉ số Điện/Nước kèm ảnh Cloudinary -->
                        <a href="#"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-2xl text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-white/[0.04] text-xs font-medium transition-colors">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                            <span>Chốt Điện / Nước (Ảnh)</span>
                        </a>

                        <!-- Hóa đơn & Gạch nợ VietQR -->
                        <a href="#"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-2xl text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-white/[0.04] text-xs font-medium transition-colors">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <rect x="2" y="5" width="20" height="14" rx="2" stroke-width="2" />
                                <line x1="2" y1="10" x2="22" y2="10" stroke-width="2" />
                            </svg>
                            <span>Hóa đơn & VietQR</span>
                            <span
                                class="ml-auto px-1.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-300">12
                                Nợ</span>
                        </a>
                    </nav>
                </div>

                <!-- TÀI CHÍNH & TÀI KHOẢN (PAYMENTS & ACCOUNTS) -->
                <div class="mt-6">
                    <p
                        class="text-[11px] font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500 px-3 mb-2">
                        Tài chính & Phân quyền
                    </p>
                    <nav class="space-y-1">
                        <!-- Quản lý tòa nhà (Managers) -->
                        <a href="#"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-2xl text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-white/[0.04] text-xs font-medium transition-colors">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <span>Quản lý tòa nhà (Manager)</span>
                        </a>

                        <!-- Khách thuê & Cư dân (Tenants) -->
                        <a href="#"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-2xl text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-white/[0.04] text-xs font-medium transition-colors">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span>Khách thuê (Tenants)</span>
                        </a>

                        <!-- Đơn giá dịch vụ chung -->
                        <a href="#"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-2xl text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-white/[0.04] text-xs font-medium transition-colors">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="9" stroke-width="2" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 7v10M9 9h6a2 2 0 010 4H9a2 2 0 000 4h6" />
                            </svg>
                            <span>Đơn giá dịch vụ & Điện/Nước</span>
                        </a>
                    </nav>
                </div>
            </div>

            <!-- Phần dưới: Settings, PDF Export, User Profile -->
            <div class="pt-6 border-t border-slate-100 dark:border-white/[0.04] space-y-4">
                <nav class="space-y-1">
                    <a href="#"
                        class="flex items-center gap-3 px-3.5 py-2 rounded-2xl text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-white/[0.04] text-xs font-medium transition-colors">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <circle cx="12" cy="12" r="3" stroke-width="2" />
                        </svg>
                        <span>Cài đặt hệ thống & VietQR</span>
                    </a>

                    <a href="#"
                        class="flex items-center gap-3 px-3.5 py-2 rounded-2xl text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-white/[0.04] text-xs font-medium transition-colors">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span>Mẫu in PDF & Báo cáo</span>
                    </a>
                </nav>

                <!-- Profile chủ trọ / Admin -->
                <div class="flex items-center justify-between pt-2">
                    <div class="flex items-center gap-3">
                        <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=120&q=80"
                            alt="Admin Rentifia" class="w-9 h-9 rounded-full object-cover ring-2 ring-emerald-500/30">
                        <div class="leading-tight">
                            <p class="text-xs font-bold text-slate-900 dark:text-white">Gia Bảo (Admin)</p>
                            <p class="text-[11px] text-slate-400">admin@rentifia.vn</p>
                        </div>
                    </div>
                    <a href="/Rentifia/app/Views/auth/login.php" title="Đăng xuất"
                        class="text-slate-400 hover:text-rose-500 p-1 rounded transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                    </a>
                </div>
            </div>
        </aside>

        <!-- ============================================================== -->
        <!-- MAIN CONTENT (NỘI DUNG CHÍNH DASHBOARD RENTIFIA)              -->
        <!-- ============================================================== -->
        <main class="flex-1 flex flex-col overflow-x-hidden min-w-0 transition-colors duration-300">

            <!-- TOP NAVBAR -->
            <header
                class="h-20 px-8 flex items-center justify-between border-b border-slate-200 dark:border-white/[0.04] bg-white/80 dark:bg-[#090a10]/70 backdrop-blur-md sticky top-0 z-20 transition-colors duration-300">
                <!-- Thanh tìm kiếm nhanh phòng / khách / hợp đồng -->
                <div class="w-full max-w-md">
                    <div class="relative flex items-center">
                        <div
                            class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" placeholder="Tìm số phòng, tên khách thuê, mã hóa đơn..."
                            class="w-full pl-11 pr-24 py-2.5 bg-slate-100 dark:bg-[#12131b] border border-slate-200 dark:border-white/[0.08] rounded-full text-xs text-slate-800 dark:text-slate-200 placeholder-slate-400 focus:outline-none focus:border-emerald-500/50 focus:ring-1 focus:ring-emerald-500/30 transition-all">
                        <button type="button"
                            class="absolute right-1.5 px-4 py-1.5 bg-white dark:bg-[#1b1c27] hover:bg-slate-50 dark:hover:bg-[#232433] text-[11px] font-semibold text-slate-700 dark:text-slate-300 rounded-full border border-slate-200 dark:border-white/[0.06] shadow-sm transition-colors">
                            Tìm kiếm
                        </button>
                    </div>
                </div>

                <!-- Cụm tác vụ góc phải & Công tắc Sáng/Tối -->
                <div class="flex items-center gap-3">
                    <!-- Nút Thông báo sự cố / Hóa đơn quá hạn -->
                    <button type="button" title="Thông báo công nợ & bảo trì"
                        class="relative p-2.5 rounded-full bg-slate-100 dark:bg-[#12131b] border border-slate-200 dark:border-white/[0.06] text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <span
                            class="absolute top-2 right-2 w-2 h-2 rounded-full bg-rose-500 ring-2 ring-white dark:ring-[#08090d]"></span>
                    </button>

                    <!-- Nút Hỗ trợ / Phản hồi khách thuê -->
                    <button type="button" title="Khiếu nại khách thuê"
                        class="p-2.5 rounded-full bg-slate-100 dark:bg-[#12131b] border border-slate-200 dark:border-white/[0.06] text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                        </svg>
                    </button>

                    <!-- Nút Cài đặt VietQR -->
                    <button type="button" title="Cấu hình tài khoản ngân hàng VietQR"
                        class="p-2.5 rounded-full bg-slate-100 dark:bg-[#12131b] border border-slate-200 dark:border-white/[0.06] text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <circle cx="12" cy="12" r="3" stroke-width="2" />
                        </svg>
                    </button>

                    <!-- ========================================================== -->
                    <!-- CÔNG TẮC ĐỔI GIAO DIỆN SÁNG / TỐI (THEME SWITCHER)          -->
                    <!-- ========================================================== -->
                    <div
                        class="flex items-center bg-slate-100 dark:bg-[#12131b] border border-slate-200 dark:border-white/[0.06] rounded-full p-1 gap-1">
                        <!-- Nút Chế độ Sáng (Sun) -->
                        <button type="button" id="theme-sun-btn" onclick="setAppTheme('light')"
                            title="Chuyển sang Giao diện Sáng"
                            class="p-1.5 rounded-full text-slate-400 hover:text-amber-500 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="5" stroke-width="2" />
                                <path stroke-linecap="round" stroke-width="2"
                                    d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42" />
                            </svg>
                        </button>

                        <!-- Nút Chế độ Tối (Moon) -->
                        <button type="button" id="theme-moon-btn" onclick="setAppTheme('dark')"
                            title="Chuyển sang Giao diện Tối"
                            class="p-1.5 rounded-full text-slate-400 hover:text-purple-400 transition-all">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z" />
                            </svg>
                        </button>
                    </div>
                </div>
            </header>

            <!-- BODY CONTENT CỦA DASHBOARD -->
            <div class="p-8 space-y-7 max-w-[1600px] w-full mx-auto">

                <!-- TIÊU ĐỀ DASHBOARD & BỘ LỌC TÒA NHÀ / KỲ HÓA ĐƠN -->
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">
                            Bảng Điều Hành Rentifia
                        </h1>
                        <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1 font-medium">
                            Giám sát tỷ lệ lấp đầy, theo dõi dòng tiền hóa đơn và tự động hóa gạch nợ qua VietQR
                        </p>
                    </div>

                    <!-- Toolbar bên phải: Lọc Tòa nhà, Kỳ chốt & Trợ lý tự động hóa -->
                    <div class="flex flex-wrap items-center gap-3">
                        <!-- Chọn khoảng thời gian / Kỳ hóa đơn -->
                        <div
                            class="bg-white dark:bg-[#151621] border border-slate-200 dark:border-white/[0.07] rounded-2xl px-3.5 py-2 flex items-center gap-3 text-xs text-slate-700 dark:text-slate-300 shadow-sm">
                            <div class="flex items-center gap-2">
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <rect x="3" y="4" width="18" height="18" rx="2" stroke-width="2" />
                                    <line x1="16" y1="2" x2="16" y2="6" stroke-width="2" />
                                    <line x1="8" y1="2" x2="8" y2="6" stroke-width="2" />
                                </svg>
                                <span class="font-semibold text-slate-800 dark:text-slate-200">Kỳ: 01/10 -
                                    31/10/2026</span>
                            </div>
                            <span class="w-[1px] h-4 bg-slate-200 dark:bg-white/10"></span>
                            <div
                                class="flex items-center gap-1.5 cursor-pointer hover:text-purple-600 dark:hover:text-white">
                                <span class="font-medium">Tháng này</span>
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>

                        <!-- Lọc Tòa nhà / Chi nhánh -->
                        <div
                            class="bg-white dark:bg-[#151621] border border-slate-200 dark:border-white/[0.07] rounded-2xl px-4 py-2 flex items-center gap-2 text-xs text-slate-700 dark:text-slate-300 shadow-sm cursor-pointer hover:border-slate-300 dark:hover:border-white/20">
                            <span class="font-semibold">Toàn bộ chi nhánh</span>
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>

                        <!-- Nút Tự động tính & Phát hành Hóa đơn -->
                        <button type="button"
                            class="px-5 py-2.5 rounded-2xl bg-gradient-to-r from-purple-600 via-indigo-600 to-emerald-600 hover:from-purple-500 hover:to-emerald-500 text-white font-semibold text-xs tracking-wide shadow-lg shadow-purple-600/25 flex items-center gap-2 transition-all active:scale-95">
                            <svg class="w-4 h-4 text-purple-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                            <span>Tính & Phát hành Hóa đơn</span>
                        </button>
                    </div>
                </div>

                <!-- ============================================================== -->
                <!-- 4 THẺ THỐNG KÊ CHỈ SỐ VẬN HÀNH RENTIFIA (KPI STAT CARDS)      -->
                <!-- ============================================================== -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">

                    <!-- Card 1: Tỷ lệ lấp đầy phòng -->
                    <div
                        class="bg-white dark:bg-[#12131b] border border-slate-200/80 dark:border-white/[0.06] rounded-3xl p-6 shadow-sm dark:shadow-none transition-all duration-300 hover:translate-y-[-2px]">
                        <div class="flex items-center justify-between text-slate-500 dark:text-slate-400">
                            <span class="text-xs font-semibold tracking-wide uppercase">Tỷ lệ lấp đầy</span>
                            <span
                                class="p-1.5 rounded-xl bg-purple-50 dark:bg-purple-500/10 text-purple-600 dark:text-purple-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </span>
                        </div>
                        <div class="mt-4 mb-2">
                            <div class="flex items-baseline gap-2">
                                <h3 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">92.5%
                                </h3>
                                <span class="text-xs text-slate-400 font-medium">(185/200 phòng)</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-1.5 text-xs">
                            <span class="text-emerald-600 dark:text-emerald-400 font-bold font-mono">↑ +3.2%</span>
                            <span class="text-slate-500 dark:text-slate-400">so với tháng trước</span>
                        </div>
                    </div>

                    <!-- Card 2: Doanh thu thực thu kỳ này -->
                    <div
                        class="bg-white dark:bg-[#12131b] border border-slate-200/80 dark:border-white/[0.06] rounded-3xl p-6 shadow-sm dark:shadow-none transition-all duration-300 hover:translate-y-[-2px]">
                        <div class="flex items-center justify-between text-slate-500 dark:text-slate-400">
                            <span class="text-xs font-semibold tracking-wide uppercase">Doanh thu thực thu</span>
                            <span
                                class="p-1.5 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </span>
                        </div>
                        <div class="mt-4 mb-2">
                            <h3 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">485.25 tr
                            </h3>
                        </div>
                        <div class="flex items-center gap-1.5 text-xs">
                            <span class="text-emerald-600 dark:text-emerald-400 font-bold font-mono">↑ +12.4%</span>
                            <span class="text-slate-500 dark:text-slate-400">tăng trưởng theo chu kỳ</span>
                        </div>
                    </div>

                    <!-- Card 3: Công nợ tồn đọng -->
                    <div
                        class="bg-white dark:bg-[#12131b] border border-slate-200/80 dark:border-white/[0.06] rounded-3xl p-6 shadow-sm dark:shadow-none transition-all duration-300 hover:translate-y-[-2px]">
                        <div class="flex items-center justify-between text-slate-500 dark:text-slate-400">
                            <span class="text-xs font-semibold tracking-wide uppercase">Công nợ tồn đọng</span>
                            <span
                                class="p-1.5 rounded-xl bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </span>
                        </div>
                        <div class="mt-4 mb-2">
                            <div class="flex items-baseline gap-2">
                                <h3 class="text-3xl font-extrabold text-amber-600 dark:text-amber-400 tracking-tight">
                                    34.80 tr</h3>
                                <span class="text-xs text-slate-400 font-medium">(12 phòng chưa nộp)</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-1.5 text-xs">
                            <span class="text-emerald-600 dark:text-emerald-400 font-bold font-mono">↓ -18.5%</span>
                            <span class="text-slate-500 dark:text-slate-400">nhờ gạch nợ nhanh VietQR</span>
                        </div>
                    </div>

                    <!-- Card 4: Hợp đồng lưu trú hiệu lực -->
                    <div
                        class="bg-white dark:bg-[#12131b] border border-slate-200/80 dark:border-white/[0.06] rounded-3xl p-6 shadow-sm dark:shadow-none transition-all duration-300 hover:translate-y-[-2px]">
                        <div class="flex items-center justify-between text-slate-500 dark:text-slate-400">
                            <span class="text-xs font-semibold tracking-wide uppercase">Hợp đồng hiệu lực</span>
                            <span
                                class="p-1.5 rounded-xl bg-purple-50 dark:bg-purple-500/10 text-purple-600 dark:text-purple-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </span>
                        </div>
                        <div class="mt-4 mb-2">
                            <div class="flex items-baseline gap-2">
                                <h3 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">185 HĐ
                                </h3>
                                <span class="text-xs text-rose-500 font-medium">(8 HĐ sắp hết hạn)</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-1.5 text-xs">
                            <span class="text-emerald-600 dark:text-emerald-400 font-bold font-mono">↑ +6 HĐ</span>
                            <span class="text-slate-500 dark:text-slate-400">đã ký mới tuần này</span>
                        </div>
                    </div>

                </div>

                <!-- ============================================================== -->
                <!-- KHU VỰC BIỂU ĐỒ: BIẾN ĐỘNG DÒNG TIỀN & HIỆN TRẠNG PHÒNG       -->
                <!-- ============================================================== -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

                    <!-- CỘT TRÁI: BIẾN ĐỘNG DÒNG TIỀN THU PHÍ (7 NGÀY GẦN NHẤT) -->
                    <div
                        class="lg:col-span-7 bg-white dark:bg-[#12131b] border border-slate-200/80 dark:border-white/[0.06] rounded-3xl p-6 sm:p-7 relative flex flex-col justify-between shadow-sm dark:shadow-none">
                        <!-- Header biểu đồ -->
                        <div class="flex items-center justify-between mb-8">
                            <div>
                                <h2 class="text-base font-bold text-slate-900 dark:text-white tracking-tight">Dòng Tiền
                                    Thu Hóa Đơn & Dịch Vụ</h2>
                                <p class="text-[11px] text-slate-500 dark:text-slate-400 font-medium">Tiến độ thu tiền
                                    phòng, điện/nước trong chu kỳ tháng 10/2026</p>
                            </div>
                            <div
                                class="bg-slate-100 dark:bg-[#151621] border border-slate-200 dark:border-white/[0.07] px-3.5 py-1.5 rounded-xl flex items-center gap-2 text-xs text-slate-700 dark:text-slate-300 cursor-pointer shadow-sm">
                                <span>7 ngày qua</span>
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>

                        <!-- Vùng biểu đồ dạng Bar Chart -->
                        <div class="relative h-64 w-full flex flex-col justify-end">
                            <!-- Lưới trục Y và đường kẻ ngang -->
                            <div
                                class="absolute inset-0 flex flex-col justify-between pointer-events-none text-[11px] text-slate-400 font-mono">
                                <div class="flex items-center">
                                    <span class="w-10">100 tr</span>
                                    <div class="flex-1 border-b border-slate-100 dark:border-white/[0.04]"></div>
                                </div>
                                <div class="flex items-center">
                                    <span class="w-10">75 tr</span>
                                    <div class="flex-1 border-b border-slate-100 dark:border-white/[0.04]"></div>
                                </div>
                                <div class="flex items-center">
                                    <span class="w-10">50 tr</span>
                                    <div class="flex-1 border-b border-slate-100 dark:border-white/[0.04]"></div>
                                </div>
                                <div class="flex items-center">
                                    <span class="w-10">0 đ</span>
                                    <div class="flex-1 border-b border-slate-100 dark:border-white/[0.04]"></div>
                                </div>
                            </div>

                            <!-- Đường dóng và Tooltip tại ngày 5/10 (Cao điểm thanh toán VietQR) -->
                            <div
                                class="absolute left-[62%] bottom-8 top-3 flex flex-col items-center pointer-events-none z-10">
                                <!-- Hộp Tooltip Glassmorphism -->
                                <div
                                    class="tooltip-card bg-slate-900/90 dark:bg-[#161823]/95 text-white backdrop-blur-md border border-white/20 rounded-2xl p-3.5 text-left w-44 mb-2">
                                    <p
                                        class="text-[11px] font-bold text-slate-200 mb-2 border-b border-white/10 pb-1 flex items-center justify-between">
                                        <span>Ngày 05/10/2026</span>
                                        <span class="text-[10px] text-emerald-400 font-mono">82.5 tr</span>
                                    </p>
                                    <div class="space-y-1 text-[11px]">
                                        <div class="flex items-center justify-between text-slate-300">
                                            <span class="text-slate-400">Tiền phòng:</span>
                                            <span class="font-bold text-white">58.0 tr</span>
                                        </div>
                                        <div class="flex items-center justify-between text-slate-300">
                                            <span class="text-slate-400">Tiền Điện:</span>
                                            <span class="font-bold text-white">16.5 tr</span>
                                        </div>
                                        <div class="flex items-center justify-between text-slate-300">
                                            <span class="text-slate-400">Nước & Dịch vụ:</span>
                                            <span class="font-bold text-white">8.0 tr</span>
                                        </div>
                                    </div>
                                </div>
                                <!-- Đường dóng thẳng đứt đoạn -->
                                <div class="w-[1px] flex-1 border-l border-dashed border-purple-400"></div>
                            </div>

                            <!-- Các cột Bar Chart -->
                            <div class="relative pl-12 pr-2 h-48 flex items-end justify-between gap-3 sm:gap-6 z-0">

                                <!-- 01/10 -->
                                <div class="flex-1 flex flex-col items-center gap-3 h-full justify-end group">
                                    <div class="w-full max-w-[42px] bg-gradient-to-t from-purple-900/30 via-purple-700/60 to-purple-500 rounded-t-lg transition-all group-hover:brightness-125"
                                        style="height: 48%;"></div>
                                    <span
                                        class="text-[11px] text-slate-500 dark:text-slate-400 group-hover:text-slate-800 dark:group-hover:text-slate-200">01/10</span>
                                </div>

                                <!-- 02/10 -->
                                <div class="flex-1 flex flex-col items-center gap-3 h-full justify-end group">
                                    <div class="w-full max-w-[42px] bg-gradient-to-t from-purple-900/30 via-purple-700/60 to-purple-500 rounded-t-lg transition-all group-hover:brightness-125"
                                        style="height: 38%;"></div>
                                    <span
                                        class="text-[11px] text-slate-500 dark:text-slate-400 group-hover:text-slate-800 dark:group-hover:text-slate-200">02/10</span>
                                </div>

                                <!-- 03/10 -->
                                <div class="flex-1 flex flex-col items-center gap-3 h-full justify-end group">
                                    <div class="w-full max-w-[42px] bg-gradient-to-t from-purple-900/30 via-purple-700/60 to-purple-500 rounded-t-lg transition-all group-hover:brightness-125"
                                        style="height: 45%;"></div>
                                    <span
                                        class="text-[11px] text-slate-500 dark:text-slate-400 group-hover:text-slate-800 dark:group-hover:text-slate-200">03/10</span>
                                </div>

                                <!-- 04/10 -->
                                <div class="flex-1 flex flex-col items-center gap-3 h-full justify-end group">
                                    <div class="w-full max-w-[42px] bg-gradient-to-t from-purple-900/40 via-purple-700/70 to-purple-400 rounded-t-lg transition-all group-hover:brightness-125"
                                        style="height: 70%;"></div>
                                    <span
                                        class="text-[11px] text-slate-500 dark:text-slate-400 group-hover:text-slate-800 dark:group-hover:text-slate-200">04/10</span>
                                </div>

                                <!-- 05/10 (Active Bar - Ngày cao điểm thanh toán) -->
                                <div class="flex-1 flex flex-col items-center gap-3 h-full justify-end group">
                                    <div class="w-full max-w-[42px] bg-gradient-to-t from-purple-900/60 via-purple-600 to-emerald-400 rounded-t-lg shadow-lg shadow-purple-600/30 transition-all group-hover:brightness-125"
                                        style="height: 84%;"></div>
                                    <span
                                        class="text-[11px] text-purple-600 dark:text-purple-300 font-bold">05/10</span>
                                </div>

                                <!-- 06/10 -->
                                <div class="flex-1 flex flex-col items-center gap-3 h-full justify-end group">
                                    <div class="w-full max-w-[42px] bg-gradient-to-t from-purple-900/30 via-purple-700/60 to-purple-500 rounded-t-lg transition-all group-hover:brightness-125"
                                        style="height: 52%;"></div>
                                    <span
                                        class="text-[11px] text-slate-500 dark:text-slate-400 group-hover:text-slate-800 dark:group-hover:text-slate-200">06/10</span>
                                </div>

                                <!-- 07/10 -->
                                <div class="flex-1 flex flex-col items-center gap-3 h-full justify-end group">
                                    <div class="w-full max-w-[42px] bg-gradient-to-t from-purple-900/30 via-purple-700/60 to-purple-500 rounded-t-lg transition-all group-hover:brightness-125"
                                        style="height: 35%;"></div>
                                    <span
                                        class="text-[11px] text-slate-500 dark:text-slate-400 group-hover:text-slate-800 dark:group-hover:text-slate-200">07/10</span>
                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- CỘT PHẢI: HIỆN TRẠNG 200 PHÒNG (DONUT CHART THEO README) -->
                    <div
                        class="lg:col-span-5 bg-white dark:bg-[#12131b] border border-slate-200/80 dark:border-white/[0.06] rounded-3xl p-6 sm:p-7 flex flex-col justify-between shadow-sm dark:shadow-none">
                        <!-- Header -->
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h2 class="text-base font-bold text-slate-900 dark:text-white tracking-tight">Hiện Trạng
                                    200 Phòng</h2>
                                <p class="text-[11px] text-slate-500 dark:text-slate-400 font-medium">Theo dõi tình
                                    trạng phòng theo thời gian thực</p>
                            </div>
                            <div
                                class="bg-slate-100 dark:bg-[#151621] border border-slate-200 dark:border-white/[0.07] px-3 py-1.5 rounded-xl flex items-center gap-2 text-xs text-slate-700 dark:text-slate-300">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                <span>Trực tiếp</span>
                            </div>
                        </div>

                        <!-- Donut Chart & 4 Trạng thái phòng theo nghiệp vụ Rentifia -->
                        <div class="flex items-center justify-between gap-6 my-auto py-2">
                            <!-- SVG Donut Chart -->
                            <div class="relative w-40 h-40 flex-shrink-0 flex items-center justify-center">
                                <svg class="w-full h-full transform -rotate-90" viewBox="0 0 100 100">
                                    <!-- Vòng xám nền -->
                                    <circle cx="50" cy="50" r="38" stroke="currentColor"
                                        class="text-slate-100 dark:text-white/[0.05]" stroke-width="12"
                                        fill="transparent" />

                                    <!-- Đang thuê (Màu tím/indigo) ~ 92.5% -->
                                    <circle cx="50" cy="50" r="38" stroke="#8b5cf6" stroke-width="12"
                                        stroke-linecap="round" stroke-dasharray="238.76" stroke-dashoffset="35"
                                        fill="transparent" />

                                    <!-- Phòng trống (Màu xanh lá neon) ~ 4% -->
                                    <circle cx="50" cy="50" r="38" stroke="#10b981" stroke-width="12"
                                        stroke-linecap="round" stroke-dasharray="238.76" stroke-dashoffset="215"
                                        fill="transparent" />

                                    <!-- Đã đặt cọc (Màu vàng) ~ 2.5% -->
                                    <circle cx="50" cy="50" r="38" stroke="#f59e0b" stroke-width="12"
                                        stroke-linecap="round" stroke-dasharray="238.76" stroke-dashoffset="230"
                                        fill="transparent" />
                                </svg>

                                <!-- Text trung tâm Donut -->
                                <div class="absolute inset-0 flex flex-col items-center justify-center text-center">
                                    <span
                                        class="text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight">200</span>
                                    <span class="text-[10px] text-slate-400 font-semibold uppercase">Tổng số
                                        phòng</span>
                                </div>
                            </div>

                            <!-- Bảng thông số 4 trạng thái phòng -->
                            <div class="flex-1 grid grid-cols-2 gap-y-5 gap-x-3">
                                <!-- Đang thuê -->
                                <div>
                                    <div class="flex items-center gap-2 mb-1">
                                        <span
                                            class="w-2.5 h-2.5 rounded-sm bg-purple-500 shadow-sm shadow-purple-500/50"></span>
                                        <span class="text-xs text-slate-500 dark:text-slate-400">Đang thuê</span>
                                    </div>
                                    <p class="text-lg font-extrabold text-slate-900 dark:text-white pl-4">185</p>
                                </div>

                                <!-- Phòng trống -->
                                <div>
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="w-2.5 h-2.5 rounded-sm bg-emerald-500"></span>
                                        <span class="text-xs text-slate-500 dark:text-slate-400">Phòng trống</span>
                                    </div>
                                    <p class="text-lg font-extrabold text-slate-900 dark:text-white pl-4">8</p>
                                </div>

                                <!-- Đã đặt cọc -->
                                <div>
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="w-2.5 h-2.5 rounded-sm bg-amber-400"></span>
                                        <span class="text-xs text-slate-500 dark:text-slate-400">Đã cọc</span>
                                    </div>
                                    <p class="text-lg font-extrabold text-slate-900 dark:text-white pl-4">5</p>
                                </div>

                                <!-- Đang bảo trì -->
                                <div>
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="w-2.5 h-2.5 rounded-sm bg-rose-400"></span>
                                        <span class="text-xs text-slate-500 dark:text-slate-400">Bảo trì</span>
                                    </div>
                                    <p class="text-lg font-extrabold text-slate-900 dark:text-white pl-4">2</p>
                                </div>
                            </div>
                        </div>

                        <!-- Nút Xem sơ đồ phòng chi tiết -->
                        <div class="mt-6">
                            <a href="#"
                                class="w-full py-3 bg-slate-100 dark:bg-[#151722] hover:bg-slate-200 dark:hover:bg-[#1c1e2b] text-slate-700 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white rounded-2xl border border-slate-200 dark:border-white/[0.06] text-xs font-semibold tracking-wide transition-colors flex items-center justify-center gap-2">
                                <span>Xem Sơ Đồ Phòng Trực Quan (Theo Tầng)</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </a>
                        </div>
                    </div>

                </div>

                <!-- ============================================================== -->
                <!-- BẢNG PHÒNG CẦN XỬ LÝ & HÓA ĐƠN CHU KỲ GẦN NHẤT                 -->
                <!-- ============================================================== -->
                <div
                    class="bg-white dark:bg-[#12131b] border border-slate-200/80 dark:border-white/[0.06] rounded-3xl p-6 sm:p-7 shadow-sm dark:shadow-none">
                    <!-- Tiêu đề & Filter -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
                        <div>
                            <h2 class="text-lg font-bold text-slate-900 dark:text-white tracking-tight">Phòng Cần Xử Lý
                                & Tiến Độ Thu Phí</h2>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Theo dõi hợp đồng sắp hết hạn,
                                hóa đơn chờ gạch nợ VietQR và chốt điện nước</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <div
                                class="bg-slate-100 dark:bg-[#151621] border border-slate-200 dark:border-white/[0.07] px-3.5 py-1.5 rounded-xl flex items-center gap-2 text-xs text-slate-700 dark:text-slate-300 cursor-pointer shadow-sm">
                                <span>Tất cả trạng thái</span>
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Bảng dữ liệu phòng theo chuẩn Rentifia -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <!-- Tiêu đề cột -->
                            <thead>
                                <tr
                                    class="text-slate-500 dark:text-slate-400 border-b border-slate-100 dark:border-white/[0.04] font-semibold text-[11px] uppercase tracking-wider">
                                    <th class="pb-3.5 font-semibold">Mã Phòng</th>
                                    <th class="pb-3.5 font-semibold">Khách Thuê Đại Diện</th>
                                    <th class="pb-3.5 font-semibold">Tòa Nhà / Chi Nhánh</th>
                                    <th class="pb-3.5 font-semibold">Loại Phòng</th>
                                    <th class="pb-3.5 font-semibold">Tiền Hóa Đơn Kỳ Này</th>
                                    <th class="pb-3.5 font-semibold">Phương Thức</th>
                                    <th class="pb-3.5 font-semibold">Trạng Thái</th>
                                    <th class="pb-3.5 font-semibold">Tiến Độ Thu</th>
                                    <th class="pb-3.5 font-semibold text-right">Tác Vụ</th>
                                </tr>
                            </thead>

                            <!-- Dữ liệu từng dòng -->
                            <tbody class="divide-y divide-slate-100 dark:divide-white/[0.03]">

                                <!-- Hàng 1: Phòng P.302 (Đã thanh toán VietQR) -->
                                <tr class="hover:bg-slate-50/70 dark:hover:bg-white/[0.02] transition-colors group">
                                    <td class="py-4 font-mono font-bold text-purple-600 dark:text-purple-400">#P.302
                                    </td>
                                    <td class="py-4">
                                        <div class="flex items-center gap-3">
                                            <img src="https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&w=80&q=80"
                                                alt="Nguyễn Văn An"
                                                class="w-8 h-8 rounded-full object-cover ring-1 ring-slate-200 dark:ring-white/10">
                                            <div>
                                                <p class="font-semibold text-slate-900 dark:text-slate-200">Nguyễn Văn
                                                    An</p>
                                                <p class="text-[11px] text-slate-400 font-mono">0912.345.678</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4 text-slate-600 dark:text-slate-400">Rentifia Q7 - Tầng 3</td>
                                    <td class="py-4 text-slate-600 dark:text-slate-400">Studio Full Nội Thất</td>
                                    <td class="py-4 font-semibold text-slate-900 dark:text-white font-mono">5.850.000 đ
                                    </td>
                                    <td class="py-4">
                                        <span
                                            class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-500/10 dark:text-emerald-400">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            VietQR Động
                                        </span>
                                    </td>
                                    <td class="py-4">
                                        <span
                                            class="inline-flex items-center gap-1.5 text-xs text-emerald-600 dark:text-emerald-400 font-semibold">
                                            <span
                                                class="w-2 h-2 rounded-full bg-emerald-500 shadow-sm shadow-emerald-500/50"></span>
                                            Đã Gạch Nợ
                                        </span>
                                    </td>
                                    <td class="py-4">
                                        <div class="flex items-center gap-3 w-36">
                                            <div
                                                class="flex-1 bg-slate-100 dark:bg-white/[0.08] h-1.5 rounded-full overflow-hidden">
                                                <div class="bg-gradient-to-r from-emerald-500 to-teal-400 h-full rounded-full"
                                                    style="width: 100%;"></div>
                                            </div>
                                            <span
                                                class="text-emerald-600 dark:text-emerald-400 font-bold font-mono text-[11px]">100%</span>
                                        </div>
                                    </td>
                                    <td class="py-4 text-right">
                                        <button type="button" title="Xuất phiếu thu PDF"
                                            class="text-slate-400 hover:text-purple-600 dark:hover:text-white p-1 rounded transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>

                                <!-- Hàng 2: Phòng P.405 (Chờ thanh toán VietQR) -->
                                <tr class="hover:bg-slate-50/70 dark:hover:bg-white/[0.02] transition-colors group">
                                    <td class="py-4 font-mono font-bold text-purple-600 dark:text-purple-400">#P.405
                                    </td>
                                    <td class="py-4">
                                        <div class="flex items-center gap-3">
                                            <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=80&q=80"
                                                alt="Trần Thị Bích Ngọc"
                                                class="w-8 h-8 rounded-full object-cover ring-1 ring-slate-200 dark:ring-white/10">
                                            <div>
                                                <p class="font-semibold text-slate-900 dark:text-slate-200">Trần Thị
                                                    Bích Ngọc</p>
                                                <p class="text-[11px] text-slate-400 font-mono">0988.765.432</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4 text-slate-600 dark:text-slate-400">Rentifia Thủ Đức - Tầng 4</td>
                                    <td class="py-4 text-slate-600 dark:text-slate-400">1PN Ban Công Lớn</td>
                                    <td class="py-4 font-semibold text-slate-900 dark:text-white font-mono">6.420.000 đ
                                    </td>
                                    <td class="py-4">
                                        <span
                                            class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 dark:bg-amber-500/10 dark:text-amber-400">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                            Chờ Chuyển Khoản
                                        </span>
                                    </td>
                                    <td class="py-4">
                                        <span
                                            class="inline-flex items-center gap-1.5 text-xs text-amber-600 dark:text-amber-400 font-semibold">
                                            <span class="w-2 h-2 rounded-full bg-amber-500 animate-ping"></span>
                                            Chờ Thanh Toán
                                        </span>
                                    </td>
                                    <td class="py-4">
                                        <div class="flex items-center gap-3 w-36">
                                            <div
                                                class="flex-1 bg-slate-100 dark:bg-white/[0.08] h-1.5 rounded-full overflow-hidden">
                                                <div class="bg-gradient-to-r from-amber-500 to-rose-500 h-full rounded-full"
                                                    style="width: 0%;"></div>
                                            </div>
                                            <span
                                                class="text-amber-600 dark:text-amber-400 font-bold font-mono text-[11px]">0%</span>
                                        </div>
                                    </td>
                                    <td class="py-4 text-right">
                                        <button type="button" title="Gửi link VietQR qua Zalo"
                                            class="text-slate-400 hover:text-emerald-500 p-1 rounded transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>

                                <!-- Hàng 3: Phòng P.201 (Hợp đồng sắp hết hạn - cần gia hạn) -->
                                <tr class="hover:bg-slate-50/70 dark:hover:bg-white/[0.02] transition-colors group">
                                    <td class="py-4 font-mono font-bold text-purple-600 dark:text-purple-400">#P.201
                                    </td>
                                    <td class="py-4">
                                        <div class="flex items-center gap-3">
                                            <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=80&q=80"
                                                alt="Lê Hoàng Minh"
                                                class="w-8 h-8 rounded-full object-cover ring-1 ring-slate-200 dark:ring-white/10">
                                            <div>
                                                <p class="font-semibold text-slate-900 dark:text-slate-200">Lê Hoàng
                                                    Minh</p>
                                                <p class="text-[11px] text-slate-400 font-mono">0903.112.233</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4 text-slate-600 dark:text-slate-400">Rentifia Bình Thạnh - Tầng 2
                                    </td>
                                    <td class="py-4 text-slate-600 dark:text-slate-400">Duplex Gác Lửng</td>
                                    <td class="py-4 font-semibold text-slate-900 dark:text-white font-mono">4.900.000 đ
                                    </td>
                                    <td class="py-4">
                                        <span
                                            class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-purple-100 text-purple-800 dark:bg-purple-500/10 dark:text-purple-400">
                                            <span class="w-1.5 h-1.5 rounded-full bg-purple-500"></span>
                                            HĐ Còn 15 Ngày
                                        </span>
                                    </td>
                                    <td class="py-4">
                                        <span
                                            class="inline-flex items-center gap-1.5 text-xs text-purple-600 dark:text-purple-400 font-semibold">
                                            <span
                                                class="w-2 h-2 rounded-full bg-purple-500 shadow-sm shadow-purple-500/50"></span>
                                            Sắp Hết Hạn HĐ
                                        </span>
                                    </td>
                                    <td class="py-4">
                                        <div class="flex items-center gap-3 w-36">
                                            <div
                                                class="flex-1 bg-slate-100 dark:bg-white/[0.08] h-1.5 rounded-full overflow-hidden">
                                                <div class="bg-gradient-to-r from-purple-500 to-indigo-500 h-full rounded-full"
                                                    style="width: 85%;"></div>
                                            </div>
                                            <span
                                                class="text-purple-600 dark:text-purple-400 font-bold font-mono text-[11px]">85%</span>
                                        </div>
                                    </td>
                                    <td class="py-4 text-right">
                                        <button type="button" title="Gia hạn hoặc thanh lý hợp đồng"
                                            class="text-slate-400 hover:text-purple-600 dark:hover:text-white p-1 rounded transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>

                                <!-- Hàng 4: Phòng P.506 (Chưa chốt chỉ số Điện/Nước) -->
                                <tr class="hover:bg-slate-50/70 dark:hover:bg-white/[0.02] transition-colors group">
                                    <td class="py-4 font-mono font-bold text-purple-600 dark:text-purple-400">#P.506
                                    </td>
                                    <td class="py-4">
                                        <div class="flex items-center gap-3">
                                            <img src="https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=80&q=80"
                                                alt="Phạm Quốc Dũng"
                                                class="w-8 h-8 rounded-full object-cover ring-1 ring-slate-200 dark:ring-white/10">
                                            <div>
                                                <p class="font-semibold text-slate-900 dark:text-slate-200">Phạm Quốc
                                                    Dũng</p>
                                                <p class="text-[11px] text-slate-400 font-mono">0977.889.900</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4 text-slate-600 dark:text-slate-400">Rentifia Q7 - Tầng 5</td>
                                    <td class="py-4 text-slate-600 dark:text-slate-400">Studio Ban Công</td>
                                    <td class="py-4 font-semibold text-slate-900 dark:text-white font-mono">Chờ chốt số
                                    </td>
                                    <td class="py-4">
                                        <span
                                            class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800 dark:bg-rose-500/10 dark:text-rose-400">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                            Thiếu Ảnh Công Tơ
                                        </span>
                                    </td>
                                    <td class="py-4">
                                        <span
                                            class="inline-flex items-center gap-1.5 text-xs text-rose-600 dark:text-rose-400 font-semibold">
                                            <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                                            Chưa Chốt Điện/Nước
                                        </span>
                                    </td>
                                    <td class="py-4">
                                        <div class="flex items-center gap-3 w-36">
                                            <div
                                                class="flex-1 bg-slate-100 dark:bg-white/[0.08] h-1.5 rounded-full overflow-hidden">
                                                <div class="bg-gradient-to-r from-rose-500 to-amber-500 h-full rounded-full"
                                                    style="width: 45%;"></div>
                                            </div>
                                            <span
                                                class="text-rose-600 dark:text-rose-400 font-bold font-mono text-[11px]">45%</span>
                                        </div>
                                    </td>
                                    <td class="py-4 text-right">
                                        <button type="button" title="Chụp / Tải ảnh công tơ lên Cloudinary"
                                            class="text-slate-400 hover:text-emerald-500 p-1 rounded transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </main>

    </div>

    <!-- ============================================================== -->
    <!-- LOGIC CHUYỂN ĐỔI CHẾ ĐỘ SÁNG / TỐI (THEME SWITCHER SCRIPT)    -->
    <!-- ============================================================== -->
    <script>
        // Hàm thiết lập giao diện Sáng / Tối và lưu vào LocalStorage
        function setAppTheme(theme) {
            const html = document.documentElement;
            const sunBtn = document.getElementById('theme-sun-btn');
            const moonBtn = document.getElementById('theme-moon-btn');

            if (theme === 'dark') {
                html.classList.add('dark');
                localStorage.setItem('rentifia_theme', 'dark');

                // Cập nhật trạng thái nút
                if (sunBtn && moonBtn) {
                    sunBtn.className = 'p-1.5 rounded-full text-slate-400 hover:text-amber-500 transition-all';
                    moonBtn.className = 'p-1.5 rounded-full bg-white/[0.12] text-purple-400 shadow-sm transition-all';
                }
            } else {
                html.classList.remove('dark');
                localStorage.setItem('rentifia_theme', 'light');

                // Cập nhật trạng thái nút
                if (sunBtn && moonBtn) {
                    sunBtn.className = 'p-1.5 rounded-full bg-white text-amber-500 shadow-sm transition-all';
                    moonBtn.className = 'p-1.5 rounded-full text-slate-400 hover:text-purple-400 transition-all';
                }
            }
        }

        // Tự động khởi tạo theme khi trang được tải
        (function initTheme() {
            const savedTheme = localStorage.getItem('rentifia_theme');
            if (savedTheme) {
                setAppTheme(savedTheme);
            } else {
                // Mặc định dark mode theo thiết kế chuẩn của hệ thống
                const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                setAppTheme(prefersDark ? 'dark' : 'dark');
            }
        })();
    </script>
</body>

</html>