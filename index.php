<?php
include 'koneksi.php';
session_start();
if (isset($_SESSION['username'])) {
    header("Location: dashboard.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <div class="login-container">
        <div class="login-box">
            <h2>Login</h2>
            <form action="auth.php" method="POST">
                <div class="input-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <input type="hidden" name="action" value="login" id="action">
                <button type="submit" class="login-btn">Login</button>
                <p class="signup-link">Belum punya akun? <a href="signup.php">Daftar</a></p>
            </form>
        </div>
    </div>
</body>

</html>