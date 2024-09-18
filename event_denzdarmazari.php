<?php
// Získání IP adresy uživatele
$ip_address = $_SERVER['REMOTE_ADDR'];
$user_email = $_GET['email']; // nebo POST, záleží na tom, jak bude předán e-mail do skriptu

// Připojení k databázi
include 'CRONS/config.php';

// Vytvoření připojení
$conn = new mysqli($servername, $username, $password, $dbname);

// Kontrola připojení
if ($conn->connect_error) {
    die("Připojení k databázi selhalo: " . $conn->connect_error);
}

// Kontrola, zda již IP adresa nebo e-mail existují v tabulce `event_denzdarmazari`
$sql_check = "SELECT * FROM event_denzdarmazari WHERE ip = '$ip_address' OR email = '$user_email'";
$result_check = $conn->query($sql_check);
if ($result_check->num_rows > 0) {
    // IP adresa nebo e-mail již existují
    echo "<script>alert('Chyba: už jste kód aktivovali, lze aktivovat jednou');</script>";

    echo "<script>window.location.href = 'https://koukej.online';</script>";
    
    
    // Přesměrování zpět na koukej.online
    
} else {
    // IP adresa ani e-mail neexistují, vložíme je do tabulky
    $sql_insert = "INSERT INTO event_denzdarmazari (ip, email) VALUES ('$ip_address', '$user_email')";
    if ($conn->query($sql_insert) === TRUE) {
        // Aktualizace oprávnění uživatele v tabulce Uzivatel
        $sql_update_permission = "UPDATE Uzivatel SET opravneni = opravneni + 2 WHERE email = '$user_email'";
        if ($conn->query($sql_update_permission) === TRUE) {

            echo "<script>alert('Výborně, máte den na koukání zdarma :) Přeji pěknou podívanou!');</script>";
            echo "<script>window.location.href = 'https://koukej.online';</script>";
            
            // Přesměrování zpět na koukej.online
            
        } else {
            echo "Chyba při aktualizaci oprávnění: " . $conn->error;
        }
    } else {
        echo "Chyba při vkládání záznamu: " . $conn->error;
    }
}

// Zavření připojení
$conn->close();
?>
