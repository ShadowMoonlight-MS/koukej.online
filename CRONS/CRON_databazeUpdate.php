<?php
$servername = "db80";
$username = "koukej_Soubory";
$password = "acNgUBqERUfm";
$dbname = "koukej_Soubory";

// Vytvoření připojení k databázi
$conn = new mysqli($servername, $username, $password, $dbname);

// Kontrola připojení
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected to the database successfully.\n";

function fetchData($page) {
    $url = 'https://api.hydrax.net/9fb84d1976e10e82aaaf0709e4a49348/list?page=' . $page;

    $result = file_get_contents($url);

    if ($result === FALSE) {
        echo "Failed to fetch data for page $page.\n";
        return null;
    }

    echo "Data fetched for page $page.\n";
    return json_decode($result, true);
}

function formatSQLInsert($item) {
    $jmeno = isset($item['name']) ? addslashes($item['name']) : '';
    $slug = isset($item['slug']) ? addslashes($item['slug']) : '';
    $datum = date('Y-m-d');
    return "INSERT INTO `Soubor` (`idsoubor`, `Jmeno`, `Slugid`, `cas`,`datum`)
SELECT NULL, '$jmeno', '$slug', NULL, '$datum'
WHERE NOT EXISTS (
    SELECT 1
    FROM `Soubor`
    WHERE `Slugid` = '$slug'
);
";
}

function fetchAllData($conn) {
    for ($page = 1; $page <= 4; $page++) {
        $data = fetchData($page);
        if ($data && isset($data['items'])) {
            
            foreach ($data['items'] as $item) {
                $sqlInsert = formatSQLInsert($item);
                
                if ($conn->query($sqlInsert) === FALSE) {
                    
                } else {
                    
                }
            }
        } else {
            
        }
        usleep(500000); // Optional delay, 500000 microseconds = 0.5 seconds
    }
}

// Spuštění funkce pro získání a vložení dat
fetchAllData($conn);

// Zavření připojení k databázi
$conn->close();
echo "Database connection closed.\n";
?>
