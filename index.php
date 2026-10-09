
<?php
include_once 'koneksi.php';

// Ambil semua data siswa
$query = mysqli_query($db, "SELECT * FROM siswa");

if (!$query) {
    die("Gagal mengambil data: " . mysqli_error($db));
}

// Simpan data ke array
$data_siswa = [];

while ($row = mysqli_fetch_assoc($query)) {
    $data_siswa[] = $row;
}

// Mengamankan output HTML
function e($data) {
    return htmlspecialchars((string)$data, ENT_QUOTES, 'UTF-8');
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
            box-sizing: border-box;
        }

        body {
            max-width: 1000px;
            margin: 30px auto;
            padding: 0 20px;
            background: #f4f4f4;
        }

        h2 {
            text-align: center;
            color: #2c3e50;
        }

        .tambah {
            display: inline-block;
            margin-bottom: 15px;
            padding: 10px 15px;
            background: #198754;
            color: white;
            text-decoration: none;
            border-radius: 5px;
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

        .edit, .hapus {
            display: inline-block;
            padding: 7px 10px;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            margin-bottom: 3px;
        }

        .edit {
            background: #0d6efd;
        }

        .hapus {
            background: #dc3545;
        }

        form {
            display: inline;
        }

        .tabel-wrapper {
            overflow-x: auto;
        }
    </style>
</head>

<body>

    <h2>📊 Daftar Data Siswa</h2>

    <!-- Tombol tambah siswa -->
    <a href="tambah.php" class="tambah">
        + Tambah Siswa
    </a>

    <div class="tabel-wrapper">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>NIS</th>
                    <th>Nama Lengkap</th>
                    <th>Kelas</th>
                    <th>Jurusan</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                <?php if (!empty($data_siswa)): ?>

                    <?php $no = 1; ?>

                    <?php foreach ($data_siswa as $siswa): ?>
                    <tr>
                        <td><?= $no++; ?></td>

                        <td><?= e($siswa['nis']); ?></td>

                        <td><?= e($siswa['nama']); ?></td>

                        <td><?= e($siswa['kelas']); ?></td>

                        <td><?= e($siswa['jurusan']); ?></td>

                        <td>
                            <!-- Tombol edit -->
                            <a class="edit"
                               href="edit.php?nis=<?= urlencode($siswa['nis']); ?>">
                                Edit
                            </a>

                            <!-- Tombol hapus khusus untuk siswa ini -->
                            <form action="hapus.php"
                                  method="POST"
                                  onsubmit="return confirm('Apakah kamu yakin ingin menghapus data siswa <?= e($siswa['nama']); ?>?');">

                                <input type="hidden"
                                       name="nis"
                                       value="<?= e($siswa['nis']); ?>">

                                <button type="submit" class="hapus">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>

                <?php else: ?>

                    <tr>
                        <td colspan="6"
                            style="text-align:center; padding:20px;">
                            Belum ada data siswa.
                        </td>
                    </tr>

                <?php endif; ?>
            </tbody>
        </table>
    </div>

</body>
</html>