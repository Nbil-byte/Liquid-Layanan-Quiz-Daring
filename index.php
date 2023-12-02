<?php
// File: index.php

// Simpan informasi koneksi database dari config.php
include_once 'config.php';
include_once 'fungsi.php';
session_start();

// Fungsi untuk memeriksa login
function checkLogin($username, $password, $role) {
    global $dbconn;

    // Hindari SQL injection
    $username = pg_escape_string($username);
    $password = pg_escape_string($password);

    // Query untuk memeriksa login
    $query = "SELECT * FROM $role WHERE id_$role = '$username'";
    $result = pg_query($dbconn, $query);

    if ($result) {
        $row_count = pg_num_rows($result);

        if ($row_count == 1) {
            $row = pg_fetch_assoc($result);
            $hashed_password = $row['password'];

            // Membandingkan password dengan password_hash
            if (password_verify($password, $hashed_password)) {
                return $row; // Return data user
            } else {
                return false; // Password salah
            }
        } else {
            return false; // Username tidak ditemukan
        }
    } else {
        die("Error dalam query: " . pg_last_error());
    }
}

// Inisialisasi variabel
$username_mahasiswa = isset($_POST['username_mahasiswa']) ? $_POST['username_mahasiswa'] : '';
$password_mahasiswa = isset($_POST['password_mahasiswa']) ? $_POST['password_mahasiswa'] : '';

$username_dosen = isset($_POST['username_dosen']) ? $_POST['username_dosen'] : '';
$password_dosen = isset($_POST['password_dosen']) ? $_POST['password_dosen'] : '';

// Pengecekan login
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['submit_mahasiswa'])) {
        $user_data = checkLogin($username_mahasiswa, $password_mahasiswa, 'mahasiswa');
        if ($user_data) {
            $user_data['role']='mahasiswa';
            $_SESSION['user_data'] = $user_data;
            $_SESSION['csrf_token'] = generate_csrf_token();
            // Redirect ke halaman Mahasiswa setelah login berhasil
            header('Location: dashboard_mahasiswa.php');
            exit();
        } else {
            echo "<p>Login gagal sebagai Mahasiswa. Username atau password salah.</p>";
        }
    } elseif (isset($_POST['submit_dosen'])) {
        $user_data = checkLogin($username_dosen, $password_dosen, 'dosen');
        if ($user_data) {
            $user_data['role']='dosen';
            $_SESSION['user_data'] = $user_data;
            $_SESSION['csrf_token'] = generate_csrf_token();
            // Redirect ke halaman Dosen setelah login berhasil
            header('Location: dashboard_dosen.php');
            exit();
        } else {
            echo "<p>Login gagal sebagai Dosen. Username atau password salah.</p>";
        }
    }
    
}
echo '<link rel="stylesheet" type="text/css" href="style.css">';
echo '<script src="script.js"></script>';
include_once 'index.html';
include_once 'footer.php';
?>

