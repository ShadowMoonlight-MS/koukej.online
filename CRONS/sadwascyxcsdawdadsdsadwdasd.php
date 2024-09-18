<?php
// Nastavení pro připojení k e-mailu
$hostname = '{mail.hukot.net:993/imap/ssl}INBOX';
$username = 'podpora@koukej.online';
$password = 'ScHVNkZQQu7J';  // Zadejte heslo k e-mailovému účtu

// Nastavení pro připojení k databázi
$db_servername = "db80";
$db_username = "koukej_Soubory";
$db_password = "acNgUBqERUfm";
$db_name = "koukej_Soubory";

// Připojení k e-mailovému serveru
$inbox = imap_open($hostname, $username, $password) or die('Cannot connect to Mail: ' . imap_last_error());

// Hledání nepřečtených zpráv od info@airbank.cz
$emails = imap_search($inbox, 'UNSEEN FROM "info@airbank.cz"');

if ($emails) {
    // Připojení k databázi
    $conn = new mysqli($db_servername, $db_username, $db_password, $db_name);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $permissions_updated = false;

    foreach ($emails as $email_number) {
        $overview = imap_fetch_overview($inbox, $email_number, 0);
        $message = imap_fetchbody($inbox, $email_number, 1.2); // 1.2 for HTML body, 1 for plain text

        if (empty($message)) {
            $message = imap_fetchbody($inbox, $email_number, 1);
        }

        // Hledání částky a zprávy pro příjemce v těle e-mailu
        if (preg_match('/Částka:\s*(\d+,\d+)\s*CZK/', $message, $amount_matches) && preg_match('/Zpráva pro příjemce:\s*([\w\.@]+)/', $message, $email_matches)) {
            $amount = $amount_matches[1];
            $email_recipient = $email_matches[1];

            // Kontrola, zda je částka 39,-
            if ($amount === '39,00') {
                // Aktualizace oprávnění v databázi
                $stmt = $conn->prepare("UPDATE Uzivatel SET opravneni = opravneni + 30 WHERE email = ?");
                $stmt->bind_param("s", $email_recipient);
                $stmt->execute();
                if ($stmt->affected_rows > 0) {
                    echo "Permissions updated for: $email_recipient\n";
                    $permissions_updated = true;
                } else {
                    echo "No permissions updated for: $email_recipient\n";
                }
                $stmt->close();
            }
        }

        // Označení e-mailu jako přečteného
        imap_setflag_full($inbox, $email_number, "\\Seen");
    }

    if (!$permissions_updated) {
        echo "No permissions were updated.\n";
    }

    $conn->close();
} else {
    echo "No new emails found.\n";
}

// Zavření připojení k e-mailovému serveru
imap_close($inbox);
?>
