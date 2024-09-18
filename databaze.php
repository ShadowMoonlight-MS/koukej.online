<?php
include 'CRONS/config.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$data = json_decode(file_get_contents('php://input'), true);
if (json_last_error() !== JSON_ERROR_NONE) {
    die("JSON decode error: " . json_last_error_msg());
}


$slugId = $conn->real_escape_string($data['slugId']);
$fileName = $conn->real_escape_string($data['fileName']);
$cas = $conn->real_escape_string($data['cas']);


$sql = "INSERT INTO Soubor (Jmeno, Slugid, cas) VALUES ('$fileName', '$slugId','$cas')";


if ($conn->query($sql) === TRUE) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'error' => $conn->error]);
}

// Close connection
$conn->close();
?>
