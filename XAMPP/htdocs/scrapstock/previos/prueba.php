<?php

//Ejemplo de copia simple del contenido de una URL para mostrarlo tal cual en otra

//encabezado para que se muestren correctamente los acentos, caracteres especiales,...
header('Content-Type: text/html; charset=UTF-8');

//Por ejemplo, la página de inicio de CIFRA
$html = file_get_contents('https://www.cifra.es/index.php?route=common/home');

//por ejemplo, en cifra, si voy a la página que muestra el resulado de la búsqueda de la referencia A-009 - NO FUNCIONARÁ PORQUE NO ESTOY LOGUEADO
//$html = file_get_contents('https://www.cifra.es/index.php?route=product/advanced_search&keyword=A-009');

echo $html;

?>