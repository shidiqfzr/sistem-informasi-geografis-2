<?php
include 'koneksi.php';

// Fetch nama toko untuk dropdown
$query = "SELECT id_toko, nama_toko FROM toko";
$result = $conn->query($query);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_produk = $_POST['idProduk'];
    $id_toko = $_POST['namaToko'];
    $harga = $_POST['hargaProduk'];
    $jumlah = $_POST['jumlahProduk'];

    // Insert data ke tabel transaksi
    $sql = "INSERT INTO transaksi (id_produk, id_toko, harga, jumlah, waktu) 
            VALUES ('$id_produk', '$id_toko', '$harga', '$jumlah', CURRENT_TIMESTAMP)";

    if ($conn->query($sql) === TRUE) {
        echo "<script>
                alert('Transaksi berhasil ditambahkan');
                window.location.href = 'input_transaksi.php';
              </script>";
    } else {
        echo "<script>
                alert('Error: " . $conn->error . "');
              </script>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Transaksi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/html5-qrcode/minified/html5-qrcode.min.js"></script>
    <style>
        .container {
            max-width: 800px;
        }

        #qr-reader-transaksi {
            width: 100%;
            max-width: 400px;
            margin: 0 auto;
            border: 1px solid #ccc;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .btn {
            margin-top: 1rem;
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
                    <li class="nav-item"><a class="nav-link active" href="../sig2/index.html">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="../sig2/input_toko.php">Input Toko</a></li>
                    <li class="nav-item"><a class="nav-link" href="../sig2/input_produk.php">Input Produk</a></li>
                    <li class="nav-item"><a class="nav-link" href="../sig2/input_transaksi.php">Input Transaksi</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mt-5">
        <h2 class="mb-4">Input Transaksi</h2>

        <!-- QR Code Scanner -->
        <div id="qr-reader-transaksi"></div>
        <div class="text-center mt-2">
            <button id="start-btn-transaksi" class="btn btn-success">Start Scanner</button>
            <button id="stop-btn-transaksi" class="btn btn-danger">Stop Scanner</button>
        </div>

        <!-- Form Input Transaksi -->
        <form action="input_transaksi.php" method="POST" class="shadow p-4 rounded bg-light mt-4">
            <div class="form-group">
                <label for="idProduk">ID Produk</label>
                <input type="text" class="form-control" id="idProduk" name="idProduk" readonly required>
            </div>
            <div class="form-group">
                <label for="namaToko">Nama Toko</label>
                <select class="form-control" id="namaToko" name="namaToko" required>
                    <option value="">Pilih Toko</option>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <option value="<?= $row['id_toko'] ?>"><?= htmlspecialchars($row['nama_toko']) ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group">
                <label for="hargaProduk">Harga Satuan Produk</label>
                <input type="number" class="form-control" id="hargaProduk" name="hargaProduk" required>
            </div>
            <div class="form-group">
                <label for="jumlahProduk">Jumlah Produk</label>
                <input type="number" class="form-control" id="jumlahProduk" name="jumlahProduk" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Simpan Transaksi</button>
        </form>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let scanner = null;
            const productIdField = document.getElementById('idProduk');
            const startBtn = document.getElementById('start-btn-transaksi');
            const stopBtn = document.getElementById('stop-btn-transaksi');
            const qrReaderContainer = "qr-reader-transaksi";

            const startScanner = () => {
                if (!scanner) {
                    scanner = new Html5Qrcode(qrReaderContainer); // Initialize scanner
                }

                scanner.start(
                    { facingMode: "environment" },
                    { fps: 10, qrbox: { width: 250, height: 250 } },
                    (decodedText, decodedResult) => {
                        productIdField.value = decodedText; // Populate the input with scanned text
                        alert("QR Code detected: " + decodedText);
                    },
                    (errorMessage) => {
                        console.log("Scanning error: ", errorMessage);
                    }
                ).catch(err => {
                    console.error("Failed to start scanner: ", err);
                    alert("Cannot access the camera. Please check camera permissions.");
                });
            };

            const stopScanner = () => {
                if (scanner) {
                    scanner.stop().then(() => {
                        console.log("Scanner stopped.");
                    }).catch(err => {
                        console.error("Failed to stop scanner: ", err);
                    });
                }
            };

            startBtn.addEventListener('click', startScanner);
            stopBtn.addEventListener('click', stopScanner);
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
