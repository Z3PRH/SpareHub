<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include '../database/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userid = trim($_POST["userid"]);
    $password = $_POST["password"];

    if (empty($userid) || empty($password)) {
        header("Location: ../pages/Login.html?error=Please fill in all fields");
        exit;
    }

    $stmt = $conn->prepare("SELECT user_id, name, password, role FROM users WHERE user_id = ? LIMIT 1");

    if (!$stmt) {
        header("Location: ../pages/Login.html?error=Database error");
        exit;
    }

    $stmt->bind_param("i", $userid);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        $db_userid = $user['user_id'];
        $db_password = $user['password'];
        $db_name = $user['name'];
        $db_role = $user['role'];

        if ($password == $db_password) {
            $_SESSION["loggedin"] = true;
            $_SESSION["user_id"] = $db_userid;
            $_SESSION["buyername"] = $db_name;
            $_SESSION["role"] = $db_role;

            if (isset($_SESSION['redirect_after_login'])) {
                $redirectUrl = $_SESSION['redirect_after_login'];
                unset($_SESSION['redirect_after_login']);
                header("Location: ../pages/$redirectUrl");
                exit;
            }

            if ($db_userid == 101) {
                header("Location: ../pages/admin.php");
            } elseif ($db_userid == 201) {
                header("Location: ../pages/buyer.php");
            } else {
                header("Location: ../pages/Homepage.php");
            }
            exit;
        } else {
            header("Location: ../pages/Login.html?error=Invalid credentials");
            exit;
        }
    } else {
        header("Location: ../pages/Login.html?error=Invalid credentials");
        exit;
    }
} else {
    header("Location: ../pages/Login.html?error=Invalid request method");
    exit;
}
