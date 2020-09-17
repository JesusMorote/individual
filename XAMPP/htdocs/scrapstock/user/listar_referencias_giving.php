<?php

if(!isset($_SESSION['user'])) { //Se intenta acceder a la aplicación sin un login correcto
    //Muestro un mensaje de advertencia y redirijo al usuario a la página de acceso    
    ?>
    <script>
        alert ("Para Acceder a la Aplicación introduzca sus Credenciales de Acceso.");
        location.replace("../index.php");
    </script>
    <?php
} else {
    
    function url_exists( $url = NULL ) {
        if( empty( $url ) ){
            return false;
        }
        $options['http'] = array(
            'method' => "HEAD",
            'ignore_errors' => 1,
            'max_redirects' => 0
        );
        $body = @file_get_contents( $url, NULL, stream_context_create( $options ) );

        // Ver http://php.net/manual/es/reserved.variables.httpresponseheader.php
        if( isset( $http_response_header ) ) {
            sscanf( $http_response_header[0], 'HTTP/%*d.%*d %d', $httpcode );

            //Aceptar solo respuesta 200 (Ok), 301 (redirección permanente) o 302 (redirección temporal)
            $accepted_response = array( 200, 301, 302 );
            if( in_array( $httpcode, $accepted_response ) ) {
                return true;
            } else {
                return false;
            }
         } else {
             return false;
         }
    }
    
    
    $pag = 1;
    
    if(isset($_GET['pag'])) {
        $pag = $_GET['pag'];
    }
    
    
    if (isset($_SESSION['ref_por_pag'])) {
        $ref_por_pag = $_SESSION['ref_por_pag'];
    } else {
        $ref_por_pag = 100;
    }
    
    
    if(isset($_POST['num_ref_por_pag'])) {
        $ref_por_pag = $_POST['num_ref_por_pag'];
        
        $_SESSION['ref_por_pag'] = $ref_por_pag;
        
        $pag = 1;
    }
    
    if (isset($_SESSION['filtro_giving'])) {
        $filtro = $_SESSION['filtro_giving'];
    } else {
        $filtro = "todos";
    }
    
    
    if(isset($_POST['filtro'])) {
        $filtro = $_POST['filtro'];
        
        $_SESSION['filtro_giving'] = $filtro;
        
        $pag = 1;
    }
    
    
    $ref_inicio = (($pag-1) * $ref_por_pag) + 1;
    $ref_fin = $ref_inicio + ($ref_por_pag -1);
    
    $offset = (($pag-1) * $ref_por_pag);
    
    if ($filtro == "todos") {
         $consulta_referencias = "SELECT DISTINCT refs_giving_referencia
                                    FROM refs_giving
                                    ORDER BY refs_giving_referencia ASC
                                    LIMIT $ref_por_pag OFFSET $offset;";
        
        $consulta_referencias_prev = "SELECT DISTINCT refs_giving_referencia
                                        FROM refs_giving;";

        $resultado_consulta_referencias_prev = $bd->Execute($consulta_referencias_prev);

        $num_referencias = 0;

        $resultado_consulta_referencias_prev->moveFirst();

        while (!($resultado_consulta_referencias_prev->EOF)) {
            $num_referencias++;
            $resultado_consulta_referencias_prev->MoveNext();
        }
    } else {
        if(substr($filtro, 0, 2) == "ZZ") { //se ha seleccionado una subcategoría
            $subcategoria = substr($filtro, 2);
            
            $consulta_referencias = "SELECT DISTINCT refs_giving_referencia
                                    FROM refs_giving
                                    WHERE refs_giving_subcategoria LIKE '$subcategoria'
                                    ORDER BY refs_giving_referencia ASC
                                    LIMIT $ref_por_pag OFFSET $offset;";
            
            $consulta_referencias_prev = "SELECT DISTINCT refs_giving_referencia
                                            FROM refs_giving
                                            WHERE refs_giving_subcategoria LIKE '$subcategoria';";

            $resultado_consulta_referencias_prev = $bd->Execute($consulta_referencias_prev);

            $num_referencias = 0;

            $resultado_consulta_referencias_prev->moveFirst();

            while (!($resultado_consulta_referencias_prev->EOF)) {
                $num_referencias++;
                $resultado_consulta_referencias_prev->MoveNext();
            }
        } else { //se ha seleccionado una categoría
            $categoria = $filtro;
            
            $consulta_referencias = "SELECT DISTINCT refs_giving_referencia
                                    FROM refs_giving
                                    WHERE refs_giving_categoria LIKE '$categoria'
                                    ORDER BY refs_giving_referencia ASC
                                    LIMIT $ref_por_pag OFFSET $offset;";
            
            $consulta_referencias_prev = "SELECT DISTINCT refs_giving_referencia
                                            FROM refs_giving
                                            WHERE refs_giving_categoria LIKE '$categoria';";

            $resultado_consulta_referencias_prev = $bd->Execute($consulta_referencias_prev);

            $num_referencias = 0;

            $resultado_consulta_referencias_prev->moveFirst();

            while (!($resultado_consulta_referencias_prev->EOF)) {
                $num_referencias++;
                $resultado_consulta_referencias_prev->MoveNext();
            }
        }
    }
    
    $resultado_consulta_referencias = $bd->Execute($consulta_referencias);
    
    $num_paginas = ceil($num_referencias / $ref_por_pag);
    
    ?>
    
    <div class="container-fluid">
        <!--Cabecera de Elección de Número de Referencias a mostrar por página--> 
        <div class="row">
        
            <h4 class="enmarcado2 indentado text-center">Listado de Referencias de GIVING</h4>
           
            <div class="col-md-3 text-center margin_inf">
                <strong>Mostrando:</strong> <span class="resultado"><?php echo $ref_por_pag; ?> refs/pag.</span>                
            </div>
            <div class="col-md-4 text-center margin_inf">
                <form name="selector" method="post" action="">
                <div class="input-group">
                    <span class="input-group-addon" id="basic-addon1">Nº de Ref./Pág.</span>
                    <input type="number" min="25" step="25" max"200" class="form-control" placeholder="25-200 (múltiplos de 25)" aria-describedby="basic-addon1" name="num_ref_por_pag" id="num_ref_por_pag" required>
                </div>
            </div>
            <div class="col-md-2 margin_inf">
                <button type="submit" class="btn btn-primary btn-block">
                    <span class="glyphicon glyphicon-th" aria-hidden="true"></span> Actualizar Listado
                </button>
                </form>
            </div>
            <div class="col-md-3 text-center margin_inf">
                <strong>Desde ref:</strong> <span class="resultado" id="ref_desde"></span> <strong>Hasta ref:</strong> <span class="resultado" id="ref_hasta"></span>              
            </div>            
        </div>
        <!--FIN de Cabecera de Elección de Número de Referencias a mostrar por página-->
        
        <!--Cabecera de Filtrado de Referencias a mostrar--> 
        <div class="row">
            <div class="col-md-4 col-md-offset-3 text-center margin_inf">
                <form class="form-horizontal" name="filtrado" method="post" action="">
                <div class="form-group">                
                    <select class="form-control" name="filtro" id="filtro">
                        <option value="todos" id="todos">Todas las Categorías</option>
                        <option disabled>─────────────────────────</option>
                        <?php
                        
                        $consulta_categorias = "SELECT DISTINCT refs_giving_categoria
                                                    FROM refs_giving
                                                    ORDER BY refs_giving_categoria ASC";
    
                        $resultado_consulta_categorias = $bd->Execute($consulta_categorias);
                        
                        while (!($resultado_consulta_categorias->EOF)) {
                            $nombre_categoria = $resultado_consulta_categorias->fields['refs_giving_categoria'];
                            
                            ?>
                            <option value="<?php echo $nombre_categoria; ?>" id="<?php echo preg_replace('[&]', "", preg_replace('[,]', "", preg_replace('[ñ]', "", preg_replace('[/]', "", preg_replace('[\s+]', "", $nombre_categoria))))); ?>" class="capitalize categoria"><?php echo $nombre_categoria; ?></option>
                            <?php
                            
                            $consulta_subcategorias = "SELECT DISTINCT refs_giving_subcategoria
                                                        FROM refs_giving
                                                        WHERE refs_giving_categoria LIKE '$nombre_categoria'
                                                        ORDER BY refs_giving_subcategoria ASC";

                            $resultado_consulta_subcategorias = $bd->Execute($consulta_subcategorias);
                            
                            while (!($resultado_consulta_subcategorias->EOF)) {
                                $nombre_subcategoria = $resultado_consulta_subcategorias->fields['refs_giving_subcategoria'];
                                
                                ?>
                                <option value="<?php echo "ZZ".$nombre_subcategoria; ?>" id="<?php echo "ZZ".preg_replace('[&]', "", preg_replace('[,]', "", preg_replace('[ñ]', "", preg_replace('[/]', "", preg_replace('[\s+]', "", $nombre_subcategoria))))); ?>" class="capitalize"> - <?php echo $nombre_subcategoria; ?></option>
                                <?php
                                
                                $resultado_consulta_subcategorias->MoveNext();
                            }
                            
                            ?>
                            <option disabled>─────────────────────────</option>
                            <?php
                            $resultado_consulta_categorias->MoveNext();
                        }
                        
    
                        ?>
                    </select>
                </div>
            </div>
            <div class="col-md-2 margin_inf">
                <button type="submit" class="btn btn-primary btn-block">
                    <span class="glyphicon glyphicon-filter" aria-hidden="true"></span> Filtrar Listado
                </button>
                </form>
            </div> 
            <div class="col-md-3 text-center margin_inf">
                <strong>TOTAL ref. filtradas:</strong> <span class="resultado" id="total_ref"></span>                
            </div>          
        </div>
        <!--FIN de Cabecera de Filtrado de Referencias a mostrar-->
        
        <!--Fila que muestra la página del listado actual y los botones para adelantar o retroceder-->
        <div class="row">
            <nav>
                <ul class="pager linea_nav">
                    <?php
                    if($pag == 1) {
                        ?>
                        <li class="disabled">
                            <a href="#">
                                <span aria-hidden="true">&laquo;</span> Pág. Anterior
                            </a>
                        </li>
                        <?php
                    } else {
                        ?>
                        <li>
                            <a href="inicio.php?id=listar_referencias_giving&pag=<?php echo ($pag-1); ?>">
                                <span aria-hidden="true">&laquo;</span> Pág. Anterior
                            </a>
                        </li>
                        <?php
                    }
                    ?>
                    <li id="pag_actual"><strong>Página:</strong> <?php echo $pag; ?> de <?php echo $num_paginas; ?></li>
                    <?php
                    if($pag == $num_paginas) {
                        ?>
                        <li class="disabled">
                            <a href="#" aria-label="Next">
                                Pág. Siguiente <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                        <?php
                    } else {
                        ?>
                        <li>
                            <a href="inicio.php?id=listar_referencias_giving&pag=<?php echo ($pag+1); ?>" aria-label="Previous">
                                Pág. Siguiente <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                        <?php
                    }
                    ?>
                </ul>
            </nav>
        </div>
        <!--FIN de Fila que muestra la página del listado actual y los botones para adelantar o retroceder-->
        
        <hr>
        
        <!--Barra de Progreso de la carga de referencias a mostrar-->  
        <!-### SCRIPTS JS Y jQuery ###->
        <script src="../js/jquery.js"></script>

        <script>
            num_referencias = <?php echo $ref_por_pag; ?>;
            ref_actual = 0;
        </script>
        
        <div class="row" id="barra_prog">
            <div class="col-md-12">
                <h5 class="text-center" id="aviso"><em>Cargando referencias...</em></h5>
                <div class="progress">
                  <div id="progreso" class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%; min-width: 6em;">
                    <span class="sr-only"><span class="porc_completo">0</span>% Completo</span>
                    <span class="porc_completo">0</span>% Completo
                  </div>
                </div>
                <hr>
            </div>
        </div>
        <!--FIN de Barra de Progreso de la carga de referencias a mostrar-->   
        
        <!--Tabla de resultados a mostrar-->
        <div class="row" id="resultados" style="display: none;">
            <div class="col-md-12">
                
                <table class="table table-bordered table-condensed table-striped">
                    <tr id="cabecera_listado">
                        <th class="info celdacentradahor celdacentradavert">Imagen</th>
                        <th class="info celdacentradahor celdacentradavert nombre_makito">Nombre</th>
                        <th class="info celdacentradahor celdacentradavert">Categoría</th>
                        <th class="info celdacentradahor celdacentradavert">Subcategoría</th>
                        <th class="info celdacentradahor celdacentradavert"><i class="glyphicon glyphicon-barcode"></i> Ref. GIVING</th>
                        <th class="info celdacentradahor celdacentradavert">Variación</th>
                    </tr>
                    
                    <tr id="cabecera_top">
                        <!--El contenido de esta cabecera lo creo dinámicamente con jQuery al cargar la página-->
                    </tr>
               
                <?php
    
                $resultado_consulta_referencias->MoveFirst();
    
                while (!($resultado_consulta_referencias->EOF)) {
                    $articulo_ref = $resultado_consulta_referencias->fields['refs_giving_referencia'];
                    
                    $consulta_datos_referencia = "SELECT * FROM refs_giving
                                                    WHERE refs_giving_referencia LIKE '$articulo_ref'
                                                    LIMIT 1;";
                    $resultado_consulta_datos_referencia = $bd->Execute($consulta_datos_referencia);
                    
                    $articulo_url_imagen = $resultado_consulta_datos_referencia->fields['refs_giving_url_imagen'];
                    
                    $articulo_nombre = $resultado_consulta_datos_referencia->fields['refs_giving_nombre'];$articulo_categoria = $resultado_consulta_datos_referencia->fields['refs_giving_categoria'];
                    $articulo_subcategoria = $resultado_consulta_datos_referencia->fields['refs_giving_subcategoria'];
                    
                    ?>
                    
                    <tr>
                        <td class="celdacentradahor celdacentradavert">
                            <?php
                            $urlexists = url_exists($articulo_url_imagen);
                    
                            //if(@fopen('http://www.makito.es/imagenes/0-7999/'.$articulo_ref.'.jpg',"r")==true) {
                            if($urlexists) {
                                ?>
                                <div class="enmarcado3 miniatura contenedor_miniatura">
                                   <div class="contenido_td">
                                       <img class="miniatura_block" src="<?php echo $articulo_url_imagen; ?>">
                                   </div>
                                </div>
                                <?php
                            } else {
                                ?>
                                <img src="img/varios/noimg.jpg" width="50px" class="enmarcado3 miniatura">
                                <?php
                            }
                            ?>  
                        </td>
                        <td class="celdacentradahor celdacentradavert nombre_makito">
                            <span><strong><?php echo $articulo_nombre; ?></strong></span>
                        </td>
                        <td class="celdacentradahor celdacentradavert capitalize">
                            <span><?php echo $articulo_categoria; ?></span>
                        </td>
                        <td class="celdacentradahor celdacentradavert capitalize">
                            <span><?php echo $articulo_subcategoria; ?></span>
                        </td>
                        <td class="celdacentradahor celdacentradavert mas_info celda_homogenea">
                            <span class="articulo_ref"><?php echo $articulo_ref; ?></span>
                            <?php
                            if($articulo_ref != "") {
                                ?>
                                <br>
                                <a href="../inc/muestra_web_competidor.inc.php?ref=<?php echo $articulo_ref; ?>&comp=cifra" target="_blank">
                                    <button type="button" class="btn btn-muted btn-xs contorneado"><span class="glyphicon glyphicon-link" aria-hidden="true"></span></button>
                                </a>
                                <?php
                            }
                            ?>
                        </td>
                        <td class="celdacentradahor celdacentradavert">
                            <a href="inicio.php?id=variacion_stock_cifra&ref=<?php echo $articulo_ref; ?>" target="_blank">
                                <button type="button" class="btn btn-info btn-sm"><span class="glyphicon glyphicon-stats" aria-hidden="true"></span></button>
                            </a>  
                        </td>
                    </tr>
                    
                    <script>
                        ref_actual++;
                        //alert(ref_actual);
                        var porcentaje = Math.round((ref_actual/num_referencias)*100);
                        
                        $("#progreso").attr("aria-valuenow", porcentaje);
                        var ancho_porc = porcentaje+"%";
                        $("#progreso").css("width", ancho_porc);
                        $(".porc_completo").text(porcentaje);
                        
                    </script>
                    
                    <?php

                    $resultado_consulta_referencias->MoveNext();
                }
    
                ?>
                
                </table>
            
            </div>
            <!--FIN de Listado de Items-->
    
        </div>
        
        <hr>
        
        <!--Botones de Navegación - Paginación-->
        <div class="row" id="paginacion" style="display: none;">
            <nav aria-label="Page navigation" class="text-center">
                <ul class="pagination linea_nav2">
                    
                    <?php
                    if($pag == 1) {
                        ?>
                        <li class="disabled">
                            <a href="#" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                        <?php
                    } else {
                        ?>
                        <li>
                            <a href="inicio.php?id=listar_referencias_giving&pag=<?php echo ($pag-1); ?>" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                        <?php
                    }
                    ?>
                    
                    
                    <?php
                    for($i=1; $i<= $num_paginas; $i++) {
                        
                        if($i == $pag) {
                            ?>
                            <li class="active"><a href="inicio.php?id=listar_referencias_giving&pag=<?php echo $i; ?>" class="btn_pg_<?php echo $i; ?>"><?php echo $i; ?></a></li>
                            <?php
                        } else {
                            ?>
                            <li><a href="inicio.php?id=listar_referencias_giving&pag=<?php echo $i; ?>" class="btn_pg_<?php echo $i; ?>"><?php echo $i; ?></a></li>
                            <?php
                        }
                        
                        
                    }
                    ?>
                    
                    <?php
                    if($pag == $num_paginas) {
                        ?>
                        <li class="disabled">
                            <a href="#" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                        <?php
                    } else {
                        ?>
                        <li>
                            <a href="inicio.php?id=listar_referencias_giving&pag=<?php echo ($pag+1); ?>" aria-label="Previous">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                        <?php
                    }
                    ?>
                    
                </ul>
            </nav>
        </div>
        <!--FIN de Botones de Navegación - Paginación-->        
    </div>
    
    <!-### SCRIPTS JS Y jQuery ###->
    <script>
        
        $(document).ready(function() {
            
            $(".no_link").click(function(event) {
                event.preventDefault();
            });
            
            var primera_ref = $(".articulo_ref").first();
            $("#ref_desde").text(primera_ref.text());
            
            var ultima_ref = $(".articulo_ref").last();
            $("#ref_hasta").text(ultima_ref.text());
            
            var total_ref = <?php echo $num_referencias; ?>;
            $("#total_ref").text(total_ref);
            
            $("#<?php echo preg_replace('[&]', "", preg_replace('[,]', "", preg_replace('[ñ]', "", preg_replace('[/]', "", preg_replace('[\s+]', "",$filtro))))); ?>").attr("selected", "selected");
            $("#<?php echo preg_replace('[&]', "", preg_replace('[,]', "",preg_replace('[ñ]', "", preg_replace('[/]', "", preg_replace('[\s+]', "",$filtro))))); ?>").css("background-color", "#337ab7");
            $("#<?php echo preg_replace('[&]', "", preg_replace('[,]', "",preg_replace('[ñ]', "", preg_replace('[/]', "", preg_replace('[\s+]', "",$filtro))))); ?>").css("color", "white");
            
            
            //Script para ampliar la imagen de cada artículo, al pasar el ratón sobre ella
            $(".miniatura").hover(function(){
                $(this).css("transform", "scale(3)");
                $(this).css("z-index", "10");
                $(this).css("cursor", "no-drop");
            }, function(){
                $(this).css("transform", "scale(1)");
                $(this).css("z-index", "1");
                $(this).css("cursor", "default");
            });            
            //FIN del Script para ampliar la imagen de cada artículo, al pasar el ratón sobre ella
            
            
            //Script para mostrar info sobre las referencias extra o a excluir en relación a la referencia de Makito
            var referencias_a_extender = $(".mas_info");
            
            referencias_a_extender.each(function() {
                if(($(this).find("span").text() != "") && (($(this).next().find("span").text() != "") || ($(this).next().next().find("span").text() != ""))){
                    $(this).css("text-decoration", "underline");
                    $(this).css("background-color", "rgba(255, 168, 0, 0.15)");
                    var texto_ref = $(this).find("span").text();
                    $(this).find("span").first().append(" <span class='glyphicon glyphicon-list-alt' aria-hidden='true'></span>");
                    
                    $(this).find("span").first().attr("data-toggle", "popover");
                    $(this).find("span").first().attr("title", "Ref. Extra/Excluir");
                    $(this).find("span").first().attr("data-placement", "bottom");
                    
                    var ref_extra = $(this).next().find("span").text();
                    if(ref_extra == "") {
                        ref_extra = "ninguna"
                    }
                    var ref_excluir = $(this).next().next().find("span").text();
                    if(ref_excluir == "") {
                        ref_excluir = "ninguna"
                    }
                    
                    var texto_pop = "Extra: "+ref_extra+"\nExcluir: "+ref_excluir;
                    
                    $(this).find("span").first().attr("data-content", texto_pop);
                    
                    $(this).find("span").click(function() {
                        $(this).find("span").first().popover("toggle");
                    });
                }
            });
            
            $(".mas_info").children("span").hover(function(){
                if(($(this).parent().find("span").text() != "") && (($(this).parent().next().find("span").text() != "") || ($(this).parent().next().next().find("span").text() != ""))){
                    $(this).css("cursor", "copy");
                    $(this).css("color", "#337ab7");
                    $(this).css("font-weight", "bolder");
                }
            }, function(){
                    $(this).css("cursor", "default");
                    $(this).css("color", "#333");
                    $(this).css("font-weight", "normal");
            });
            //FIn del Script para mostrar info sobre las referencias extra o a excluir en relación a la referencia de Makito
            
            //Script para mostrar los resultados tras completarse el proceso de carga de todas las referencias a mostrar
            $("#aviso").html("COMPLETADO - Todas las referencias han sido cargadas");
            setTimeout(function() {
                $("#paginacion").show(1000); 
                setTimeout(function() {
                    $("#barra_prog").css("opacity", "0");
                    $("#barra_prog").css("transition", "opacity 3s");
                    $("#barra_prog").slideUp(3000); 
                    $("#resultados").slideDown(3000);
                    
                    //Script para mostrar una cabecera de la tabla fija en el top cuando se haga scroll por debajo de la cabecera inicial
                    $("#cabecera_top").html($("#cabecera_listado").html());

                    var celda_cabecera = $("#cabecera_listado").find("th");
                    var celda_cabecera_top = $("#cabecera_top").find("th");

                    celda_cabecera_top.each(function() {                
                        //$(this).css("width", celda_cabecera.eq($(this).index()).css("width"));
                        $(this).width(celda_cabecera.eq($(this).index()).width());
                    });

                    $(document).scroll(function() {
                        var pos_y_cabecera2 = $("#cabecera_listado").parent().position().top;
                        var pos_y_cabecera3 = $("#cabecera_listado").parent().parent().position().top;
                        var pos_y_cabecera4 = $("#cabecera_listado").parent().parent().parent().position().top;

                        var pos_y_cabecera = pos_y_cabecera2 + pos_y_cabecera3 + pos_y_cabecera4;

                        var scroll = $(window).scrollTop();

                        if(scroll >= pos_y_cabecera) {
                            $("#cabecera_top").slideDown(500);
                        } else {
                            $("#cabecera_top").slideUp(100);
                        }
                    })            
                    //FIN del Script para mostrar una cabecera de la tabla fija en el top cuando se haga scroll por debajo de la cabecera inicial
                    
                }, 1000);            
            }, 1000);
            //FIN del Script para mostrar los resultados tras completarse el proceso de carga de todas las referencias a mostrar
            
        });
        
    </script>
    
    <?php
    
}

?>