<?php
// File: login_mahasiswa.php

// Koneksi ke database (gantilah sesuai konfigurasi Anda)
include 'config.php';

// Proses login mahasiswa
if (isset($_POST['tombollogin'])) {
    $email = pg_escape_string($_POST['email_mahasiswa']);
    $password = $_POST['password_mahasiswa'];

    // Query untuk mendapatkan data mahasiswa berdasarkan email
    $result = pg_query_params($db, "SELECT * FROM mahasiswa WHERE email = $1", array($email));

    if ($result) {
        if (pg_num_rows($result) > 0) {
            $row = pg_fetch_assoc($result);

            // Memeriksa apakah password cocok
            if (password_verify($password, $row['password'])) {
                // Login berhasil, simpan informasi mahasiswa ke dalam session
                session_start();
                $_SESSION['id_mahasiswa'] = $row['id_mahasiswa'];
                $_SESSION['nama_mahasiswa'] = $row['nama_mahasiswa'];

                // Redirect ke halaman dashboard
                header("Location: dashboard_mahasiswa.php");
                exit();
            } else {
                // Password tidak cocok
                echo "Password salah. Silakan coba lagi.";
            }
        } else {
            // Email tidak ditemukan
            echo "Email tidak terdaftar. Silakan daftar terlebih dahulu.";
        }
    } else {
        // Terjadi kesalahan saat menjalankan query
        echo "Terjadi kesalahan. Silakan coba lagi.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Mahasiswa - Liquid</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container main-container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card mt-5">
                    <div class="card-body">
                        <h1 class="text-center mb-4">Login Mahasiswa</h1>
                        <form action="" method="post">
                            <div class="form-group">
                                <label for="email_mahasiswa">Email:</label>
                                <input type="email" class="form-control" id="email_mahasiswa" name="email_mahasiswa" required>
                            </div>
                            <div class="form-group">
                                <label for="password_mahasiswa">Password:</label>
                                <input type="password" class="form-control" id="password_mahasiswa" name="password_mahasiswa" required>
                            </div>
                            <button type="submit" name="tombollogin" class="btn btn-primary btn-block">Login</button>
                        </form>
                        <p class="mt-3 text-center">Belum punya akun? <a href="register_mahasiswa.php">Daftar sekarang</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
