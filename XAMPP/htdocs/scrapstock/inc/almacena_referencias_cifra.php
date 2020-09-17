<?php

//error_reporting(0);

include_once('curl_functions.inc.php');

//******************************************************//

//Incluyo la Librería PHP Simple HTML DOM Parser
include_once('simple_html_dom.php');

//FUNCIÓN consulta_referencias_cifra QUE CONSULTA Y ALMACENA LOS DATOS DE TODAS LAS REFERENCIAS PERTENECIENTES A UNA SUBCATEGORIA
//  Se pasan como parámetros:
//      $categoria          -> El nombre con el que se desea almacenar esa categoría en la BD
//      $subcategoria       -> El nombre con el que se desea almacenar esa subcategoría en la BD
//      $subcategoria_url   -> El string que contiene la URL correspondiente a la subcategoría a scrappear
//
//  La función no devuelve nada   
//  Lo que hace es almacenar en la BD los datos correspondientes a cada una de las referencias pertenecientes a esa subcategoría:
//      id              (autoincrementado)
//      referencia
//      nombre
//      categoria
//      subcategoria
//      url_imagen

function consulta_referencias_cifra($categoria, $subcategoria, $subcategoria_url){
    
    include('conexion2.php');
    
    //En primer lugar "simulamos" el login en la home (https://www.cifra.es/index.php?route=common/home)
    //OJO tras introducir el email y la contraseña en el formulario de acceso se pasan por POST y se comprueban en
    //(https://www.cifra.es/index.php?route=account/login), por lo que es en esa URL en la que simulamos el login
    login("https://www.cifra.es/index.php?route=account/login", "email=jbp@jblascopublicidad.com&password=JavierJimena8066");
    
    //Obtengo el código HTML del resultado de la consulta de la subcategoría deseada
    $html_scraped = grab_page($subcategoria_url);
    
    //Creo un HTML DOM object (empleando un método de la Librería PHP Simple HTML DOM Parser que he importado)
    $html = str_get_html($html_scraped);
    
    //Voy almacenando los distintos elementos que contienen los valores de cada artículo mostrado, que luego se almacenarán en la BD
    $articulos = $html->find('table.list', 0)->find('td');

    foreach($articulos as $articulo) {
        
        $contenido = $articulo->find('span');
        
        if(count($contenido) != 0) {
            $referencia_articulo = $articulo->find('span', 0)->innertext;
            
            if($referencia_articulo == "NEW") {
                $referencia_articulo = $articulo->find('span', 1)->innertext;
            }
            
            $nombre_articulo = $articulo->find('a', 1)->innertext;
            $url_imagen = $articulo->find('div.img-icon', 0)->find('img', 0)->src;

            if($referencia_articulo != "") {
                $consulta_inserta_ref = "INSERT INTO refs_cifra2
                                           (refs_cifra2_referencia,
                                            refs_cifra2_nombre,
                                            refs_cifra2_categoria,
                                            refs_cifra2_subcategoria,
                                            refs_cifra2_url_imagen)
                                        VALUES
                                           ('$referencia_articulo',
                                            '$nombre_articulo',
                                            '$categoria',
                                            '$subcategoria',
                                            '$url_imagen');";
                $bd2->Execute($consulta_inserta_ref);
            }
            
            echo $referencia_articulo." - Completado<br>";
            
        }
        
    }
    
}

//prueba - BORRAR
/*
  
    //Accesorios Vehículos
       
        //Cubre Volante / Ventanilla
        consulta_referencias_cifra("Accesorios Vehículos", "Cubre Volante / Ventanilla", 'https://www.cifra.es/index.php?route=product/category&path=18_52');
       
        //Iluminación
        consulta_referencias_cifra("Accesorios Vehículos", "Iluminación", 'https://www.cifra.es/index.php?route=product/category&path=18_422');
                       
        //Parasoles
        consulta_referencias_cifra("Accesorios Vehículos", "Parasoles", 'https://www.cifra.es/index.php?route=product/category&path=18_53');
                       
        //Triángulos
        consulta_referencias_cifra("Accesorios Vehículos", "Triángulos", 'https://www.cifra.es/index.php?route=product/category&path=18_57');
        
  
    //Agendas y Marroquinería
        
        //Agendas
        consulta_referencias_cifra("Agendas y Marroquinería", "Agendas", 'https://www.cifra.es/index.php?route=product/category&path=1_26');
        consulta_referencias_cifra("Agendas y Marroquinería", "Agendas", 'https://www.cifra.es/index.php?route=product/category&path=1_26&page=2');

        //Bloc de Notas
        consulta_referencias_cifra("Agendas y Marroquinería", "Bloc de Notas", 'https://www.cifra.es/index.php?route=product/category&path=1_29');
        consulta_referencias_cifra("Agendas y Marroquinería", "Bloc de Notas", 'https://www.cifra.es/index.php?route=product/category&path=1_29&page=2');

        //Carteras
        consulta_referencias_cifra("Agendas y Marroquinería", "Carteras", 'https://www.cifra.es/index.php?route=product/category&path=1_30');

        //Cinturones
        consulta_referencias_cifra("Agendas y Marroquinería", "Cinturones", 'https://www.cifra.es/index.php?route=product/category&path=1_31');

        //Monederos
        consulta_referencias_cifra("Agendas y Marroquinería", "Monederos", 'https://www.cifra.es/index.php?route=product/category&path=1_33');
        consulta_referencias_cifra("Agendas y Marroquinería", "Monederos", 'https://www.cifra.es/index.php?route=product/category&path=1_33&page=2');
        consulta_referencias_cifra("Agendas y Marroquinería", "Monederos", 'https://www.cifra.es/index.php?route=product/category&path=1_33&page=3');

        //Porta Documentos
        consulta_referencias_cifra("Agendas y Marroquinería", "Porta Documentos", 'https://www.cifra.es/index.php?route=product/category&path=1_34');

        //Porta Llaves
        consulta_referencias_cifra("Agendas y Marroquinería", "Porta Llaves", 'https://www.cifra.es/index.php?route=product/category&path=1_345');

        //Tarjeteros
        consulta_referencias_cifra("Agendas y Marroquinería", "Tarjeteros", 'https://www.cifra.es/index.php?route=product/category&path=1_39');
        consulta_referencias_cifra("Agendas y Marroquinería", "Tarjeteros", 'https://www.cifra.es/index.php?route=product/category&path=1_39&page=2');
        consulta_referencias_cifra("Agendas y Marroquinería", "Tarjeteros", 'https://www.cifra.es/index.php?route=product/category&path=1_39&page=3');
        
       
    //Bolsas de Compra
        
        //Bolsa Plegable
        consulta_referencias_cifra("Bolsas de Compra", "Bolsa Plegable", 'https://www.cifra.es/index.php?route=product/category&path=12_326');

        //Bolsa Regalo
        consulta_referencias_cifra("Bolsas de Compra", "Bolsa Regalo", 'https://www.cifra.es/index.php?route=product/category&path=12_407');
        consulta_referencias_cifra("Bolsas de Compra", "Bolsa Regalo", 'https://www.cifra.es/index.php?route=product/category&path=12_407&page=2');

        //Bolsas
        consulta_referencias_cifra("Bolsas de Compra", "Bolsas", 'https://www.cifra.es/index.php?route=product/category&path=12_40');
        consulta_referencias_cifra("Bolsas de Compra", "Bolsas", 'https://www.cifra.es/index.php?route=product/category&path=12_40&page=2');
        consulta_referencias_cifra("Bolsas de Compra", "Bolsas", 'https://www.cifra.es/index.php?route=product/category&path=12_40&page=3');
        consulta_referencias_cifra("Bolsas de Compra", "Bolsas", 'https://www.cifra.es/index.php?route=product/category&path=12_40&page=4');
        consulta_referencias_cifra("Bolsas de Compra", "Bolsas", 'https://www.cifra.es/index.php?route=product/category&path=12_40&page=5');

        //Bolsas de Pan
        consulta_referencias_cifra("Bolsas de Compra", "Bolsas de Pan", 'https://www.cifra.es/index.php?route=product/category&path=12_331');

        //Bolsas Nevera
        consulta_referencias_cifra("Bolsas de Compra", "Bolsas Nevera", 'https://www.cifra.es/index.php?route=product/category&path=12_325');
        
    
    //Calendarios
        
        //Alfombrillas
        consulta_referencias_cifra("Calendarios", "Alfombrillas", 'https://www.cifra.es/index.php?route=product/category&path=3_43');

        //Almanaques
        consulta_referencias_cifra("Calendarios", "Almanaques", 'https://www.cifra.es/index.php?route=product/category&path=3_44');

        //Bolígrafos
        //consulta_referencias_cifra("Calendarios", "Bolígrafos", 'https://www.cifra.es/index.php?route=product/category&path=3_45');

        //Calendarios
        consulta_referencias_cifra("Calendarios", "Calendarios", 'https://www.cifra.es/index.php?route=product/category&path=3_46');

        //Portalápices
        consulta_referencias_cifra("Calendarios", "Portalápices", 'https://www.cifra.es/index.php?route=product/category&path=3_48');
 
    
    //Complementos y Detalles
        
        //Antiestrés
        consulta_referencias_cifra("Complementos y Detalles", "Antiestrés", 'https://www.cifra.es/index.php?route=product/category&path=14_367');

        //Barajas
        consulta_referencias_cifra("Complementos y Detalles", "Barajas", 'https://www.cifra.es/index.php?route=product/category&path=14_229');
    
        //Bolsas
        consulta_referencias_cifra("Complementos y Detalles", "Bolsas", 'https://www.cifra.es/index.php?route=product/category&path=14_178');
        consulta_referencias_cifra("Complementos y Detalles", "Bolsas", 'https://www.cifra.es/index.php?route=product/category&path=14_178&page=2');

        //Cajitas
        consulta_referencias_cifra("Complementos y Detalles", "Cajitas", 'https://www.cifra.es/index.php?route=product/category&path=14_230');

        //Camiones
        consulta_referencias_cifra("Complementos y Detalles", "Camiones", 'https://www.cifra.es/index.php?route=product/category&path=14_160');

        //Cintas
        consulta_referencias_cifra("Complementos y Detalles", "Cintas", 'https://www.cifra.es/index.php?route=product/category&path=14_231');

        //Corbatas y Pañuelos
        consulta_referencias_cifra("Complementos y Detalles", "Corbatas y Pañuelos", 'https://www.cifra.es/index.php?route=product/category&path=14_232');

        //Detalles
        consulta_referencias_cifra("Complementos y Detalles", "Detalles", 'https://www.cifra.es/index.php?route=product/category&path=14_233');

        //Gafas
        consulta_referencias_cifra("Complementos y Detalles", "Gafas", 'https://www.cifra.es/index.php?route=product/category&path=14_235');
        consulta_referencias_cifra("Complementos y Detalles", "Gafas", 'https://www.cifra.es/index.php?route=product/category&path=14_235&page=2');

        //Maceta Solar
        consulta_referencias_cifra("Complementos y Detalles", "Maceta Solar", 'https://www.cifra.es/index.php?route=product/category&path=14_425');

        //Mascotas
        consulta_referencias_cifra("Complementos y Detalles", "Mascotas", 'https://www.cifra.es/index.php?route=product/category&path=14_386');

        //Paraguas
        consulta_referencias_cifra("Complementos y Detalles", "Paraguas", 'https://www.cifra.es/index.php?route=product/category&path=14_238');
        consulta_referencias_cifra("Complementos y Detalles", "Paraguas", 'https://www.cifra.es/index.php?route=product/category&path=14_238&page=2');

        //Patucos
        //consulta_referencias_cifra("Complementos y Detalles", "Patucos", 'https://www.cifra.es/index.php?route=product/category&path=14_239');

        //Ponchos
        consulta_referencias_cifra("Complementos y Detalles", "Ponchos", 'https://www.cifra.es/index.php?route=product/category&path=14_241');

        //Pulseras
        consulta_referencias_cifra("Complementos y Detalles", "Pulseras", 'https://www.cifra.es/index.php?route=product/category&path=14_190');
        consulta_referencias_cifra("Complementos y Detalles", "Pulseras", 'https://www.cifra.es/index.php?route=product/category&path=14_190&page=2');

        //Quitapelusas
        consulta_referencias_cifra("Complementos y Detalles", "Quitapelusas", 'https://www.cifra.es/index.php?route=product/category&path=14_432');

        //Toallas
        consulta_referencias_cifra("Complementos y Detalles", "Toallas", 'https://www.cifra.es/index.php?route=product/category&path=14_243');
        
    
    //Conmemoraciones Religiosas
        
        //Llaveros
        consulta_referencias_cifra("Conmemoraciones Religiosas", "Llaveros", 'https://www.cifra.es/index.php?route=product/category&path=6_244');

        //Retablos
        consulta_referencias_cifra("Conmemoraciones Religiosas", "Retablos", 'https://www.cifra.es/index.php?route=product/category&path=6_246');

        //Rosarios
        consulta_referencias_cifra("Conmemoraciones Religiosas", "Rosarios", 'https://www.cifra.es/index.php?route=product/category&path=6_247');
        
   
    //Display Rapid
        
        //Atriles / Portagráficas
        consulta_referencias_cifra("Display Rapid", "Atriles / Portagráficas", 'https://www.cifra.es/index.php?route=product/category&path=393_399');

        //Banderas / Soportes
        consulta_referencias_cifra("Display Rapid", "Banderas / Soportes", 'https://www.cifra.es/index.php?route=product/category&path=393_401');

        //Banners
        consulta_referencias_cifra("Display Rapid", "Banners", 'https://www.cifra.es/index.php?route=product/category&path=393_397');

        //Caballetes
        consulta_referencias_cifra("Display Rapid", "Caballetes", 'https://www.cifra.es/index.php?route=product/category&path=393_400');

        //Carpas
        consulta_referencias_cifra("Display Rapid", "Carpas", 'https://www.cifra.es/index.php?route=product/category&path=393_403');
        consulta_referencias_cifra("Display Rapid", "Carpas", 'https://www.cifra.es/index.php?route=product/category&path=393_403&page=2');

        //Molduras / Marcos
        consulta_referencias_cifra("Display Rapid", "Molduras / Marcos", 'https://www.cifra.es/index.php?route=product/category&path=393_398');

        //Mostradores / Expositores
        consulta_referencias_cifra("Display Rapid", "Mostradores / Expositores", 'https://www.cifra.es/index.php?route=product/category&path=393_395');

        //Pop Up
        consulta_referencias_cifra("Display Rapid", "Pop Up", 'https://www.cifra.es/index.php?route=product/category&path=393_396');

        //Postes
        consulta_referencias_cifra("Display Rapid", "Postes", 'https://www.cifra.es/index.php?route=product/category&path=393_402');

        //Roll Up
        consulta_referencias_cifra("Display Rapid", "Roll Up", 'https://www.cifra.es/index.php?route=product/category&path=393_394');
        
      
    //Electrónica
        
        //Alfombrillas
        consulta_referencias_cifra("Electrónica", "Alfombrillas", 'https://www.cifra.es/index.php?route=product/category&path=7_431');

        //Altavoces
        consulta_referencias_cifra("Electrónica", "Altavoces", 'https://www.cifra.es/index.php?route=product/category&path=7_435');

        //Auriculares
        consulta_referencias_cifra("Electrónica", "Auriculares", 'https://www.cifra.es/index.php?route=product/category&path=7_58');

        //Calculadoras
        consulta_referencias_cifra("Electrónica", "Calculadoras", 'https://www.cifra.es/index.php?route=product/category&path=7_59');

        //Cargadores
        consulta_referencias_cifra("Electrónica", "Cargadores", 'https://www.cifra.es/index.php?route=product/category&path=7_60');

        //Estaciones
        consulta_referencias_cifra("Electrónica", "Estaciones", 'https://www.cifra.es/index.php?route=product/category&path=7_61');

        //Fundas y Soportes
        consulta_referencias_cifra("Electrónica", "Fundas y Soportes", 'https://www.cifra.es/index.php?route=product/category&path=7_68');
        consulta_referencias_cifra("Electrónica", "Fundas y Soportes", 'https://www.cifra.es/index.php?route=product/category&path=7_68&page=2');

        //Guantes
        consulta_referencias_cifra("Electrónica", "Guantes", 'https://www.cifra.es/index.php?route=product/category&path=7_420');

        //Informática
        consulta_referencias_cifra("Electrónica", "Informática", 'https://www.cifra.es/index.php?route=product/category&path=7_366');
        consulta_referencias_cifra("Electrónica", "Informática", 'https://www.cifra.es/index.php?route=product/category&path=7_366&page=2');

        //Lentes
        consulta_referencias_cifra("Electrónica", "Lentes", 'https://www.cifra.es/index.php?route=product/category&path=7_427');

        //MonoPod Selfie
        consulta_referencias_cifra("Electrónica", "MonoPod Selfie", 'https://www.cifra.es/index.php?route=product/category&path=7_421');

        //Pilas
        consulta_referencias_cifra("Electrónica", "Pilas", 'https://www.cifra.es/index.php?route=product/category&path=7_63');

        //Portalápices
        //consulta_referencias_cifra("Electrónica", "Portalápices", 'https://www.cifra.es/index.php?route=product/category&path=7_64');

        //Power Banks
        consulta_referencias_cifra("Electrónica", "Power Banks", 'https://www.cifra.es/index.php?route=product/category&path=7_409');
        consulta_referencias_cifra("Electrónica", "Power Banks", 'https://www.cifra.es/index.php?route=product/category&path=7_409&page=2');
        consulta_referencias_cifra("Electrónica", "Power Banks", 'https://www.cifra.es/index.php?route=product/category&path=7_409&page=3');

        //Punteros
        consulta_referencias_cifra("Electrónica", "Punteros", 'https://www.cifra.es/index.php?route=product/category&path=7_365');

        //Radios
        consulta_referencias_cifra("Electrónica", "Radios", 'https://www.cifra.es/index.php?route=product/category&path=7_65');

        //Relojes
        consulta_referencias_cifra("Electrónica", "Relojes", 'https://www.cifra.es/index.php?route=product/category&path=7_67');

        //Relojes Inteligentes
        consulta_referencias_cifra("Electrónica", "Relojes Inteligentes", 'https://www.cifra.es/index.php?route=product/category&path=7_437');

        //USB
        consulta_referencias_cifra("Electrónica", "USB", 'https://www.cifra.es/index.php?route=product/category&path=7_70');
        consulta_referencias_cifra("Electrónica", "USB", 'https://www.cifra.es/index.php?route=product/category&path=7_70&page=2');

        //Visor 3D
        consulta_referencias_cifra("Electrónica", "Visor 3D", 'https://www.cifra.es/index.php?route=product/category&path=7_438');
        
      
    //Escritura
        
        //Bolígrafos Metal
        consulta_referencias_cifra("Escritura", "Bolígrafos Metal", 'https://www.cifra.es/index.php?route=product/category&path=8_71');
        consulta_referencias_cifra("Escritura", "Bolígrafos Metal", 'https://www.cifra.es/index.php?route=product/category&path=8_71&page=2');
        consulta_referencias_cifra("Escritura", "Bolígrafos Metal", 'https://www.cifra.es/index.php?route=product/category&path=8_71&page=3');

        //Bolígrafos Mixto Metal
        consulta_referencias_cifra("Escritura", "Bolígrafos Mixto Metal", 'https://www.cifra.es/index.php?route=product/category&path=8_433');
        consulta_referencias_cifra("Escritura", "Bolígrafos Mixto Metal", 'https://www.cifra.es/index.php?route=product/category&path=8_433&page=2');
        consulta_referencias_cifra("Escritura", "Bolígrafos Mixto Metal", 'https://www.cifra.es/index.php?route=product/category&path=8_433&page=3');

        //Bolígrafos Plástico
        consulta_referencias_cifra("Escritura", "Bolígrafos Plástico", 'https://www.cifra.es/index.php?route=product/category&path=8_380');
        consulta_referencias_cifra("Escritura", "Bolígrafos Plástico", 'https://www.cifra.es/index.php?route=product/category&path=8_380&page=2');
        consulta_referencias_cifra("Escritura", "Bolígrafos Plástico", 'https://www.cifra.es/index.php?route=product/category&path=8_380&page=3');
        consulta_referencias_cifra("Escritura", "Bolígrafos Plástico", 'https://www.cifra.es/index.php?route=product/category&path=8_380&page=4');
        consulta_referencias_cifra("Escritura", "Bolígrafos Plástico", 'https://www.cifra.es/index.php?route=product/category&path=8_380&page=5');

        //Bolígrafos Smartphone
        consulta_referencias_cifra("Escritura", "Bolígrafos Smartphone", 'https://www.cifra.es/index.php?route=product/category&path=8_381');
        consulta_referencias_cifra("Escritura", "Bolígrafos Smartphone", 'https://www.cifra.es/index.php?route=product/category&path=8_381&page=2');
        consulta_referencias_cifra("Escritura", "Bolígrafos Smartphone", 'https://www.cifra.es/index.php?route=product/category&path=8_381&page=3');
        consulta_referencias_cifra("Escritura", "Bolígrafos Smartphone", 'https://www.cifra.es/index.php?route=product/category&path=8_381&page=4');
        consulta_referencias_cifra("Escritura", "Bolígrafos Smartphone", 'https://www.cifra.es/index.php?route=product/category&path=8_381&page=5');

        //Estuches
        consulta_referencias_cifra("Escritura", "Estuches", 'https://www.cifra.es/index.php?route=product/category&path=8_74');

        //Fundas
        consulta_referencias_cifra("Escritura", "Fundas", 'https://www.cifra.es/index.php?route=product/category&path=8_75');

        //Gráficas
        consulta_referencias_cifra("Escritura", "Gráficas", 'https://www.cifra.es/index.php?route=product/category&path=8_429');

        //Lápices
        consulta_referencias_cifra("Escritura", "Lápices", 'https://www.cifra.es/index.php?route=product/category&path=8_76');

        //Marcadores
        consulta_referencias_cifra("Escritura", "Marcadores", 'https://www.cifra.es/index.php?route=product/category&path=8_77');

        //Portalápices
        consulta_referencias_cifra("Escritura", "Portalápices", 'https://www.cifra.es/index.php?route=product/category&path=8_330');

        //Portaminas
        consulta_referencias_cifra("Escritura", "Portaminas", 'https://www.cifra.es/index.php?route=product/category&path=8_78');

        //Roller
        consulta_referencias_cifra("Escritura", "Roller", 'https://www.cifra.es/index.php?route=product/category&path=8_79');

        //Set / Juegos
        consulta_referencias_cifra("Escritura", "Set / Juegos", 'https://www.cifra.es/index.php?route=product/category&path=8_80');
        
 
    //Fiestas y Eventos
        
        //Animación
        consulta_referencias_cifra("Fiestas y Eventos", "Animación", 'https://www.cifra.es/index.php?route=product/category&path=20_434');

        //Banderas
        consulta_referencias_cifra("Fiestas y Eventos", "Banderas", 'https://www.cifra.es/index.php?route=product/category&path=20_81');
        consulta_referencias_cifra("Fiestas y Eventos", "Banderas", 'https://www.cifra.es/index.php?route=product/category&path=20_81&page=2');

        //Banderines
        consulta_referencias_cifra("Fiestas y Eventos", "Banderines", 'https://www.cifra.es/index.php?route=product/category&path=20_82');
        consulta_referencias_cifra("Fiestas y Eventos", "Banderines", 'https://www.cifra.es/index.php?route=product/category&path=20_82&page=2');

        //Gafas
        //consulta_referencias_cifra("Fiestas y Eventos", "Gafas", 'https://www.cifra.es/index.php?route=product/category&path=20_87');

        //Gorros
        consulta_referencias_cifra("Fiestas y Eventos", "Gorros", 'https://www.cifra.es/index.php?route=product/category&path=20_88');

        //Palos Tambor
        consulta_referencias_cifra("Fiestas y Eventos", "Palos Tambor", 'https://www.cifra.es/index.php?route=product/category&path=20_90');

        //Pay Pay
        //consulta_referencias_cifra("Fiestas y Eventos", "Pay Pay", 'https://www.cifra.es/index.php?route=product/category&path=20_436');

        //Pañoletas
        consulta_referencias_cifra("Fiestas y Eventos", "Pañoletas", 'https://www.cifra.es/index.php?route=product/category&path=20_91');

        //Pulseras
        consulta_referencias_cifra("Fiestas y Eventos", "Pulseras", 'https://www.cifra.es/index.php?route=product/category&path=20_93');
        consulta_referencias_cifra("Fiestas y Eventos", "Pulseras", 'https://www.cifra.es/index.php?route=product/category&path=20_93&page=2');

        //Silbatos
        consulta_referencias_cifra("Fiestas y Eventos", "Silbatos", 'https://www.cifra.es/index.php?route=product/category&path=20_415');
        
    
    //Fumador
        
        //Ceniceros
        consulta_referencias_cifra("Fumador", "Ceniceros", 'https://www.cifra.es/index.php?route=product/category&path=16_94');

        //Colilleros
        consulta_referencias_cifra("Fumador", "Colilleros", 'https://www.cifra.es/index.php?route=product/category&path=16_95');

        //Encendedores
        consulta_referencias_cifra("Fumador", "Encendedores", 'https://www.cifra.es/index.php?route=product/category&path=16_96');
        consulta_referencias_cifra("Fumador", "Encendedores", 'https://www.cifra.es/index.php?route=product/category&path=16_96&page=2');

        //Trituradores
        consulta_referencias_cifra("Fumador", "Trituradores", 'https://www.cifra.es/index.php?route=product/category&path=16_391');
        
     
    //Gorras y Sombreros
        
        //Cintas
        consulta_referencias_cifra("Gorras y Sombreros", "Cintas", 'https://www.cifra.es/index.php?route=product/category&path=23_97');

        //Gorras
        consulta_referencias_cifra("Gorras y Sombreros", "Gorras", 'https://www.cifra.es/index.php?route=product/category&path=23_98');
        consulta_referencias_cifra("Gorras y Sombreros", "Gorras", 'https://www.cifra.es/index.php?route=product/category&path=23_98&page=2');
        consulta_referencias_cifra("Gorras y Sombreros", "Gorras", 'https://www.cifra.es/index.php?route=product/category&path=23_98&page=3');

        //Sombreros
        consulta_referencias_cifra("Gorras y Sombreros", "Sombreros", 'https://www.cifra.es/index.php?route=product/category&path=23_99');
        consulta_referencias_cifra("Gorras y Sombreros", "Sombreros", 'https://www.cifra.es/index.php?route=product/category&path=23_99&page=2');
        
   
    //Herramientas y Laboral
        
        //Camisas
        consulta_referencias_cifra("Herramientas y Laboral", "Camisas", 'https://www.cifra.es/index.php?route=product/category&path=17_111');

        //Chalecos
        consulta_referencias_cifra("Herramientas y Laboral", "Chalecos", 'https://www.cifra.es/index.php?route=product/category&path=17_114');
        consulta_referencias_cifra("Herramientas y Laboral", "Chalecos", 'https://www.cifra.es/index.php?route=product/category&path=17_114&page=2');

        //Fundas
        consulta_referencias_cifra("Herramientas y Laboral", "Fundas", 'https://www.cifra.es/index.php?route=product/category&path=17_116');

        //Herramientas
        consulta_referencias_cifra("Herramientas y Laboral", "Herramientas", 'https://www.cifra.es/index.php?route=product/category&path=17_128');

        //Linternas
        consulta_referencias_cifra("Herramientas y Laboral", "Linternas", 'https://www.cifra.es/index.php?route=product/category&path=17_118');
        consulta_referencias_cifra("Herramientas y Laboral", "Linternas", 'https://www.cifra.es/index.php?route=product/category&path=17_118&page=2');

        //Medidores
        consulta_referencias_cifra("Herramientas y Laboral", "Medidores", 'https://www.cifra.es/index.php?route=product/category&path=17_120');

        //Metros
        consulta_referencias_cifra("Herramientas y Laboral", "Metros", 'https://www.cifra.es/index.php?route=product/category&path=17_121');

        //Multiusos
        consulta_referencias_cifra("Herramientas y Laboral", "Multiusos", 'https://www.cifra.es/index.php?route=product/category&path=17_123');

        //Navajas
        consulta_referencias_cifra("Herramientas y Laboral", "Navajas", 'https://www.cifra.es/index.php?route=product/category&path=17_124');

        //Oficina
        consulta_referencias_cifra("Herramientas y Laboral", "Oficina", 'https://www.cifra.es/index.php?route=product/category&path=17_131');

        //Parkas
        consulta_referencias_cifra("Herramientas y Laboral", "Parkas", 'https://www.cifra.es/index.php?route=product/category&path=17_125');

        //Portafotos
        consulta_referencias_cifra("Herramientas y Laboral", "Portafotos", 'https://www.cifra.es/index.php?route=product/category&path=17_242');

        //Sillas
        //consulta_referencias_cifra("Herramientas y Laboral", "Sillas", 'https://www.cifra.es/index.php?route=product/category&path=17_129');
        
    
    //Hogar
        
        //Abrebotellas
        consulta_referencias_cifra("Hogar", "Abrebotellas", 'https://www.cifra.es/index.php?route=product/category&path=13_132');

        //Agarradores
        consulta_referencias_cifra("Hogar", "Agarradores", 'https://www.cifra.es/index.php?route=product/category&path=13_134');

        //Bolsas
        consulta_referencias_cifra("Hogar", "Bolsas", 'https://www.cifra.es/index.php?route=product/category&path=13_135');

        //Cocina
        consulta_referencias_cifra("Hogar", "Cocina", 'https://www.cifra.es/index.php?route=product/category&path=13_426');

        //Cubre Jamones
        consulta_referencias_cifra("Hogar", "Cubre Jamones", 'https://www.cifra.es/index.php?route=product/category&path=13_136');

        //Delantales
        consulta_referencias_cifra("Hogar", "Delantales", 'https://www.cifra.es/index.php?route=product/category&path=13_140');
        consulta_referencias_cifra("Hogar", "Delantales", 'https://www.cifra.es/index.php?route=product/category&path=13_140&page=2');

        //Estuches
        consulta_referencias_cifra("Hogar", "Estuches", 'https://www.cifra.es/index.php?route=product/category&path=13_141');

        //Fiambrera
        consulta_referencias_cifra("Hogar", "Fiambrera", 'https://www.cifra.es/index.php?route=product/category&path=13_142');

        //Gorros
        consulta_referencias_cifra("Hogar", "Gorros", 'https://www.cifra.es/index.php?route=product/category&path=13_145');

        //Imanes
        consulta_referencias_cifra("Hogar", "Imanes", 'https://www.cifra.es/index.php?route=product/category&path=13_418');

        //Jarras y Vasos
        consulta_referencias_cifra("Hogar", "Jarras y Vasos", 'https://www.cifra.es/index.php?route=product/category&path=13_147');

        //Mandiles
        consulta_referencias_cifra("Hogar", "Mandiles", 'https://www.cifra.es/index.php?route=product/category&path=13_149');

        //Mugs
        consulta_referencias_cifra("Hogar", "Mugs", 'https://www.cifra.es/index.php?route=product/category&path=13_150');
        consulta_referencias_cifra("Hogar", "Mugs", 'https://www.cifra.es/index.php?route=product/category&path=13_150&page=2');

        //Set
        consulta_referencias_cifra("Hogar", "Set", 'https://www.cifra.es/index.php?route=product/category&path=13_153');

        //Utensilios Cocina
        consulta_referencias_cifra("Hogar", "Utensilios Cocina", 'https://www.cifra.es/index.php?route=product/category&path=13_137');
        
     
    //Infantil
        
        //Balones
        consulta_referencias_cifra("Infantil", "Balones", 'https://www.cifra.es/index.php?route=product/category&path=9_172');

        //Bolsas
        consulta_referencias_cifra("Infantil", "Bolsas", 'https://www.cifra.es/index.php?route=product/category&path=9_158');

        //Bolígrafos
        consulta_referencias_cifra("Infantil", "Bolígrafos", 'https://www.cifra.es/index.php?route=product/category&path=9_157');

        //Cajas
        consulta_referencias_cifra("Infantil", "Cajas", 'https://www.cifra.es/index.php?route=product/category&path=9_159');

        //Chalecos
        consulta_referencias_cifra("Infantil", "Chalecos", 'https://www.cifra.es/index.php?route=product/category&path=9_161');

        //Estuches
        consulta_referencias_cifra("Infantil", "Estuches", 'https://www.cifra.es/index.php?route=product/category&path=9_162');

        //Huchas
        consulta_referencias_cifra("Infantil", "Huchas", 'https://www.cifra.es/index.php?route=product/category&path=9_163');

        //Juegos
        consulta_referencias_cifra("Infantil", "Juegos", 'https://www.cifra.es/index.php?route=product/category&path=9_170');

        //Lápices
        consulta_referencias_cifra("Infantil", "Lápices", 'https://www.cifra.es/index.php?route=product/category&path=9_164');

        //Peluches
        //consulta_referencias_cifra("Infantil", "Peluches", 'https://www.cifra.es/index.php?route=product/category&path=9_166');

        //Petos
        consulta_referencias_cifra("Infantil", "Petos", 'https://www.cifra.es/index.php?route=product/category&path=9_167');

        //Portalápices
        //consulta_referencias_cifra("Infantil", "Portalápices", 'https://www.cifra.es/index.php?route=product/category&path=9_168');

        //Reglas
        consulta_referencias_cifra("Infantil", "Reglas", 'https://www.cifra.es/index.php?route=product/category&path=9_392');

        //Set
        consulta_referencias_cifra("Infantil", "Set", 'https://www.cifra.es/index.php?route=product/category&path=9_169');
        
    
    //Llaveros
        
        //Aluminio
        consulta_referencias_cifra("Llaveros", "Aluminio", 'https://www.cifra.es/index.php?route=product/category&path=10_175');
        consulta_referencias_cifra("Llaveros", "Aluminio", 'https://www.cifra.es/index.php?route=product/category&path=10_175&page=2');
        consulta_referencias_cifra("Llaveros", "Aluminio", 'https://www.cifra.es/index.php?route=product/category&path=10_175&page=3');

        //Espuma
        consulta_referencias_cifra("Llaveros", "Espuma", 'https://www.cifra.es/index.php?route=product/category&path=10_174');

        //Grabados
        consulta_referencias_cifra("Llaveros", "Grabados", 'https://www.cifra.es/index.php?route=product/category&path=10_430');

        //Metálicos
        consulta_referencias_cifra("Llaveros", "Metálicos", 'https://www.cifra.es/index.php?route=product/category&path=10_173');
        consulta_referencias_cifra("Llaveros", "Metálicos", 'https://www.cifra.es/index.php?route=product/category&path=10_173&page=2');
        consulta_referencias_cifra("Llaveros", "Metálicos", 'https://www.cifra.es/index.php?route=product/category&path=10_173&page=3');
        consulta_referencias_cifra("Llaveros", "Metálicos", 'https://www.cifra.es/index.php?route=product/category&path=10_173&page=4');
        consulta_referencias_cifra("Llaveros", "Metálicos", 'https://www.cifra.es/index.php?route=product/category&path=10_173&page=5');

        //Plástico
        consulta_referencias_cifra("Llaveros", "Plástico", 'https://www.cifra.es/index.php?route=product/category&path=10_388');
        consulta_referencias_cifra("Llaveros", "Plástico", 'https://www.cifra.es/index.php?route=product/category&path=10_388&page=2');
        
    
    //Mujer
        
        //Abanicos
        consulta_referencias_cifra("Mujer", "Abanicos", 'https://www.cifra.es/index.php?route=product/category&path=11_176');
        consulta_referencias_cifra("Mujer", "Abanicos", 'https://www.cifra.es/index.php?route=product/category&path=11_176&page=2');

        //Brazaletes
        consulta_referencias_cifra("Mujer", "Brazaletes", 'https://www.cifra.es/index.php?route=product/category&path=11_180');

        //Cuelga Bolsos
        consulta_referencias_cifra("Mujer", "Cuelga Bolsos", 'https://www.cifra.es/index.php?route=product/category&path=11_182');

        //Espejos
        consulta_referencias_cifra("Mujer", "Espejos", 'https://www.cifra.es/index.php?route=product/category&path=11_183');

        //Limas
        consulta_referencias_cifra("Mujer", "Limas", 'https://www.cifra.es/index.php?route=product/category&path=11_186');

        //Neceseres
        consulta_referencias_cifra("Mujer", "Neceseres", 'https://www.cifra.es/index.php?route=product/category&path=11_187');

        //Perfumadores
        consulta_referencias_cifra("Mujer", "Perfumadores", 'https://www.cifra.es/index.php?route=product/category&path=11_189');

        //Tocados
        consulta_referencias_cifra("Mujer", "Tocados", 'https://www.cifra.es/index.php?route=product/category&path=11_424');
        
  
    //Navidad
        
        //Bolsas
        consulta_referencias_cifra("Navidad", "Bolsas", 'https://www.cifra.es/index.php?route=product/category&path=2_194');

        //Gorros
        consulta_referencias_cifra("Navidad", "Gorros", 'https://www.cifra.es/index.php?route=product/category&path=2_197');

        //Muñecos
        consulta_referencias_cifra("Navidad", "Muñecos", 'https://www.cifra.es/index.php?route=product/category&path=2_390');

        //Nacimientos
        consulta_referencias_cifra("Navidad", "Nacimientos", 'https://www.cifra.es/index.php?route=product/category&path=2_354');

        //Textil
        consulta_referencias_cifra("Navidad", "Textil", 'https://www.cifra.es/index.php?route=product/category&path=2_195');
        
    
    //Otros
        
        //Catálogos
        consulta_referencias_cifra("Otros", "Catálogos", 'https://www.cifra.es/index.php?route=product/category&path=442_443');
        
    
    //Outlet
        
        //Accesorios Vehículos
        consulta_referencias_cifra("Outlet", "Accesorios Vehículos", 'https://www.cifra.es/index.php?route=product/category&path=25_217');

        //Agendas y Marroquinería
        consulta_referencias_cifra("Outlet", "Agendas y Marroquinería", 'https://www.cifra.es/index.php?route=product/category&path=25_209');

        //Bolsas de Compra
        consulta_referencias_cifra("Outlet", "Bolsas de Compra", 'https://www.cifra.es/index.php?route=product/category&path=25_206');

        //Complementos y Detalles
        consulta_referencias_cifra("Outlet", "Complementos y Detalles", 'https://www.cifra.es/index.php?route=product/category&path=25_412');

        //Electrónica
        consulta_referencias_cifra("Outlet", "Electrónica", 'https://www.cifra.es/index.php?route=product/category&path=25_343');

        //Escritura
        consulta_referencias_cifra("Outlet", "Escritura", 'https://www.cifra.es/index.php?route=product/category&path=25_205');

        //Fiestas y Eventos
        consulta_referencias_cifra("Outlet", "Fiestas y Eventos", 'https://www.cifra.es/index.php?route=product/category&path=25_201');

        //Fumador
        //consulta_referencias_cifra("Outlet", "Fumador", 'https://www.cifra.es/index.php?route=product/category&path=25_444');

        //Gorras y Sombreros
        consulta_referencias_cifra("Outlet", "Gorras y Sombreros", 'https://www.cifra.es/index.php?route=product/category&path=25_341');

        //Herramientas y Laboral
        consulta_referencias_cifra("Outlet", "Herramientas y Laboral", 'https://www.cifra.es/index.php?route=product/category&path=25_410');

        //Hogar
        consulta_referencias_cifra("Outlet", "Hogar", 'https://www.cifra.es/index.php?route=product/category&path=25_411');

        //Infantil
        consulta_referencias_cifra("Outlet", "Infantil", 'https://www.cifra.es/index.php?route=product/category&path=25_374');

        //Llaveros
        consulta_referencias_cifra("Outlet", "Llaveros", 'https://www.cifra.es/index.php?route=product/category&path=25_214');

        //Mujer
        consulta_referencias_cifra("Outlet", "Mujer", 'https://www.cifra.es/index.php?route=product/category&path=25_218');

        //Salud y Aseo
        consulta_referencias_cifra("Outlet", "Salud y Aseo", 'https://www.cifra.es/index.php?route=product/category&path=25_212');

        //Textil Invierno
        consulta_referencias_cifra("Outlet", "Textil Invierno", 'https://www.cifra.es/index.php?route=product/category&path=25_224');

        //Verano
        consulta_referencias_cifra("Outlet", "Verano", 'https://www.cifra.es/index.php?route=product/category&path=25_387');

        //Viajes y Congresos
        consulta_referencias_cifra("Outlet", "Viajes y Congresos", 'https://www.cifra.es/index.php?route=product/category&path=25_344');

        //Vinos y Enología
        consulta_referencias_cifra("Outlet", "Vinos y Enología", 'https://www.cifra.es/index.php?route=product/category&path=25_413');
        
      
    //Salud y Aseo
        
        //Botiquines
        consulta_referencias_cifra("Salud y Aseo", "Botiquines", 'https://www.cifra.es/index.php?route=product/category&path=21_249');

        //Cepillos
        consulta_referencias_cifra("Salud y Aseo", "Cepillos", 'https://www.cifra.es/index.php?route=product/category&path=21_416');

        //Chanclas
        //consulta_referencias_cifra("Salud y Aseo", "Chanclas", 'https://www.cifra.es/index.php?route=product/category&path=21_251');

        //Deportes
        consulta_referencias_cifra("Salud y Aseo", "Deportes", 'https://www.cifra.es/index.php?route=product/category&path=21_414');
        consulta_referencias_cifra("Salud y Aseo", "Deportes", 'https://www.cifra.es/index.php?route=product/category&path=21_414&page=2');

        //Lápices
        consulta_referencias_cifra("Salud y Aseo", "Lápices", 'https://www.cifra.es/index.php?route=product/category&path=21_355');

        //Manta Emergencia
        consulta_referencias_cifra("Salud y Aseo", "Manta Emergencia", 'https://www.cifra.es/index.php?route=product/category&path=21_417');

        //Pastillero
        consulta_referencias_cifra("Salud y Aseo", "Pastillero", 'https://www.cifra.es/index.php?route=product/category&path=21_256');
        consulta_referencias_cifra("Salud y Aseo", "Pastillero", 'https://www.cifra.es/index.php?route=product/category&path=21_256&page=2');

        //Porta Documentos
        consulta_referencias_cifra("Salud y Aseo", "Porta Documentos", 'https://www.cifra.es/index.php?route=product/category&path=21_408');

        //Salud
        consulta_referencias_cifra("Salud y Aseo", "Salud", 'https://www.cifra.es/index.php?route=product/category&path=21_254');

        //Set
        consulta_referencias_cifra("Salud y Aseo", "Set", 'https://www.cifra.es/index.php?route=product/category&path=21_258');

        //Sobres
        consulta_referencias_cifra("Salud y Aseo", "Sobres", 'https://www.cifra.es/index.php?route=product/category&path=21_259');

        //Termómetro
        consulta_referencias_cifra("Salud y Aseo", "Termómetro", 'https://www.cifra.es/index.php?route=product/category&path=21_260');

        //Toallas
        consulta_referencias_cifra("Salud y Aseo", "Toallas", 'https://www.cifra.es/index.php?route=product/category&path=21_261');

        //Zapatillas
        consulta_referencias_cifra("Salud y Aseo", "Zapatillas", 'https://www.cifra.es/index.php?route=product/category&path=21_263');
        
    
    //Textil Invierno
        
        //Bragas
        consulta_referencias_cifra("Textil Invierno", "Bragas", 'https://www.cifra.es/index.php?route=product/category&path=19_264');

        //Bufandas
        consulta_referencias_cifra("Textil Invierno", "Bufandas", 'https://www.cifra.es/index.php?route=product/category&path=19_265');

        //Gorros
        consulta_referencias_cifra("Textil Invierno", "Gorros", 'https://www.cifra.es/index.php?route=product/category&path=19_267');

        //Mantas
        consulta_referencias_cifra("Textil Invierno", "Mantas", 'https://www.cifra.es/index.php?route=product/category&path=19_268');
        
    
    //Trofeos y Conmemoraciones
        
        //Apoya Platos
        consulta_referencias_cifra("Trofeos y Conmemoraciones", "Apoya Platos", 'https://www.cifra.es/index.php?route=product/category&path=5_281');

        //Bandejas
        consulta_referencias_cifra("Trofeos y Conmemoraciones", "Bandejas", 'https://www.cifra.es/index.php?route=product/category&path=5_282');

        //Placas
        consulta_referencias_cifra("Trofeos y Conmemoraciones", "Placas", 'https://www.cifra.es/index.php?route=product/category&path=5_283');

        //Relojes
        consulta_referencias_cifra("Trofeos y Conmemoraciones", "Relojes", 'https://www.cifra.es/index.php?route=product/category&path=5_284');

        //Trofeos
        consulta_referencias_cifra("Trofeos y Conmemoraciones", "Trofeos", 'https://www.cifra.es/index.php?route=product/category&path=5_285');
        consulta_referencias_cifra("Trofeos y Conmemoraciones", "Trofeos", 'https://www.cifra.es/index.php?route=product/category&path=5_285&page=2');
        
   
    //Verano
        
        //Balones
        consulta_referencias_cifra("Verano", "Balones", 'https://www.cifra.es/index.php?route=product/category&path=24_270');

        //Bolsas
        consulta_referencias_cifra("Verano", "Bolsas", 'https://www.cifra.es/index.php?route=product/category&path=24_271');

        //Camisetas Hombre
        consulta_referencias_cifra("Verano", "Camisetas Hombre", 'https://www.cifra.es/index.php?route=product/category&path=24_273');
        consulta_referencias_cifra("Verano", "Camisetas Hombre", 'https://www.cifra.es/index.php?route=product/category&path=24_273&page=2');
        consulta_referencias_cifra("Verano", "Camisetas Hombre", 'https://www.cifra.es/index.php?route=product/category&path=24_273&page=3');
        consulta_referencias_cifra("Verano", "Camisetas Hombre", 'https://www.cifra.es/index.php?route=product/category&path=24_273&page=4');
        consulta_referencias_cifra("Verano", "Camisetas Hombre", 'https://www.cifra.es/index.php?route=product/category&path=24_273&page=5');
        consulta_referencias_cifra("Verano", "Camisetas Hombre", 'https://www.cifra.es/index.php?route=product/category&path=24_273&page=6');
        consulta_referencias_cifra("Verano", "Camisetas Hombre", 'https://www.cifra.es/index.php?route=product/category&path=24_273&page=7');
        consulta_referencias_cifra("Verano", "Camisetas Hombre", 'https://www.cifra.es/index.php?route=product/category&path=24_273&page=8');
        consulta_referencias_cifra("Verano", "Camisetas Hombre", 'https://www.cifra.es/index.php?route=product/category&path=24_273&page=9');

        //Camisetas Mujer
        consulta_referencias_cifra("Verano", "Camisetas Mujer", 'https://www.cifra.es/index.php?route=product/category&path=24_379');
        consulta_referencias_cifra("Verano", "Camisetas Mujer", 'https://www.cifra.es/index.php?route=product/category&path=24_379&page=2');

        //Camisetas Niño
        consulta_referencias_cifra("Verano", "Camisetas Niño", 'https://www.cifra.es/index.php?route=product/category&path=24_378');
        consulta_referencias_cifra("Verano", "Camisetas Niño", 'https://www.cifra.es/index.php?route=product/category&path=24_378&page=2');
        consulta_referencias_cifra("Verano", "Camisetas Niño", 'https://www.cifra.es/index.php?route=product/category&path=24_378&page=3');
        consulta_referencias_cifra("Verano", "Camisetas Niño", 'https://www.cifra.es/index.php?route=product/category&path=24_378&page=4');
        consulta_referencias_cifra("Verano", "Camisetas Niño", 'https://www.cifra.es/index.php?route=product/category&path=24_378&page=5');

        //Chanclas
        consulta_referencias_cifra("Verano", "Chanclas", 'https://www.cifra.es/index.php?route=product/category&path=24_441');

        //Juegos
        consulta_referencias_cifra("Verano", "Juegos", 'https://www.cifra.es/index.php?route=product/category&path=24_383');

        //Neveras
        consulta_referencias_cifra("Verano", "Neveras", 'https://www.cifra.es/index.php?route=product/category&path=24_277');
        consulta_referencias_cifra("Verano", "Neveras", 'https://www.cifra.es/index.php?route=product/category&path=24_277&page=2');

        //Pantalones Adulto
        consulta_referencias_cifra("Verano", "Pantalones Adulto", 'https://www.cifra.es/index.php?route=product/category&path=24_439');

        //Pantalones Niño
        consulta_referencias_cifra("Verano", "Pantalones Niño", 'https://www.cifra.es/index.php?route=product/category&path=24_440');

        //Petates
        consulta_referencias_cifra("Verano", "Petates", 'https://www.cifra.es/index.php?route=product/category&path=24_419');

        //Petos
        consulta_referencias_cifra("Verano", "Petos", 'https://www.cifra.es/index.php?route=product/category&path=24_278');

        //Polos Hombre
        consulta_referencias_cifra("Verano", "Polos Hombre", 'https://www.cifra.es/index.php?route=product/category&path=24_279');
        consulta_referencias_cifra("Verano", "Polos Hombre", 'https://www.cifra.es/index.php?route=product/category&path=24_279&page=2');
        consulta_referencias_cifra("Verano", "Polos Hombre", 'https://www.cifra.es/index.php?route=product/category&path=24_279&page=3');
        consulta_referencias_cifra("Verano", "Polos Hombre", 'https://www.cifra.es/index.php?route=product/category&path=24_279&page=4');
        consulta_referencias_cifra("Verano", "Polos Hombre", 'https://www.cifra.es/index.php?route=product/category&path=24_279&page=5');
        consulta_referencias_cifra("Verano", "Polos Hombre", 'https://www.cifra.es/index.php?route=product/category&path=24_279&page=6');

        //Polos Mujer
        consulta_referencias_cifra("Verano", "Polos Mujer", 'https://www.cifra.es/index.php?route=product/category&path=24_375');
        consulta_referencias_cifra("Verano", "Polos Mujer", 'https://www.cifra.es/index.php?route=product/category&path=24_375&page=2');
        consulta_referencias_cifra("Verano", "Polos Mujer", 'https://www.cifra.es/index.php?route=product/category&path=24_375&page=3');

        //Porta Móviles
        consulta_referencias_cifra("Verano", "Porta Móviles", 'https://www.cifra.es/index.php?route=product/category&path=24_280');

        //Sombrillas
        consulta_referencias_cifra("Verano", "Sombrillas", 'https://www.cifra.es/index.php?route=product/category&path=24_382');
        
     
    //Viajes y Congresos
        
        //Blocs
        consulta_referencias_cifra("Viajes y Congresos", "Blocs", 'https://www.cifra.es/index.php?route=product/category&path=15_288');

        //Bolsas
        consulta_referencias_cifra("Viajes y Congresos", "Bolsas", 'https://www.cifra.es/index.php?route=product/category&path=15_289');
        consulta_referencias_cifra("Viajes y Congresos", "Bolsas", 'https://www.cifra.es/index.php?route=product/category&path=15_289&page=2');
        consulta_referencias_cifra("Viajes y Congresos", "Bolsas", 'https://www.cifra.es/index.php?route=product/category&path=15_289&page=3');
        consulta_referencias_cifra("Viajes y Congresos", "Bolsas", 'https://www.cifra.es/index.php?route=product/category&path=15_289&page=4');
        consulta_referencias_cifra("Viajes y Congresos", "Bolsas", 'https://www.cifra.es/index.php?route=product/category&path=15_289&page=5');

        //Carpetas
        consulta_referencias_cifra("Viajes y Congresos", "Carpetas", 'https://www.cifra.es/index.php?route=product/category&path=15_290');
        consulta_referencias_cifra("Viajes y Congresos", "Carpetas", 'https://www.cifra.es/index.php?route=product/category&path=15_290&page=2');

        //Carteras
        //consulta_referencias_cifra("Viajes y Congresos", "Carteras", 'https://www.cifra.es/index.php?route=product/category&path=15_291');

        //Cuadernos
        consulta_referencias_cifra("Viajes y Congresos", "Cuadernos", 'https://www.cifra.es/index.php?route=product/category&path=15_293');

        //Fundas
        //consulta_referencias_cifra("Viajes y Congresos", "Fundas", 'https://www.cifra.es/index.php?route=product/category&path=15_294');

        //Identificadores
        consulta_referencias_cifra("Viajes y Congresos", "Identificadores", 'https://www.cifra.es/index.php?route=product/category&path=15_295');

        //Lanyard
        consulta_referencias_cifra("Viajes y Congresos", "Lanyard", 'https://www.cifra.es/index.php?route=product/category&path=15_296');
        consulta_referencias_cifra("Viajes y Congresos", "Lanyard", 'https://www.cifra.es/index.php?route=product/category&path=15_296&page=2');

        //Maletines
        consulta_referencias_cifra("Viajes y Congresos", "Maletines", 'https://www.cifra.es/index.php?route=product/category&path=15_299');
        consulta_referencias_cifra("Viajes y Congresos", "Maletines", 'https://www.cifra.es/index.php?route=product/category&path=15_299&page=2');

        //Mochilas
        consulta_referencias_cifra("Viajes y Congresos", "Mochilas", 'https://www.cifra.es/index.php?route=product/category&path=15_301');
        consulta_referencias_cifra("Viajes y Congresos", "Mochilas", 'https://www.cifra.es/index.php?route=product/category&path=15_301&page=2');

        //Porta Documentos
        consulta_referencias_cifra("Viajes y Congresos", "Porta Documentos", 'https://www.cifra.es/index.php?route=product/category&path=15_305');
        consulta_referencias_cifra("Viajes y Congresos", "Porta Documentos", 'https://www.cifra.es/index.php?route=product/category&path=15_305&page=2');

        //Porta Identificadores
        consulta_referencias_cifra("Viajes y Congresos", "Porta Identificadores", 'https://www.cifra.es/index.php?route=product/category&path=15_306');

        //Trolleys
        consulta_referencias_cifra("Viajes y Congresos", "Trolleys", 'https://www.cifra.es/index.php?route=product/category&path=15_310');
        
    
    //Vinos y Enología
        
        //Abrebotellas
        consulta_referencias_cifra("Vinos y Enología", "Abrebotellas", 'https://www.cifra.es/index.php?route=product/category&path=4_312');

        //Bandejas
        consulta_referencias_cifra("Vinos y Enología", "Bandejas", 'https://www.cifra.es/index.php?route=product/category&path=4_313');

        //Bolsas
        consulta_referencias_cifra("Vinos y Enología", "Bolsas", 'https://www.cifra.es/index.php?route=product/category&path=4_314');

        //Cajas
        consulta_referencias_cifra("Vinos y Enología", "Cajas", 'https://www.cifra.es/index.php?route=product/category&path=4_315');

        //Cavas
        consulta_referencias_cifra("Vinos y Enología", "Cavas", 'https://www.cifra.es/index.php?route=product/category&path=4_316');

        //Cubiteras
        consulta_referencias_cifra("Vinos y Enología", "Cubiteras", 'https://www.cifra.es/index.php?route=product/category&path=4_384');

        //Decantador
        consulta_referencias_cifra("Vinos y Enología", "Decantador", 'https://www.cifra.es/index.php?route=product/category&path=4_317');

        //Estuches
        consulta_referencias_cifra("Vinos y Enología", "Estuches", 'https://www.cifra.es/index.php?route=product/category&path=4_320');

        //Set
        consulta_referencias_cifra("Vinos y Enología", "Set", 'https://www.cifra.es/index.php?route=product/category&path=4_323');

        //Tapones
        consulta_referencias_cifra("Vinos y Enología", "Tapones", 'https://www.cifra.es/index.php?route=product/category&path=4_321');

        //Vinos
        consulta_referencias_cifra("Vinos y Enología", "Vinos", 'https://www.cifra.es/index.php?route=product/category&path=4_324');
    
  
*/    

//FIN de prueba - BORRAR

?>