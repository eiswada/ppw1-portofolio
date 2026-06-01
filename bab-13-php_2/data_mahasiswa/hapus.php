<?php
require 'koneksi.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    mysqli_query($conn, "DELETE FROM mahasiswa WHERE id = $id");
}

header('Location: index.php?pesan=hapus');
exit;
