<?php
$hasil = null;
$error = null;

function tambah($a, $b) { return $a + $b; }
function kurang($a, $b) { return $a - $b; }
function kali($a, $b)   { return $a * $b; }
function bagi($a, $b) {
    if ($b == 0) return null;
    return $a / $b;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $a = (float) $_POST['angka1'];
    $b = (float) $_POST['angka2'];
    $operasi = $_POST['operasi'];

    if ($operasi === '+')     $hasil = tambah($a, $b);
    elseif ($operasi === '-') $hasil = kurang($a, $b);
    elseif ($operasi === '*') $hasil = kali($a, $b);
    elseif ($operasi === '/') {
        if ($b == 0) $error = "Tidak bisa dibagi 0!";
        else $hasil = bagi($a, $b);
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kalkulator PHP + JS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex justify-content-center align-items-center vh-100">

<div class="card p-4 shadow" style="width: 350px;">
    <h4 class="card-title text-center mb-4">Kalkulator Sederhana</h4>

    <form method="POST" id="formKalkulator">
        <div class="mb-3">
            <label class="form-label">Bilangan Pertama</label>
            <input type="number" name="angka1" id="angka1" class="form-control" placeholder="Masukkan angka..."
                value="<?= isset($_POST['angka1']) ? $_POST['angka1'] : '' ?>">
            <div class="invalid-feedback">Angka pertama wajib diisi!</div>
        </div>

        <div class="mb-3">
            <label class="form-label">Bilangan Kedua</label>
            <input type="number" name="angka2" id="angka2" class="form-control" placeholder="Masukkan angka..."
                value="<?= isset($_POST['angka2']) ? $_POST['angka2'] : '' ?>">
            <div class="invalid-feedback" id="pesanAngka2">Angka kedua wajib diisi!</div>
        </div>

        <div class="row g-2 mb-3">
            <div class="col-6">
                <button type="submit" name="operasi" value="+" class="btn btn-success w-100">Tambah (+)</button>
            </div>
            <div class="col-6">
                <button type="submit" name="operasi" value="-" class="btn btn-warning w-100">Kurang (−)</button>
            </div>
            <div class="col-6">
                <button type="submit" name="operasi" value="*" class="btn btn-info w-100">Kali (×)</button>
            </div>
            <div class="col-6">
                <button type="submit" name="operasi" value="/" class="btn btn-danger w-100">Bagi (÷)</button>
            </div>
        </div>
    </form>

    <div class="alert <?= $error ? 'alert-danger' : 'alert-primary' ?> text-center mb-0">
        <?php if ($error): ?>
            <?= $error ?>
        <?php elseif ($hasil !== null): ?>
            Hasil: <strong><?= $hasil ?></strong>
        <?php else: ?>
            Hasil: <strong>-</strong>
        <?php endif; ?>
    </div>
</div>

<script>
    document.getElementById('formKalkulator').addEventListener('submit', function(e) {
        const angka1 = document.getElementById('angka1');
        const angka2 = document.getElementById('angka2');
        const pesanAngka2 = document.getElementById('pesanAngka2');
        const operasiKlik = e.submitter ? e.submitter.value : '';
        let valid = true;

        if (angka1.value === '') {
            angka1.classList.add('is-invalid');
            valid = false;
        } else {
            angka1.classList.remove('is-invalid');
        }

        if (angka2.value === '') {
            pesanAngka2.textContent = 'Angka kedua wajib diisi!';
            angka2.classList.add('is-invalid');
            valid = false;
        } else if (operasiKlik === '/' && parseFloat(angka2.value) === 0) {
            pesanAngka2.textContent = 'Tidak bisa dibagi 0!';
            angka2.classList.add('is-invalid');
            valid = false;
        } else {
            angka2.classList.remove('is-invalid');
        }

        if (!valid) e.preventDefault();
    });
</script>

</body>
</html>