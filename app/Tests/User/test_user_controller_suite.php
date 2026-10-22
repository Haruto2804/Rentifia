<?php

require_once __DIR__ . '/../../Core/Database.php';
require_once __DIR__ . '/../../Models/User.php';
require_once __DIR__ . '/../../Controllers/Admin/UserController.php';

use App\Core\Database;
use App\Model\User;
use App\Controllers\Admin\UserController;

$startTime = microtime(true);
$db = Database::getConnection();
$controller = new UserController();

$testResults = [];
$passedCount = 0;
$failedCount = 0;

function record_test(string $group, string $name, bool $condition, string $detail = '')
{
    global $testResults, $passedCount, $failedCount;
    if ($condition) {
        $passedCount++;
        $status = 'PASS';
    } else {
        $failedCount++;
        $status = 'FAIL';
    }

    $testResults[$group][] = [
        'name'    => $name,
        'status'  => $status,
        'detail'  => $detail,
        'passed'  => $condition
    ];
}

// ==========================================
// 1. Kiểm thử getAll()
// ==========================================
$groupName = 'getAll() - Lấy danh sách toàn bộ người dùng';
$allUsers = $controller->getAll();
record_test($groupName, 'getAll() trả về định dạng mảng (array)', is_array($allUsers), 'Kiểu trả về phải là array');
record_test($groupName, 'Cơ sở dữ liệu có ít nhất 1 bản ghi người dùng để test', count($allUsers) > 0, 'Tìm thấy ' . count($allUsers) . ' bản ghi trong bảng users');

if (!empty($allUsers)) {
    $firstRecord = $allUsers[0];
    $hasRequiredFields = isset($firstRecord['id'], $firstRecord['username'], $firstRecord['email'], $firstRecord['role']);
    record_test($groupName, 'Mỗi phần tử chứa đầy đủ các khóa cần thiết (id, username, email, role)', $hasRequiredFields, 'Các trường cơ bản phải tồn tại');
}

// ==========================================
// 2. Kiểm thử findById()
// ==========================================
$groupName = 'findById(int $id) - Tìm người dùng theo ID';
$firstId = !empty($allUsers) ? (int) $allUsers[0]['id'] : 1;
$foundUser = $controller->findById($firstId);

record_test($groupName, 'findById() trả về đối tượng thể hiện của App\Model\User', $foundUser instanceof User, 'Đối tượng trả về phải là instance của App\Model\User');
record_test($groupName, 'Getter getId() khớp chính xác với ID được truyền vào (#' . $firstId . ')', $foundUser && $foundUser->getId() === $firstId, 'ID lấy qua getter phải khớp');
record_test($groupName, 'Getter getUsername() và getEmail() có dữ liệu hợp lệ', $foundUser && !empty($foundUser->getUsername()) && !empty($foundUser->getEmail()), 'Username và Email không được để trống');

$notFoundUser = $controller->findById(9999999);
record_test($groupName, 'findById(9999999) với ID không tồn tại trả về đúng null', $notFoundUser === null, 'Phải trả về null khi không tìm thấy');

// ==========================================
// 3. Kiểm thử findByUsername()
// ==========================================
$groupName = 'findByUsername(string $username) - Tìm theo tên đăng nhập';
$targetUsername = $foundUser ? $foundUser->getUsername() : ($allUsers[0]['username'] ?? '');
$userByUsername = $controller->findByUsername($targetUsername);

record_test($groupName, 'findByUsername("' . htmlspecialchars($targetUsername) . '") trả về đối tượng User', $userByUsername instanceof User, 'Đối tượng trả về phải là instance của App\Model\User');
record_test($groupName, 'Getter getUsername() khớp chính xác với chuỗi tìm kiếm', $userByUsername && $userByUsername->getUsername() === $targetUsername, 'Username lấy từ getter phải khớp');

$notFoundByUsername = $controller->findByUsername('__non_existent_username_' . time() . '__');
record_test($groupName, 'findByUsername() với username không tồn tại trả về đúng null', $notFoundByUsername === null, 'Phải trả về null khi không tìm thấy');

// ==========================================
// 4, 5, 6, 7. CRUD trong Database Transaction
// ==========================================
$db->beginTransaction();
try {
    $tempUsername = 'test_runner_' . time();
    $tempEmail = $tempUsername . '@rentifia.local';
    $rawPassword = 'PasswordTest@123';

    // 4. addUser()
    $groupName = 'addUser(User $user) - Thêm người dùng mới';
    $newUser = new User();
    $newUser->setUsername($tempUsername)
            ->setEmail($tempEmail)
            ->setPasswordHash(password_hash($rawPassword, PASSWORD_DEFAULT))
            ->setRole('tenant')
            ->setIsActive(true);

    $addResult = $controller->addUser($newUser);
    record_test($groupName, 'addUser() lưu đối tượng User mới thành công, trả về true', $addResult === true, 'Hàm thực thi câu lệnh INSERT thành công');

    $createdUser = $controller->findByUsername($tempUsername);
    record_test($groupName, 'Người dùng vừa tạo được tìm thấy ngay lập tức qua findByUsername()', $createdUser instanceof User, 'Đối tượng User đã được insert vào CSDL');
    record_test($groupName, 'Email của người dùng mới khớp chính xác (' . $tempEmail . ')', $createdUser && $createdUser->getEmail() === $tempEmail, 'Email lưu trữ chính xác');

    $duplicateUser = new User();
    $duplicateUser->setUsername($tempUsername)
                  ->setEmail('other_' . $tempEmail)
                  ->setPasswordHash('hash')
                  ->setRole('tenant');
    $dupResult = $controller->addUser($duplicateUser);
    record_test($groupName, 'addUser() từ chối khi trùng tên đăng nhập (UNIQUE constraint), trả về false', $dupResult === false, 'Xử lý ngoại lệ PDOException trả về false đúng mong đợi');

    if ($createdUser) {
        $createdId = $createdUser->getId();

        // 5. updateUser()
        $groupName = 'updateUser(User $user) - Cập nhật thông tin người dùng';
        $createdUser->setEmail('updated_' . $tempEmail)
                    ->setRole('manager')
                    ->setIsActive(false);

        $updateResult = $controller->updateUser($createdUser);
        record_test($groupName, 'updateUser() cập nhật dữ liệu thành công, trả về true', $updateResult === true, 'Hàm thực thi câu lệnh UPDATE thành công');

        $refetchedUser = $controller->findById($createdId);
        record_test($groupName, 'Getter getEmail() phản ánh đúng email mới sau cập nhật', $refetchedUser && $refetchedUser->getEmail() === 'updated_' . $tempEmail, 'Email đã được cập nhật');
        record_test($groupName, 'Getter getRole() phản ánh đúng role mới ("manager")', $refetchedUser && $refetchedUser->getRole() === 'manager', 'Role đã được cập nhật');
        record_test($groupName, 'Getter getIsActive() phản ánh đúng trạng thái mới (false/khóa)', $refetchedUser && $refetchedUser->getIsActive() === false, 'Trạng thái hoạt động đã được cập nhật');

        // 6. changePassword()
        $groupName = 'changePassword(int $id, string $newPassword) - Đổi mật khẩu';
        $newSecret = 'NewSecretPassword@456';
        $changeResult = $controller->changePassword($createdId, $newSecret);
        record_test($groupName, 'changePassword() cập nhật mật khẩu thành công, trả về true', $changeResult === true, 'Mật khẩu đã được mã hóa và lưu vào CSDL');

        $userAfterPassChange = $controller->findById($createdId);
        $hashValid = $userAfterPassChange && password_verify($newSecret, $userAfterPassChange->getPasswordHash());
        record_test($groupName, 'Mã hash trong CSDL khớp 100% với mật khẩu mới qua password_verify()', $hashValid, 'Mã băm bcrypt kiểm tra thành công');

        $emptyPassResult = $controller->changePassword($createdId, '   ');
        record_test($groupName, 'changePassword() từ chối chuỗi mật khẩu rỗng hoặc toàn khoảng trắng', $emptyPassResult === false, 'Đã kiểm tra validation mật khẩu rỗng');

        // 7. deleteById()
        $groupName = 'deleteById(int $id) - Xóa người dùng';
        $deleteResult = $controller->deleteById($createdId);
        record_test($groupName, 'deleteById() xóa người dùng thành công, trả về true', $deleteResult === true, 'Đã xóa bản ghi khỏi CSDL');

        $deletedCheck = $controller->findById($createdId);
        record_test($groupName, 'findById() trả về đúng null sau khi bản ghi đã bị xóa', $deletedCheck === null, 'Bản ghi không còn tồn tại trong CSDL');
    }

    $deleteNonExistent = $controller->deleteById(9999999);
    record_test('deleteById(int $id) - Xóa người dùng', 'deleteById(9999999) với ID không tồn tại trả về false an toàn', $deleteNonExistent === false, 'Không báo lỗi cú pháp, trả về false đúng');

} finally {
    // Luôn luôn rollback để giữ nguyên vẹn dữ liệu thực tế
    $db->rollBack();
}

$executionTime = round((microtime(true) - $startTime) * 1000, 2);
$totalTests = $passedCount + $failedCount;
$successRate = $totalTests > 0 ? round(($passedCount / $totalTests) * 100, 1) : 0;

// Nếu chạy từ Terminal CLI: In văn bản tóm tắt rồi dừng
if (PHP_SAPI === 'cli' && empty($_SERVER['HTTP_HOST'])) {
    echo "=== USER CONTROLLER TEST SUITE (CLI MODE) ===" . PHP_EOL;
    foreach ($testResults as $group => $tests) {
        echo PHP_EOL . "[$group]" . PHP_EOL;
        foreach ($tests as $t) {
            echo "  " . ($t['passed'] ? '[PASS]' : '[FAIL]') . " " . $t['name'] . PHP_EOL;
        }
    }
    echo PHP_EOL . "Total: $totalTests | Passed: $passedCount | Failed: $failedCount | Time: {$executionTime}ms" . PHP_EOL;
    exit($failedCount === 0 ? 0 : 1);
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rentifia - Visual Test Suite Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        code, pre, .font-mono {
            font-family: 'JetBrains Mono', monospace;
        }
    </style>
</head>

<body class="bg-slate-900 text-slate-100 min-h-screen py-10 px-4">
    <div class="max-w-5xl mx-auto">
        <!-- Top Navigation / Brand -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-6 mb-8 border-b border-slate-800 gap-4">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-emerald-500 to-teal-400 flex items-center justify-center text-slate-950 font-black text-xl shadow-lg shadow-emerald-500/20">
                    R
                </div>
                <div>
                    <h1 class="text-xl font-bold tracking-tight text-white flex items-center gap-2">
                        Rentifia Test Suite
                        <span class="text-xs px-2.5 py-0.5 rounded-full font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                            v1.0 OOP
                        </span>
                    </h1>
                    <p class="text-xs text-slate-400">
                        Kiểm thử tự động toàn diện <code class="text-emerald-400">App\Controllers\Admin\UserController</code>
                    </p>
                </div>
            </div>

            <!-- Nút chạy lại và mở các trang test -->
            <div class="flex items-center gap-2">
                <a href="test_user_controller_suite.php"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-semibold text-xs rounded-xl shadow-md shadow-emerald-500/20 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Chạy lại bộ test
                </a>
            </div>
        </div>

        <!-- Metric Stat Cards -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <!-- Tổng số test -->
            <div class="bg-slate-800/60 border border-slate-700/60 rounded-2xl p-5 backdrop-blur-sm">
                <div class="flex items-center justify-between text-slate-400 mb-2">
                    <span class="text-xs font-semibold uppercase tracking-wider">Tổng kiểm thử</span>
                    <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <div class="text-3xl font-extrabold text-white"><?= $totalTests ?></div>
                <div class="text-[11px] text-slate-400 mt-1">7 hàm UserController</div>
            </div>

            <!-- Số lượng Passed -->
            <div class="bg-emerald-950/30 border border-emerald-500/30 rounded-2xl p-5 backdrop-blur-sm">
                <div class="flex items-center justify-between text-emerald-400 mb-2">
                    <span class="text-xs font-semibold uppercase tracking-wider">Thành công (PASS)</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="text-3xl font-extrabold text-emerald-400"><?= $passedCount ?></div>
                <div class="text-[11px] text-emerald-300/80 mt-1">Đạt 100% assertions</div>
            </div>

            <!-- Số lượng Failed -->
            <div class="bg-rose-950/30 border <?= $failedCount > 0 ? 'border-rose-500/50' : 'border-slate-800' ?> rounded-2xl p-5 backdrop-blur-sm">
                <div class="flex items-center justify-between <?= $failedCount > 0 ? 'text-rose-400' : 'text-slate-500' ?> mb-2">
                    <span class="text-xs font-semibold uppercase tracking-wider">Thất bại (FAIL)</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="text-3xl font-extrabold <?= $failedCount > 0 ? 'text-rose-400' : 'text-slate-500' ?>">
                    <?= $failedCount ?>
                </div>
                <div class="text-[11px] text-slate-500 mt-1">Lỗi cần sửa</div>
            </div>

            <!-- Tỉ lệ & Thời gian -->
            <div class="bg-slate-800/60 border border-slate-700/60 rounded-2xl p-5 backdrop-blur-sm">
                <div class="flex items-center justify-between text-slate-400 mb-2">
                    <span class="text-xs font-semibold uppercase tracking-wider">Thời gian chạy</span>
                    <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="text-3xl font-extrabold text-amber-400"><?= $executionTime ?> <span class="text-sm font-normal text-slate-400">ms</span></div>
                <div class="text-[11px] text-slate-400 mt-1">Transaction rollback tự động</div>
            </div>
        </div>

        <!-- Progress Bar -->
        <div class="bg-slate-800/80 rounded-2xl p-6 border border-slate-700/60 mb-8">
            <div class="flex items-center justify-between mb-2">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full <?= $failedCount === 0 ? 'bg-emerald-400 animate-pulse' : 'bg-rose-400' ?>"></span>
                    <span class="text-sm font-bold text-white">
                        <?= $failedCount === 0 ? 'Toàn bộ hàm kiểm thử đạt chuẩn (All Tests Passed)!' : 'Phát hiện có kiểm thử thất bại' ?>
                    </span>
                </div>
                <span class="text-sm font-mono font-bold <?= $failedCount === 0 ? 'text-emerald-400' : 'text-rose-400' ?>">
                    <?= $successRate ?>%
                </span>
            </div>
            <div class="w-full h-3 bg-slate-900 rounded-full overflow-hidden p-0.5 border border-slate-700">
                <div class="h-full rounded-full bg-gradient-to-r from-emerald-500 to-teal-400 transition-all duration-500"
                    style="width: <?= $successRate ?>%;"></div>
            </div>
        </div>

        <!-- Danh sách chi tiết từng hàm (Visual Test Accordion / Groups) -->
        <div class="space-y-6">
            <?php foreach ($testResults as $groupName => $tests): ?>
                <?php
                    $groupFailed = array_filter($tests, fn($t) => !$t['passed']);
                    $hasFailures = count($groupFailed) > 0;
                ?>
                <div class="bg-slate-800/40 rounded-2xl border border-slate-800 overflow-hidden transition-all hover:border-slate-700">
                    <!-- Tiêu đề nhóm hàm -->
                    <div class="p-5 bg-slate-800/70 border-b border-slate-700/60 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-xl <?= $hasFailures ? 'bg-rose-500/20 text-rose-400' : 'bg-emerald-500/20 text-emerald-400' ?> flex items-center justify-center font-bold text-sm">
                                <?php if ($hasFailures): ?>
                                    ✕
                                <?php else: ?>
                                    ✓
                                <?php endif; ?>
                            </div>
                            <div>
                                <h3 class="font-bold text-sm text-white font-mono"><?= htmlspecialchars($groupName) ?></h3>
                                <p class="text-xs text-slate-400 mt-0.5"><?= count($tests) ?> assertions</p>
                            </div>
                        </div>

                        <span class="text-xs px-3 py-1 rounded-full font-semibold <?= $hasFailures ? 'bg-rose-500/10 text-rose-400 border border-rose-500/20' : 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' ?>">
                            <?= $hasFailures ? count($groupFailed) . ' lỗi' : 'Tất cả đạt (PASS)' ?>
                        </span>
                    </div>

                    <!-- Danh sách các assertions trong nhóm -->
                    <div class="divide-y divide-slate-800/60">
                        <?php foreach ($tests as $t): ?>
                            <div class="p-4 flex items-start gap-3 hover:bg-slate-800/30 transition-colors">
                                <?php if ($t['passed']): ?>
                                    <div class="w-6 h-6 rounded-lg bg-emerald-500/10 text-emerald-400 flex items-center justify-center flex-shrink-0 mt-0.5 border border-emerald-500/20">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                <?php else: ?>
                                    <div class="w-6 h-6 rounded-lg bg-rose-500/10 text-rose-400 flex items-center justify-center flex-shrink-0 mt-0.5 border border-rose-500/20">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </div>
                                <?php endif; ?>

                                <div class="flex-1">
                                    <div class="text-xs sm:text-sm font-medium <?= $t['passed'] ? 'text-slate-200' : 'text-rose-300 font-bold' ?>">
                                        <?= htmlspecialchars($t['name']) ?>
                                    </div>
                                    <?php if (!empty($t['detail'])): ?>
                                        <div class="text-xs text-slate-400 mt-1 font-mono">
                                            Chi tiết: <?= htmlspecialchars($t['detail']) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <span class="font-mono text-[11px] font-bold px-2.5 py-0.5 rounded <?= $t['passed'] ? 'bg-emerald-500/10 text-emerald-400' : 'bg-rose-500/20 text-rose-400' ?>">
                                    <?= $t['status'] ?>
                                </span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Các liên kết nhanh tới các trang test giao diện riêng lẻ -->
        <div class="mt-10 p-6 bg-slate-800/40 border border-slate-800 rounded-2xl">
            <h3 class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-4">
                Các trang kiểm thử trực quan từng chức năng (Visual Unit Pages):
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                <a href="test_getAll_users.php" class="p-3 bg-slate-800/70 hover:bg-slate-700/70 border border-slate-700/60 rounded-xl text-xs font-medium text-slate-300 hover:text-white transition-all flex items-center justify-between">
                    <span>1. test_getAll_users.php</span>
                    <span class="text-slate-500">→</span>
                </a>
                <a href="test_findById_users.php" class="p-3 bg-slate-800/70 hover:bg-slate-700/70 border border-slate-700/60 rounded-xl text-xs font-medium text-slate-300 hover:text-white transition-all flex items-center justify-between">
                    <span>2. test_findById_users.php</span>
                    <span class="text-slate-500">→</span>
                </a>
                <a href="test_findByUsername.php" class="p-3 bg-slate-800/70 hover:bg-slate-700/70 border border-slate-700/60 rounded-xl text-xs font-medium text-teal-400 hover:text-teal-300 transition-all flex items-center justify-between">
                    <span>3. test_findByUsername.php (Mới)</span>
                    <span class="text-teal-500">→</span>
                </a>
                <a href="test_addUser.php" class="p-3 bg-slate-800/70 hover:bg-slate-700/70 border border-slate-700/60 rounded-xl text-xs font-medium text-slate-300 hover:text-white transition-all flex items-center justify-between">
                    <span>4. test_addUser.php</span>
                    <span class="text-slate-500">→</span>
                </a>
                <a href="test_updateUser.php" class="p-3 bg-slate-800/70 hover:bg-slate-700/70 border border-slate-700/60 rounded-xl text-xs font-medium text-slate-300 hover:text-white transition-all flex items-center justify-between">
                    <span>5. test_updateUser.php</span>
                    <span class="text-slate-500">→</span>
                </a>
                <a href="test_changePassword.php" class="p-3 bg-slate-800/70 hover:bg-slate-700/70 border border-slate-700/60 rounded-xl text-xs font-medium text-slate-300 hover:text-white transition-all flex items-center justify-between">
                    <span>6. test_changePassword.php</span>
                    <span class="text-slate-500">→</span>
                </a>
                <a href="test_deleteById_users.php" class="p-3 bg-slate-800/70 hover:bg-slate-700/70 border border-slate-700/60 rounded-xl text-xs font-medium text-slate-300 hover:text-white transition-all flex items-center justify-between">
                    <span>7. test_deleteById_users.php</span>
                    <span class="text-slate-500">→</span>
                </a>
            </div>
        </div>

        <div class="mt-8 text-center text-xs text-slate-500">
            Rentifia System Architecture • Powered by Google Antigravity IDE • 2026
        </div>
    </div>
</body>

</html>
