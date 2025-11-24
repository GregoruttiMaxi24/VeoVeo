<?php
// Cambia este email por el tuyo para probar
$to = 'maxigregorutti24@gmail.com';
$subject = 'Prueba de email desde PHP';
$message = 'Este es un mensaje de prueba enviado desde PHP usando mail() en XAMPP.';
$headers = 'From: prueba@veoveo.com' . "\r\n" .
           'Reply-To: prueba@veoveo.com' . "\r\n" .
           'X-Mailer: PHP/' . phpversion();

$result = mail($to, $subject, $message, $headers);

if ($result) {
    echo 'El email fue enviado correctamente.';
} else {
    echo 'Error: El email NO pudo ser enviado.';
}
?>
