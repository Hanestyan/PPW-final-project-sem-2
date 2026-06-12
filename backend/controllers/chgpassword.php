<?php
session_start();
include 'database.php';

if (isset($_POST['ganti_password'])) {
    $password_baru = $_POST['password_baru'];
    $id_user = $_SESSION['id_user'];

    $password_hashed = password_hash($password_baru, PASSWORD_DEFAULT);

    $sql = "UPDATE users SET password = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$password_hashed, $id_user]);

    header("Location: ../settings.php?pesan=sukses");
}
?>