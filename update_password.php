<?php
include 'CRONS/config.php';

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$token = isset($_POST['token']) ? $_POST['token'] : '';
$new_password = isset($_POST['new_password']) ? $_POST['new_password'] : '';
$confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';

if ($new_password !== $confirm_password) {
    die(json_encode(["success" => false, "message" => "Passwords do not match"]));
}

if (strlen($new_password) < 8) {
    die(json_encode(["success" => false, "message" => "Password must be at least 8 characters long"]));
}

$sql = "SELECT email FROM password_resets WHERE token=? AND expires_at > NOW()";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $token);
$stmt->execute();
$stmt->bind_result($email);
$stmt->fetch();
$stmt->close();

if ($email) {
    $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

    $sql = "UPDATE Uzivatel SET heslo=? WHERE email=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $hashed_password, $email);

    if ($stmt->execute()) {
        echo "<script>alert('Password has been reset successfully'); window.location.href = 'https://koukej.online';</script>";
    } else {
        echo json_encode(["success" => false, "message" => "Failed to reset password"]);
    }

    $stmt->close();

    $sql = "DELETE FROM password_resets WHERE email=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->close();
} else {
    echo json_encode(["success" => false, "message" => "Invalid or expired token"]);
}

$conn->close();
?>
