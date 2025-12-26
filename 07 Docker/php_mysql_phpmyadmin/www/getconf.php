<?php
include 'dbconf.php';

$no = $_GET['no'];

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 故意使用不安全的 SQL 串接來演示 SQL Injection
    $sql = "SELECT * FROM 課程表 where 課程編號 = '$no'";
    echo "SQL: " . $sql . "<br><hr>";

    $stmt = $conn->query($sql);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($result) > 0) {
        echo "Exist";
    } else {
        echo "Not Exist";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>