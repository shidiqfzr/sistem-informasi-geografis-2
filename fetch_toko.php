<?php
include 'koneksi.php'; // Ganti sesuai dengan file koneksi database Anda

$sql = "SELECT id_toko, nama_toko, alamat, lat, lng FROM toko";
$result = $conn->query($sql);

$toko = [];
while ($row = $result->fetch_assoc()) {
    $toko[] = $row;
}

header('Content-Type: application/json');
echo json_encode($toko);
?>
