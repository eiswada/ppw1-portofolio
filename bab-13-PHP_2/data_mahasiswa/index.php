<?php
require 'koneksi.php';

$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

if ($search !== '') {
    $query = "SELECT * FROM mahasiswa WHERE nama LIKE '%$search%' OR nim LIKE '%$search%' ORDER BY id DESC";
} else {
    $query = "SELECT * FROM mahasiswa ORDER BY id DESC";
}

$result = mysqli_query($conn, $query);
$total  = mysqli_num_rows($result);

function getPredikat($ipk) {
    if ($ipk >= 3.51) return ['teks' => 'Dengan Pujian',    'badge' => 'bg-success'];
    if ($ipk >= 3.01) return ['teks' => 'Sangat Memuaskan', 'badge' => 'bg-primary'];
    if ($ipk >= 2.76) return ['teks' => 'Memuaskan',        'badge' => 'bg-info text-dark'];
    if ($ipk >= 2.00) return ['teks' => 'Cukup',            'badge' => 'bg-warning text-dark'];
    return                   ['teks' => 'Tidak Lulus',      'badge' => 'bg-danger'];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>CRUD Mahasiswa</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet"/>
</head>
<body class="bg-light">
<div class="container py-5">

  <div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold text-primary mb-0"><i class="bi bi-people-fill me-2"></i>Data Mahasiswa</h4>
    <a href="tambah.php" class="btn btn-primary">
      <i class="bi bi-plus-lg me-1"></i> Tambah
    </a>
  </div>

  <?php if (isset($_GET['pesan'])): ?>
    <?php
      $pesan = $_GET['pesan'];
      $tipe  = 'success';
      $teks  = '';
      if ($pesan === 'tambah')   $teks = 'Data berhasil ditambahkan.';
      if ($pesan === 'edit')     $teks = 'Data berhasil diperbarui.';
      if ($pesan === 'hapus')    $teks = 'Data berhasil dihapus.';
      if ($pesan === 'duplikat') { $teks = 'NIM sudah terdaftar!'; $tipe = 'danger'; }
    ?>
    <div class="alert alert-<?= $tipe ?> alert-dismissible fade show" role="alert">
      <?= $teks ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  <?php endif; ?>

  <div class="card shadow-sm">
    <div class="card-body">

      <form method="GET" class="mb-3">
        <div class="input-group w-50">
          <input type="text" name="search" class="form-control" placeholder="Cari nama / NIM..." value="<?= htmlspecialchars($search) ?>"/>
          <button class="btn btn-outline-secondary" type="submit"><i class="bi bi-search"></i></button>
          <?php if ($search): ?>
            <a href="index.php" class="btn btn-outline-danger"><i class="bi bi-x"></i></a>
          <?php endif; ?>
        </div>
      </form>

      <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
          <thead class="table-primary">
            <tr>
              <th>#</th>
              <th>NIM</th>
              <th>Nama</th>
              <th>Prodi</th>
              <th>IPK</th>
              <th>Semester</th>
              <th>Predikat</th>
              <th class="text-center">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php if ($total === 0): ?>
              <tr>
                <td colspan="8" class="text-center text-muted fst-italic">Tidak ada data ditemukan.</td>
              </tr>
            <?php else: ?>
              <?php $no = 1; while ($row = mysqli_fetch_assoc($result)): ?>
              <?php $predikat = getPredikat($row['ipk']); ?>
              <tr>
                <td><?= $no++ ?></td>
                <td><span class="badge bg-secondary"><?= htmlspecialchars($row['nim']) ?></span></td>
                <td><?= htmlspecialchars($row['nama']) ?></td>
                <td><?= htmlspecialchars($row['prodi']) ?></td>
                <td><?= number_format($row['ipk'], 2) ?></td>
                <td><?= htmlspecialchars($row['semester']) ?></td>
                <td><span class="badge <?= $predikat['badge'] ?>"><?= $predikat['teks'] ?></span></td>
                <td class="text-center">
                  <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm me-1">
                    <i class="bi bi-pencil-fill"></i> Edit
                  </a>
                  <a href="hapus.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm"
                     onclick="return confirm('Yakin ingin menghapus data <?= htmlspecialchars($row['nama']) ?>?')">
                    <i class="bi bi-trash-fill"></i> Hapus
                  </a>
                </td>
              </tr>
              <?php endwhile; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

      <small class="text-muted">Total: <?= $total ?> mahasiswa</small>

    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
