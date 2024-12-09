<?php
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_toko = $_POST['namaToko'];
    $alamat = $_POST['alamatToko'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];

    // Query untuk menyimpan data toko
    $sql = "INSERT INTO toko (nama_toko, alamat, lat, lng) VALUES ('$nama_toko', '$alamat', '$latitude', '$longitude')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>
                alert('Toko berhasil ditambahkan');
                window.location.href = 'input_toko.php';
              </script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Toko</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAgGBjlEnlrlO2KdsQMFL70E_Ppo3GmFPs&callback=initMap" async defer></script>
    <style>
        #map {
            height: 400px;
            width: 100%;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="../sig2/index.html">WebGIS</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" href="../sig2/index.html">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../sig2/input_toko.php">Input Toko</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../sig2/input_produk.php">Input Produk</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../sig2/input_transaksi.php">Input Transaksi</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h2>Input Toko</h2>
        
        <!-- Maps Section -->
        <div class="container mt-4">
            <div id="map" style="height: 400px; border: 1px solid #ccc;"></div>
            <div class="d-flex justify-content-between mt-2">
                <button id="current-location-btn" class="btn btn-primary">Pan to Current Location</button>
            </div>
        </div>

        <!-- Form Input Toko -->
        <form action="input_toko.php" method="POST">
            <div class="form-group">
                <label for="namaToko">Nama Toko</label>
                <input type="text" class="form-control" id="namaToko" name="namaToko" required>
            </div>
            <div class="form-group">
                <label for="alamatToko">Alamat Toko</label>
                <input type="text" class="form-control" id="alamatToko" name="alamatToko" required>
            </div>
            <div class="form-group">
                <label for="latitude">Latitude</label>
                <input type="text" class="form-control" id="latitude" name="latitude" readonly required>
            </div>
            <div class="form-group">
                <label for="longitude">Longitude</label>
                <input type="text" class="form-control" id="longitude" name="longitude" readonly required>
            </div>
            <button type="submit" class="btn btn-primary">Simpan Toko</button>
        </form>
    </div>

    <script>
        // Initialize Map
        let map;
        let userLocation;
        let marker;
        let infowindow;

        // Inisialisasi peta
        function initMap() {
            map = new google.maps.Map(document.getElementById("map"), {
                center: { lat: -0.0291, lng: 109.334 }, // Lokasi awal (Pontianak)
                zoom: 16, // Zoom level
            });

            // Initialize InfoWindow
            infowindow = new google.maps.InfoWindow();

            // Tambahkan event listener klik pada peta
            google.maps.event.addListener(map, 'click', function(event) {
                const lat = event.latLng.lat();
                const lng = event.latLng.lng();

                // Set marker pada lokasi yang diklik
                if (marker) {
                    marker.setPosition(event.latLng); // Move existing marker
                } else {
                    marker = new google.maps.Marker({
                        position: event.latLng,
                        map: map,
                        title: "Lokasi yang diklik",
                    });
                }

                // Menampilkan latitude dan longitude pada form
                document.getElementById('latitude').value = lat;
                document.getElementById('longitude').value = lng;

                // Tampilkan InfoWindow
                infowindow.setContent(`Latitude: ${lat} <br> Longitude: ${lng}`);
                infowindow.open(map, marker);
            });
        }

        // Pan to Current Location
        document.getElementById("current-location-btn").addEventListener("click", () => {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition((position) => {
                    userLocation = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude,
                    };

                    // Set map center to current location
                    map.setCenter(userLocation);

                    // Add or move marker to current location
                    if (marker) {
                        marker.setPosition(userLocation); // Move existing marker
                    } else {
                        marker = new google.maps.Marker({
                            position: userLocation,
                            map: map,
                            title: "Lokasi Anda saat ini",
                        });
                    }

                    // Show InfoWindow with text "Lokasi Anda saat ini"
                    infowindow.setContent("Lokasi Anda saat ini");
                    infowindow.open(map, marker);
                });
            } else {
                alert("Geolocation is not supported by this browser.");
            }
        });

        // Pastikan peta diinisialisasi setelah Google Maps API dimuat
        window.initMap = initMap;
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>