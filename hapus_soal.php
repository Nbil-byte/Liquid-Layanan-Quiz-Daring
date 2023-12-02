<?php
include_once 'config.php';
session_start();

// Pastikan hanya dosen yang dapat mengakses halaman ini
if (!isset($_SESSION['user_data']) || $_SESSION['user_data']['role'] !== 'dosen') {
    http_response_code(403); // Tidak diizinkan
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Pastikan menggunakan metode POST untuk menghapus data
    if (isset($_POST['id_soal'])) {
        $id_soal = $_POST['id_soal'];

        $queryDeletesoal = "DELETE FROM soal WHERE id_soal = $1";
        $resultDeletesoal = pg_query_params($dbconn, $queryDeletesoal, array($id_soal));

        if ($resultDeletesoal) {
            echo json_encode(['status' => 'success']);
            exit();
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus soal. Silakan coba lagi.']);
            exit();
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'ID soal tidak ditemukan.']);
        exit();
    }
} else {
    http_response_code(405); // Method Not Allowed
    exit();
}
?>
