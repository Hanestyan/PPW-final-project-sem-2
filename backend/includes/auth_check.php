<?php
// ===================================================
// auth_check.php - Fungsi pengecekan autentikasi
// Di-include oleh halaman yang butuh proteksi login
// ===================================================

/**
 * Fungsi untuk memeriksa apakah user sudah login.
 * Jika belum login, akan diarahkan ke halaman login.
 */
function cek_login() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION['id_user'])) {
        header("Location: /safewalknew/frontend/index.php");
        exit();
    }
}

/**
 * Fungsi untuk memeriksa apakah user memiliki role yang sesuai.
 * Jika role tidak sesuai, akan diarahkan ke halaman login.
 * 
 * @param string $role_dibutuhkan Role yang diizinkan ('mahasiswa', 'satpam', atau 'admin')
 */
function cek_role($role_dibutuhkan) {
    cek_login(); // Pastikan sudah login dulu

    if ($_SESSION['role'] !== $role_dibutuhkan) {
        // Role tidak sesuai, arahkan ke login
        header("Location: /safewalknew/frontend/index.php");
        exit();
    }
}
?>
