<?php
// Sertakan konfigurasi dan mulai sesi
include_once 'config.php';
session_start();

// Pastikan hanya mahasiswa yang dapat mengakses halaman ini
if (!isset($_SESSION['user_data']) || $_SESSION['user_data']['role'] !== 'mahasiswa') {
    header('Location: index.php');
    exit();
}

if(isset($_SESSION['sudah_dikerjakan'])? $_SESSION['sudah_dikerjakan']:false) echo "Anda sudah mengerjakan kuis ini";
$_SESSION['sudah_dikerjakan'] = false;

// Mendapatkan daftar kuis yang belum dikerjakan
$query_kuis = "SELECT * FROM kuis";
$result_kuis= pg_query($dbconn, $query_kuis);
$kuis_mahasiswa = pg_fetch_all($result_kuis);

// Mendapatkan daftar kuis yang sudah dikerjakan oleh mahasiswa
$id_mahasiswa = $_SESSION['user_data']['id_mahasiswa'];

//Pencarian
// Ambil nilai pencarian
$search_term = isset($_GET['search']) ? $_GET['search'] : '';
$query_kuis = "$query_kuis";
if (!empty($search_term)) {
    $query_kuis .= " AND nama_kuis LIKE '%$search_term%'";
}

echo '<link rel="stylesheet" type="text/css" href="style.css">';

// Sesuaikan judul halaman
$page_title = "Dashboard Mahasiswa";
include_once 'dashboard_mahasiswa.html';

?>

<?php if (isset($error_message)): ?>
<p style="color: red;"><?php echo $error_message; ?></p>
<?php endif; ?>
<?php include 'footer.php';?>
