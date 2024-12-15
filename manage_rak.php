<?php
session_start();

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

include 'koneksi.php';

// Hapus rak jika parameter delete_id ada
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $delete_query = "DELETE FROM tempat_simpan WHERE id = $delete_id";
    if (mysqli_query($conn, $delete_query)) {
        echo "<script>
            alert('Rak berhasil dihapus!');
            window.location='manage_trak.php';
        </script>";
    } else {
        echo "<script>
            alert('Gagal menghapus rak.');
            window.location='manage_tempat.php';
        </script>";
    }
}

// Ambil data rak
$query = "SELECT * FROM tempat_simpan";
$result = mysqli_query($conn, $query);

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Tempat Rak</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <div class="dashboard-container">
        <h1>Manage Tempat Rak</h1>
        <div class="keta">
            <a href="add_tempat.php" class="tm">Tambah Rak</a>
        </div>
        <table class="user-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tempat Rak</th>
                    <th>Kapasitas</th>
                    <th>keterangan</th>
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
                        <td><?php echo htmlspecialchars($row['nama_tempat_simpan']); ?></td>
                        <td><?php echo htmlspecialchars($row['kapasitas']); ?></td>
                        <td><?php echo htmlspecialchars($row['deskripsi']); ?></td>
                        <td>
                            <a href="edit_tempat.php?id=<?php echo $row['id']; ?>" class="btn-edit">Edit</a>
                            <a href="manage_rak.php?delete_id=<?php echo $row['id']; ?>"
                                onclick="return confirm('Hapus rak ini?');" class="btn-delete">Hapus</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>

</html>