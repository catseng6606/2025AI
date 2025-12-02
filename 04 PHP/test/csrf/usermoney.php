<?php
// usermoney.php
// 顯示使用者餘額的頁面 from cookie 
session_start(); // 雖然我們手動檢查 Cookie，但通常會配合 Session

if (isset($_COOKIE['session_id']) && $_COOKIE['session_id'] === 'user_secret_123') {
  
    echo "<h1>💰 我的錢包</h1>";
    echo "歡迎回來，尊貴的會員！<br>";
    echo "目前餘額：<strong>".$_COOKIE['user_secret_123']."</strong>";
} else {
    echo "<h1>⛔ 未登入</h1>";
    echo "抱歉，我看不到你的 Cookie (通行證)。請先 <a href='login.php'>登入</a>。";
}
?>