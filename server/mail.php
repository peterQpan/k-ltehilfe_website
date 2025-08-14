<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $to = "deine@email.de"; // <--- ANPASSEN
    $subject = "Kontaktformular";
    $message = "Name: " . $_POST["name"] . "\n";
    $message .= "E-Mail: " . $_POST["email"] . "\n";
    $message .= "Nachricht: " . $_POST["message"];
    $headers = "From: " . $_POST["email"];
    mail($to, $subject, $message, $headers);
    header("Location: danke.html");
    exit();
}
?>
