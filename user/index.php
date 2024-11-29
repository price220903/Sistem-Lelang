<?php
include '../database.php';
session_start();

date_default_timezone_set('Asia/Jakarta'); // Atur zona waktu ke WIB

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
</head>

<body>
    <nav id='menu'>
        <ul>
            <li><a href='index.php' class="active">Sistem Lelang</a></li>
            <li><a href='profil.php?id=<?php echo $_SESSION['id']; ?>'>Profil</a></li>
            <li><a href="?logout=true">Logout</a></li>
        </ul>
    </nav>
    <main class="user">
        <div class="main-card">
            <?php while ($item = $result->fetch_assoc()): ?>
                <div class="card">
                    <img src="<?php echo $item['gambar']; ?>" alt="<?php echo $item['gambar']; ?>">
                    <h2><?php echo $item['nama']; ?></h2>
                    <div class="card-button">
                        <a href="detail.php?id=<?php echo $item['id']; ?>">Detail</a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </main>
</body>

</html>