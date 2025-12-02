<?php
// addmoney.php
// 接收 GET 請求來增加餘額

session_start(); // 雖然我們手動檢查 Cookie，但通常會配合 Session

// 1. 檢查是否有登入憑證 (Cookie)
if (isset($_COOKIE['session_id']) && $_COOKIE['session_id'] === 'user_secret_123') {
    
    // 2. 執行敏感操作 (這裡是加錢)
    // 在真實資料庫中應該是 UPDATE users SET money = money + 1000 ...
    $s = "";
    
    $s .= "<h1>💰 交易成功！</h1>";
    $s .= "已為您的帳戶增加 $10 元。(" . $_COOKIE["user_secret_123"] . ")<br>";
    $s .= "目前時間：" . date("Y-m-d H:i:s");
    $new_money = intval($_COOKIE["user_secret_123"]) + 10;
    setcookie("user_secret_123", $new_money, time() + 3600);
    $s .= "<br>新的餘額：" . $new_money;
    echo $s . "\n";
    // 記錄個 log 方便我們觀察 (這裡簡化用 echo)
    file_put_contents("log.txt", "有人加 10 元了! 時間: " . date("H:i:s") . "\n" . $s , FILE_APPEND);

} else {
    // 沒有 Cookie，拒絕服務
    echo "<h1>⛔ 交易失敗</h1>";
    echo "請先登入。";
}
?>