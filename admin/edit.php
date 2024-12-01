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

// Ambil data barang
$sql = "SELECT * FROM item WHERE id = $id_item";
$result = $conn->query($sql);
$item = $result->fetch_assoc();

// Update barang
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $nama = $_POST['nama'];
    $deskripsi = $_POST['deskripsi'];
    $harga_minimal = $_POST['harga_minimal'];
    $batas_waktu = $_POST['batas_waktu'];
    $gambar_lama = $item['gambar'];

    // Proses upload gambar baru
    if (!empty($_FILES["gambar"]["tmp_name"])) {
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
        } else {
            if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file)) {
                // Hapus gambar lama
                if (file_exists($gambar_lama)) {
                    unlink($gambar_lama);
                }
                // Update informasi barang di database
                $sql = "UPDATE item SET nama='$nama', deskripsi='$deskripsi', gambar='$target_file', harga_minimal='$harga_minimal', batas_waktu='$batas_waktu' WHERE id='$id_item'";
                if ($conn->query($sql) === TRUE) {
                    $message = "Barang berhasil diupdate!";
                    echo "<script type='text/javascript'>alert('$message'); window.location.href = 'index.php';</script>";
                    exit;
                } else {
                    $message = "Error: " . $sql . "<br>" . $conn->error;
                }
            } else {
                $message = "Maaf, terjadi kesalahan saat mengupload file Anda.";
            }
        }
    } else {
        // Update informasi barang di database tanpa mengubah gambar
        $sql = "UPDATE item SET nama='$nama', deskripsi='$deskripsi', harga_minimal='$harga_minimal', batas_waktu='$batas_waktu' WHERE id='$id_item'";
        if ($conn->query($sql) === TRUE) {
            $message = "Barang berhasil diupdate!";
            echo "<script type='text/javascript'>alert('$message'); window.location.href = 'index.php';</script>";
            exit;
        } else {
            $message = "Error: " . $sql . "<br>" . $conn->error;
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
            <h1>Edit Item</h1>
            <form method="post" enctype='multipart/form-data' action="edit.php?id=<?php echo $id_item; ?>">
                <label for="nama">Item Name</label>
                <input type="text" name="nama" value="<?php echo $item['nama']; ?>" required>
                <label for="deskripsi">Item Description</label>
                <textarea name="deskripsi" required><?php echo $item['deskripsi']; ?></textarea>
                <label for="harga_minimal">Minimal Bid</label>
                <input type="number" name="harga_minimal" value="<?php echo $item['harga_minimal']; ?>" required>
                <label for="batas_waktu">Bid Deadline</label>
                <input type="datetime-local" name="batas_waktu" value="<?php echo $item['batas_waktu']; ?>" required>
                <label for="gambar">Image</label>
                <input type="file" name="gambar">
                <button type="submit" name="update">Update</button>
            </form>
            <a href="detail.php?id=<?php echo $item['id']; ?>">Kembali</a>
        </div>
    </div>
</body>

</html>