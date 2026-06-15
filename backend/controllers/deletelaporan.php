<?php
// ===================================================
// deletelaporan.php - Memproses penghapusan laporan
// ===================================================

session_start();
require_once __DIR__ . '/../config/database.php';

// Cek apakah user sudah login sebagai mahasiswa
if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'mahasiswa') {
    header("Location: ../../frontend/index.php");
    exit();
}

if (isset($_POST['id_laporan'])) {
    $id_laporan = $_POST['id_laporan'];
    $id_user    = $_SESSION['id_user'];

    // Validasi: Pastikan laporan milik user ini dan masih berstatus 'Pending'
    // Kolom tabel: id_laporan, id_user, status
    $sql_check  = "SELECT status FROM laporan_kerusakan WHERE id_laporan = ? AND id_user = ?";
    $stmt_check = $pdo->prepare($sql_check);
    $stmt_check->execute([$id_laporan, $id_user]);
    $laporan = $stmt_check->fetch();

    if ($laporan && $laporan['status'] === 'Pending') {
        // Hapus laporan
        $sql_delete  = "DELETE FROM laporan_kerusakan WHERE id_laporan = ?";
        $stmt_delete = $pdo->prepare($sql_delete);
        $stmt_delete->execute([$id_laporan]);

        // Cek darimana request berasal
        $halaman_asal = $_POST['halaman_asal'] ?? 'dashboard';
        
        if ($halaman_asal === 'history') {
            header("Location: ../../frontend/views/mahasiswa/history_user.php?pesan=dihapus");
        } else {
            header("Location: ../../frontend/views/mahasiswa/dashboard_user.php?pesan=dihapus");
        }
        exit();
    }
}

// Kembali ke dashboard jika gagal
header("Location: ../../frontend/views/mahasiswa/dashboard_user.php?error=gagal_hapus");
exit();
?>
