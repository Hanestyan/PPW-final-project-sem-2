<?php
// ===================================================
// chgpassword.php - Memproses ganti password
// ===================================================

session_start();

// Hubungkan ke database
require_once __DIR__ . '/../config/database.php';

// Jalankan hanya jika form ganti password dikirim
if (isset($_POST['ganti_password'])) {

    // Pastikan user sudah login
    if (!isset($_SESSION['id_user'])) {
        header("Location: ../../frontend/index.php");
        exit();
    }

    $password_baru    = $_POST['password_baru'];
    $konfirmasi       = $_POST['konfirmasi_password'];
    $id_user          = $_SESSION['id_user'];
    $role             = $_SESSION['role'];

    // Validasi: password baru dan konfirmasi harus sama
    if ($password_baru !== $konfirmasi) {
        // Password tidak cocok, kembali dengan pesan error
        header("Location: ../../frontend/views/mahasiswa/settings_user.php?pesan=tidak_cocok");
        exit();
    }

    // Validasi: password minimal 6 karakter
    if (strlen($password_baru) < 6) {
        header("Location: ../../frontend/views/mahasiswa/settings_user.php?pesan=terlalu_pendek");
        exit();
    }

    // Hash password baru agar aman disimpan di database
    $password_hashed = password_hash($password_baru, PASSWORD_DEFAULT);

    // Update password di database
    // (nama kolom di DB adalah id_user, bukan id)
    $sql  = "UPDATE users SET password = ? WHERE id_user = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$password_hashed, $id_user]);

    // Arahkan ke halaman settings sesuai role dengan pesan sukses
    if ($role === 'satpam' || $role === 'admin') {
        header("Location: ../../frontend/views/satpam/settings_satpam.php?pesan=sukses");
    } else {
        header("Location: ../../frontend/views/mahasiswa/settings_user.php?pesan=sukses");
    }
    exit();
}

// Jika tidak ada data POST, kembali ke halaman login
header("Location: ../../frontend/index.php");
exit();
?>