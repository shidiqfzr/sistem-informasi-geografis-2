<?php
$host = "localhost"; // Host database
$user = "root";      // Username database
$password = "";      // Password database (default kosong untuk XAMPP)
$dbname = "sig2";  // Nama database

// Membuat koneksi
$conn = new mysqli($host, $user, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Mengatur charset ke UTF-8 untuk mendukung karakter khusus
$conn->set_charset("utf8mb4");
?>