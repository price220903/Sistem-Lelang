<?php
include '../database.php';
session_start();

date_default_timezone_set('Asia/Jakarta'); // Atur zona waktu ke WIB

if (!isset($_SESSION['id'])) {
    header("Location: ../login.php");
    exit;
}

$message = "";
$id_user = $_SESSION['id'];

// Ambil data pengguna
$sql = "SELECT * FROM user WHERE id = $id_user";
$result = $conn->query($sql);
$user = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : $user['password'];
    $nama = $_POST['nama'];
    $no_telp = $_POST['no_telp'];

    $sql_update = "UPDATE user SET nama = '$nama', email = '$email', password = '$password', no_telp = '$no_telp' WHERE id = $id_user";

    if ($conn->query($sql_update) === TRUE) {
        $message = "Profil berhasil diperbarui.";
        echo "<script type='text/javascript'>alert('$message'); window.location.href = 'profil.php?id=$id_user';</script>";
        exit;
    } else {
        $message = "Update profil gagal, coba lagi.";
        echo "<script type='text/javascript'>alert('$message'); window.location.href = 'editProfil.php';</script>";
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
            <li><a href="?logout=true">Logout</a></li>
        </ul>
    </nav>
    <div class="wrapper">
        <div class="card">
            <h1>Edit Profil</h1>
            <form method="post" action="editProfil.php">
                <label for="email">Email</label>
                <input type="email" name="email" value="<?php echo $user['email']; ?>" required>
                <label for="password">Password (kosongkan jika tidak ingin mengubah)</label>
                <input type="password" name="password">
                <label for="nama">Name</label>
                <input type="text" name="nama" value="<?php echo $user['nama']; ?>" required>
                <label for="no_telp">Phone Number</label>
                <input type="number" name="no_telp" value="<?php echo $user['no_telp']; ?>" required>
                <button type="submit" style="margin-bottom: 20px;">Update</button>
            </form>
        </div>
    </div>
</body>

</html>