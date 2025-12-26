<?php
$clsname = "新尖兵資安入門";
if (!isset($_POST['username']) || !isset($_POST['password']) || $_POST['username'] == "" || $_POST['password'] == "") {
    header("Location: index.php");
}
$username = $_POST['username'];
$password = sha1($_POST['password']);

require_once('config.php');
$sql = "SELECT * FROM users WHERE username = '$username' and password = '$password';";
$stmt = $pdo->query($sql);
$success = count($stmt->fetchAll()) > 0;
$text = "";
$success ? $text = $clsname : $text = "登入失敗";
echo $text;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>

<body>
    <form method="POST" action="login.php">
        <input id="username" placeholder="Username" required="" autofocus="" type="text" name="username">
        <input id="password" placeholder="Password" required="" type="password" name="password">
        <button type="submit">登入</button>
    </form>
</body>

</html>