<?php
$namaBulan    = date('F');          
$hariIni      = (int) date('j');    
$totalHari    = (int) date('t');    
$hariTersisa  = $totalHari - $hariIni;
$tahun        = date('Y');
$tanggalHari  = date('d-m-Y');

$terjemahan = [
    'January'   => 'Januari',
    'February'  => 'Februari',
    'March'     => 'Maret',
    'April'     => 'April',
    'May'       => 'Mei',
    'June'      => 'Juni',
    'July'      => 'Juli',
    'August'    => 'Agustus',
    'September' => 'September',
    'October'   => 'Oktober',
    'November'  => 'November',
    'December'  => 'Desember',
];

$namaBulanID = $terjemahan[$namaBulan];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Info Bulan Sekarang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex justify-content-center align-items-center vh-100">

    <div class="card shadow p-4" style="max-width: 400px; width: 100%;">
        <h4 class="card-title text-center mb-1">Kalender</h4>
        <p class="text-center text-muted mb-4">Tanggal hari ini: <strong><?= $tanggalHari ?></strong></p>

                <div class="alert alert-primary">
            <small class="text-uppercase text-muted">Nama Bulan</small>
            <div class="fs-4 fw-bold"><?= $namaBulanID ?> <?= $tahun ?></div>
        </div>

               <div class="alert alert-success">
            <small class="text-uppercase text-muted">Hari Ke- / Total Hari</small>
            <div class="fs-4 fw-bold">Hari ke-<?= $hariIni ?> dari <?= $totalHari ?> hari</div>
        </div>

             <div class="alert alert-warning mb-0">
            <small class="text-uppercase text-muted">Sisa Hari di Bulan Ini</small>
            <div class="fs-4 fw-bold">
                <?php if ($hariTersisa === 0): ?>
                    Hari ini hari terakhir! 
                <?php else: ?>
                    <?= $hariTersisa ?> hari lagi
                <?php endif; ?>
            </div>
        </div>
    </div>

</body>
</html>