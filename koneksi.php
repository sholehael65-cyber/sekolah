

<?php
// host = xampp itu hostnya localhost
// user = root
// password = kosong
// database = sekolah

$db = mysqli_connect("localhost", "root", "", "sekolah");

// Cek apakah koneksi berhasil
if (!$db) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
// echo "Koneksi berhasil!";
?>