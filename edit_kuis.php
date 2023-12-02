<?php
include_once 'config.php';
include_once 'fungsi.php';
session_start();

if (!isset($_SESSION['user_data']) || $_SESSION['user_data']['role'] !== 'dosen') {
    header('Location: index.php');
    exit();
}

$id_kuis = $_GET['id_kuis'];
$_SESSION['id_kuis']=$id_kuis;

// Handle form submission untuk update nama kuis dan deskripsi
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_kuis = isset($_POST['nama_kuis']) ? $_POST['nama_kuis'] : '';
    $deskripsi = isset($_POST['deskripsi']) ? $_POST['deskripsi'] : '';
    $cek_update_or_add = 1;

    if (!empty($nama_kuis) || !empty($deskripsi)) {
        // Panggil fungsi untuk update nama kuis
        updateKuisDetails($id_kuis, $nama_kuis, $deskripsi);
        header('Location: dashboard_dosen.php');
        $cek_update_or_add = 0;
    }
}

// Handle penghapusan kuis
if (isset($_POST['hapus_kuis'])) {
    // Pastikan token CSRF valid
    $submitted_token = isset($_POST['csrf_token']) ? $_POST['csrf_token'] : '';
    if (verify_csrf_token($submitted_token)) {
        // Lakukan penghapusan kuis
        if (hapusKuis($id_kuis)) {
            // Redirect ke halaman tertentu setelah penghapusan kuis berhasil
            header('Location: dashboard_dosen.php');
            exit();
        } else {
            echo "Gagal menghapus kuis. Silakan coba lagi.";
        }
    } else {
        echo "Token CSRF tidak valid!";
    }
}

// ... (Bagian-bagian lain dari skrip PHP tetap tidak berubah)

$kuis_details = getKuisDetails($id_kuis);
$soal_list = getSoalList($id_kuis);
$total_poin = 0;

if ($soal_list) {
    $sumpoin = "SELECT SUM(poin) AS total_poin FROM soal WHERE id_kuis_terkait = $1";

    // Lakukan query dengan menggunakan parameterized query
    $resultsumpoin = pg_query_params($dbconn, $sumpoin, array($id_kuis));

    // Periksa apakah query berhasil dijalankan
    if ($resultsumpoin) {
        // Ambil hasil query
        $row = pg_fetch_assoc($resultsumpoin);
        $total_poin = $row['total_poin'];
    }
}

echo '<link rel="stylesheet" type="text/css" href="style.css">';

if ($kuis_details) {
    include 'edit_kuis.html';
} else {
    // Handle kesalahan atas berikan pesan kepada pengguna
    echo "Kuis tidak ditemukan atau terjadi kesalahan.";
    echo "ID Kuis: " . $_SESSION['id_kuis'];
}

include 'footer.php';
?>
