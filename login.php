<?php
// File: login.php

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
$username = isset($_POST['id']) ? $_POST['id'] : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

// Pengecekan login
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['submit'])) {
        // Pengecekan login sebagai mahasiswa
        $user_data = checkLogin($username, $password, 'mahasiswa');
        if ($user_data) {
            $user_data['role'] = 'mahasiswa';
            $_SESSION['user_data'] = $user_data;
            $_SESSION['csrf_token'] = generate_csrf_token();
            // Redirect ke halaman Mahasiswa setelah login berhasil
            header('Location: dashboard_mahasiswa.php');
            exit();
        } else {
            // Pengecekan login sebagai dosen
            $user_data = checkLogin($username, $password, 'dosen');
            if ($user_data) {
                $user_data['role'] = 'dosen';
                $_SESSION['user_data'] = $user_data;
                $_SESSION['csrf_token'] = generate_csrf_token();
                // Redirect ke halaman Dosen setelah login berhasil
                header('Location: dashboard_dosen.php');
                exit();
            } else {
                echo "<p>Login gagal. Username atau password salah.</p>";
            }
        }
    }
}

echo '<link rel="stylesheet" type="text/css" href="style.css">';
echo '<script src="script.js"></script>';
include_once 'login.html';
include_once 'footer.php';
?>
