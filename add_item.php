<?php
session_start();

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

include 'koneksi.php';

// Ambil kategori dari URL
$kategori = isset($_GET['kategori']) ? mysqli_real_escape_string($conn, $_GET['kategori']) : '';
if (!$kategori) {
    header("Location: manage_inventory.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_barang = mysqli_real_escape_string($conn, $_POST['nama_barang']);
    $stok = intval($_POST['stok']);
    $tempat_simpan = intval($_POST['tempat_simpan']);
    $ket = mysqli_real_escape_string($conn, $_POST['ket']);

    // Validasi data
    if (!empty($nama_barang) && $stok > 0 && $tempat_simpan > 0) {
        $query = "INSERT INTO barang (nama_barang, stok, id_kategori, id_tempat_simpan,keterangan) 
                  VALUES ('$nama_barang', $stok, '$kategori', $tempat_simpan,'$ket')";
        if (mysqli_query($conn, $query)) {
            echo "<script>
                alert('Barang berhasil ditambahkan!');
                window.location='view_inventory.php?kategori=$kategori';
            </script>";
        } else {
            $error_message = "Gagal menambahkan barang. Silakan coba lagi.";
        }
    } else {
        $error_message = "Semua data harus diisi dengan benar.";
    }
}

// Ambil daftar tempat simpan
$tempat_query = "SELECT id, nama_tempat_simpan FROM tempat_simpan";
$tempat_result = mysqli_query($conn, $tempat_query);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Barang</title>
    <link rel="stylesheet" href="styles.css?v=1">
</head>

<body>
    <div class="dashboard-container">
        <h1>Tambah Barang</h1>
        <?php if (isset($error_message)) : ?>
            <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>
        <form action="" method="POST" class="form-container">
            <div class="form-group">
                <label for="nama_barang">Nama Barang</label>
                <input type="text" name="nama_barang" id="nama_barang" required>
            </div>
            <div class="form-group">
                <label for="stok">Stok</label>
                <input type="number" name="stok" id="stok" min="1" required>
            </div>
            <div class="form-group">
                <label for="ket">Keterangan</label>
                <input type="text" name="ket" id="ket" required>
            </div>
            <div class="form-group">
                <label for="tempat_simpan">Tempat Simpan</label>
                <select name="tempat_simpan" id="tempat_simpan" required>
                    <option value="">Pilih Rak</option>
                    <?php while ($tempat = mysqli_fetch_assoc($tempat_result)) : ?>
                        <option value="<?php echo $tempat['id']; ?>">
                            <?php echo htmlspecialchars($tempat['nama_tempat_simpan']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <button type="submit" class="btn-submit">Simpan</button>
        </form>
        <br>
        <a href="view_inventory.php?kategori=<?php echo $kategori; ?>" class="menu-item">Kembali</a>
    </div>
</body>

</html>

<?php
// Tutup koneksi setelah semua operasi selesai
mysqli_close($conn); ?>