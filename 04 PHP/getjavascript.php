<?php
// using $_GET[""] to demonstrate XSS vulnerability
if (isset($_GET["input"])) {
    $input = $_GET["input"];
    echo "<img src=\"$input\" onerror=\"$input\" alt=\"Image\"><br>";
} else {
    echo '<form method="get" action="">
            Input: <input type="text" name="input">
            <input type="submit" value="Submit">        
          </form>';
}
$code = "<img src=\"input\" onerror=\"input\" alt=\"Image\"><br>";
echo htmlspecialchars($code, ENT_QUOTES, 'UTF-8');
// using " onchange=javascript:<script>alert("XXX")</script>"
?>
