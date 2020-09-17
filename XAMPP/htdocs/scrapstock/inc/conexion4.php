<?php

//MODIFICAR LOS PARÁMETROS DE SERVIDOR, USUARIO, CONTRASEÑA Y BASE DE DATOS CUANDO TRASLADE LA APLICACIÓN DESDE EL XAMP HASTA EL SERVIDOR REAL

//OJO, EN EL SERVIDOR REAL ESTÁ INSTALADO PHP5 Y NO PHP7
// Por tanto la línea   $bd=NewADOConnection("mysqli");
// debo sustituirla por $bd=NewADOConnection("mysql");
// y las líneas $consulta = "SET NAMES 'utf8';";
//              $bd->Execute($consulta); 
// por la línea mysql_query("SET NAMES 'utf8'");

//Importo AdoDB para el manejo más cómodo de la Base de datos ($bd)
include "../adodb/adodb.inc.php";

$servidor="localhost";
$usuario="root";
$contrasena="";
$basedatos="competidores";

//Esta línea es la que uso con PHP 5
//$bd=NewADOConnection("mysql");
//Así me aseguro que los acentos y demás caracteres "especiales" se incorporan correctamente en la BD

//En PHP 7, tengo que usar mysqli_query en vez de mysql_query
$bd4=NewADOConnection("mysqli");

$bd4->Connect($servidor,$usuario,$contrasena,$basedatos);

//Esta línea es la que uso con PHP 5
//mysql_query("SET NAMES 'utf8'");

//En PHP 7, tengo que usar mysqli_query en vez de mysql_query
//Consulta para que con mysqli funcionen correctamente los acentos y caracteres especiales 
$consulta = "SET NAMES 'utf8';";
$bd4->Execute($consulta);

?>