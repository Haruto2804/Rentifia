<?php

// 1. Nạp Database, Model và Controller (Lùi 2 cấp từ app/Tests/User về app/)
require_once __DIR__ . '/../../Core/Database.php';
require_once __DIR__ . '/../../Models/User.php';
require_once __DIR__ . '/../../Controllers/Admin/UserController.php';

use App\Controllers\Admin\UserController;

$userController = new UserController();
$message = null;
$messageType = null; // 'success' | 'error' | 'warning'

// Xử lý khi nhấn nút Xóa (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $deleteId = isset($_POST['id']) ? (int) $_POST['id'] : 0;

    if ($deleteId <= 0) {
        $message = "Vui lòng nhập ID hợp lệ lớn hơn 0.";
        $messageType = 'warning';
    } else {
        // Kiểm tra xem user có tồn tại trước khi xóa để lấy thông tin hiển thị
        $targetUser = $userController->findById($deleteId);

        if (!$targetUser) {
            $message = "Không tìm thấy người dùng có ID #{$deleteId} để xóa.";
            $messageType = 'warning';
        } else {
            // Thực hiện xóa
            $result = $userController->deleteById($deleteId);
            if ($result) {
                $message = "Đã xóa thành công người dùng #{$deleteId} ('" . htmlspecialchars($targetUser['username']) . "')!";
                $messageType = 'success';
            } else {
                $message = "Không thể xóa người dùng #{$deleteId}. (Có thể do ràng buộc dữ liệu khóa ngoại trong CSDL).";
                $messageType = 'error';
            }
        }
    }
}

// Lấy danh sách user hiện tại để hiển thị đối chiếu trực quan
$userList = $userController->getAll();

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
    <title>Rentifia - Test deleteById()</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-800 min-h-screen py-10 px-4">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8 text-center">
            <div
                class="inline-flex items-center gap-2 px-3 py-1 bg-rose-50 text-rose-700 rounded-full text-xs font-semibold uppercase tracking-wider mb-2 border border-rose-200">
                <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></span>
                Unit Test Page
            </div>
            <h1 class="text-2xl font-bold text-slate-900">Kiểm Thử Phương Thức deleteById()</h1>
            <p class="text-sm text-slate-500 mt-1">
                Gọi qua <code class="bg-slate-100 px-1.5 py-0.5 rounded text-rose-600 font-mono">App\Controllers\Admin\UserController::deleteById(int $id)</code>
            </p>
        </div>

        <!-- Form nhập ID để xóa -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-8">
            <h2 class="text-base font-semibold text-slate-800 mb-3 flex items-center gap-2">
                <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
                Nhập ID người dùng cần xóa
            </h2>
            <form method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa người dùng có ID này không? Hành động này không thể hoàn tác!');" class="flex flex-col sm:flex-row items-center gap-3">
                <input type="hidden" name="action" value="delete">
                <div class="relative flex-1 w-full">
                    <label for="id" class="sr-only">ID người dùng</label>
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <input type="number" name="id" id="id" placeholder="Nhập ID người dùng cần xóa (ví dụ: 5, 6...)" required min="1"
                        class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all font-mono">
                </div>
                <button type="submit"
                    class="w-full sm:w-auto px-6 py-2.5 bg-rose-600 hover:bg-rose-700 text-white font-medium text-sm rounded-xl shadow-sm shadow-rose-200 transition-all flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Thực Hiện Xóa
                </button>
            </form>
        </div>

        <!-- Thông báo kết quả -->
        <?php if ($message): ?>
            <div class="p-4 mb-8 rounded-2xl border <?php
                echo match($messageType) {
                    'success' => 'bg-emerald-50 border-emerald-200 text-emerald-800',
                    'error' => 'bg-rose-50 border-rose-200 text-rose-800',
                    default => 'bg-amber-50 border-amber-200 text-amber-800'
                };
            ?>">
                <div class="flex items-center gap-3">
                    <?php if ($messageType === 'success'): ?>
                        <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    <?php elseif ($messageType === 'error'): ?>
                        <svg class="w-5 h-5 text-rose-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    <?php else: ?>
                        <svg class="w-5 h-5 text-amber-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    <?php endif; ?>
                    <span class="text-sm font-medium"><?= htmlspecialchars($message) ?></span>
                </div>
            </div>
        <?php endif; ?>

        <!-- Danh sách người dùng hiện tại trong DB để đối chiếu -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h2 class="text-base font-bold text-slate-900">Danh sách người dùng hiện có trong Database</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Dữ liệu thời gian thực sau khi thực hiện thao tác xóa</p>
                </div>
                <span class="px-3 py-1 bg-slate-100 text-slate-600 rounded-full text-xs font-semibold">
                    Tổng: <?= count($userList) ?> người dùng
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-xs font-semibold uppercase tracking-wider text-slate-500">
                            <th class="py-3 px-6">ID</th>
                            <th class="py-3 px-6">Username</th>
                            <th class="py-3 px-6">Email</th>
                            <th class="py-3 px-6">Vai trò</th>
                            <th class="py-3 px-6">Trạng thái</th>
                            <th class="py-3 px-6 text-right">Thao tác nhanh</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php if (empty($userList)): ?>
                            <tr>
                                <td colspan="6" class="py-8 text-center text-slate-400">
                                    Không còn người dùng nào trong cơ sở dữ liệu.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($userList as $u): ?>
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="py-3.5 px-6 font-mono text-xs font-bold text-slate-500">
                                        #<?= htmlspecialchars($u['id']) ?>
                                    </td>
                                    <td class="py-3.5 px-6 font-medium text-slate-900">
                                        <?= htmlspecialchars($u['username']) ?>
                                    </td>
                                    <td class="py-3.5 px-6 text-slate-600 font-mono text-xs">
                                        <?= htmlspecialchars($u['email']) ?>
                                    </td>
                                    <td class="py-3.5 px-6">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border <?= getRoleBadgeClass($u['role'] ?? '') ?>">
                                            <?= htmlspecialchars(strtoupper($u['role'] ?? 'TENANT')) ?>
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-6">
                                        <?php if (($u['is_active'] ?? 1) == 1): ?>
                                            <span class="text-xs text-emerald-600 font-medium">Hoạt động</span>
                                        <?php else: ?>
                                            <span class="text-xs text-rose-500 font-medium">Đã khóa</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="py-3.5 px-6 text-right">
                                        <form method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa người dùng #<?= $u['id'] ?> (<?= htmlspecialchars($u['username']) ?>) không?');" class="inline">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id" value="<?= htmlspecialchars($u['id']) ?>">
                                            <button type="submit" class="px-2.5 py-1 text-xs font-medium text-rose-600 hover:text-rose-800 hover:bg-rose-50 rounded-lg transition-colors border border-rose-200">
                                                Xóa
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>
