<?php
$nama       = "Raya Iswada";
$nim        = "25/557089/SV/26097";
$prodi      = "Teknologi Rekayasa Perangkat Lunak";
$asal_kota  = "Kota Pekalongan";
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Profil Mahasiswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light d-flex justify-content-center mt-5">
    <div class="card shadow p-4" style="width: 400px;">
        <h2 class="text-center mb-4 fw-bold text-dark">Profil Mahasiswa</h2>
        <table class="table table-bordered mb-0">
            <tr>
                <td class="fw-bold bg-primary-subtle" style="width: 40%;">Nama</td>
                <td><?php echo $nama; ?></td>
            </tr>
            <tr>
                <td class="fw-bold bg-primary-subtle">NIM</td>
                <td><?php echo $nim; ?></td>
            </tr>
            <tr>
                <td class="fw-bold bg-primary-subtle">Program Studi</td>
                <td><?php echo $prodi; ?></td>
            </tr>
            <tr>
                <td class="fw-bold bg-primary-subtle">Asal Kota</td>
                <td><?php echo $asal_kota; ?></td>
            </tr>
        </table>
    </div>
</body>

</html>