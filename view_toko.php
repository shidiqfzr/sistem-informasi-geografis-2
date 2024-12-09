<?php
include 'koneksi.php';

$sql = "SELECT * FROM toko";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Tampilkan data toko
    while($row = $result->fetch_assoc()) {
        echo "Nama Toko: " . $row["nama_toko"] . " - Alamat: " . $row["alamat"] . "<br>";
    }
} else {
    echo "Tidak ada toko ditemukan.";
}

$conn->close();
?>
