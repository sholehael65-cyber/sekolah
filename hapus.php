
<?php
include_once 'koneksi.php';

// Hapus hanya boleh dilakukan melalui metode POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.php");
    exit;
}

// Ambil NIS dari tombol Hapus yang ditekan
$nis = trim($_POST['nis'] ?? '');

// Periksa apakah NIS tersedia
if ($nis === '') {
    die("NIS siswa tidak ditemukan.");
}

// Siapkan query untuk menghapus satu siswa berdasarkan NIS
$stmt = mysqli_prepare(
    $db,
    "DELETE FROM siswa WHERE nis = ? LIMIT 1"
);

if (!$stmt) {
    die("Gagal menyiapkan query hapus.");
}

// Masukkan NIS ke dalam query
mysqli_stmt_bind_param($stmt, "s", $nis);

// Jalankan proses hapus
if (mysqli_stmt_execute($stmt)) {

    // Periksa apakah ada data yang berhasil dihapus
    $jumlah_dihapus = mysqli_stmt_affected_rows($stmt);

    mysqli_stmt_close($stmt);

    if ($jumlah_dihapus > 0) {
        header("Location: index.php");
        exit;
    } else {
        die("Data siswa tidak ditemukan atau sudah dihapus.");
    }

} else {
    mysqli_stmt_close($stmt);
    die("Gagal menghapus data siswa.");
}
?>