<?php
// Sertakan konfigurasi dan mulai sesi
include_once 'config.php';
include_once 'fungsi.php';
session_start();

// Pastikan hanya mahasiswa yang dapat mengakses halaman ini
if (!isset($_SESSION['user_data']) || $_SESSION['user_data']['role'] !== 'mahasiswa') {
    header('Location: index.php');
    exit();
}

// Ambil ID mahasiswa
$id_mahasiswa = $_SESSION['user_data']['id_mahasiswa'];

// Ambil data hasil kuis untuk mahasiswa tertentu
$query_grades = "SELECT k.nama_kuis, h.hasil
                 FROM hasil_kuis h
                 JOIN kuis k ON h.id_kuis = k.id_kuis
                 WHERE h.id_mahasiswa = $1";
$result_grades = pg_query_params($dbconn, $query_grades, array($id_mahasiswa));

// Sesuaikan judul halaman
$page_title = "Grades - " . $_SESSION['user_data']['nama'];
echo '<link rel="stylesheet" type="text/css" href="style.css">';
include_once 'grades.html';
?>