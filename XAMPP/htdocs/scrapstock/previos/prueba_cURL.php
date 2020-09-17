<?php

//Ejemplo de copia simple del contenido de una URL, con la librería cURL de PHP, para mostrarlo tal cual en otra

//encabezado para que se muestren correctamente los acentos, caracteres especiales,...
header('Content-Type: text/html; charset=UTF-8');

//phpinfo();

// Definimos la función cURL
function curl($url) {
    $ch = curl_init($url); // Inicia sesión cURL
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); // Configura cURL para devolver el resultado como cadena
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Configura cURL para que no verifique el peer del certificado dado que nuestra URL utiliza el protocolo HTTPS
    $info = curl_exec($ch); // Establece una sesión cURL y asigna la información a la variable $info
    curl_close($ch); // Cierra sesión cURL
    return $info; // Devuelve la información de la función
}




//Por ejemplo, la página de inicio de CIFRA
$html = curl('https://www.cifra.es/index.php?route=common/home');

//por ejemplo, en cifra, si voy a la página que muestra el resulado de la búsqueda de la referencia A-009 - NO FUNCIONARÁ PORQUE NO ESTOY LOGUEADO
//$html = curl('https://www.cifra.es/index.php?route=product/advanced_search&keyword=A-009');

echo $html;

?>