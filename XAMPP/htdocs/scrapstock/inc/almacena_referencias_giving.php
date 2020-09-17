<?php

error_reporting(0);

include_once('curl_functions.inc.php');

//******************************************************//

//Incluyo la Librería PHP Simple HTML DOM Parser
include_once('simple_html_dom.php');


//FUNCIÓN consulta_referencias_giving QUE CONSULTA Y ALMACENA LOS DATOS DE TODAS LAS REFERENCIAS PERTENECIENTES A UNA SUBCATEGORÍA
//  Se pasan como parámetros:
//      $categoria          -> El nombre con el que se desea almacenar esa categoría en la BD   
//      $categoria_url      -> El nombre (en el buscador de giving) que figura en la URL para mostrar esa categoría   
//      $subcategoria       -> El nombre con el que se desea almacenar esa subcategoría en la BD   
//      $subcategoria_url   -> El nombre (en el buscador de giving) que figura en la URL para mostrar esa subcategoría   
//      $num_articulos      -> El número de artículos a mostrar en la consulta
//                              (poner un número mayor que el número de resultados
//                               para que todos los artículos de esa subcategoría se muestren en una sóla página)
//  La función no devuelve nada   
//  Lo que hace es almacenar en la BD los datos correspondientes a cada una de las referencias pertenecientes a esa subcategoría:
//      id              (autoincrementado)
//      referencia
//      nombre
//      categoria
//      subcategoria
//      url_imagen

function consulta_referencias_giving($categoria, $categoria_url, $subcategoria, $subcategoria_url, $num_articulos){
        
    include('conexion4.php');

    //En primer lugar "simulamos" el login en la home (https://www.impression-catalogue.com/es)
    //OJO tras introducir el email y la contraseña en el formulario de acceso se pasan por POST y se comprueban en
    //(https://www.impression-catalogue.com/es/bienvenido-su-buscador-de-regalos?destination=node/19668), por lo que es en esa URL en la que simulamos el login
    login("https://www.impression-catalogue.com/es/bienvenido-su-buscador-de-regalos?destination=node/19668", "name=javi@jblasco.es&pass=JavierJimena8066&remember_me=1&form_build_id=form-pdEhS-k8MHvgxdkKcaIUt0KQbrbHhwIA0THAxoDV-Tk&form_id=user_login_block&op=Inicio+de+sesión");

    //Obtengo el código HTML del resultado de la consulta de los artículos pertenecientes a esa subcategoría
    $url_busqueda = "https://www.impression-catalogue.com/es/".$categoria_url."/".$subcategoria_url."?items=".$num_articulos;

    //Una vez obtenida la URL que muestra el resultado de la consulta de la subcategoría, capturo su HTML        
    $html_scraped = grab_page($url_busqueda);  

    //Creo un HTML DOM object (empleando un método de la Librería PHP Simple HTML DOM Parser que he importado)
    $html = str_get_html($html_scraped);

    //Voy almacenando los distintos elementos que contienen los valores de cada artículo mostrado, que luego se almacenarán en la BD
    $articulos = $html->find('ul.liststyle4 li');

    foreach($articulos as $articulo) {
        
        $referencia_articulo = $articulo->find('div.box-info h2', 0)->innertext;
        $nombre_articulo = $articulo->find('div.box-info p', 0)->innertext;
        $url_imagen = $articulo->find('div.box-img img', 0)->src;

        if($referencia_articulo != "") {
            $consulta_inserta_ref = "INSERT INTO refs_giving
                                       (refs_giving_referencia,
                                        refs_giving_nombre,
                                        refs_giving_categoria,
                                        refs_giving_subcategoria,
                                        refs_giving_url_imagen)
                                    VALUES
                                       ('$referencia_articulo',
                                        '$nombre_articulo',
                                        '$categoria',
                                        '$subcategoria',
                                        '$url_imagen');";
            $bd4->Execute($consulta_inserta_ref);
        }
        
    }

    echo "Completado<br>";
        
}


//prueba - BORRAR

    //BOLSAS & VIAJE
    /*
    consulta_referencias_giving("Bolsas & Viaje", "bolsas-viaje", "Accesorios De Viaje", "accesorios-de-viaje", "50");
    consulta_referencias_giving("Bolsas & Viaje", "bolsas-viaje", "Bolsa De La Compra", "bolsa-de-la-compra", "50");
    consulta_referencias_giving("Bolsas & Viaje", "bolsas-viaje", "Bolsas De Playa", "bolsas-de-playa", "10");
    consulta_referencias_giving("Bolsas & Viaje", "bolsas-viaje", "Bolsas Isotérmicas", "bolsas-isotermicas", "50");
    consulta_referencias_giving("Bolsas & Viaje", "bolsas-viaje", "Bolsas Para Promoción", "bolsas-para-promocion", "10");
    consulta_referencias_giving("Bolsas & Viaje", "bolsas-viaje", "Carteras Portadocumentos", "carteras-portadocumentos", "10");
    consulta_referencias_giving("Bolsas & Viaje", "bolsas-viaje", "Deportes / Bolsas de Viaje", "deporte/bolsas-de-viaje", "50");
    consulta_referencias_giving("Bolsas & Viaje", "bolsas-viaje", "GETBAG", "getbag", "10");
    consulta_referencias_giving("Bolsas & Viaje", "bolsas-viaje", "Identificadores De Maleta", "identificadores-de-maleta", "10");
    consulta_referencias_giving("Bolsas & Viaje", "bolsas-viaje", "Maletas", "maletas", "10");
    consulta_referencias_giving("Bolsas & Viaje", "bolsas-viaje", "Mochila Portadocumentos", "mochila-portadocumentos", "50");
    consulta_referencias_giving("Bolsas & Viaje", "bolsas-viaje", "Mochilas", "mochilas", "50");
    consulta_referencias_giving("Bolsas & Viaje", "bolsas-viaje", "Mochilas", "mochilas-0", "50");
    consulta_referencias_giving("Bolsas & Viaje", "bolsas-viaje", "Neceser Para Maquillaje", "neceser-para-maquillaje", "10");
    consulta_referencias_giving("Bolsas & Viaje", "bolsas-viaje", "Petates y Mochilas", "petates-y-mochilas", "50");
    consulta_referencias_giving("Bolsas & Viaje", "bolsas-viaje", "Riñoneras", "rinoneras", "10");
    */

    //CARAMELOS
    /*    
    consulta_referencias_giving("Caramelos", "caramelos", "Caramelos Mentolados", "caramelos-mentolados", "10");
    */

    //CHARLES DICKENS
    /*
    consulta_referencias_giving("Charles Dickens", "charles-dickensr", "Charles Dickens", "charles-dickensr", "50");
    consulta_referencias_giving("Charles Dickens", "charles-dickensr", "Charles Dickens leather accesories", "charles-dickensr-leather-accesories", "10");
    */

    //DIRECTAMENTE DE ORIGEN
    /*
    consulta_referencias_giving("Directamente de Origen", "directamente-de-origen", "Gorras & Gorros", "gorras-gorros", "10");
    */

    //ELECTRONICA
    /*
    consulta_referencias_giving("Electrónica", "electronica", "Accesorios De Ordenador", "accesorios-de-ordenador", "50");
    consulta_referencias_giving("Electrónica", "electronica", "Accesorios Para Móvil", "accesorios-para-movil", "100");
    consulta_referencias_giving("Electrónica", "electronica", "Mp3", "mp3", "10");
    consulta_referencias_giving("Electrónica", "electronica", "Powerbanks & Speakers", "powerbanks-speakers", "50");
    consulta_referencias_giving("Electrónica", "electronica", "Punteros Láser", "punteros-laser", "10");
    consulta_referencias_giving("Electrónica", "electronica", "Radios", "radios", "10");
    consulta_referencias_giving("Electrónica", "electronica", "Reloj De Pared", "reloj-de-pared", "10");
    consulta_referencias_giving("Electrónica", "electronica", "Relojes De Sobremesa", "relojes-de-sobremesa", "10");
    */

    //ESCRITURA
    /*
    consulta_referencias_giving("Escritura", "escritura", "Bolígrafos Exclusivos", "boligrafos-exclusivos", "50");
    consulta_referencias_giving("Escritura", "escritura", "Charles Dickens", "charles-dickensr", "10");
    consulta_referencias_giving("Escritura", "escritura", "Conjuntos De Escritura Charles Dickens", "conjuntos-de-escritura-charles-dick", "10");
    consulta_referencias_giving("Escritura", "escritura", "Escritura", "escritura", "150");
    consulta_referencias_giving("Escritura", "escritura", "Lápices", "lapices", "10");
    consulta_referencias_giving("Escritura", "escritura", "Marcadores Fluorescentes", "marcadores-fluorescentes", "10");
    consulta_referencias_giving("Escritura", "escritura", "Punteros Láser", "punteros-laser", "10");
    consulta_referencias_giving("Escritura", "escritura", "Set De Escritura", "set-de-escritura", "10");
    consulta_referencias_giving("Escritura", "escritura", "Sets De Escritura Exclusivos", "sets-de-escritura-exclusivos", "50");
    consulta_referencias_giving("Escritura", "escritura", "Sets De Lápices Y Colores", "sets-de-lapices-y-colores", "50");
    consulta_referencias_giving("Escritura", "escritura", "Soporte Para Bolígrafos", "soporte-para-boligrafos", "10");
    consulta_referencias_giving("Escritura", "escritura", "Stylus/touchscreen", "stylus/touchscreen", "50");
    */


    //HERRAMIENTAS
    /*
    consulta_referencias_giving("Herramientas", "herramientas", "Accesorios De Bicicleta", "accesorios-de-bicicleta", "50");
    consulta_referencias_giving("Herramientas", "herramientas", "Accesorios De Coche", "accesorios-de-coche", "50");
    consulta_referencias_giving("Herramientas", "herramientas", "Aventura, Set De", "aventura-set-de", "10");
    consulta_referencias_giving("Herramientas", "herramientas", "Cuchillos", "cuchillos", "10");
    consulta_referencias_giving("Herramientas", "herramientas", "Farmacia", "farmacia", "50");
    consulta_referencias_giving("Herramientas", "herramientas", "Herramientas", "herramientas", "50");
    consulta_referencias_giving("Herramientas", "herramientas", "Linterna", "linterna", "10");
    consulta_referencias_giving("Herramientas", "herramientas", "Linternas", "linternas", "50");
    consulta_referencias_giving("Herramientas", "herramientas", "Llaveros Con Pilas", "llaveros-con-pilas", "50");
    consulta_referencias_giving("Herramientas", "herramientas", "Metros", "metros", "50");
    consulta_referencias_giving("Herramientas", "herramientas", "Multi Herramientas", "multi-herramientas", "10");
    consulta_referencias_giving("Herramientas", "herramientas", "Pulsera", "pulsera", "10");
    consulta_referencias_giving("Herramientas", "herramientas", "Reflectantes", "reflectantes", "50");
    consulta_referencias_giving("Herramientas", "herramientas", "Safety/Fireworks glasses", "safety/fireworks-glasses", "10");
    consulta_referencias_giving("Herramientas", "herramientas", "Seguridad Contra El Fuego", "seguridad-contra-el-fuego", "10");
    */

    //HOGAR
    /*
    consulta_referencias_giving("Hogar", "hogar", "Abrebotellas", "abrebotellas", "50");
    consulta_referencias_giving("Hogar", "hogar", "Accesorios De Vino", "accesorios-de-vino", "50");
    consulta_referencias_giving("Hogar", "hogar", "Baking accessories", "baking-accessories", "10");
    consulta_referencias_giving("Hogar", "hogar", "Bolsa De La Compra", "bolsa-de-la-compra", "50");
    consulta_referencias_giving("Hogar", "hogar", "Botella Para El Agua", "botella-para-el-agua", "50");
    consulta_referencias_giving("Hogar", "hogar", "Cocina, Utensilios Para La", "cocina-utensilios-para-la", "50");
    consulta_referencias_giving("Hogar", "hogar", "Cocteleras", "cocteleras", "10");
    consulta_referencias_giving("Hogar", "hogar", "Cuchillos", "cuchillos", "10");
    consulta_referencias_giving("Hogar", "hogar", "Dispensador de bebidas", "dispensador-de-bebidas", "10");
    consulta_referencias_giving("Hogar", "hogar", "Fiambrera", "fiambrera", "10");
    consulta_referencias_giving("Hogar", "hogar", "Fotos", "fotos", "10");
    consulta_referencias_giving("Hogar", "hogar", "Llaveros Sin Pilas", "llaveros-sin-pilas", "50");
    consulta_referencias_giving("Hogar", "hogar", "Petates Y Mochilas", "petates-y-mochilas", "50");
    consulta_referencias_giving("Hogar", "hogar", "Polar", "polar", "10");
    consulta_referencias_giving("Hogar", "hogar", "Reloj De Pared", "reloj-de-pared", "10");
    consulta_referencias_giving("Hogar", "hogar", "Seguridad Contra El Fuego", "seguridad-contra-el-fuego", "10");
    consulta_referencias_giving("Hogar", "hogar", "Set De Limpiacalzado", "set-de-limpiacalzado", "10");
    consulta_referencias_giving("Hogar", "hogar", "Sets De Cuchillos", "sets-de-cuchillos", "10");
    consulta_referencias_giving("Hogar", "hogar", "Tablas De Quesos", "tablas-de-quesos", "10");
    consulta_referencias_giving("Hogar", "hogar", "Tazas", "tazas", "50");
    consulta_referencias_giving("Hogar", "hogar", "Termos", "termos", "50");
    consulta_referencias_giving("Hogar", "hogar", "Velas & Incienso", "velas-incienso", "10");
    consulta_referencias_giving("Hogar", "hogar", "Waffel", "waffel", "10");
    */

    //INFANTIL & JUEGO
    /*
    consulta_referencias_giving("Infantil & Juego", "infantil-juego", "Antistress", "antistress", "50");
    consulta_referencias_giving("Infantil & Juego", "infantil-juego", "Artículos De Fiesta", "articulos-de-fiesta", "10");
    consulta_referencias_giving("Infantil & Juego", "infantil-juego", "Artículos Deportivos", "articulos-deportivos", "10");
    consulta_referencias_giving("Infantil & Juego", "infantil-juego", "Colouring book", "colouring-book", "10");
    consulta_referencias_giving("Infantil & Juego", "infantil-juego", "Hinchables & Artículos De Playa", "hinchables-articulos-de-playa", "50");
    consulta_referencias_giving("Infantil & Juego", "infantil-juego", "Huchas", "huchas", "10");
    consulta_referencias_giving("Infantil & Juego", "infantil-juego", "Juegos", "juegos", "10");
    consulta_referencias_giving("Infantil & Juego", "infantil-juego", "Juguetes", "juguetes", "50");
    consulta_referencias_giving("Infantil & Juego", "infantil-juego", "Lápices", "lapices", "10");
    consulta_referencias_giving("Infantil & Juego", "infantil-juego", "Peluches", "peluches", "50");
    consulta_referencias_giving("Infantil & Juego", "infantil-juego", "Sets De Lápices Y Colores", "sets-de-lapices-y-colores", "50");
    consulta_referencias_giving("Infantil & Juego", "infantil-juego", "Xmas", "xmas", "10");
    */

    //LOYAL TEA
    /*
    consulta_referencias_giving("LoyalTea", "loyaltear", "Té", "te", "50");
    */

    //MEGA DISCOUNT
    /*
    consulta_referencias_giving("Mega Discount", "mega-discount", "Mega Discount", "mega-discount", "100");
    consulta_referencias_giving("Mega Discount", "mega-discount", "Venta De Verano", "venta-de-verano", "10");
    */

    //NAVIDAD
    /*
    consulta_referencias_giving("navidad", "navidad", "Xmas", "xmas", "50");
    */

    //NOVEDADES
    /*
    consulta_referencias_giving("Novedades", "novedades", "Nuevos Artículos", "nuevos-articulos", "50");
    */

    //OCIO
    /*
    consulta_referencias_giving("Ocio", "ocio", "Artículos De Fiesta", "articulos-de-fiesta", "10");
    consulta_referencias_giving("Ocio", "ocio", "Artículos Deportivos", "articulos-deportivos", "50");
    consulta_referencias_giving("Ocio", "ocio", "Aventura, Set De", "aventura-set-de", "10");
    consulta_referencias_giving("Ocio", "ocio", "Barbacoas", "barbacoas", "10");
    consulta_referencias_giving("Ocio", "ocio", "Binoculares", "binoculares", "10");
    consulta_referencias_giving("Ocio", "ocio", "Bolsas De Playa", "bolsas-de-playa", "10");
    consulta_referencias_giving("Ocio", "ocio", "Bolsas Isotérmicas", "bolsas-isotermicas", "50");
    consulta_referencias_giving("Ocio", "ocio", "Botella Para El Agua", "botella-para-el-agua", "50");
    consulta_referencias_giving("Ocio", "ocio", "Cronómetros", "cronometros", "10");
    consulta_referencias_giving("Ocio", "ocio", "Deporte / Bolsas De Viaje", "deporte/bolsas-de-viaje", "50");
    consulta_referencias_giving("Ocio", "ocio", "Dispensador de bebidas", "dispensador-de-bebidas", "10");
    consulta_referencias_giving("Ocio", "ocio", "Fiambrera", "fiambrera", "10");
    consulta_referencias_giving("Ocio", "ocio", "Gafas De Sol", "gafas-de-sol", "10");
    consulta_referencias_giving("Ocio", "ocio", "Gorras", "gorras", "50");
    consulta_referencias_giving("Ocio", "ocio", "Guantes", "guantes", "10");
    consulta_referencias_giving("Ocio", "ocio", "Hinchables & Artículos De Playa", "hinchables-articulos-de-playa", "50");
    consulta_referencias_giving("Ocio", "ocio", "Mantas", "mantas", "10");
    consulta_referencias_giving("Ocio", "ocio", "Mochilas", "mochilas", "50");
    consulta_referencias_giving("Ocio", "ocio", "Picnic", "picnic", "10");
    consulta_referencias_giving("Ocio", "ocio", "Polar", "polar", "10");
    consulta_referencias_giving("Ocio", "ocio", "Protector solar", "protector-solar", "10");
    consulta_referencias_giving("Ocio", "ocio", "Reflectantes", "reflectantes", "50");
    consulta_referencias_giving("Ocio", "ocio", "Tazas", "tazas", "50");
    consulta_referencias_giving("Ocio", "ocio", "Termos", "termos", "50");
    */

    //OFICINA
    /*
    consulta_referencias_giving("Oficina", "oficina", "Accesorios De Oficina", "accesorios-de-oficina", "100");
    consulta_referencias_giving("Oficina", "oficina", "Accesorios De Ordenador", "accesorios-de-ordenador", "50");
    consulta_referencias_giving("Oficina", "oficina", "Accesorios Para Móvil", "accesorios-para-movil", "100");
    consulta_referencias_giving("Oficina", "oficina", "Blocs De Congresos", "blocs-de-congresos", "50");
    consulta_referencias_giving("Oficina", "oficina", "Blocs De Notas", "blocs-de-notas", "50");
    consulta_referencias_giving("Oficina", "oficina", "Bluetooth", "bluetooth", "10");
    consulta_referencias_giving("Oficina", "oficina", "Calculadoras", "calculadoras", "10");
    consulta_referencias_giving("Oficina", "oficina", "Carteras Portadocumentos", "carteras-portadocumentos", "10");
    consulta_referencias_giving("Oficina", "oficina", "Charles Dickens", "charles-dickensr", "10");
    consulta_referencias_giving("Oficina", "oficina", "Cubiletes", "cubiletes", "10");
    consulta_referencias_giving("Oficina", "oficina", "Escritura", "escritura", "150");
    consulta_referencias_giving("Oficina", "oficina", "Estaciones Meteorológicas", "estaciones-meteorologicas", "10");
    consulta_referencias_giving("Oficina", "oficina", "Estándar", "estandar", "10");
    consulta_referencias_giving("Oficina", "oficina", "Láser", "laser", "10");
    consulta_referencias_giving("Oficina", "oficina", "Marcadores Fluorescentes", "marcadores-fluorescentes", "10");
    consulta_referencias_giving("Oficina", "oficina", "Measuring Instruments", "measuring-instruments", "50");
    consulta_referencias_giving("Oficina", "oficina", "Mochila Portadocumentos", "mochila-portadocumentos", "50");
    consulta_referencias_giving("Oficina", "oficina", "Mochilas", "mochilas", "50");
    consulta_referencias_giving("Oficina", "oficina", "Portamemos", "portamemos", "10");
    consulta_referencias_giving("Oficina", "oficina", "Powerbanks & Speakers", "powerbanks-speakers", "50");
    consulta_referencias_giving("Oficina", "oficina", "Punteros Láser", "punteros-laser", "10");
    consulta_referencias_giving("Oficina", "oficina", "Reloj De Pared", "reloj-de-pared", "10");
    consulta_referencias_giving("Oficina", "oficina", "Relojes De Sobremesa", "relojes-de-sobremesa", "10");
    consulta_referencias_giving("Oficina", "oficina", "Sobremesa, Juego De", "sobremesa-juego-de", "10");
    consulta_referencias_giving("Oficina", "oficina", "Stylus/touchscreen", "stylus/touchscreen", "50");
    consulta_referencias_giving("Oficina", "oficina", "Tarjeteros", "tarjeteros", "50");
    */

    //PAPERMATE
    /*
    consulta_referencias_giving("Papermate", "papermate", "Papermate", "papermate", "10");
    */

    //PARAGUAS
    /*
    consulta_referencias_giving("Paraguas", "paraguas", "Cubiletes", "cubiletes", "10");
    consulta_referencias_giving("Paraguas", "paraguas", "Estaciones Meteorológicas", "estaciones-meteorologicas", "50");
    consulta_referencias_giving("Paraguas", "paraguas", "Foldable umbrella", "foldable-umbrella", "10");
    consulta_referencias_giving("Paraguas", "paraguas", "Paraguas", "paraguas", "50");
    consulta_referencias_giving("Paraguas", "paraguas", "Paraguas Anti Elementos", "paraguas-anti-elementos", "10");
    consulta_referencias_giving("Paraguas", "paraguas", "Paraguas Golf", "paraguas-golf", "10");
    consulta_referencias_giving("Paraguas", "paraguas", "Poncho", "poncho", "10");
    */

    //PARKER, WATERMAN & ROTRING
    /*
    consulta_referencias_giving("Parker, Waterman & Rotring", "parker-waterman-rotring", "Parker", "parker", "50");
    consulta_referencias_giving("Parker, Waterman & Rotring", "parker-waterman-rotring", "Rotring", "rotring", "10");
    consulta_referencias_giving("Parker, Waterman & Rotring", "parker-waterman-rotring", "Waterman", "waterman", "10");
    */

    //PERSONAL
    /*
    consulta_referencias_giving("Personal", "personal", "Accesorios De Viaje", "accesorios-de-viaje", "50");
    consulta_referencias_giving("Personal", "personal", "Antistress", "antistress", "50");
    consulta_referencias_giving("Personal", "personal", "Barra Labial", "barra-labial", "10");
    consulta_referencias_giving("Personal", "personal", "Caramelos", "caramelos", "10");
    consulta_referencias_giving("Personal", "personal", "Cuarto De Baño, Accesorios Para El", "cuarto-de-bano-accesorios-para-el", "10");
    consulta_referencias_giving("Personal", "personal", "Espejos De Bolsillo", "espejos-de-bolsillo", "10");
    consulta_referencias_giving("Personal", "personal", "Farmacia", "farmacia", "50");
    consulta_referencias_giving("Personal", "personal", "Gafas De Sol", "gafas-de-sol", "10");
    consulta_referencias_giving("Personal", "personal", "Huchas", "huchas", "10");
    consulta_referencias_giving("Personal", "personal", "Lanyards", "lanyards", "10");
    consulta_referencias_giving("Personal", "personal", "Linternas", "linternas", "50");
    consulta_referencias_giving("Personal", "personal", "Llaveros Con Pilas", "llaveros-con-pilas", "50");
    consulta_referencias_giving("Personal", "personal", "Llaveros Sin Pilas", "llaveros-sin-pilas", "50");
    consulta_referencias_giving("Personal", "personal", "Masajeadores", "masajeadores", "10");
    consulta_referencias_giving("Personal", "personal", "Monederos", "monederos", "50");
    consulta_referencias_giving("Personal", "personal", "Neceser Para Maquillaje", "neceser-para-maquillaje", "10");
    consulta_referencias_giving("Personal", "personal", "Pañuelos De Papel", "panuelos-de-papel", "10");
    consulta_referencias_giving("Personal", "personal", "Pulsera", "pulsera", "10");
    consulta_referencias_giving("Personal", "personal", "Relojes", "relojes", "10");
    consulta_referencias_giving("Personal", "personal", "Set De Limpiacalzado", "set-de-limpiacalzado", "10");
    consulta_referencias_giving("Personal", "personal", "Sets De Manicura", "sets-de-manicura", "10");
    consulta_referencias_giving("Personal", "personal", "Velas & Incienso", "velas-incienso", "10");
    */
    
    //PRODUCTOS PROMOCIONALES SOSTENIBLES
    /*
    consulta_referencias_giving("Productos Promocionales Sostenibles", "productos-promocionales-sostenibles", "Estilo ECO", "estilo-eco", "50");
    consulta_referencias_giving("Productos Promocionales Sostenibles", "productos-promocionales-sostenibles", "FSC", "fsc", "10");
    consulta_referencias_giving("Productos Promocionales Sostenibles", "productos-promocionales-sostenibles", "Materiales naturales", "materiales-naturales", "100");
    consulta_referencias_giving("Productos Promocionales Sostenibles", "productos-promocionales-sostenibles", "PEFC", "pefc", "10");
    consulta_referencias_giving("Productos Promocionales Sostenibles", "productos-promocionales-sostenibles", "Productos de madera", "productos-de-madera", "100");
    consulta_referencias_giving("Productos Promocionales Sostenibles", "productos-promocionales-sostenibles", "Productos de piel", "productos-de-piel", "50");
    consulta_referencias_giving("Productos Promocionales Sostenibles", "productos-promocionales-sostenibles", "Reciclable", "reciclable", "50");
    consulta_referencias_giving("Productos Promocionales Sostenibles", "productos-promocionales-sostenibles", "Solar powered", "solar-powered", "10");
    consulta_referencias_giving("Productos Promocionales Sostenibles", "productos-promocionales-sostenibles", "Fabricado en Europa", "fabricado-en-europa", "50");
    */

//FIN de prueba - BORRAR

?>