<?php
session_start();
include '../../../backend/config/database.php';

if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'mahasiswa') {
    header("Location: index.php");
    exit();
}

$id_mahasiswa = $_SESSION['id_user'];
$riwayat_terbaru = [];

try {
    $stmt = $db->prepare("SELECT * FROM laporan_kerusakan WHERE id_user = ? ORDER BY id DESC LIMIT 4");
    $stmt->execute([$id_mahasiswa]);
    $riwayat_terbaru = $stmt->fetchAll();
}   catch (PDOException $e) {
    die("Gagal memuat data dashboard: " . $e->getMessage());
}
?>