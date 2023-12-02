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

// Ambil ID kuis dan list soal dari session
$id_kuis = isset($_SESSION['id_kuis']) ? $_SESSION['id_kuis'] : '';
$soal_list = isset($_SESSION['listsoal']) ? $_SESSION['listsoal'] : array();
unset($_SESSION['nomor_soal']);
unset($_SESSION['nama_kuis']);

// Inisialisasi variabel untuk menyimpan hasil pemeriksaan
$hasil_pemeriksaan = array();

// Inisialisasi variabel untuk menyimpan poin keseluruhan
$nilai = 0;

// Inisialisasi variabel untuk menyimpan jawaban pengguna
$jawaban_pengguna_array = array();

// Loop melalui setiap nomor soal
foreach ($soal_list as $nomor_soal => $soal) {
    // Membuat kunci jawaban yang benar
    $kunci_jawaban = $soal['kunci_jawaban'];

    // Mendapatkan jawaban yang dipilih oleh pengguna
    $jawaban_pengguna = isset($_SESSION['jawaban'][$nomor_soal+1]) ? $_SESSION['jawaban'][$nomor_soal+1] : '';
    // Memeriksa apakah jawaban pengguna benar
    $jawaban_benar = ($jawaban_pengguna === $kunci_jawaban);
    if(isset($_SESSION['jawaban'][$nomor_soal]))unset($_SESSION['jawaban'][$nomor_soal]);
    // Menghitung dan menyimpan poin jika jawaban benar
    if ($jawaban_benar) {
        $nilai += $soal['poin'];
    }

    // Menyimpan hasil pemeriksaan untuk setiap nomor soal
    $hasil_pemeriksaan[$nomor_soal] = array(
        'jawaban_pengguna' => $jawaban_pengguna,
        'jawaban_benar' => $jawaban_benar,
    );

    // Menyimpan jawaban pengguna ke dalam array
    $jawaban_pengguna_array[$nomor_soal] = $jawaban_pengguna;
}

// Menyimpan detail jawaban dan hasil kuis ke dalam database
$query_insert_hasil = "INSERT INTO hasil_kuis (id_kuis, id_mahasiswa, hasil, jawaban_pengguna) VALUES ($1, $2, $3, $4)";
$result_insert_hasil = pg_query_params($dbconn, $query_insert_hasil, array($id_kuis, $_SESSION['user_data']['id_mahasiswa'], $nilai, json_encode($jawaban_pengguna_array)));

// Periksa keberhasilan eksekusi query
if ($result_insert_hasil) {
    header('Location: dashboard_mahasiswa.php');
    exit();}
//  else {
//     // Jika terjadi kesalahan, set pesan error
//     $error_message = "Terjadi kesalahan dalam menyimpan hasil kuis. Silakan coba lagi atau hubungi administrator.";
//     $_SESSION['error_message'] = $error_message;
//     header('Location: dashboard_mahasiswa.php'); // Redirect kembali ke halaman_kuis.php dengan pesan error
//     exit();
// }
?>
