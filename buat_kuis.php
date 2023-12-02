<!-- File: buat_kuis.php -->
<?php
include_once 'config.php';
include_once 'fungsi.php';
session_start();


// Pastikan hanya dosen yang dapat mengakses halaman ini
if (!isset($_SESSION['user_data']) || $_SESSION['user_data']['role'] !== 'dosen') {
    header('Location: index.php');
    exit();
}

// Fungsi untuk menyimpan kuis ke database
function simpanKuis($nama_kuis, $deskripsi) {   
    global $dbconn;

    $nama_kuis = pg_escape_string($nama_kuis);
    $deskripsi = pg_escape_string($deskripsi);
    $id_dosen = $_SESSION['user_data']['id_dosen'];
    $tanggal_dibuat = date("Y-m-d");

    $query = "INSERT INTO kuis (nama_kuis, deskripsi, id_dosen_pembuat, tanggal_dibuat) VALUES ('$nama_kuis', '$deskripsi', '$id_dosen', '$tanggal_dibuat' ) RETURNING id_kuis";
    $result = pg_query($dbconn, $query);

    if ($result) {
        $row = pg_fetch_assoc($result);
        $_SESSION['id_kuis'] = $row['id_kuis'];
        return true; // Kuis berhasil disimpan
    } else {
        return false; // Gagal menyimpan kuis
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_kuis = isset($_POST['nama_kuis']) ? $_POST['nama_kuis'] : '';
    $deskripsi = isset($_POST['deskripsi']) ? $_POST['deskripsi'] : '';

    $submitted_token = isset($_POST['csrf_token']) ? $_POST['csrf_token'] : '';
    if (verify_csrf_token($submitted_token)) {
        if (!empty($nama_kuis) && !empty($deskripsi)) {
            // Panggil fungsi untuk menyimpan kuis
            if (simpanKuis($nama_kuis, $deskripsi)) {
                $id_kuis = isset($_GET['id_kuis']) ? $_GET['id_kuis'] : '';
                $_SESSION['csrf_token'] = generate_csrf_token();
                header('Location: dashboard_dosen.php'); // Alihkan ke halaman hasil_kuis.php
                // exit();
            } else {
                echo "<p>Gagal membuat kuis. Silakan coba lagi.</p>";
            }
        } else {
            echo "<p>Silakan lengkapi semua kolom.</p>";
        }
    }
    else {
        // Token tidak valid, mungkin menampilkan pesan kesalahan atau menghentikan pemrosesan formulir.
        echo "Token CSRF tidak valid!";}

    
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Kuis</title>
    <!-- Tambahkan link CSS Bootstrap -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">

    <h2>Buat Kuis Baru</h2>
    <form method="post" action="" class="mt-3">
        <input type="hidden" name="csrf_token" value="<?php echo isset($_SESSION['csrf_token']) ? htmlspecialchars($_SESSION['csrf_token']) : ''; ?>">
        <input type="hidden" name="id_kuis" value="<?php echo isset($kuis_details['id_kuis']) ? htmlspecialchars($kuis_details['id_kuis']) : ''; ?>">
        
        <div class="form-group">
            <label for="nama_kuis">Nama Kuis:</label>
            <input type="text" name="nama_kuis" class="form-control" required>
        </div>
        
        <div class="form-group">
            <label for="deskripsi">Deskripsi:</label>
            <textarea name="deskripsi" rows="4" class="form-control" required></textarea>
        </div>
        
        <button type="submit" class="btn btn-primary">Buat Kuis</button>
    </form>

    <!-- Tambahkan script dan link Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

</body>
</html>


<?php include 'footer.php';?>
