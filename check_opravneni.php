<?php
header('Content-Type: application/json');


include 'CRONS/config.php';





$conn = new mysqli($servername, $username, $password, $dbname);






if ($conn->connect_error) {
    echo json_encode(["error" => "Connection failed: " . $conn->connect_error]);
    exit;
}

$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!isset($data['email'])) {
    echo json_encode(["error" => "Email is missing"]);
    exit;
}

$email = $data['email'];

// Prepare and bind
$stmt = $conn->prepare("SELECT opravneni FROM Uzivatel WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($opravneni);
$stmt->fetch();

if ($opravneni !== null) {
    echo json_encode(["opravneni" => $opravneni]);
} else {
    echo json_encode(["error" => "No record found for email: " . $email]);
}

// Close statement and connection
$stmt->close();
$conn->close();
?>
