<?php
session_start();

// Periksa apakah pengguna sudah login dan memiliki hak akses admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

// Koneksi ke database
include 'koneksi.php';


// Ambil daftar pengguna
$query = "SELECT * FROM User";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pengguna</title>
    <link rel="stylesheet" href="styles.css?v=1">
</head>

<body>
    <div class="dashboard-container">
        <h1>Kelola Pengguna</h1>
        <a href="add_user.php" class="menu-item">Tambah Pengguna</a>
        <table class="user-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Lengkap</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                while ($user = mysqli_fetch_assoc($result)) :
                ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo htmlspecialchars($user['nama_lengkap']); ?></td>
                        <td><?php echo $user['username']; ?></td>
                        <td><?php echo $user['email']; ?></td>
                        <td><?php echo $user['role']; ?></td>
                        <td class="bt">
                            <a href="edit_user.php?id=<?php echo $user['id']; ?>" class="btn-edit">Edit</a>
                            <a href="proses.php?delete_id=<?php echo $user['id']; ?>"
                                onclick="return confirm('Hapus pengguna ini?');" class="btn-delete">Hapus</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

</body>

</html>

<?php mysqli_close($conn); ?>