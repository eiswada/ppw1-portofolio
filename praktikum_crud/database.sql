-- Buat database
CREATE DATABASE IF NOT EXISTS praktikum_crud;
USE praktikum_crud;

-- Buat tabel mahasiswa
CREATE TABLE IF NOT EXISTS mahasiswa (
    id       INT AUTO_INCREMENT PRIMARY KEY,
    nim      VARCHAR(20)  NOT NULL UNIQUE,
    nama     VARCHAR(100) NOT NULL,
    prodi    VARCHAR(100) NOT NULL,
    ipk      DECIMAL(3,2) NOT NULL,
    semester TINYINT      NOT NULL,
    foto     VARCHAR(255) DEFAULT NULL
);

-- Buat tabel users untuk sistem login
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert admin default (password: admin123)
INSERT IGNORE INTO users (username, email, password, full_name) 
VALUES ('admin', 'admin@localhost.com', 
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 
        'Administrator');