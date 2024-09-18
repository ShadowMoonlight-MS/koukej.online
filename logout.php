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

if (isset($_COOKIE['login_token'])) {
    $token = $_COOKIE['login_token'];

    // Delete the token from the Uzivatel_token table
    $deleteTokenSql = "DELETE FROM Uzivatel_token WHERE token = ?";
    $deleteTokenStmt = $conn->prepare($deleteTokenSql);
    $deleteTokenStmt->bind_param("s", $token);
    $deleteTokenStmt->execute();

    // Remove the cookie
    setcookie('login_token', '', time() - 3600, "/");
}

session_unset();
session_destroy();

header("Location: index.php");
exit();
?>
