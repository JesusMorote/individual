<?php

//error_reporting(0);

include_once('curl_functions.inc.php');

//******************************************************//

//Incluyo la Librería PHP Simple HTML DOM Parser
include_once('simple_html_dom.php');


//FUNCIÓN consulta_referencias_mob QUE CONSULTA Y ALMACENA LOS DATOS DE TODAS LAS REFERENCIAS PERTENECIENTES A UNA SUBCATEGORÍA
//  Se pasan como parámetros:
//      $categoria          -> El nombre con el que se desea almacenar esa categoría en la BD   
//      $subcategoria       -> El nombre con el que se desea almacenar esa subcategoría en la BD   
//      $url                -> El valor (en el buscador de mob) que figura en la URL para mostrar esa subcategoría
//        OJO, Sólo se muestran 20 artículos por página, así que par determinadas subcategorías hay que llamar varias veces a la función //            pasándole distintas URL  
//
//  La función no devuelve nada   
//  Lo que hace es almacenar en la BD los datos correspondientes a cada una de las referencias pertenecientes a esa subcategoría:
//      id              (autoincrementado)
//      referencia
//      nombre
//      categoria
//      subcategoria
//      url_imagen
//
//  ATENCIÓN!!! ESTA FORMA DE OBTENER LAS REFERENCIAS DE MOB IMPLICA UNA VISITA POR CADA REFERENCIA PARA OBTENER REF, NOMBRE Y URL IMAGEN
//

function consulta_referencias_mob($categoria, $subcategoria, $url){
    
    include('conexion5.php');

    //En primer lugar "simulamos" el login en la página de login (https://www.midoceanbrands.com/Iberia/es/eur/login)
    //OJO tras introducir el email y la contraseña en el formulario de acceso se pasan por POST y se comprueban en
    //(https://www.midoceanbrands.com/Iberia/es/eur/processLogin), por lo que es en esa URL en la que simulamos el login
    login("https://www.midoceanbrands.com/Iberia/es/eur/processLogin", "SynchronizerToken=d4cb8e5bbfa56afd46dc59a41bdc0f…c00d51bcbb216de6893877b6ff9ce&ShopLoginForm_Login=javi@jblasco.es&ShopLoginForm_Password=JavierJimena8066&rememberMe=on");

    //Obtengo el código HTML del resultado de la consulta de los artículos pertenecientes a esa subcategoría
    $url_busqueda = $url;

    //Una vez obtenida la URL que muestra el resultado de la consulta de la subcategoría, capturo su HTML        
    $html_scraped = grab_page($url_busqueda);

    //Creo un HTML DOM object (empleando un método de la Librería PHP Simple HTML DOM Parser que he importado)
    $html = str_get_html($html_scraped);
    
    //Voy almacenando los distintos elementos que contienen los valores de cada artículo mostrado, que luego se almacenarán en la BD
    //$articulos = $html->find('div.product');    
    // A PARTIR DE AQUÍ NO FUNCIONA PORQUE EL RESULTADO DE LA BÚSQUEDA SE CARGA POR AJAX...
    // (Y NO EXISTEN EN EL HTML SCRAPEADO AÚN NINGÚN DIV CON LA CLASE PRODUCT)
    
    //Lo que voy a hacer es buscar la cadena getSearchedProduct... dentro de elementos <script></script> del html scrapeado
    //pues a esa función se van pasando 
    $scripts = $html->find('script');

    foreach($scripts as $script) {
        
        $texto_script = $script->innertext;
        
        if(strpos($texto_script, "getSearchedProduct") !== false) {
            //echo $texto_script."<br>";
            
            //Obtengo el valor de la referencia a buscar
            $ref_buscar_array = explode("'", $texto_script);
            $ref_buscar = $ref_buscar_array[1];
            
            $url_busqueda_articulo = "https://www.midoceanbrands.com/Iberia/es/eur/-/Product?SKU=".$ref_buscar;
            
            //Scrapeo la página que muestra el artículo correspondiente a esa referencia de búsqueda
            $html_scraped2 = grab_page($url_busqueda_articulo);
            
            $html2 = str_get_html($html_scraped2);
            
            $referencia_articulo = $html2->find('h1.product-title', 0)->find("text", 0);
            $nombre_articulo = $html2->find('div.title-head', 0)->find('div.large-24', 0)->find('div.row', 2)->find('div.columns', 0)->innertext;
            $url_imagen = "https://www.midoceanbrands.com/INTERSHOP/static/WFS/MidOceanBrands-IB-Site/-/MidOceanBrands/es_ES/images/L/".strtolower(str_replace("-", "_", $referencia_articulo)).".jpg";

            //echo $referencia_articulo."<br>";
            //echo $nombre_articulo."<br>";
            //echo $url_imagen."<hr>";

            if($referencia_articulo != "") {
                $consulta_inserta_ref = "INSERT INTO refs_mob
                                           (refs_mob_referencia,
                                            refs_mob_nombre,
                                            refs_mob_categoria,
                                            refs_mob_subcategoria,
                                            refs_mob_url_imagen)
                                        VALUES
                                           ('$referencia_articulo',
                                            '$nombre_articulo',
                                            '$categoria',
                                            '$subcategoria',
                                            '$url_imagen');";
                
                //echo $consulta_inserta_ref."<hr>";
                
                $bd5->Execute($consulta_inserta_ref);
            }
            
            
        }
    }

    echo "Completado<br>";
        
}


//prueba - BORRAR

    /*
    //Tecnología y Accesorios
        
        //Accesorios para ordenadores
        consulta_referencias_mob("Tecnología y Accesorios", "Accesorios para ordenadores", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=ZbasH05CmtAAAAFQah54JNJR&ParamChanged=Category");
        
        //Accesorios para tablets
        consulta_referencias_mob("Tecnología y Accesorios", "Accesorios para tablets", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=rC6sH05COycAAAFQcR54JNJR&ParamChanged=Category");
    
        //Accesorios para teléfonos
        consulta_referencias_mob("Tecnología y Accesorios", "Accesorios para teléfonos", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=r7usH05CLFYAAAFQbB54JNJR&ParamChanged=Category");
        consulta_referencias_mob("Tecnología y Accesorios", "Accesorios para teléfonos", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=r7usH05CLFYAAAFQbB54JNJR&PageNumber=2&ParamChanged=");
        consulta_referencias_mob("Tecnología y Accesorios", "Accesorios para teléfonos", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=r7usH05CLFYAAAFQbB54JNJR&PageNumber=3&ParamChanged=");
        consulta_referencias_mob("Tecnología y Accesorios", "Accesorios para teléfonos", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=r7usH05CLFYAAAFQbB54JNJR&PageNumber=4&ParamChanged=");
    
        //Alatavoces
        consulta_referencias_mob("Tecnología y Accesorios", "Altavoces", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=8MqsH05C_GEAAAFYamF6FEE8&ParamChanged=Category");
        consulta_referencias_mob("Tecnología y Accesorios", "Altavoces", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=8MqsH05C_GEAAAFYamF6FEE8&PageNumber=2&ParamChanged=");
      
        //Auriculares
        consulta_referencias_mob("Tecnología y Accesorios", "Auriculares", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=FeqsH05CFFwAAAFYbmF6FEE8&ParamChanged=Category");
          
        //Cargadores
        consulta_referencias_mob("Tecnología y Accesorios", "Cargadores", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=KWOsH05Cz1AAAAFQch54JNJR&ParamChanged=Category");
        
        //Estaciones meteorológicas
        consulta_referencias_mob("Tecnología y Accesorios", "Estaciones meteorológicas", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=cA.sH05C._MAAAFQYR54JNJR&ParamChanged=Category");
        
        //Fundas
        consulta_referencias_mob("Tecnología y Accesorios", "Fundas", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=kRqsH05CmtIAAAFQah54JNJR&ParamChanged=Category");
      
        //Gafas (VR)
        consulta_referencias_mob("Tecnología y Accesorios", "Gafas (VR)", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=_eisH05CAHgAAAFYcGF6FEE8&ParamChanged=Category");
        
        //Hub
        consulta_referencias_mob("Tecnología y Accesorios", "Hub", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=mkusH05CIAAAAAFYcWF6FEE8&ParamChanged=Category");
       
        //Powerbanks
        consulta_referencias_mob("Tecnología y Accesorios", "Powerbanks", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=ySWsH05COyUAAAFQcR54JNJR&ParamChanged=Category");
        consulta_referencias_mob("Tecnología y Accesorios", "Powerbanks", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=ySWsH05COyUAAAFQcR54JNJR&PageNumber=2&ParamChanged=");
       
        //Ratón
        consulta_referencias_mob("Tecnología y Accesorios", "Ratón", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=5q6sH05CRTAAAAFYcmF6FEE8&ParamChanged=Category");
      
        //Salud -/ Smartwatch
        consulta_referencias_mob("Tecnología y Accesorios", "Salud -/ Smartwatch", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=pamsH05C.WwAAAFYZ2F6FEE8&ParamChanged=Category");
    
    //Bolsas y Viaje
        
        //Accesorios de viaje
        consulta_referencias_mob("Bolsas y Viaje", "Accesorios de viaje", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=68CsH05Cf9kAAAFQWmJ4JNJR&ParamChanged=Category");
        consulta_referencias_mob("Bolsas y Viaje", "Accesorios de viaje", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=68CsH05Cf9kAAAFQWmJ4JNJR&PageNumber=2&ParamChanged=");

        //Bolsa nevera
        consulta_referencias_mob("Bolsas y Viaje", "Bolsa nevera", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=IrysH05C2wcAAAFYgaR6FEE8&ParamChanged=Category");

        //Bolsas de compra y playa
        consulta_referencias_mob("Bolsas y Viaje", "Bolsas de compra y playa", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=muKsH05Ci3oAAAFQSmJ4JNJR&ParamChanged=Category");
        consulta_referencias_mob("Bolsas y Viaje", "Bolsas de compra y playa", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=muKsH05Ci3oAAAFQSmJ4JNJR&PageNumber=2&ParamChanged=");

        //Bolsas de cordones
        consulta_referencias_mob("Bolsas y Viaje", "Bolsas de cordones", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=iKmsH05Cfp0AAAFQY2J4JNJR&ParamChanged=Category");

        //Bolsas de deporte
        consulta_referencias_mob("Bolsas y Viaje", "Bolsas de deporte", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=f7CsH05CCocAAAFQUWJ4JNJR&ParamChanged=Category");

        //Bolsas de papel
        consulta_referencias_mob("Bolsas y Viaje", "Bolsas de papel", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=eDGsH05CqDcAAAFYhaR6FEE8&ParamChanged=Category");

        //Bolsas para ordenador portátil
        consulta_referencias_mob("Bolsas y Viaje", "Bolsas para ordenador portátil", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=mdmsH05C.QsAAAFQQ2J4JNJR&ParamChanged=Category");
        consulta_referencias_mob("Bolsas y Viaje", "Bolsas para ordenador portátil", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=mdmsH05C.QsAAAFQQ2J4JNJR&PageNumber=2&ParamChanged=");

        //Bolsas plegables
        consulta_referencias_mob("Bolsas y Viaje", "Bolsas plegables", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=bQqsH05CbY0AAAFQTWJ4JNJR&ParamChanged=Category");

        //Bolsas portadocumentos
        consulta_referencias_mob("Bolsas y Viaje", "Bolsas portadocumentos", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=QlusH05C9dQAAAFQQmJ4JNJR&ParamChanged=Category");
        consulta_referencias_mob("Bolsas y Viaje", "Bolsas portadocumentos", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=QlusH05C9dQAAAFQQmJ4JNJR&PageNumber=2&ParamChanged=");

        //Carteras/Monederos
        consulta_referencias_mob("Bolsas y Viaje", "Carteras/Monederos", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=PKmsH05CabUAAAFQYWJ4JNJR&ParamChanged=Category");

        //Macutos
        consulta_referencias_mob("Bolsas y Viaje", "Macutos", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=KuCsH05Cp60AAAFQZGJ4JNJR&ParamChanged=Category");

        //Maletas, trolleys y bolsas de viaje
        consulta_referencias_mob("Bolsas y Viaje", "Maletas, trolleys y bolsas de viaje", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=bZ.sH05C9dYAAAFQQmJ4JNJR&ParamChanged=Category");

        //Mantas de viaje
        consulta_referencias_mob("Bolsas y Viaje", "Mantas de viaje", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=5PSsH05C6a0AAAFQW2J4JNJR&ParamChanged=Category");

        //Mochilas
        consulta_referencias_mob("Bolsas y Viaje", "Mochilas", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=qemsH05CFAYAAAFQWGJ4JNJR&ParamChanged=Category");

        //Paraguas
        consulta_referencias_mob("Bolsas y Viaje", "Paraguas", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=XKmsH05CFAoAAAFQWGJ4JNJR&ParamChanged=Category");
        consulta_referencias_mob("Bolsas y Viaje", "Paraguas", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=XKmsH05CFAoAAAFQWGJ4JNJR&PageNumber=2&ParamChanged=");

        //Riñoneras
        consulta_referencias_mob("Bolsas y Viaje", "Riñoneras", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=eeqsH05CqDkAAAFYhaR6FEE8&ParamChanged=Category");

        //Sets de limpieza para calzado
        consulta_referencias_mob("Bolsas y Viaje", "Sets de limpieza para calzado", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=J4usH05CbYsAAAFQTWJ4JNJR&ParamChanged=Category");

    
    //Deporte y Ocio
        
        //Accesorios bicicleta
        consulta_referencias_mob("Deporte y Ocio", "Accesorios bicicleta", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=a1CsH05CsNMAAAFY22l6FCd.&ParamChanged=Category");
        
        //Accesorios de jardín
        consulta_referencias_mob("Deporte y Ocio", "Accesorios de jardín", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=QJqsH05CrPoAAAFY4Wl6FCd.&ParamChanged=Category");
        
        //Brújulas
        consulta_referencias_mob("Deporte y Ocio", "Brújulas", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=OV2sH05CdVEAAAFY5Wl6FCd.&ParamChanged=Category");
        
        //Camping y picnic
        consulta_referencias_mob("Deporte y Ocio", "Camping y picnic", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=aEOsH05CeOQAAAFQlfd4JNJS&ParamChanged=Category");
        consulta_referencias_mob("Deporte y Ocio", "Camping y picnic", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=aEOsH05CeOQAAAFQlfd4JNJS&PageNumber=2&ParamChanged=");
        
        //Fitness
        consulta_referencias_mob("Deporte y Ocio", "Fitness", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=94msH05ClggAAAFQmfd4JNJS&ParamChanged=Category");
        
        //Hinchables
        consulta_referencias_mob("Deporte y Ocio", "Hinchables", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=282sH05CeOYAAAFQlfd4JNJS&ParamChanged=Category");
        
        //Juegos, deporte y eventos
        consulta_referencias_mob("Deporte y Ocio", "Juegos, deporte y eventos", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=1LasH05ClgoAAAFQmfd4JNJS&ParamChanged=Category");
        consulta_referencias_mob("Deporte y Ocio", "Juegos, deporte y eventos", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=1LasH05ClgoAAAFQmfd4JNJS&PageNumber=2&ParamChanged=");
         
        //Playa
        consulta_referencias_mob("Deporte y Ocio", "Playa", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=3zGsH05CeOIAAAFQlfd4JNJS&ParamChanged=Category"); 
        consulta_referencias_mob("Deporte y Ocio", "Playa", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=3zGsH05CeOIAAAFQlfd4JNJS&PageNumber=2&ParamChanged=");
        
        //Podómetros
        consulta_referencias_mob("Deporte y Ocio", "Podómetros", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=Y.msH05ClgwAAAFQmfd4JNJS&ParamChanged=Category");
        
        //Prismáticos
        consulta_referencias_mob("Deporte y Ocio", "Prismáticos", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=ImOsH05CsNUAAAFY22l6FCd.&ParamChanged=Category");

    
    //Cocina y Hogar
        
        //Abridores
        consulta_referencias_mob("Cocina y Hogar", "Abridores", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=1NWsH05CRa0AAAFYj116FCd9&ParamChanged=Category");

        //Accesorios de cocina y utensilios
        consulta_referencias_mob("Cocina y Hogar", "Accesorios de cocina y utensilios", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=G7WsH05CRBgAAAFQwul4JNJR&ParamChanged=Category");
        consulta_referencias_mob("Cocina y Hogar", "Accesorios de cocina y utensilios", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=G7WsH05CRBgAAAFQwul4JNJR&PageNumber=2&ParamChanged=");

        //Accesorios para vino
        consulta_referencias_mob("Cocina y Hogar", "Accesorios para vino", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=elGsH05ChKEAAAFQuul4JNJR&ParamChanged=Category");
        consulta_referencias_mob("Cocina y Hogar", "Accesorios para vino", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=elGsH05ChKEAAAFQuul4JNJR&PageNumber=2&ParamChanged=");

        //Deporte & Botellas agua
        consulta_referencias_mob("Cocina y Hogar", "Deporte & Botellas agua", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=5h6sH05CwC8AAAFYjl16FCd9&ParamChanged=Category");

        //Fiambreras
        consulta_referencias_mob("Cocina y Hogar", "Fiambreras", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=8cWsH05C7f0AAAFYkV16FCd9&ParamChanged=Category");

        //Sets de café y té
        consulta_referencias_mob("Cocina y Hogar", "Sets de café y té", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=nxGsH05CRBYAAAFQwul4JNJR&ParamChanged=Category");

        //Taza de pizarra
        consulta_referencias_mob("Cocina y Hogar", "Taza de pizarra", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=ODasH05C0VsAAAFYkF16FCd9&ParamChanged=Category");

        //Vasos y tazas
        consulta_referencias_mob("Cocina y Hogar", "Vasos y tazas", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=29qsH05Cb6sAAAFQu.l4JNJR&ParamChanged=Category");

        //Vaso térmico & botellas
        consulta_referencias_mob("Cocina y Hogar", "Vaso térmico & botellas", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=ohusH05CscgAAAFYjF16FCd9&ParamChanged=Category");

    
    //Niños y Juegos
        
        //Accesorios
        consulta_referencias_mob("Niños y Juegos", "Accesorios", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=VRusH05C5k4AAAFQPjt4JNJT&ParamChanged=Category");

        //Escritorio infantil
        consulta_referencias_mob("Niños y Juegos", "Escritorio infantil", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=sMOsH05Cb4sAAAFQOjt4JNJT&ParamChanged=Category");

        //Juegos
        consulta_referencias_mob("Niños y Juegos", "Juegos", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=W2OsH05Cb4kAAAFQOjt4JNJT&ParamChanged=Category");
        consulta_referencias_mob("Niños y Juegos", "Juegos", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=W2OsH05Cb4kAAAFQOjt4JNJT&PageNumber=2&ParamChanged=");

        //Lápices y bolígrafos
        consulta_referencias_mob("Niños y Juegos", "Lápices y bolígrafos", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=hl2sH05CUsgAAAFQMTt4JNJT&ParamChanged=Category");

        //Peluches
        consulta_referencias_mob("Niños y Juegos", "Peluches", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=2gWsH05CUsYAAAFQMTt4JNJT&ParamChanged=Category");

        //Pinturas
        consulta_referencias_mob("Niños y Juegos", "Pinturas", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=FGCsH05CUsoAAAFQMTt4JNJT&ParamChanged=Category");
        consulta_referencias_mob("Niños y Juegos", "Pinturas", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=FGCsH05CUsoAAAFQMTt4JNJT&PageNumber=2&ParamChanged=");

    
    //Oficina y Escritura
        
        //Accesorios de escritorio
        consulta_referencias_mob("Oficina y Escritura", "Accesorios de escritorio", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=fqqsH05CAfUAAAFQtXB4JNJS&ParamChanged=Category");
        consulta_referencias_mob("Oficina y Escritura", "Accesorios de escritorio", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=fqqsH05CAfUAAAFQtXB4JNJS&PageNumber=2&ParamChanged=");
        consulta_referencias_mob("Oficina y Escritura", "Accesorios de escritorio", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=fqqsH05CAfUAAAFQtXB4JNJS&PageNumber=3&ParamChanged=");

        //Accesorios de lectura
        consulta_referencias_mob("Oficina y Escritura", "Accesorios de lectura", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=p2esH05CERQAAAFY5zh6FGVK&ParamChanged=Category");

        //Bolígrafos
        consulta_referencias_mob("Oficina y Escritura", "Bolígrafos", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=ceisH05CIHkAAAFQwHB4JNJS&ParamChanged=Category");
        consulta_referencias_mob("Oficina y Escritura", "Bolígrafos", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=ceisH05CIHkAAAFQwHB4JNJS&PageNumber=2&ParamChanged=");
        consulta_referencias_mob("Oficina y Escritura", "Bolígrafos", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=ceisH05CIHkAAAFQwHB4JNJS&PageNumber=3&ParamChanged=");
        consulta_referencias_mob("Oficina y Escritura", "Bolígrafos", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=ceisH05CIHkAAAFQwHB4JNJS&PageNumber=4&ParamChanged=");
        consulta_referencias_mob("Oficina y Escritura", "Bolígrafos", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=ceisH05CIHkAAAFQwHB4JNJS&PageNumber=5&ParamChanged=");

        //Bolígrafos táctiles
        consulta_referencias_mob("Oficina y Escritura", "Bolígrafos táctiles", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=XqysH05CIHsAAAFQwHB4JNJS&ParamChanged=Category");

        //Calculadoras
        consulta_referencias_mob("Oficina y Escritura", "Calculadoras", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=NH2sH05ClhIAAAFQy3B4JNJS&ParamChanged=Category");

        //Lápices
        consulta_referencias_mob("Oficina y Escritura", "Lápices", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=2SKsH05C5DoAAAFQwnB4JNJS&ParamChanged=Category");

        //Libretas y blocs de notas
        consulta_referencias_mob("Oficina y Escritura", "Libretas y blocs de notas", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=x7.sH05CgacAAAFQtnB4JNJS&ParamChanged=Category");
        consulta_referencias_mob("Oficina y Escritura", "Libretas y blocs de notas", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=x7.sH05CgacAAAFQtnB4JNJS&PageNumber=2&ParamChanged=");
        consulta_referencias_mob("Oficina y Escritura", "Libretas y blocs de notas", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=x7.sH05CgacAAAFQtnB4JNJS&PageNumber=3&ParamChanged=");

        //Marcadores
        consulta_referencias_mob("Oficina y Escritura", "Marcadores", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=lfisH05CL0QAAAFQynB4JNJS&ParamChanged=Category");

        
//OJO
//OJO
//OJO
//OJO
//OJO   CAPTURAR DE FORMA MANUAL E INDIVIDUAL

        //Marcos de fotos
        //consulta_referencias_mob("Oficina y Escritura", "Marcos de fotos", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=dhusH05CwnoAAAFQ03B4JNJS&ParamChanged=Category");
//OJO
//OJO
//OJO
//OJO
//OJO

        
        //Portafolios y portadocumentos
        consulta_referencias_mob("Oficina y Escritura", "Portafolios y portadocumentos", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=FEysH05C1ukAAAFQvXB4JNJS&ParamChanged=Category");

        //Punteros láser
        consulta_referencias_mob("Oficina y Escritura", "Punteros láser", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=XWCsH05CAfcAAAFQtXB4JNJS&ParamChanged=Category");

        //Relojes y marcos de fotos
        consulta_referencias_mob("Oficina y Escritura", "Relojes y marcos de fotos", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=IrmsH05Cu28AAAFQzXB4JNJS&ParamChanged=Category");
        consulta_referencias_mob("Oficina y Escritura", "Relojes y marcos de fotos", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=IrmsH05Cu28AAAFQzXB4JNJS&PageNumber=2&ParamChanged=");

        //Sets de escritura
        consulta_referencias_mob("Oficina y Escritura", "Sets de escritura", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=j5SsH05CL0IAAAFQynB4JNJS&ParamChanged=Category");
        consulta_referencias_mob("Oficina y Escritura", "Sets de escritura", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=j5SsH05CL0IAAAFQynB4JNJS&PageNumber=2&ParamChanged=");

        //Trofeos
        consulta_referencias_mob("Oficina y Escritura", "Trofeos", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=FdGsH05Cu3EAAAFQzXB4JNJS&ParamChanged=Category");

    
    //Llaveros y Utensilios
        
        //Accesorios para coche
        consulta_referencias_mob("Llaveros y Utensilios", "Accesorios para coche", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=1QisH05C7TwAAAFQYtt4JNJQ&ParamChanged=Category");
        consulta_referencias_mob("Llaveros y Utensilios", "Accesorios para coche", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=1QisH05C7TwAAAFQYtt4JNJQ&PageNumber=2&ParamChanged=");

        //Anti Estrés
        consulta_referencias_mob("Llaveros y Utensilios", "Anti Estrés", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=XxisH05C0UAAAAFQXtt4JNJQ&ParamChanged=Category");

        //Caramelos
        consulta_referencias_mob("Llaveros y Utensilios", "Caramelos", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=O8isH05C.q8AAAFQXNt4JNJQ&ParamChanged=Category");

        //Cinta métrica
        consulta_referencias_mob("Llaveros y Utensilios", "Cinta métrica", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=6YqsH05C25QAAAFYN1F6FCd8&ParamChanged=Category");

        //Herramientas
        consulta_referencias_mob("Llaveros y Utensilios", "Herramientas", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=LXWsH05Ckr0AAAFQWdt4JNJQ&ParamChanged=Category");
        consulta_referencias_mob("Llaveros y Utensilios", "Herramientas", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=LXWsH05Ckr0AAAFQWdt4JNJQ&PageNumber=2&ParamChanged=");
        consulta_referencias_mob("Llaveros y Utensilios", "Herramientas", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=LXWsH05Ckr0AAAFQWdt4JNJQ&PageNumber=3&ParamChanged=");

        //Linternas
        consulta_referencias_mob("Llaveros y Utensilios", "Linternas", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=HMCsH05C_D8AAAFQZtt4JNJQ&ParamChanged=Category");
        consulta_referencias_mob("Llaveros y Utensilios", "Linternas", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=HMCsH05C_D8AAAFQZtt4JNJQ&PageNumber=2&ParamChanged=");

        //Llaveros
        consulta_referencias_mob("Llaveros y Utensilios", "Llaveros", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=4MesH05C.rEAAAFQXNt4JNJQ&ParamChanged=Category");
        consulta_referencias_mob("Llaveros y Utensilios", "Llaveros", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=4MesH05C.rEAAAFQXNt4JNJQ&PageNumber=2&ParamChanged=");
        consulta_referencias_mob("Llaveros y Utensilios", "Llaveros", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=4MesH05C.rEAAAFQXNt4JNJQ&PageNumber=3&ParamChanged=");

        //Rascador de hielo
        consulta_referencias_mob("Llaveros y Utensilios", "Rascador de hielo", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=PPesH05CbOoAAAFYNFF6FCd8&ParamChanged=Category");

        //Seguridad
        consulta_referencias_mob("Llaveros y Utensilios", "Seguridad", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=QJKsH05CKEwAAAFYOFF6FCd8&ParamChanged=Category");

    
    //Ropa y Complementos
        
        //Accesorios
        consulta_referencias_mob("Ropa y Complementos", "Accesorios", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=4KisH05CepwAAAFQ0aV4JNJR&ParamChanged=Category");
    
        //Bufandas
        consulta_referencias_mob("Ropa y Complementos", "Bufandas", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=B_msH05CbXkAAAFQ1aV4JNJR&ParamChanged=Category");

        //Calzado
        consulta_referencias_mob("Ropa y Complementos", "Calzado", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=O9.sH05Cep4AAAFQ0aV4JNJR&ParamChanged=Category");

        //Camisetas
        consulta_referencias_mob("Ropa y Complementos", "Camisetas", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=xBWsH05C12wAAAFQwKV4JNJR&ParamChanged=Category");
        consulta_referencias_mob("Ropa y Complementos", "Camisetas", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=xBWsH05C12wAAAFQwKV4JNJR&PageNumber=2&ParamChanged=");

        //Chaleco
        consulta_referencias_mob("Ropa y Complementos", "Chaleco", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=djKsH05Cm1sAAAFQyqV4JNJR&ParamChanged=Category");

        //Chaquetas deportivas
        consulta_referencias_mob("Ropa y Complementos", "Chaquetas deportivas", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=f7OsH05Cm10AAAFQyqV4JNJR&ParamChanged=Category");

        //Chubasqueros
        consulta_referencias_mob("Ropa y Complementos", "Chubasqueros", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=NkSsH05CWuoAAAFQ06V4JNJR&ParamChanged=Category");

        //Gafas de sol
        consulta_referencias_mob("Ropa y Complementos", "Gafas de sol", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=kzqsH05Ch_0AAAFQ0KV4JNJR&ParamChanged=Category");

        //Gorras/Gorros
        consulta_referencias_mob("Ropa y Complementos", "Gorras/Gorros", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=DOWsH05C2g8AAAFQzaV4JNJR&ParamChanged=Category");

        //Lanyards
        consulta_referencias_mob("Ropa y Complementos", "Lanyards", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=TmqsH05COAUAAAFYhCx6FB0x&ParamChanged=Category");

        //Polares
        consulta_referencias_mob("Ropa y Complementos", "Polares", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=mUusH05CYh0AAAFQyKV4JNJR&ParamChanged=Category");

        //Polos
        consulta_referencias_mob("Ropa y Complementos", "Polos", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=0lGsH05C13AAAAFQwKV4JNJR&ParamChanged=Category");

        //Relojes
        consulta_referencias_mob("Ropa y Complementos", "Relojes", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=Lt6sH05Cm88AAAFQzqV4JNJR&ParamChanged=Category");

        //Ropa deportiva
        consulta_referencias_mob("Ropa y Complementos", "Ropa deportiva", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=R7WsH05C124AAAFQwKV4JNJR&ParamChanged=Category");

        //Softshell
        consulta_referencias_mob("Ropa y Complementos", "Softshell", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=Z52sH05CoUUAAAFQxqV4JNJR&ParamChanged=Category");

        //Sudaderas
        consulta_referencias_mob("Ropa y Complementos", "Sudaderas", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=uHqsH05CoUMAAAFQxqV4JNJR&ParamChanged=Category");

    
    //Salud y Cuidado Personal
        
        //Accesorios de baño
        consulta_referencias_mob("Salud y Cuidado Personal", "Accesorios de baño", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=IxusH05CYugAAAFQty14JNJS&ParamChanged=Category");

        //Bálsamos labiales
        consulta_referencias_mob("Salud y Cuidado Personal", "Bálsamos labiales", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=vg6sH05CAWoAAAFQyS14JNJS&ParamChanged=Category");

        //Calienta manos & Bolsa masaje
        consulta_referencias_mob("Salud y Cuidado Personal", "Calienta manos & Bolsa masaje", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=cdusH05CwwYAAAFYy596FCd9&ParamChanged=Category");

        //Cepillo de dientes & Sets
        consulta_referencias_mob("Salud y Cuidado Personal", "Cepillo de dientes & Sets", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=33GsH05Ch2wAAAFYzZ96FCd9&ParamChanged=Category");

        //Cuidado personal
        consulta_referencias_mob("Salud y Cuidado Personal", "Cuidado personal", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=ESOsH05CEpsAAAFQti14JNJS&ParamChanged=Category");

        //Espejos
        consulta_referencias_mob("Salud y Cuidado Personal", "Espejos", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=FHKsH05C17IAAAFQyC14JNJS&ParamChanged=Category");

        //Mantas
        consulta_referencias_mob("Salud y Cuidado Personal", "Mantas", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=diesH05CEp0AAAFQti14JNJS&ParamChanged=Category");

        //Neceseres
        consulta_referencias_mob("Salud y Cuidado Personal", "Neceseres", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=Z5esH05CLn4AAAFQwC14JNJS&ParamChanged=Category");

        //Salud
        consulta_referencias_mob("Salud y Cuidado Personal", "Salud", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=SymsH05CLYkAAAFQvi14JNJS&ParamChanged=Category");

        //Sets de manicura
        consulta_referencias_mob("Salud y Cuidado Personal", "Sets de manicura", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=AISsH05C17AAAAFQyC14JNJS&ParamChanged=Category");

        //Velas & sets
        consulta_referencias_mob("Salud y Cuidado Personal", "Velas & sets", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=sRmsH05COlwAAAFYz596FCd9&ParamChanged=Category");

    
    //Outlet, Navidad y Novedades
        
        //Novedades
        consulta_referencias_mob("Outlet, Navidad y Novedades", "Novedades", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=rRisH05CTDsAAAFaB34UpczU&ParamChanged=Category");
        consulta_referencias_mob("Outlet, Navidad y Novedades", "Novedades", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=rRisH05CTDsAAAFaB34UpczU&PageNumber=2&ParamChanged=");
        consulta_referencias_mob("Outlet, Navidad y Novedades", "Novedades", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=rRisH05CTDsAAAFaB34UpczU&PageNumber=3&ParamChanged=");
        consulta_referencias_mob("Outlet, Navidad y Novedades", "Novedades", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=rRisH05CTDsAAAFaB34UpczU&PageNumber=4&ParamChanged=");
        consulta_referencias_mob("Outlet, Navidad y Novedades", "Novedades", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=rRisH05CTDsAAAFaB34UpczU&PageNumber=5&ParamChanged=");
    
        //Navidad
        consulta_referencias_mob("Outlet, Navidad y Novedades", "Navidad", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=x6GsH05Cus8AAAFaPwcUpczV&ParamChanged=Category");
        consulta_referencias_mob("Outlet, Navidad y Novedades", "Navidad", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=x6GsH05Cus8AAAFaPwcUpczV&PageNumber=2&ParamChanged=");
        consulta_referencias_mob("Outlet, Navidad y Novedades", "Navidad", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=x6GsH05Cus8AAAFaPwcUpczV&PageNumber=3&ParamChanged=");
        consulta_referencias_mob("Outlet, Navidad y Novedades", "Navidad", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=x6GsH05Cus8AAAFaPwcUpczV&PageNumber=4&ParamChanged=");
    
        //Outlet
        consulta_referencias_mob("Outlet, Navidad y Novedades", "Outlet", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=ndSsH05Cz5IAAAFapmYsYPsi&ParamChanged=Category");
        consulta_referencias_mob("Outlet, Navidad y Novedades", "Outlet", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=ndSsH05Cz5IAAAFapmYsYPsi&PageNumber=2&ParamChanged=");
        consulta_referencias_mob("Outlet, Navidad y Novedades", "Outlet", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=ndSsH05Cz5IAAAFapmYsYPsi&PageNumber=3&ParamChanged=");
        consulta_referencias_mob("Outlet, Navidad y Novedades", "Outlet", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=ndSsH05Cz5IAAAFapmYsYPsi&PageNumber=4&ParamChanged=");
        consulta_referencias_mob("Outlet, Navidad y Novedades", "Outlet", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=ndSsH05Cz5IAAAFapmYsYPsi&PageNumber=5&ParamChanged=");
        consulta_referencias_mob("Outlet, Navidad y Novedades", "Outlet", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=ndSsH05Cz5IAAAFapmYsYPsi&PageNumber=6&ParamChanged=");
    
        //Bestseller
        consulta_referencias_mob("Outlet, Navidad y Novedades", "Bestseller", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=zESsH05CTvAAAAFeZYNvf8Lx&ParamChanged=Category");
        consulta_referencias_mob("Outlet, Navidad y Novedades", "Bestseller", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=zESsH05CTvAAAAFeZYNvf8Lx&PageNumber=2&ParamChanged=");
        consulta_referencias_mob("Outlet, Navidad y Novedades", "Bestseller", "https://www.midoceanbrands.com/Iberia/es/eur/Filter?SearchTerm=&Category=zESsH05CTvAAAAFeZYNvf8Lx&PageNumber=3&ParamChanged=");
    
    */

//FIN de prueba - BORRAR

?>