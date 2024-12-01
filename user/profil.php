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

$id_user = $_SESSION['id'];

// Logika logout
if (isset($_GET['logout'])) {
    session_unset(); // Hapus semua variabel sesi
    session_destroy(); // Hancurkan sesi
    $message = "Log out berhasil.";
    echo "<script type='text/javascript'>alert('$message'); window.location.href = '../login.php';</script>";
    exit;
}

// Ambil data pengguna
$sql = "SELECT * FROM user WHERE id = $id_user";
$result = $conn->query($sql);
$user = $result->fetch_assoc();
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
            <li><a href="?logout=true">Logout</a></li>
        </ul>
    </nav>
    <div class="wrapper">
        <div class="card">
            <h1>Profil</h1>
            <form method="post" action="tambahAdmin.php">
                <label for="nama">Name</label>
                <input type="text" value="<?php echo $user['nama']; ?>" readonly>
                <label for="email">Email</label>
                <input type="email" value="<?php echo $user['email']; ?>" readonly>
                <label for="no_telp">Phone Number</label>
                <input type="number" value="<?php echo $user['no_telp']; ?>" readonly>
                <a href="editProfil.php?id=<?php echo $_SESSION['id']; ?>">Edit Profil</a>
            </form>
        </div>
    </div>
</body>

</html>