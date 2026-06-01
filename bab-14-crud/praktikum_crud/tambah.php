<?php
include_once("config.php");
requireLogin();

if (isset($_POST['submit'])) {
    $nim     = mysqli_real_escape_string($conn, $_POST['nim']);
    $nama    = mysqli_real_escape_string($conn, $_POST['nama']);
    $jurusan = mysqli_real_escape_string($conn, $_POST['jurusan']);
    $email   = mysqli_real_escape_string($conn, $_POST['email']);
    $alamat  = mysqli_real_escape_string($conn, $_POST['alamat']);

    $errors        = [];
    $foto_filename = null;

    if (empty($nim)) {
    $errors[] = "NIM tidak boleh kosong";
    } elseif (!is_numeric($nim)) {
        $errors[] = "NIM hanya boleh berisi angka, tidak boleh mengandung huruf atau simbol";
    } elseif (!preg_match('/^[0-9]{8,12}$/', $nim)) {
        $errors[] = "Panjang NIM harus antara 8 sampai 12 digit";
    }
    if (empty($nama))    $errors[] = "Nama tidak boleh kosong";
    if (empty($jurusan)) $errors[] = "Jurusan tidak boleh kosong";
    if (empty($email))   $errors[] = "Email tidak boleh kosong";
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Format email tidak valid";

    $check_nim = mysqli_query($conn, "SELECT nim FROM mahasiswa WHERE nim = '$nim'");
    if (mysqli_num_rows($check_nim) > 0) $errors[] = "NIM sudah terdaftar";

    if (!empty($_FILES['foto']['name'])) {
        $upload_result = uploadFile($_FILES['foto']);
        if ($upload_result['success']) {
            $foto_filename = $upload_result['filename'];
        } else {
            $errors[] = $upload_result['message'];
        }
    }

    if (empty($errors)) {
        $foto_sql = $foto_filename ? "'$foto_filename'" : "NULL";
        $query = "INSERT INTO mahasiswa (nim, nama, jurusan, email, alamat, foto)
                  VALUES ('$nim', '$nama', '$jurusan', '$email', '$alamat', $foto_sql)";
        if (mysqli_query($conn, $query)) {
            $success = "Data berhasil ditambahkan!";
        } else {
            $errors[] = "Error: " . mysqli_error($conn);
            if ($foto_filename) deleteFile($foto_filename);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Mahasiswa</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; margin: 0; padding: 20px; background-color: #f5f7fa; }
        .container { max-width: 700px; margin: 0 auto; background-color: white; padding: 30px; border-radius: 10px; box-shadow: 0 0 20px rgba(0,0,0,0.1); }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; padding-bottom: 20px; border-bottom: 2px solid #e9ecef; }
        h1 { color: #333; margin: 0; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: 600; color: #495057; }
        input[type="text"], input[type="email"], textarea { width: 100%; padding: 12px; border: 2px solid #e1e5e9; border-radius: 5px; box-sizing: border-box; font-size: 16px; transition: border-color 0.3s; }
        input[type="text"]:focus, input[type="email"]:focus, textarea:focus { outline: none; border-color: #667eea; }
        textarea { height: 100px; resize: vertical; }
        .file-input { opacity: 0; position: absolute; z-index: -1; }
        .file-input-label { display: block; padding: 12px; border: 2px dashed #667eea; border-radius: 5px; text-align: center; cursor: pointer; background-color: #f8f9fa; transition: all 0.3s; }
        .file-input-label:hover { background-color: #e9ecef; }
        .preview-container { margin-top: 10px; text-align: center; }
        .preview-image { max-width: 200px; max-height: 200px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .btn { display: inline-block; padding: 12px 24px; background-color: #667eea; color: white; text-decoration: none; border: none; border-radius: 5px; cursor: pointer; transition: all 0.3s; font-size: 16px; margin-right: 10px; }
        .btn:hover { background-color: #5a67d8; transform: translateY(-2px); }
        .btn-secondary { background-color: #6c757d; } .btn-secondary:hover { background-color: #5a6268; }
        .alert { padding: 15px; border-radius: 5px; margin-bottom: 20px; }
        .alert-danger  { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .alert-success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .form-actions { margin-top: 30px; padding-top: 20px; border-top: 1px solid #e9ecef; }
        .required { color: #dc3545; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>➕ Tambah Mahasiswa</h1>
            <a href="index.php" class="btn btn-secondary">← Kembali</a>
        </div>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <strong>Error:</strong>
                <ul style="margin: 10px 0 0 20px;">
                    <?php foreach ($errors as $error): ?><li><?php echo $error; ?></li><?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if (isset($success)): ?>
            <div class="alert alert-success">
                <strong><?php echo $success; ?></strong><br>
                <a href="index.php">Lihat semua data</a> atau <a href="tambah.php">Tambah data lagi</a>
            </div>
        <?php endif; ?>

        <form action="tambah.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="foto">📷 Foto Profil</label>
                <div style="position: relative;">
                    <input type="file" name="foto" id="foto" class="file-input" accept="image/*">
                    <label for="foto" class="file-input-label">
                        <span>📁 Klik untuk pilih foto atau drag & drop di sini</span>
                        <small style="display: block; margin-top: 5px; color: #6c757d;">Format: JPG, JPEG, PNG, GIF (Max: 5MB)</small>
                    </label>
                </div>
                <div class="preview-container" id="preview" style="display: none;"></div>
            </div>

            <div class="form-group">
                <label for="nim">NIM <span class="required">*</span></label>
                <input type="text" name="nim" id="nim" required value="<?php echo isset($_POST['nim']) ? htmlspecialchars($_POST['nim']) : ''; ?>">
            </div>
            <div class="form-group">
                <label for="nama">Nama Lengkap <span class="required">*</span></label>
                <input type="text" name="nama" id="nama" required value="<?php echo isset($_POST['nama']) ? htmlspecialchars($_POST['nama']) : ''; ?>">
            </div>
            <div class="form-group">
                <label for="jurusan">Jurusan <span class="required">*</span></label>
                <input type="text" name="jurusan" id="jurusan" required value="<?php echo isset($_POST['jurusan']) ? htmlspecialchars($_POST['jurusan']) : ''; ?>">
            </div>
            <div class="form-group">
                <label for="email">Email <span class="required">*</span></label>
                <input type="email" name="email" id="email" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
            </div>
            <div class="form-group">
                <label for="alamat">Alamat</label>
                <textarea name="alamat" id="alamat" placeholder="Masukkan alamat lengkap..."><?php echo isset($_POST['alamat']) ? htmlspecialchars($_POST['alamat']) : ''; ?></textarea>
            </div>

            <div class="form-actions">
                <button type="submit" name="submit" class="btn">💾 Simpan Data</button>
                <a href="index.php" class="btn btn-secondary">❌ Batal</a>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('foto').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('preview');
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = '<img src="' + e.target.result + '" alt="Preview" class="preview-image"><p>Preview foto yang akan diupload</p>';
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                preview.style.display = 'none';
            }
        });
    </script>
</body>
</html>