<?php
include '../database.php';
session_start();

date_default_timezone_set('Asia/Jakarta'); // Atur zona waktu ke WIB

$id_item = $_GET['id'];

$message = ""; // Variabel untuk menyimpan pesan

if (!isset($_SESSION['id'])) {
    header("Location: ../login.php");
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