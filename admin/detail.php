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

// Logika untuk menghapus item
if (isset($_GET['delete'])) {
    $id_item = $_GET['delete'];

    // Ambil data barang untuk mendapatkan nama file gambar
    $sql = "SELECT gambar FROM item WHERE id='$id_item'";
    $result = $conn->query($sql);
    $item = $result->fetch_assoc();
    $gambar = $item['gambar'];

    // Hapus data dari database
    $sql = "DELETE FROM item WHERE id='$id_item'";
    if ($conn->query($sql) === TRUE) {
        // Hapus file gambar dari folder uploads
        if (file_exists("../uploads/" . $gambar)) {
            unlink("../uploads/" . $gambar);
        }
        $message = "Barang berhasil dihapus!";
        echo "<script type='text/javascript'>alert('$message'); window.location.href = 'index.php';</script>";
        exit;
    } else {
        $message = "Error: " . $sql . "<br>" . $conn->error;
    }
    echo "<script type='text/javascript'>alert('$message');</script>";
}

// Ambil data barang
$sql = "SELECT * FROM item WHERE id = $id_item";
$result = $conn->query($sql);
$item = $result->fetch_assoc();
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
                <label for="nama">Item Name</label>
                <input type="text" name="nama" placeholder="<?php echo $item['nama'] ?>" readonly>
                <label for="deskripsi">Item Description</label>
                <textarea name="deskripsi" readonly><?php echo $item['deskripsi'] ?></textarea>
                <label for="harga_minimal">Minimal Bid</label>
                <input type="number" name="harga_minimal" placeholder="Rp <?php echo number_format($item['harga_minimal'], 2, ',', '.'); ?>" readonly>
                <label for="batas_waktu">Bid Deadline</label>
                <input type="datetime-local" name="batas_waktu" value="<?php echo $item['batas_waktu'] ?>" readonly>
                <div class="button">
                    <a href="edit.php?id=<?php echo $item['id']; ?>">Edit</a>
                    <a href="detail.php?delete=<?php echo $item['id']; ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus item ini?');">Hapus</a>
                </div>
            </form>
            <a href="index.php">Kembali</a>
        </div>
    </div>
</body>

</html>