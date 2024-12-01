<?php
include '../database.php';
session_start();

date_default_timezone_set('Asia/Jakarta'); // Atur zona waktu ke WIB

$message = ""; // Variabel untuk menyimpan pesan
$timeout = 300; // waktu dalam detik (5 menit)

if (!isset($_SESSION['id'])) {
    header("Location: ../login.php");
    exit;
}

// Logika timeout
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout) {
    session_unset(); // Hapus semua variabel sesi 
    session_destroy(); // Hancurkan sesi 
    $message = "Sesi telah berakhir karena tidak ada aktivitas selama 5 menit.";
    echo "<script type='text/javascript'>alert('$message'); window.location.href = '../login.php';</script>";
    exit;
}
$_SESSION['last_activity'] = time(); // Perbarui waktu aktivitas terakhir

// Logika logout
if (isset($_GET['logout'])) {
    session_unset(); // Hapus semua variabel sesi
    session_destroy(); // Hancurkan sesi
    $message = "Log out berhasil.";
    echo "<script type='text/javascript'>alert('$message'); window.location.href = '../login.php';</script>";
    exit;
}

// Ambil data barang
$sql = "SELECT * FROM item";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Lelang</title>
    <link rel="stylesheet" href="../asset/style.css">
    <link rel="icon" href="../asset/logo.png" type="image/x-icon">
    <script>
        var timeout = 300000; // waktu dalam milidetik (5 menit) 
        var logoutTimer;

        function resetTimer() {
            clearTimeout(logoutTimer);
            logoutTimer = setTimeout(logout, timeout);
        }

        function logout() {
            window.location.href = '?logout=true'; // arahkan ke halaman logout 
        }
        document.onload = resetTimer;
        document.onmousemove = resetTimer;
        document.onkeypress = resetTimer;
    </script>
</head>

<body>
    <nav id='menu'>
        <ul>
            <li><a href='index.php' class="active">Sistem Lelang</a></li>
            <li><a href='profil.php?id=<?php echo $_SESSION['id']; ?>'>Profil</a></li>
            <li><a href='tambahAdmin.php'>Tambah Admin</a></li>
            <li><a href='listUser.php'>List Pengguna</a></li>
            <li><a href="?logout=true">Logout</a></li>
        </ul>
    </nav>
    <main>
        <a href="tambah.php">Tambah Barang</a>
        <div class="main-card">
            <?php while ($item = $result->fetch_assoc()): ?>
                <div class="card">
                    <img src="<?php echo $item['gambar']; ?>" alt="<?php echo $item['gambar']; ?>">
                    <h2><?php echo $item['nama']; ?></h2>
                    <div class="card-button">
                        <a href="detail.php?id=<?php echo $item['id']; ?>">Detail</a>
                        <a href="bidding.php?id=<?php echo $item['id']; ?>">Bidding</a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </main>
</body>

</html>