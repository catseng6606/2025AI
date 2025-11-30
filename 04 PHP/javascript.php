<?php
    // 請準備偽協議的 php 範例讓我了解 XSS 安全與不安全的寫法
    // 偽協議範例：javascript:alert('XSS')
    // 透過 form 到 javascript.php 配合 href 或 img src 來觸發 XSS
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $input = $_POST['input'];
        // 安全寫法
        $safe_input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
        echo "Safe Output: <a href=\"$safe_input\">Link</a><br>";
        echo "Safe Output: <img src=\"$safe_input\" onerror=\"$safe_input\" alt=\"Image\"><br>";
        // 不安全寫法
        echo "Unsafe Output: <a href=\"$input\">Link</a><br>";
        echo "Unsafe Output: <img src=\"$input\"  onerror=\"$input\" alt=\"Image\"><br>";
        // 新增一個按鈕點選後觸發不安全的 window.location 觸發 XSS
        
    } else {
        echo '<form method="post" action="">
                Input: <input type="text" name="input">
                <input type="submit" value="Submit">        
            </form>';
    }
echo '<button onclick="testXSS()">Click meto trigger XSS</button>'; 
?>
<html>
    <script>
        // 用來測試 XSS 的 javascript:alert('XSS') 範例
        // window.location='javascript:alert("XSS")';
        function testXSS() {
            var xssInput = 'javascript:alert("XSS")';
            // 不安全寫法
            window.location = xssInput;
        }
    </script>
    </html>