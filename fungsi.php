<?php
function generate_csrf_token() {
    return bin2hex(random_bytes(32)); // Menghasilkan token acak (butuh PHP 7 atau lebih tinggi)
}

// Fungsi untuk memeriksa token CSRF
function verify_csrf_token($token) {
    // Anda dapat menyesuaikan logika verifikasi sesuai kebutuhan aplikasi Anda
    // Misalnya, menyimpan token di sesi dan membandingkannya dengan nilai dari formulir
    return isset($_SESSION['csrf_token']) && $_SESSION['csrf_token'] === $token;
}

function getKuisDetails($id_kuis) {
    global $dbconn;

    $id_kuis = pg_escape_string($id_kuis);

    $query = "SELECT * FROM kuis WHERE id_kuis = '$id_kuis'";
    $result = pg_query($dbconn, $query);

    if ($result) {
        return pg_fetch_assoc($result);
    } else {
        die("Error dalam query: " . pg_last_error());
    }
}

function updateKuisDetails($id_kuis, $nama_kuis, $deskripsi) {
    global $dbconn;

    $id_kuis = pg_escape_string($id_kuis);
    $nama_kuis = pg_escape_string($nama_kuis);
    $deskripsi = pg_escape_string($deskripsi);

    // Gunakan kondisi untuk menentukan nilai yang akan diupdate
    $setClause = '';
    if (!empty($nama_kuis)) {
        $setClause .= "nama_kuis = '$nama_kuis'";
    }

    if (!empty($deskripsi)) {
        $setClause .= !empty($setClause) ? ", " : "";
        $setClause .= "deskripsi = '$deskripsi'";
    }

    // Pastikan setClause tidak kosong
    if (!empty($setClause)) {
        $query = "UPDATE kuis SET $setClause WHERE id_kuis = '$id_kuis'";
        $result = pg_query($dbconn, $query);

        if ($result) {
            return true; // Update berhasil
        } else {
            return false; // Gagal update
        }
    } else {
        return false; // Kedua kolom kosong, tidak ada yang diupdate
    }
}

function getSoalList($id_kuis) {
    global $dbconn;

    $id_kuis = pg_escape_string($id_kuis);

    $query = "SELECT * FROM soal WHERE id_kuis_terkait = '$id_kuis'";
    $result = pg_query($dbconn, $query);

    if ($result) {
        return pg_fetch_all($result);
    } else {
        die("Error dalam query: " . pg_last_error());
    }
}

function tambahSoal($id_kuis, $pertanyaan, $pilihan_a, $pilihan_b, $pilihan_c, $jawaban_benar, $no_soal, $poin) {
    global $dbconn;

    $id_kuis = pg_escape_string($id_kuis);
    $pertanyaan = pg_escape_string($pertanyaan);
    $pilihan_a = pg_escape_string($pilihan_a);
    $pilihan_b = pg_escape_string($pilihan_b);
    $pilihan_c = pg_escape_string($pilihan_c);
    $jawaban_benar = pg_escape_string($jawaban_benar);
    $poin = pg_escape_string($poin);

    $query = "INSERT INTO soal (id_kuis_terkait, pertanyaan, opsi_jawaban1, opsi_jawaban2, opsi_jawaban3, kunci_jawaban, no_soal, poin) 
              VALUES ('$id_kuis', '$pertanyaan', '$pilihan_a', '$pilihan_b', '$pilihan_c', '$jawaban_benar', '$no_soal', '$poin')";
    
    $result = pg_query($dbconn, $query);

    if ($result) {
        $queryUpdateJumlahSoal = "UPDATE kuis SET jumlah_soal = jumlah_soal + 1 WHERE id_kuis = '$id_kuis'";
        pg_query($dbconn, $queryUpdateJumlahSoal);
        return true; // Soal berhasil disimpan
    } else {
        return false; // Gagal menyimpan soal
    }
}

function hapusSoal($id_soal) {
    global $dbconn;

    $id_soal = pg_escape_string($id_soal);

    // Periksa apakah soal dengan ID tertentu ada di database
    $queryCekSoal = "SELECT * FROM soal WHERE id_soal = '$id_soal'";
    $resultCekSoal = pg_query($dbconn, $queryCekSoal);

    if (pg_num_rows($resultCekSoal) > 0) {
        // Hapus soal dari database
        $queryHapusSoal = "DELETE FROM soal WHERE id_soal = '$id_soal'";
        $resultHapusSoal = pg_query($dbconn, $queryHapusSoal);

        if ($resultHapusSoal) {
            // Update jumlah soal di tabel kuis
            $queryUpdateJumlahSoal = "UPDATE kuis SET jumlah_soal = jumlah_soal - 1 WHERE id_kuis = (SELECT id_kuis_terkait FROM soal WHERE id_soal = '$id_soal')";
            $queryUpdateNoSoal = " UPDATE kuis set no_soal = no_soal-1 WHERE id_kuis_terkait ='$id_kuis' AND no_soal > (SELECT no_soal FROM soal WHERE id_soal = '$id_soal')";
            $resultUpdateJumlahSoal = pg_query($dbconn, $queryUpdateJumlahSoal);

            if ($resultUpdateJumlahSoal) {
                return true; // Soal berhasil dihapus
            } else {
                return false; // Gagal mengupdate jumlah soal
            }
        } else {
            return false; // Gagal menghapus soal
        }
    } else {
        return false; // Soal tidak ditemukan di database
    }
}

function verifyAnswer($id_soal, $jawaban) {
    global $dbconn;
    // Query untuk mendapatkan jawaban yang benar dan poin dari database
    $query = "SELECT kunci_jawaban, poin FROM soal WHERE id_soal = '$id_soal'";
    
    // Eksekusi query dengan parameter
    $result = pg_query($dbconn, $query);

    // Periksa apakah query berhasil dieksekusi
    if (!$result) {
        die("Query error: " . pg_last_error($dbconn));
    }

    // Ambil hasil query
    $row = pg_fetch_assoc($result);

    // Periksa apakah baris data ditemukan
    if (!$row) {
        return 0; // ID soal tidak ditemukan
    }

    // Cek apakah jawaban yang diberikan sama dengan jawaban yang benar
    if ($jawaban === $row['kunci_jawaban']) {
        return $row['poin'];
    } else {
        return 0; // Jawaban salah, tidak mendapatkan poin
    }
}



?>