<?php
session_start();
require_once("../database/db.php"); // Include the database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["userid"];
    $password = $_POST["password"];

    // Prepare and execute query to fetch admin user
    $stmt = $conn->prepare("SELECT user_id, password FROM users WHERE user_id = ? AND role = 'admin' LIMIT 1");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    // Check if user exists
    if ($stmt->num_rows == 1) {
        $stmt->bind_result($db_userid, $db_password);
        $stmt->fetch();

        // Compare passwords (plain text, for demo; use password_hash in production)
        if ($password === $db_password) {
            $_SESSION["loggedin"] = true;
            $_SESSION["username"] = $db_userid;
            header("Location: admin.php");
            exit;
        }
    }
    // Redirect back to login with error
    header("Location: Login.html?error=Invalid credentials");
    exit;
}
?>