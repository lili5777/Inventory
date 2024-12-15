<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

include 'koneksi.php';

$id = intval($_GET['id']);
$query = "SELECT * FROM kategori WHERE id = $id";
$result = mysqli_query($conn, $query);
$kategori = mysqli_fetch_assoc($result);

if (!$kategori) {
    echo "<script>
        alert('Kategori tidak ditemukan!');
        window.location='manage_inventory.php';
    </script>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_kategori = mysqli_real_escape_string($conn, $_POST['nama_kategori']);
    $update_query = "UPDATE kategori SET nama_kategori = '$nama_kategori' WHERE id = $id";

    if (mysqli_query($conn, $update_query)) {
        echo "<script>
            alert('Kategori berhasil diubah!');
            window.location='manage_inventory.php';
        </script>";
    } else {
        echo "<script>
            alert('Gagal mengubah kategori.');
        </script>";
    }
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Kategori</title>
    <link rel="stylesheet" href="styles.css?v=1">
</head>

<body>
    <div class="dashboard-container">
        <h1>Edit Kategori</h1>
        <form action="" method="POST" class="form-container">
            <div class="form-group">
                <label for="nama_kategori">Nama Kategori</label>
                <input type="text" name="nama_kategori" id="nama_kategori" value="<?php echo htmlspecialchars($kategori['nama_kategori']); ?>" required>
            </div>
            <button type="submit" class="btn-submit">Simpan Perubahan</button>
        </form>
        <a href="manage_inventory.php" class="menu-item">Kembali</a>
    </div>
</body>

</html>