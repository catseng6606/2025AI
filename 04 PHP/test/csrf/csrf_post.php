<?php
session_start();
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $num = isset($_POST['num']) ? intval($_POST['num']) : 0;
    if ($num < 1) {
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
    <title>CSRF Demo - 無 Token</title>
</head>
<body>
    <?php echo $msg; ?>
    <form method="post" action="http://localhost:8080/csrf/csrf_post.php">
        <label for="num">扣款金額：</label>
        <input type="number" name="num" id="num" min="1" value="10">
        <button type="submit">扣款</button>
    </form>
</body>
</html>
