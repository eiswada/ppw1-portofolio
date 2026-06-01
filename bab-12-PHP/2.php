<?php
function hitungIMT($berat, $tinggi)
{
     $tinggiMeter = $tinggi / 100;
     $imt = $berat / ($tinggiMeter * $tinggiMeter);
     if ($imt < 18.5) {
          $kategori = "Kurus";
     } elseif ($imt < 25.0) {
          $kategori = "Normal";
     } elseif ($imt < 30.0) {
          $kategori = "Gemuk";
     } else {
          $kategori = "Obesitas";
     }
     return [
          "nilai"    => round($imt, 1),
          "kategori" => $kategori
     ];
}

$hasil = null;
$error = null;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
     $berat  = $_POST["berat"];
     $tinggi = $_POST["tinggi"];
     if ($berat > 0 && $tinggi > 0) {
          $hasil = hitungIMT($berat, $tinggi);
     } else {
          $error = "Masukkan berat dan tinggi yang valid.";
     }
}

$warnaBadge = [
     "Kurus"    => "info",
     "Normal"   => "success",
     "Gemuk"    => "warning",
     "Obesitas" => "danger",
];

$posisi = [
     "Kurus"    => 12,
     "Normal"   => 37,
     "Gemuk"    => 62,
     "Obesitas" => 87,
];
?>
<!DOCTYPE html>
<html lang="id">

<head>
     <meta charset="UTF-8">
     <title>Kalkulator IMT</title>
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light py-5">

     <div class="container" style="max-width: 400px;">
          <h4 class="text-center mb-4">Kalkulator IMT</h4>

          <div class="card shadow-sm mb-3">
               <div class="card-body">
                    <form method="POST" action="">
                         <div class="mb-3">
                              <label class="form-label">Berat Badan (kg)</label>
                              <input type="number" name="berat" class="form-control" placeholder="Contoh: 65" min="1" step="0.1"
                                   value="<?= isset($_POST['berat']) ? htmlspecialchars($_POST['berat']) : '' ?>">
                         </div>
                         <div class="mb-3">
                              <label class="form-label">Tinggi Badan (cm)</label>
                              <input type="number" name="tinggi" class="form-control" placeholder="Contoh: 170" min="1" step="0.1"
                                   value="<?= isset($_POST['tinggi']) ? htmlspecialchars($_POST['tinggi']) : '' ?>">
                         </div>
                         <button type="submit" class="btn btn-dark w-100">Hitung IMT</button>
                    </form>
               </div>
          </div>

          <?php if ($error): ?>
               <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
          <?php endif; ?>

          <?php if ($hasil): ?>
               <?php
               $badge = $warnaBadge[$hasil['kategori']];
               $pct   = $posisi[$hasil['kategori']];
               ?>
               <div class="card shadow-sm">
                    <div class="card-header bg-dark text-white text-center py-4">
                         <div style="font-size: 56px; font-weight: bold; line-height: 1;"><?= $hasil['nilai'] ?></div>
                         <div class="text-secondary small">kg/m²</div>
                         <span class="badge bg-<?= $badge ?> mt-2 fs-6"><?= $hasil['kategori'] ?></span>
                    </div>

                    <div class="card-body">
                         <table class="table table-borderless mb-3">
                              <tr>
                                   <td class="text-muted">Berat Badan</td>
                                   <td class="fw-bold text-end"><?= htmlspecialchars($_POST['berat']) ?> kg</td>
                              </tr>
                              <tr>
                                   <td class="text-muted">Tinggi Badan</td>
                                   <td class="fw-bold text-end"><?= htmlspecialchars($_POST['tinggi']) ?> cm</td>
                              </tr>
                              <tr>
                                   <td class="text-muted">Nilai IMT</td>
                                   <td class="fw-bold text-end"><?= $hasil['nilai'] ?> kg/m²</td>
                              </tr>
                              <tr>
                                   <td class="text-muted">Kategori</td>
                                   <td class="fw-bold text-end"><?= $hasil['kategori'] ?></td>
                              </tr>
                         </table>

                         <p class="text-muted small mb-1">Posisi IMT kamu</p>
                         <div class="progress mb-1" style="height: 8px;">
                              <div class="progress-bar bg-dark" style="width: <?= $pct ?>%;"></div>
                         </div>
                         <div class="d-flex justify-content-between">
                              <small class="text-muted">Kurus</small>
                              <small class="text-muted">Normal</small>
                              <small class="text-muted">Gemuk</small>
                              <small class="text-muted">Obesitas</small>
                         </div>
                    </div>
               </div>
          <?php endif; ?>
     </div>

</body>

</html>