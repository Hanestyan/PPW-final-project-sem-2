<?php
// ===================================================
// dashadmin.php - Mengambil data untuk dashboard satpam/admin
// File ini di-include oleh halaman dashboard satpam
// ===================================================

// Hubungkan ke database
require_once __DIR__ . '/../config/database.php';

// Hitung jumlah laporan berdasarkan status
$total_pending = $pdo->query("SELECT COUNT(*) FROM laporan_kerusakan WHERE status = 'Pending'")->fetchColumn();
$total_proses  = $pdo->query("SELECT COUNT(*) FROM laporan_kerusakan WHERE status = 'Diproses'")->fetchColumn();
$total_selesai = $pdo->query("SELECT COUNT(*) FROM laporan_kerusakan WHERE status = 'Selesai'")->fetchColumn();

// Ambil semua data laporan, urut dari yang terbaru
$data_laporan = $pdo->query("SELECT * FROM laporan_kerusakan ORDER BY id_laporan DESC")->fetchAll();
?>