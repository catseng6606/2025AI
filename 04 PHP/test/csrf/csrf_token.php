<?php
session_start();
function generate_csrf_token() {
    return bin2hex(random_bytes(32));
}
function verify_csrf_token($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}
// 初始化 CSRF token
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = generate_csrf_token();
}
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $num = isset($_POST['num']) ? intval($_POST['num']) : 0;
    $token = isset($_POST['csrf_token']) ? $_POST['csrf_token'] : '';
    if (!verify_csrf_token($token)) {
        $msg = '<h2 style="color:red;">CSRF 驗證失敗</h2>';
    } elseif ($num < 1) {
        $msg = '<h2 style="color:red;">金額不合法</h2>';
    } else {
        $old_money = isset($_COOKIE["user_secret_123"]) ? intval($_COOKIE["user_secret_123"]) : 0;
        $new_money = $old_money - $num;
        if ($new_money < 0) $new_money = 0;
        setcookie("user_secret_123", $new_money, time() + 3600);
        $msg = "<h2 style='color:green;'>扣款成功！</h2>";
        $msg .= "<p>原餘額：$old_money<br>扣除：$num<br>新餘額：$new_money</p>";
    }
} else {
    $msg = "金額:" . (isset($_COOKIE["user_secret_123"]) ? $_COOKIE["user_secret_123"] : 0);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CSRF Demo - Token</title>
</head>
<body>
    <?php echo $msg; ?>

    <form method="post" action="http://localhost:8080/csrf/csrf_token.php">

        <label for="num">扣款金額：</label>
        <input type="number" name="num" id="num" min="1" value="10">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">
        <button type="submit">扣款</button>
    </form>
</body>
</html>