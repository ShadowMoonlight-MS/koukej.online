<?php
include 'CRONS/config.php';

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json'); // Přesunuto na začátek

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(array("error" => "Connection failed: " . $conn->connect_error)));
}

$searchTerm = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

// Prepare the SQL statement
$sql = "SELECT Slugid, Jmeno, cas FROM Soubor WHERE Jmeno COLLATE utf8_general_ci LIKE '%$searchTerm%' ORDER BY idsoubor DESC LIMIT 24";

$result = $conn->query($sql);

// Check for SQL errors
if ($conn->error) {
    die(json_encode(array("error" => "SQL error: " . $conn->error)));
}

$files = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $files[] = $row;
    }
} else {
    echo json_encode(array("message" => "No results found"));
    exit();  // Ukončení skriptu
}

$conn->close();

echo json_encode($files);
?>
