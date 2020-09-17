<?php

//error_reporting(0);

include_once('curl_functions.inc.php');

//******************************************************//

//Incluyo la Librería PHP Simple HTML DOM Parser
include_once('simple_html_dom.php');

//FUNCIÓN consulta_referencias_ggoya QUE CONSULTA Y ALMACENA LOS DATOS DE TODAS LAS REFERENCIAS PERTENECIENTES A UNA SUBCATEGORIA
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

function consulta_referencias_ggoya($categoria, $subcategoria, $html_man){
    
    include('conexion3.php');
    
    //Almaceno el HTML capturado "a mano" en una variable 
    $html_scraped = $html_man;
    
    //Creo un HTML DOM object (empleando un método de la Librería PHP Simple HTML DOM Parser que he importado)
    $html = str_get_html($html_scraped);
    
    //Voy almacenando los distintos elementos que contienen los valores de cada artículo mostrado, que luego se almacenarán en la BD
    $articulos = $html->find('div.product-card');

    foreach($articulos as $articulo) {
        
        $referencia_articulo = substr($articulo->find('p.product-ref', 0)->innertext, 5);
        $nombre_articulo = $articulo->find('h3.product-name', 0)->find('a', 0)->innertext;
        $url_imagen = $articulo->find('img.img-responsive', 0)->src;

        if($referencia_articulo != "") {
            $consulta_inserta_ref = "INSERT INTO refs_ggoya
                                       (refs_ggoya_referencia,
                                        refs_ggoya_nombre,
                                        refs_ggoya_categoria,
                                        refs_ggoya_subcategoria,
                                        refs_ggoya_url_imagen)
                                    VALUES
                                       ('$referencia_articulo',
                                        '$nombre_articulo',
                                        '$categoria',
                                        '$subcategoria',
                                        '$url_imagen');";
            $bd3->Execute($consulta_inserta_ref);
        }
        
    }
    
    echo "Completado<br>";
    
}

//prueba - BORRAR

/*  
    //TECNOLOGÍA
        
        //Power bank
        consulta_referencias_ggoya("TECNOLOGÍA", "Power bank", '');
       
        //USB
        consulta_referencias_ggoya("TECNOLOGÍA", "USB", '');
        
        //Punteros
        consulta_referencias_ggoya("TECNOLOGÍA", "Punteros", '');
        
        //Ratones
        consulta_referencias_ggoya("TECNOLOGÍA", "Ratones", '');
        
        //Puertos de conexión
        consulta_referencias_ggoya("TECNOLOGÍA", "Puertos de conexión", '');
        
        //Cables
        consulta_referencias_ggoya("TECNOLOGÍA", "Cables", '');
        
        //Auriculares/cascos
        consulta_referencias_ggoya("TECNOLOGÍA", "Auriculares/cascos", '');
        
        //Altavoces
        consulta_referencias_ggoya("TECNOLOGÍA", "Altavoces", '');
        
        //Relojes
        consulta_referencias_ggoya("TECNOLOGÍA", "Relojes", '');
        
        //Estaciones meteorológicas
        consulta_referencias_ggoya("TECNOLOGÍA", "Estaciones meteorológicas", '');
        
        //Varios Tecnología
        consulta_referencias_ggoya("TECNOLOGÍA", "Varios Tecnología", '');
        
    
    
    //NEGOCIOS
        
        //Libretas y cuadernos
        consulta_referencias_ggoya("NEGOCIOS", "Libretas y cuadernos", '');

        //Accesorios de oficina y sobremesa
        consulta_referencias_ggoya("NEGOCIOS", "Accesorios de oficina y sobremesa", '');

        //Portafolios
        consulta_referencias_ggoya("NEGOCIOS", "Portafolios", '');

        //Bolsas y mochilas para congresos
        consulta_referencias_ggoya("NEGOCIOS", "Bolsas y mochilas para congresos", '');

        //Trolleys
        consulta_referencias_ggoya("NEGOCIOS", "Trolleys", '');

        //Conjuntos escritura
        consulta_referencias_ggoya("NEGOCIOS", "Conjuntos escritura", '');

        //Bolígrafos metal
        consulta_referencias_ggoya("NEGOCIOS", "Bolígrafos metal", '');

        //Bolígrafos plástico
        consulta_referencias_ggoya("NEGOCIOS", "Bolígrafos plástico", '');

        //Bolígrafos aluminio
        consulta_referencias_ggoya("NEGOCIOS", "Bolígrafos aluminio", '');

        //Bolígrafos puntero
        consulta_referencias_ggoya("NEGOCIOS", "Bolígrafos puntero", '');

        //Roller
        consulta_referencias_ggoya("NEGOCIOS", "Roller", '');

        //Lapiceros y gomas
        consulta_referencias_ggoya("NEGOCIOS", "Lapiceros y gomas", '');

        //Presentaciones artículos escritura
        consulta_referencias_ggoya("NEGOCIOS", "Presentaciones artículos escritura", '');

        //Fluorescentes
        consulta_referencias_ggoya("NEGOCIOS", "Fluorescentes", '');

        //Bolígrafos y lápices de papel
        consulta_referencias_ggoya("NEGOCIOS", "Bolígrafos y lápices de papel", '');
        
     
    //VIAJE
        
        //Bolsas de deporte
        consulta_referencias_ggoya("VIAJE", "Bolsas de deporte", '');

        //Zapatilleros
        consulta_referencias_ggoya("VIAJE", "Zapatilleros", '');

        //Mochilas
        consulta_referencias_ggoya("VIAJE", "Mochilas", '');

        //Bolsas multifunción
        consulta_referencias_ggoya("VIAJE", "Bolsas multifunción", '');

        //Accesorios viaje
        consulta_referencias_ggoya("VIAJE", "Accesorios viaje", '');

        //Trolleys
        consulta_referencias_ggoya("VIAJE", "Trolleys", '');
        
    
    //COMPLEMENTOS
        
        //Artículos para cuidado personal
        consulta_referencias_ggoya("COMPLEMENTOS", "Artículos para cuidado personal", '');

        //Pulseras
        consulta_referencias_ggoya("COMPLEMENTOS", "Pulseras", '');

        //Abanicos
        consulta_referencias_ggoya("COMPLEMENTOS", "Abanicos", '');

        //Bufandas, foulares y corbatas
        consulta_referencias_ggoya("COMPLEMENTOS", "Bufandas, foulares y corbatas", '');

        //Paraguas
        consulta_referencias_ggoya("COMPLEMENTOS", "Paraguas", '');

        //Tarjeteros
        consulta_referencias_ggoya("COMPLEMENTOS", "Tarjeteros", '');

        //Portaretratos
        consulta_referencias_ggoya("COMPLEMENTOS", "Portaretratos", '');

        //Opciones de presentación
        consulta_referencias_ggoya("COMPLEMENTOS", "Opciones de presentación", '');

        //Vaciabolsillos
        consulta_referencias_ggoya("COMPLEMENTOS", "Vaciabolsillos", '');

        //Llaveros
        consulta_referencias_ggoya("COMPLEMENTOS", "Llaveros", '');

        //Varios complementos personales
        consulta_referencias_ggoya("COMPLEMENTOS", "Varios complementos personales", '');
        
    
    //HOGAR
        
        //Termos y tazas
        consulta_referencias_ggoya("HOGAR", "Termos y tazas", '');

        //Accesorios y utensilios de cocina
        consulta_referencias_ggoya("HOGAR", "Accesorios y utensilios de cocina", '');

        //Accesorios y utensilios para vino
        consulta_referencias_ggoya("HOGAR", "Accesorios y utensilios para vino", '');

        //Artículos textiles para el hogar
        consulta_referencias_ggoya("HOGAR", "Artículos textiles para el hogar", '');

        //Fragancias para el hogar
        consulta_referencias_ggoya("HOGAR", "Fragancias para el hogar", '');
        
    
    //TIEMPO LIBRE
        
        //Toallas y pareos
        consulta_referencias_ggoya("TIEMPO LIBRE", "Toallas y pareos", '');

        //Gafas y accesorios
        consulta_referencias_ggoya("TIEMPO LIBRE", "Gafas y accesorios", '');

        //Sombreros y gorras
        consulta_referencias_ggoya("TIEMPO LIBRE", "Sombreros y gorras", '');

        //Bolsas
        consulta_referencias_ggoya("TIEMPO LIBRE", "Bolsas", '');

        //Accesorios playa
        consulta_referencias_ggoya("TIEMPO LIBRE", "Accesorios playa", '');

        //Juegos
        consulta_referencias_ggoya("TIEMPO LIBRE", "Juegos", '');

        //Bidones y botellas
        consulta_referencias_ggoya("TIEMPO LIBRE", "Bidones y botellas", '');

        //Accesorios para el deporte
        consulta_referencias_ggoya("TIEMPO LIBRE", "Accesorios para el deporte", '');
        
    
    //NIÑOS
        
        //Sets de pinturas y artículos de escritura
        consulta_referencias_ggoya("NIÑOS", "Sets de pinturas y artículos de escritura", '');

        //Mochilas y bolsas
        consulta_referencias_ggoya("NIÑOS", "Mochilas y bolsas", '');

        //Huchas
        consulta_referencias_ggoya("NIÑOS", "Huchas", '');

        //Juguetes
        consulta_referencias_ggoya("NIÑOS", "Juguetes", '');

        //Juguetes para pintar
        consulta_referencias_ggoya("NIÑOS", "Juguetes para pintar", '');

        //Otros
        consulta_referencias_ggoya("NIÑOS", "Otros", '');

        //Peluches
        consulta_referencias_ggoya("NIÑOS", "Peluches", '');
      
    //BRICO
        
        //Linternas
        consulta_referencias_ggoya("BRICO", "Linternas", '');

        //Herramientas y accesorios
        consulta_referencias_ggoya("BRICO", "Herramientas y accesorios", '');

        //Navajas
        consulta_referencias_ggoya("BRICO", "Navajas", '');

        //Flexómetros
        consulta_referencias_ggoya("BRICO", "Flexómetros", '');

        //Encendedores
        consulta_referencias_ggoya("BRICO", "Encendedores", '');
        
    
    //XMAS
        
        //Xmas
        consulta_referencias_ggoya("XMAS", "Xmas", '');
        
     
    //OUTLET
        
        //Outlet
        consulta_referencias_ggoya("OUTLET", "Outlet", '');
        
    
    //NOVEDADES
        
        //Novedades
        consulta_referencias_ggoya("NOVEDADES", "Novedades", '');
    
*/    

//FIN de prueba - BORRAR

?>