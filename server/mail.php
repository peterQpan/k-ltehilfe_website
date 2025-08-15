<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $to = "guggu@hotelheldenplatz.com"; // <--- ANPASSEN
    $subject = "Formular-Eingang";
    $message = "Neue Nachricht vom Webformular:\n\n";
    $headers = "From: info@aktuell.hotelheldenplatz.com";


    // Kontaktformular
    if (isset($_POST['first-name']) || isset($_POST['last-name'])) {
        $message .= "Vorname: " . ($_POST['first-name'] ?? '') . "\n";
        $message .= "Nachname: " . ($_POST['last-name'] ?? '') . "\n";
        $message .= "E-Mail: " . ($_POST['email'] ?? '') . "\n";
        $message .= "Nachricht: " . ($_POST['message'] ?? '') . "\n";
    }
    // Mithelfen-Formular
    elseif (isset($_POST['volunteer-name'])) {
    $message .= "Name: " . ($_POST['volunteer-name'] ?? '') . "\n";
    $message .= "E-Mail: " . ($_POST['volunteer-email'] ?? '') . "\n";
    $message .= "Betreff: " . ($_POST['volunteer-subject'] ?? '') . "\n";
    }
    // Newsletter-Formular
    elseif (isset($_POST['subscribe-email'])) {
        $message .= "Newsletter-Anmeldung: " . ($_POST['subscribe-email'] ?? '') . "\n";
    }
    // Fallback für unbekannte Felder
    else {
        foreach ($_POST as $key => $value) {
            $message .= ucfirst($key) . ": " . $value . "\n";
        }
    }

    mail($to, $subject, $message, $headers);
    echo '<div style="margin:2em auto;max-width:500px;padding:1em;border:2px solid #4caf50;background:#e8f5e9;color:#333;text-align:center;font-size:1.2em;">'
        . 'Vielen Dank für deine Nachricht!<br>Wir melden uns so schnell wie möglich.'
        . '</div>';
    exit();
}
?>