<?php

include('cabecera.php');

$cli=$_GET['cli'];
$res=$_GET['res'];
$res2=$_GET['res2'];
$fecha_respuesta=date();
$hora_respuesta=time();

echo "hola";
$inserta="INSERT INTO cliente VALUES(fecha_respuesta='$fecha_respuesta',hora_respuesta='$hora_respuesta',respuesta='$res',respuesta2='$res2') WHERE cod_cliente=$cli;";
echo $inserta;

include('pie.php');

?>