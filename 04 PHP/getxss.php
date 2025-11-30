<?php
// using $_GET[""] to demonstrate XSS vulnerability
if (isset($_GET["input"])) {
    $input = $_GET["input"];
    // Unsafe output
    echo "Unsafe Output: " . $input . "<br>";
    // Safe output
    $safe_input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
    echo "Safe Output: " . $safe_input . "<br>";
} else {
    echo '<form method="get" action="">
            Input: <input type="text" name="input">
            <input type="submit" value="Submit">        
          </form>';
}
?>