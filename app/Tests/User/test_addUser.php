<?php

// 1. Nạp Database, Model và Controller (Lùi 2 cấp từ app/Tests/User về app/)
require_once __DIR__ . '/../../Core/Database.php';
require_once __DIR__ . '/../../Models/User.php';
require_once __DIR__ . '/../../Controllers/Admin/UserController.php';

use App\Controllers\Admin\UserController;
use App\Model\User;

$userController = new UserController();
$message = null;
$messageType = null; // 'success' | 'error' | 'warning'

// Xử lý khi Submit Form thêm User (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_user') {
    $username = trim($_POST['username'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $role     = trim($_POST['role'] ?? 'tenant');
    $isActive = isset($_POST['is_active']) ? (bool) $_POST['is_active'] : true;

    // Kiểm tra dữ liệu đầu vào cơ bản
    if ($username === '' || $email === '' || $password === '') {
        $message = "Vui lòng điền đầy đủ Tên đăng nhập, Email và Mật khẩu.";
        $messageType = 'warning';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Định dạng Email không hợp lệ.";
        $messageType = 'warning';
    } else {
        // Tạo đối tượng User và nạp dữ liệu
        $newUser = new User();
        $newUser->setUsername($username)
                ->setEmail($email)
                ->setPasswordHash(password_hash($password, PASSWORD_DEFAULT)) // Luôn băm mật khẩu
                ->setRole($role)
                ->setIsActive($isActive);

        // Gọi Controller thực hiện thêm vào CSDL
        $result = $userController->addUser($newUser);

        if ($result) {
            $message = "Thêm người dùng '<strong>" . htmlspecialchars($username) . "</strong>' thành công!";
            $messageType = 'success';
        } else {
            $message = "Thêm người dùng thất bại! (Tên đăng nhập hoặc Email có thể đã tồn tại trong hệ thống).";
            $messageType = 'error';
        }
    }
}

// Lấy danh sách người dùng để hiển thị kiểm tra
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
    <title>Rentifia - Test addUser()</title>
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
                class="inline-flex items-center gap-2 px-3 py-1 bg-emerald-50 text-emerald-700 rounded-full text-xs font-semibold uppercase tracking-wider mb-2 border border-emerald-200">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                Unit Test Page
            </div>
            <h1 class="text-2xl font-bold text-slate-900">Kiểm Thử Phương Thức addUser()</h1>
            <p class="text-sm text-slate-500 mt-1">
                Gọi qua <code class="bg-slate-100 px-1.5 py-0.5 rounded text-emerald-600 font-mono">App\Controllers\Admin\UserController::addUser(User $user)</code>
            </p>
        </div>

        <!-- Form nhập thông tin thêm người dùng -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-8">
            <h2 class="text-base font-semibold text-slate-800 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                </svg>
                Nhập thông tin người dùng mới
            </h2>

            <form method="POST" class="space-y-4">
                <input type="hidden" name="action" value="add_user">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Username -->
                    <div>
                        <label for="username" class="block text-xs font-semibold text-slate-600 mb-1">
                            Tên đăng nhập (Username) <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="username" id="username" required
                            placeholder="Ví dụ: hoangnam_tenant"
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all">
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-xs font-semibold text-slate-600 mb-1">
                            Địa chỉ Email <span class="text-rose-500">*</span>
                        </label>
                        <input type="email" name="email" id="email" required
                            placeholder="Ví dụ: nam.nguyen@gmail.com"
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all font-mono">
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-xs font-semibold text-slate-600 mb-1">
                            Mật khẩu <span class="text-rose-500">*</span>
                        </label>
                        <input type="password" name="password" id="password" required
                            placeholder="Nhập mật khẩu (sẽ được tự động băm bCrypt)"
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all">
                    </div>

                    <!-- Role & Trạng thái -->
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label for="role" class="block text-xs font-semibold text-slate-600 mb-1">
                                Vai trò (Role)
                            </label>
                            <select name="role" id="role"
                                class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all">
                                <option value="tenant" selected>Tenant (Khách thuê)</option>
                                <option value="manager">Manager (Quản lý)</option>
                                <option value="admin">Admin (Quản trị viên)</option>
                            </select>
                        </div>

                        <div>
                            <label for="is_active" class="block text-xs font-semibold text-slate-600 mb-1">
                                Trạng thái
                            </label>
                            <select name="is_active" id="is_active"
                                class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all">
                                <option value="1" selected>Hoạt động (Active)</option>
                                <option value="0">Khóa (Inactive)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Nút Submit -->
                <div class="pt-2 flex justify-end">
                    <button type="submit"
                        class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-medium text-sm rounded-xl shadow-sm shadow-emerald-200 transition-all flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4v16m8-8H4" />
                        </svg>
                        Thêm Người Dùng Mới
                    </button>
                </div>
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
                    <span class="text-sm font-medium"><?= $message ?></span>
                </div>
            </div>
        <?php endif; ?>

        <!-- Bảng danh sách người dùng trong DB để đối chiếu -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h2 class="text-base font-bold text-slate-900">Danh sách người dùng hiện có trong Database</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Dữ liệu được cập nhật tự động sau khi thêm</p>
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
                            <th class="py-3 px-6">Ngày tạo</th>
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
                                    <td class="py-3.5 px-6 text-slate-400 font-mono text-xs">
                                        <?= htmlspecialchars($u['created_at'] ?? 'N/A') ?>
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
