<?php

//Conexión para poder usar la bd con ADODB ($bd)
require_once('conexion.php');

//encabezado para que se muestren correctamente los acentos, caracteres especiales,...
header('Content-Type: text/html; charset=UTF-8');

//Obtengo la hora actual
$hora_actual_inicio = date('H:i:s');

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


//En primer lugar "simulamos" el login en la home (https://www.cifra.es/index.php?route=common/home)
//OJO tras introducir el eamil y la contraseña en el formulario de acceso se pasan por POST y se comprueban en
//(https://www.cifra.es/index.php?route=account/login), por lo que es en esa URL en la que simulamos el login
login("https://www.cifra.es/index.php?route=account/login", "email=jbp@jblascopublicidad.com&password=JavierJimena8066");

//Obtengo las referencias de los artículos de la tabla datosxml_cifra
$consulta_referencias ="SELECT datosxml_cifra_ref
                            FROM datosxml_cifra
                            LIMIT 12;"; //De momento hago un limit 12 para pruebas...
$resultado_consulta_referencias = $bd->Execute($consulta_referencias);

//Voy recorriendo las referencias obtenidas y registrando el stock de cada una de ellas
$contador = 0;

echo "Referencias cuyo stock ha sido actualizado:<hr>";

while (!($resultado_consulta_referencias->EOF)) {
    
    $ref_articulo = $resultado_consulta_referencias->fields['datosxml_cifra_ref'];
    
    //Para que funcione correctamente, en la URL que genero para consultar el stock de cada artículo,
    //en las referencias de los artículos hay que remplazar los espacios (si los hay) por %20
    $ref_articulo_saneada = str_replace(" ", "%20", $ref_articulo);

    //Obtengo el código HTML del resultado de la consulta del artículo con la esa referencia
    $html_scraped = grab_page("https://www.cifra.es/index.php?route=product/advanced_search&keyword=".$ref_articulo_saneada);

    //Busco en el HTML obtenido la cadena Stock:(.*) mediante una expresión regular
    preg_match('/Stock:(.*)/', $html_scraped, $matches);
    
    //Obtengo el stock, eliminando (para la primera coincidencia con la expresión regular encontrada) en la cadena obtenida el string "Stock:", con lo que me queda únicamente el número de unidades
    $num_unidades = str_replace("Stock: ", "", $matches[0]);
    
    //Elimino los puntos en el string de la cantidad de unidades (se tomaría como una coma decimal al tratar de insertar el valor como INT en la BD)
    $num_unidades = str_replace(".", "", $num_unidades);

    //Si no hay stock, como cantidad de unidades figurará la cadena "próximamente". En ese caso el número de unidades es 0 
    if(substr_count($num_unidades, "próximamente") > 0) {
        $num_unidades = 0;
    }

    echo "Stock del Artículo ".$ref_articulo." : ".$num_unidades." unidades<br>";
    
    //Obtengo la fecha actual
    $fecha_actual = date('Y-m-d');
    //Obtengo la hora actual
    $hora_actual = date('H:i:s');
    
    //Paso a almacenar el valor de stock de esa referencia en la Base de Datos 
    $consulta_inserta_stock = "INSERT INTO stock_cifra
                                   (stock_cifra_ref,
                                    stock_cifra_fecha,
                                    stock_cifra_hora,
                                    stock_cifra_stock)
                                VALUES
                                   ('$ref_articulo',
                                    '$fecha_actual',
                                    '$hora_actual',
                                    '$num_unidades');";
    $bd->Execute($consulta_inserta_stock);
    
    //Paso a la siguiente referencia
    $resultado_consulta_referencias->MoveNext();
    
    $contador++;
}

//Obtengo la hora de finalización del proceso de inserción de datos XML
$hora_actual_fin = date('H:i:s');

echo "Número de Productos del Competidor CIFRA cuyo stock ha sido actualizado: ".$contador."<br>";
echo "La Actualización de Stock comenzó a las: ".$hora_actual_inicio." horas<br>";
echo "La Actualización de Stock finalizó a las: ".$hora_actual_fin." horas<br>";


?>