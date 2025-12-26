<?php
include 'dbconf.php';

$no = $_GET['no'];

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 故意使用不安全的 SQL 串接來演示 SQL Injection
    $sql = "SELECT * FROM 課程表 where 課程編號 = '$no' and 學分 > 2";
    echo "SQL: " . $sql . "<br><hr>";

    $stmt = $conn->query($sql);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($result) > 0) {
        echo "<table border='1'>";
        echo "<tr><th>課程編號</th><th>課程名稱</th><th>學分</th></tr>";
        foreach ($result as $row) {
            echo "<tr>";
            echo "<td>" . $row['課程編號'] . "</td>";
            echo "<td>" . $row['課程名稱'] . "</td>";
            echo "<td>" . $row['學分'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "0 results";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>