<?php
 
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
 
?>


<?php

// De esta forma no sólo haríamos login, sino que obtendríamos también el código HTML de la página que se muestra tras loguearse
//$var = login("https://www.cifra.es/index.php?route=account/login", "email=jbp@jblascopublicidad.com&password=JavierJimena8066");
//echo $var;


//En primer lugar "simulamos" el login en la home (https://www.cifra.es/index.php?route=common/home)
//OJO tras introducir el eamil y la contraseña en el formulario de acceso se pasan por POST y se comprueban en
//(https://www.cifra.es/index.php?route=account/login), por lo que es en esa URL en la que simulamos el login
login("https://www.cifra.es/index.php?route=account/login", "email=jbp@jblascopublicidad.com&password=JavierJimena8066");


//***CONSULTA DE UN ARTÍCULO INDIVIDUAL***//

$ref_articulo = "Z-755 USB 4GB-PT";   //No hay existencias
//$ref_articulo = "P-156";              //Referencia sin referencias hijas (variantes)
//$ref_articulo = "G-081";                //Referencia Raiz, tiene referencias hijas (variantes de color)
//$ref_articulo = "Z-929";

$ref_articulo_saneada = str_replace(" ", "%20", $ref_articulo);

$html_scraped = grab_page("https://www.cifra.es/index.php?route=product/advanced_search&keyword=".$ref_articulo_saneada);

//echo $html_scraped;

preg_match_all('/Stock:(.*)/', $html_scraped, $matches);

//print_r($matches);

//foreach($matches as $match) {
//    echo "Stock del Artículo ".$ref_articulo." : ".str_replace("Stock: ", "", $match)." unidades<br>";
//}

$num_unidades = 0;

foreach($matches[1] as $match) {
    $num_unidades_variante = $match;
    
    if($match == "próximamente") {
        $num_unidades_variante = 0;
    } else {
        $num_unidades_variante = (int)str_replace(".", "", $match);
    }
    
    $num_unidades = $num_unidades + $num_unidades_variante;
}

//$num_unidades = str_replace("Stock: ", "", $matches[0]);
/*$num_unidades = $matches[1];

if(substr_count($num_unidades, "próximamente") > 0) {
    $num_unidades = 0;
}*/

echo "Stock del Artículo ".$ref_articulo." : ".$num_unidades." unidades";



//***CONSULTA DE TODOS LOS ARTÍCULOS***//

//Conexión para poder usar la bd con ADODB ($bd)
/*require_once('conexion.php');

$consulta_referencias ="SELECT datosxml_cifra_ref
                            FROM datosxml_cifra
                            LIMIT 10;";

$resultado_consulta_referencias = $bd->Execute($consulta_referencias);

while (!($resultado_consulta_referencias->EOF)) {
    
    $ref_articulo = $resultado_consulta_referencias->fields['datosxml_cifra_ref'];
    
    $ref_articulo_saneada = str_replace(" ", "%20", $ref_articulo);

    $html_scraped = grab_page("https://www.cifra.es/index.php?route=product/advanced_search&keyword=".$ref_articulo_saneada);

    //echo $html_scraped;

    preg_match('/Stock:(.*)/', $html_scraped, $matches);

    //foreach($matches as $match) {
    //    echo "Stock del Artículo ".$ref_articulo." : ".str_replace("Stock: ", "", $match)." unidades<br>";
    //}

    $num_unidades = str_replace("Stock: ", "", $matches[0]);

    if(substr_count($num_unidades, "próximamente") > 0) {
        $num_unidades = 0;
    }

    echo "Stock del Artículo ".$ref_articulo." : ".$num_unidades." unidades<br>";
    
    
    $resultado_consulta_referencias->MoveNext();
}*/

?>