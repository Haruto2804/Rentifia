<?php

// 1. Nạp Database, Model và Controller
require_once __DIR__ . '/../../Core/Database.php';
require_once __DIR__ . '/../../Models/User.php';
require_once __DIR__ . '/../../Controllers/Admin/UserController.php';

use App\Controllers\Admin\UserController;

$errorMessage = null;
$users = [];

try {
    $userController = new UserController();
    $users = $userController->getAll();
} catch (Exception $e) {
    $errorMessage = $e->getMessage();
}

// Hàm hỗ trợ đổi màu Badge theo Role
function getRoleBadgeClass($role)
{
    return match (strtolower($role ?? '')) {
        'admin' => 'bg-rose-100 text-rose-700 border-rose-200',
        'manager' => 'bg-amber-100 text-amber-700 border-amber-200',
        'tenant' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
        default => 'bg-slate-100 text-slate-700 border-slate-200'
    };
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rentifia - Test Danh Sách Người Dùng</title>
    <!-- Tailwind CSS CDN để giao diện hiện đại, chuẩn UI/UX -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-800 min-h-screen py-10 px-4">
    <div class="max-w-5xl mx-auto">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between pb-6 mb-8 border-b border-slate-200">
            <div>
                <div
                    class="inline-flex items-center gap-2 px-3 py-1 bg-indigo-50 text-indigo-700 rounded-full text-xs font-semibold uppercase tracking-wider mb-2 border border-indigo-100">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    Database Connected
                </div>
                <h1 class="text-2xl font-bold text-slate-900">Rentifia User Management Test</h1>
                <p class="text-sm text-slate-500 mt-1">Kiểm tra hiển thị dữ liệu trực tiếp từ Model <code
                        class="bg-slate-100 px-1.5 py-0.5 rounded text-indigo-600 font-mono">App\Model\User::getAllUsers()</code>
                </p>
            </div>
            <div class="mt-4 md:mt-0 flex items-center gap-3">
                <span class="text-xs text-slate-400 font-mono">PHP 8.2 + MySQL</span>
                <span
                    class="px-3.5 py-1.5 bg-white border border-slate-200 rounded-lg text-sm font-semibold text-slate-700 shadow-sm">
                    Tổng: <?= count($users) ?> tài khoản
                </span>
            </div>
        </div>

        <!-- Báo lỗi nếu có -->
        <?php if ($errorMessage): ?>
            <div class="p-4 mb-6 rounded-xl bg-rose-50 border border-rose-200 text-rose-700">
                <div class="flex items-center gap-2 font-semibold mb-1">
                    <svg class="w-5 h-5 text-rose-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                            clip-rule="evenodd" />
                    </svg>
                    Lỗi truy vấn cơ sở dữ liệu
                </div>
                <p class="text-sm font-mono"><?= htmlspecialchars($errorMessage) ?></p>
            </div>
        <?php endif; ?>

        <!-- Bảng danh sách người dùng -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr
                            class="bg-slate-50 border-b border-slate-200 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                            <th class="py-4 px-6">ID</th>
                            <th class="py-4 px-6">Tài khoản (Username)</th>
                            <th class="py-4 px-6">Email</th>
                            <th class="py-4 px-6">Vai trò (Role)</th>
                            <th class="py-4 px-6">Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        <?php if (empty($users)): ?>
                            <tr>
                                <td colspan="5" class="py-12 text-center text-slate-400">
                                    Chưa có dữ liệu nào trong bảng <code class="font-mono text-slate-600">users</code>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($users as $user): ?>
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="py-4 px-6 font-mono text-xs text-slate-400 font-semibold">
                                        #<?= htmlspecialchars($user['id'] ?? '') ?>
                                    </td>
                                    <td class="py-4 px-6">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center font-bold text-slate-600 text-xs uppercase border border-slate-200">
                                                <?= strtoupper(substr($user['username'] ?? 'U', 0, 1)) ?>
                                            </div>
                                            <span class="font-medium text-slate-900">
                                                <?= htmlspecialchars($user['username'] ?? '') ?>
                                            </span>
                                        </div>
                                    </td>
                                    <td class="py-4 px-6 text-slate-600 font-mono text-xs">
                                        <?= htmlspecialchars($user['email'] ?? '') ?>
                                    </td>
                                    <td class="py-4 px-6">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border <?= getRoleBadgeClass($user['role'] ?? '') ?>">
                                            <?= htmlspecialchars(strtoupper($user['role'] ?? 'TENANT')) ?>
                                        </span>
                                    </td>
                                    <td class="py-4 px-6">
                                        <?php if (($user['is_active'] ?? 1) == 1): ?>
                                            <span class="inline-flex items-center gap-1.5 text-xs text-emerald-600 font-medium">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                Hoạt động
                                            </span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center gap-1.5 text-xs text-rose-500 font-medium">
                                                <span class="w-1.5 h-1.5 rounded-full bg-rose-400"></span>
                                                Đã khóa
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Footer ghi chú -->
            <div
                class="py-4 px-6 bg-slate-50/50 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-500 gap-2">
                <span>Dữ liệu được bảo vệ bằng <code class="text-slate-700">htmlspecialchars()</code> chống tấn công
                    XSS</span>
                <span class="font-mono text-slate-400">Rentifia Monolith Architecture</span>
            </div>
        </div>
    </div>
</body>

</html>