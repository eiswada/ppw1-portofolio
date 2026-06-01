<?php
$nilai = null;
$grade = null;
$deskripsi = null;
$badge = null;
$alert = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = $_POST['nilai'] ?? '';

    if (!is_numeric($input) || $input === '') {
        $error = 'Masukkan angka yang valid.';
    } else {
        $nilai = (int)$input;

        if ($nilai < 0 || $nilai > 100) {
            $error = 'Nilai harus berada di antara 0 sampai 100.';
        } elseif ($nilai >= 85) {
            $grade = 'A'; $deskripsi = 'Sangat Baik — Luar biasa! Pertahankan prestasi anda.';
            $badge = 'bg-success'; $alert = 'alert-success';
        } elseif ($nilai >= 70) {
            $grade = 'B'; $deskripsi = 'Baik — Hasil yang bagus, terus tingkatkan!';
            $badge = 'bg-primary'; $alert = 'alert-primary';
        } elseif ($nilai >= 55) {
            $grade = 'C'; $deskripsi = 'Cukup — Perlu lebih banyak belajar dan berlatih.';
            $badge = 'bg-warning text-dark'; $alert = 'alert-warning';
        } elseif ($nilai >= 40) {
            $grade = 'D'; $deskripsi = 'Kurang — Nilai di bawah standar, perlu perbaikan serius.';
            $badge = 'bg-secondary'; $alert = 'alert-secondary';
        } else {
            $grade = 'E'; $deskripsi = 'Sangat Kurang — Tidak memenuhi syarat kelulusan.';
            $badge = 'bg-danger'; $alert = 'alert-danger';
        }
    }
}

$step = $grade ? 2 : 1;
$pct  = $grade ? 100 : 50;
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Konversi Nilai</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet"/>
</head>
<body class="bg-light">
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card shadow-sm">

        <div class="card-header bg-white pt-4 pb-0 border-0">
          <div class="d-flex justify-content-between align-items-center mb-3 px-1">

            <div class="text-center flex-fill">
              <span class="badge rounded-pill bg-primary mb-1">1</span>
              <div class="small text-primary fw-semibold">Input Nilai</div>
            </div>

            <div class="flex-fill">
              <hr class="border-2 <?= $grade ? 'border-primary' : 'border-secondary' ?>"/>
            </div>

            <div class="text-center flex-fill">
              <span class="badge rounded-pill <?= $grade ? 'bg-primary' : 'bg-secondary' ?> mb-1">2</span>
              <div class="small <?= $grade ? 'text-primary fw-semibold' : 'text-muted' ?>">Hasil</div>
            </div>

          </div>
          <div class="progress mb-0 rounded-0" style="height:4px">
            <div class="progress-bar bg-primary" style="width:<?= $pct ?>%"></div>
          </div>
        </div>

        <div class="card-body p-4">

          <?php if (!$grade): ?>

          <h5 class="fw-bold mb-1">Masukkan Nilai</h5>
          <p class="text-muted small mb-4">Masukkan nilai angka antara 0 hingga 100.</p>

          <form method="POST">
            <div class="mb-4">
              <label class="form-label fw-semibold">Nilai Akhir</label>
              <input
                type="number"
                name="nilai"
                class="form-control form-control-lg text-center <?= $error ? 'is-invalid' : '' ?>"
                placeholder="0 – 100"
                min="0" max="100"
                value="<?= htmlspecialchars($_POST['nilai'] ?? '') ?>"
                required
              />
              <?php if ($error): ?>
                <div class="invalid-feedback"><?= $error ?></div>
              <?php endif; ?>
            </div>
            <div class="d-grid">
              <button type="submit" class="btn btn-primary btn-lg">
                Konversi <i class="bi bi-arrow-right ms-1"></i>
              </button>
            </div>
          </form>

          <?php else: ?>

          <h5 class="fw-bold mb-1">Berikut Hasil Konversi Nilai Anda</h5>

          <div class="alert <?= $alert ?> text-center mb-4" role="alert">
            <span class="badge <?= $badge ?> fs-1 px-4 py-3 mb-3 d-inline-block"><?= $grade ?></span>
            <h5 class="fw-bold mb-1">Nilai: <?= $nilai ?></h5>
            <p class="mb-0"><?= $deskripsi ?></p>
          </div>

          <table class="table table-bordered table-sm text-center small mb-4">
            <thead class="table-dark">
              <tr><th>Grade</th><th>Rentang</th><th>Keterangan</th></tr>
            </thead>
            <tbody>
              <tr class="<?= $grade === 'A' ? 'table-success fw-bold' : '' ?>"><td>A</td><td>85–100</td><td>Sangat Baik</td></tr>
              <tr class="<?= $grade === 'B' ? 'table-primary fw-bold' : '' ?>"><td>B</td><td>70–84</td><td>Baik</td></tr>
              <tr class="<?= $grade === 'C' ? 'table-warning fw-bold' : '' ?>"><td>C</td><td>55–69</td><td>Cukup</td></tr>
              <tr class="<?= $grade === 'D' ? 'table-secondary fw-bold' : '' ?>"><td>D</td><td>40–54</td><td>Kurang</td></tr>
              <tr class="<?= $grade === 'E' ? 'table-danger fw-bold' : '' ?>"><td>E</td><td>0–39</td><td>Sangat Kurang</td></tr>
            </tbody>
          </table>

          <form method="POST">
            <div class="d-grid">
              <button type="submit" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali
              </button>
            </div>
          </form>

          <?php endif; ?>

        </div>
      </div>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>