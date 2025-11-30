<!DOCTYPE html>
<html lang="zh-Hant-tw">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demo Dom-based XSS</title>
</head>
<body>
    <h1>Demo Dom-based XSS</h1>
    <p>請在下方輸入框中輸入內容，並觀察頁面行為。</p>
    <input type="text" id="userInput" placeholder="輸入一些內容...">
    <button onclick="displayInput()">顯示輸入內容</button>
    <div id="output"></div>
    <img id="img" src="" alt="Image"  onerror=""/>
    <script>
        function displayInput() {
            // 從輸入框取得使用者輸入的值
            var input = document.getElementById('userInput').value;
            console.log("User input:", input);
            // 直接將 onerror 屬性設為使用者輸入（不安全，僅供測試）
            document.getElementById('img').setAttribute('onerror', input);
            // 設定 src 為 javascript:alert('XSS') 來測試偽協議
            document.getElementById('img').src = "x.jpg";
        }
    </script>
</body>
</html>