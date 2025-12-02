<?php
// Simulate a simple login form processing
// ahter login save session or cookie (not implemented here for simplicity)

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Simple hardcoded authentication for demonstration
    if ($username === 'admin' && $password === 'password') {
        // Here you could start a session or set a cookie
        session_start();
        $_SESSION['username'] = $username;
        $_SESSION['money'] = 1000; // Example of storing user money in session
        // Redirect to logininfo.php or another page
        // 修正 header already sent 問題，避免在 echo 之後呼叫 header
        header("Location: logininfo.php");
        exit();
    } else {
        $login_error = "Login failed! Invalid username or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">    
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
</head>
<body>
    <h2>Login Form</h2>
    <?php if (!empty($login_error)) { echo '<p style="color:red;">' . htmlspecialchars($login_error, ENT_QUOTES, 'UTF-8') . '</p>'; } ?>
    <form method="POST" action="">
        <label for="username">Username:</label><br>
        <input type="text" id="username" name="username" required><br><br>
        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" required><br><br>
        <input type="submit" value="Login">
    </form>
</body>
</html>