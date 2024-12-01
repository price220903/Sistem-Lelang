<?php
include 'database.php';
session_start();

$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $nama = $_POST['nama'];
    $no_telp = $_POST['no_telp'];

    $sql = "INSERT INTO user (nama, email, password, no_telp, role) VALUES ('$nama', '$email', '$password', '$no_telp', 'user')";

    if ($conn->query($sql) === TRUE) {
        $message = "Daftar berhasil.";
        echo "<script type='text/javascript'>alert('$message'); window.location.href = 'login.php';</script>";
        exit;
    } else {
        $message = "Daftar gagal, coba lagi.";
        echo "<script type='text/javascript'>alert('$message'); window.location.href = 'register.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Lelang</title>
    <link rel="stylesheet" href="asset/style.css">
    <link rel="icon" href="asset/logo.png" type="image/x-icon">
</head>

<body>
    <div class="wrapper">
        <div class="card">
            <h1>Registrasi</h1>
            <form method="post" action="signin.php">
                <label for="email">Your Email</label>
                <input type="email" name="email" required>
                <label for="password">Your Password</label>
                <input type="password" name="password" required>
                <label for="nama">Your Name</label>
                <input type="text" name="nama" required>
                <label for="no_telp">Your Phone Number</label>
                <input type="number" name="no_telp" required>
                <button type="submit">Sign in</button>
            </form>
            <p>Sudah punya akun? <a href="login.php">Kembali</a></p>
        </div>
    </div>
</body>

</html>