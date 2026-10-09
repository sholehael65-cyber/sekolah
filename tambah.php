
<?php
include_once 'koneksi.php';

$error = "";

$nis = "";
$nama = "";
$kelas = "";
$jurusan = "";

function e($data) {
    return htmlspecialchars((string)$data, ENT_QUOTES, 'UTF-8');
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nis = trim($_POST["nis"] ?? "");
    $nama = trim($_POST["nama"] ?? "");
    $kelas = trim($_POST["kelas"] ?? "");
    $jurusan = trim($_POST["jurusan"] ?? "");

    if ($nis === "" || $nama === "" ||
        $kelas === "" || $jurusan === "") {
        $error = "Semua kolom wajib diisi.";
    } else {
        $stmt = mysqli_prepare(
            $db,
            "INSERT INTO siswa (nis, nama, kelas, jurusan)
             VALUES (?, ?, ?, ?)"
        );

        mysqli_stmt_bind_param(
            $stmt, "ssss", $nis, $nama, $kelas, $jurusan
        );

        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            header("Location: index.php");
            exit;
        } else {
            $error = "Gagal menyimpan data. Pastikan NIS belum digunakan.";
            mysqli_stmt_close($stmt);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Data Siswa</title>

    <style>
        body {
            max-width: 600px;
            margin: 30px auto;
            padding: 20px;
            background: #f4f4f4;
            font-family: Arial, sans-serif;
        }

        h2 { color: #2c3e50; }

        form {
            background: white;
            padding: 20px;
            border-radius: 8px;
        }

        label {
            display: block;
            margin-top: 12px;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button, .kembali {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            text-decoration: none;
            cursor: pointer;
        }

        button {
            background: #198754;
            color: white;
        }

        .kembali {
            background: #6c757d;
            color: white;
        }

        .error { color: #dc3545; }
    </style>
</head>

<body>

    <h2>Tambah Data Siswa</h2>

    <?php if ($error !== ""): ?>
        <p class="error"><?= e($error); ?></p>
    <?php endif; ?>

    <form method="POST">
        <label>NIS</label>
        <input type="text" name="nis"
               value="<?= e($nis); ?>" required>

        <label>Nama Lengkap</label>
        <input type="text" name="nama"
               value="<?= e($nama); ?>" required>

        <label>Kelas</label>
        <input type="text" name="kelas"
               value="<?= e($kelas); ?>" required>

        <label>Jurusan</label>
        <input type="text" name="jurusan"
               value="<?= e($jurusan); ?>" required>

        <button type="submit">Simpan Data</button>
        <a href="index.php" class="kembali">Kembali</a>
    </form>

</body>
</html>