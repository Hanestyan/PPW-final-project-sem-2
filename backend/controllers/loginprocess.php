<?php
session_start();
include 'database.php';

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['id_user'] = $user['id'];
        $_SESSION['role'] = $user['role'];

        if (isset($_POST['remember_me'])) {
            setcookie('remember_username', $username, time() + (86400 * 30), "/");
        } else {
            setcookie('remember_username', '', time() - 3600, "/");
        }

        if ($user['role'] == 'admin') {
            header("Location: ../dashboard_satpam.php");
        } else {
            header("Location: ../dashboard_user.php");
        }
        exit();
    } else {
        header("Location: index.php?error=salah_password");
    }
}
?>