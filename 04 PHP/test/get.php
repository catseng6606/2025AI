<?php
// curl http://localhost:8080/get.php?num=123
$str = "";
if (isset($_GET["num"]) && $_GET["num"] != "") {
    $str = $_GET["num"];
}
// header api key value
// .env add .gitignore
header("X-API-KEY: your_api_key_here");
// check api key
if (isset($_SERVER['HTTP_X_API_KEY'])) {
    $api_key = $_SERVER['HTTP_X_API_KEY'];
    if ($api_key !== 'your_api_key_here') {
        http_response_code(403);
        echo "Forbidden: Invalid API Key";
        exit;
    }
} else {
    http_response_code(403);
    echo "Forbidden: API Key Missing";
    exit;
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
    <form action="" method="get">
        <label for="num">輸入數字：</label>
        <input type="text" id="num" name="num" 
        value="<?php echo htmlspecialchars($str, ENT_QUOTES, 'UTF-8'); ?>">
        <button type="submit">提交</button>
    </form>
    <p>你輸入的數字是：<strong>
        <?php echo htmlspecialchars($str, ENT_QUOTES, 'UTF-8'); ?>
    </strong></p>
</body>

</html>