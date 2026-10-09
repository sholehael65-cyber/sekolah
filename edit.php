```php
<?php
include_once 'koneksi.php';

function e($data) {
    return htmlspecialchars((string)$data, ENT_QUOTES, 'UTF-8');
}

// Mengambil NIS awal dari URL
$nis_awal = $_GET['nis'] ?? '';

if ($nis_awal === '') {
    die("NIS siswa tidak ditemukan.");
}

// Mengambil data siswa
$stmt = mysqli_prepare(
    $db,
    "SELECT nis, nama, kelas, jurusan FROM siswa WHERE nis = ?"
);

if (!$stmt) {
    die("Gagal mengambil data siswa.");
}

mysqli_stmt_bind_param($stmt, "s", $nis_awal);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$siswa = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$siswa) {
    die("Data siswa tidak ditemukan.");
}

$error = "";

// Daftar pilihan jurusan
$daftar_jurusan = [
    "Teknik Komputer dan Jaringan",
    "Rekayasa Perangkat Lunak",
    "Multimedia",
    "Teknik Kendaraan Ringan"
];

// Memproses formulir ketika tombol Simpan ditekan
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nis_baru = trim($_POST['nis'] ?? '');
    $nama = trim($_POST['nama'] ?? '');
    $kelas = trim($_POST['kelas'] ?? '');
    $jurusan = trim($_POST['jurusan'] ?? '');

    if (
        $nis_baru === '' ||
        $nama === '' ||
        $kelas === '' ||
        $jurusan === ''
    ) {
        $error = "Semua kolom wajib diisi.";
    } elseif (!in_array($jurusan, $daftar_jurusan, true)) {
        $error = "Pilihan jurusan tidak valid.";
    } else {
        $stmt = mysqli_prepare(
            $db,
            "UPDATE siswa
             SET nis = ?, nama = ?, kelas = ?, jurusan = ?
             WHERE nis = ?"
        );

        if (!$stmt) {
            $error = "Gagal menyiapkan perubahan data.";
        } else {
            mysqli_stmt_bind_param(
                $stmt,
                "sssss",
                $nis_baru,
                $nama,
                $kelas,
                $jurusan,
                $nis_awal
            );

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_close($stmt);
                header("Location: index.php");
                exit;
            } else {
                $error = "Gagal memperbarui data. Pastikan NIS tidak digunakan siswa lain.";
                mysqli_stmt_close($stmt);
            }
        }
    }

    // Menampilkan kembali data yang diisi jika ada kesalahan
    $siswa['nis'] = $nis_baru;
    $siswa['nama'] = $nama;
    $siswa['kelas'] = $kelas;
    $siswa['jurusan'] = $jurusan;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Siswa</title>

    <style>
        body {
            max-width: 600px;
            margin: 30px auto;
            padding: 20px;
            background: #f4f4f4;
            font-family: Arial, sans-serif;
        }

        h2 {
            color: #2c3e50;
        }

        form {
            background: white;
            padding: 20px;
            border-radius: 8px;
        }

        label {
            display: block;
            margin-top: 12px;
        }

        input,
        select {
            display: block;
            width: 100%;
            box-sizing: border-box;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
            background: white;
        }

        button,
        .kembali {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            text-decoration: none;
            cursor: pointer;
            font-size: 16px;
        }

        button {
            background: #0d6efd;
            color: white;
        }

        button:hover {
            background: #0b5ed7;
        }

        .kembali {
            background: #6c757d;
            color: white;
        }

        .error {
            color: #dc3545;
        }
    </style>
</head>

<body>

    <h2>Edit Data Siswa</h2>

    <?php if ($error !== ""): ?>
        <p class="error"><?= e($error); ?></p>
    <?php endif; ?>

    <form method="POST">
        <label for="nis">NIS</label>
        <input
            type="text"
            id="nis"
            name="nis"
            value="<?= e($siswa['nis']); ?>"
            required
        >

        <label for="nama">Nama Lengkap</label>
        <input
            type="text"
            id="nama"
            name="nama"
            value="<?= e($siswa['nama']); ?>"
            required
        >

        <label for="kelas">Kelas</label>
        <input
            type="text"
            id="kelas"
            name="kelas"
            value="<?= e($siswa['kelas']); ?>"
            required
        >

        <label for="jurusan">Jurusan</label>
        <select id="jurusan" name="jurusan" required>
            <option value="">-- Pilih Jurusan --</option>

            <?php foreach ($daftar_jurusan as $pilihan): ?>
                <option
                    value="<?= e($pilihan); ?>"
                    <?= $siswa['jurusan'] === $pilihan ? 'selected' : ''; ?>
                >
                    <?= e($pilihan); ?>
                </option>
            <?php endforeach; ?>

            <?php
            // Mempertahankan jurusan lama jika belum ada dalam daftar
            if (
                $siswa['jurusan'] !== '' &&
                !in_array($siswa['jurusan'], $daftar_jurusan, true)
            ):
            ?>
                <option value="<?= e($siswa['jurusan']); ?>" selected>
                    <?= e($siswa['jurusan']); ?> (Jurusan saat ini)
                </option>
            <?php endif; ?>
        </select>

        <button type="submit">Simpan Perubahan</button>
        <a href="index.php" class="kembali">Kembali</a>
    </form>

</body>
</html>
```
