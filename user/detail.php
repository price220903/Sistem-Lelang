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

// Ambil data barang
$sql = "SELECT * FROM item WHERE id = $id_item";
$result = $conn->query($sql);
$item = $result->fetch_assoc();

// Ambil data penawaran tertinggi dan nama pengguna
$sql2 = "SELECT bid.*, user.nama AS nama_user FROM bid JOIN user ON bid.user_id = user.id WHERE bid.item_id = $id_item ORDER BY bid.harga_tawaran DESC";
$result2 = $conn->query($sql2);
$bid = $result2->fetch_assoc(); // Mengambil data dari $result2
$bid_terbesar = isset($bid['harga_tawaran']) ? $bid['harga_tawaran'] : 0; // Periksa apakah $bid['harga_tawaran'] null
$nama_penawar_terbesar = isset($bid['nama_user']) ? $bid['nama_user'] : 'Belum Ada Penawar';

// Ambil data penawaran pengguna saat ini
$sql3 = "SELECT * FROM bid WHERE item_id = $id_item AND user_id = {$_SESSION['id']} ORDER BY harga_tawaran DESC";
$result3 = $conn->query($sql3);
$bid_user = $result3->fetch_assoc();
$bid_user_terbesar = isset($bid_user['harga_tawaran']) ? $bid_user['harga_tawaran'] : 0; // Periksa apakah $bid_user['harga_tawaran'] null

// Tentukan status_bid
$current_time = date("Y-m-d H:i:s");
$batas_waktu = $item['batas_waktu'];
$status_bid = (strtotime($current_time) < strtotime($batas_waktu)) ? 'Masih Berlaku' : 'Tidak Berlaku';
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
    <div class="content-wrapper">
        <div class="content-card">
            <h1>Detail Item</h1>
            <img src="<?php echo $item['gambar']; ?>" alt="<?php echo $item['gambar']; ?>">
            <form method="post" enctype='multipart/form-data' action="tambah.php">
                <label for="nama">Nama Item</label>
                <input type="text" name="nama" placeholder="<?php echo $item['nama'] ?>" readonly>
                <label for="deskripsi">Deskripsi Item</label>
                <textarea name="deskripsi" readonly><?php echo $item['deskripsi'] ?></textarea>
                <label for="harga_minimal">Minimal Bid</label>
                <input type="number" name="harga_minimal" placeholder="Rp <?php echo number_format($item['harga_minimal'], 2, ',', '.'); ?>" readonly>
                <label for="penawar_terbesar">Penawar Terbesar (Refresh setiap saat untuk melihat perkembangan terbaru!)</label>
                <input type="text" name="penawar_terbesar" placeholder="<?php echo $nama_penawar_terbesar ?>" readonly>
                <label for="bid_terbesar">Bid Terbesar Saat Ini (Refresh setiap saat untuk melihat perkembangan terbaru!)</label>
                <input type="number" placeholder="Rp <?php echo number_format($bid_terbesar, 2, ',', '.'); ?>" readonly>
                <label for="bid_user_terbesar">Bid Terakhir Kamu</label>
                <input type="number" placeholder="Rp <?php echo number_format($bid_user_terbesar, 2, ',', '.'); ?>" readonly>
                <label for="batas_waktu">Bid Deadline</label>
                <input type="datetime-local" name="batas_waktu" value="<?php echo $item['batas_waktu'] ?>" readonly>
                <label for="status_bid">Status Bid</label>
                <input type="text" value="<?php echo $status_bid ?>" readonly>
                <?php if ($status_bid == 'Masih Berlaku'): ?>
                    <a href="tawar.php?id=<?php echo $item['id']; ?>">Mulai Menawar</a>
                <?php endif; ?>
            </form>
            <a href="index.php" class="user">Kembali</a>
        </div>
    </div>
</body>

</html>