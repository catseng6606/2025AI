<?php
session_start();
function generate_csrf_token() {
    return bin2hex(random_bytes(32));
}
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = generate_csrf_token();
}
$str = "";
$csrf_msg = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = isset($_POST['csrf_token']) ? $_POST['csrf_token'] : '';
    if (!isset($_SESSION['csrf_token']) || $_SESSION['csrf_token'] !== $token) {
        $csrf_msg = '<span style="color:red;">CSRF 驗證失敗</span>';
    } elseif (isset($_POST["num"]) && $_POST["num"] != "") {
        $str = $_POST["num"];
    }
}
?>
<!DOCTYPE html>
<html lang="zh-Hant-tw">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demo Get</title>
</head>

<body>
    <?php echo $csrf_msg; ?>
    <form action="" method="post">
        <label for="num">輸入數字：</label>
        <input type="text" id="num" name="num" value="<?php echo htmlspecialchars($str, ENT_QUOTES, 'UTF-8'); ?>">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">
        <button type="submit">提交</button>
    </form>
    <p>你輸入的數字是：<strong><?php echo htmlspecialchars($str, ENT_QUOTES, 'UTF-8'); ?></strong></p>
    <hr>
    <pre>
curl -X POST -d "num=123&csrf_token=<?php echo $_SESSION['csrf_token']; ?>" http://localhost:8080/post_csrf.php

# 帶 PHPSESSID 範例（請將 YOUR_SESSION_ID 換成實際值）
curl -X POST -d "num=123&csrf_token=<?php echo $_SESSION['csrf_token']; ?>" --cookie "PHPSESSID=YOUR_SESSION_ID" http://localhost:8080/post_csrf.php
    </pre>
</body>

</html>