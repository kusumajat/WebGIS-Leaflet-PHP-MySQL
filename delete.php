<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dbsleman";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Mengambil parameter kecamatan dari URL
if (isset($_GET['kecamatan'])) {
    $kecamatan = $conn->real_escape_string($_GET['kecamatan']);

    // Query untuk menghapus data
    $sql = "DELETE FROM jumlah_penduduk WHERE kecamatan = '$kecamatan'";

    if ($conn->query($sql) === TRUE) {
        echo "<script>
                alert('Data berhasil dihapus');
                window.location.href = 'index.php';
            </script>";
    } else {
        echo "<script>
                alert('Error: " . $conn->error . "');
                window.location.href = 'index.php';
            </script>";
    }
} else {
    echo "<script>
            alert('Parameter kecamatan tidak ditemukan');
            window.location.href = 'index.php';
        </script>";
}

$conn->close();
