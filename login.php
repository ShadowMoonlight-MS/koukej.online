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

$sql = "SELECT * FROM Uzivatel WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    if (password_verify($password, $user['heslo'])) {
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['premium'] = $user['premium'];  // Store premium value in session

        // Generate a unique token for auto-login
        $token = bin2hex(random_bytes(32));
        $expiresAt = date('Y-m-d H:i:s', strtotime('+30 days'));

        // Insert the token into the Uzivatel_token table
        $tokenSql = "INSERT INTO Uzivatel_token (email, token, expires_at) VALUES (?, ?, ?)";
        $tokenStmt = $conn->prepare($tokenSql);
        $tokenStmt->bind_param("sss", $email, $token, $expiresAt);
        $tokenStmt->execute();

        // Set the cookie with the token
        setcookie('login_token', $token, time() + (86400 * 30), "/", "", false, true); // HttpOnly

        echo json_encode(["success" => true, "message" => "Login successful"]);
    } else {
        echo json_encode(["success" => false, "message" => "Invalid password"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid email"]);
}

$stmt->close();
$conn->close();
?>
