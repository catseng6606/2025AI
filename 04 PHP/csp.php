<?php
// CSP 這意味著你的 HTML 裡不能有 <script>code...</script>，也不能有 onclick="..."
// script-src 'self' http://localhost:8080 'nonce-...'
?>
<?php
// 1. 生成一個隨機的 nonce (實務上要用夠強的亂數產生器)
$nonce = base64_encode(random_bytes(16));

// 2. 設定 CSP Header
// 這裡允許：
// - 'self': 本站的 js 檔案
// - 'nonce-...': 擁有正確 nonce 的 inline script
// - 嚴禁 'unsafe-inline' (這是預設行為)
header("Content-Security-Policy: script-src 'self' 'nonce-{$nonce}' https://cdnjs.cloudflare.com/"); // allow self and nonce and https://cdnjs.cloudflare.com/
    
?>

<!DOCTYPE html>
<html>
<head>
    <title>CSP Demo</title>
</head>
<body>
    <h1>CSP 安全測試</h1>

    <script nonce="<?php echo $nonce; ?>">
        console.log("我是合法的 Inline Script，因為我有 Nonce！");
        document.write("✅ 合法腳本已執行<br>");
    </script>

    <script>
        console.log("我是駭客注入的，我會被瀏覽器擋住！");
        alert("XSS 成功？並沒有！");
    </script>

    <script src="csp.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <hr>
    <p>請打開瀏覽器的開發者工具 (F12) -> Console，你會看到紅色的錯誤訊息，顯示駭客的腳本被 CSP 攔截了。</p>
</body>
</html>