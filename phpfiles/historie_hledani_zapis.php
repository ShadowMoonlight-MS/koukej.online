<?php
include __DIR__ . '/../CRONS/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Načteme POST data
    $email = $_POST['email'];
    $query = $_POST['query'];

    // Připojení k databázi
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Připojení selhalo: " . $conn->connect_error);
    }

    // Vložíme email a query do tabulky historie_hledani
    $sql = "INSERT INTO historie_hledani (email, nazev) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $query);

    if ($stmt->execute()) {
        echo "Úspěšně zapsáno do historie.";
    } else {
        echo "Chyba při zápisu: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
