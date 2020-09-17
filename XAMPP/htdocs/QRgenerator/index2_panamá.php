<?php

//GENERADOR DE CÓDIGOS QR PARA PANAMÁ - para generar códigos individuales
// SE USA $textoqr = "https://qrpromo.eu/america/?r=".$i;
// Valdría para generar cualquier otros QR, modificando el script

require_once('phpqrcode/phpqrcode.php');
//include('phpqrcode/qrlib.php');

//Genero manualmente el qr de la ref. 2497SPE
$textoqr = "https://qrpromo.eu/america/?r=2497SPE";
$nombre_archivo_qr = "qr/2497SPE";
QRcode::png($textoqr, $nombre_archivo_qr.'.png', QR_ECLEVEL_L, 10);

//mensaje para identificar el fin del proceso
echo "Código/s QR generado/s con éxito";

?>