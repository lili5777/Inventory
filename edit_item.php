<?php
session_start();

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

include 'koneksi.php';

// Ambil data barang berdasarkan ID
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id === 0) {
    header("Location: manage_inventory.php");
    exit();
}

$query = "SELECT * FROM barang WHERE id = $id";
$result = mysqli_query($conn, $query);
$barang = mysqli_fetch_assoc($result);

if (!$barang) {
    header("Location: manage_inventory.php");
    exit();
}

// Ambil daftar tempat simpan
$tempat_query = "SELECT id, nama_tempat_simpan FROM tempat_simpan";
$tempat_result = mysqli_query($conn, $tempat_query);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_barang = mysqli_real_escape_string($conn, $_POST['nama_barang']);
    $stok = intval($_POST['stok']);
    $tempat_simpan = intval($_POST['tempat_simpan']);

    if (!empty($nama_barang) && $stok > 0 && $tempat_simpan > 0) {
        $update_query = "UPDATE barang 
                         SET nama_barang = '$nama_barang', stok = $stok, id_tempat_simpan = $tempat_simpan 
                         WHERE id = $id";
        if (mysqli_query($conn, $update_query)) {
            echo "<script>
                alert('Barang berhasil diperbarui!');
                window.location='view_inventory.php?kategori={$barang['id_kategori']}';
            </script>";
        } else {
            $error_message = "Gagal memperbarui barang.";
        }
    } else {
        $error_message = "Semua data harus diisi dengan benar.";
    }
}

mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Barang</title>
    <link rel="stylesheet" href="styles.css?v=1">
</head>

<body>
    <div class="dashboard-container">
        <h1>Edit Barang</h1>
        <?php if (isset($error_message)) : ?>
            <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>
        <form action="" method="POST" class="form-container">
            <div class="form-group">
                <label for="nama_barang">Nama Barang</label>
                <input type="text" name="nama_barang" id="nama_barang" value="<?php echo htmlspecialchars($barang['nama_barang']); ?>" required>
            </div>
            <div class="form-group">
                <label for="stok">Stok</label>
                <input type="number" name="stok" id="stok" min="1" value="<?php echo $barang['stok']; ?>" required>
            </div>
            <div class="form-group">
                <label for="tempat_simpan">Tempat Simpan</label>
                <select name="tempat_simpan" id="tempat_simpan" required>
                    <?php while ($tempat = mysqli_fetch_assoc($tempat_result)) : ?>
                        <option value="<?php echo $tempat['id']; ?>" <?php echo ($tempat['id'] == $barang['id_tempat_simpan']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($tempat['nama_tempat_simpan']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <button type="submit" class="btn-submit">Simpan</button>
        </form>
        <br>
        <a href="view_inventory.php?kategori=<?php echo $barang['id_kategori']; ?>" class="menu-item">Kembali</a>
    </div>
</body>

</html>