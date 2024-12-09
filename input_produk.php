<?php
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_produk = $_POST['idProduk'];
    $nama_produk = $_POST['namaProduk'];
    $kategori = $_POST['kategori'];

    // Query untuk menyimpan data produk
    $sql = "INSERT INTO produk (id_produk, nama_produk, kategori) VALUES ('$id_produk', '$nama_produk', '$kategori')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>
                alert('Produk berhasil ditambahkan');
                window.location.href = 'input_produk.php';
              </script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/html5-qrcode/minified/html5-qrcode.min.js"></script>
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
        <h2>Input Produk</h2>

        <!-- QR Code Scanner -->
        <div class="container mt-4">
            <div id="qr-reader" style="width: 100%; max-width: 400px; height: auto; margin: 0 auto; border: 1px solid #ccc;"></div>
            <div class="mt-2 text-center">
                <button id="start-btn" class="btn btn-success">Start Scanner</button>
                <button id="stop-btn" class="btn btn-danger">Stop Scanner</button>
            </div>
        </div>

        <!-- Form Input Produk -->
        <form action="input_produk.php" method="POST">
            <div class="form-group">
                <label for="idProduk">ID Produk</label>
                <input type="text" class="form-control" id="idProduk" name="idProduk" readonly required>
            </div>
            <div class="form-group">
                <label for="namaProduk">Nama Produk</label>
                <input type="text" class="form-control" id="namaProduk" name="namaProduk" required>
            </div>
            <div class="form-group">
                <label for="kategori">Kategori</label>
                <select class="form-control" id="kategori" name="kategori" required>
                    <option value="Elektronik">Elektronik</option>
                    <option value="Pakaian">Pakaian</option>
                    <option value="Makanan">Makanan</option>
                    <option value="Perabot">Perabot</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Simpan Produk</button>
        </form>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let scanner = null;
            const productIdField = document.getElementById('idProduk');
            const startBtn = document.getElementById('start-btn');
            const stopBtn = document.getElementById('stop-btn');
            const qrReaderContainer = "qr-reader";

            // Initialize scanner only when needed
            startBtn.addEventListener('click', () => {
                if (!scanner) {
                    scanner = new Html5Qrcode(qrReaderContainer);
                }

                // Start the scanner
                scanner.start({
                        facingMode: "environment"
                    }, // Use back-facing camera
                    {
                        fps: 10, // Frames per second
                        qrbox: {
                            width: 250,
                            height: 250
                        } // Scanner area size
                    },
                    (decodedText, decodedResult) => {
                        productIdField.value = decodedText; // Populate the input with the scanned text
                        alert("QR Code detected: " + decodedText); // Notify user of detected QR code
                    },
                    (errorMessage) => {
                        // Optional: Log scanning errors
                        console.log("Scanning error: ", errorMessage);
                    }
                ).catch(err => {
                    console.error("Failed to start QR scanner: ", err);
                });
            });

            // Stop scanner
            stopBtn.addEventListener('click', () => {
                if (scanner) {
                    scanner.stop().then(() => {
                        console.log("Scanner stopped.");
                    }).catch(err => {
                        console.error("Failed to stop QR scanner: ", err);
                    });
                }
            });
        });
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>