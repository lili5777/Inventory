<?php
session_start();

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

include 'koneksi.php';

$kategori = isset($_GET['kategori']) ? mysqli_real_escape_string($conn, $_GET['kategori']) : '';
if (!$kategori) {
    header("Location: manage_inventory.php");
    exit();
}

if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);

    $delete_query = "DELETE FROM barang WHERE id = $delete_id";
    if (mysqli_query($conn, $delete_query)) {
        echo "<script>
            alert('Barang berhasil dihapus!');
            window.location='view_inventory.php?kategori=$kategori';
        </script>";
    } else {
        echo "<script>
            alert('Gagal menghapus barang.');
            window.location='view_inventory.php?kategori=$kategori';
        </script>";
    }
}

// Query dengan perbaikan JOIN
$query = "SELECT i.id, i.nama_barang, i.stok, r.nama_tempat_simpan 
          FROM barang i
          JOIN tempat_simpan r ON i.id_tempat_simpan = r.id
          WHERE i.id_kategori = '$kategori'";
$result = mysqli_query($conn, $query);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barang dalam Kategori: <?php echo htmlspecialchars($kategori); ?></title>
    <link rel="stylesheet" href="styles.css?">
</head>

<body>
    <div class="dashboard-container">
        <h1>Barang dalam Kategori: <?php echo htmlspecialchars($kategori); ?></h1>
        <div class="keta">
            <a href="manage_inventory.php" class="km">Kembali</a>
            <a href="add_item.php?kategori=<?php echo $kategori; ?>" class="tm">Tambah Barang</a>
        </div>
        <table class="user-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Barang</th>
                    <th>Jumlah</th>
                    <th>Rak</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                while ($row = mysqli_fetch_assoc($result)) :
                ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo htmlspecialchars($row['nama_barang']); ?></td>
                        <td><?php echo $row['stok']; ?></td>
                        <td><?php echo htmlspecialchars($row['nama_tempat_simpan']); ?></td>
                        <td>
                            <a href="edit_item.php?id=<?php echo $row['id']; ?>" class="btn-edit">Edit</a>
                            <a href="view_inventory.php?delete_id=<?php echo $row['id']; ?>&kategori=<?php echo $kategori ?>"
                                onclick="return confirm('Hapus barang ini?');" class="btn-delete">Hapus</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>

</html>
<?php mysqli_close($conn); ?>