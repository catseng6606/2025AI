<?php
// login.php
// 模擬使用者登入並設定 Cookie

$cookie_name = "session_id";
$cookie_value = "user_secret_123";

// 關鍵在這裡！使用 options 陣列設定 samesite
setcookie($cookie_name, $cookie_value, [
    'expires' => time() + 3600, // 1小時後過期
    'path' => '/',
    'domain' => '', // 預設當前網域
    'secure' => false, // 本機測試若無 HTTPS 設為 false，正式環境請設 true
    'httponly' => true,
    'samesite' => 'Lax' // 👈 主角登場！
]);
setcookie($cookie_value, 50000, [
    'expires' => time() + 3600, // 1小時後過期
    'path' => '/',
    'domain' => '', // 預設當前網域
    'secure' => false, // 本機測試若無 HTTPS 設為 false，正式環境請設 true
    'httponly' => true,
    'samesite' => 'Lax' // 👈 主角登場！
]);

echo "<h1>登入成功！</h1>";
echo "Cookie 已設定。<br>";
echo "<a href='usermoney.php'>查看我的餘額 (usermoney.php)</a>";
?>