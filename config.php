<?php

// Konfigurasi Koneksi Database
$host = 'localhost';  // Server database (localhost jika di komputer lokal)
$database = 'liquid';  // Nama database
$port = '5432';  // Port database PostgreSQL (default: 5432)
$username = 'postgres';  // Nama pengguna database
$password = 'janganlupa';  // Kata sandi pengguna database

// Koneksi ke Database

$dbconn = pg_connect("host=$host dbname=$database user=$username password=$password port=$port");

// Periksa koneksi
if (!$dbconn) {
    die("Koneksi database gagal: " . pg_last_error());
}

// Jika koneksi berhasil
// echo "Koneksi database berhasil.";

// Tutup koneksi
// pg_close($dbconn);

?>
