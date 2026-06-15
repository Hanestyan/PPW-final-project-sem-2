<?php
// ===================================================
// updatestatus.php - Memperbarui status laporan
// Diakses oleh satpam melalui form di dashboard
// ===================================================

session_start();

// Hubungkan ke database
require_once __DIR__ . '/../config/database.php';

// Jalankan hanya jika ada data POST yang dikirim
if (isset($_POST['id_laporan']) && isset($_POST['status_baru'])) {

    $id_laporan  = $_POST['id_laporan'];  // ID laporan yang akan diupdate
    $status_baru = $_POST['status_baru']; // Status baru: 'Diproses' atau 'Selesai'

    // Validasi status yang diizinkan (keamanan)
    $status_valid = ['Diproses', 'Selesai'];
    if (!in_array($status_baru, $status_valid)) {
        // Jika status tidak valid, kembali ke dashboard
        header("Location: ../../frontend/views/satpam/dashboard_satpam.php");
        exit();
    }

    // Update kolom 'status' di tabel laporan_kerusakan
    // (nama kolom di DB adalah 'status', dan primary key adalah 'id_laporan')
    $sql  = "UPDATE laporan_kerusakan SET status = ? WHERE id_laporan = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$status_baru, $id_laporan]);

    // Tentukan halaman tujuan redirect setelah update
    $halaman_asal = $_POST['halaman_asal'] ?? 'dashboard';
    if ($halaman_asal === 'active_alerts') {
        header("Location: ../../frontend/views/satpam/active_alerts.php");
    } else {
        header("Location: ../../frontend/views/satpam/dashboard_satpam.php");
    }
    exit();
}

// Jika tidak ada data POST, kembali ke dashboard
header("Location: ../../frontend/views/satpam/dashboard_satpam.php");
exit();
?>