<?php

include_once('curl_functions.inc.php');

//******************************************************//

//Incluyo la Librería PHP Simple HTML DOM Parser
include_once('simple_html_dom.php');


//FUNCIÓN consulta_stock_mob QUE CONSULTA EL STOCK DE UN ARTÍCULO
//  Se pasa como parámetro:   
//      $ref_mob   -> La referencia en mob del artículo
//  La función devuelve:   
//      $num_unidades -> El stock del Artículo correspondiente a la referencia recibida
function consulta_stock_mob($ref_mob){
    
    try {
        
        //Obtengo la fecha actual
        $fecha_actual = date('Y-m-d');
        //Obtengo la hora actual
        $hora_actual = date('H:i:s');

        include('conexion5.php');

        //En primer lugar "simulamos" el login en la página de login (https://www.midoceanbrands.com/Iberia/es/eur/login)
        //OJO tras introducir el email y la contraseña en el formulario de acceso se pasan por POST y se comprueban en
        //(https://www.midoceanbrands.com/Iberia/es/eur/processLogin), por lo que es en esa URL en la que simulamos el login
        login("https://www.midoceanbrands.com/Iberia/es/eur/processLogin", "SynchronizerToken=d4cb8e5bbfa56afd46dc59a41bdc0f…c00d51bcbb216de6893877b6ff9ce&ShopLoginForm_Login=javi@jblasco.es&ShopLoginForm_Password=JavierJimena8066&rememberMe=on");

        //Obtengo la referencia del artículo que he recibido como parámetro
        $ref_articulo = $ref_mob;

        //Para que funcione correctamente, en la URL que genero para consultar el stock de cada artículo,
        //en las referencias de los artículos hay que remplazar los espacios (si los hay) por %20
        $ref_articulo_saneada = str_replace(" ", "%20", $ref_articulo);

        //Obtengo el código HTML del resultado de la consulta del artículo con esa referencia
        $html_scraped = grab_page("https://www.midoceanbrands.com/Iberia/es/eur/QuickSearch?SearchTerm=".$ref_articulo_saneada);
        //echo $html_scraped;
        
        $num_unidades = 0;

        //En este caso, el HTML scrapeado no es completo, pues muchos contenidos (entre ellos el stock) se cargan mediante AJAX
        //Recupero el valor de la URL que devuelve por AJAX los datos de stock
        $html = str_get_html($html_scraped);
        
        if($html != false) { //OJO, LOS BLOQUES TRY CATCH (no funcionan bien y en ocasiones se produce un error que interrumpe el proceso por lotes -call find on boolean...-) POR ESO HE AÑADIDO ESTE IF ELSE QUE, EN CASO DE QUE EXISTA ALGÚN ERROR EN EL SCRAPPING DE LA PÁGINA ($html == false), NO DA ERROR Y DEVUELVE -2, COMO DEBÍA HACER EL TRY CATCH, PARA QUE LUEGO YA QUEDE REGISTRADO EL POSIBLE ERROR EN EL FICHERO DE LOG Y PROCEDER A SU REVISIÓN POSTERIOR
            
            if($html->find('#productImagesAndStockURL', 0) != null) {
                $url_stock = $html->find('#productImagesAndStockURL', 0)->value;    
                //echo $url_stock;    

                //Obtengo el código HTML del resultado de la consulta de stock por AJAX del artículo
                $html_stock_scraped = grab_page($url_stock);
                //echo $html_stock_scraped;    

                //Creo un HTML DOM object (empleando un método de la Librería PHP Simple HTML DOM Parser que he importado)
                $html2 = str_get_html($html_stock_scraped);

                $cantidades = $html2->find('div.mobImgWrap b'); //El método find, de la Librería PHP Simple HTML DOM Parser, permite recorrer el DOM de forma similar a como se hace con jQuery

                if(count($cantidades) != 0) {
                    foreach($cantidades as $cantidad) {
                        $ref_variante = $ref_mob." ".str_replace("&nbsp;", "", $cantidad->parent()->find('p', 0)->innertext);
                        $valor = $cantidad->innertext;  //El método innertext, de la Librería PHP Simple HTML DOM Parser, permite obtener el texto dentro del elemento

                        $num_unidades_variante = $valor;

                        if($valor == "-") {
                            $num_unidades_variante = 0;
                        } else {
                            $num_unidades_variante = (int)str_replace(".", "", $valor);
                        }

                        //INSERTO EL RESULTADO DE LA CONSULTA DE STOCK EN LA BD
                        $consulta_inserta_stock_mob = "INSERT INTO stock_mob
                                                               (stock_mob_ref,
                                                                stock_mob_fecha,
                                                                stock_mob_hora,
                                                                stock_mob_stock)
                                                            VALUES
                                                               ('$ref_variante',
                                                                '$fecha_actual',
                                                                '$hora_actual',
                                                                '$num_unidades_variante');";
                        $bd5->Execute($consulta_inserta_stock_mob);

                        $num_unidades = $num_unidades + $num_unidades_variante;
                    }
                }
            } else { //En caso de que la referencia sea errónea, inserto un registro en la BD con un stock de -1, para luego poder revisar más adelante las correspondencias que están "huérfanas" 
                //INSERTO EL RESULTADO DE LA CONSULTA DE STOCK EN LA BD
                $consulta_inserta_stock_mob = "INSERT INTO stock_mob
                                                       (stock_mob_ref,
                                                        stock_mob_fecha,
                                                        stock_mob_hora,
                                                        stock_mob_stock)
                                                    VALUES
                                                       ('$ref_articulo',
                                                        '$fecha_actual',
                                                        '$hora_actual',
                                                        '-1');";
                $bd5->Execute($consulta_inserta_stock_mob);
            
                //Y devuelvo $num_unidades = -2; Para que quede constancia de que ha existido un error si se registra un fichero de log
                $num_unidades = -2;
            }
            
        } else {
            $num_unidades = -2; //si hay algún error, devuelvo -2 como número de unidades en stock, para luego al recoger este valor concreto saber que no es un stock real, sino que se ha producido un error en el proceso
        }
        
    } catch (Exception $e) {
        $num_unidades = -2; //si hay algún error, devuelvo -2 como número de unidades en stock, para luego al recoger este valor concreto saber que no es un stock real, sino que se ha producido un error en el proceso
    }
    
    //DEVUELVO EL NÚMERO DE UNIDADES EN STOCK
    return($num_unidades);
}


//prueba - BORRAR
//$ref = "MO8516";  //Referencia de un Artículo con variantes (colores)
//$ref = "BC0150";  //Referencia de un Artículo con variantes (colores y tallas)
//$ref = "MO8879";  //Referencia de un Artículo sin stock (en el día de la prueba)
//$ref = "MO7264";  //Referencia de un Artículo con una sóla variante
//$ref = "MO72645";  //Referencia inexistente

/*
$ref = "KC4164";  
$stock = consulta_stock_mob($ref);
echo "Stock MOB ".$ref.": ".$stock." unidades";
*/

/*
consulta_stock_mob('MO8635');

echo "completado";
*/



//FIN de prueba - BORRAR

?>