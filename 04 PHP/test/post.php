<?php
// curl -X POST -d "num=123" http://localhost:8080/post.php
$str = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["num"]) && $_POST["num"] != "") {
    $str = $_POST["num"];
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
    <form action="" method="post">
        <label for="num">輸入數字：</label>
        <input type="text" id="num" name="num" value="<?php echo htmlspecialchars($str, ENT_QUOTES, 'UTF-8'); ?>">
        <button type="submit">提交</button>
    </form>
    <p>你輸入的數字是：<strong><?php echo htmlspecialchars($str, ENT_QUOTES, 'UTF-8'); ?></strong></p>
    <hr>
    <pre>
curl -X POST -d "num=123" http://localhost:8080/post.php
    </pre>
</body>

</html>