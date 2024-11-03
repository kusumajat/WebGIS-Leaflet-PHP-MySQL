<?php
$kecamatan = $_POST['kecamatan'];
$longitude = $_POST['longitude'];
$latitude = $_POST['latitude'];
$luas = $_POST['luas'];
$jumlah_penduduk = $_POST['jumlah_penduduk'];

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
$sql = "INSERT INTO jumlah_penduduk (kecamatan, longitude, latitude, luas, jumlah_penduduk)
VALUES ('$kecamatan', '$longitude', '$latitude', $luas, $jumlah_penduduk)";
if ($conn->query($sql) === TRUE) {
    echo "Data berhasil ditambahkan";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
$conn->close();

//exit;
