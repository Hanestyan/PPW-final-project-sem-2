<?php
// Konfigurasi koneksi database PostgreSQL dengan PDO
$host = 'localhost';
$port = '5432';
$db   = 'safewalk';
$user = 'postgres'; // Default user PostgreSQL di Laragon
$pass = 'hanestyan';     // Sesuaikan password PostgreSQL Anda (default biasanya 'root' atau dikosongkan '')

try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$db";
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]);
} catch (PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
}
?>
