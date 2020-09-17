<?php

//Conexión para poder usar la bd con ADODB ($bd), si fuera necesario
//require_once('conexion.php');

//encabezado para que se muestren correctamente los acentos, caracteres especiales,...
//header('Content-Type: text/html; charset=UTF-8');

//***********************************************//
//*****   FUNCIONES PARA EL USO DE cURL     *****//
//Upload a blank cookie.txt to the same directory as this file with a CHMOD/Permission to 777

//FUNCIÓN QUE PERMITE HACER EL LOGIN AUTOMÁTICAMENTE EN UNA URL
//  Se pasan como parámetros:   
//      $url    -> La URL que ha de recoger por POST los campos del login
//                  OJO, no es la URL en la que se hace LOGIN sino la URL que recoge y procesa los campos
//                  del formulario de login después de "pulsar" el botón para acceder.
//                  ESTA URL SE PUEDE OBTENER MEDIANTE LA HERRAMIENTA PARA DESARROLLADORES DE CUALQUIER NAVEGADOR
//                  EN LA PESTAÑA RED (NETWORK), HACIENDO UN LOGIN MANUAL Y VIENDO A QUÉ URL SE ENVÍAN LOS CAMPOS
//                  POR POST. DESDE AHÍ TAMBIÉN ES POSIBLE VER EL NOMBRE DE DICHOS CAMPOS ASÍ COMO SU VALOR
//      $data   -> Se han de pasar los pares de campo=valor (separados por &) que se deben recibir en la URL anterior
//
//  La función también devuelve (si es necesario) el código HTML de la página a la que se redirije al usuario tras el login
function login($url,$data){
    $fp = fopen("cookie.txt", "w");
    fclose($fp);
    $login = curl_init();
    curl_setopt($login, CURLOPT_COOKIEJAR, "cookie.txt");
    curl_setopt($login, CURLOPT_COOKIEFILE, "cookie.txt");
    curl_setopt($login, CURLOPT_TIMEOUT, 40000);
    curl_setopt($login, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($login, CURLOPT_URL, $url);
    curl_setopt($login, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($login, CURLOPT_FOLLOWLOCATION, TRUE);
    curl_setopt($login, CURLOPT_POST, TRUE);
    curl_setopt($login, CURLOPT_POSTFIELDS, $data);
    ob_start();
    return curl_exec ($login);
    ob_end_clean();
    curl_close ($login);
    unset($login);    
}                  


//FUNCIÓN QUE PERMITE OBTENER EL CONTENIDO (HTML) DE UNA URL
//  Se pasa como parámetro:
//      $site   -> La URL de la que se quiere obtener el código HTML
//  La función devuleve el código HTML en forma de string
function grab_page($site){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($ch, CURLOPT_TIMEOUT, 40);
    curl_setopt($ch, CURLOPT_COOKIEFILE, "cookie.txt");
    curl_setopt($ch, CURLOPT_URL, $site);
    ob_start();
    return curl_exec ($ch);
    ob_end_clean();
    curl_close ($ch);
}


// FUNCIÓN QUE PERMITE ENVIAR DATOS A LOS CAMPOS DE UN FORMULARIO?
function post_data($site,$data){
    $datapost = curl_init();
        $headers = array("Expect:");
    curl_setopt($datapost, CURLOPT_URL, $site);
        curl_setopt($datapost, CURLOPT_TIMEOUT, 40000);
    curl_setopt($datapost, CURLOPT_HEADER, TRUE);
        curl_setopt($datapost, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($datapost, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($datapost, CURLOPT_POST, TRUE);
    curl_setopt($datapost, CURLOPT_POSTFIELDS, $data);
        curl_setopt($datapost, CURLOPT_COOKIEFILE, "cookie.txt");
    ob_start();
    return curl_exec ($datapost);
    ob_end_clean();
    curl_close ($datapost);
    unset($datapost);    
}
//*****   FIN de FUNCIONES PARA EL USO DE cURL     *****//
//******************************************************//

?>