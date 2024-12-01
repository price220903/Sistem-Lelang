<?php
include '../database.php';
session_start();

date_default_timezone_set('Asia/Jakarta'); // Atur zona waktu ke WIB

$id_item = $_GET['id'];

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

// Ambil data user dan penawaran
$sql = "SELECT user.nama, bid.harga_tawaran, bid.waktu_tawaran, user.email, user.no_telp 
        FROM bid 
        JOIN user ON bid.user_id = user.id 
        WHERE bid.item_id = $id_item 
        ORDER BY bid.harga_tawaran DESC";
$result = $conn->query($sql);
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
    <main>
        <a href="bidding.php?id=<?php echo $id_item; ?>">Kembali</a>
        <div class="main-table">
            <table class="table">
                <tr>
                    <th>No</th>
                    <th>Nama Penawar</th>
                    <th>Harga Tawaran</th>
                    <th>Waktu Tawaran</th>
                    <th>Email</th>
                    <th>No. Telp</th>
                </tr>
                <?php
                $no = 1;
                while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo $row['nama']; ?></td>
                        <td>Rp <?php echo number_format($row['harga_tawaran'], 2, ',', '.'); ?></td>
                        <td><?php echo $row['waktu_tawaran']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td><?php echo $row['no_telp']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        </div>
    </main>
</body>

</html>