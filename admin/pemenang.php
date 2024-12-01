<?php
include '../database.php';
session_start();

date_default_timezone_set('Asia/Jakarta'); // Atur zona waktu ke WIB

$message = ""; // Variabel untuk menyimpan pesan
$timeout = 300; // waktu dalam detik (5 menit)

$id_item = $_GET['id'];

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

// Ambil data penawaran tertinggi setelah batas waktu berakhir
$sql = "SELECT bid.*, user.nama, user.email, user.no_telp 
        FROM bid 
        JOIN user ON bid.user_id = user.id 
        WHERE bid.item_id = $id_item 
        ORDER BY bid.harga_tawaran DESC 
        LIMIT 1";
$result = $conn->query($sql);
$pemenang = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Lelang</title>
    <link rel="stylesheet" href="../asset/style.css">
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
    <div class="wrapper">
        <div class="card">
            <h1>Profil Pemenang</h1>
            <form method="post" action="tambahAdmin.php">
                <label for="nama">Name</label>
                <input type="text" value="<?php echo $pemenang['nama']; ?>" readonly>
                <label for="email">Email</label>
                <input type="email" value="<?php echo $pemenang['email']; ?>" readonly>
                <label for="no_telp">Phone Number</label>
                <input type="number" value="<?php echo $pemenang['no_telp']; ?>" readonly>
                <a href="bidding.php?id=<?php echo $id_item; ?>">Kembali</a>
            </form>
        </div>
    </div>
</body>

</html>