<?php
echo "<h1>PDO Drivers Check</h1>";
print_r(PDO::getAvailableDrivers());
echo "<hr>";
include 'dbconf.php';

echo "<h2>MySQL Connection Test (PDO)</h2>";
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // 設定 PDO 錯誤模式為例外
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "連線成功 (Connected successfully)";
} catch (PDOException $e) {
    echo "連線失敗 (Connection failed): " . $e->getMessage();
}
?>