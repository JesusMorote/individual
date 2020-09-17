<?php
include "../adodb/adodb.inc.php";
$servidor="localhost";
$usuario="root";
$contrasena="catal2006";
$basedatos="catal";
$bd=NewADOConnection("mysql");
$bd->Connect($servidor,$usuario,$contrasena,$basedatos);
?>
