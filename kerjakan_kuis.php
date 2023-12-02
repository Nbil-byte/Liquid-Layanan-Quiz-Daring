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

// Ambil ID kuis dari parameter URL
$id_kuis = isset($_GET['id_kuis']) ? $_GET['id_kuis'] : '';
$querynamakuis = "SELECT nama_kuis FROM kuis WHERE id_kuis = $1";
$resultnamakuis = pg_query_params($dbconn, $querynamakuis, array($id_kuis));
$nama_kuis = join(',',pg_fetch_assoc($resultnamakuis));
$_SESSION['nama_kuis']=$nama_kuis;
$id_mahasiswa = $_SESSION['user_data']['id_mahasiswa'];

// Memeriksa apakah mahasiswa sudah mengerjakan kuis ini sebelumnya
$query_check_kuis = "SELECT * FROM hasil_kuis WHERE id_mahasiswa = $1 AND id_kuis = $2";
$result_check_kuis = pg_query_params($dbconn, $query_check_kuis, array($id_mahasiswa, $id_kuis));

// Jika sudah ada data, maka redirect ke halaman dashboard atau berikan pesan sesuai kebutuhan
if (pg_num_rows($result_check_kuis) > 0) {
    header('Location: dashboard_mahasiswa.php');
    $_SESSION['sudah_dikerjakan'] = true; 
    // exit();
}
else $_SESSION['sudah_dikerjakan'] = false;

$soal_list = getSoalList($id_kuis);
$_SESSION['id_kuis']=$id_kuis;
$_SESSION['listsoal']=$soal_list;

// Cek apakah ID kuis valid
if (empty($id_kuis)) {
    header('Location: dashboard_mahasiswa.php'); // Redirect kembali ke dashboard jika ID kuis tidak valid
    exit();
}

// Ambil informasi kuis dari database berdasarkan ID
$query_kuis_info = "SELECT * FROM kuis WHERE id_kuis = $1";
$result_kuis_info = pg_query_params($dbconn, $query_kuis_info, array($id_kuis));
$kuis_info = pg_fetch_assoc($result_kuis_info);

// Periksa apakah kuis ditemukan
if (!$kuis_info) {
    header('Location: dashboard_mahasiswa.php'); // Redirect kembali ke dashboard jika kuis tidak ditemukan
    exit();
}

// TODO: Tambahkan logika untuk menampilkan pertanyaan kuis dan form untuk menjawab

// Sesuaikan judul halaman
$page_title = "Kerjakan Kuis - " . $kuis_info['nama_kuis'];
$dosen_pembuat = "SELECT nama from dosen WHERE id_dosen = (SELECT id_dosen_pembuat from kuis where id_kuis=$1)";
$result_dosen = pg_query_params($dbconn,$dosen_pembuat,array($id_kuis));
$nama_dosen = pg_fetch_assoc($result_dosen);

$deadline = $kuis_info['deadline'];
$waktu_pengerjaan = $kuis_info['waktu_pengerjaan'];
$jumlah_soal = $kuis_info['jumlah_soal'];
$desc = $kuis_info['deskripsi'];
$nama_kuis = $kuis_info['nama_kuis'];

include_once 'kerjakan_kuis.html';
// Sertakan header
// include 'header.php';
?>

