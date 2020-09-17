<?php

include_once('curl_functions.inc.php');

//******************************************************//

//Incluyo la Librería PHP Simple HTML DOM Parser
include_once('simple_html_dom.php');


//FUNCIÓN consulta_stock_pf QUE CONSULTA EL STOCK DE UN ARTÍCULO
//  Se pasa como parámetro:   
//      $ref_pf   -> La referencia en pf del artículo
//  La función devuelve:   
//      $num_unidades -> El stock del Artículo correspondiente a la referencia recibida
function consulta_stock_pf($ref_pf){
    
    try {
        
        //Obtengo la fecha actual
        $fecha_actual = date('Y-m-d');
        //Obtengo la hora actual
        $hora_actual = date('H:i:s');

        include('conexion6.php');

        //En primer lugar "simulamos" el login en la página de login (http://www.pfconcept.com/cgi-bin/wspd_pcdb_cgi.sh/y/y2ygeneralworld.p?world=general)
        //OJO tras introducir el email y la contraseña en el formulario de acceso se pasan por POST y se comprueban en
        //(http://www.pfconcept.com/cgi-bin/wspd_pcdb_cgi.sh/y/y2login-ajax.p), por lo que es en esa URL en la que simulamos el login
        login("http://www.pfconcept.com/cgi-bin/wspd_pcdb_cgi.sh/y/y2login-ajax.p", "haccount=1419786&huser=JAVIERBL&landing=no&frmdeeplink=&url=&account_number=1419786&user_name=JAVIERBL&user_password=JavierJimena8066&checker=");

        //Obtengo la referencia del artículo que he recibido como parámetro
        $ref_articulo = $ref_pf;

        //Para que funcione correctamente, en la URL que genero para consultar el stock de cada artículo,
        //en las referencias de los artículos hay que remplazar los espacios (si los hay) por %20
        $ref_articulo_saneada = str_replace(" ", "%20", $ref_articulo);

        //Obtengo el código HTML del resultado de la consulta del artículo con esa referencia
        $html_scraped = grab_page("http://www.pfconcept.com/cgi-bin/wspd_pcdb_cgi.sh/y/y2facetmain.p?fctkeywords=".$ref_articulo_saneada);
        //echo $html_scraped;

        $num_unidades = 0;
        
        //Creo un HTML DOM object (empleando un método de la Librería PHP Simple HTML DOM Parser que he importado)
        $html = str_get_html($html_scraped);
        
        if($html != false) { //OJO, LOS BLOQUES TRY CATCH (no funcionan bien y en ocasiones se produce un error que interrumpe el proceso por lotes -call find on boolean...-) POR ESO HE AÑADIDO ESTE IF ELSE QUE, EN CASO DE QUE EXISTA ALGÚN ERROR EN EL SCRAPPING DE LA PÁGINA ($html == false), NO DA ERROR Y DEVUELVE -2, COMO DEBÍA HACER EL TRY CATCH, PARA QUE LUEGO YA QUEDE REGISTRADO EL POSIBLE ERROR EN EL FICHERO DE LOG Y PROCEDER A SU REVISIÓN POSTERIOR

            //Compruebo que. al menos, exista el dato del stock de la variante mostrada. Si no existe, es que la referencia es errónea
            $stock_referencia = $html->find('table.stock-tbl tr', 1);

            if($stock_referencia != null) {
                //Compruebo si existe el dato de stock de todos los colores (artículo con variantes)
                $articulo_con_variantes = $html->find('table.stock-tbl tr', 1)->find('td', 3);

                //Capturo el texto del elemento que contiene el dato del stock
                if ($articulo_con_variantes == null) { //El artículo no tiene variantes
                    //echo "Artículo SIN Variantes";
                    $stock_total = $html->find('table.stock-tbl tr', 1)->find('td', 1)->innertext;

                    //INSERTO EL RESULTADO DE LA CONSULTA DE STOCK EN LA BD
                    $consulta_inserta_stock_pf = "INSERT INTO stock_pf
                                                           (stock_pf_ref,
                                                            stock_pf_fecha,
                                                            stock_pf_hora,
                                                            stock_pf_stock)
                                                        VALUES
                                                           ('$ref_articulo',
                                                            '$fecha_actual',
                                                            '$hora_actual',
                                                            '$stock_total');";
                    $bd6->Execute($consulta_inserta_stock_pf);
                } else { //El artículo tiene variantes
                    //echo "Artículo CON Variantes";
                    $stock_total = $html->find('table.stock-tbl tr', 1)->find('td', 3)->innertext;

                    //En este caso, almaceno el stock de cada variante
                    $variantes = $html->find('div.color-thumbs', 0)->find('div.cl');

                    foreach($variantes as $variante) {
                        $ref_variante_sin_sanear = $variante->onclick;

                        //Extraigo sólo los números de la cadena anterior
                        //$ref_variante = preg_replace('/[^0-9]+/', '', $ref_variante_sin_sanear);
                        //$ref_variante = substr($ref_variante_sin_sanear, 14, -12);                
                        $ref_variante_componentes = explode('"', $ref_variante_sin_sanear);

                        $ref_variante = $ref_variante_componentes[1];

                        //Obtengo el código HTML del resultado de la consulta del artículo con esa referencia
                        $html_scraped_variante = grab_page("http://www.pfconcept.com/cgi-bin/wspd_pcdb_cgi.sh/y/y2facetmain.p?fctkeywords=".$ref_variante);

                        //Creo un HTML DOM object (empleando un método de la Librería PHP Simple HTML DOM Parser que he importado)
                        $html_variante = str_get_html($html_scraped_variante);

                        //Obtengo el stock, únicamente de esa variante
                        $stock_variante = $html_variante->find('table.stock-tbl tr', 1)->find('td', 1)->innertext;

                        $num_unidades_variante = (int)$stock_variante;

                        //Añado a la variante, como prefijo, la ref "padre" de la que se deriva antes de registrar su stock en la BD
                        $ref_variante = $ref_articulo." - ".$ref_variante_componentes[1];

                        //INSERTO EL RESULTADO DE LA CONSULTA DE STOCK EN LA BD
                        $consulta_inserta_stock_pf = "INSERT INTO stock_pf
                                                           (stock_pf_ref,
                                                            stock_pf_fecha,
                                                            stock_pf_hora,
                                                            stock_pf_stock)
                                                            VALUES
                                                               ('$ref_variante',
                                                                '$fecha_actual',
                                                                '$hora_actual',
                                                                '$num_unidades_variante');";
                        $bd6->Execute($consulta_inserta_stock_pf);
                    }
                }

                //Convierto el string con la cantidad en un entero
                $num_unidades = (int)$stock_total;
            } else { //En caso de que la referencia sea errónea, inserto un registro en la BD con un stock de -1, para luego poder revisar más adelante las correspondencias que están "huérfanas" 
                //INSERTO EL RESULTADO DE LA CONSULTA DE STOCK EN LA BD
                $consulta_inserta_stock_pf = "INSERT INTO stock_pf
                                                       (stock_pf_ref,
                                                        stock_pf_fecha,
                                                        stock_pf_hora,
                                                        stock_pf_stock)
                                                    VALUES
                                                       ('$ref_articulo',
                                                        '$fecha_actual',
                                                        '$hora_actual',
                                                        '-1');";
                $bd6->Execute($consulta_inserta_stock_pf);
            
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
//$ref = "10035404";  //Referencia de un Artículo con variantes (colores)
//$ref = "33S04721";  //Referencia de un Artículo con variantes (colores y tallas)
//$ref = "10513100";  //Referencia de un Artículo sin stock (en el día de la prueba)
//$ref = "10045902";  //Referencia de un Artículo sin stock (en el día de la prueba)
//$ref = "10403900";  //Referencia de un Artículo con una sóla variante
//$ref = "MO72645";  //Referencia inexistente

/*
$ref = "11941100";  
$stock = consulta_stock_pf($ref);
echo "Stock PF ".$ref.": ".$stock." unidades";
*/

//FIN de prueba - BORRAR

?>