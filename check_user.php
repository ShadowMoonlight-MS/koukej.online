<?php
include 'CRONS/config.php';



$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$data = json_decode(file_get_contents("php://input"), true);
$email = $data['email'];

$sql = "SELECT * FROM Uzivatel WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

$response = array('exists' => $result->num_rows > 0);

echo json_encode($response);

$stmt->close();
$conn->close();
?>
