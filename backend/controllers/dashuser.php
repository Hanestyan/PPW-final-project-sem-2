<?php
// ===================================================
// dashuser.php - Mengambil data untuk dashboard mahasiswa
// File ini di-include oleh halaman dashboard_user.php
// ===================================================

session_start();

// Hubungkan ke database
require_once __DIR__ . '/../config/database.php';

// Cek apakah user sudah login sebagai mahasiswa
if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'mahasiswa') {
    // Jika belum login atau bukan mahasiswa, redirect ke halaman login
    header("Location: ../../frontend/index.php");
    exit();
}

// Ambil ID mahasiswa dari session
$id_mahasiswa    = $_SESSION['id_user'];
$riwayat_terbaru = []; // Default kosong jika tidak ada laporan

try {
    // Ambil 4 laporan terbaru milik mahasiswa ini
    // Kolom: id_laporan, id_user (sesuai gambar tabel)
    $stmt = $pdo->prepare(
        "SELECT * FROM laporan_kerusakan WHERE id_user = ? ORDER BY id_laporan DESC LIMIT 4"
    );
    $stmt->execute([$id_mahasiswa]);
    $riwayat_terbaru = $stmt->fetchAll();

} catch (PDOException $e) {
    die("Gagal memuat data dashboard: " . $e->getMessage());
}
?>