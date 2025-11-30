<?php
    $kwd = "";
    if (isset($_GET['kwd'])) { // check if 'kwd' parameter is set
        $kwd = $_GET['kwd']; // get the value of 'kwd' parameter
    }
?>
<!DOCTYPE html>
<html lang="zh-Hant-tw">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
        kwd is 
        <span style='color:red'>
        <?php 
            echo htmlspecialchars($kwd, ENT_QUOTES, 'UTF-8');
            // echo $kwd;
            // `javascript:` 偽協議攻擊示範
            // 用 regex 檢查是否以 javascript: 開頭
            if (preg_match('/^\s*javascript:/i', $kwd)) {
                echo "<br>Detected javascript: pseudo-protocol!";
            }
            // 用 http:// // https://

             // 各種語言的 XSS 安全與不安全寫法範例
            // PHP Safe `echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8')` Unsafe `echo $username;`
            // Node.js Safe `<%= username %></h1>` Unsafe  `<%- username %>`
            // Python Flask Safe `{{ username }}` Unsafe `{{ username | safe }}` 
            // ASP.NET Safe `<%: username %>` Unsafe `<%= username %>`
            // ASP.NET Core Safe `@username` Unsafe `@Html.Raw(username)`

            // 使用 HTML 清洗函式來防止 XSS 攻擊
            // PHP safe: `require 'HTML/QuickForm2/Rule/SafeHTML.php
            // Node.js safe: `const clean = DOMPurify.sanitize(dirty);`
            // Python Flask safe: `from markupsafe import escape` print(escape(user_input
            // ASP.NET safe: `var sanitizedInput = sanitizer.Sanitize(userInput);`
            // ASP.NET Core safe: `var sanitizedInput = sanitizer.Sanitize(userInput);`
        ?>        
        </span>
        
        <a href="<?php echo htmlspecialchars($kwd, ENT_QUOTES, 'UTF-8'); ?>" >link</a>
        <img src="" onerror="<?php echo htmlspecialchars($kwd, ENT_QUOTES, 'UTF-8'); ?>" alt="Image">
        <img src="" onerror="" /> 
        <script>
                // using ajax post to send kwd to server and get response
        </script>
        <img src="" />
        
</body>
</html>