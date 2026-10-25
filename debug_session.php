<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

echo "<h2>Dữ liệu trong Session hiện tại:</h2>";
echo "<pre style='background: #1e1e1e; color: #4ec9b0; padding: 15px; border-radius: 8px;'>";
print_r($_SESSION);
echo "</pre>";
