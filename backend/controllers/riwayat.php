<?php
// ===================================================
// riwayat.php - Mengambil riwayat laporan milik mahasiswa
// File ini di-include oleh halaman history_user.php
// ===================================================

// Mulai session jika belum aktif
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Hubungkan ke database
require_once __DIR__ . '/../config/database.php';

// Cek apakah user sudah login
if (!isset($_SESSION['id_user'])) {
    header("Location: ../../frontend/index.php");
    exit();
}

// Ambil ID user dari session
$id_user = $_SESSION['id_user'];

// Cek apakah ada filter status dari URL (contoh: ?status=Selesai)
if (isset($_GET['status']) && !empty($_GET['status'])) {
    // Jika ada filter, ambil laporan dengan status tertentu saja
    // Kolom: status (sesuai gambar tabel)
    $status_filter = $_GET['status'];
    $sql  = "SELECT * FROM laporan_kerusakan 
             WHERE id_user = ? AND status = ? 
             ORDER BY id_laporan DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_user, $status_filter]);
} else {
    // Jika tidak ada filter, ambil semua laporan milik user ini
    $sql  = "SELECT * FROM laporan_kerusakan 
             WHERE id_user = ? 
             ORDER BY id_laporan DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_user]);
}

// Simpan hasil query ke variabel yang digunakan di history_user.php
$riwayat_user = $stmt->fetchAll();
?>