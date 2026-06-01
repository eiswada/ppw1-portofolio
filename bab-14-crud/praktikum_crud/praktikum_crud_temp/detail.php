<?php
include_once("config.php");
requireLogin();

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id     = (int)$_GET['id'];
$result = mysqli_query($conn, "SELECT * FROM mahasiswa WHERE id=$id");

if (mysqli_num_rows($result) == 0) {
    header("Location: index.php");
    exit();
}

$row = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Mahasiswa</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; padding: 20px; background-color: #f5f7fa; }
        .container { max-width: 700px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 0 20px rgba(0,0,0,0.1); }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; padding-bottom: 20px; border-bottom: 2px solid #e9ecef; }
        h1 { color: #333; margin: 0; }
        .foto-wrapper { text-align: center; margin-bottom: 30px; }
        .foto-wrapper img { width: 220px; height: 220px; object-fit: cover; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.15); }
        .no-foto { width: 220px; height: 220px; background: #e9ecef; border-radius: 15px; display: inline-flex; align-items: center; justify-content: center; font-size: 60px; color: #adb5bd; }
        .info-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .info-table tr { border-bottom: 1px solid #e9ecef; }
        .info-table td { padding: 14px 10px; font-size: 15px; }
        .info-table td:first-child { font-weight: 600; color: #495057; width: 160px; }
        .info-table td:last-child { color: #333; }
        .btn { display: inline-block; padding: 10px 22px; color: white; text-decoration: none; border-radius: 5px; font-size: 15px; margin-right: 10px; }
        .btn-back  { background: #6c757d; }
        .btn-edit  { background: #667eea; }
        .btn:hover { opacity: 0.9; }
        .actions { margin-top: 30px; padding-top: 20px; border-top: 1px solid #e9ecef; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>🎓 Detail Mahasiswa</h1>
        <a href="index.php" class="btn btn-back">← Kembali</a>
    </div>

    <div class="foto-wrapper">
        <?php if ($row['foto'] && file_exists("uploads/mahasiswa/" . $row['foto'])): ?>
            <img src="uploads/mahasiswa/<?php echo htmlspecialchars($row['foto']); ?>" alt="Foto <?php echo htmlspecialchars($row['nama']); ?>">
        <?php else: ?>
            <div class="no-foto">👤</div>
        <?php endif; ?>
    </div>

    <table class="info-table">
        <tr><td>NIM</td><td><?php echo htmlspecialchars($row['nim']); ?></td></tr>
        <tr><td>Nama Lengkap</td><td><?php echo htmlspecialchars($row['nama']); ?></td></tr>
        <tr><td>Jurusan</td><td><?php echo htmlspecialchars($row['jurusan']); ?></td></tr>
        <tr><td>Email</td><td><?php echo htmlspecialchars($row['email']); ?></td></tr>
        <tr><td>Alamat</td><td><?php echo nl2br(htmlspecialchars($row['alamat'] ?? '-')); ?></td></tr>
        <tr><td>Tanggal Daftar</td><td><?php echo isset($row['created_at']) ? date('d F Y, H:i', strtotime($row['created_at'])) : '-'; ?></td></tr>
    </table>

    <div class="actions">
        <a href="index.php" class="btn btn-back">← Kembali ke Daftar</a>
        <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-edit">✏️ Edit Data</a>
    </div>
</div>
</body>
</html>