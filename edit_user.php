<?php
session_start();

// Periksa apakah pengguna sudah login dan memiliki hak akses admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

include 'koneksi.php';

// Jika ID pengguna yang akan diedit ada di URL
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // Ambil data pengguna dari database berdasarkan ID
    $query = "SELECT * FROM User WHERE id = '$user_id'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
    } else {
        echo '<script>alert("Pengguna tidak ditemukan."); window.location="manage_users.php";</script>';
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $user_id = $_POST['user_id']; // Ambil ID pengguna yang benar
    $nama_lengkap = mysqli_real_escape_string($conn, $_POST['nama_lengkap']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : ''; // Kosongkan jika tidak ada perubahan password
    $role = mysqli_real_escape_string($conn, $_POST['role']);

    // Query untuk memperbarui data pengguna
    $query = "UPDATE User SET
                nama_lengkap = '$nama_lengkap',
                username = '$username',
                email = '$email',
                role = '$role'";

    // Jika ada password baru, tambahkan ke query
    if (!empty($password)) {
        $query .= ", password = '$password'";
    }

    $query .= " WHERE id = '$user_id'";

    // Eksekusi query
    if (mysqli_query($conn, $query)) {
        echo '<script>alert("Pengguna berhasil diperbarui."); window.location="manage_users.php";</script>';
    } else {
        echo '<script>alert("Gagal memperbarui pengguna: ' . mysqli_error($conn) . '");</script>';
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pengguna</title>
    <link rel="stylesheet" href="styles.css?v=1">
</head>

<body>
    <div class="dashboard-container">
        <h1>Edit Pengguna</h1>
        <form action="edit_user.php?id=<?php echo $user['id']; ?>" method="POST" class="form-container">

            <!-- Input hidden untuk mengirim ID pengguna -->
            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">

            <div class="form-group">
                <label for="nama_lengkap">Nama Lengkap</label>
                <input type="text" name="nama_lengkap" id="nama_lengkap" value="<?php echo htmlspecialchars($user['nama_lengkap']); ?>" required>
            </div>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>
            <div class="form-group">
                <label for="password">Password (kosongkan jika tidak diubah)</label>
                <input type="password" name="password" id="password">
            </div>
            <div class="form-group">
                <label for="role">Role</label>
                <select name="role" id="role" required>
                    <option value="admin" <?php echo $user['role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
                    <option value="staff" <?php echo $user['role'] === 'staff' ? 'selected' : ''; ?>>Staff</option>
                </select>
            </div>
            <button type="submit" class="btn-submit">Perbarui Pengguna</button>
        </form><br>
        <a href="manage_users.php" class="menu-item">Kembali</a>
    </div>
</body>

</html>