<?php

//error_reporting(0);

include_once('curl_functions.inc.php');

//******************************************************//

//Incluyo la Librería PHP Simple HTML DOM Parser
include_once('simple_html_dom.php');

//  OJO, LA CARGA DE REFERENCIAS PARECE QUE ES ASÍNCRONA SEGÚN SE VA HACIENDO SCROLL EN LA PÁGINA DE RESULTADOS... 

//EN PRINCIPIO POR LA CARGA ASINCRONA DE LOS RESULTADOS DE LA BÚSQUEDA POR CATEGORÍA (se capturan sólo 15 resultados con este método)
//FUNCIÓN consulta_referencias_ps QUE CONSULTA Y ALMACENA LOS DATOS DE TODAS LAS REFERENCIAS PERTENECIENTES A UNA CATEGORIA
//  OJO, EN PS NO HAY SUBCATEGORÍAS
//  Se pasan como parámetros:
//      $categoria          -> El nombre con el que se desea almacenar esa categoría en la BD
//      $url                -> El valor (en el buscador de ps) que figura en la URL para mostrar esa subcategoría
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
//  ATENCIÓN!!! ESTA FORMA DE OBTENER LAS REFERENCIAS DE PS IMPLICA UNA VISITA POR CADA REFERENCIA PARA OBTENER REF, NOMBRE Y URL IMAGEN

function consulta_referencias_ps($categoria, $url){
    
    include('conexion7.php');

    //En primer lugar "simulamos" el login en la página de login (https://www.stricker-europe.com/es/)
    //OJO tras introducir el email y la contraseña en el formulario de acceso se pasan por POST y se comprueban en
    //(https://www.stricker-europe.com/es/zona-reservada/login/), por lo que es en esa URL en la que simulamos el login
    login("https://www.stricker-europe.com/es/zona-reservada/login/", "usr=javierbp&pwd=JavierJimena8066&subm=true&urlReturn=/es/");

    //Obtengo el código HTML del resultado de la consulta de los artículos pertenecientes a esa subcategoría
    $url_busqueda = $url;
    
    //$subcategoria -> El nombre con el que se desea almacenar esa subcategoría en la BD (SERÁ EL MISMO QUE EL DE LA CATEGORÍA)
    //  puesto que en PS no existen Subcategorías
    //  (pero almaceno el mismo valor que el de categoría para ser consistente con lo que he hecho para el resto de competidores)
    $subcategoria = $categoria;
    
    //Una vez obtenida la URL que muestra el resultado de la consulta de la subcategoría, capturo su HTML        
    $html_scraped = grab_page($url_busqueda);

//echo $html_scraped;
    
    //Creo un HTML DOM object (empleando un método de la Librería PHP Simple HTML DOM Parser que he importado)
    $html = str_get_html($html_scraped);
    
    //Voy almacenando los distintos elementos que contienen los valores de cada artículo mostrado, que luego se almacenarán en la BD
    $articulos = $html->find('a.produto');

    foreach($articulos as $articulo) {
        
        $referencia_articulo = $articulo->find('div.ref', 0)->innertext;
        $nombre_articulo = $articulo->find('div.titulo', 0)->innertext;
        $url_imagen = "https://www.stricker-europe.com".$articulo->find('img', 0)->src;

        if($referencia_articulo != "") {
            $consulta_inserta_ref = "INSERT INTO refs_ps
                                       (refs_ps_referencia,
                                        refs_ps_nombre,
                                        refs_ps_categoria,
                                        refs_ps_subcategoria,
                                        refs_ps_url_imagen)
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


//PARA CATEGORÍAS CON MÁS DE 15 RESULTADOS (se emplea este otro método)
//FUNCIÓN consulta_referencias2_ps QUE CONSULTA Y ALMACENA LOS DATOS DE TODAS LAS REFERENCIAS PERTENECIENTES A UNA CATEGORIA
//  OJO, EN PS NO HAY SUBCATEGORÍAS
//  Se pasan como parámetros:
//      $categoria2         -> El nombre con el que se desea almacenar esa categoría en la BD
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
//
//  ATENCIÓN!!! ESTA FORMA DE OBTENER LAS REFERENCIAS DE PS IMPLICA UNA VISITA POR CADA REFERENCIA PARA OBTENER REF, NOMBRE Y URL IMAGEN

function consulta_referencias2_ps($categoria2, $html_man){
    
    include('conexion7.php');
    
    //$subcategoria2 -> El nombre con el que se desea almacenar esa subcategoría en la BD (SERÁ EL MISMO QUE EL DE LA CATEGORÍA)
    //  puesto que en PS no existen Subcategorías
    //  (pero almaceno el mismo valor que el de categoría para ser consistente con lo que he hecho para el resto de competidores)
    $subcategoria2 = $categoria2;
    
    //Almaceno el HTML capturado "a mano" en una variable 
    $html_scraped2 = $html_man;
    
    //Creo un HTML DOM object (empleando un método de la Librería PHP Simple HTML DOM Parser que he importado)
    $html2 = str_get_html($html_scraped2);
    
    //Voy almacenando los distintos elementos que contienen los valores de cada artículo mostrado, que luego se almacenarán en la BD
    $articulos2 = $html2->find('a.produto');

    foreach($articulos2 as $articulo2) {
        
        $referencia_articulo2 = $articulo2->find('div.ref', 0)->innertext;
        $nombre_articulo2 = $articulo2->find('div.titulo', 0)->innertext;
        $url_imagen2 = "https://www.stricker-europe.com".$articulo2->find('img', 0)->src;

        if($referencia_articulo2 != "") {
            $consulta_inserta_ref2 = "INSERT INTO refs_ps
                                       (refs_ps_referencia,
                                        refs_ps_nombre,
                                        refs_ps_categoria,
                                        refs_ps_subcategoria,
                                        refs_ps_url_imagen)
                                    VALUES
                                       ('$referencia_articulo2',
                                        '$nombre_articulo2',
                                        '$categoria2',
                                        '$subcategoria2',
                                        '$url_imagen2');";
            $bd7->Execute($consulta_inserta_ref2);
        }
        
    }
    
    echo "Completado<br>";
    
}

//prueba - BORRAR

    /*
    //Sets para 1 componente
    consulta_referencias_ps("Sets para 1 componente", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=7&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
    
    //Sets para 2 componentes
    consulta_referencias_ps("Sets para 2 componentes", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=8&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
    
    //Marcadores Fluorescentes
    consulta_referencias_ps("Marcadores Fluorescentes", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=11&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
    
    //Bolígrafos de Plástico y Metal
    consulta_referencias2_ps("Bolígrafos de Plástico y Metal", '
						<a class="produto" href="/es/catalogo/boligrafos-de-plastico-y-metal/91019/" title="SWING Bolígrafo">
					<div class="fav" data-fav="0" data-prod="18"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/91019_set_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">91019</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,097€							</div>
							
						<div class="titulo">SWING Bolígrafo</div>
						<div class="stock">Stock: 546.415</div>

													<ul class="colors">
																	<li style="background-color: #000000"></li>
																		<li style="background-color: #0043a7"></li>
																		<li style="background-color: #ff0000"></li>
																		<li style="background-color: #41ad00"></li>
																		<li style="background-color: #f7931e"></li>
																		<li style="background-color: #42b1ff"></li>
																		<li style="background-color: #be13c8"></li>
																		<li style="background-color: #bee017"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/boligrafos-de-plastico-y-metal/91118/" title="NEXO Bolígrafo">
					<div class="fav" data-fav="0" data-prod="8139"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/91118_05_979879613593bd963d3fbe.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">91118</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,128€							</div>
							
						<div class="titulo">NEXO Bolígrafo</div>
						<div class="stock">Stock: 10.693</div>

													<ul class="colors">
																	<li style="background-color: #ff0000"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/boligrafos-de-plastico-y-metal/91200/" title="TRINITY Bolígrafo">
					<div class="fav" data-fav="0" data-prod="8140"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/91200_22_361273556593bd9e4452e3.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">91200</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,149€							</div>
							
						<div class="titulo">TRINITY Bolígrafo</div>
						<div class="stock">Stock: 247</div>

													<ul class="colors">
																	<li style="background-color: #bee017"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/boligrafos-de-plastico-y-metal/91296/" title="SIMBEL Bolígrafo">
					<div class="fav" data-fav="0" data-prod="31"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/91296_set_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">91296</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,137€							</div>
							
						<div class="titulo">SIMBEL Bolígrafo</div>
						<div class="stock">Stock: 77.387</div>

													<ul class="colors">
																	<li style="background-color: #000000"></li>
																		<li style="background-color: #0043a7"></li>
																		<li style="background-color: #ff0000"></li>
																		<li style="background-color: #41ad00"></li>
																		<li style="background-color: #f7931e"></li>
																		<li style="background-color: #f387a8"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/boligrafos-de-plastico-y-metal/91342/" title="CENTUS Bolígrafo">
					<div class="fav" data-fav="0" data-prod="37"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/91342_set_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">91342</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,202€							</div>
							
						<div class="titulo">CENTUS Bolígrafo</div>
						<div class="stock">Stock: 42.873</div>

													<ul class="colors">
																	<li style="background-color: #000000"></li>
																		<li style="background-color: #ffffff"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/boligrafos-de-plastico-y-metal/91343/" title="GLOSS Bolígrafo">
					<div class="fav" data-fav="0" data-prod="38"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/91343_03_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">91343</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,160€							</div>
							
						<div class="titulo">GLOSS Bolígrafo</div>
						<div class="stock">Stock: 78.058</div>

													<ul class="colors">
																	<li style="background-color: #000000"></li>
																		<li style="background-color: #ff0000"></li>
																		<li style="background-color: #41ad00"></li>
																		<li style="background-color: #f7931e"></li>
																		<li style="background-color: #0046ad"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/boligrafos-de-plastico-y-metal/91346/" title="LEON Bolígrafo">
					<div class="fav" data-fav="0" data-prod="39"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/91346_set_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">91346</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,137€							</div>
							
						<div class="titulo">LEON Bolígrafo</div>
						<div class="stock">Stock: 239.676</div>

													<ul class="colors">
																	<li style="background-color: #000000"></li>
																		<li style="background-color: #0043a7"></li>
																		<li style="background-color: #ff0000"></li>
																		<li style="background-color: #f7931e"></li>
																		<li style="background-color: #42b1ff"></li>
																		<li style="background-color: #bee017"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/boligrafos-de-plastico-y-metal/91375/" title="HAVANA Bolígrafo">
					<div class="fav" data-fav="0" data-prod="42"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/91375_set_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">91375</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,140€							</div>
							
						<div class="titulo">HAVANA Bolígrafo</div>
						<div class="stock">Stock: 209.956</div>

													<ul class="colors">
																	<li style="background-color: #000000"></li>
																		<li style="background-color: #0043a7"></li>
																		<li style="background-color: #ff0000"></li>
																		<li style="background-color: #ffffff"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/boligrafos-de-plastico-y-metal/91395/" title="WISPY Bolígrafo">
					<div class="fav" data-fav="0" data-prod="8147"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/91395_09_1036629208593be4a328ce2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">91395</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,152€							</div>
							
						<div class="titulo">WISPY Bolígrafo</div>
						<div class="stock">Stock: 445</div>

													<ul class="colors">
																	<li style="background-color: #41ad00"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/boligrafos-de-plastico-y-metal/91397/" title="HOOP Bolígrafo">
					<div class="fav" data-fav="0" data-prod="48"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/91397_set_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">91397</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,176€							</div>
							
						<div class="titulo">HOOP Bolígrafo</div>
						<div class="stock">Stock: 147.003</div>

													<ul class="colors">
																	<li style="background-color: #0043a7"></li>
																		<li style="background-color: #ff0000"></li>
																		<li style="background-color: #f7931e"></li>
																		<li style="background-color: #42b1ff"></li>
																		<li style="background-color: #bee017"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/boligrafos-de-plastico-y-metal/91451/" title="FOX Bolígrafo">
					<div class="fav" data-fav="0" data-prod="66"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/91451_set_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">91451</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,197€							</div>
							
						<div class="titulo">FOX Bolígrafo</div>
						<div class="stock">Stock: 63.180</div>

													<ul class="colors">
																	<li style="background-color: #0043a7"></li>
																		<li style="background-color: #ff0000"></li>
																		<li style="background-color: #41ad00"></li>
																		<li style="background-color: #f7931e"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/boligrafos-de-plastico-y-metal/91457/" title="INDY Bolígrafo">
					<div class="fav" data-fav="0" data-prod="69"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/91457_set_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">91457</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,192€							</div>
							
						<div class="titulo">INDY Bolígrafo</div>
						<div class="stock">Stock: 167.245</div>

													<ul class="colors">
																	<li style="background-color: #000000"></li>
																		<li style="background-color: #0043a7"></li>
																		<li style="background-color: #ff0000"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/boligrafos-de-plastico-y-metal/91476/" title="SHANGHAI Bolígrafo">
					<div class="fav" data-fav="0" data-prod="73"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/91476_set_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">91476</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,347€							</div>
							
						<div class="titulo">SHANGHAI Bolígrafo</div>
						<div class="stock">Stock: 75.901</div>

													<ul class="colors">
																	<li style="background-color: #0043a7"></li>
																		<li style="background-color: #ff0000"></li>
																		<li style="background-color: #544945"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/boligrafos-de-plastico-y-metal/91477/" title="BONO Bolígrafo">
					<div class="fav" data-fav="0" data-prod="74"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/91477_set_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">91477</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,371€							</div>
							
						<div class="titulo">BONO Bolígrafo</div>
						<div class="stock">Stock: 54.270</div>

													<ul class="colors">
																	<li style="background-color: #000000"></li>
																		<li style="background-color: #0043a7"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/boligrafos-de-plastico-y-metal/91479/" title="REED Bolígrafo">
					<div class="fav" data-fav="0" data-prod="76"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/91479_set_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">91479</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,195€							</div>
							
						<div class="titulo">REED Bolígrafo</div>
						<div class="stock">Stock: 60.024</div>

													<ul class="colors">
																	<li style="background-color: #000000"></li>
																</ul>
												</div>
				</a>
								<a class="produto new" href="/es/catalogo/boligrafos-de-plastico-y-metal/91497/" title="GUM Bolígrafo">
					<div class="fav" data-fav="0" data-prod="90"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/91497_set_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">91497</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,495€							</div>
							
						<div class="titulo">GUM Bolígrafo</div>
						<div class="stock">Stock: 30.435</div>

													<ul class="colors">
																	<li style="background-color: #ff0000"></li>
																		<li style="background-color: #ffffff"></li>
																		<li style="background-color: #fff200"></li>
																		<li style="background-color: #f7931e"></li>
																		<li style="background-color: #f387a8"></li>
																		<li style="background-color: #42b1ff"></li>
																		<li style="background-color: #bee017"></li>
																</ul>
												</div>
				</a>
								<a class="produto new" href="/es/catalogo/boligrafos-de-plastico-y-metal/91600/" title="LENA Bolígrafo">
					<div class="fav" data-fav="0" data-prod="93"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/91600_set_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">91600</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,167€							</div>
							
						<div class="titulo">LENA Bolígrafo</div>
						<div class="stock">Stock: 197.902</div>

													<ul class="colors">
																	<li style="background-color: #000000"></li>
																		<li style="background-color: #ff0000"></li>
																		<li style="background-color: #ffffff"></li>
																		<li style="background-color: #0046ad"></li>
																</ul>
												</div>
				</a>
								<a class="produto new" href="/es/catalogo/boligrafos-de-plastico-y-metal/91620/" title="POINTY Bolígrafo">
					<div class="fav" data-fav="0" data-prod="103"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/91620_set_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">91620</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,191€							</div>
							
						<div class="titulo">POINTY Bolígrafo</div>
						<div class="stock">Stock: 53.054</div>

													<ul class="colors">
																	<li style="background-color: #000000"></li>
																</ul>
												</div>
				</a>
								<a class="produto new" href="/es/catalogo/boligrafos-de-plastico-y-metal/91634/" title="SANS Bolígrafo">
					<div class="fav" data-fav="0" data-prod="117"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/91634_set_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">91634</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,147€							</div>
							
						<div class="titulo">SANS Bolígrafo</div>
						<div class="stock">Stock: 458.552</div>

													<ul class="colors">
																	<li style="background-color: #0043a7"></li>
																		<li style="background-color: #ff0000"></li>
																		<li style="background-color: #ffffff"></li>
																		<li style="background-color: #fff200"></li>
																		<li style="background-color: #41ad00"></li>
																		<li style="background-color: #f7931e"></li>
																</ul>
												</div>
				</a>
								<a class="produto new" href="/es/catalogo/boligrafos-de-plastico-y-metal/91636/" title="CLIFF Bolígrafo">
					<div class="fav" data-fav="0" data-prod="119"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/91636_set_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">91636</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,212€							</div>
							
						<div class="titulo">CLIFF Bolígrafo</div>
						<div class="stock">Stock: 51.569</div>

													<ul class="colors">
																	<li style="background-color: #000000"></li>
																		<li style="background-color: #0043a7"></li>
																		<li style="background-color: #ff0000"></li>
																</ul>
												</div>
				</a>
								<a class="produto new" href="/es/catalogo/boligrafos-de-plastico-y-metal/91637/" title="VISO Bolígrafo">
					<div class="fav" data-fav="0" data-prod="120"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/91637_set_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">91637</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,247€							</div>
							
						<div class="titulo">VISO Bolígrafo</div>
						<div class="stock">Stock: 39.399</div>

													<ul class="colors">
																	<li style="background-color: #000000"></li>
																		<li style="background-color: #ff0000"></li>
																		<li style="background-color: #41ad00"></li>
																		<li style="background-color: #f7931e"></li>
																		<li style="background-color: #0046ad"></li>
																</ul>
												</div>
				</a>
								<a class="produto new" href="/es/catalogo/boligrafos-de-plastico-y-metal/91671/" title="ELBE Bolígrafo">
					<div class="fav" data-fav="0" data-prod="130"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/91671_set_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">91671</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,137€							</div>
							
						<div class="titulo">ELBE Bolígrafo</div>
						<div class="stock">Stock: 194.178</div>

													<ul class="colors">
																	<li style="background-color: #000000"></li>
																		<li style="background-color: #0043a7"></li>
																		<li style="background-color: #ff0000"></li>
																		<li style="background-color: #41ad00"></li>
																		<li style="background-color: #f7931e"></li>
																</ul>
												</div>
				</a>
								<a class="produto new" href="/es/catalogo/boligrafos-de-plastico-y-metal/91672/" title="THAYA Bolígrafo">
					<div class="fav" data-fav="0" data-prod="8153"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/91672_03_1825468963593be66b68f8a.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">91672</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,125€							</div>
							
						<div class="titulo">THAYA Bolígrafo</div>
						<div class="stock">Stock: 7.602</div>

													<ul class="colors">
																	<li style="background-color: #000000"></li>
																</ul>
												</div>
				</a>
								<a class="produto new" href="/es/catalogo/boligrafos-de-plastico-y-metal/91698/" title="VARNA Bolígrafo">
					<div class="fav" data-fav="0" data-prod="133"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/91698_set_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">91698</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,147€							</div>
							
						<div class="titulo">VARNA Bolígrafo</div>
						<div class="stock">Stock: 223.781</div>

													<ul class="colors">
																	<li style="background-color: #000000"></li>
																		<li style="background-color: #0043a7"></li>
																		<li style="background-color: #ff0000"></li>
																		<li style="background-color: #41ad00"></li>
																		<li style="background-color: #f7931e"></li>
																</ul>
												</div>
				</a>
								<a class="produto new" href="/es/catalogo/boligrafos-de-plastico-y-metal/91699/" title="ESLA Bolígrafo">
					<div class="fav" data-fav="0" data-prod="134"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/91699_set_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">91699</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,212€							</div>
							
						<div class="titulo">ESLA Bolígrafo</div>
						<div class="stock">Stock: 384.549</div>

													<ul class="colors">
																	<li style="background-color: #000000"></li>
																		<li style="background-color: #0043a7"></li>
																</ul>
												</div>
				</a>
					');
    
    //Bolígrafos de Metal
    consulta_referencias2_ps("Bolígrafos de Metal", '
						<a class="produto" href="/es/catalogo/boligrafos-de-metal/91008/" title="PLATA Bolígrafo">
					<div class="fav" data-fav="0" data-prod="16"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/91008_set_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">91008</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,765€							</div>
							
						<div class="titulo">PLATA Bolígrafo</div>
						<div class="stock">Stock: 16.131</div>

													<ul class="colors">
																	<li style="background-color: #a62828"></li>
																		<li style="background-color: #000000"></li>
																		<li style="background-color: #0043a7"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/boligrafos-de-metal/91017/" title="RIOJA Bolígrafo">
					<div class="fav" data-fav="0" data-prod="17"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/91017_42-A_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">91017</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,765€							</div>
							
						<div class="titulo">RIOJA Bolígrafo</div>
						<div class="stock">Stock: 11.051</div>

													<ul class="colors">
																	<li style="background-color: #544945"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/boligrafos-de-metal/91310/" title="OMEGA Bolígrafo">
					<div class="fav" data-fav="0" data-prod="32"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/91310_set_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">91310</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,410€							</div>
							
						<div class="titulo">OMEGA Bolígrafo</div>
						<div class="stock">Stock: 43.312</div>

													<ul class="colors">
																	<li style="background-color: #000000"></li>
																		<li style="background-color: #0043a7"></li>
																		<li style="background-color: #ff0000"></li>
																		<li style="background-color: #fffccb"></li>
																		<li style="background-color: #bee017"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/boligrafos-de-metal/91311/" title="BETA Bolígrafo">
					<div class="fav" data-fav="0" data-prod="33"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/91311_set_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">91311</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,287€							</div>
							
						<div class="titulo">BETA Bolígrafo</div>
						<div class="stock">Stock: 455.629</div>

													<ul class="colors">
																	<li style="background-color: #000000"></li>
																		<li style="background-color: #ff0000"></li>
																		<li style="background-color: #42b1ff"></li>
																		<li style="background-color: #0046ad"></li>
																		<li style="background-color: #fffccb"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/boligrafos-de-metal/91322/" title="MALMO Bolígrafo">
					<div class="fav" data-fav="0" data-prod="8142"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/91322_02_805588169593bdab068793.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">91322</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,335€							</div>
							
						<div class="titulo">MALMO Bolígrafo</div>
						<div class="stock">Stock: 638</div>

													<ul class="colors">
																	<li style="background-color: #a62828"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/boligrafos-de-metal/91334/" title="BREL Bolígrafo">
					<div class="fav" data-fav="0" data-prod="34"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/91334_set_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">91334</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,546€							</div>
							
						<div class="titulo">BREL Bolígrafo</div>
						<div class="stock">Stock: 29.737</div>

													<ul class="colors">
																	<li style="background-color: #a62828"></li>
																		<li style="background-color: #0043a7"></li>
																		<li style="background-color: #41ad00"></li>
																		<li style="background-color: #544945"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/boligrafos-de-metal/91392/" title="SERRAT Bolígrafo">
					<div class="fav" data-fav="0" data-prod="45"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/91392_03-A_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">91392</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,510€							</div>
							
						<div class="titulo">SERRAT Bolígrafo</div>
						<div class="stock">Stock: 21.240</div>

													<ul class="colors">
																	<li style="background-color: #000000"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/boligrafos-de-metal/91433/" title="MINERVA Bolígrafo">
					<div class="fav" data-fav="0" data-prod="54"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/91433_set_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">91433</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,441€							</div>
							
						<div class="titulo">MINERVA Bolígrafo</div>
						<div class="stock">Stock: 20.964</div>

													<ul class="colors">
																	<li style="background-color: #000000"></li>
																		<li style="background-color: #0046ad"></li>
																		<li style="background-color: #544945"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/boligrafos-de-metal/91434/" title="MARE Bolígrafo">
					<div class="fav" data-fav="0" data-prod="55"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/91434_set_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">91434</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,407€							</div>
							
						<div class="titulo">MARE Bolígrafo</div>
						<div class="stock">Stock: 36.797</div>

													<ul class="colors">
																	<li style="background-color: #000000"></li>
																		<li style="background-color: #ff0000"></li>
																		<li style="background-color: #544945"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/boligrafos-de-metal/91443/" title="MIKADO Bolígrafo">
					<div class="fav" data-fav="0" data-prod="60"></div>

											<div class="tag azul">Novedad!</div>
												<div class="img-wrap center">
							<span><img src="/fotos/produtos/91443_set_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">91443</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,486€							</div>
							
						<div class="titulo">MIKADO Bolígrafo</div>
						<div class="stock">Stock: 45.749</div>

													<ul class="colors">
																	<li style="background-color: #000000"></li>
																		<li style="background-color: #ff0000"></li>
																		<li style="background-color: #fff200"></li>
																		<li style="background-color: #0046ad"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/boligrafos-de-metal/91444/" title="MIRO Bolígrafo">
					<div class="fav" data-fav="0" data-prod="61"></div>

											<div class="tag azul">Novedad!</div>
												<div class="img-wrap center">
							<span><img src="/fotos/produtos/91444_04_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">91444</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,261€							</div>
							
						<div class="titulo">MIRO Bolígrafo</div>
						<div class="stock">Stock: 100.475</div>

													<ul class="colors">
																	<li style="background-color: #0043a7"></li>
																		<li style="background-color: #ff0000"></li>
																		<li style="background-color: #fff200"></li>
																		<li style="background-color: #f7931e"></li>
																		<li style="background-color: #bee017"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/boligrafos-de-metal/91453/" title="TEXAS Bolígrafo">
					<div class="fav" data-fav="0" data-prod="67"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/91453_set_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">91453</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,441€							</div>
							
						<div class="titulo">TEXAS Bolígrafo</div>
						<div class="stock">Stock: 36.052</div>

													<ul class="colors">
																	<li style="background-color: #000000"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/boligrafos-de-metal/91485/" title="WALK Bolígrafo">
					<div class="fav" data-fav="0" data-prod="81"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/91485_set_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">91485</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,287€							</div>
							
						<div class="titulo">WALK Bolígrafo</div>
						<div class="stock">Stock: 59.278</div>

													<ul class="colors">
																	<li style="background-color: #0046ad"></li>
																		<li style="background-color: #544945"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/boligrafos-de-metal/91488/" title="MIRROR Bolígrafo">
					<div class="fav" data-fav="0" data-prod="83"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/91488_set_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">91488</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,197€							</div>
							
						<div class="titulo">MIRROR Bolígrafo</div>
						<div class="stock">Stock: 44.681</div>

													<ul class="colors">
																	<li style="background-color: #ff0000"></li>
																		<li style="background-color: #0046ad"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/boligrafos-de-metal/91493/" title="BARCODE Bolígrafo">
					<div class="fav" data-fav="0" data-prod="86"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/91493_set_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">91493</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,517€							</div>
							
						<div class="titulo">BARCODE Bolígrafo</div>
						<div class="stock">Stock: 19.711</div>

													<ul class="colors">
																	<li style="background-color: #0046ad"></li>
																		<li style="background-color: #544945"></li>
																</ul>
												</div>
				</a>
								<a class="produto new" href="/es/catalogo/boligrafos-de-metal/91494/" title="SCRIPT Bolígrafo">
					<div class="fav" data-fav="0" data-prod="87"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/91494_set_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">91494</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,441€							</div>
							
						<div class="titulo">SCRIPT Bolígrafo</div>
						<div class="stock">Stock: 24.712</div>

													<ul class="colors">
																	
																		<li style="background-color: #544945"></li>
																</ul>
												</div>
				</a>
								<a class="produto new" href="/es/catalogo/boligrafos-de-metal/91495/" title="CONVEX Bolígrafo">
					<div class="fav" data-fav="0" data-prod="88"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/91495_set_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">91495</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,562€							</div>
							
						<div class="titulo">CONVEX Bolígrafo</div>
						<div class="stock">Stock: 16.020</div>

													<ul class="colors">
																	<li style="background-color: #0046ad"></li>
																		<li style="background-color: #544945"></li>
																</ul>
												</div>
				</a>
								<a class="produto new" href="/es/catalogo/boligrafos-de-metal/91624/" title="ZOE Bolígrafo">
					<div class="fav" data-fav="0" data-prod="107"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/91624_set_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">91624</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,261€							</div>
							
						<div class="titulo">ZOE Bolígrafo</div>
						<div class="stock">Stock: 35.388</div>

													<ul class="colors">
																	<li style="background-color: #000000"></li>
																		<li style="background-color: #ffffff"></li>
																</ul>
												</div>
				</a>
								<a class="produto new" href="/es/catalogo/boligrafos-de-metal/91629/" title="HALOS Bolígrafo">
					<div class="fav" data-fav="0" data-prod="112"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/91629_set_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">91629</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,340€							</div>
							
						<div class="titulo">HALOS Bolígrafo</div>
						<div class="stock">Stock: 57.762</div>

													<ul class="colors">
																	<li style="background-color: #000000"></li>
																		<li style="background-color: #544945"></li>
																</ul>
												</div>
				</a>
								<a class="produto new" href="/es/catalogo/boligrafos-de-metal/91630/" title="FLUMA Bolígrafo">
					<div class="fav" data-fav="0" data-prod="113"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/91630_set_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">91630</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,347€							</div>
							
						<div class="titulo">FLUMA Bolígrafo</div>
						<div class="stock">Stock: 14.958</div>

													<ul class="colors">
																	<li style="background-color: #000000"></li>
																		<li style="background-color: #0046ad"></li>
																		<li style="background-color: #544945"></li>
																</ul>
												</div>
				</a>
								<a class="produto new" href="/es/catalogo/boligrafos-de-metal/91646/" title="BETA TOUCH BETA TOUCH Bolígrafo">
					<div class="fav" data-fav="0" data-prod="128"></div>

											<div class="tag azul">Novedad!</div>
												<div class="img-wrap center">
							<span><img src="/fotos/produtos/91646_set_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">91646</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,315€							</div>
							
						<div class="titulo">BETA TOUCH BETA TOUCH Bolígrafo</div>
						<div class="stock">Stock: 169.999</div>

													<ul class="colors">
																	<li style="background-color: #000000"></li>
																		<li style="background-color: #0046ad"></li>
																		<li style="background-color: #fffccb"></li>
																		<li style="background-color: #544945"></li>
																</ul>
												</div>
				</a>
								<a class="produto new" href="/es/catalogo/boligrafos-de-metal/91814/" title="DELI Bolígrafo">
					<div class="fav" data-fav="0" data-prod="150"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/91814_03_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">91814</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,782€							</div>
							
						<div class="titulo">DELI Bolígrafo</div>
						<div class="stock">Stock: 8.852</div>

													<ul class="colors">
																	<li style="background-color: #000000"></li>
																</ul>
												</div>
				</a>
								<a class="produto new" href="/es/catalogo/boligrafos-de-metal/91847/" title="INKY Bolígrafo">
					<div class="fav" data-fav="0" data-prod="169"></div>

											<div class="tag azul">Novedad!</div>
												<div class="img-wrap center">
							<span><img src="/fotos/produtos/91847_set_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">91847</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,347€							</div>
							
						<div class="titulo">INKY Bolígrafo</div>
						<div class="stock">Stock: 114.779</div>

													<ul class="colors">
																	<li style="background-color: #000000"></li>
																		<li style="background-color: #0043a7"></li>
																		<li style="background-color: #ff0000"></li>
																		<li style="background-color: #f7931e"></li>
																		<li style="background-color: #fffccb"></li>
																		<li style="background-color: #bee017"></li>
																</ul>
												</div>
				</a>
					');
    
    //Bolígrafos de Madera
    consulta_referencias_ps("Bolígrafos de Madera", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=17&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
    
    //Bolígrafos Biodegradables
    consulta_referencias_ps("Bolígrafos Biodegradables", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=18&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
    
    //Bolígrafos de Plástico
    consulta_referencias2_ps("Bolígrafos de Plástico", '
						<a class="produto" href="/es/catalogo/boligrafos-de-plastico/91032/" title="TIP Bolígrafo">
					<div class="fav" data-fav="0" data-prod="19"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/91032_set_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">91032</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,121€							</div>
							
						<div class="titulo">TIP Bolígrafo</div>
						<div class="stock">Stock: 122.618</div>

													<ul class="colors">
																	<li style="background-color: #0043a7"></li>
																		<li style="background-color: #ff0000"></li>
																		<li style="background-color: #ffffff"></li>
																		<li style="background-color: #fff200"></li>
																		<li style="background-color: #41ad00"></li>
																		<li style="background-color: #f7931e"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/boligrafos-de-plastico/91115/" title="SINGLE Bolígrafo">
					<div class="fav" data-fav="0" data-prod="8138"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/91115_10_565053732593bd8ad0280e.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">91115</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,149€							</div>
							
						<div class="titulo">SINGLE Bolígrafo</div>
						<div class="stock">Stock: 0</div>

													<ul class="colors">
																	<li style="background-color: #f7931e"></li>
																		<li style="background-color: #bee017"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/boligrafos-de-plastico/91147/" title="EAGLE Bolígrafo">
					<div class="fav" data-fav="0" data-prod="20"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/91147_set_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">91147</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,122€							</div>
							
						<div class="titulo">EAGLE Bolígrafo</div>
						<div class="stock">Stock: 70.367</div>

													<ul class="colors">
																	<li style="background-color: #000000"></li>
																		<li style="background-color: #0043a7"></li>
																		<li style="background-color: #ff0000"></li>
																		<li style="background-color: #bee017"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/boligrafos-de-plastico/91216/" title="CORVINA Bolígrafo">
					<div class="fav" data-fav="0" data-prod="21"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/91216_set_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">91216</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,082€							</div>
							
						<div class="titulo">CORVINA Bolígrafo</div>
						<div class="stock">Stock: 546.366</div>

													<ul class="colors">
																	<li style="background-color: #000000"></li>
																		<li style="background-color: #0043a7"></li>
																		<li style="background-color: #ff0000"></li>
																		<li style="background-color: #ffffff"></li>
																		<li style="background-color: #fff200"></li>
																		<li style="background-color: #41ad00"></li>
																		<li style="background-color: #f7931e"></li>
																		<li style="background-color: #42b1ff"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/boligrafos-de-plastico/91226/" title="LEILA Bolígrafo">
					<div class="fav" data-fav="0" data-prod="23"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/91226_set_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">91226</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,150€							</div>
							
						<div class="titulo">LEILA Bolígrafo</div>
						<div class="stock">Stock: 247.183</div>

													<ul class="colors">
																	<li style="background-color: #0043a7"></li>
																		<li style="background-color: #ff0000"></li>
																		<li style="background-color: #ffffff"></li>
																		<li style="background-color: #fff200"></li>
																		<li style="background-color: #41ad00"></li>
																		<li style="background-color: #f7931e"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/boligrafos-de-plastico/91247/" title="SLIM Bolígrafo">
					<div class="fav" data-fav="0" data-prod="24"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/91247_set_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">91247</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,065€							</div>
							
						<div class="titulo">SLIM Bolígrafo</div>
						<div class="stock">Stock: 996.045</div>

													<ul class="colors">
																	<li style="background-color: #000000"></li>
																		<li style="background-color: #0043a7"></li>
																		<li style="background-color: #ff0000"></li>
																		<li style="background-color: #ffffff"></li>
																		<li style="background-color: #fff200"></li>
																		<li style="background-color: #41ad00"></li>
																		<li style="background-color: #f7931e"></li>
																		<li style="background-color: #f387a8"></li>
																		<li style="background-color: #42b1ff"></li>
																		<li style="background-color: #be13c8"></li>
																		<li style="background-color: #bee017"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/boligrafos-de-plastico/91255/" title="ALFA Bolígrafo">
					<div class="fav" data-fav="0" data-prod="25"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/91255_set_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">91255</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,186€							</div>
							
						<div class="titulo">ALFA Bolígrafo</div>
						<div class="stock">Stock: 78.409</div>

													<ul class="colors">
																	<li style="background-color: #a62828"></li>
																		<li style="background-color: #000000"></li>
																		<li style="background-color: #0043a7"></li>
																		<li style="background-color: #41ad00"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/boligrafos-de-plastico/91256/" title="CARIBE Bolígrafo">
					<div class="fav" data-fav="0" data-prod="26"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/91256_set_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">91256</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,105€							</div>
							
						<div class="titulo">CARIBE Bolígrafo</div>
						<div class="stock">Stock: 1.845.567</div>

													<ul class="colors">
																	<li style="background-color: #0043a7"></li>
																		<li style="background-color: #ff0000"></li>
																		<li style="background-color: #ffffff"></li>
																		<li style="background-color: #fff200"></li>
																		<li style="background-color: #41ad00"></li>
																		<li style="background-color: #f7931e"></li>
																		<li style="background-color: #f387a8"></li>
																		<li style="background-color: #bee017"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/boligrafos-de-plastico/91274/" title="MIST Bolígrafo">
					<div class="fav" data-fav="0" data-prod="8141"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/91274_05_1411938380593bda0f3fabe.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">91274</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,080€							</div>
							
						<div class="titulo">MIST Bolígrafo</div>
						<div class="stock">Stock: 40.558</div>

													<ul class="colors">
																	<li style="background-color: #ff0000"></li>
																		<li style="background-color: #41ad00"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/boligrafos-de-plastico/91287/" title="ITZA Bolígrafo">
					<div class="fav" data-fav="0" data-prod="27"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/91287_set_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">91287</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,122€							</div>
							
						<div class="titulo">ITZA Bolígrafo</div>
						<div class="stock">Stock: 118.879</div>

													<ul class="colors">
																	<li style="background-color: #000000"></li>
																		<li style="background-color: #0043a7"></li>
																		<li style="background-color: #ff0000"></li>
																		<li style="background-color: #be13c8"></li>
																		<li style="background-color: #bee017"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/boligrafos-de-plastico/91371/" title="BAGAN Bolígrafo">
					<div class="fav" data-fav="0" data-prod="8144"></div>

					
					<div class="bottom">
						<div class="ref">91371</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,082€							</div>
							
						<div class="titulo">BAGAN Bolígrafo</div>
						<div class="stock">Stock: 0</div>

													<ul class="colors">
															</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/boligrafos-de-plastico/91385/" title="WHITY Bolígrafo">
					<div class="fav" data-fav="0" data-prod="44"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/91385_03_45200629258baf09179ccb_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">91385</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,077€							</div>
							
						<div class="titulo">WHITY Bolígrafo</div>
						<div class="stock">Stock: 15.813</div>

													<ul class="colors">
																	<li style="background-color: #000000"></li>
																		<li style="background-color: #0043a7"></li>
																		<li style="background-color: #ff0000"></li>
																		<li style="background-color: #fff200"></li>
																		<li style="background-color: #f7931e"></li>
																		<li style="background-color: #42b1ff"></li>
																		<li style="background-color: #be13c8"></li>
																		<li style="background-color: #bee017"></li></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/boligrafos-de-plastico/91386/" title="CALIS Bolígrafo">
					<div class="fav" data-fav="0" data-prod="8146"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/91386_05_977470041593be46783829_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">91386</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,062€							</div>
							
						<div class="titulo">CALIS Bolígrafo</div>
						<div class="stock">Stock: 1.801</div>

													<ul class="colors">
																	<li style="background-color: #ff0000"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/boligrafos-de-plastico/91393/" title="CHAPLIN Bolígrafo">
					<div class="fav" data-fav="0" data-prod="46"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/91393_set_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">91393</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,131€							</div>
							
						<div class="titulo">CHAPLIN Bolígrafo</div>
						<div class="stock">Stock: 113.744</div>

													<ul class="colors">
																	<li style="background-color: #000000"></li>
																		<li style="background-color: #ffffff"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/boligrafos-de-plastico/91396/" title="TOUCAN Bolígrafo">
					<div class="fav" data-fav="0" data-prod="47"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/91396_set_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">91396</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,131€							</div>
							
						<div class="titulo">TOUCAN Bolígrafo</div>
						<div class="stock">Stock: 231.102</div>

													<ul class="colors">
																	<li style="background-color: #0043a7"></li>
																		<li style="background-color: #ff0000"></li>
																		<li style="background-color: #fff200"></li>
																		<li style="background-color: #f7931e"></li>
																		<li style="background-color: #bee017"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/boligrafos-de-plastico/91398/" title="POPLIN Bolígrafo">
					<div class="fav" data-fav="0" data-prod="8148"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/91398_05_1698231231593be4c531436.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">91398</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,097€							</div>
							
						<div class="titulo">POPLIN Bolígrafo</div>
						<div class="stock">Stock: 4.589</div>

													<ul class="colors">
																	<li style="background-color: #ff0000"></li>
																		<li style="background-color: #fff200"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/boligrafos-de-plastico/91399/" title="CARIBE XOK CARIBE XOK Bolígrafo">
					<div class="fav" data-fav="0" data-prod="49"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/91399_set_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">91399</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,115€							</div>
							
						<div class="titulo">CARIBE XOK CARIBE XOK Bolígrafo</div>
						<div class="stock">Stock: 174.792</div>

													<ul class="colors">
																	<li style="background-color: #f387a8"></li>
																		<li style="background-color: #42b1ff"></li>
																		<li style="background-color: #bee017"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/boligrafos-de-plastico/91430/" title="SURYA Bolígrafo">
					<div class="fav" data-fav="0" data-prod="52"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/91430_set_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">91430</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,236€							</div>
							
						<div class="titulo">SURYA Bolígrafo</div>
						<div class="stock">Stock: 145.614</div>

													<ul class="colors">
																	<li style="background-color: #000000"></li>
																		<li style="background-color: #0043a7"></li>
																		<li style="background-color: #ffffff"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/boligrafos-de-plastico/91445/" title="TYRA Bolígrafo">
					<div class="fav" data-fav="0" data-prod="62"></div>

											<div class="tag azul">Novedad!</div>
												<div class="img-wrap center">
							<span><img src="/fotos/produtos/91445_44-A_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">91445</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,362€							</div>
							
						<div class="titulo">TYRA Bolígrafo</div>
						<div class="stock">Stock: 12.926</div>

													<ul class="colors">
																	
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/boligrafos-de-plastico/91446/" title="KOLY Bolígrafo">
					<div class="fav" data-fav="0" data-prod="63"></div>

											<div class="tag azul">Novedad!</div>
												<div class="img-wrap center">
							<span><img src="/fotos/produtos/91446_set_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">91446</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,141€							</div>
							
						<div class="titulo">KOLY Bolígrafo</div>
						<div class="stock">Stock: 243.310</div>

													<ul class="colors">
																	<li style="background-color: #000000"></li>
																		<li style="background-color: #ff0000"></li>
																		<li style="background-color: #ffffff"></li>
																		<li style="background-color: #0046ad"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/boligrafos-de-plastico/91447/" title="VOLPI Bolígrafo">
					<div class="fav" data-fav="0" data-prod="64"></div>

											<div class="tag azul">Novedad!</div>
												<div class="img-wrap center">
							<span><img src="/fotos/produtos/91447_set_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">91447</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,137€							</div>
							
						<div class="titulo">VOLPI Bolígrafo</div>
						<div class="stock">Stock: 178.731</div>

													<ul class="colors">
																	<li style="background-color: #0043a7"></li>
																		<li style="background-color: #ff0000"></li>
																		<li style="background-color: #f7931e"></li>
																		<li style="background-color: #bee017"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/boligrafos-de-plastico/91448/" title="GAUSS Bolígrafo">
					<div class="fav" data-fav="0" data-prod="65"></div>

											<div class="tag azul">Novedad!</div>
												<div class="img-wrap center">
							<span><img src="/fotos/produtos/91448_set_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">91448</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,161€							</div>
							
						<div class="titulo">GAUSS Bolígrafo</div>
						<div class="stock">Stock: 163.959</div>

													<ul class="colors">
																	<li style="background-color: #000000"></li>
																		<li style="background-color: #ff0000"></li>
																		<li style="background-color: #ffffff"></li>
																		<li style="background-color: #0046ad"></li>
																		<li style="background-color: #544945"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/boligrafos-de-plastico/91452/" title="DELTA Bolígrafo">
					<div class="fav" data-fav="0" data-prod="8150"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/91452_03_534497091593be58c40e84.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">91452</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,115€							</div>
							
						<div class="titulo">DELTA Bolígrafo</div>
						<div class="stock">Stock: 30</div>

													<ul class="colors">
																	<li style="background-color: #000000"></li>
																		<li style="background-color: #0043a7"></li>
																		<li style="background-color: #f7931e"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/boligrafos-de-plastico/91454/" title="COOKIE Bolígrafo">
					<div class="fav" data-fav="0" data-prod="68"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/91454_06_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">91454</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,065€							</div>
							
						<div class="titulo">COOKIE Bolígrafo</div>
						<div class="stock">Stock: 72.150</div>

													<ul class="colors">
																	<li style="background-color: #ffffff"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/boligrafos-de-plastico/91458/" title="FUTUR Bolígrafo">
					<div class="fav" data-fav="0" data-prod="70"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/91458_set_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">91458</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,152€							</div>
							
						<div class="titulo">FUTUR Bolígrafo</div>
						<div class="stock">Stock: 45.643</div>

													<ul class="colors">
																	<li style="background-color: #000000"></li>
																		<li style="background-color: #ffffff"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/boligrafos-de-plastico/91471/" title="ODESSA Bolígrafo">
					<div class="fav" data-fav="0" data-prod="8151"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/91471_10_729914339593be5d26ee8d.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">91471</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,082€							</div>
							
						<div class="titulo">ODESSA Bolígrafo</div>
						<div class="stock">Stock: 3.454</div>

													<ul class="colors">
																	<li style="background-color: #f7931e"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/boligrafos-de-plastico/91473/" title="SENA Bolígrafo">
					<div class="fav" data-fav="0" data-prod="72"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/91473_set_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">91473</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,051€							</div>
							
						<div class="titulo">SENA Bolígrafo</div>
						<div class="stock">Stock: 88.642</div>

													<ul class="colors">
																	<li style="background-color: #000000"></li>
																		<li style="background-color: #0043a7"></li>
																		<li style="background-color: #ff0000"></li>
																		<li style="background-color: #ffffff"></li>
																		<li style="background-color: #fff200"></li>
																		<li style="background-color: #41ad00"></li>
																		<li style="background-color: #f7931e"></li>
																		<li style="background-color: #42b1ff"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/boligrafos-de-plastico/91478/" title="SATURDAY Bolígrafo">
					<div class="fav" data-fav="0" data-prod="75"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/91478_set_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">91478</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,132€							</div>
							
						<div class="titulo">SATURDAY Bolígrafo</div>
						<div class="stock">Stock: 145.249</div>

													<ul class="colors">
																	<li style="background-color: #000000"></li>
																		<li style="background-color: #0043a7"></li>
																		<li style="background-color: #ff0000"></li>
																		<li style="background-color: #41ad00"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/boligrafos-de-plastico/91480/" title="SUNRISE Bolígrafo">
					<div class="fav" data-fav="0" data-prod="77"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/91480_set_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">91480</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,127€							</div>
							
						<div class="titulo">SUNRISE Bolígrafo</div>
						<div class="stock">Stock: 172.116</div>

													<ul class="colors">
																	<li style="background-color: #000000"></li>
																		<li style="background-color: #0043a7"></li>
																		<li style="background-color: #ffffff"></li>
																		<li style="background-color: #42b1ff"></li>
																		<li style="background-color: #bee017"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/boligrafos-de-plastico/91481/" title="BOWIE Bolígrafo">
					<div class="fav" data-fav="0" data-prod="78"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/91481_set_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">91481</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,167€							</div>
							
						<div class="titulo">BOWIE Bolígrafo</div>
						<div class="stock">Stock: 59.302</div>

													<ul class="colors">
																	<li style="background-color: #000000"></li>
																		<li style="background-color: #0043a7"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/boligrafos-de-plastico/91484/" title="AURIGA Bolígrafo">
					<div class="fav" data-fav="0" data-prod="80"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/91484_set_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">91484</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,082€							</div>
							
						<div class="titulo">AURIGA Bolígrafo</div>
						<div class="stock">Stock: 91.695</div>

													<ul class="colors">
																	<li style="background-color: #000000"></li>
																		<li style="background-color: #0043a7"></li>
																		<li style="background-color: #ff0000"></li>
																		<li style="background-color: #f7931e"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/boligrafos-de-plastico/91486/" title="RUBIX Bolígrafo">
					<div class="fav" data-fav="0" data-prod="82"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/91486_set_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">91486</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,116€							</div>
							
						<div class="titulo">RUBIX Bolígrafo</div>
						<div class="stock">Stock: 190.324</div>

													<ul class="colors">
																	<li style="background-color: #ff0000"></li>
																		<li style="background-color: #fff200"></li>
																		<li style="background-color: #f7931e"></li>
																		<li style="background-color: #42b1ff"></li>
																		<li style="background-color: #bee017"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/boligrafos-de-plastico/91487/" title="THAMES Bolígrafo">
					<div class="fav" data-fav="0" data-prod="8152"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/91487_03_2044899255593be62407f6b.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">91487</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,172€							</div>
							
						<div class="titulo">THAMES Bolígrafo</div>
						<div class="stock">Stock: 24</div>

													<ul class="colors">
																	<li style="background-color: #000000"></li>
																		<li style="background-color: #0043a7"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/boligrafos-de-plastico/91490/" title="SENA Bolígrafo">
					<div class="fav" data-fav="0" data-prod="85"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/91490_set_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">91490</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,051€							</div>
							
						<div class="titulo">SENA Bolígrafo</div>
						<div class="stock">Stock: 91.066</div>

													<ul class="colors">
																	<li style="background-color: #000000"></li>
																		<li style="background-color: #0043a7"></li>
																		<li style="background-color: #ff0000"></li>
																		<li style="background-color: #ffffff"></li>
																		<li style="background-color: #fff200"></li>
																		<li style="background-color: #41ad00"></li>
																		<li style="background-color: #f7931e"></li>
																		<li style="background-color: #42b1ff"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/boligrafos-de-plastico/91498/" title="MARS Bolígrafo">
					<div class="fav" data-fav="0" data-prod="91"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/91498_set_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">91498</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,070€							</div>
							
						<div class="titulo">MARS Bolígrafo</div>
						<div class="stock">Stock: 293.604</div>

													<ul class="colors">
																	<li style="background-color: #000000"></li>
																		<li style="background-color: #0043a7"></li>
																		<li style="background-color: #ff0000"></li>
																		<li style="background-color: #ffffff"></li>
																		<li style="background-color: #fff200"></li>
																		<li style="background-color: #f7931e"></li>
																		<li style="background-color: #be13c8"></li>
																		<li style="background-color: #bee017"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/boligrafos-de-plastico/91499/" title="MARS Bolígrafo">
					<div class="fav" data-fav="0" data-prod="92"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/91499_set_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">91499</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,077€							</div>
							
						<div class="titulo">MARS Bolígrafo</div>
						<div class="stock">Stock: 255.201</div>

													<ul class="colors">
																	<li style="background-color: #000000"></li>
																		<li style="background-color: #0043a7"></li>
																		<li style="background-color: #ff0000"></li>
																		<li style="background-color: #41ad00"></li>
																		<li style="background-color: #42b1ff"></li>
																		<li style="background-color: #be13c8"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/boligrafos-de-plastico/91622/" title="CAMBRIA Bolígrafo">
					<div class="fav" data-fav="0" data-prod="105"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/91622_03-A_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">91622</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,162€							</div>
							
						<div class="titulo">CAMBRIA Bolígrafo</div>
						<div class="stock">Stock: 34.079</div>

													<ul class="colors">
																	<li style="background-color: #000000"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/boligrafos-de-plastico/91623/" title="ARCADA Bolígrafo">
					<div class="fav" data-fav="0" data-prod="106"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/91623_set_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">91623</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,132€							</div>
							
						<div class="titulo">ARCADA Bolígrafo</div>
						<div class="stock">Stock: 148.947</div>

													<ul class="colors">
																	<li style="background-color: #0043a7"></li>
																		<li style="background-color: #ff0000"></li>
																		<li style="background-color: #41ad00"></li>
																		<li style="background-color: #544945"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/boligrafos-de-plastico/91625/" title="STRACED Bolígrafo">
					<div class="fav" data-fav="0" data-prod="108"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/91625_set_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">91625</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,136€							</div>
							
						<div class="titulo">STRACED Bolígrafo</div>
						<div class="stock">Stock: 96.963</div>

													<ul class="colors">
																	<li style="background-color: #0043a7"></li>
																		<li style="background-color: #ff0000"></li>
																		<li style="background-color: #f7931e"></li>
																		<li style="background-color: #bee017"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/boligrafos-de-plastico/91626/" title="MILA Bolígrafo">
					<div class="fav" data-fav="0" data-prod="109"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/91626_set_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">91626</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,092€							</div>
							
						<div class="titulo">MILA Bolígrafo</div>
						<div class="stock">Stock: 220.327</div>

													<ul class="colors">
																	<li style="background-color: #000000"></li>
																		<li style="background-color: #0043a7"></li>
																		<li style="background-color: #ff0000"></li>
																		<li style="background-color: #fff200"></li>
																		<li style="background-color: #41ad00"></li>
																		<li style="background-color: #f7931e"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/boligrafos-de-plastico/91627/" title="FOCUS Bolígrafo">
					<div class="fav" data-fav="0" data-prod="110"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/91627_set_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">91627</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,087€							</div>
							
						<div class="titulo">FOCUS Bolígrafo</div>
						<div class="stock">Stock: 100.205</div>

													<ul class="colors">
																	<li style="background-color: #000000"></li>
																		<li style="background-color: #0043a7"></li>
																		<li style="background-color: #ff0000"></li>
																		<li style="background-color: #41ad00"></li>
																		<li style="background-color: #f7931e"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/boligrafos-de-plastico/91631/" title="JELLY Bolígrafo">
					<div class="fav" data-fav="0" data-prod="114"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/91631_set_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">91631</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,082€							</div>
							
						<div class="titulo">JELLY Bolígrafo</div>
						<div class="stock">Stock: 290.462</div>

													<ul class="colors">
																	<li style="background-color: #0043a7"></li>
																		<li style="background-color: #ff0000"></li>
																		<li style="background-color: #f7931e"></li>
																		<li style="background-color: #bee017"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/boligrafos-de-plastico/91632/" title="LESTER Bolígrafo">
					<div class="fav" data-fav="0" data-prod="115"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/91632_set_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">91632</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,117€							</div>
							
						<div class="titulo">LESTER Bolígrafo</div>
						<div class="stock">Stock: 239.738</div>

													<ul class="colors">
																	<li style="background-color: #000000"></li>
																		<li style="background-color: #0043a7"></li>
																		<li style="background-color: #ff0000"></li>
																		<li style="background-color: #bee017"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/boligrafos-de-plastico/91633/" title="CURL Bolígrafo">
					<div class="fav" data-fav="0" data-prod="116"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/91633_set_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">91633</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,117€							</div>
							
						<div class="titulo">CURL Bolígrafo</div>
						<div class="stock">Stock: 429.594</div>

													<ul class="colors">
																	<li style="background-color: #000000"></li>
																		<li style="background-color: #ff0000"></li>
																		<li style="background-color: #f7931e"></li>
																		<li style="background-color: #42b1ff"></li>
																		<li style="background-color: #be13c8"></li>
																		<li style="background-color: #bee017"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/boligrafos-de-plastico/91635/" title="AERO Bolígrafo">
					<div class="fav" data-fav="0" data-prod="118"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/91635_set_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">91635</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,082€							</div>
							
						<div class="titulo">AERO Bolígrafo</div>
						<div class="stock">Stock: 235.724</div>

													<ul class="colors">
																	<li style="background-color: #000000"></li>
																		<li style="background-color: #0043a7"></li>
																		<li style="background-color: #ff0000"></li>
																		<li style="background-color: #fff200"></li>
																		<li style="background-color: #41ad00"></li>
																		<li style="background-color: #f7931e"></li>
																		<li style="background-color: #be13c8"></li>
																</ul>
												</div>
				</a>
								<a class="produto new" href="/es/catalogo/boligrafos-de-plastico/91639/" title="CARTER Bolígrafo">
					<div class="fav" data-fav="0" data-prod="121"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/91639_44_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">91639</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,212€							</div>
							
						<div class="titulo">CARTER Bolígrafo</div>
						<div class="stock">Stock: 18.306</div>

													<ul class="colors">
																	
																</ul>
												</div>
				</a>
								<a class="produto new" href="/es/catalogo/boligrafos-de-plastico/91640/" title="SPECTRA Bolígrafo">
					<div class="fav" data-fav="0" data-prod="122"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/91640_set_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">91640</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,142€							</div>
							
						<div class="titulo">SPECTRA Bolígrafo</div>
						<div class="stock">Stock: 99.552</div>

													<ul class="colors">
																	<li style="background-color: #000000"></li>
																		<li style="background-color: #0043a7"></li>
																</ul>
												</div>
				</a>
								<a class="produto new" href="/es/catalogo/boligrafos-de-plastico/91641/" title="GARDA Bolígrafo">
					<div class="fav" data-fav="0" data-prod="123"></div>

											<div class="tag azul">Novedad!</div>
												<div class="img-wrap center">
							<span><img src="/fotos/produtos/91641_14-A_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">91641</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,201€							</div>
							
						<div class="titulo">GARDA Bolígrafo</div>
						<div class="stock">Stock: 40.784</div>

													<ul class="colors">
																	<li style="background-color: #000000"></li>
																		<li style="background-color: #0046ad"></li>
																</ul>
												</div>
				</a>
								<a class="produto new" href="/es/catalogo/boligrafos-de-plastico/91642/" title="TECNA Bolígrafo">
					<div class="fav" data-fav="0" data-prod="124"></div>

											<div class="tag azul">Novedad!</div>
												<div class="img-wrap center">
							<span><img src="/fotos/produtos/91642_03_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">91642</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,186€							</div>
							
						<div class="titulo">TECNA Bolígrafo</div>
						<div class="stock">Stock: 91.769</div>

													<ul class="colors">
																	<li style="background-color: #000000"></li>
																		<li style="background-color: #ff0000"></li>
																		<li style="background-color: #0046ad"></li>
																</ul>
												</div>
				</a>
								<a class="produto new" href="/es/catalogo/boligrafos-de-plastico/91643/" title="CIRCLE Bolígrafo">
					<div class="fav" data-fav="0" data-prod="125"></div>

											<div class="tag azul">Novedad!</div>
												<div class="img-wrap center">
							<span><img src="/fotos/produtos/91643_04_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">91643</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,182€							</div>
							
						<div class="titulo">CIRCLE Bolígrafo</div>
						<div class="stock">Stock: 156.435</div>

													<ul class="colors">
																	<li style="background-color: #0043a7"></li>
																		<li style="background-color: #ff0000"></li>
																		<li style="background-color: #42b1ff"></li>
																		<li style="background-color: #bee017"></li>
																</ul>
												</div>
				</a>
								<a class="produto new" href="/es/catalogo/boligrafos-de-plastico/91644/" title="SUNNY Bolígrafo">
					<div class="fav" data-fav="0" data-prod="126"></div>

											<div class="tag azul">Novedad!</div>
												<div class="img-wrap center">
							<span><img src="/fotos/produtos/91644_set_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">91644</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,137€							</div>
							
						<div class="titulo">SUNNY Bolígrafo</div>
						<div class="stock">Stock: 233.725</div>

													<ul class="colors">
																	<li style="background-color: #0043a7"></li>
																		<li style="background-color: #ff0000"></li>
																		<li style="background-color: #fff200"></li>
																		<li style="background-color: #f7931e"></li>
																		<li style="background-color: #bee017"></li>
																</ul>
												</div>
				</a>
								<a class="produto new" href="/es/catalogo/boligrafos-de-plastico/91645/" title="RIFE Bolígrafo">
					<div class="fav" data-fav="0" data-prod="127"></div>

											<div class="tag azul">Novedad!</div>
												<div class="img-wrap center">
							<span><img src="/fotos/produtos/91645_set_42952836058b06c60c07af_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">91645</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,094€							</div>
							
						<div class="titulo">RIFE Bolígrafo</div>
						<div class="stock">Stock: 295.049</div>

													<ul class="colors">
																	<li style="background-color: #000000"></li>
																		<li style="background-color: #0043a7"></li>
																		<li style="background-color: #ff0000"></li>
																		<li style="background-color: #ffffff"></li>
																		<li style="background-color: #fff200"></li>
																		<li style="background-color: #f7931e"></li>
																		<li style="background-color: #bee017"></li>
																</ul>
												</div>
				</a>
								<a class="produto new" href="/es/catalogo/boligrafos-de-plastico/91674/" title="PO Bolígrafo">
					<div class="fav" data-fav="0" data-prod="131"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/91674_set_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">91674</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,136€							</div>
							
						<div class="titulo">PO Bolígrafo</div>
						<div class="stock">Stock: 332.706</div>

													<ul class="colors">
																	<li style="background-color: #ff0000"></li>
																		<li style="background-color: #f7931e"></li>
																		<li style="background-color: #0046ad"></li>
																		<li style="background-color: #ff007e"></li>
																		<li style="background-color: #bee017"></li>
																</ul>
												</div>
				</a>
								<a class="produto new" href="/es/catalogo/boligrafos-de-plastico/91686/" title="AMER Bolígrafo">
					<div class="fav" data-fav="0" data-prod="132"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/91686_05_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">91686</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,086€							</div>
							
						<div class="titulo">AMER Bolígrafo</div>
						<div class="stock">Stock: 212.761</div>

													<ul class="colors">
																	<li style="background-color: #ff0000"></li>
																		<li style="background-color: #fff200"></li>
																		<li style="background-color: #f7931e"></li>
																		<li style="background-color: #42b1ff"></li>
																		<li style="background-color: #0046ad"></li>
																		<li style="background-color: #be13c8"></li>
																		<li style="background-color: #bee017"></li>
																</ul>
												</div>
				</a>
					');
    
    //Rollers
    consulta_referencias_ps("Rollers", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=20&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
    
    //Bolígrafos - Marcas
    consulta_referencias_ps("Bolígrafos - Marcas", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=27&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
    
    //Lápices
    consulta_referencias_ps("Lápices", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=32&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
    
    //Lápices de Colores
    consulta_referencias_ps("Lápices de Colores", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=34&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
    
    //Lápices - Otros
    consulta_referencias_ps("Lápices - Otros", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=36&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
    
    //Accesorios de Escritura
    consulta_referencias_ps("Accesorios de Escritura", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=38&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
    
    //Accesorios de Moda
    consulta_referencias_ps("Accesorios de Moda", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=42&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
    
    //Accesorios de Escritura
    consulta_referencias_ps("Accesorios de Escritura", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=38&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
    
    //Accesorios de Moda
    consulta_referencias_ps("Accesorios de Moda", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=42&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
    
    //Paraguas Automáticos
    consulta_referencias_ps("Paraguas Automáticos", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=50&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
    
    //Paraguas Manuales
    consulta_referencias_ps("Paraguas Manuales", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=51&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
    
    //Paraguas Plegables
    consulta_referencias_ps("Paraguas Plegables", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=52&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
    
    //Chubasqueros
    consulta_referencias_ps("Chubasqueros", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=54&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
    
    //Bandanas
    consulta_referencias_ps("Bandanas", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=57&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
    
    //Gorras
    consulta_referencias_ps("Gorras", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=58&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
    
    //Gorros Panamá
    consulta_referencias_ps("Gorros Panamá", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=61&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
    
    //Sombreros
    consulta_referencias_ps("Sombreros", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=67&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
    
    //Accesorios - Otros
    consulta_referencias_ps("Accesorios - Otros", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=75&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
    
    //Accesorios - Verano
    consulta_referencias_ps("Accesorios - Verano", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=76&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
    
    //Accesorios - Invierno
    consulta_referencias_ps("Accesorios - Invierno", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=77&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
    
    //Mantas
    consulta_referencias_ps("Mantas", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=82&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
    
    //Toallas
    consulta_referencias_ps("Toallas", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=86&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
    
    //Toallas de Playa
    consulta_referencias_ps("Toallas de Playa", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=87&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
    
    //Delantales
    consulta_referencias_ps("Delantales", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=92&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
    
    //Pastilleros
    consulta_referencias_ps("Pastilleros", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=126&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
    
    //Ceniceros
    consulta_referencias_ps("Ceniceros", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=130&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
    
    //Sets de Manicura
    consulta_referencias_ps("Sets de Manicura", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=134&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
    
    //Espejos y Cepillos Plegables
    consulta_referencias_ps("Espejos y Cepillos Plegables", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=136&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
    
    //Anti-Estrés
    consulta_referencias_ps("Anti-Estrés", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=139&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
    
    //Personales - Otros
    consulta_referencias_ps("Personales - Otros", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=140&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
    
    //Decoración
    consulta_referencias_ps("Decoración", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=141&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
    
    //Marcos de Fotos de Metal
    consulta_referencias_ps("Marcos de Fotos de Metal", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=145&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
    
    //Estaciones Digitales
    consulta_referencias_ps("Estaciones Digitales", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=147&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
    
    //Velas y Sets Aromáticos
    consulta_referencias_ps("Velas y Sets Aromáticos", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=153&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
    
    //Huchas
    consulta_referencias_ps("Huchas", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=159&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
    
    //Navidad
    consulta_referencias_ps("Navidad", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=162&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
    
    //Adornos de Navidad
    consulta_referencias_ps("Adornos de Navidad", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=163&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
    
    //Calcetines de Navidad
    consulta_referencias_ps("Calcetines de Navidad", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=165&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
    
    //Gorros de Navidad
    consulta_referencias_ps("Gorros de Navidad", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=166&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
    
    //Decoraciones Navideñas
    consulta_referencias_ps("Decoraciones Navideñas", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=168&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
    
    //Niños
    consulta_referencias_ps("Niños", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=169&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
    
    //Juegos
    consulta_referencias_ps("Juegos", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=171&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
    
    //Menaje - Navidad
    consulta_referencias_ps("Menaje - Navidad", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=173&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
    
    //Lanyards
    consulta_referencias_ps("Lanyards", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=176&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
    
    //Llaveros Multiusos
    consulta_referencias_ps("Llaveros Multiusos", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=178&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
    
    //Llaveros con Moneda
    consulta_referencias_ps("Llaveros con Moneda", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=179&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
    
    //Llaveros con Linterna
    consulta_referencias_ps("Llaveros con Linterna", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=180&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
    
    //Llaveros de Plástico
    consulta_referencias_ps("Llaveros de Plástico", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=184&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
    
    //Llaveros de Metal
    consulta_referencias_ps("Llaveros de Metal", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=185&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
    
    //Llaveros de Metal y Otros
    consulta_referencias_ps("Llaveros de Metal y Otros", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=186&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
    
    //Llaveros de Madera
    consulta_referencias_ps("Llaveros de Madera", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=188&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
    
    //Llaveros - Otros
    consulta_referencias_ps("Llaveros - Otros", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=190&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
    
    //Llaveros de PVC/Silicona
    consulta_referencias_ps("Llaveros de PVC/Silicona", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=191&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
    
    //Otros Adornos
    consulta_referencias_ps("Otros Adornos", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=195&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
    
    //Juegos
    consulta_referencias_ps("Juegos", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=196&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
    
    //Pelota de Fútbol
    consulta_referencias_ps("Pelota de Fútbol", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=204&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
    
    //Juegos al aire libre
    consulta_referencias_ps("Juegos al aire libre", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=206&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
    
    //Peluches
    consulta_referencias_ps("Peluches", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=208&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
    
    //Tazas Mug de Cerámica
    consulta_referencias_ps("Tazas Mug de Cerámica", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=211&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
    
    //Tazas Mug de Aluminio
    consulta_referencias_ps("Tazas Mug de Aluminio", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=213&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
    
    //Tazas de Viaje
    consulta_referencias_ps("Tazas de Viaje", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=215&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
    
    //Tazas Mug - Otras
    consulta_referencias_ps("Tazas Mug - Otras", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=216&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
    
    //Botellas de Aluminio
    consulta_referencias_ps("Botellas de Aluminio", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=219&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
    
    //Botellas de Plástico
    consulta_referencias_ps("Botellas de Plástico", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=220&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
    
    //Botellas Térmicas
    consulta_referencias_ps("Botellas Térmicas", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=226&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
    
    //Shaker plástico
    consulta_referencias_ps("Shaker plástico", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=229&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
    
    //Artículos para Vino
    consulta_referencias_ps("Artículos para Vino", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=230&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
    
    //Fundas Enfriadoras
    consulta_referencias_ps("Fundas Enfriadoras", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=232&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
    
    //Utensilios de Cocina
    consulta_referencias2_ps("Utensilios de Cocina", '<div id="produtos-wrap" class="list" data-limit-curr="17" data-limit="32" data-order="5">
						<a class="produto" href="/es/catalogo/utensilios-de-cocina/92836/" title="Bolsa para el pan">
					<div class="fav" data-fav="0" data-prod="331"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/92836_60_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">92836</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,461€							</div>
							
						<div class="titulo">Bolsa para el pan</div>
						<div class="stock">Stock: 9.509</div>

													
												</div>
				</a>
								<a class="produto" href="/es/catalogo/utensilios-de-cocina/93859/" title="Caja hermética">
					<div class="fav" data-fav="0" data-prod="496"></div>

											<div class="tag azul">Novedad!</div>
												<div class="img-wrap center">
							<span><img src="/fotos/produtos/93859_13_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">93859</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,666€							</div>
							
						<div class="titulo">Caja hermética</div>
						<div class="stock">Stock: 14.021</div>

													<ul class="colors">
																	<li style="background-color: #42b1ff"></li>
																		<li style="background-color: #bee017"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/utensilios-de-cocina/93862/" title="Vaporizador de vinagre y aceite">
					<div class="fav" data-fav="0" data-prod="499"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/93862_44_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">93862</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								3,192€							</div>
							
						<div class="titulo">Vaporizador de vinagre y aceite</div>
						<div class="stock">Stock: 3.308</div>

													
												</div>
				</a>
								<a class="produto" href="/es/catalogo/utensilios-de-cocina/93871/" title="Set de 3 cajas herméticas">
					<div class="fav" data-fav="0" data-prod="504"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/93871_00_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">93871</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								1,187€							</div>
							
						<div class="titulo">Set de 3 cajas herméticas</div>
						<div class="stock">Stock: 6.012</div>

													
												</div>
				</a>
								<a class="produto" href="/es/catalogo/utensilios-de-cocina/93875/" title="Vinagrera">
					<div class="fav" data-fav="0" data-prod="508"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/93875_61_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">93875</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								4,089€							</div>
							
						<div class="titulo">Vinagrera</div>
						<div class="stock">Stock: 0</div>

													
												</div>
				</a>
								<a class="produto" href="/es/catalogo/utensilios-de-cocina/93877/" title="Set de 2 argollas para servilletas">
					<div class="fav" data-fav="0" data-prod="509"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/93877_60_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">93877</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								1,693€							</div>
							
						<div class="titulo">Set de 2 argollas para servilletas</div>
						<div class="stock">Stock: 2.385</div>

													
												</div>
				</a>
								<a class="produto" href="/es/catalogo/utensilios-de-cocina/93878/" title="Recipiente para ensalada">
					<div class="fav" data-fav="0" data-prod="510"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/93878_05_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">93878</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,711€							</div>
							
						<div class="titulo">Recipiente para ensalada</div>
						<div class="stock">Stock: 16.150</div>

													<ul class="colors">
																	<li style="background-color: #ff0000"></li>
																		<li style="background-color: #ffffff"></li>
																		<li style="background-color: #0046ad"></li>
																		<li style="background-color: #bee017"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/utensilios-de-cocina/93881/" title="Exprimidor de cítricos">
					<div class="fav" data-fav="0" data-prod="513"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/93881_set_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">93881</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,531€							</div>
							
						<div class="titulo">Exprimidor de cítricos</div>
						<div class="stock">Stock: 12.789</div>

													<ul class="colors">
																	<li style="background-color: #f7931e"></li>
																		<li style="background-color: #42b1ff"></li>
																		<li style="background-color: #bee017"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/utensilios-de-cocina/93889/" title="Molinillo de pimienta/sal">
					<div class="fav" data-fav="0" data-prod="521"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/93889_60_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">93889</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								4,503€							</div>
							
						<div class="titulo">Molinillo de pimienta/sal</div>
						<div class="stock">Stock: 3.218</div>

													
												</div>
				</a>
								<a class="produto" href="/es/catalogo/utensilios-de-cocina/93899/" title="Caja hermética">
					<div class="fav" data-fav="0" data-prod="529"></div>

											<div class="tag azul">Novedad!</div>
												<div class="img-wrap center">
							<span><img src="/fotos/produtos/93899_05_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">93899</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,684€							</div>
							
						<div class="titulo">Caja hermética</div>
						<div class="stock">Stock: 14.720</div>

													<ul class="colors">
																	<li style="background-color: #ff0000"></li>
																		<li style="background-color: #0046ad"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/utensilios-de-cocina/93967/" title="Set de 4 posavasos">
					<div class="fav" data-fav="0" data-prod="537"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/93967_60_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">93967</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								1,960€							</div>
							
						<div class="titulo">Set de 4 posavasos</div>
						<div class="stock">Stock: 2.613</div>

													
												</div>
				</a>
								<a class="produto" href="/es/catalogo/utensilios-de-cocina/93968/" title="Ensaladera">
					<div class="fav" data-fav="0" data-prod="538"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/93968_60_20595380005895d2ed1ce2d_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">93968</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								3,146€							</div>
							
						<div class="titulo">Ensaladera</div>
						<div class="stock">Stock: 5.145</div>

													
												</div>
				</a>
								<a class="produto" href="/es/catalogo/utensilios-de-cocina/93971/" title="Agitador de coctel">
					<div class="fav" data-fav="0" data-prod="540"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/93971_03_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">93971</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,049€							</div>
							
						<div class="titulo">Agitador de coctel</div>
						<div class="stock">Stock: 37.724</div>

													<ul class="colors">
																	<li style="background-color: #000000"></li>
																		<li style="background-color: #0043a7"></li>
																		<li style="background-color: #ff0000"></li>
																		<li style="background-color: #ffffff"></li>
																		<li style="background-color: #fff200"></li>
																		<li style="background-color: #f7931e"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/utensilios-de-cocina/93973/" title="Mantel individual">
					<div class="fav" data-fav="0" data-prod="541"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/93973_13_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">93973</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,482€							</div>
							
						<div class="titulo">Mantel individual</div>
						<div class="stock">Stock: 16.060</div>

													<ul class="colors">
																	<li style="background-color: #42b1ff"></li>
																		<li style="background-color: #bee017"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/utensilios-de-cocina/93974/" title="Set de 2 posavasos">
					<div class="fav" data-fav="0" data-prod="542"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/93974_13_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">93974</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,212€							</div>
							
						<div class="titulo">Set de 2 posavasos</div>
						<div class="stock">Stock: 8.860</div>

													<ul class="colors">
																	<li style="background-color: #42b1ff"></li>
																		<li style="background-color: #bee017"></li>
																</ul>
												</div>
				</a>
								<a class="produto new" href="/es/catalogo/utensilios-de-cocina/93989/" title="Salero/pimentero">
					<div class="fav" data-fav="0" data-prod="546"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/93989_44_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">93989</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								2,088€							</div>
							
						<div class="titulo">Salero/pimentero</div>
						<div class="stock">Stock: 5.333</div>

													
												</div>
				</a>
								<a class="produto new" href="/es/catalogo/utensilios-de-cocina/99802/" title="Manopla de cocina">
					<div class="fav" data-fav="0" data-prod="891"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/99802_04_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">99802</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,641€							</div>
							
						<div class="titulo">Manopla de cocina</div>
						<div class="stock">Stock: 32.490</div>

													<ul class="colors">
																	<li style="background-color: #0043a7"></li>
																		<li style="background-color: #ff0000"></li>
																		<li style="background-color: #f7931e"></li>
																		<li style="background-color: #bee017"></li>
																</ul>
												</div>
				</a>
					</div>');
    
    //Tablas de Cocina
    consulta_referencias_ps("Tablas de Cocina", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=236&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
    
    //Bandejas y Platos
    consulta_referencias_ps("Bandejas y Platos", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=237&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
    
    //Artículos para té y café
    consulta_referencias_ps("Artículos para té y café", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=239&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
    
    //Utensilios de cocina
    consulta_referencias_ps("Utensilios de cocina", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=244&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
    
    //Barbacoa
    consulta_referencias_ps("Barbacoa", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=245&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
    
    //Bolsas Térmicas
    consulta_referencias_ps("Bolsas Térmicas", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=247&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
    
    //Portamenús
    consulta_referencias_ps("Portamenús", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=249&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
    
    //Monederos
    consulta_referencias_ps("Monederos", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=252&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
    
    //Tarjeteros
    consulta_referencias_ps("Tarjeteros", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=253&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
    
    //Billeteros
    consulta_referencias_ps("Billeteros", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=254&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
    
    //Bolsas Multiusos
    consulta_referencias_ps("Bolsas Multiusos", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=258&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
    
    //Bolsos Móvil
    consulta_referencias_ps("Bolsos Móvil", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=259&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
    
    //Neceseres
    consulta_referencias_ps("Neceseres", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=260&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
    
    //Herramientas
    consulta_referencias_ps("Herramientas", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=262&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
    
    //Navajas
    consulta_referencias_ps("Navajas", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=265&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
    
    //Brújulas
    consulta_referencias_ps("Brújulas", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=267&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
    
    //Linternas
    consulta_referencias_ps("Linternas", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=269&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
    
    //Ratones de Ordenador
    consulta_referencias_ps("Ratones de Ordenador", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=276&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
    
    //Auriculares
    consulta_referencias_ps("Auriculares", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=277&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
    
    //Cargadores y adaptadores
    consulta_referencias_ps("Cargadores y adaptadores", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=278&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
    
    //Mini Altavoces
    consulta_referencias_ps("Mini Altavoces", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=279&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
    
    //Accesorios de Tecnología
    consulta_referencias_ps("Accesorios de Tecnología", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=280&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
    
    //Memorias Flash
    consulta_referencias_ps("Memorias Flash", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=287&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
    
    //Papelería
    consulta_referencias_ps("Papelería", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=297&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
    
    //Calculadoras
    consulta_referencias_ps("Calculadoras", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=302&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
    
    //Soportes
    consulta_referencias_ps("Soportes", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=303&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
    
    //Reglas
    consulta_referencias_ps("Reglas", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=307&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
    
    //Cútters
    consulta_referencias_ps("Cútters", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=308&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
    
    //Pinzas Porta Notas
    consulta_referencias_ps("Pinzas Porta Notas", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=312&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
    
    //Gomas
    consulta_referencias_ps("Gomas", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=316&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
    
    //Notas adesivas
    consulta_referencias_ps("Notas adesivas", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=320&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
    
    //Blocs con Funciones
    consulta_referencias_ps("Blocs con Funciones", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=331&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
    
    //Blocs Ecológicos
    consulta_referencias2_ps("Blocs Ecológicos", '<div id="produtos-wrap" class="list" data-limit-curr="18" data-limit="33" data-order="5">
						<a class="produto" href="/es/catalogo/blocs-ecologicos/93422/" title="Bloc de notas">
					<div class="fav" data-fav="0" data-prod="426"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/93422_60_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">93422</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,412€							</div>
							
						<div class="titulo">Bloc de notas</div>
						<div class="stock">Stock: 11.842</div>

													<ul class="colors">
																	
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/blocs-ecologicos/93427/" title="Bloc de notas">
					<div class="fav" data-fav="0" data-prod="429"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/93427_set_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">93427</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,472€							</div>
							
						<div class="titulo">Bloc de notas</div>
						<div class="stock">Stock: 17.853</div>

													<ul class="colors">
																	<li style="background-color: #0043a7"></li>
																		<li style="background-color: #ff0000"></li>
																		<li style="background-color: #bee017"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/blocs-ecologicos/93429/" title="Bloc de notas">
					<div class="fav" data-fav="0" data-prod="430"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/93429_set_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">93429</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,659€							</div>
							
						<div class="titulo">Bloc de notas</div>
						<div class="stock">Stock: 15.347</div>

													<ul class="colors">
																	<li style="background-color: #0043a7"></li>
																		<li style="background-color: #bee017"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/blocs-ecologicos/93439/" title="Bloc de notas">
					<div class="fav" data-fav="0" data-prod="432"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/93439_60-A_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">93439</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,427€							</div>
							
						<div class="titulo">Bloc de notas</div>
						<div class="stock">Stock: 5.091</div>

													<ul class="colors">
																	
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/blocs-ecologicos/93461/" title="Bloc de notas">
					<div class="fav" data-fav="0" data-prod="436"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/93461_60_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">93461</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,216€							</div>
							
						<div class="titulo">Bloc de notas</div>
						<div class="stock">Stock: 49.369</div>

													<ul class="colors">
																	
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/blocs-ecologicos/93480/" title="Bloc de notas">
					<div class="fav" data-fav="0" data-prod="449"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/93480_set_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">93480</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								2,720€							</div>
							
						<div class="titulo">Bloc de notas</div>
						<div class="stock">Stock: 2.504</div>

													<ul class="colors">
																	
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/blocs-ecologicos/93485/" title="Bloc de notas">
					<div class="fav" data-fav="0" data-prod="453"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/93485_60_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">93485</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								2,751€							</div>
							
						<div class="titulo">Bloc de notas</div>
						<div class="stock">Stock: 7.373</div>

													<ul class="colors">
																	
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/blocs-ecologicos/93486/" title="Bloc de notas">
					<div class="fav" data-fav="0" data-prod="454"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/93486_60_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">93486</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								1,877€							</div>
							
						<div class="titulo">Bloc de notas</div>
						<div class="stock">Stock: 5.405</div>

													<ul class="colors">
																	
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/blocs-ecologicos/93488/" title="Bloc de notas">
					<div class="fav" data-fav="0" data-prod="456"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/93488_set_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">93488</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								1,590€							</div>
							
						<div class="titulo">Bloc de notas</div>
						<div class="stock">Stock: 2.358</div>

													<ul class="colors">
																	
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/blocs-ecologicos/93495/" title="Bloc de notas">
					<div class="fav" data-fav="0" data-prod="458"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/93495_set_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">93495</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,470€							</div>
							
						<div class="titulo">Bloc de notas</div>
						<div class="stock">Stock: 43.307</div>

													<ul class="colors">
																	<li style="background-color: #0043a7"></li>
																		<li style="background-color: #ff0000"></li>
																		<li style="background-color: #f7931e"></li>
																		<li style="background-color: #bee017"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/blocs-ecologicos/93706/" title="Bloc de notas">
					<div class="fav" data-fav="0" data-prod="480"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/93706_03_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">93706</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,546€							</div>
							
						<div class="titulo">Bloc de notas</div>
						<div class="stock">Stock: 9.361</div>

													<ul class="colors">
																	<li style="background-color: #000000"></li>
																		<li style="background-color: #42b1ff"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/blocs-ecologicos/93707/" title="Bloc de notas">
					<div class="fav" data-fav="0" data-prod="481"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/93707_03_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">93707</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,437€							</div>
							
						<div class="titulo">Bloc de notas</div>
						<div class="stock">Stock: 33.166</div>

													<ul class="colors">
																	<li style="background-color: #000000"></li>
																		<li style="background-color: #ff0000"></li>
																		<li style="background-color: #42b1ff"></li>
																		<li style="background-color: #bee017"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/blocs-ecologicos/93708/" title="Bloc de notas">
					<div class="fav" data-fav="0" data-prod="482"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/93708_22_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">93708</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,731€							</div>
							
						<div class="titulo">Bloc de notas</div>
						<div class="stock">Stock: 23.503</div>

													<ul class="colors">
																	<li style="background-color: #000000"></li>
																		<li style="background-color: #ff0000"></li>
																		<li style="background-color: #42b1ff"></li>
																		<li style="background-color: #bee017"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/blocs-ecologicos/93709/" title="Bloc de notas">
					<div class="fav" data-fav="0" data-prod="483"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/93709_set_13492005895d67b3ad1a_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">93709</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,551€							</div>
							
						<div class="titulo">Bloc de notas</div>
						<div class="stock">Stock: 72.476</div>

													<ul class="colors">
																	<li style="background-color: #000000"></li>
																		<li style="background-color: #0043a7"></li>
																		<li style="background-color: #ff0000"></li>
																		<li style="background-color: #f7931e"></li>
																		<li style="background-color: #bee017"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/blocs-ecologicos/93711/" title="Bloc de notas">
					<div class="fav" data-fav="0" data-prod="485"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/93711_05_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">93711</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,397€							</div>
							
						<div class="titulo">Bloc de notas</div>
						<div class="stock">Stock: 23.807</div>

													<ul class="colors">
																	<li style="background-color: #ff0000"></li>
																		<li style="background-color: #42b1ff"></li>
																		<li style="background-color: #bee017"></li>
																</ul>
												</div>
				</a>
								<a class="produto new" href="/es/catalogo/blocs-ecologicos/93715/" title="Bloc de notas">
					<div class="fav" data-fav="0" data-prod="489"></div>

											<div class="tag azul">Novedad!</div>
												<div class="img-wrap center">
							<span><img src="/fotos/produtos/93715_04_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">93715</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,596€							</div>
							
						<div class="titulo">Bloc de notas</div>
						<div class="stock">Stock: 22.721</div>

													<ul class="colors">
																	<li style="background-color: #0043a7"></li>
																		<li style="background-color: #ff0000"></li>
																		<li style="background-color: #41ad00"></li>
																</ul>
												</div>
				</a>
								<a class="produto new" href="/es/catalogo/blocs-ecologicos/93719/" title="Bloc de notas">
					<div class="fav" data-fav="0" data-prod="493"></div>

											<div class="tag azul">Novedad!</div>
												<div class="img-wrap center">
							<span><img src="/fotos/produtos/93719_60_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">93719</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								1,390€							</div>
							
						<div class="titulo">Bloc de notas</div>
						<div class="stock">Stock: 1</div>

													<ul class="colors">
																	
																</ul>
												</div>
				</a>
								<a class="produto new" href="/es/catalogo/blocs-ecologicos/93720/" title="Bloc de notas">
					<div class="fav" data-fav="0" data-prod="494"></div>

											<div class="tag azul">Novedad!</div>
												<div class="img-wrap center">
							<span><img src="/fotos/produtos/93720_60_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">93720</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,820€							</div>
							
						<div class="titulo">Bloc de notas</div>
						<div class="stock">Stock: 5.485</div>

													<ul class="colors">
																	
																</ul>
												</div>
				</a>
					</div>');
    
    //Blocs de Plástico
    consulta_referencias_ps("Blocs de Plástico", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=333&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
    
    //Blocs de Niños
    consulta_referencias_ps("Blocs de Niños", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=334&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
    
    //Blocs
    consulta_referencias_ps("Blocs", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=335&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
    
    //Maletines Multifunciones
    consulta_referencias_ps("Maletines Multifunciones", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=337&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
    
    //Maletines Portadocumentos
    consulta_referencias_ps("Maletines Portadocumentos", "https://www.stricker-europe.com/es/catalogo/index.php?f3%5B%5D=338&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=&order=5");
   
    //Maletines - Portafolios
    consulta_referencias2_ps("Maletines - Portafolios", '
						<a class="produto" href="/es/catalogo/maletines-portafolios/92032/" title="Portafolios A4">
					<div class="fav" data-fav="0" data-prod="192"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/92032_03-A_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">92032</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								5,875€							</div>
							
						<div class="titulo">Portafolios A4</div>
						<div class="stock">Stock: 2.298</div>

													<ul class="colors">
																	<li style="background-color: #000000"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/maletines-portafolios/92038/" title="Portafolios A4">
					<div class="fav" data-fav="0" data-prod="193"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/92038_set_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">92038</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								8,441€							</div>
							
						<div class="titulo">Portafolios A4</div>
						<div class="stock">Stock: 4.472</div>

													<ul class="colors">
																	<li style="background-color: #0043a7"></li>
																		<li style="background-color: #ff0000"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/maletines-portafolios/92040/" title="Portafolios A4">
					<div class="fav" data-fav="0" data-prod="194"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/92040_set_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">92040</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								3,597€							</div>
							
						<div class="titulo">Portafolios A4</div>
						<div class="stock">Stock: 10.499</div>

													<ul class="colors">
																	<li style="background-color: #ff0000"></li>
																		<li style="background-color: #f7931e"></li>
																		<li style="background-color: #42b1ff"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/maletines-portafolios/92041/" title="Portafolios A4">
					<div class="fav" data-fav="0" data-prod="195"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/92041_set_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">92041</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								3,441€							</div>
							
						<div class="titulo">Portafolios A4</div>
						<div class="stock">Stock: 13.192</div>

													<ul class="colors">
																	<li style="background-color: #0043a7"></li>
																		<li style="background-color: #ff0000"></li>
																		<li style="background-color: #41ad00"></li>
																		<li style="background-color: #bdbdbd"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/maletines-portafolios/92044/" title="Portafolios A4">
					<div class="fav" data-fav="0" data-prod="196"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/92044_03-A_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">92044</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								5,790€							</div>
							
						<div class="titulo">Portafolios A4</div>
						<div class="stock">Stock: 6.886</div>

													<ul class="colors">
																	<li style="background-color: #000000"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/maletines-portafolios/92045/" title="Portafolios A4">
					<div class="fav" data-fav="0" data-prod="197"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/92045_03-A_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">92045</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								5,631€							</div>
							
						<div class="titulo">Portafolios A4</div>
						<div class="stock">Stock: 4.400</div>

													<ul class="colors">
																	<li style="background-color: #000000"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/maletines-portafolios/92046/" title="Portafolios A4">
					<div class="fav" data-fav="0" data-prod="198"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/92046_60-A_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">92046</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								1,095€							</div>
							
						<div class="titulo">Portafolios A4</div>
						<div class="stock">Stock: 13.370</div>

													<ul class="colors">
																	
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/maletines-portafolios/92048/" title="Portafolios A4">
					<div class="fav" data-fav="0" data-prod="199"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/92048_set_6662392035895ccdf712bb.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">92048</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								6,994€							</div>
							
						<div class="titulo">Portafolios A4</div>
						<div class="stock">Stock: 2.386</div>

													<ul class="colors">
																	<li style="background-color: #000000"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/maletines-portafolios/92049/" title="Portafolios A4">
					<div class="fav" data-fav="0" data-prod="200"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/92049_04-A_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">92049</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								2,438€							</div>
							
						<div class="titulo">Portafolios A4</div>
						<div class="stock">Stock: 3.146</div>

													<ul class="colors">
																	<li style="background-color: #000000"></li>
																		<li style="background-color: #0043a7"></li>
																		<li style="background-color: #ff0000"></li>
																		<li style="background-color: #919393"></li>
																		<li style="background-color: #bee017"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/maletines-portafolios/92058/" title="Portafolios A4">
					<div class="fav" data-fav="0" data-prod="203"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/92058_03-A_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">92058</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								7,492€							</div>
							
						<div class="titulo">Portafolios A4</div>
						<div class="stock">Stock: 659</div>

													<ul class="colors">
																	<li style="background-color: #000000"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/maletines-portafolios/92059/" title="Portafolios A4">
					<div class="fav" data-fav="0" data-prod="204"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/92059_set_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">92059</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								11,186€							</div>
							
						<div class="titulo">Portafolios A4</div>
						<div class="stock">Stock: 1.331</div>

													<ul class="colors">
																	<li style="background-color: #000000"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/maletines-portafolios/92062/" title="Portafolios A4">
					<div class="fav" data-fav="0" data-prod="206"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/92062_72-A_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">92062</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								3,974€							</div>
							
						<div class="titulo">Portafolios A4</div>
						<div class="stock">Stock: 3.028</div>

													<ul class="colors">
																	<li style="background-color: #0046ad"></li>
																		<li style="background-color: #bee017"></li>
																		<li style="background-color: #bdbdbd"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/maletines-portafolios/92063/" title="Portafolios A4">
					<div class="fav" data-fav="0" data-prod="207"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/92063_set_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">92063</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								10,331€							</div>
							
						<div class="titulo">Portafolios A4</div>
						<div class="stock">Stock: 1.505</div>

													<ul class="colors">
																	<li style="background-color: #bdbdbd"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/maletines-portafolios/92064/" title="Portafolios A4">
					<div class="fav" data-fav="0" data-prod="208"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/92064_43-A_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">92064</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								5,631€							</div>
							
						<div class="titulo">Portafolios A4</div>
						<div class="stock">Stock: 1.308</div>

													<ul class="colors">
																	
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/maletines-portafolios/92066/" title="Portafolios A5">
					<div class="fav" data-fav="0" data-prod="209"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/92066_set_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">92066</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								5,339€							</div>
							
						<div class="titulo">Portafolios A5</div>
						<div class="stock">Stock: 2.075</div>

													<ul class="colors">
																	<li style="background-color: #bdbdbd"></li>
																</ul>
												</div>
				</a>
								<a class="produto new" href="/es/catalogo/maletines-portafolios/92067/" title="Portafolios A4">
					<div class="fav" data-fav="0" data-prod="210"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/92067_set_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">92067</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								6,994€							</div>
							
						<div class="titulo">Portafolios A4</div>
						<div class="stock">Stock: 1.649</div>

													<ul class="colors">
																	<li style="background-color: #bdbdbd"></li>
																</ul>
												</div>
				</a>
								<a class="produto new" href="/es/catalogo/maletines-portafolios/92068/" title="Portafolios A4">
					<div class="fav" data-fav="0" data-prod="211"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/92068_set_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">92068</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								8,639€							</div>
							
						<div class="titulo">Portafolios A4</div>
						<div class="stock">Stock: 2.599</div>

													<ul class="colors">
																	<li style="background-color: #bdbdbd"></li>
																</ul>
												</div>
				</a>
								<a class="produto new" href="/es/catalogo/maletines-portafolios/92069/" title="Portafolios A4">
					<div class="fav" data-fav="0" data-prod="212"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/92069_set_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">92069</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								4,750€							</div>
							
						<div class="titulo">Portafolios A4</div>
						<div class="stock">Stock: 1.291</div>

													<ul class="colors">
																	
																</ul>
												</div>
				</a>
								<a class="produto new" href="/es/catalogo/maletines-portafolios/92071/" title="Portafolios A5">
					<div class="fav" data-fav="0" data-prod="213"></div>

											<div class="tag azul">Novedad!</div>
												<div class="img-wrap center">
							<span><img src="/fotos/produtos/92071_60-A_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">92071</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,792€							</div>
							
						<div class="titulo">Portafolios A5</div>
						<div class="stock">Stock: 4.953</div>

													<ul class="colors">
																	
																</ul>
												</div>
				</a>
								<a class="produto new" href="/es/catalogo/maletines-portafolios/92072/" title="Portafolios A4">
					<div class="fav" data-fav="0" data-prod="214"></div>

											<div class="tag azul">Novedad!</div>
												<div class="img-wrap center">
							<span><img src="/fotos/produtos/92072_05-A_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">92072</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								4,241€							</div>
							
						<div class="titulo">Portafolios A4</div>
						<div class="stock">Stock: 6.586</div>

													<ul class="colors">
																	<li style="background-color: #ff0000"></li>
																		<li style="background-color: #0046ad"></li>
																		<li style="background-color: #bdbdbd"></li>
																</ul>
												</div>
				</a>
								<a class="produto new" href="/es/catalogo/maletines-portafolios/92073/" title="Portafolios A4">
					<div class="fav" data-fav="0" data-prod="215"></div>

											<div class="tag azul">Novedad!</div>
												<div class="img-wrap center">
							<span><img src="/fotos/produtos/92073_72-A_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">92073</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								6,477€							</div>
							
						<div class="titulo">Portafolios A4</div>
						<div class="stock">Stock: 1.633</div>

													<ul class="colors">
																	<li style="background-color: #bdbdbd"></li>
																</ul>
												</div>
				</a>
					');
    
    //Maletines para PC/Tablet
    consulta_referencias2_ps("Maletines para PC/Tablet", '
						<a class="produto" href="/es/catalogo/maletines-para-pc-tablet/92122/" title="Maletín para ordenador">
					<div class="fav" data-fav="0" data-prod="217"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/92122_04_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">92122</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								12,991€							</div>
							
						<div class="titulo">Maletín para ordenador</div>
						<div class="stock">Stock: 231</div>

													<ul class="colors">
																	<li style="background-color: #0043a7"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/maletines-para-pc-tablet/92251/" title="Maletín para ordenador">
					<div class="fav" data-fav="0" data-prod="234"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/92251_03_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">92251</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								5,330€							</div>
							
						<div class="titulo">Maletín para ordenador</div>
						<div class="stock">Stock: 4.781</div>

													<ul class="colors">
																	<li style="background-color: #000000"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/maletines-para-pc-tablet/92258/" title="Maletín para ordenador">
					<div class="fav" data-fav="0" data-prod="236"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/92258_72_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">92258</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								3,496€							</div>
							
						<div class="titulo">Maletín para ordenador</div>
						<div class="stock">Stock: 2.370</div>

													<ul class="colors">
																	<li style="background-color: #bdbdbd"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/maletines-para-pc-tablet/92266/" title="Maletín para ordenador">
					<div class="fav" data-fav="0" data-prod="238"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/92266_07_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">92266</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								5,377€							</div>
							
						<div class="titulo">Maletín para ordenador</div>
						<div class="stock">Stock: 6.767</div>

													<ul class="colors">
																	<li style="background-color: #919393"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/maletines-para-pc-tablet/92271/" title="Maletín para ordenador">
					<div class="fav" data-fav="0" data-prod="242"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/92271_set_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">92271</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								13,997€							</div>
							
						<div class="titulo">Maletín para ordenador</div>
						<div class="stock">Stock: 1.185</div>

													<ul class="colors">
																	<li style="background-color: #000000"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/maletines-para-pc-tablet/92274/" title="Maletín para ordenador">
					<div class="fav" data-fav="0" data-prod="244"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/92274_set_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">92274</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								18,940€							</div>
							
						<div class="titulo">Maletín para ordenador</div>
						<div class="stock">Stock: 250</div>

													<ul class="colors">
																	
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/maletines-para-pc-tablet/92275/" title="Maletín bandolera">
					<div class="fav" data-fav="0" data-prod="245"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/92275_03_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">92275</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								4,691€							</div>
							
						<div class="titulo">Maletín bandolera</div>
						<div class="stock">Stock: 4.240</div>

													<ul class="colors">
																	<li style="background-color: #000000"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/maletines-para-pc-tablet/92279/" title="Maletín para ordenador">
					<div class="fav" data-fav="0" data-prod="249"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/92279_07_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">92279</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								2,944€							</div>
							
						<div class="titulo">Maletín para ordenador</div>
						<div class="stock">Stock: 3.454</div>

													<ul class="colors">
																	<li style="background-color: #919393"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/maletines-para-pc-tablet/92282/" title="Maletín para ordenador">
					<div class="fav" data-fav="0" data-prod="252"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/92282_03_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">92282</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								8,639€							</div>
							
						<div class="titulo">Maletín para ordenador</div>
						<div class="stock">Stock: 1.123</div>

													<ul class="colors">
																	<li style="background-color: #000000"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/maletines-para-pc-tablet/92284/" title="Maletín bandolera">
					<div class="fav" data-fav="0" data-prod="254"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/92284_72_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">92284</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								4,493€							</div>
							
						<div class="titulo">Maletín bandolera</div>
						<div class="stock">Stock: 4.781</div>

													<ul class="colors">
																	<li style="background-color: #bdbdbd"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/maletines-para-pc-tablet/92285/" title="Maletín para ordenador">
					<div class="fav" data-fav="0" data-prod="255"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/92285_set_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">92285</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								3,588€							</div>
							
						<div class="titulo">Maletín para ordenador</div>
						<div class="stock">Stock: 15.059</div>

													<ul class="colors">
																	<li style="background-color: #000000"></li>
																		<li style="background-color: #0046ad"></li>
																		
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/maletines-para-pc-tablet/92286/" title="Maletín para ordenador">
					<div class="fav" data-fav="0" data-prod="256"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/92286_72_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">92286</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								5,490€							</div>
							
						<div class="titulo">Maletín para ordenador</div>
						<div class="stock">Stock: 8.791</div>

													<ul class="colors">
																	<li style="background-color: #bdbdbd"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/maletines-para-pc-tablet/92287/" title="Maletín para ordenador">
					<div class="fav" data-fav="0" data-prod="257"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/92287_03_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">92287</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								7,492€							</div>
							
						<div class="titulo">Maletín para ordenador</div>
						<div class="stock">Stock: 0</div>

													<ul class="colors">
																	<li style="background-color: #000000"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/maletines-para-pc-tablet/92289/" title="Maletín para ordenador">
					<div class="fav" data-fav="0" data-prod="259"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/92289_set_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">92289</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								4,131€							</div>
							
						<div class="titulo">Maletín para ordenador</div>
						<div class="stock">Stock: 3.330</div>

													<ul class="colors">
																	<li style="background-color: #000000"></li>
																		<li style="background-color: #0043a7"></li>
																		<li style="background-color: #e4e2b7"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/maletines-para-pc-tablet/92290/" title="Maletín para ordenador">
					<div class="fav" data-fav="0" data-prod="260"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/92290_03_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">92290</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								3,892€							</div>
							
						<div class="titulo">Maletín para ordenador</div>
						<div class="stock">Stock: 3.089</div>

													<ul class="colors">
																	<li style="background-color: #000000"></li>
																</ul>
												</div>
				</a>
								<a class="produto new" href="/es/catalogo/maletines-para-pc-tablet/92130/" title="Maletín para ordenador">
					<div class="fav" data-fav="0" data-prod="8154"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/92130_03_1993603102593be68ae3fe4.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">92130</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								15,950€							</div>
							
						<div class="titulo">Maletín para ordenador</div>
						<div class="stock">Stock: 37</div>

													<ul class="colors">
																	<li style="background-color: #000000"></li>
																</ul>
												</div>
				</a>
								<a class="produto new" href="/es/catalogo/maletines-para-pc-tablet/92265/" title="Maletín para ordenador">
					<div class="fav" data-fav="0" data-prod="8159"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/92265_03_1252815833593be7599c4c1.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">92265</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								2,650€							</div>
							
						<div class="titulo">Maletín para ordenador</div>
						<div class="stock">Stock: 123</div>

													<ul class="colors">
																	<li style="background-color: #000000"></li>
																</ul>
												</div>
				</a>
								<a class="produto new" href="/es/catalogo/maletines-para-pc-tablet/92267/" title="Maletín bandolera para ordenador">
					<div class="fav" data-fav="0" data-prod="8160"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/92267_04_1316982755593be7894c6cd.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">92267</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								3,290€							</div>
							
						<div class="titulo">Maletín bandolera para ordenador</div>
						<div class="stock">Stock: 486</div>

													<ul class="colors">
																	<li style="background-color: #0043a7"></li>
																</ul>
												</div>
				</a>
								<a class="produto new" href="/es/catalogo/maletines-para-pc-tablet/92298/" title="Maletín para ordenador">
					<div class="fav" data-fav="0" data-prod="8161"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/92298_04_152075631593be7a9ee450.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">92298</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								3,290€							</div>
							
						<div class="titulo">Maletín para ordenador</div>
						<div class="stock">Stock: 1.321</div>

													<ul class="colors">
																	<li style="background-color: #0043a7"></li>
																</ul>
												</div>
				</a>
								<a class="produto new" href="/es/catalogo/maletines-para-pc-tablet/92299/" title="Maletín bandolera para ordenador">
					<div class="fav" data-fav="0" data-prod="8162"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/92299_04_161417721593be7cbf1dba.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">92299</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								3,150€							</div>
							
						<div class="titulo">Maletín bandolera para ordenador</div>
						<div class="stock">Stock: 71</div>

													<ul class="colors">
																	<li style="background-color: #0043a7"></li>
																</ul>
												</div>
				</a>
					');
    
    //Bolsas para PC/Tablet
    consulta_referencias_ps("Bolsas para PC/Tablet", "https://www.stricker-europe.com/es/catalogo/?f3%5B%5D=344&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=");
    
    //Maletines Portadocumentos
    consulta_referencias_ps("Maletines Portadocumentos", "https://www.stricker-europe.com/es/catalogo/?f3%5B%5D=345&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=");
    
    //Seguridad
    consulta_referencias_ps("Seguridad", "https://www.stricker-europe.com/es/catalogo/?f3%5B%5D=347&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=");
    
    //Accesorios Reflectantes
    consulta_referencias_ps("Accesorios Reflectantes", "https://www.stricker-europe.com/es/catalogo/?f3%5B%5D=348&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=");
    
    //Chalecos Reflectantes
    consulta_referencias_ps("Chalecos Reflectantes", "https://www.stricker-europe.com/es/catalogo/?f3%5B%5D=349&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=");
    
    //Kits Primeros Auxilios
    consulta_referencias_ps("Kits Primeros Auxilios", "https://www.stricker-europe.com/es/catalogo/?f3%5B%5D=350&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=");
    
    //Luces de Emergencia
    consulta_referencias_ps("Luces de Emergencia", "https://www.stricker-europe.com/es/catalogo/?f3%5B%5D=351&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=");
    
    //Bolsas de Compras Algodón
    consulta_referencias_ps("Bolsas de Compras Algodón", "https://www.stricker-europe.com/es/catalogo/?f3%5B%5D=354&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=");
    
    //Bolsas de Compras Non-Woven
    consulta_referencias_ps("Bolsas de Compras Non-Woven", "https://www.stricker-europe.com/es/catalogo/?f3%5B%5D=362&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=");
    
    //Bolsas de Compras Non-Woven - Plegables
    consulta_referencias_ps("Bolsas de Compras Non-Woven - Plegables", "https://www.stricker-europe.com/es/catalogo/?f3%5B%5D=364&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=");
    
    //Bolsas de Compras Non-Woven - Otras
    consulta_referencias_ps("Bolsas de Compras Non-Woven - Otras", "https://www.stricker-europe.com/es/catalogo/?f3%5B%5D=365&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=");
    
    //Bolsas de Compras Poliéster - Plegables
    consulta_referencias_ps("Bolsas de Compras Poliéster - Plegables", "https://www.stricker-europe.com/es/catalogo/?f3%5B%5D=368&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=");
    
    //Maletas y Bolsas
    consulta_referencias_ps("Maletas y Bolsas", "https://www.stricker-europe.com/es/catalogo/?f3%5B%5D=371&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=");
    
    //Trolleys
    consulta_referencias_ps("Trolleys", "https://www.stricker-europe.com/es/catalogo/?f3%5B%5D=372&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=");
    
    //Neceseres
    consulta_referencias_ps("Neceseres", "https://www.stricker-europe.com/es/catalogo/?f3%5B%5D=375&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=");
    
    //Mochilas
    consulta_referencias_ps("Mochilas", "https://www.stricker-europe.com/es/catalogo/?f3%5B%5D=376&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=");
    
    //Bolsas Tipo Mochila
    consulta_referencias_ps("Bolsas Tipo Mochila", "https://www.stricker-europe.com/es/catalogo/?f3%5B%5D=379&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=");
    
    //Bolsas de Deporte
    consulta_referencias_ps("Bolsas de Deporte", "https://www.stricker-europe.com/es/catalogo/?f3%5B%5D=381&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=");
    
    //Bolsas - Otros
    consulta_referencias_ps("Bolsas - Otros", "https://www.stricker-europe.com/es/catalogo/?f3%5B%5D=382&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=");
    
    //Inflables
    consulta_referencias_ps("Inflables", "https://www.stricker-europe.com/es/catalogo/?f3%5B%5D=383&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=");
    
    //Artículos para Mascotas
    consulta_referencias_ps("Artículos para Mascotas", "https://www.stricker-europe.com/es/catalogo/?f3%5B%5D=387&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=");
    
    //Utensilios de Viaje
    consulta_referencias2_ps("Utensilios de Viaje", '
						<a class="produto" href="/es/catalogo/utensilios-de-viaje/92851/" title="Funda para zapatos">
					<div class="fav" data-fav="0" data-prod="340"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/92851_03_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">92851</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,294€							</div>
							
						<div class="titulo">Funda para zapatos</div>
						<div class="stock">Stock: 10.193</div>

													<ul class="colors">
																	<li style="background-color: #000000"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/utensilios-de-viaje/94855/" title="Cepillo de dientes">
					<div class="fav" data-fav="0" data-prod="653"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/94855_04_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">94855</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,162€							</div>
							
						<div class="titulo">Cepillo de dientes</div>
						<div class="stock">Stock: 17.610</div>

													<ul class="colors">
																	<li style="background-color: #0043a7"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/utensilios-de-viaje/98108/" title="Calzador">
					<div class="fav" data-fav="0" data-prod="753"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/98108_03_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">98108</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,112€							</div>
							
						<div class="titulo">Calzador</div>
						<div class="stock">Stock: 25.019</div>

													<ul class="colors">
																	<li style="background-color: #000000"></li>
																		<li style="background-color: #0043a7"></li>
																		<li style="background-color: #ff0000"></li>
																		<li style="background-color: #ffffff"></li>
																		<li style="background-color: #f7931e"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/utensilios-de-viaje/98109/" title="Candado">
					<div class="fav" data-fav="0" data-prod="754"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/98109_44_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">98109</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,434€							</div>
							
						<div class="titulo">Candado</div>
						<div class="stock">Stock: 3.508</div>

													<ul class="colors">
																	
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/utensilios-de-viaje/98111/" title="Identificador">
					<div class="fav" data-fav="0" data-prod="755"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/98111_44_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">98111</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,522€							</div>
							
						<div class="titulo">Identificador</div>
						<div class="stock">Stock: 9.151</div>

													<ul class="colors">
																	
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/utensilios-de-viaje/98112/" title="Identificador">
					<div class="fav" data-fav="0" data-prod="756"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/98112_set_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">98112</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,155€							</div>
							
						<div class="titulo">Identificador</div>
						<div class="stock">Stock: 37.294</div>

													<ul class="colors">
																	<li style="background-color: #000000"></li>
																		<li style="background-color: #ff0000"></li>
																		<li style="background-color: #ffffff"></li>
																		<li style="background-color: #f7931e"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/utensilios-de-viaje/98113/" title="Antifaz para dormir">
					<div class="fav" data-fav="0" data-prod="757"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/98113_03_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">98113</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,191€							</div>
							
						<div class="titulo">Antifaz para dormir</div>
						<div class="stock">Stock: 5.676</div>

													<ul class="colors">
																	<li style="background-color: #000000"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/utensilios-de-viaje/98114/" title="Tapones para los oídos">
					<div class="fav" data-fav="0" data-prod="758"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/98114_08_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">98114</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,155€							</div>
							
						<div class="titulo">Tapones para los oídos</div>
						<div class="stock">Stock: 22.828</div>

													<ul class="colors">
																	<li style="background-color: #fff200"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/utensilios-de-viaje/98117/" title="Set limpieza calzado">
					<div class="fav" data-fav="0" data-prod="759"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/98117_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">98117</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								1,375€							</div>
							
						<div class="titulo">Set limpieza calzado</div>
						<div class="stock">Stock: 10.904</div>

													<ul class="colors">
																	<li style="background-color: "></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/utensilios-de-viaje/98119/" title="Costurero">
					<div class="fav" data-fav="0" data-prod="760"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/98119_04_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">98119</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,232€							</div>
							
						<div class="titulo">Costurero</div>
						<div class="stock">Stock: 21.003</div>

													<ul class="colors">
																	<li style="background-color: #0043a7"></li>
																		<li style="background-color: #ff0000"></li>
																		<li style="background-color: #fff200"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/utensilios-de-viaje/98123/" title="Identificador">
					<div class="fav" data-fav="0" data-prod="763"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/98123_03_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">98123</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,267€							</div>
							
						<div class="titulo">Identificador</div>
						<div class="stock">Stock: 13.342</div>

													<ul class="colors">
																	<li style="background-color: #000000"></li>
																		<li style="background-color: #0043a7"></li>
																		<li style="background-color: #ff0000"></li>
																		<li style="background-color: #bee017"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/utensilios-de-viaje/98124/" title="Identificador">
					<div class="fav" data-fav="0" data-prod="764"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/98124_05_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">98124</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,137€							</div>
							
						<div class="titulo">Identificador</div>
						<div class="stock">Stock: 106.089</div>

													<ul class="colors">
																	<li style="background-color: #ff0000"></li>
																		<li style="background-color: #ffffff"></li>
																		<li style="background-color: #f7931e"></li>
																		<li style="background-color: #0046ad"></li>
																		<li style="background-color: #bee017"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/utensilios-de-viaje/98171/" title="Porta trajes">
					<div class="fav" data-fav="0" data-prod="765"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/98171_03_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">98171</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								1,242€							</div>
							
						<div class="titulo">Porta trajes</div>
						<div class="stock">Stock: 2.636</div>

													<ul class="colors">
																	<li style="background-color: #000000"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/utensilios-de-viaje/98180/" title="Almohada de viaje">
					<div class="fav" data-fav="0" data-prod="766"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/98180_07-pouch_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">98180</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								0,622€							</div>
							
						<div class="titulo">Almohada de viaje</div>
						<div class="stock">Stock: 12.726</div>

													<ul class="colors">
																	<li style="background-color: #919393"></li>
																</ul>
												</div>
				</a>
								<a class="produto" href="/es/catalogo/utensilios-de-viaje/98196/" title="Porta trajes">
					<div class="fav" data-fav="0" data-prod="771"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/98196_03_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">98196</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								1,076€							</div>
							
						<div class="titulo">Porta trajes</div>
						<div class="stock">Stock: 9.527</div>

													<ul class="colors">
																	<li style="background-color: #000000"></li>
																</ul>
												</div>
				</a>
								<a class="produto new" href="/es/catalogo/utensilios-de-viaje/98197/" title="Set de viaje">
					<div class="fav" data-fav="0" data-prod="772"></div>

											<div class="img-wrap center">
							<span><img src="/fotos/produtos/98197_03_2.jpg" alt=""></span>
						</div>
						
					<div class="bottom">
						<div class="ref">98197</div>

													<div class="from-wrap">
								<span class="from">Desde</span>
								1,831€							</div>
							
						<div class="titulo">Set de viaje</div>
						<div class="stock">Stock: 5.513</div>

													<ul class="colors">
																	<li style="background-color: #000000"></li>
																</ul>
												</div>
				</a>
					');
    
    //Bolsas
    consulta_referencias_ps("Bolsas", "https://www.stricker-europe.com/es/catalogo/?f3%5B%5D=390&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=");
    
    //Bolsas Portadocumentos
    consulta_referencias_ps("Bolsas Portadocumentos", "https://www.stricker-europe.com/es/catalogo/?f3%5B%5D=391&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=");
    
    //Bolsas de Cockpit
    consulta_referencias_ps("Bolsas de Cockpit", "https://www.stricker-europe.com/es/catalogo/?f3%5B%5D=392&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=");
    
    //Sombrilla
    consulta_referencias_ps("Sombrilla", "https://www.stricker-europe.com/es/catalogo/?f3%5B%5D=393&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=");
    
    //Accesorios Automóvil
    consulta_referencias_ps("Accesorios Automóvil", "https://www.stricker-europe.com/es/catalogo/?f3%5B%5D=389&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=");
    
    //Pilas
    consulta_referencias_ps("Pilas", "https://www.stricker-europe.com/es/catalogo/?f3%5B%5D=401&f5%5B0%5D=&f5%5B1%5D=&f7%5B0%5D=&f7%5B1%5D=0&f7%5B2%5D=");
    
    */    

//FIN de prueba - BORRAR

?>