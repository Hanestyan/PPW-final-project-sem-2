<?php
session_start();
function check_access($allowed_role) {
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== $allowed_role) {
        header("Location: login.php");
        exit();
    }
}
?>
