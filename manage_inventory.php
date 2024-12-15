<?php
session_start();

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

include 'koneksi.php';
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);

    include 'koneksi.php';

    // Hapus barang yang terkait dengan kategori
    $delete_items_query = "DELETE FROM barang WHERE id_kategori = $delete_id";
    $delete_items_result = mysqli_query($conn, $delete_items_query);

    if ($delete_items_result) {
        // Jika barang berhasil dihapus, hapus kategori
        $delete_query = "DELETE FROM kategori WHERE id = $delete_id";
        if (mysqli_query($conn, $delete_query)) {
            echo "<script>
                alert('Kategori dan barang terkait berhasil dihapus!');
                window.location='manage_inventory.php';
            </script>";
        } else {
            echo "<script>
                alert('Gagal menghapus kategori.');
                window.location='manage_inventory.php';
            </script>";
        }
    } else {
        echo "<script>
            alert('Gagal menghapus barang terkait.');
            window.location='manage_inventory.php';
        </script>";
    }

    mysqli_close($conn);
    exit();
}


// Ambil data kategori dari tabel Inventaris
$query = "SELECT * FROM kategori";
$result = mysqli_query($conn, $query);

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Inventaris</title>
    <link rel="stylesheet" href="styles.css?v=1">
</head>

<body>
    <div class="dashboard-container">
        <h1>Kelola Inventaris</h1>
        <a href="add_inventory.php" class="menu-item">Tambah Kategori</a>
        <table class="user-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kategori</th>
                    <th>Barang</th>
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
                        <td><?php echo htmlspecialchars($row['nama_kategori']); ?></td>
                        <td>
                            <a href="view_inventory.php?kategori=<?php echo $row['id']; ?>" class="menu-item">Lihat Barang</a>
                        </td>
                        <td>
                            <a href="edit_category.php?id=<?php echo $row['id']; ?>" class="btn-edit">Edit</a>
                            <a href="manage_inventory.php?delete_id=<?php echo $row['id']; ?>"
                                onclick="return confirm('Yakin ingin menghapus kategori ini?');"
                                class="btn-delete">Hapus</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>

</html>