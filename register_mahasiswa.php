<?php
// File: register_mahasiswa.php

// Simpan informasi koneksi database dari config.php
include_once 'config.php';

// Fungsi untuk menyimpan data pendaftaran mahasiswa
function registerMahasiswa($id_mahasiswa, $password, $nama, $email, $jurusan) {
    global $dbconn;

    // Hindari SQL injection
    $id_mahasiswa = pg_escape_string($id_mahasiswa);
    $password = pg_escape_string($password);
    $nama = pg_escape_string($nama);
    $email = pg_escape_string($email);
    $jurusan = pg_escape_string($jurusan);

    // Hash password untuk keamanan
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Query untuk menyimpan data pendaftaran mahasiswa
    $query = "INSERT INTO mahasiswa (id_mahasiswa, password, nama, email, jurusan) VALUES ('$id_mahasiswa', '$hashed_password', '$nama', '$email', '$jurusan')";
    
    // Mengeksekusi query
    $result = pg_query($dbconn, $query);

    // Periksa hasil query
    if ($result) {
        // Set pesan sukses ke sesi
        $_SESSION['success_message'] = "Akun Mahasiswa berhasil dibuat.";
        // Redirect ke halaman index.php
        header('Location: index.php');
        exit();
    } else {
        die("Error dalam query: " . pg_last_error());
    }
}

// Pengecekan form pendaftaran
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_mahasiswa = isset($_POST['id_mahasiswa']) ? $_POST['id_mahasiswa'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $nama = isset($_POST['nama']) ? $_POST['nama'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $jurusan = isset($_POST['jurusan']) ? $_POST['jurusan'] : '';

    // Panggil fungsi registerMahasiswa
    registerMahasiswa($id_mahasiswa, $password, $nama, $email, $jurusan);
}
echo '<script src="script_register.js"></script>';
include_once 'register_mahasiswa.html';
?>

