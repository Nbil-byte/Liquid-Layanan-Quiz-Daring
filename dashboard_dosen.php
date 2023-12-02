<?php
include_once 'config.php';
include_once 'fungsi.php';
session_start();

$id_dosen = $_SESSION['user_data']['id_dosen'];
$kuis_dosen_query = "SELECT * FROM kuis WHERE id_dosen_pembuat = $1";
$result_kuis_dosen = pg_query_params($dbconn, $kuis_dosen_query, array($id_dosen));

if(isset($_SESSION['id_kuis'])) unset($_SESSION['id_kuis']);

if (!isset($_SESSION['user_data']) || $_SESSION['user_data']['role'] !== 'dosen') {
    header('Location: index.php');
    exit();
}

if (!$result_kuis_dosen) {
    die("Error dalam query: " . pg_last_error());
}

$kuis_dosen = pg_fetch_all($result_kuis_dosen);
echo '<link rel="stylesheet" type="text/css" href="style.css">';

include_once 'dashboard_dosen.html';
include 'footer.php';?>