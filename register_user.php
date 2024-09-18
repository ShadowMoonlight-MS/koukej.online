<?php
session_start();

include 'CRONS/config.php';

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$data = json_decode(file_get_contents('php://input'), true);
$email = $data['email'];
$password = $data['password'];

// Check if email already exists
$sql = "SELECT * FROM Uzivatel WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo json_encode(["success" => false, "message" => "Uživatel již existuje. Přihlašte se, nebo si změnte heslo(zapomenuté heslo)"]);
    $stmt->close();
} else {
    $stmt->close();

    // Insert new user
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    $sql = "INSERT INTO Uzivatel (email, heslo, opravneni, premium) VALUES (?, ?, 0, '0000-00-00')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $hashedPassword);

    if ($stmt->execute()) {
        $_SESSION['user_email'] = $email;
        echo json_encode(["success" => true, "message" => "Registrace proběhla úspěšně"]);
    } else {
        echo json_encode(["success" => false, "message" => "Registration failed"]);
    }
    $stmt->close();
}

$conn->close();
?>
