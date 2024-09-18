<?php
// Database credentials
$servername = "db80";
$username = "koukej_Soubory";
$password = "acNgUBqERUfm";
$dbname = "koukej_Soubory";

// Create a new mysqli instance
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL updates to remove diacritics
$queries = [
    "UPDATE Soubor SET Jmeno = REPLACE(Jmeno, 'á', 'a');",
    "UPDATE Soubor SET Jmeno = REPLACE(Jmeno, 'č', 'c');",
    "UPDATE Soubor SET Jmeno = REPLACE(Jmeno, 'ď', 'd');",
    "UPDATE Soubor SET Jmeno = REPLACE(Jmeno, 'é', 'e');",
    "UPDATE Soubor SET Jmeno = REPLACE(Jmeno, 'ě', 'e');",
    "UPDATE Soubor SET Jmeno = REPLACE(Jmeno, 'í', 'i');",
    "UPDATE Soubor SET Jmeno = REPLACE(Jmeno, 'ň', 'n');",
    "UPDATE Soubor SET Jmeno = REPLACE(Jmeno, 'ó', 'o');",
    "UPDATE Soubor SET Jmeno = REPLACE(Jmeno, 'ř', 'r');",
    "UPDATE Soubor SET Jmeno = REPLACE(Jmeno, 'š', 's');",
    "UPDATE Soubor SET Jmeno = REPLACE(Jmeno, 'ť', 't');",
    "UPDATE Soubor SET Jmeno = REPLACE(Jmeno, 'ú', 'u');",
    "UPDATE Soubor SET Jmeno = REPLACE(Jmeno, 'ů', 'u');",
    "UPDATE Soubor SET Jmeno = REPLACE(Jmeno, 'ý', 'y');",
    "UPDATE Soubor SET Jmeno = REPLACE(Jmeno, 'ž', 'z');",
    "UPDATE Soubor SET Jmeno = REPLACE(Jmeno, 'ä', 'a');",
    "UPDATE Soubor SET Jmeno = REPLACE(Jmeno, 'ö', 'o');",
    "UPDATE Soubor SET Jmeno = REPLACE(Jmeno, 'ü', 'u');",
    "UPDATE Soubor SET Jmeno = REPLACE(Jmeno, 'ë', 'e');",
    "UPDATE Soubor SET Jmeno = REPLACE(Jmeno, 'ï', 'i');",
    "UPDATE Soubor SET Jmeno = REPLACE(Jmeno, 'ÿ', 'y');",
    "UPDATE Soubor SET Jmeno = REPLACE(Jmeno, 'Ä', 'A');",
    "UPDATE Soubor SET Jmeno = REPLACE(Jmeno, 'Ö', 'O');",
    "UPDATE Soubor SET Jmeno = REPLACE(Jmeno, 'Ü', 'U');",
    "UPDATE Soubor SET Jmeno = REPLACE(Jmeno, 'Á', 'A');",
    "UPDATE Soubor SET Jmeno = REPLACE(Jmeno, 'Č', 'C');",
    "UPDATE Soubor SET Jmeno = REPLACE(Jmeno, 'Ď', 'D');",
    "UPDATE Soubor SET Jmeno = REPLACE(Jmeno, 'É', 'E');",
    "UPDATE Soubor SET Jmeno = REPLACE(Jmeno, 'Ě', 'E');",
    "UPDATE Soubor SET Jmeno = REPLACE(Jmeno, 'Í', 'I');",
    "UPDATE Soubor SET Jmeno = REPLACE(Jmeno, 'Ň', 'N');",
    "UPDATE Soubor SET Jmeno = REPLACE(Jmeno, 'Ó', 'O');",
    "UPDATE Soubor SET Jmeno = REPLACE(Jmeno, 'Ř', 'R');",
    "UPDATE Soubor SET Jmeno = REPLACE(Jmeno, 'Š', 'S');",
    "UPDATE Soubor SET Jmeno = REPLACE(Jmeno, 'Ť', 'T');",
    "UPDATE Soubor SET Jmeno = REPLACE(Jmeno, 'Ú', 'U');",
    "UPDATE Soubor SET Jmeno = REPLACE(Jmeno, 'Ů', 'U');",
    "UPDATE Soubor SET Jmeno = REPLACE(Jmeno, 'Ý', 'Y');",
    "UPDATE Soubor SET Jmeno = REPLACE(Jmeno, 'Ž', 'Z');"
];

// Execute each query
foreach ($queries as $query) {
    if ($conn->query($query) === TRUE) {
        echo "Record updated successfully\n";
    } else {
        echo "Error updating record: " . $conn->error . "\n";
    }
}

// Close the connection
$conn->close();
?>
