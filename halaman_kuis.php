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


// Ambil ID kuis dari session
$id_kuis = isset($_SESSION['id_kuis']) ? $_SESSION['id_kuis'] : '';
$id_mahasiswa = $_SESSION['user_data']['id_mahasiswa'];

$query_check_kuis = "SELECT * FROM hasil_kuis WHERE id_mahasiswa = $1 AND id_kuis = $2";
$result_check_kuis = pg_query_params($dbconn, $query_check_kuis, array($id_mahasiswa, $id_kuis));

if (isset($_SESSION['sudah_dikerjakan'])?$_SESSION['sudah_dikerjakan']:false) {
    header('Location: dashboard_mahasiswa.php');
}

// Ambil list soal dari session
$soal_list = isset($_SESSION['listsoal']) ? $_SESSION['listsoal'] : array();

// Tentukan nomor soal yang sedang ditampilkan
$nomor_soal = isset($_GET['nomor_soal']) ? $_GET['nomor_soal'] : (isset($_SESSION['nomor_soal']) ? $_SESSION['nomor_soal'] : 1);

$soal_terpilih = ($nomor_soal > 0 && $nomor_soal <= count($soal_list)) ? $soal_list[$nomor_soal - 1] : null;


// Saat form di-submit, simpan jawaban ke session
if (isset($_POST['jawaban'])) {
    $_SESSION['jawaban'][$nomor_soal] = $_POST['jawaban'];
}

// Simpan nomor soal ke dalam session
$_SESSION['nomor_soal'] = $nomor_soal;
$nama_kuis=isset($_SESSION['nama_kuis'])?$_SESSION['nama_kuis']:"";

// Tetapkan URL halaman duplikat
$halaman_duplikat_url = 'halaman_kuis_duplikat.php';

// Jika tombol next ditekan, arahkan ke halaman duplikat
if (isset($_POST['next']) && $nomor_soal < count($soal_list)) {
    header("Location: $halaman_duplikat_url?nomor_soal=" . ($nomor_soal + 1));
    exit();
}

if (isset($_POST['submit'])) {
    header("Location: proses_jawaban.php");
    exit();
}

// Jika tombol previous ditekan, arahkan ke halaman duplikat
if (isset($_POST['previous']) && $nomor_soal > 1) {
    header("Location: $halaman_duplikat_url?nomor_soal=" . ($nomor_soal - 1));
    exit();
}

include_once 'halaman_kuis.html';
?>



