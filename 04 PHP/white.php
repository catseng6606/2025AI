<?php
// A simple white page to test XSS attacks
// case 6 單引號雙引號混合的 script 標籤攻擊
$cases = [
    '<script>alert("script");</script>',
    '<scrscriptipt>alert("取代 script ʼʼ ");</scrscriptipt>',
    '<scr&#x69;pt>alert("script 內碼");</scr&#x69;pt>',
    '<a href="javascript:alert(\'偽協議範例：javascript:alert\')">Click me</a>',
    '<a href="java&#x73;cript:alert(\'使用偽協議加內碼\')">Click me</a>'

];
$case_number = -1;
if (isset($_GET["case"])) {
    $case_number = intval($_GET["case"]);

}
?>
<html>
    <head>
        <title>White Page</title>
    </head>
    <body>
        <h1>This is a white page.</h1>
        <p>case <?php echo $case_number; ?></p>
        <?php echo $cases[$case_number] ; ?>
        <hr />
        <?php
            foreach ($cases as $index => $case) {
                echo "case " . ($index) . ": ";
                echo "<code>" . htmlspecialchars( $case , ENT_QUOTES, 'UTF-8') . "</code><br />";
            }
        ?>
       <a href="java&#x73;cript:alert(\'使用偽協議加內碼\')">Click me</a>
    </body>
</html>