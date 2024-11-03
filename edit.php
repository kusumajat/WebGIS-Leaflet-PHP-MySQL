<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dbsleman";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch existing data
$kecamatan = urldecode($_GET['kecamatan']);
$sql = "SELECT * FROM jumlah_penduduk WHERE kecamatan = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $kecamatan);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Update the data
    $newLongitude = $_POST['longitude'];
    $newLatitude = $_POST['latitude'];
    $newLuas = $_POST['luas'];
    $newJumlahPenduduk = $_POST['jumlah_penduduk'];

    $updateSql = "UPDATE jumlah_penduduk SET longitude = ?, latitude = ?, luas = ?, jumlah_penduduk = ? WHERE kecamatan = ?";
    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->bind_param("dddis", $newLongitude, $newLatitude, $newLuas, $newJumlahPenduduk, $kecamatan);
    
    if ($updateStmt->execute()) {
        header("Location: index.php");
        exit();
    } else {
        echo "<div class='alert alert-danger'>Update failed: " . $conn->error . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Data Kecamatan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-4">
        <h2 class="mb-4">Edit Data Kecamatan: <?= htmlspecialchars($row['kecamatan']) ?></h2>
        <form method="post">
            <div class="mb-3">
                <label for="longitude" class="form-label">Longitude</label>
                <input type="text" class="form-control" name="longitude" value="<?= htmlspecialchars($row['longitude']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="latitude" class="form-label">Latitude</label>
                <input type="text" class="form-control" name="latitude" value="<?= htmlspecialchars($row['latitude']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="luas" class="form-label">Luas</label>
                <input type="text" class="form-control" name="luas" value="<?= htmlspecialchars($row['luas']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="jumlah_penduduk" class="form-label">Jumlah Penduduk</label>
                <input type="number" class="form-control" name="jumlah_penduduk" value="<?= htmlspecialchars($row['jumlah_penduduk']) ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="index.php" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</body>
</html>

<?php
$conn->close();
?>
