<?php
include 'CRONS/config.php';


$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$ip_address = $_SERVER['REMOTE_ADDR'];

$current_date = date('Y-m-d');

$stmt = $conn->prepare("SELECT COUNT(*) FROM navstevnost WHERE ipadresa = ? AND DATE(datum) = ?");
if ($stmt === false) {
    error_log('prepare() failed: ' . htmlspecialchars($conn->error));
    die('prepare() failed: ' . htmlspecialchars($conn->error));
}
$stmt->bind_param("ss", $ip_address, $current_date);
$stmt->execute();
$stmt->bind_result($count);
$stmt->fetch();
$stmt->close();


if ($count == 0) {
   
    $date_time = date('Y-m-d H:i:s');

   
    $stmt = $conn->prepare("INSERT INTO navstevnost (ipadresa, datum) VALUES (?, ?)");
    if ($stmt === false) {
        error_log('prepare() failed: ' . htmlspecialchars($conn->error));
        die('prepare() failed: ' . htmlspecialchars($conn->error));
    }
    $stmt->bind_param("ss", $ip_address, $date_time);
    if ($stmt->execute() === false) {
        error_log('execute() failed: ' . htmlspecialchars($stmt->error));
        die('execute() failed: ' . htmlspecialchars($stmt->error));
    }
    $stmt->close();
}

$conn->close();
?>
