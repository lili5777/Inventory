<?php
session_start();

// Periksa apakah pengguna sudah login dan memiliki hak akses
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_kategori = mysqli_real_escape_string($conn, $_POST['nama_kategori']);

    // Validasi data
    if (!empty($nama_kategori)) {
        $query = "INSERT INTO kategori (nama_kategori) VALUES ('$nama_kategori')";
        if (mysqli_query($conn, $query)) {
            echo "<script>
                alert('Kategori berhasil ditambahkan!');
                window.location='manage_inventory.php';
            </script>";
        } else {
            $error_message = "Gagal menambahkan kategori. Silakan coba lagi.";
        }
    } else {
        $error_message = "Nama kategori tidak boleh kosong.";
    }
}

mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kategori</title>
    <link rel="stylesheet" href="styles.css?v=1">
</head>

<body>
    <div class="dashboard-container">
        <h1>Tambah Kategori</h1>
        <?php if (isset($error_message)) : ?>
            <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>
        <form action="" method="POST" class="form-container">
            <div class="form-group">
                <label for="nama_kategori">Nama Kategori</label>
                <input type="text" name="nama_kategori" id="nama_kategori" required>
            </div>
            <button type="submit" class="btn-submit">Simpan</button>
        </form>
        <br>
        <a href="manage_inventory.php" class="menu-item">Kembali</a>
    </div>
</body>

</html>