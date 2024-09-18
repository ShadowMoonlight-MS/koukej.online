<?php
include 'CRONS/config.php';

// Vytvoření připojení k databázi
$conn = new mysqli($servername, $username, $password, $dbname);

// Kontrola připojení
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL dotaz
$sql = "SELECT * FROM Soubor ORDER BY idSoubor ASC";
$result = $conn->query($sql);

// Výpis výsledku dotazu do HTML tabulky
if ($result->num_rows > 0) {
    echo "<table border='1'>";
    echo "<tr><th>idSoubor</th><th>Jmeno</th><th>Slugid</th><th>cas</th></tr>";
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["idsoubor"] . "</td>";
        echo "<td>" . $row["Jmeno"] . "</td>";
        echo "<td>" . $row["Slugid"] . "</td>";
        echo "<td>" . $row["cas"] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "0 results";
}

// Uzavření připojení
$conn->close();
?>
