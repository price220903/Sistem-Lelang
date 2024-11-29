<?php
include '../database.php';
session_start();

date_default_timezone_set('Asia/Jakarta'); // Atur zona waktu ke WIB

$message = ""; // Variabel untuk menyimpan pesan

if (!isset($_SESSION['id'])) {
    header("Location: ../login.php");
    exit;
}

// Logika logout
if (isset($_GET['logout'])) {
    session_unset(); // Hapus semua variabel sesi
    session_destroy(); // Hancurkan sesi
    $message = "Log out berhasil.";
    echo "<script type='text/javascript'>alert('$message'); window.location.href = '../login.php';</script>";
    exit;
}

// Ambil data barang
$sql = "SELECT * FROM user ORDER BY role, nama";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Lelang</title>
    <link rel="stylesheet" href="../asset/style.css">
</head>

<body>
    <nav id='menu'>
        <ul>
            <li><a href='index.php' class="active">Sistem Lelang</a></li>
            <li><a href='#'>Profil</a></li>
            <li><a href='tambahAdmin.php'>Tambah Admin</a></li>
            <li><a href='listUser.php'>List Pengguna</a></li>
            <li><a href="?logout=true">Logout</a></li>
        </ul>
    </nav>
    <main>
        <a href="index.php">Kembali</a>
        <div class="main-table">
            <table class="table">
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>No. Telp</th>
                    <th>Role</th>
                </tr>
                <?php
                $no = 1;
                while ($user = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo $user['nama']; ?></td>
                        <td><?php echo $user['email']; ?></td>
                        <td><?php echo $user['no_telp']; ?></td>
                        <td><?php echo $user['role']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        </div>
    </main>
</body>

</html>