<?php
include_once 'koneksi.php';

// Ambil semua data siswa
$query = mysqli_query($db, "SELECT * FROM siswa");

// Simpan ke dalam array
$data_siswa = [];
while ($row = mysqli_fetch_assoc($query)) {
    $data_siswa[] = $row;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Siswa — Sekolah</title>
    <style>
        * {
            font-family: Arial, sans-serif;
        }
        body {
            max-width: 800px;
            margin: 30px auto;
            padding: 0 20px;
            background: #f4f4f4;
        }
        h2 {
            text-align: center;
            color: #2c3e50;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background: #2c3e50;
            color: white;
        }
        tr:hover {
            background: #f9f9f9;
        }
    </style>
</head>
<body>
    <h2>📊 Daftar Data Siswa</h2>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>NIS</th>
                <th>Nama Lengkap</th>
                <th>Kelas</th>
                <th>Jurusan</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($data_siswa)): ?>
                <?php $no = 1; ?>
                <?php foreach ($data_siswa as $siswa): ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= $siswa['nis']; ?></td>
                    <td><?= $siswa['nama']; ?></td>
                    <td><?= $siswa['kelas']; ?></td>
                    <td><?= $siswa['jurusan']; ?></td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" style="text-align:center; padding:20px;">Belum ada data siswa.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>