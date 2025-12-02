<?php
session_start();
// TODO: Add input num to decrease money by that amount
if (isset($_GET["num"])) {
    
    $deduct = intval($_GET['num']);
    echo "decrease deduct";
    if (isset($_SESSION['money']) && $_SESSION['money'] >= $deduct) {
        $_SESSION['money'] -= $deduct;
    } else {
        $_SESSION['money'] = 0;
    }
} else {
    echo "not int";
}
?>