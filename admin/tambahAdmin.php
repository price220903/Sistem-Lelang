<?php
include '../database.php';
session_start();

date_default_timezone_set('Asia/Jakarta'); // Atur zona waktu ke WIB

if (!isset($_SESSION['id'])) {
    header("Location: ../login.php");
    exit;
}

$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $nama = $_POST['nama'];
    $no_telp = $_POST['no_telp'];

    $sql = "INSERT INTO user (nama, email, password, no_telp, role) VALUES ('$nama', '$email', '$password', '$no_telp', 'admin')";

    if ($conn->query($sql) === TRUE) {
        $message = "Tambah Admin berhasil.";
        echo "<script type='text/javascript'>alert('$message'); window.location.href = 'index.php';</script>";
        exit;
    } else {
        $message = "Tambah Admin gagal, coba lagi.";
        echo "<script type='text/javascript'>alert('$message'); window.location.href = 'tambahAdmin.php';</script>";
    }
}
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
            <li><a href='tambahAdmin.php'>Tambah Admin</a></li>
            <li><a href='listUser.php'>List Pengguna</a></li>
            <li><a href="?logout=true">Logout</a></li>
        </ul>
    </nav>
    <div class="wrapper">
        <div class="card">
            <h1>Tambah Admin</h1>
            <form method="post" action="tambahAdmin.php">
                <label for="email">Email</label>
                <input type="email" name="email" required>
                <label for="password">Password</label>
                <input type="password" name="password" required>
                <label for="nama">Name</label>
                <input type="text" name="nama" required>
                <label for="no_telp">Phone Number</label>
                <input type="number" name="no_telp" required>
                <button type="submit" style="margin-bottom: 20px;">Tambah</button>
            </form>
        </div>
    </div>
</body>

</html>