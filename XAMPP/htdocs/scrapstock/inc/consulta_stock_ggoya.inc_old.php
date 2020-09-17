<?php

include_once('curl_functions.inc.php');

//******************************************************//

//Incluyo la Librería PHP Simple HTML DOM Parser
include_once('simple_html_dom.php');


//FUNCIÓN consulta_stock_ggoya QUE CONSULTA EL STOCK DE UN ARTÍCULO
//  Se pasa como parámetro:   
//      $ref_ggoya   -> La referencia en ggoya del artículo
//  La función devuelve:   
//      $num_unidades -> El stock del Artículo correspondiente a la referencia recibida
function consulta_stock_ggoya($ref_ggoya){
    
    try {
        
        //Obtengo la fecha actual
        $fecha_actual = date('Y-m-d');
        //Obtengo la hora actual
        $hora_actual = date('H:i:s');

        include('conexion3.php');

        //En primer lugar "simulamos" el login en la página de login https://www.ggoya.com/customer/account/login/)
        //OJO tras introducir el email y la contraseña en el formulario de acceso se pasan por POST y se comprueban en
        //(https://www.ggoya.com/customer/account/loginPost/referer/aHR0cHM6Ly93d3cuZ2dveWEuY29tL2N1c3RvbWVyL2FjY291bnQvbG9nb3V0Lw,,/), por lo que es en esa URL en la que simulamos el login
        login("https://www.ggoya.com/customer/account/loginPost/referer/aHR0cHM6Ly93d3cuZ2dveWEuY29tL2N1c3RvbWVyL2FjY291bnQvbG9nb3V0Lw,,/", "form_key=hZgCPRUsz5P3w0br&login[username]=B54909403&login[password]=011104&send=");

        //Obtengo la referencia del artículo que he recibido como parámetro
        $ref_articulo = $ref_ggoya;

        //Para que funcione correctamente, en la URL que genero para consultar el stock de cada artículo,
        //en las referencias de los artículos hay que remplazar los espacios (si los hay) por %20
        $ref_articulo_saneada = str_replace(" ", "%20", $ref_articulo);

        //Obtengo el código HTML del resultado de la consulta del artículo con esa referencia
        $html_scraped = grab_page("https://www.ggoya.com/catalogsearch/result/?q=".$ref_articulo_saneada);
        //echo $html_scraped;

        $num_unidades = 0;

        //Creo un HTML DOM object (empleando un método de la Librería PHP Simple HTML DOM Parser que he importado)
        $html = str_get_html($html_scraped);

        //Obtengo el valor del stock total mostrado en la página        
        $cantidades = $html->find('li.product-stock span'); //El método find, de la Librería PHP Simple HTML DOM Parser, permite recorrer el DOM de forma similar a como se hace con jQuery

        if(count($cantidades) == 1) { //La referencia buscada es correcta y se muestra sólo un resultado
            foreach($cantidades as $cantidad) {
                $valor = $cantidad->innertext;  //El método innertext, de la Librería PHP Simple HTML DOM Parser, permite obtener el texto dentro del elemento

                $num_unidades = $num_unidades + (int)$valor;
            }

            //Antes de devolver el número de unidades, compruebo si el artículo tiene variantes, para en ese caso guardar el stock de cada una por separado
            $ul_variantes = $html->find('.configurable-swatch-list', 0);

            if($ul_variantes != null) { //El artículo tiene variantes

                //ESTO, DE MOMENTO NO FUNCIONA PORQUE EL ELEMENTO ul.sku_producto EN LA PÁGINA SE CARGA CON AJAX Y NO ESTÁ ALMACENADO EN $html_variantes_scraped
                /*
                //Obtengo la URL en la que se muestra, en detallle, el artículo con el stock de cada una de sus variantes
                $url_variantes = $html->find('div.top-actions-inner a', 0)->href;

                //Obtengo el código HTML del resultado de la consulta del artículo con esa referencia
                $html_variantes_scraped = grab_page($url_variantes);

                //echo $html_variantes_scraped;

                //Creo un HTML DOM object (empleando un método de la Librería PHP Simple HTML DOM Parser que he importado)
                $html_variantes = str_get_html($html_variantes_scraped);

                $variantes = $html_variantes->find('ul.sku_producto'); 

                foreach($variantes as $variante) {
                    $ref_variante = $variante->value;

                    $stock_variante = $variante->parent()->parent()->find('td.stock_actual', 0)->innertext;

                    $num_unidades_variante = (int)str_replace(",", "", $stock_variante);

                    //INSERTO EL RESULTADO DE LA CONSULTA DE STOCK EN LA BD
                    $consulta_inserta_stock_ggoya = "INSERT INTO stock_ggoya
                                                           (stock_ggoya_ref,
                                                            stock_ggoya_fecha,
                                                            stock_ggoya_hora,
                                                            stock_ggoya_stock)
                                                        VALUES
                                                           ('$ref_variante',
                                                            '$fecha_actual',
                                                            '$hora_actual',
                                                            '$num_unidades_variante');";
                    $bd3->Execute($consulta_inserta_stock_ggoya);
                }
                */
                //DE MOMENTO, HASTA QUE NO ENCUENTRE UNA SOLUCIÓN (p.ej. probar a usar CasperJS), SI EL ARTÍCULO TIENE VARIANTES ALMACENO EN LA TABLA stock_ggoya DE LA BD EL STOCK DE LA REFERENCIA "PADRE"
                //NO, PORQUE YA SE HACE DESDE stock_actual.php
                /*
                $num_unidades_variante = (int)$valor;

                $consulta_inserta_stock_ggoya = "INSERT INTO stock_ggoya
                                                   (stock_ggoya_ref,
                                                    stock_ggoya_fecha,
                                                    stock_ggoya_hora,
                                                    stock_ggoya_stock)
                                                VALUES
                                                   ('$ref_articulo',
                                                    '$fecha_actual',
                                                    '$hora_actual',
                                                    '$num_unidades_variante');";
                $bd3->Execute($consulta_inserta_stock_ggoya);
                */
            } else { //En caso de que el artículo no tenga variantes, almaceno también el stock de la única referencia del artículo
                $num_unidades_variante = (int)$valor;

                $consulta_inserta_stock_ggoya = "INSERT INTO stock_ggoya
                                                   (stock_ggoya_ref,
                                                    stock_ggoya_fecha,
                                                    stock_ggoya_hora,
                                                    stock_ggoya_stock)
                                                VALUES
                                                   ('$ref_articulo',
                                                    '$fecha_actual',
                                                    '$hora_actual',
                                                    '$num_unidades_variante');";
                $bd3->Execute($consulta_inserta_stock_ggoya);
            }
        } else { //En caso de que se muestre más de un resultado, significa que la referencia buscada no es correcta, inserto un registro en la BD con un stock de -1, para luego poder revisar más adelante las correspondencias que están "huérfanas" 
            //INSERTO EL RESULTADO DE LA CONSULTA DE STOCK EN LA BD
            $consulta_inserta_stock_ggoya = "INSERT INTO stock_ggoya
                                                   (stock_ggoya_ref,
                                                    stock_ggoya_fecha,
                                                    stock_ggoya_hora,
                                                    stock_ggoya_stock)
                                                VALUES
                                                   ('$ref_articulo',
                                                    '$fecha_actual',
                                                    '$hora_actual',
                                                    '-1');";
            $bd3->Execute($consulta_inserta_stock_ggoya);
        }
        
    } catch (Exception $e) {
        $num_unidades = -2; //si hay algún error, devuelvo -2 como número de unidades en stock, para luego al recoger este valor concreto saber que no es un stock real, sino que se ha producido un error en el proceso
    }
    
    //DEVUELVO EL NÚMERO DE UNIDADES EN STOCK
    return($num_unidades);
}


//prueba - BORRAR
//$ref = "35535";  //Referencia de un Artículo con variantes (colores)
//$ref = "";  //Referencia de un Artículo con variantes (colores y tallas)
//$ref = "35683";  //Referencia de un Artículo sin stock (en el día de la prueba)
//$ref = "37507";  //Referencia de un Artículo con una sóla variante
//$ref = "MO72645";  //Referencia inexistente

/*
$ref = "960";  
$stock = consulta_stock_ggoya($ref);
echo "Stock GGOYA ".$ref.": ".$stock." unidades";
*/

//FIN de prueba - BORRAR

?>