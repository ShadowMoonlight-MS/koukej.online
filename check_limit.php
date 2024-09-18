<?php
// Database connection
include 'CRONS/config.php';

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get IP address and query from the request
$ip_address = $_SERVER['REMOTE_ADDR'];
$query = $_POST['query'];

// Check how many times the IP has submitted queries today
$sql = "SELECT COUNT(*) AS count FROM Nenasel_jsem_soubor WHERE ip = ? AND Datum = CURDATE()";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $ip_address);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($row['count'] >= 5) {
    // Return an error if the limit is reached
    echo json_encode(['status' => 'error', 'message' => '😔 Už jsi nám toho poslal dneska hodně (max 5x), zkus to zítra 😊']);
} else {
    // Insert the new query into the database
    $insert_sql = "INSERT INTO Nenasel_jsem_soubor (ip, nazev, Datum) VALUES (?, ?, CURDATE())";
    $insert_stmt = $conn->prepare($insert_sql);
    $insert_stmt->bind_param("ss", $ip_address, $query);
    if ($insert_stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Do týdne se na to podívám, děkuji']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to submit your query.']);
    }
}

$conn->close();
?>
