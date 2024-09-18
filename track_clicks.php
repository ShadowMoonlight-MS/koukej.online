<?php
// Database connection
include 'CRONS/config.php';

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get IP address and file slug from the request
$ip_address = $_SERVER['REMOTE_ADDR'];
$file_slug = $_POST['file_slug'];

// Check how many times the IP has clicked today
$sql = "SELECT COUNT(*) AS count FROM Nahlasit_soubor_dabing WHERE ip = ? AND datum = CURDATE()";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $ip_address);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($row['count'] >= 5) {
    // Return an error if the limit is reached
    echo json_encode(['status' => 'error', 'message' => 'moc']);
} else {
    // Insert the new click into the database
    $insert_sql = "INSERT INTO Nahlasit_soubor_dabing (ip, nazev, datum) VALUES (?, ?, CURDATE())";
    $insert_stmt = $conn->prepare($insert_sql);
    $insert_stmt->bind_param("ss", $ip_address, $file_slug);
    if ($insert_stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'hotovo']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to submit your click.']);
    }
}

$conn->close();
?>
