<?php

include_once('curl_functions.inc.php');

//******************************************************//

//Incluyo la Librería PHP Simple HTML DOM Parser
include_once('simple_html_dom.php');


//FUNCIÓN consulta_stock_giving QUE CONSULTA EL STOCK DE UN ARTÍCULO
//  Se pasa como parámetro:   
//      $ref_giving   -> La referencia en giving del artículo
//  La función devuelve:   
//      $num_unidades -> El stock del Artículo correspondiente a la referencia recibida
function consulta_stock_giving($ref_giving){
    
    try {
        
        //Obtengo la fecha actual
        $fecha_actual = date('Y-m-d');
        //Obtengo la hora actual
        $hora_actual = date('H:i:s');

        include('conexion4.php');

        //En primer lugar "simulamos" el login en la home (https://www.impression-catalogue.com/es)
        //OJO tras introducir el email y la contraseña en el formulario de acceso se pasan por POST y se comprueban en
        //(https://www.impression-catalogue.com/es/bienvenido-su-buscador-de-regalos?destination=node/19668), por lo que es en esa URL en la que simulamos el login
        login("https://www.impression-catalogue.com/es/bienvenido-su-buscador-de-regalos?destination=node/19668", "name=javi@jblasco.es&pass=JavierJimena8066&remember_me=1&form_build_id=form-pdEhS-k8MHvgxdkKcaIUt0KQbrbHhwIA0THAxoDV-Tk&form_id=user_login_block&op=Inicio+de+sesión");

        //Obtengo la referencia del artículo que he recibido como parámetro
        $ref_articulo = $ref_giving;

        //Para que funcione correctamente, en la URL que genero para consultar el stock de cada artículo,
        //en las referencias de los artículos hay que remplazar los espacios (si los hay) por %20
        $ref_articulo_saneada = str_replace(" ", "%20", $ref_articulo);

        //Obtengo el código HTML del resultado de la consulta del artículo con esa referencia
        //En primer lugar, he de obtener la URL que tiene la referencia de búsqueda del artículo que se obtiene del buscador
        //(p.ej. https://www.impression-catalogue.com/es/article/7681, para el artículo cuya referencia real es 8958)
        $url_busqueda = "https://www.impression-catalogue.com/es/search?keywords=".$ref_articulo_saneada."&op=Buscar";
        $ch = curl_init($url_busqueda);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, TRUE); // We'll parse redirect url from header.
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, FALSE); // We want to just get redirect url but not to follow it.
        $response = curl_exec($ch);
        preg_match_all('/^Location:(.*)$/mi', $response, $matches);
        curl_close($ch);
        //echo !empty($matches[1]) ? trim($matches[1][0]) : 'No redirect found';

        $num_unidades = 0;

        if(count($matches[1]) != 0) {
            $url_resultado = trim($matches[1][0]);
            
            /*
            //Puedo ajustar el valor de la variable $url_resultado para capturas "manuales" en casos de fallo en el redireccionamiento
            //Pasó con las refs: 5401, 5403 y 6478
            //En esos casos hay que cambiar la línea 54: if(count($matches[1]) != 0) { por: if(count($matches[1]) == 0) {
            $url_resultado = "https://www.impression-catalogue.com/es/article/9915";
            */

            //Una vez obtenida la URL que muestra el resultado de la consulta del artículo, capturo su HTML
            $html_scraped = grab_page($url_resultado);    
            //echo $html_scraped;

            //Creo un HTML DOM object (empleando un método de la Librería PHP Simple HTML DOM Parser que he importado)
            $html = str_get_html($html_scraped);

            if($html != false) { //OJO, LOS BLOQUES TRY CATCH (no funcionan bien y en ocasiones se produce un error que interrumpe el proceso por lotes -call find on boolean...-) POR ESO HE AÑADIDO ESTE IF ELSE QUE, EN CASO DE QUE EXISTA ALGÚN ERROR EN EL SCRAPPING DE LA PÁGINA ($html == false), NO DA ERROR Y DEVUELVE -2, COMO DEBÍA HACER EL TRY CATCH, PARA QUE LUEGO YA QUEDE REGISTRADO EL POSIBLE ERROR EN EL FICHERO DE LOG Y PROCEDER A SU REVISIÓN POSTERIOR
                
                $cantidades = $html->find('td.col3'); //El método find, de la Librería PHP Simple HTML DOM Parser, permite recorrer el DOM de forma similar a como se hace con jQuery

                foreach($cantidades as $cantidad) {
                    //$ref_variante = str_replace("(", " (", str_replace(" ", "", $cantidad->parent()->find('td.col2', 0)->innertext));

                    $ref_variante_sin_sanear = $cantidad->parent()->find('td.col2', 0)->innertext;

                    $posicion_br = strpos($ref_variante_sin_sanear, "<br>");

                    $ref_variante_sin_sanear_solo_ref = substr($ref_variante_sin_sanear, 0, $posicion_br);

                    $ref_variante = preg_replace('/\s+/', ' ', $ref_variante_sin_sanear_solo_ref);

                    $valor = $cantidad->innertext;  //El método innertext, de la Librería PHP Simple HTML DOM Parser, permite obtener el texto dentro del elemento

                    $num_unidades_variante = $valor;

                    if($valor == "-") {
                        $num_unidades_variante = 0;
                    } else {
                        $num_unidades_variante = (int)str_replace(".", "", $valor);
                    }
                    
                    //INSERTO EL RESULTADO DE LA CONSULTA DE STOCK EN LA BD
                    $consulta_inserta_stock_giving = "INSERT INTO stock_giving
                                                           (stock_giving_ref,
                                                            stock_giving_fecha,
                                                            stock_giving_hora,
                                                            stock_giving_stock)
                                                        VALUES
                                                           ('$ref_variante',
                                                            '$fecha_actual',
                                                            '$hora_actual',
                                                            '$num_unidades_variante');";
                    $bd4->Execute($consulta_inserta_stock_giving);

                    $num_unidades = $num_unidades + $num_unidades_variante;
                }
            
            } else {
                $num_unidades = -2; //si hay algún error, devuelvo -2 como número de unidades en stock, para luego al recoger este valor concreto saber que no es un stock real, sino que se ha producido un error en el proceso
            }
            
        } else { //En caso de que la referencia sea errónea, inserto un registro en la BD con un stock de -1, para luego poder revisar más adelante las correspondencias que están "huérfanas"
            //INSERTO EL RESULTADO DE LA CONSULTA DE STOCK EN LA BD
            $consulta_inserta_stock_giving = "INSERT INTO stock_giving
                                                   (stock_giving_ref,
                                                    stock_giving_fecha,
                                                    stock_giving_hora,
                                                    stock_giving_stock)
                                                VALUES
                                                   ('$ref_articulo',
                                                    '$fecha_actual',
                                                    '$hora_actual',
                                                    '-1');";
            $bd4->Execute($consulta_inserta_stock_giving);
            
            //Y devuelvo $num_unidades = -2; Para que quede constancia de que ha existido un error si se registra un fichero de log
            $num_unidades = -2;
        }
        
    } catch (Exception $e) {
        $num_unidades = -2; //si hay algún error, devuelvo -2 como número de unidades en stock, para luego al recoger este valor concreto saber que no es un stock real, sino que se ha producido un error en el proceso
    }
    
    //DEVUELVO EL NÚMERO DE UNIDADES EN STOCK
    return($num_unidades);
}


//prueba - BORRAR
//$ref = "6242";  //Referencia de un Artículo con variantes
//$ref = "1503";  //Referencia de un Artículo sin stock (en el día de la prueba)
//$ref = "8958";  //Referencia de un Artículo con una sóla variante
//$ref = "7681";  //Referencia del Artículo (id?) que se obtiene del buscador y a partir de la que se obtiene el resultado final
//$ref = "9242";  //Referencia inexistente

/*
$ref = "5401";
$stock = consulta_stock_giving($ref);
echo "Stock GIVING ".$ref.": ".$stock." unidades";
*/

//FIN de prueba - BORRAR

?>