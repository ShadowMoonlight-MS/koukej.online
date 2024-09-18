<?php
include 'CRONS/config.php';

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$query = isset($_GET['query']) ? $_GET['query'] : '';
$suggestions = [];

if ($query) {
    // Odstranění diakritiky z hledaného výrazu
    $normalizedQuery = $conn->real_escape_string($query);
    
    $stmt = $conn->prepare("SELECT DISTINCT Jmeno FROM Soubor WHERE CONVERT(Jmeno USING ASCII) LIKE CONVERT(? USING ASCII) LIMIT 10");
    if ($stmt === false) {
        error_log('prepare() failed: ' . htmlspecialchars($conn->error));
        die('prepare() failed: ' . htmlspecialchars($conn->error));
    }
    $searchTerm = "%$normalizedQuery%";
    $stmt->bind_param("s", $searchTerm);
    if ($stmt->execute() === false) {
        error_log('execute() failed: ' . htmlspecialchars($stmt->error));
        die('execute() failed: ' . htmlspecialchars($stmt->error));
    }
    $stmt->bind_result($name);
    
    while ($stmt->fetch()) {
        $suggestions[] = $name;
    }

    $stmt->close();
}

$conn->close();

header('Content-Type: application/json');
echo json_encode($suggestions);
?>
