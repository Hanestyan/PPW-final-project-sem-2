<?php
require_once 'database.php';

try {
    // User Mahasiswa
    $username_mhs = 'budi';
    $password_mhs = password_hash('mahasiswa123', PASSWORD_DEFAULT);
    $role_mhs = 'mahasiswa';

    // User Satpam
    $username_satpam = 'pak_joko';
    $password_satpam = password_hash('satpam123', PASSWORD_DEFAULT);
    $role_satpam = 'satpam';

    // User Admin
    $username_admin = 'admin_utama';
    $password_admin = password_hash('admin123', PASSWORD_DEFAULT);
    $role_admin = 'admin';

    // Insert Mahasiswa
    $stmt1 = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?) ON CONFLICT (username) DO NOTHING");
    $stmt1->execute([$username_mhs, $password_mhs, $role_mhs]);

    // Insert Satpam
    $stmt2 = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?) ON CONFLICT (username) DO NOTHING");
    $stmt2->execute([$username_satpam, $password_satpam, $role_satpam]);

    // Insert Admin
    $stmt3 = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?) ON CONFLICT (username) DO NOTHING");
    $stmt3->execute([$username_admin, $password_admin, $role_admin]);

    echo "Data berhasil ditambahkan!";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
