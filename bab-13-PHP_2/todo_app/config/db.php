<?php

define('DB_HOST', 'localhost');
define('DB_USER', 'root');        
define('DB_PASS', '');         
define('DB_NAME', 'todo_db');

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
$conn->set_charset('utf8mb4');

if ($conn->connect_error) {
    die('<div class="alert alert-danger m-4">Koneksi gagal: ' . $conn->connect_error . '</div>');
}
