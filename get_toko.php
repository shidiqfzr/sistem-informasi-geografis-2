<?php
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $latitude = isset($_GET['latitude']) ? $_GET['latitude'] : null;
    $longitude = isset($_GET['longitude']) ? $_GET['longitude'] : null;
    $radius = isset($_GET['radius']) ? $_GET['radius'] : null;

    if ($latitude && $longitude && $radius) {
        // Query untuk toko dalam radius
        $sql = "SELECT *, 
                (6371 * ACOS(COS(RADIANS($latitude)) * COS(RADIANS(latitude)) * 
                COS(RADIANS(longitude) - RADIANS($longitude)) + 
                SIN(RADIANS($latitude)) * SIN(RADIANS(latitude)))) AS distance
                FROM toko
                HAVING distance <= $radius
                ORDER BY distance";
    } else {
        // Query untuk semua toko
        $sql = "SELECT * FROM toko";
    }

    $result = $conn->query($sql);
    $stores = [];

    while ($row = $result->fetch_assoc()) {
        $stores[] = $row;
    }

    echo json_encode($stores);
}

$conn->close();
?>
