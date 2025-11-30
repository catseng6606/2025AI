<?php
    // TODO: Generate a form for user input contain a text field and a submit button
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8'); // safe
        $name =$_POST['name']; // unsafe
        echo "Hello, " . $name . "!";
    } else {
        echo '<form method="post" action="">
                Name: <input type="text" name="name">
                <input type="submit" value="Submit">
              </form>';
    }    
    
    // 各種語言的 XSS 安全與不安全寫法範例
    // PHP Safe `echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8')` Unsafe `echo $username;`
    // Node.js Safe `<%= username %></h1>` Unsafe  `<%- username %>`
    // Python Flask Safe `{{ username }}` Unsafe `{{ username | safe }}` 
    // ASP.NET Safe `<%: username %>` Unsafe `<%= username %>`
    // ASP.NET Core Safe `@username` Unsafe `@Html.Raw(username)`

    // 使用 HTML 清洗函式來防止 XSS 攻擊
    // PHP safe: `require 'HTML/QuickForm2/Rule/SafeHTML.php';`
    // Node.js safe: `const clean = DOMPurify.sanitize(dirty);`
    // Python Flask safe: `from markupsafe import escape` print(escape(user_input))
    // ASP.NET safe: `var sanitizedInput = sanitizer.Sanitize(userInput);`
    // ASP.NET Core safe: `var sanitizedInput = sanitizer.Sanitize(userInput);`    


?>