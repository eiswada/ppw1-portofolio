CREATE DATABASE IF NOT EXISTS db_mahasiswa;

USE db_mahasiswa;

CREATE TABLE IF NOT EXISTS mahasiswa (
    id       INT AUTO_INCREMENT PRIMARY KEY,
    nim      VARCHAR(20)  NOT NULL UNIQUE,
    nama     VARCHAR(100) NOT NULL,
    prodi    VARCHAR(100) NOT NULL,
    ipk      DECIMAL(3,2) NOT NULL,
    semester TINYINT      NOT NULL
);
