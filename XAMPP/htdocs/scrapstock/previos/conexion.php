<?php

include "adodb/adodb.inc.php";
$servidor="localhost";
$usuario="root";
$contrasena="";
$basedatos="competidores";
//$bd=NewADOConnection("mysql");
$bd=NewADOConnection("mysqli");
$bd->Connect($servidor,$usuario,$contrasena,$basedatos);

//Esta línea es la que uso con PHP 5
//mysql_query("SET NAMES 'utf8'");
//En PHP 7, tengo que usar mysqli_query en vez de mysql_query
//Consulta para que con mysqli funcione 
$consulta = "SET NAMES 'utf8';";
$bd->Execute($consulta);
//Así me aseguro que los acentos y demás caracteres "especiales" se incorporan correctamente en la BD

//En teoría tembién debería funcionar así
//mysqli_query("SET NAMES 'utf8'"); //Necesita dos parámetros conexión y consulta
//mysqli_query($bd, "SET NAMES 'utf8'"); //Pero no va

?>