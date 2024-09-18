<?php
$servername = "db80";
$username = "koukej_Soubory";
$password = "acNgUBqERUfm";
$dbname = "koukej_Soubory";
// Vytvoření připojení
$conn = new mysqli($servername, $username, $password, $dbname);

// Kontrola připojení
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Najdi duplicity
$sql = "SELECT idsoubor FROM Soubor WHERE slugid IN (SELECT slugid FROM Soubor GROUP BY slugid HAVING COUNT(*) > 1)";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Odstraň duplicity kromě jedné
    $deleteSql = "DELETE t1 FROM Soubor t1 INNER JOIN Soubor t2 WHERE t1.idsoubor < t2.idsoubor AND t1.slugid = t2.slugid";
    if ($conn->query($deleteSql) === TRUE) {
        echo "Duplicity byly úspěšně odstraněny.";
    } else {
        echo "Error deleting records: " . $conn->error;
    }
} else {
    echo "Žádné duplicity nebyly nalezeny.";
}

$conn->close();
?>
