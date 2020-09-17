<?php

include_once('curl_functions.inc.php');

//******************************************************//

//Incluyo la Librería PHP Simple HTML DOM Parser
include_once('simple_html_dom.php');


//FUNCIÓN consulta_stock_ps QUE CONSULTA EL STOCK DE UN ARTÍCULO
//  Se pasa como parámetro:   
//      $ref_ps   -> La referencia en ps del artículo
//  La función devuelve:   
//      $num_unidades -> El stock del Artículo correspondiente a la referencia recibida
function consulta_stock_ps($ref_ps){
    
    try {
        
        //Obtengo la fecha actual
        $fecha_actual = date('Y-m-d');
        //Obtengo la hora actual
        $hora_actual = date('H:i:s');

        include('conexion7.php');

        //En primer lugar "simulamos" el login en la página de login (https://www.stricker-europe.com/es/)
        //OJO tras introducir el email y la contraseña en el formulario de acceso se pasan por POST y se comprueban en
        //(https://www.stricker-europe.com/es/zona-reservada/login/), por lo que es en esa URL en la que simulamos el login
        login("https://www.stricker-europe.com/es/zona-reservada/login/", "usr=javierbp&pwd=JavierJimena8066&subm=true&urlReturn=/es/");

        //Obtengo la referencia del artículo que he recibido como parámetro
        $ref_articulo = $ref_ps;

        //Para que funcione correctamente, en la URL que genero para consultar el stock de cada artículo,
        //en las referencias de los artículos hay que remplazar los espacios (si los hay) por %20
        $ref_articulo_saneada = str_replace(" ", "%20", $ref_articulo);

        //Obtengo el código HTML del resultado de la consulta del artículo con esa referencia
        $html_scraped = grab_page("https://www.stricker-europe.com/es/busqueda/?q=".$ref_articulo_saneada);
        //echo $html_scraped;

        $num_unidades = 0;

        //Creo un HTML DOM object (empleando un método de la Librería PHP Simple HTML DOM Parser que he importado)
        $html = str_get_html($html_scraped);

        //Compruebo que el resultado de la búsqueda muestre sólo un documento (artículo) encontrado. En caso contrario la referencia sería errónea    
        $ref_encontradas = $html->find('div#search-summary b', 1)->innertext;

        if($ref_encontradas != '0') {
            //Obtengo el valor del stock total mostrado en la página        
            $cantidades = $html->find('div.stock'); //El método find, de la Librería PHP Simple HTML DOM Parser, permite recorrer el DOM de forma similar a como se hace con jQuery

            foreach($cantidades as $cantidad) {
                //Compruebo primero que la referencia corresponde a la que se busca
                //Por ejemplo en el caso de la ref. 99423 (sombrero), también aparece la ref 99449 (cinta de sombrero)
                $ref_para_cantidad = $cantidad->parent()->find('div.ref', 0)->innertext;

                if($ref_para_cantidad == $ref_articulo) {
                    $valor = $cantidad->innertext;  //El método innertext, de la Librería PHP Simple HTML DOM Parser, permite obtener el texto dentro del elemento

                    //Elimino la cadena 'Stock: ' y me quedo sólo con la cantidad (que es aún un string)
                    $num_unidades_str = str_replace("Stock: ", "", $valor);

                    $num_unidades = $num_unidades + (int)str_replace(".", "", $num_unidades_str);

                    //Antes de devolver el número de unidades que hay en stock de todas las variantes agregadas guardo también el stock por variante

                    //Obtengo el link con la URL de la página que muestra la descripción pormenorizada del artículo con el stock de cada variante
                    $link_local = $cantidad->parent()->parent()->href;

                    //Obtengo el código HTML del resultado de la consulta de esa URL, añadiendo el dominio para que la URL sea absoluta
                    $html_variantes_scraped = grab_page("https://www.stricker-europe.com".$link_local);

                    //Creo un HTML DOM object (empleando un método de la Librería PHP Simple HTML DOM Parser que he importado)
                    $html_variantes = str_get_html($html_variantes_scraped);

                    $variantes = $html_variantes->find('table.tabela-order tbody tr');

                    $indice = 0;

                    foreach($variantes as $variante) {
                        if($indice != 0) { //No tengo en cuenta un primer elemento que se añade al array $variantes
                            $ref_variante = $ref_articulo." ".$variante->find('td', 0)->find('div.color', 0)->title;

                            $stock_variante = $variante->find('td', 1)->innertext;
                            $num_unidades_variante = (int)str_replace(".", "", $stock_variante);

                            //INSERTO EL RESULTADO DE LA CONSULTA DE STOCK EN LA BD
                            $consulta_inserta_stock_ps = "INSERT INTO stock_ps
                                                                   (stock_ps_ref,
                                                                    stock_ps_fecha,
                                                                    stock_ps_hora,
                                                                    stock_ps_stock)
                                                                VALUES
                                                                   ('$ref_variante',
                                                                    '$fecha_actual',
                                                                    '$hora_actual',
                                                                    '$num_unidades_variante');";
                            $bd7->Execute($consulta_inserta_stock_ps);
                        }
                        $indice++;
                    }
                }
            }

        } else { //En caso de que la referencia sea errónea, inserto un registro en la BD con un stock de -1, para luego poder revisar más adelante las correspondencias que están "huérfanas" 
            //INSERTO EL RESULTADO DE LA CONSULTA DE STOCK EN LA BD
            $consulta_inserta_stock_ps = "INSERT INTO stock_ps
                                                   (stock_ps_ref,
                                                    stock_ps_fecha,
                                                    stock_ps_hora,
                                                    stock_ps_stock)
                                                VALUES
                                                   ('$ref_articulo',
                                                    '$fecha_actual',
                                                    '$hora_actual',
                                                    '-1');";
            $bd7->Execute($consulta_inserta_stock_ps);
        }
        
    } catch (Exception $e) {
        $num_unidades = -2; //si hay algún error, devuelvo -2 como número de unidades en stock, para luego al recoger este valor concreto saber que no es un stock real, sino que se ha producido un error en el proceso
    }
    
    //DEVUELVO EL NÚMERO DE UNIDADES EN STOCK
    return($num_unidades);
}


//prueba - BORRAR
//$ref = "99137";  //Referencia de un Artículo con variantes (colores)
//$ref = "91884";  //Referencia de un Artículo sin stock (en el día de la prueba)
//$ref = "99423";  //Referencia de un Artículo sin stock (en el día de la prueba)
//$ref = "93874";  //Referencia de un Artículo con una sóla variante
//$ref = "MO72645";  //Referencia inexistente

/*
$ref = "92414";  
$stock = consulta_stock_ps($ref);
echo "Stock PS ".$ref.": ".$stock." unidades";
*/

//FIN de prueba - BORRAR

?>