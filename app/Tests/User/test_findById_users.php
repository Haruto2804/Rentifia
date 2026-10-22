<?php

// 1. Nạp Database, Model và Controller
require_once __DIR__ . '/../../Core/Database.php';
require_once __DIR__ . '/../../Models/User.php';
require_once __DIR__ . '/../../Controllers/Admin/UserController.php';

use App\Controllers\Admin\UserController;

$searchId = isset($_GET['id']) && trim($_GET['id']) !== '' ? (int) $_GET['id'] : null;
$user = null;
$errorMessage = null;
$hasSearched = $searchId !== null;

if ($hasSearched) {
    try {
        $userController = new UserController();
        $user = $userController->findById($searchId);
    } catch (Exception $e) {
        $errorMessage = $e->getMessage();
    }
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
    <title>Rentifia - Test findById()</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-800 min-h-screen py-10 px-4">
    <div class="max-w-3xl mx-auto">
        <!-- Header -->
        <div class="mb-8 text-center">
            <div
                class="inline-flex items-center gap-2 px-3 py-1 bg-indigo-50 text-indigo-700 rounded-full text-xs font-semibold uppercase tracking-wider mb-2 border border-indigo-100">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                Unit Test Page
            </div>
            <h1 class="text-2xl font-bold text-slate-900">Kiểm Thử Phương Thức findById()</h1>
            <p class="text-sm text-slate-500 mt-1">
                Gọi qua <code
                    class="bg-slate-100 px-1.5 py-0.5 rounded text-indigo-600 font-mono">App\Controllers\Admin\UserController::findById(int $id)</code>
            </p>
        </div>

        <!-- Form tìm kiếm ID -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-8">
            <form method="GET" class="flex flex-col sm:flex-row items-center gap-3">
                <div class="relative flex-1 w-full">
                    <label for="id" class="sr-only">ID người dùng</label>
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input type="number" name="id" id="id" value="<?= htmlspecialchars((string) ($searchId ?? '')) ?>"
                        placeholder="Nhập ID người dùng (ví dụ: 1, 2, 3...)" required min="1"
                        class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all font-mono">
                </div>
                <button type="submit"
                    class="w-full sm:w-auto px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm rounded-xl shadow-sm shadow-indigo-200 transition-all">
                    Tìm kiếm
                </button>
                <?php if ($hasSearched): ?>
                    <a href="test_findById_users.php"
                        class="w-full sm:w-auto px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 font-medium text-sm rounded-xl text-center transition-all">
                        Xóa
                    </a>
                <?php endif; ?>
            </form>
        </div>

        <!-- Hiển thị lỗi DB nếu có -->
        <?php if ($errorMessage): ?>
            <div class="p-4 mb-6 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700">
                <div class="flex items-center gap-2 font-semibold mb-1">
                    <svg class="w-5 h-5 text-rose-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                            clip-rule="evenodd" />
                    </svg>
                    Lỗi thực thi truy vấn
                </div>
                <p class="text-sm font-mono"><?= htmlspecialchars($errorMessage) ?></p>
            </div>
        <?php endif; ?>

        <!-- Khu vực kết quả -->
        <?php if ($hasSearched && !$errorMessage): ?>
            <?php if ($user): ?>
                <!-- Thẻ Profile chi tiết người dùng tìm thấy -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    <div
                        class="bg-gradient-to-r from-indigo-500 to-indigo-600 p-6 text-white flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-14 h-14 rounded-2xl bg-white/20 backdrop-blur-sm flex items-center justify-center font-bold text-2xl text-white border border-white/30 shadow-inner">
                                <?= strtoupper(substr($user->getUsername() ?? 'U', 0, 1)) ?>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold"><?= htmlspecialchars($user->getUsername() ?? '') ?></h2>
                                <p class="text-xs text-indigo-100 font-mono mt-0.5">
                                    <?= htmlspecialchars($user->getEmail() ?? '') ?>
                                </p>
                            </div>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-white text-indigo-700 shadow-sm">
                            ID #<?= htmlspecialchars((string) $user->getId()) ?>
                        </span>
                    </div>

                    <div class="p-6 divide-y divide-slate-100 text-sm">
                        <div class="py-3 flex items-center justify-between">
                            <span class="text-slate-500">Vai trò (Role):</span>
                            <span
                                class="inline-flex items-center px-3 py-0.5 rounded-full text-xs font-semibold border <?= getRoleBadgeClass($user->getRole()) ?>">
                                <?= htmlspecialchars(strtoupper($user->getRole())) ?>
                            </span>
                        </div>

                        <div class="py-3 flex items-center justify-between">
                            <span class="text-slate-500">Trạng thái:</span>
                            <?php if ($user->getIsActive()): ?>
                                <span
                                    class="inline-flex items-center gap-1.5 text-xs text-emerald-600 font-semibold bg-emerald-50 px-2.5 py-1 rounded-lg border border-emerald-200">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                    Đang hoạt động
                                </span>
                            <?php else: ?>
                                <span
                                    class="inline-flex items-center gap-1.5 text-xs text-rose-600 font-semibold bg-rose-50 px-2.5 py-1 rounded-lg border border-rose-200">
                                    <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                                    Đang bị khóa
                                </span>
                            <?php endif; ?>
                        </div>

                        <div class="py-3 flex items-center justify-between">
                            <span class="text-slate-500">Reset Token:</span>
                            <span class="font-mono text-xs text-slate-600">
                                <?= $user->getResetToken() ? htmlspecialchars($user->getResetToken()) : '<span class="text-slate-400 italic">null (Không có)</span>' ?>
                            </span>
                        </div>

                        <div class="py-3 flex items-center justify-between">
                            <span class="text-slate-500">Mã băm mật khẩu:</span>
                            <span class="font-mono text-xs text-slate-400 truncate max-w-xs"
                                title="<?= htmlspecialchars($user->getPasswordHash() ?? '') ?>">
                                <?= htmlspecialchars(substr($user->getPasswordHash() ?? '', 0, 20)) ?>...
                            </span>
                        </div>

                        <div class="py-3 flex items-center justify-between">
                            <span class="text-slate-500">Ngày tạo:</span>
                            <span class="font-mono text-xs text-slate-600">
                                <?= htmlspecialchars($user->getCreatedAt() ?? 'N/A') ?>
                            </span>
                        </div>

                        <div class="py-3 flex items-center justify-between">
                            <span class="text-slate-500">Cập nhật lần cuối:</span>
                            <span class="font-mono text-xs text-slate-600">
                                <?= htmlspecialchars($user->getUpdatedAt() ?? 'N/A') ?>
                            </span>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <!-- Thông báo không tìm thấy -->
                <div class="bg-amber-50 rounded-2xl border border-amber-200 p-8 text-center">
                    <div
                        class="w-12 h-12 bg-amber-100 text-amber-600 rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-amber-900">Không tìm thấy người dùng!</h3>
                    <p class="text-sm text-amber-700 mt-1">
                        Không có bản ghi nào với ID = <span
                            class="font-bold font-mono">#<?= htmlspecialchars((string) $searchId) ?></span> trong cơ sở dữ liệu.
                    </p>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <!-- Trạng thái chờ người dùng nhập -->
            <div class="border-2 border-dashed border-slate-200 rounded-2xl p-10 text-center text-slate-400">
                <svg class="w-10 h-10 mx-auto mb-2 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122">
                    </path>
                </svg>
                <p class="text-sm">Vui lòng nhập ID vào ô tìm kiếm ở trên và nhấn <strong class="text-slate-600">Tìm
                        kiếm</strong></p>
            </div>
        <?php endif; ?>
    </div>
</body>

</html>