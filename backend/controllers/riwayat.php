<?php
// 2. Panggil koneksi database
if (session_start() == PHP_SESSION_NONE){
    session_start();
}
require_once __DIR__ . '/../config/database.php';

// 3. Ambil ID user dari session yang sudah aktif
$id_user = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : 0;

if (isset($_GET['status'])) {
    $status_filter = $_GET['status'];
    $sql = "SELECT * FROM laporan_kerusakan WHERE id_user = ? AND status_penanganan = ? ORDER BY id_laporan DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_user, $status_filter]);
} else {
    $sql = "SELECT * FROM laporan_kerusakan WHERE id_user = ? ORDER BY id_laporan DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_user]);
}

// Simpan ke variabel yang dicari oleh history_user.php
$riwayat_user = $stmt->fetchAll();
?>