<?php
include '../database.php';
session_start();

date_default_timezone_set('Asia/Jakarta'); // Atur zona waktu ke WIB

$message = ""; // Variabel untuk menyimpan pesan

$id_item = $_GET['id'];

if (!isset($_SESSION['id'])) {
    header("Location: ../login.php");
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