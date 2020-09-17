<?php
include('cabecera.php');

$cli=$_GET['cli'];
$res=$_GET['res'];
$res2=$_GET['res2'];

$inserta="UPDATE cliente SET fecha_respuesta=CURDATE(), hora_respuesta=CURTIME(), respuesta='$res', respuesta2='$res2' WHERE cod_cliente=$cli;";
$bd->Execute($inserta);

echo "Gracias por colaborar";

include('pie.php');

?>