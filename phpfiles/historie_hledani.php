<?php
include __DIR__ . '/../CRONS/config.php';

    $userEmail = $userEmail; // nebo jiný způsob, jak získat e-mail uživatele

    // Připoj se k databázi
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Ověření připojení
    if ($conn->connect_error) {
        die("Připojení selhalo: " . $conn->connect_error);
    }

    // Příprava dotazu na kontrolu e-mailu v tabulce historie_hledani
    $sql = "SELECT id, nazev FROM historie_hledani WHERE email = ? ORDER BY `historie_hledani`.`id` DESC LIMIT 5;";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $userEmail);
    $stmt->execute();
    $result = $stmt->get_result();

    // Pokud dotaz vrátí alespoň jeden řádek
    if ($result->num_rows > 0) {
        // Projdi všechny výsledky
        while($row = $result->fetch_assoc()) {
            echo  '<li class="list-group-item">'.$row["nazev"].'</li>';
        }
    } else {
        // Pokud e-mail v databázi není, nedělej nic
        // Můžeš také vrátit nějakou informaci, pokud chceš
        
    }

    // Uzavři spojení s databází
    $stmt->close();
    $conn->close();

?>
