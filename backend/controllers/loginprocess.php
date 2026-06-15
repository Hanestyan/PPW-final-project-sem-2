<?php
// ===================================================
// loginprocess.php - Memproses form login
// ===================================================

session_start();

// Hubungkan ke database
require_once __DIR__ . '/../config/database.php';

// Jalankan hanya jika tombol login ditekan (POST request)
if (isset($_POST['login'])) {

    // Ambil data dari form login
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Cari user di database berdasarkan username
    $sql  = "SELECT * FROM users WHERE username = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    // Cek apakah user ditemukan dan password cocok
    if ($user && password_verify($password, $user['password'])) {

        // Simpan data user ke session
        $_SESSION['id_user']  = $user['id_user']; // nama kolom di DB adalah id_user
        $_SESSION['username'] = $user['username'];
        $_SESSION['role']     = $user['role'];

        // Jika user centang "Remember Me", simpan username di cookie
        if (isset($_POST['remember_me'])) {
            setcookie('remember_username', $username, time() + (86400 * 30), "/");
        } else {
            setcookie('remember_username', '', time() - 3600, "/");
        }

        // Arahkan ke halaman sesuai role
        if ($user['role'] === 'satpam' || $user['role'] === 'admin') {
            // Satpam dan admin masuk ke dashboard satpam
            header("Location: ../../frontend/views/satpam/dashboard_satpam.php");
        } else {
            // Mahasiswa masuk ke dashboard user
            header("Location: ../../frontend/views/mahasiswa/dashboard_user.php");
        }
        exit();

    } else {
        // Username atau password salah, kembali ke halaman login dengan pesan error
        header("Location: ../../frontend/index.php?error=salah_password");
        exit();
    }
}
?>