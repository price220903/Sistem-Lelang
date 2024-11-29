<?php
include 'database.php';
session_start();

$message = ""; // Variabel untuk menyimpan pesan

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM user WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['id'] = $user['id']; // Store user ID in session
            $_SESSION['role'] = $user['role']; // Store user role in session

            if ($user['role'] == 'user') {
                $message = "Login berhasil.";
                echo "<script type='text/javascript'>alert('$message'); window.location.href = 'user/index.php';</script>";
                exit;
            } elseif ($user['role'] == 'admin') {
                $message = "Login berhasil.";
                echo "<script type='text/javascript'>alert('$message'); window.location.href = 'admin/index.php';</script>";
                exit;
            }
        } else {
            $message = "Password invalid.";
            echo "<script type='text/javascript'>alert('$message'); window.location.href = 'login.php';</script>";
        }
    } else {
        $message = "User dengan email tersebut tidak ditemukan.";
        echo "<script type='text/javascript'>alert('$message'); window.location.href = 'login.php';</script>";
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
</head>

<body>
    <div class="wrapper">
        <div class="card">
            <h1>Login</h1>
            <form method="post" action="login.php">
                <label for="email">Your Email</label>
                <input type="email" name="email" required>
                <label for="password">Your Password</label>
                <input type="password" name="password" required>
                <button type="submit">Login</button>
            </form>
            <p>Belum punya akun? <a href="signin.php">Sign in</a></p>
        </div>
    </div>
</body>

</html>