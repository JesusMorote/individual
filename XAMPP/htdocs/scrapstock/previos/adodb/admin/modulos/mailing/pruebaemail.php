<?php

$to = 'alara@makito.es';
$subject = 'Correo de prueba';
$message = 'Este es sólo un mensaje de prueba.';
$from = 'marketing@makito.es';
$headers = 'From:' . $from;
mail($to,$subject,$message,$headers);
echo 'Correo enviado';

?>