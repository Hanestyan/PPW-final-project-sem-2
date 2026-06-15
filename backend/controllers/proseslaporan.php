<?php
// ===================================================
// proseslaporan.php - Memproses pengiriman laporan kerusakan
// Diakses saat mahasiswa submit form laporan
// ===================================================

session_start();

// Hubungkan ke database
require_once __DIR__ . '/../config/database.php';

// Pastikan user sudah login sebagai mahasiswa
if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'mahasiswa') {
    header("Location: ../../frontend/index.php");
    exit();
}

// Jalankan hanya jika form laporan dikirim
if (isset($_POST['submit'])) {

    // Ambil data dari form
    // (nama kolom di DB: lokasi, deskripsi, foto, tingkat_urgensi, status)
    $lokasi          = trim($_POST['lokasi']);
    $deskripsi       = trim($_POST['deskripsi']);
    $tingkat_urgensi = $_POST['urgensi'] ?? 'Sedang';
    $id_user         = $_SESSION['id_user'];

    // Validasi: lokasi dan deskripsi tidak boleh kosong
    if (empty($lokasi) || empty($deskripsi)) {
        header("Location: ../../frontend/views/mahasiswa/dashboard_user.php?error=data_kosong");
        exit();
    }

    // Proses upload foto (jika ada)
    $nama_foto = null; // Default: tidak ada foto

    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        // Tentukan folder penyimpanan foto
        $folder_upload = __DIR__ . '/../../uploads/';

        // Buat folder uploads jika belum ada
        if (!is_dir($folder_upload)) {
            mkdir($folder_upload, 0777, true);
        }

        // Ambil ekstensi file asli
        $ekstensi = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);

        // Validasi: hanya terima file gambar
        $ekstensi_diizinkan = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array(strtolower($ekstensi), $ekstensi_diizinkan)) {
            header("Location: ../../frontend/views/mahasiswa/dashboard_user.php?error=format_foto");
            exit();
        }

        // Buat nama file unik agar tidak bentrok dengan file lain
        $nama_foto   = uniqid('laporan_', true) . '.' . $ekstensi;
        $path_tujuan = $folder_upload . $nama_foto;

        // Pindahkan file ke folder uploads
        if (!move_uploaded_file($_FILES['foto']['tmp_name'], $path_tujuan)) {
            header("Location: ../../frontend/views/mahasiswa/dashboard_user.php?error=upload_gagal");
            exit();
        }
    }

    // Simpan laporan ke database
    // Kolom sesuai gambar: lokasi, deskripsi, foto, tingkat_urgensi, status
    $sql = "INSERT INTO laporan_kerusakan 
            (id_user, lokasi, deskripsi, foto, tingkat_urgensi, status) 
            VALUES (?, ?, ?, ?, ?, 'Pending')";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_user, $lokasi, $deskripsi, $nama_foto, $tingkat_urgensi]);

    // Kembali ke dashboard dengan pesan sukses
    header("Location: ../../frontend/views/mahasiswa/dashboard_user.php?pesan=laporan_terkirim");
    exit();
}

// Jika bukan POST request, kembali ke dashboard
header("Location: ../../frontend/views/mahasiswa/dashboard_user.php");
exit();
?>