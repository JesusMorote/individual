<?php

include_once('curl_functions.inc.php');

//******************************************************//

//Incluyo la Librería PHP Simple HTML DOM Parser
include_once('simple_html_dom.php');


//FUNCIÓN consulta_stock_cifra QUE CONSULTA EL STOCK DE UN ARTÍCULO
//  Se pasa como parámetro:   
//      $ref_cifra   -> La referencia en Cifra del artículo
//  La función devuelve:   
//      $num_unidades -> El stock del Artículo correspondiente a la referencia recibida
function consulta_stock_cifra($ref_cifra){
    
    try {
        
        //Obtengo la fecha actual
        $fecha_actual = date('Y-m-d');
        //Obtengo la hora actual
        $hora_actual = date('H:i:s');

        include('conexion2.php');

        //En primer lugar "simulamos" el login en la home (https://www.cifra.es/index.php?route=common/home)
        //OJO tras introducir el email y la contraseña en el formulario de acceso se pasan por POST y se comprueban en
        //(https://www.cifra.es/index.php?route=account/login), por lo que es en esa URL en la que simulamos el login
        login("https://www.cifra.es/index.php?route=account/login", "email=jbp@jblascopublicidad.com&password=JavierJimena8066");

        //Obtengo la referencia del artículo que he recibido como parámetro
        $ref_articulo = $ref_cifra;

        //Para que funcione correctamente, en la URL que genero para consultar el stock de cada artículo,
        //en las referencias de los artículos hay que remplazar los espacios (si los hay) por %20
        $ref_articulo_saneada = str_replace(" ", "%20", $ref_articulo);

        //Obtengo el código HTML del resultado de la consulta del artículo con esa referencia
        $html_scraped = grab_page("https://www.cifra.es/index.php?route=product/advanced_search&keyword=".$ref_articulo_saneada);

        //Busco en el HTML obtenido la cadena Stock:(.*) mediante una expresión regular
        //preg_match_all('/Stock:(.*)/', $html_scraped, $matches);

        $num_unidades = 0;

        //Creo un HTML DOM object (empleando un método de la Librería PHP Simple HTML DOM Parser que he importado)
        $html = str_get_html($html_scraped);

        $variantes = $html->find('span[style=color: #000000; font-size: 11px;]');

        foreach($variantes as $variante) {
            $ref_variante = $variante->parent()->find('span[style=color: #666666; font-size: 11px;]', 0)->innertext;
            $stock_variante = $variante->innertext;
            $num_unidades_variante = str_replace("Stock: ", "", $stock_variante);

            if($num_unidades_variante == "próximamente") {
                $num_unidades_variante = 0;
            } else {
                $num_unidades_variante = (int)str_replace(".", "", $num_unidades_variante);
            }

            //INSERTO EL RESULTADO DE LA CONSULTA DE STOCK EN LA BD
            $consulta_inserta_stock_cifra = "INSERT INTO stock_cifra
                                                   (stock_cifra_ref,
                                                    stock_cifra_fecha,
                                                    stock_cifra_hora,
                                                    stock_cifra_stock)
                                                VALUES
                                                   ('$ref_variante',
                                                    '$fecha_actual',
                                                    '$hora_actual',
                                                    '$num_unidades_variante');";
            $bd2->Execute($consulta_inserta_stock_cifra);

            $num_unidades = $num_unidades + $num_unidades_variante;
        }

        if(count($variantes) == 0) { //En caso de que la referencia sea errónea, inserto un registro en la BD con un stock de -1, para luego poder revisar más adelante las correspondencias que están "huérfanas" 
            //INSERTO EL RESULTADO DE LA CONSULTA DE STOCK EN LA BD
            $consulta_inserta_stock_cifra = "INSERT INTO stock_cifra
                                                   (stock_cifra_ref,
                                                    stock_cifra_fecha,
                                                    stock_cifra_hora,
                                                    stock_cifra_stock)
                                                VALUES
                                                   ('$ref_articulo',
                                                    '$fecha_actual',
                                                    '$hora_actual',
                                                    '-1');";
            $bd2->Execute($consulta_inserta_stock_cifra);
        }        
        
    } catch (Exception $e) {
        $num_unidades = -2; //si hay algún error, devuelvo -2 como número de unidades en stock, para luego al recoger este valor concreto saber que no es un stock real, sino que se ha producido un error en el proceso
    }
    
    //DEVUELVO EL NÚMERO DE UNIDADES EN STOCK
    return($num_unidades);
}


//prueba - BORRAR
//$ref = "Z-990";  //Referencia de un Artículo con variantes
//$ref = "B-091";  //Referencia de un Artículo sin stock (en el día de la prueba)
//$ref = "Z-282";  //Referencia de un Artículo con una sóla variante
//$ref = "S-251";  //Referencia inexistente

/*
$ref = "T-019";
$stock = consulta_stock_cifra($ref);
echo "Stock CIFRA ".$ref.": ".$stock." unidades";
*/

//FIN de prueba - BORRAR

?>