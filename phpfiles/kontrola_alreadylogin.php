<?php
session_start();
// Check if the user is already logged in via session
$isLoggedIn = isset($_SESSION['user_email']);
$userEmail = $isLoggedIn ? $_SESSION['user_email'] : '';
if (!$isLoggedIn && isset($_COOKIE['login_token'])) {
    $token = $_COOKIE['login_token'];
    


    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    // Verify the token
    $stmt = $conn->prepare("SELECT email FROM Uzivatel_token WHERE token = ? AND expires_at > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->bind_result($userEmail);
    if ($stmt->fetch()) {
        // Token is valid, log in the user
        $_SESSION['user_email'] = $userEmail;
        $isLoggedIn = true;
    }

    $stmt->close();
    $conn->close();
}
$isAdminUser = $userEmail === 'lagycz.lp@gmail.com';
$opravneni = 0;
// Fetch user permissions if logged in
if ($isLoggedIn) {
    // Fetch permissions from the database
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT opravneni FROM Uzivatel WHERE email = ?");
    $stmt->bind_param("s", $userEmail);
    $stmt->execute();
    $stmt->bind_result($opravneni);
    $stmt->fetch();
    $stmt->close();
    $conn->close();
}
?>