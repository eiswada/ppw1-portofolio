<?php
require 'koneksi.php';

$prodi_list = [
    'Teknik Informatika',
    'Sistem Informasi',
    'Teknik Elektro',
    'Manajemen',
    'Akuntansi',
    'Hukum',
    'Kedokteran',
    'Psikologi',
];

$id     = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$result = mysqli_query($conn, "SELECT * FROM mahasiswa WHERE id = $id");

if (mysqli_num_rows($result) === 0) {
    header('Location: index.php');
    exit;
}

$data = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nim      = mysqli_real_escape_string($conn, trim($_POST['nim']));
    $nama     = mysqli_real_escape_string($conn, trim($_POST['nama']));
    $prodi    = mysqli_real_escape_string($conn, trim($_POST['prodi']));
    $ipk      = mysqli_real_escape_string($conn, trim($_POST['ipk']));
    $semester = mysqli_real_escape_string($conn, trim($_POST['semester']));

    $cek = mysqli_query($conn, "SELECT id FROM mahasiswa WHERE nim = '$nim' AND id != $id");
    if (mysqli_num_rows($cek) > 0) {
        header('Location: index.php?pesan=duplikat');
        exit;
    }

    mysqli_query($conn, "UPDATE mahasiswa SET nim='$nim', nama='$nama', prodi='$prodi',
                         ipk='$ipk', semester='$semester' WHERE id=$id");
    header('Location: index.php?pesan=edit');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Edit Mahasiswa</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet"/>
</head>
<body class="bg-light">
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card shadow-sm">
        <div class="card-body p-4">

          <h5 class="fw-bold mb-1"><i class="bi bi-pencil-fill me-2 text-warning"></i>Edit Mahasiswa</h5>
          <p class="text-muted small mb-4">Ubah data yang diperlukan lalu simpan.</p>

          <form method="POST">
            <div class="mb-3">
              <label class="form-label fw-semibold">NIM</label>
              <input type="text" name="nim" class="form-control" value="<?= htmlspecialchars($data['nim']) ?>" required/>
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">Nama</label>
              <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($data['nama']) ?>" required/>
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">Program Studi</label>
              <select name="prodi" class="form-select" required>
                <option value="">-- Pilih Prodi --</option>
                <?php foreach ($prodi_list as $p): ?>
                  <option value="<?= htmlspecialchars($p) ?>"
                    <?= $data['prodi'] === $p ? 'selected' : '' ?>>
                    <?= htmlspecialchars($p) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">IPK</label>
              <input type="number" name="ipk" class="form-control" value="<?= htmlspecialchars($data['ipk']) ?>" step="0.01" min="0" max="4" required/>
            </div>
            <div class="mb-4">
              <label class="form-label fw-semibold">Semester</label>
              <input type="number" name="semester" class="form-control" value="<?= htmlspecialchars($data['semester']) ?>" min="1" max="14" required/>
            </div>
            <div class="d-flex gap-2">
              <button type="submit" class="btn btn-warning flex-fill">
                <i class="bi bi-save me-1"></i> Update
              </button>
              <a href="index.php" class="btn btn-outline-secondary flex-fill">
                <i class="bi bi-arrow-left me-1"></i> Batal
              </a>
            </div>
          </form>

        </div>
      </div>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
