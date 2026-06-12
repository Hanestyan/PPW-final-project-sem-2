<?php
include 'database.php';

if (isset($_POST['id_laporan']) && isset($_POST['status_baru'])) {
    $id = $_POST['id_laporan'];
    $status_baru = $_POST['status_baru'];

    $sql = "UPDATE laporan_kerusakan SET status = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$status_baru, $id]);

    header("Location: ../dashboard_satpam.php");
}
?>