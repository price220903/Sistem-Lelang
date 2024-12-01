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

// Tambah barang
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add'])) {
    $nama = $_POST['nama'];
    $deskripsi = $_POST['deskripsi'];
    $harga_minimal = $_POST['harga_minimal'];
    $batas_waktu = $_POST['batas_waktu'];

    // Proses upload gambar
    $target_dir = "../uploads/";
    $target_file = $target_dir . basename($_FILES["gambar"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Periksa apakah file adalah gambar asli atau bukan
    $check = getimagesize($_FILES["gambar"]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        $message = "File bukan gambar.";
        $uploadOk = 0;
    }

    // Periksa apakah file sudah ada
    if (file_exists($target_file)) {
        $message = "Maaf, file sudah ada.";
        $uploadOk = 0;
    }

    // Batasi ukuran file (misalnya, maksimal 500KB)
    if ($_FILES["gambar"]["size"] > 500000) {
        $message = "Maaf, ukuran file terlalu besar.";
        $uploadOk = 0;
    }

    // Batasi format file yang diizinkan
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        $message = "Maaf, hanya file JPG, JPEG, PNG & GIF yang diizinkan.";
        $uploadOk = 0;
    }

    // Periksa apakah $uploadOk bernilai 0 karena kesalahan
    if ($uploadOk == 0) {
        $message = "Maaf, file Anda tidak terupload.";
        // Jika semua pengecekan lolos, coba upload file
    } else {
        if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file)) {
            // Simpan informasi barang ke database
            $sql = "INSERT INTO item (nama, deskripsi, gambar, harga_minimal, batas_waktu) VALUES ('$nama', '$deskripsi', '$target_file', '$harga_minimal', '$batas_waktu')";
            if ($conn->query($sql) === TRUE) {
                $message = "Barang berhasil ditambahkan!";
                echo "<script type='text/javascript'>alert('$message'); window.location.href = 'index.php';</script>";
                exit;
            } else {
                $message = "Error: " . $sql . "<br>" . $conn->error;
            }
        } else {
            $message = "Maaf, terjadi kesalahan saat mengupload file Anda.";
        }
    }
    echo "<script type='text/javascript'>alert('$message');</script>";
}
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
    <div class="content-wrapper">
        <div class="content-card">
            <h1>Tambah Item</h1>
            <form method="post" enctype='multipart/form-data' action="tambah.php">
                <label for="nama">Item Name</label>
                <input type="text" name="nama" required>
                <label for="deskripsi">Item Description</label>
                <textarea name="deskripsi" required></textarea>
                <label for="harga_minimal">Minimal Bid</label>
                <input type="number" name="harga_minimal" required>
                <label for="batas_waktu">Bid Deadline</label>
                <input type="datetime-local" name="batas_waktu" required>
                <label for="gambar">Image</label>
                <input type="file" name="gambar" required>
                <button type="submit" name="add">Add</button>
            </form>
            <a href="index.php">Kembali</a>
        </div>
    </div>
</body>

</html>