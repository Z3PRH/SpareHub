<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'buyer') {
    $_SESSION['redirect_after_login'] = 'payment.php';
    header('Location: Login.html');
    exit();
}

header('Location: payment.php');
exit();
?>
