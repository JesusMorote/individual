<?php

//error_reporting(0);

include_once('curl_functions.inc.php');

//******************************************************//

//Incluyo la Librería PHP Simple HTML DOM Parser
include_once('simple_html_dom.php');

//FUNCIÓN consulta_referencias_pf QUE CONSULTA Y ALMACENA LOS DATOS DE TODAS LAS REFERENCIAS PERTENECIENTES A UNA SUBCATEGORIA
//  Se pasan como parámetros:
//      $categoria          -> El nombre con el que se desea almacenar esa categoría en la BD
//      $subcategoria       -> El nombre con el que se desea almacenar esa subcategoría en la BD
//      $html_man           -> El string que contiene el html de la página de resultados
//                              (después de esperar que se carguen todos los resultados) - Este string se copia "a mano" navegador
//
//  La función no devuelve nada   
//  Lo que hace es almacenar en la BD los datos correspondientes a cada una de las referencias pertenecientes a esa subcategoría:
//      id              (autoincrementado)
//      referencia
//      nombre
//      categoria
//      subcategoria
//      url_imagen

function consulta_referencias_pf($categoria, $subcategoria, $html_man){
    
    include('conexion7.php');
    
    //Almaceno el HTML capturado "a mano" en una variable 
    $html_scraped = $html_man;
    
    //Creo un HTML DOM object (empleando un método de la Librería PHP Simple HTML DOM Parser que he importado)
    $html = str_get_html($html_scraped);
    
    //Voy almacenando los distintos elementos que contienen los valores de cada artículo mostrado, que luego se almacenarán en la BD
    $articulos = $html->find('div.grid-item');

    foreach($articulos as $articulo) {
        
        $referencia_articulo = $articulo->find('span.item-no', 0)->innertext;
        $nombre_articulo = $articulo->find('p.item-name', 0)->innertext;
        $url_imagen = "https:".$articulo->find('img.prod_img', 0)->src;

        if($referencia_articulo != "") {
            $consulta_inserta_ref = "INSERT INTO refs_pf
                                       (refs_pf_referencia,
                                        refs_pf_nombre,
                                        refs_pf_categoria,
                                        refs_pf_subcategoria,
                                        refs_pf_url_imagen)
                                    VALUES
                                       ('$referencia_articulo',
                                        '$nombre_articulo',
                                        '$categoria',
                                        '$subcategoria',
                                        '$url_imagen');";
            $bd7->Execute($consulta_inserta_ref);
        }
        
    }
    
    echo "Completado<br>";
    
}

//prueba - BORRAR

//EXPRESIONES REGULARES PARA ELIMINAR EN EL HTML SCRAPEADO LOS BLOQUES CON COMILLAS SIMPLES QUE PODRÍAN DAR ERROR
// 
// 'rel'
// '\d+'
// 'send.+'

/*  
    //Accesorios
        
        //Bufandas
        consulta_referencias_pf("Accesorios", "Bufandas", '');
        
        //Gorras
        consulta_referencias_pf("Accesorios", "Gorras", '');
        
        //Sombreros
        consulta_referencias_pf("Accesorios", "Sombreros", '');
        

    //Artículos hogar
        
        //Botella
        consulta_referencias_pf("Artículos hogar", "Botella", '');

        //Botella
        consulta_referencias_pf("Artículos hogar", "Botella", '');
  
        //OJO, SOLO TIENE UNA REFERENCIA, CAPTURAR A MANO...
        //Cups
        consulta_referencias_pf("Artículos hogar", "Cups", '');

        //Jarras
        consulta_referencias_pf("Artículos hogar", "Jarras", '');

        //Vaso
        consulta_referencias_pf("Artículos hogar", "Vaso", '');

    //Artículo promocional
        
        //Accesorios Caliente / Frío
        consulta_referencias_pf("Artículo promocional", "Accesorios Caliente / Frío", '');

        //OJO, SOLO TIENE UNA REFERENCIA, CAPTURAR A MANO...
        //Barra protextora de labios
        consulta_referencias_pf("Artículo promocional", "", '');
  
        //Bolas Antiestrés
        consulta_referencias_pf("Artículo promocional", "Bolas Antiestrés", '');

        //Cintas Acreditativas
        consulta_referencias_pf("Artículo promocional", "Cintas Acreditativas", '');

        //Eventos
        consulta_referencias_pf("Artículo promocional", "Eventos", '');

        //Llaveros
        consulta_referencias_pf("Artículo promocional", "Llaveros", '');
     
     
    //Bolsas & Viaje
        
        //Accesorios
        consulta_referencias_pf("Bolsas & Viaje", "Accesorios", '');

        //Airporters
        consulta_referencias_pf("Bolsas & Viaje", "Airporters", '');

        //Bandoleras
        consulta_referencias_pf("Bolsas & Viaje", "Bandoleras", '');

        //Billeteros
        consulta_referencias_pf("Bolsas & Viaje", "Billeteros", '');

        //Bolsas Business
        consulta_referencias_pf("Bolsas & Viaje", "Bolsas Business", '');

        //Bolsas de Compra
        consulta_referencias_pf("Bolsas & Viaje", "Bolsas de Compra", '');

        //Bolsas de Deporte
        consulta_referencias_pf("Bolsas & Viaje", "Bolsas de Deporte", '');

        //Bolsas de Viaje
        consulta_referencias_pf("Bolsas & Viaje", "Bolsas de Viaje", '');

        //Bolsas para portátil/tableta
        consulta_referencias_pf("Bolsas & Viaje", "Bolsas para portátil/tableta", '');

        //Bolsas para Zapatos
        consulta_referencias_pf("Bolsas & Viaje", "Bolsas para Zapatos", '');

        //Mochilas
        consulta_referencias_pf("Bolsas & Viaje", "Mochilas", '');

        //Neceseres
        consulta_referencias_pf("Bolsas & Viaje", "Neceseres", '');

        //Petate
        consulta_referencias_pf("Bolsas & Viaje", "Petate", '');

    //Casa y Vida
        
        //Accesorios de Vino
        consulta_referencias_pf("Casa y Vida", "Accesorios de Vino", '');

        //Aprons
        consulta_referencias_pf("Casa y Vida", "Aprons", '');

        //Artículos de cristal
        consulta_referencias_pf("Casa y Vida", "Artículos de cristal", '');

        //Blankets & Plaids
        consulta_referencias_pf("Casa y Vida", "Blankets & Plaids", '');

        //Fiambreras
        consulta_referencias_pf("Casa y Vida", "Fiambreras", '');

        //Regalos
        consulta_referencias_pf("Casa y Vida", "Regalos", '');

        //Set de Cuchillos
        consulta_referencias_pf("Casa y Vida", "Set de Cuchillos", '');

        //Set de Servicios
        consulta_referencias_pf("Casa y Vida", "Set de Servicios", '');

        //Utensilios de cocina
        consulta_referencias_pf("Casa y Vida", "Utensilios de cocina", '');

        //Velas
        consulta_referencias_pf("Casa y Vida", "Velas", '');
        
      
    //Chaquetas
        
        //Chalecos
        consulta_referencias_pf("Chaquetas", "Chalecos", '');

        //Chaquetas
        consulta_referencias_pf("Chaquetas", "Chaquetas", '');
        
  
    //Escritura
        
        //Bolígrafos
        consulta_referencias_pf("Escritura", "Bolígrafos", '');
 
        //Bolígrafos Multifunciones
        consulta_referencias_pf("Escritura", "Bolígrafos Multifunciones", '');

        //Embalage/presentación
        consulta_referencias_pf("Escritura", "Embalage/presentación", '');

        //Estuche para Bolígrafos
        consulta_referencias_pf("Escritura", "Estuche para Bolígrafos", '');

        //Marcadores
        consulta_referencias_pf("Escritura", "Marcadores", '');

        //Plumas
        consulta_referencias_pf("Escritura", "Plumas", '');

        //Portaminas
        consulta_referencias_pf("Escritura", "Portaminas", '');

        //Rollerballs
        consulta_referencias_pf("Escritura", "Rollerballs", '');

        //Sets de Escritura
        consulta_referencias_pf("Escritura", "Sets de Escritura", '');
        
 
    //Forro polar
        
        //Forros Outdoor
        consulta_referencias_pf("Forro polar", "Forros Outdoor", '');
        
        
    //Herramientas y Linternas
        
        //Accesorios de Coche
        consulta_referencias_pf("Herramientas y Linternas", "Accesorios de Coche", '');

        //Cintas métricas
        consulta_referencias_pf("Herramientas y Linternas", "Cintas métricas", '');

        //Cuchillos de Bolsillo
        consulta_referencias_pf("Herramientas y Linternas", "Cuchillos de Bolsillo", '');

        //Linternas
        consulta_referencias_pf("Herramientas y Linternas", "Linternas", '');

        //Set de Herramientas
        consulta_referencias_pf("Herramientas y Linternas", "Set de Herramientas", '');
        
 
    //Juegos y juguetes
        
        //Felpa
        consulta_referencias_pf("Juegos y juguetes", "Felpa", '');

        //Games Outdoor
        consulta_referencias_pf("Juegos y juguetes", "Games Outdoor", '');
 
        //OJO, SOLO TIENE UNA REFERENCIA, CAPTURAR A MANO...
        //Juegos de Ajedrez
        consulta_referencias_pf("Juegos y juguetes", "Juegos de Ajedrez", '');
 
        //Juegos de cartas
        consulta_referencias_pf("Juegos y juguetes", "Juegos de cartas", '');

        //Multi Juegos
        consulta_referencias_pf("Juegos y juguetes", "Multi Juegos", '');

        //Pompas
        consulta_referencias_pf("Juegos y juguetes", "Pompas", '');

        //Rompecabezas
        consulta_referencias_pf("Juegos y juguetes", "Rompecabezas", '');
        
 
    //Material de oficina
        
        //Accesorios de escritorio
        consulta_referencias_pf("Material de oficina", "Accesorios de escritorio", '');

        //Blocs de notas
        consulta_referencias_pf("Material de oficina", "Blocs de notas", '');

        //Notebooks
        consulta_referencias_pf("Material de oficina", "Notebooks", '');
        
  
    //Ocio y Golf
        
        //Accesorios de Playa
        consulta_referencias_pf("Ocio y Golf", "Accesorios de Playa", '');

        //Artículos Barbacoa
        consulta_referencias_pf("Ocio y Golf", "Artículos Barbacoa", '');

        //Bicicleta
        consulta_referencias_pf("Ocio y Golf", "Bicicleta", '');
 
        //Bolsas Térmicas
        consulta_referencias_pf("Ocio y Golf", "Bolsas Térmicas", '');

        //Gafas de sol
        consulta_referencias_pf("Ocio y Golf", "Gafas de sol", '');
   
        //OJO, SOLO TIENE UNA REFERENCIA, CAPTURAR A MANO...
        //Hamacas
        consulta_referencias_pf("Ocio y Golf", "Hamacas", '');
 
        //Impermeables
        consulta_referencias_pf("Ocio y Golf", "Impermeables", '');

        //Pelotas
        consulta_referencias_pf("Ocio y Golf", "Pelotas", '');

        //Picnic
        consulta_referencias_pf("Ocio y Golf", "Picnic", '');

        //Podómetro
        consulta_referencias_pf("Ocio y Golf", "Podómetro", '');

        //Prismáticos
        consulta_referencias_pf("Ocio y Golf", "Prismáticos", '');

        //Sport
        consulta_referencias_pf("Ocio y Golf", "Sport", '');
        

    //Oficina y negocio
        
        //Bussiness card holders
        consulta_referencias_pf("Oficina y negocio", "Bussiness card holders", '');

        //Portafolios
        consulta_referencias_pf("Oficina y negocio", "Portafolios", '');
  
        //OJO, SOLO TIENE UNA REFERENCIA, CAPTURAR A MANO...
        //Portatrajes
        consulta_referencias_pf("Oficina y negocio", "Portatrajes", '');
        
     
    //Pantalones
        
        //Pantalones
        consulta_referencias_pf("Pantalones", "Pantalones", '');
        
    
    //Paraguas
        
        //Paraguas 23 Inch Pulgadas
        consulta_referencias_pf("Paraguas", "Paraguas 23 Inch Pulgadas", '');

        //Paraguas 27 Inch Pulgadas
        consulta_referencias_pf("Paraguas", "Paraguas 27 Inch Pulgadas", '');

        //Paraguas 30 Inch Pulgadas
        consulta_referencias_pf("Paraguas", "Paraguas 30 Inch Pulgadas", '');

        //Paraguas 32 Inch Pulgadas
        consulta_referencias_pf("Paraguas", "Paraguas 32 Inch Pulgadas", '');

        //Paraguas plegables
        consulta_referencias_pf("Paraguas", "Paraguas plegables", '');
        
    
    //Relojes
        
        //Despertador de viaje
        consulta_referencias_pf("Relojes", "Despertador de viaje", '');

        //Estaciones Meteorológicas
        consulta_referencias_pf("Relojes", "Estaciones Meteorológicas", '');

        //OJO, SOLO TIENE UNA REFERENCIA, CAPTURAR A MANO...
        //Relojes de sobremesa
        consulta_referencias_pf("Relojes", "Relojes de sobremesa", '');
        
  
    //Relojes de Pulsera
        
        //OJO, SOLO TIENE UNA REFERENCIA, CAPTURAR A MANO...
        //Relojes de Pulsera
        consulta_referencias_pf("Relojes de Pulsera", "Relojes de Pulsera", '');
        
   
    //Salud y cuidado personal
        
        //Albornoces
        consulta_referencias_pf("Salud y cuidado personal", "Albornoces", '');

        //Sets de belleza
        consulta_referencias_pf("Salud y cuidado personal", "Sets de belleza", '');

        //Sets Manicura
        consulta_referencias_pf("Salud y cuidado personal", "Sets Manicura", '');
        
    
    //Seguridad y primeros auxilios
        
        //First aid
        consulta_referencias_pf("Seguridad y primeros auxilios", "First aid", '');

        //Reflective items
        consulta_referencias_pf("Seguridad y primeros auxilios", "Reflective items", '');
        
   
    //Sonido e imagen
        
        //Altavoces
        consulta_referencias_pf("Sonido e imagen", "Altavoces", '');

        //Auriculares
        consulta_referencias_pf("Sonido e imagen", "Auriculares", '');

        //OJO, SOLO TIENE UNA REFERENCIA, CAPTURAR A MANO...
        //Radio Reloj Despertador
        consulta_referencias_pf("Sonido e imagen", "Radio Reloj Despertador", '');
        
     
    //Sudaderas y chalecos
        
        //Jersey
        consulta_referencias_pf("Sudaderas y chalecos", "Jersey", '');

        //Sudaderas
        consulta_referencias_pf("Sudaderas y chalecos", "Sudaderas", '');
        
      
    //Tecnología
        
        //Accesorios para ordenadores
        consulta_referencias_pf("Tecnología", "Accesorios para ordenadores", '');


        consulta_referencias_pf("Tecnología", "Accesorios para ordenadores", '');

        //Accesorios para Smartphone
        consulta_referencias_pf("Tecnología", "Accesorios para Smartphone", '');


        consulta_referencias_pf("Tecnología", "Accesorios para Smartphone", '');

        //Artículos de escritorio
        consulta_referencias_pf("Tecnología", "Artículos de escritorio", '');

        //Audio & Video
        consulta_referencias_pf("Tecnología", "Audio & Video", '');

        //Calculadoras
        consulta_referencias_pf("Tecnología", "Calculadoras", '');

        //Powerbanks
        consulta_referencias_pf("Tecnología", "Powerbanks", '');


        consulta_referencias_pf("Tecnología", "Powerbanks", '');

        //Puertos
        consulta_referencias_pf("Tecnología", "Puertos", '');

        //Punteros Láser
        consulta_referencias_pf("Tecnología", "Punteros Láser", '');
        
       
    //Tops
        
        //Camisetas
        consulta_referencias_pf("Tops", "Camisetas", '');

        //Polos
        consulta_referencias_pf("Tops", "Polos", '');

        //Shirts
        consulta_referencias_pf("Tops", "Shirts", '');
        
    
    //USB
        
        //USB Sticks
        consulta_referencias_pf("USB", "USB Sticks", '');
    
*/    

//FIN de prueba - BORRAR

?>