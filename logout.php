<?php
session_start();

// Hapus semua data sesi
session_unset();

// Hapus sesi dari server
session_destroy();

// Redirect ke halaman login atau halaman utama
header("Location: index.php");
exit();
?>
