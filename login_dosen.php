<?php
// File: login_dosen.php

// Koneksi ke database (gantilah sesuai konfigurasi Anda)
include 'config.php';

// Proses login dosen
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $username = $_POST['username_dosen'];
    $password = $_POST['password_dosen'];

    // Query untuk mengambil data dari database
    $query = "SELECT * FROM dosen WHERE username = ?";

    // Prepare statement
    $stmt = $dbh->prepare($query);

    // Bind parameter
    $stmt->bindParam(1, $username);

    // Eksekusi statement
    $stmt->execute();

    // Ambil hasil query
    $dosen = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verifikasi password
    if ($dosen && password_verify($password, $dosen['password'])) {
        echo "Login berhasil sebagai Dosen.";
        // Lakukan tindakan setelah login berhasil (redirect, set session, dll.)
    } else {
        echo "Login gagal. Periksa kembali username dan password Anda.";
    }
}

// Tutup koneksi ke database
$dbh = null;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Dosen - Liquid</title>
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
                        <h1 class="text-center mb-4">Login Dosen</h1>
                        <form action="" method="post">
                            <div class="form-group">
                                <label for="username_dosen">Username:</label>
                                <input type="text" class="form-control" id="username_dosen" name="username_dosen" required>
                            </div>
                            <div class="form-group">
                                <label for="password_dosen">Password:</label>
                                <input type="password" class="form-control" id="password_dosen" name="password_dosen" required>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">Login</button>
                        </form>
                        <p class="mt-3 text-center">Belum punya akun? <a href="register_dosen.php">Silakan register</a></p>
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
