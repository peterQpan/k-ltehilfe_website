<?php
// Testaufruf für das PHP-Mailer-Skript
$postdata = http_build_query(
    array(
        'name' => 'Testname',
        'email' => 'test@example.com',
        'message' => 'Testnachricht'
    )
);

$opts = array('http' =>
    array(
        'method'  => 'POST',
        'header'  => 'Content-type: application/x-www-form-urlencoded',
        'content' => $postdata
    )
);

$context  = stream_context_create($opts);
$result = file_get_contents('http://localhost/mail_secure.php', false, $context);
echo $result;
?>
