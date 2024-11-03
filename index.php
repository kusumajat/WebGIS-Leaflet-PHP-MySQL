<?php
// Koneksi ke database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dbsleman";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM jumlah_penduduk";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Data Jumlah Penduduk - Peta dan Tabel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <style>
        body {
            background-color: #f5f5f5;
        }

        #map {
            height: 500px;
            width: 100%;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.15);
            border-radius: 12px;
            overflow: hidden;
        }

        .table-container {
            animation: fadeIn 0.6s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .btn-back {
            background-color: #4a90e2;
            color: white;
            border: none;
            font-size: 1rem;
            padding: 8px 20px;
            border-radius: 6px;
            box-shadow: 0px 2px 6px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s ease;
        }

        .btn-back:hover {
            background-color: #357ABD;
            color: #fff;
        }
    </style>
</head>

<body class="bg-light">
    <div class="container py-4">
        <div class="row mb-4">
            <div class="col text-center">
                <h1 class="display-4 fw-bold">WebGIS Sleman</h1>
            </div>
        </div>
        <div class="row">
            <!-- Table Section -->
            <div class="col-md-6">
                <h4 class="display-6 mb-3">Data Jumlah Penduduk</h4>
                <div class="card shadow-sm table-container">
                    <div class="card-body">
                        <div class="table-responsive">
                            <?php
                            if ($result->num_rows > 0) {
                                echo "<table class='table table-hover align-middle mb-0'>
                                    <thead class='table-light'>
                                        <tr>
                                            <th>Kecamatan</th>
                                            <th>Longitude</th>
                                            <th>Latitude</th>
                                            <th>Luas</th>
                                            <th class='text-end'>Jumlah Penduduk</th>
                                            <th class='text-center'>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>";
                            
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>
                                        <td>" . $row["kecamatan"] . "</td>
                                        <td>" . $row["longitude"] . "</td>
                                        <td>" . $row["latitude"] . "</td>
                                        <td>" . $row["luas"] . "</td>
                                        <td class='text-end'>" . number_format($row["jumlah_penduduk"]) . "</td>
                                        <td class='text-center'>
                                            <div class='d-flex justify-content-center gap-2'>
                                                <a href='edit.php?kecamatan=" . urlencode($row["kecamatan"]) . "' class='btn btn-outline-primary btn-sm'>
                                                    <i class='bi bi-pencil'></i> Edit
                                                </a>
                                                <button type='button'
                                                    class='btn btn-outline-danger btn-sm' 
                                                    onclick='confirmDelete(\"" . urlencode($row["kecamatan"]) . "\")'>
                                                    <i class='bi bi-trash'></i> Hapus
                                                </button>
                                            </div>
                                        </td>
                                    </tr>";
                                }
                                echo "</tbody></table>";
                            } else {
                                echo "<div class='alert alert-info'>Tidak ada data yang ditemukan</div>";
                            }
                            ?>
                        </div>
                        <div class="mt-3 d-flex justify-content-end gap-2">
                            <a href="form.html" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> Tambah Data
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Map Section -->
            <div class="col-md-6">
                <h2 class="display-6 mb-3 text-center">Peta Jumlah Penduduk</h2>
                <div id="map"></div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Initialize the map
        var map = L.map('map').setView([-7.691728, 110.375763], 11);

        // Add tile layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Add markers from database
        <?php
        $result->data_seek(0);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $lat = $row["latitude"];
                $long = $row["longitude"];
                $info = $row["kecamatan"];
                $luas = $row["luas"];
                $jmlPenduduk = $row["jumlah_penduduk"];
                echo "L.marker([$lat, $long]).addTo(map)
                      .bindPopup('<b>Kecamatan:</b> $info<br><b>Luas:</b> $luas km²<br><b>Jumlah Penduduk:</b> $jmlPenduduk');\n";
            }
        } else {
            echo "console.log('No data found');";
        }
        ?>

        // Confirmation delete function
        function confirmDelete(kecamatan) {
            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: 'Apakah Anda yakin ingin menghapus data ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'delete.php?kecamatan=' + kecamatan;
                }
            });
        }
    </script>
</body>

</html>

<?php
$conn->close();
?>