<?php
include 'koneksi.php';
session_start();
// Proses hapus pengguna
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $delete_query = "DELETE FROM User WHERE id = $id";
    mysqli_query($conn, $delete_query);
    header("Location: manage_users.php");
    exit();
}

if (isset($_GET['tambah'])) {
    $nama_lengkap = $_POST['nama_lengkap'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Enkripsi password
    $role = $_POST['role'];

    $query = "INSERT INTO User (nama_lengkap, username, email, password, role) 
              VALUES ('$nama_lengkap', '$username', '$email', '$password', '$role')";

    $hasil = mysqli_query($conn, $query);

    if ($hasil) {
        echo '<script>
        alert("Pengguna berhasil ditambahkan");
        window.location="manage_users.php";
        </script>';
    } else {
        echo '<script>
        alert("Gagal menambahkan pengguna: ' . mysqli_error($conn) . '");
        window.location="add_user.php";
        </script>';
    }
}

// Proses ketika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_GET['edit'])) {
        // Ambil data dari form
        $user_id = $_POST['user_id'];
        $nama_lengkap = mysqli_real_escape_string($conn, $_POST['nama_lengkap']);
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : $user['password']; // Password tidak diubah jika kosong
        $role = mysqli_real_escape_string($conn, $_POST['role']);

        // Query untuk memperbarui data pengguna
        $query = "UPDATE User SET
                nama_lengkap = '$nama_lengkap',
                username = '$username',
                email = '$email',
                password = '$password',
                role = '$role'
              WHERE id = '$user_id'";

        // Eksekusi query
        if (mysqli_query($conn, $query)) {
            echo '<script>alert("Pengguna berhasil diperbarui."); window.location="manage_users.php";</script>';
        } else {
            echo '<script>alert("Gagal memperbarui pengguna: ' . mysqli_error($conn) . '");</script>';
        }
    }
}
