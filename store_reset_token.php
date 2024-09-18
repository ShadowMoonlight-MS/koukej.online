<?php
include 'CRONS/config.php';

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$data = json_decode(file_get_contents('php://input'), true);
$email = isset($data['email']) ? $data['email'] : '';

// Log the received email for debugging
file_put_contents('log.txt', "Received email: $email\n", FILE_APPEND);

if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
    // Check if email is registered
    $sql = "SELECT email FROM Uzivatel WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    // Log the number of rows found for debugging
    file_put_contents('log.txt', "Number of rows found: " . $stmt->num_rows . "\n", FILE_APPEND);

    if ($stmt->num_rows > 0) {
        $stmt->close();

        // Generate token and store in password_resets table
        $token = bin2hex(random_bytes(16));
        $expires_at = date("Y-m-d H:i:s", strtotime("+1 hour"));

        $sql = "INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?) 
                ON DUPLICATE KEY UPDATE token=?, expires_at=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $email, $token, $expires_at, $token, $expires_at);

        if ($stmt->execute()) {
            $reset_link = "https://koukej.online/reset_password.php?token=$token";
            $subject = "Password Reset Request";
            $message = "To reset your password, click the following link: $reset_link";
            $headers = "From: podpora@koukej.online\r\n";

            if (mail($email, $subject, $message, $headers)) {
                echo json_encode(["success" => true]);
            } else {
                echo json_encode(["success" => false, "message" => "Failed to send email"]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "Failed to store reset token"]);
        }

        $stmt->close();
    } else {
        echo json_encode(["success" => false, "message" => "Email not registered"]);
        $stmt->close();
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid email address"]);
    // Log invalid email for debugging
    file_put_contents('log.txt', "Invalid email address: $email\n", FILE_APPEND);
}

$conn->close();
?>
