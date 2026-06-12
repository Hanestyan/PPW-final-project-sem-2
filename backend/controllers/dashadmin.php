<?php
// include '../config/database.php';
include __DIR__ . '/../config/database.php';

$total_pending = $pdo->query("SELECT COUNT(*) FROM laporan_kerusakan WHERE status = 'Pending'")->fetchColumn();
$total_proses  = $pdo->query("SELECT COUNT(*) FROM laporan_kerusakan WHERE status = 'Diproses'")->fetchColumn();
$total_selesai = $pdo->query("SELECT COUNT(*) FROM laporan_kerusakan WHERE status = 'Selesai'")->fetchColumn();

$data_laporan = $pdo->query("SELECT * FROM laporan_kerusakan ORDER BY id_laporan DESC")->fetchAll();
?>