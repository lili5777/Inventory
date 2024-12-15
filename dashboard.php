<?php
// Mulai sesi
session_start();

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// Ambil informasi role dari sesi
$role = $_SESSION['role'];
$fullname = $_SESSION['fullname'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="styles.css?v=1">
</head>

<body>
    <div class="dashboard-container">
        <h1>Selamat datang, <?php echo $fullname; ?>!</h1>
        <p>Anda login sebagai: <strong><?php echo $role; ?></strong></p>

        <div class="menu">
            <?php if ($role === 'admin') : ?>
                <a href="manage_users.php" class="menu-item">Kelola Pengguna</a>
                <a href="manage_inventory.php" class="menu-item">Kelola Inventaris</a>
                <a href="manage_rak.php" class="menu-item">Kelola Rak</a>
            <?php elseif ($role === 'staff') : ?>
                <a href="view_inventory.php" class="menu-item">Lihat Inventaris</a>
                <a href="update_inventory.php" class="menu-item">Perbarui Inventaris</a>
            <?php endif; ?>
            <a href="logout.php" class="menu-item logout">Logout</a>
        </div>
    </div>
</body>

</html>