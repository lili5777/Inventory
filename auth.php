<?php
include 'koneksi.php';


// Menentukan aksi (login atau register)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    if ($action === 'login') {
        // Proses login
        $username = $_POST['username'];
        $password = $_POST['password'];

        $query = "SELECT * FROM User WHERE username = '$username'";
        $result = mysqli_query($conn, $query);
        $user = mysqli_fetch_assoc($result);

        if ($user && password_verify($password, $user['password'])) {
            // Simpan data pengguna ke dalam sesi
            session_start();
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['fullname'] = $user['nama_lengkap'];

            // Redirect ke dashboard
            header("Location: dashboard.php");
            exit();
        } else {
            echo "Login gagal: Username atau password salah.";
        }
    } elseif ($action === 'register') {
        // Proses register
        $fullname = $_POST['fullname'];
        $email = $_POST['email'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        if ($password !== $confirm_password) {
            die("Password dan Konfirmasi Password tidak cocok!");
        }

        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        $query = "INSERT INTO User (username, password, role, nama_lengkap, email) 
                  VALUES ('$username', '$hashed_password', 'staff', '$fullname', '$email')";

        if (mysqli_query($conn, $query)) {
            echo "Registrasi berhasil! Silakan login.";
        } else {
            echo "Registrasi gagal: " . mysqli_error($conn);
        }
    }
}

mysqli_close($conn);
