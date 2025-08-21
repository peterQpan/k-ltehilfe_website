<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $to = "webmaster@ingos-kleine-kältehilfe.de"; // <--- Empfänger geändert
    $subject = "Formular-Eingang";
    $message = "Neue Nachricht vom Webformular:\n\n";
    $headers = "From: info@ingos-kleine-kältehilfe.de";


    // Kontaktformular
    if (isset($_POST['first-name'])) {
        $message .= "Vorname: " . ($_POST['first-name'] ?? '') . "\n";
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

    // // PGP-Verschlüsselung
    // $publicKeyFile = __DIR__ . '/public.asc';
    // if (file_exists($publicKeyFile)) {
    //     if (extension_loaded('gnupg')) {
    //         $gpg = new gnupg();
    //         $publicKey = file_get_contents($publicKeyFile);
    //         $import = $gpg->import($publicKey);
    //         if (!empty($import['fingerprint'])) {
    //             $gpg->addencryptkey($import['fingerprint']);
    //             $encrypted = $gpg->encrypt($message);
    //             if ($encrypted) {
    //                 $message = $encrypted;
    //                 $subject .= ' (PGP verschlüsselt)';
    //             }
    //         }
    //     }
    // }
    mail($to, $subject, $message, $headers);
    echo '<div style="margin:2em auto;max-width:500px;padding:1em;border:2px solid #4caf50;background:#e8f5e9;color:#333;text-align:center;font-size:1.2em;">'
        . 'Vielen Dank für deine Nachricht!<br>Wir melden uns so schnell wie möglich.'
        . '</div>';
    exit();
}
?>