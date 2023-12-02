<?php
// File: register_dosen.php

// Simpan informasi koneksi database dari config.php
include_once 'config.php';

// Fungsi untuk menyimpan data pendaftaran dosen
function registerdosen($id_dosen, $password, $nama, $email, $jurusan) {
    global $dbconn;

    // Hindari SQL injection
    $id_dosen = pg_escape_string($id_dosen);
    $password = pg_escape_string($password);
    $nama = pg_escape_string($nama);
    $email = pg_escape_string($email);
    $jurusan = pg_escape_string($jurusan);

    // Hash password untuk keamanan
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Query untuk menyimpan data pendaftaran dosen
    $query = "INSERT INTO dosen (id_dosen, password, nama, email, jurusan) VALUES ('$id_dosen', '$hashed_password', '$nama', '$email', '$jurusan')";
    
    // Mengeksekusi query
    $result = pg_query($dbconn, $query);

    // Periksa hasil query
    if ($result) {
        // Set pesan sukses ke sesi
        $_SESSION['success_message'] = "Akun dosen berhasil dibuat.";
        // Redirect ke halaman index.php
        header('Location: index.php');
        exit();
    } else {
        die("Error dalam query: " . pg_last_error());
    }
}

// Pengecekan form pendaftaran
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_dosen = isset($_POST['id_dosen']) ? $_POST['id_dosen'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $nama = isset($_POST['nama']) ? $_POST['nama'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $jurusan = isset($_POST['jurusan']) ? $_POST['jurusan'] : '';

    // Panggil fungsi registerdosen
    registerdosen($id_dosen, $password, $nama, $email, $jurusan);
    echo '<link rel="stylesheet" type="text/css" href="style.css">';
    echo '<script src="script_register.js"></script>'; // Tetap rujuk ke script_register.js jika diperlukan
    }

    echo '<link rel="stylesheet" type="text/css" href="style.css">';
    echo '<script src="script_register.js"></script>'; // Tetap rujuk ke script_register.js jika diperlukan
    
    include_once 'register_dosen.html';

    ?>
    
