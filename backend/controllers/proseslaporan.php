<?php
session_start();
require_once '../config/database.php';

if (isset($_POST['submit'])) {
    $lokasi = $_POST['lokasi'];
    $deskripsi = $_POST['deskripsi'];
    $id_user = $_SESSION['id_user'];
    
    $foto = $_FILES['foto']['name'];
    $target_dir = "../uploads/" . basename($foto);
    move_uploaded_file($_FILES['foto']['tmp_name'], $target_dir);

    $sql = "INSERT INTO laporan_kerusakan (id_user, lokasi, deskripsi, foto, status) VALUES (?, ?, ?, ?, 'Pending')";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_user, $lokasi, $deskripsi, $foto]);

    header("Location: ../dashboard_user.php");
}
?>