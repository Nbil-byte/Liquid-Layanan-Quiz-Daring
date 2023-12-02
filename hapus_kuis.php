<?php
include_once 'config.php';
session_start();

// Pastikan hanya dosen yang dapat mengakses halaman ini
if (!isset($_SESSION['user_data']) || $_SESSION['user_data']['role'] !== 'dosen') {
    http_response_code(403); // Tidak diizinkan
    exit();
}

if (isset($_POST['id_kuis'])) {
    $id_kuis = $_POST['id_kuis'];

    // Lakukan query untuk menghapus kuis berdasarkan ID
    $queryDeleteKuis = "DELETE FROM kuis WHERE id_kuis = $1";
    $resultDeleteKuis = pg_query_params($dbconn, $queryDeleteKuis, array($id_kuis));

    if ($resultDeleteKuis) {
        // Berhasil menghapus kuis, kirim respon sukses
        echo json_encode(['status' => 'success']);
        exit();
    } else {
        // Gagal menghapus kuis, kirim respon error
        echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus kuis. Silakan coba lagi.']);
        exit();
    }
} else {
    // Jika tidak ada ID kuis yang diberikan, kirim respon error
    echo json_encode(['status' => 'error', 'message' => 'ID kuis tidak ditemukan.']);
    exit();
}
?>
