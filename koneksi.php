<?php
// Konfigurasi database
$host = "localhost";      // Host database
$user = "root";           // Username database
$password = "";           // Password database
$database = "db_inventory"; // Nama database

// Membuat koneksi
$conn = mysqli_connect($host, $user, $password, $database);

// Cek koneksi
// if (!$connection) {
//     die("Koneksi database gagal: " . mysqli_connect_error());
// } else {
//     echo "Koneksi berhasil!";
// }
