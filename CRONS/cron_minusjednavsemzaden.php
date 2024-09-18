<?php
// Nastavení pro připojení k databázi
$servername = "db80";
$username = "koukej_Soubory";
$password = "acNgUBqERUfm";
$dbname = "koukej_Soubory";

// Vytvoření spojení
$conn = new mysqli($servername, $username, $password, $dbname);

// Kontrola spojení
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL dotaz pro snížení hodnoty `opravneni` o 1, ale ponechání na 0 pokud už je 0
$sql = "UPDATE Uzivatel SET opravneni = CASE WHEN opravneni > 0 THEN opravneni - 1 ELSE 0 END";

// Provedení dotazu
if ($conn->query($sql) === TRUE) {
    echo "Permissions updated successfully for all users.";
} else {
    echo "Error updating permissions: " . $conn->error;
}

// Zavření spojení
$conn->close();
?>
