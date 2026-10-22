<?php

// 1. Nạp Database, Model và Controller (Lùi 2 cấp từ app/Tests/User về app/)
require_once __DIR__ . '/../../Core/Database.php';
require_once __DIR__ . '/../../Models/User.php';
require_once __DIR__ . '/../../Controllers/Admin\UserController.php';

use App\Controllers\Admin\UserController;

$userController = new UserController();
$message = null;
$messageType = null; // 'success' | 'error' | 'warning'
$verifyResult = null;

// Lấy ID người dùng từ GET (khi bấm chọn trên bảng) hoặc từ POST
$selectedId = isset($_POST['id']) ? (int) $_POST['id'] : (isset($_GET['id']) ? (int) $_GET['id'] : null);
$currentUser = null;

// Xử lý khi Submit Form đổi mật khẩu (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'change_password') {
    $id              = (int) ($_POST['id'] ?? 0);
    $newPassword     = trim($_POST['new_password'] ?? '');
    $confirmPassword = trim($_POST['confirm_password'] ?? '');

    if ($id <= 0) {
        $message = "Vui lòng chọn một người dùng hợp lệ.";
        $messageType = 'warning';
    } elseif ($newPassword === '') {
        $message = "Mật khẩu mới không được để trống.";
        $messageType = 'warning';
    } elseif ($newPassword !== $confirmPassword) {
        $message = "Mật khẩu xác nhận không khớp với mật khẩu mới.";
        $messageType = 'warning';
    } else {
        // Gọi Controller thực hiện đổi mật khẩu
        $result = $userController->changePassword($id, $newPassword);

        if ($result) {
            $message = "Đổi mật khẩu cho người dùng #<strong>{$id}</strong> thành công!";
            $messageType = 'success';

            // Kiểm tra lại bằng password_verify để xác thực hash trong DB
            $updatedUser = $userController->findById($id);
            if ($updatedUser && password_verify($newPassword, $updatedUser['password_hash'])) {
                $verifyResult = [
                    'verified' => true,
                    'hash'     => $updatedUser['password_hash']
                ];
            }
        } else {
            $message = "Đổi mật khẩu thất bại! (Không tìm thấy người dùng hoặc lỗi CSDL).";
            $messageType = 'error';
        }
    }
}

// Tải thông tin người dùng được chọn
if ($selectedId) {
    $currentUser = $userController->findById($selectedId);
}

// Lấy danh sách người dùng để hiển thị đối chiếu
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
    <title>Rentifia - Test changePassword()</title>
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
                class="inline-flex items-center gap-2 px-3 py-1 bg-amber-50 text-amber-700 rounded-full text-xs font-semibold uppercase tracking-wider mb-2 border border-amber-200">
                <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                Unit Test Page
            </div>
            <h1 class="text-2xl font-bold text-slate-900">Kiểm Thử Phương Thức changePassword()</h1>
            <p class="text-sm text-slate-500 mt-1">
                Gọi qua <code class="bg-slate-100 px-1.5 py-0.5 rounded text-amber-600 font-mono">App\Controllers\Admin\UserController::changePassword(int $id, string $newPassword)</code>
            </p>
        </div>

        <!-- Thông báo kết quả nếu có -->
        <?php if ($message): ?>
            <div class="p-4 mb-6 rounded-2xl border <?php
                echo match($messageType) {
                    'success' => 'bg-emerald-50 border-emerald-200 text-emerald-800',
                    'error' => 'bg-rose-50 border-rose-200 text-rose-800',
                    default => 'bg-amber-50 border-amber-200 text-amber-800'
                };
            ?>">
                <div class="flex items-start gap-3">
                    <?php if ($messageType === 'success'): ?>
                        <svg class="w-5 h-5 text-emerald-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    <?php elseif ($messageType === 'error'): ?>
                        <svg class="w-5 h-5 text-rose-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    <?php else: ?>
                        <svg class="w-5 h-5 text-amber-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    <?php endif; ?>
                    <div>
                        <p class="text-sm font-medium"><?= $message ?></p>
                        <?php if ($verifyResult && $verifyResult['verified']): ?>
                            <div class="mt-2 text-xs bg-emerald-100/70 p-3 rounded-xl border border-emerald-200">
                                <span class="font-bold text-emerald-900">✓ Xác thực mật khẩu tự động:</span>
                                Mã hash mới trong CSDL khớp 100% với mật khẩu vừa nhập (`password_verify() = true`).
                                <div class="font-mono text-[11px] text-emerald-800 truncate mt-1">Hash: <?= htmlspecialchars($verifyResult['hash']) ?></div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Form đổi mật khẩu -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-8">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-base font-semibold text-slate-800 flex items-center gap-2">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                    </svg>
                    <?php if ($currentUser): ?>
                        Đang đổi mật khẩu cho: <span class="text-amber-600 font-mono">#<?= htmlspecialchars($currentUser['id']) ?> - <?= htmlspecialchars($currentUser['username']) ?></span>
                    <?php else: ?>
                        Chọn người dùng bên dưới để đổi mật khẩu
                    <?php endif; ?>
                </h2>

                <?php if ($currentUser): ?>
                    <a href="test_changePassword.php" class="text-xs text-slate-500 hover:text-slate-800 underline">
                        Hủy chọn
                    </a>
                <?php endif; ?>
            </div>

            <!-- Nếu chưa chọn user nào: Form nhập ID -->
            <?php if (!$currentUser): ?>
                <form method="GET" class="flex items-center gap-3 p-4 bg-slate-50 rounded-xl border border-slate-200">
                    <div class="flex-1">
                        <label for="select_id" class="block text-xs font-semibold text-slate-600 mb-1">
                            Nhập ID người dùng cần đổi mật khẩu:
                        </label>
                        <input type="number" name="id" id="select_id" required min="1" placeholder="Ví dụ: 2, 3..."
                            class="w-full px-4 py-2 bg-white border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 font-mono">
                    </div>
                    <button type="submit" class="mt-5 px-5 py-2 bg-slate-800 hover:bg-slate-900 text-white text-sm font-medium rounded-lg transition-all">
                        Tải người dùng
                    </button>
                </form>
            <?php endif; ?>

            <!-- Nếu đã chọn user: Hiển thị form đổi mật khẩu -->
            <?php if ($currentUser): ?>
                <div class="mb-5 p-3.5 bg-amber-50/60 rounded-xl border border-amber-100 flex items-center justify-between text-xs">
                    <div>
                        <span class="text-slate-500">Người dùng:</span>
                        <strong class="text-slate-800 ml-1"><?= htmlspecialchars($currentUser['username']) ?></strong>
                        <span class="text-slate-400 ml-2 font-mono">(<?= htmlspecialchars($currentUser['email']) ?>)</span>
                    </div>
                    <span class="px-2.5 py-0.5 rounded-full font-medium border <?= getRoleBadgeClass($currentUser['role'] ?? '') ?>">
                        <?= strtoupper($currentUser['role'] ?? 'TENANT') ?>
                    </span>
                </div>

                <form method="POST" class="space-y-4">
                    <input type="hidden" name="action" value="change_password">
                    <input type="hidden" name="id" value="<?= htmlspecialchars($currentUser['id']) ?>">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Mật khẩu mới -->
                        <div>
                            <label for="new_password" class="block text-xs font-semibold text-slate-600 mb-1">
                                Mật khẩu mới <span class="text-rose-500">*</span>
                            </label>
                            <input type="password" name="new_password" id="new_password" required
                                placeholder="Nhập mật khẩu mới..."
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all">
                        </div>

                        <!-- Xác nhận mật khẩu mới -->
                        <div>
                            <label for="confirm_password" class="block text-xs font-semibold text-slate-600 mb-1">
                                Xác nhận mật khẩu mới <span class="text-rose-500">*</span>
                            </label>
                            <input type="password" name="confirm_password" id="confirm_password" required
                                placeholder="Nhập lại mật khẩu mới..."
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all">
                        </div>
                    </div>

                    <!-- Nút Submit -->
                    <div class="pt-2 flex justify-end gap-3">
                        <a href="test_changePassword.php" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 text-sm font-medium rounded-xl transition-all">
                            Hủy bỏ
                        </a>
                        <button type="submit"
                            class="px-6 py-2.5 bg-amber-600 hover:bg-amber-700 text-white font-medium text-sm rounded-xl shadow-sm shadow-amber-200 transition-all flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                            </svg>
                            Xác Nhận Đổi Mật Khẩu
                        </button>
                    </div>
                </form>
            <?php endif; ?>
        </div>

        <!-- Bảng danh sách người dùng trong DB có nút Chọn đổi MK -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h2 class="text-base font-bold text-slate-900">Danh sách người dùng hiện có trong Database</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Bấm nút "Đổi MK" để chọn nhanh người dùng cần đổi mật khẩu</p>
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
                            <th class="py-3 px-6">Mã băm hiện tại (Hash)</th>
                            <th class="py-3 px-6 text-right">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php if (empty($userList)): ?>
                            <tr>
                                <td colspan="6" class="py-8 text-center text-slate-400">
                                    Chưa có người dùng nào trong cơ sở dữ liệu.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($userList as $u): ?>
                                <tr class="hover:bg-slate-50/80 transition-colors <?= ($currentUser && $currentUser['id'] == $u['id']) ? 'bg-amber-50/50' : '' ?>">
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
                                    <td class="py-3.5 px-6 font-mono text-xs text-slate-400 truncate max-w-xs"
                                        title="<?= htmlspecialchars($u['password_hash'] ?? '') ?>">
                                        <?= htmlspecialchars(substr($u['password_hash'] ?? '', 0, 20)) ?>...
                                    </td>
                                    <td class="py-3.5 px-6 text-right">
                                        <a href="test_changePassword.php?id=<?= $u['id'] ?>"
                                            class="inline-flex items-center gap-1 px-3 py-1 text-xs font-medium text-amber-700 hover:text-amber-900 hover:bg-amber-50 rounded-lg transition-colors border border-amber-200">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                            </svg>
                                            Đổi MK
                                        </a>
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
