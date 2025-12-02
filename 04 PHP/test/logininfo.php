<?php
session_start();
// TODO: Add input num to decrease money by that amount
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $deduct = isset($_POST['num']) ? intval($_POST['num']) : 10;
    if (isset($_SESSION['money']) && $_SESSION['money'] >= $deduct) {
        $_SESSION['money'] -= $deduct;
    } else {
        $_SESSION['money'] = 0;
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Info</title>
</head>

<body>
    <h1>Login Information</h1>
    <form method="post">
        <label for="num">扣款金額：</label>
        <input type="number" name="num" id="num" min="1" value="10">
        <button type="submit">扣款</button>
    </form>
    <h2>
        <?php
        if (isset($_SESSION['username'])) {
            echo "Logged in as: " . htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8');
        } else {
            echo "Not logged in.";
        }
        if(isset($_SESSION["money"])) {
            echo "<h3>Your Money: <span style='color:green;'>" 
            . htmlspecialchars($_SESSION['money'], ENT_QUOTES, 'UTF-8') 
            . "</span></h3>";
        } else {
            echo '';
        }
        ?>
    </h2>
</body>

</html>