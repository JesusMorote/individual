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
    
    if (isset($_SESSION['filtro'])) {
        $filtro = $_SESSION['filtro'];
    } else {
        $filtro = "todos";
    }
    
    
    if(isset($_POST['filtro'])) {
        $filtro = $_POST['filtro'];
        
        $_SESSION['filtro'] = $filtro;
        
        $pag = 1;
    }
    
    $ref_inicio = (($pag-1) * $ref_por_pag) + 1;
    $ref_fin = $ref_inicio + ($ref_por_pag -1);
    
    $offset = (($pag-1) * $ref_por_pag);
    
    if ($filtro == "todos") {
        $consulta_referencias = "SELECT *
                                    FROM referencias_makito_vs_competidores
                                    ORDER BY referencias_makito_vs_competidores_ref_makito ASC
                                    LIMIT $ref_por_pag OFFSET $offset;";
        
        $consulta_num_referencias = "SELECT  COUNT(*)
                                    FROM referencias_makito_vs_competidores;";
    } else if ($filtro == "con_correspondencia") {
        $consulta_referencias = "SELECT *
                                FROM referencias_makito_vs_competidores
                                WHERE referencias_makito_vs_competidores_cifra NOT LIKE ''
                                    OR referencias_makito_vs_competidores_gvng NOT LIKE ''
                                    OR referencias_makito_vs_competidores_mob NOT LIKE ''
                                    OR referencias_makito_vs_competidores_ps NOT LIKE ''
                                    OR referencias_makito_vs_competidores_pf NOT LIKE ''
                                    OR referencias_makito_vs_competidores_ggy NOT LIKE ''
                                ORDER BY referencias_makito_vs_competidores_ref_makito ASC
                                LIMIT $ref_por_pag OFFSET $offset;";
        
        $consulta_num_referencias = "SELECT  COUNT(*)
                                    FROM referencias_makito_vs_competidores
                                    WHERE referencias_makito_vs_competidores_cifra NOT LIKE ''
                                        OR referencias_makito_vs_competidores_gvng NOT LIKE ''
                                        OR referencias_makito_vs_competidores_mob NOT LIKE ''
                                        OR referencias_makito_vs_competidores_ps NOT LIKE ''
                                        OR referencias_makito_vs_competidores_pf NOT LIKE ''
                                        OR referencias_makito_vs_competidores_ggy NOT LIKE '';";
    } else if ($filtro == "con_correspondencia_total") {
        $consulta_referencias = "SELECT *
                                FROM referencias_makito_vs_competidores
                                WHERE referencias_makito_vs_competidores_cifra NOT LIKE ''
                                    AND referencias_makito_vs_competidores_gvng NOT LIKE ''
                                    AND referencias_makito_vs_competidores_mob NOT LIKE ''
                                    AND referencias_makito_vs_competidores_ps NOT LIKE ''
                                    AND referencias_makito_vs_competidores_pf NOT LIKE ''
                                    AND referencias_makito_vs_competidores_ggy NOT LIKE ''
                                ORDER BY referencias_makito_vs_competidores_ref_makito ASC
                                LIMIT $ref_por_pag OFFSET $offset;";
        
        $consulta_num_referencias = "SELECT  COUNT(*)
                                    FROM referencias_makito_vs_competidores
                                    WHERE referencias_makito_vs_competidores_cifra NOT LIKE ''
                                        AND referencias_makito_vs_competidores_gvng NOT LIKE ''
                                        AND referencias_makito_vs_competidores_mob NOT LIKE ''
                                        AND referencias_makito_vs_competidores_ps NOT LIKE ''
                                        AND referencias_makito_vs_competidores_pf NOT LIKE ''
                                        AND referencias_makito_vs_competidores_ggy NOT LIKE '';";
    } else if ($filtro == "sin_correspondencia") {
        $consulta_referencias = "SELECT *
                                FROM referencias_makito_vs_competidores
                                WHERE referencias_makito_vs_competidores_cifra LIKE ''
                                    AND referencias_makito_vs_competidores_gvng LIKE ''
                                    AND referencias_makito_vs_competidores_mob LIKE ''
                                    AND referencias_makito_vs_competidores_ps LIKE ''
                                    AND referencias_makito_vs_competidores_pf LIKE ''
                                    AND referencias_makito_vs_competidores_ggy LIKE ''
                                ORDER BY referencias_makito_vs_competidores_ref_makito ASC
                                LIMIT $ref_por_pag OFFSET $offset;";
        
        $consulta_num_referencias = "SELECT  COUNT(*)
                                    FROM referencias_makito_vs_competidores
                                    WHERE referencias_makito_vs_competidores_cifra LIKE ''
                                        AND referencias_makito_vs_competidores_gvng LIKE ''
                                        AND referencias_makito_vs_competidores_mob LIKE ''
                                        AND referencias_makito_vs_competidores_ps LIKE ''
                                        AND referencias_makito_vs_competidores_pf LIKE ''
                                        AND referencias_makito_vs_competidores_ggy LIKE '';";
    } else if ($filtro == "con_correspondencia_cifra") {
        $consulta_referencias = "SELECT *
                                FROM referencias_makito_vs_competidores
                                WHERE referencias_makito_vs_competidores_cifra NOT LIKE ''
                                ORDER BY referencias_makito_vs_competidores_ref_makito ASC
                                LIMIT $ref_por_pag OFFSET $offset;";
        
        $consulta_num_referencias = "SELECT  COUNT(*)
                                    FROM referencias_makito_vs_competidores
                                    WHERE referencias_makito_vs_competidores_cifra NOT LIKE '';";
    } else if ($filtro == "sin_correspondencia_cifra") {
        $consulta_referencias = "SELECT *
                                FROM referencias_makito_vs_competidores
                                WHERE referencias_makito_vs_competidores_cifra LIKE ''
                                ORDER BY referencias_makito_vs_competidores_ref_makito ASC
                                LIMIT $ref_por_pag OFFSET $offset;";
        
        $consulta_num_referencias = "SELECT  COUNT(*)
                                    FROM referencias_makito_vs_competidores
                                    WHERE referencias_makito_vs_competidores_cifra LIKE '';";
    } else if ($filtro == "con_correspondencia_gvng") {
        $consulta_referencias = "SELECT *
                                FROM referencias_makito_vs_competidores
                                WHERE referencias_makito_vs_competidores_gvng NOT LIKE ''
                                ORDER BY referencias_makito_vs_competidores_ref_makito ASC
                                LIMIT $ref_por_pag OFFSET $offset;";
        
        $consulta_num_referencias = "SELECT  COUNT(*)
                                    FROM referencias_makito_vs_competidores
                                    WHERE referencias_makito_vs_competidores_gvng NOT LIKE '';";
    } else if ($filtro == "sin_correspondencia_gvng") {
        $consulta_referencias = "SELECT *
                                FROM referencias_makito_vs_competidores
                                WHERE referencias_makito_vs_competidores_gvng LIKE ''
                                ORDER BY referencias_makito_vs_competidores_ref_makito ASC
                                LIMIT $ref_por_pag OFFSET $offset;";
        
        $consulta_num_referencias = "SELECT  COUNT(*)
                                    FROM referencias_makito_vs_competidores
                                    WHERE referencias_makito_vs_competidores_gvng LIKE '';";
    } else if ($filtro == "con_correspondencia_mob") {
        $consulta_referencias = "SELECT *
                                FROM referencias_makito_vs_competidores
                                WHERE referencias_makito_vs_competidores_mob NOT LIKE ''
                                ORDER BY referencias_makito_vs_competidores_ref_makito ASC
                                LIMIT $ref_por_pag OFFSET $offset;";
        
        $consulta_num_referencias = "SELECT  COUNT(*)
                                    FROM referencias_makito_vs_competidores
                                    WHERE referencias_makito_vs_competidores_mob NOT LIKE '';";
    } else if ($filtro == "sin_correspondencia_mob") {
        $consulta_referencias = "SELECT *
                                FROM referencias_makito_vs_competidores
                                WHERE referencias_makito_vs_competidores_mob LIKE ''
                                ORDER BY referencias_makito_vs_competidores_ref_makito ASC
                                LIMIT $ref_por_pag OFFSET $offset;";
        
        $consulta_num_referencias = "SELECT  COUNT(*)
                                    FROM referencias_makito_vs_competidores
                                    WHERE referencias_makito_vs_competidores_mob LIKE '';";
    } else if ($filtro == "con_correspondencia_ps") {
        $consulta_referencias = "SELECT *
                                FROM referencias_makito_vs_competidores
                                WHERE referencias_makito_vs_competidores_ps NOT LIKE ''
                                ORDER BY referencias_makito_vs_competidores_ref_makito ASC
                                LIMIT $ref_por_pag OFFSET $offset;";
        
        $consulta_num_referencias = "SELECT  COUNT(*)
                                    FROM referencias_makito_vs_competidores
                                    WHERE referencias_makito_vs_competidores_ps NOT LIKE '';";
    } else if ($filtro == "sin_correspondencia_ps") {
        $consulta_referencias = "SELECT *
                                FROM referencias_makito_vs_competidores
                                WHERE referencias_makito_vs_competidores_ps LIKE ''
                                ORDER BY referencias_makito_vs_competidores_ref_makito ASC
                                LIMIT $ref_por_pag OFFSET $offset;";
        
        $consulta_num_referencias = "SELECT  COUNT(*)
                                    FROM referencias_makito_vs_competidores
                                    WHERE referencias_makito_vs_competidores_ps LIKE '';";
    } else if ($filtro == "con_correspondencia_pf") {
        $consulta_referencias = "SELECT *
                                FROM referencias_makito_vs_competidores
                                WHERE referencias_makito_vs_competidores_pf NOT LIKE ''
                                ORDER BY referencias_makito_vs_competidores_ref_makito ASC
                                LIMIT $ref_por_pag OFFSET $offset;";
        
        $consulta_num_referencias = "SELECT  COUNT(*)
                                    FROM referencias_makito_vs_competidores
                                    WHERE referencias_makito_vs_competidores_pf NOT LIKE '';";
    } else if ($filtro == "sin_correspondencia_pf") {
        $consulta_referencias = "SELECT *
                                FROM referencias_makito_vs_competidores
                                WHERE referencias_makito_vs_competidores_pf LIKE ''
                                ORDER BY referencias_makito_vs_competidores_ref_makito ASC
                                LIMIT $ref_por_pag OFFSET $offset;";
        
        $consulta_num_referencias = "SELECT  COUNT(*)
                                    FROM referencias_makito_vs_competidores
                                    WHERE referencias_makito_vs_competidores_pf LIKE '';";
    } else if ($filtro == "con_correspondencia_ggy") {
        $consulta_referencias = "SELECT *
                                FROM referencias_makito_vs_competidores
                                WHERE referencias_makito_vs_competidores_ggy NOT LIKE ''
                                ORDER BY referencias_makito_vs_competidores_ref_makito ASC
                                LIMIT $ref_por_pag OFFSET $offset;";
        
        $consulta_num_referencias = "SELECT  COUNT(*)
                                    FROM referencias_makito_vs_competidores
                                    WHERE referencias_makito_vs_competidores_ggy NOT LIKE '';";
    } else if ($filtro == "sin_correspondencia_ggy") {
        $consulta_referencias = "SELECT *
                                FROM referencias_makito_vs_competidores
                                WHERE referencias_makito_vs_competidores_ggy LIKE ''
                                ORDER BY referencias_makito_vs_competidores_ref_makito ASC
                                LIMIT $ref_por_pag OFFSET $offset;";
        
        $consulta_num_referencias = "SELECT  COUNT(*)
                                    FROM referencias_makito_vs_competidores
                                    WHERE referencias_makito_vs_competidores_ggy LIKE '';";
    } else if ($filtro == "act_6h") {
        $consulta_referencias = "SELECT *
                                FROM referencias_makito_vs_competidores
                                WHERE referencias_makito_vs_competidores_frecuencia LIKE 'cada6h'
                                ORDER BY referencias_makito_vs_competidores_ref_makito ASC
                                LIMIT $ref_por_pag OFFSET $offset;";
        
        $consulta_num_referencias = "SELECT  COUNT(*)
                                    FROM referencias_makito_vs_competidores
                                    WHERE referencias_makito_vs_competidores_frecuencia LIKE 'cada6h';";
    } else if ($filtro == "act_12h") {
        $consulta_referencias = "SELECT *
                                FROM referencias_makito_vs_competidores
                                WHERE referencias_makito_vs_competidores_frecuencia LIKE 'cada12h'
                                ORDER BY referencias_makito_vs_competidores_ref_makito ASC
                                LIMIT $ref_por_pag OFFSET $offset;";
        
        $consulta_num_referencias = "SELECT  COUNT(*)
                                    FROM referencias_makito_vs_competidores
                                    WHERE referencias_makito_vs_competidores_frecuencia LIKE 'cada12h';";
    } else if ($filtro == "act_dia") {
        $consulta_referencias = "SELECT *
                                FROM referencias_makito_vs_competidores
                                WHERE referencias_makito_vs_competidores_frecuencia LIKE 'diaria'
                                ORDER BY referencias_makito_vs_competidores_ref_makito ASC
                                LIMIT $ref_por_pag OFFSET $offset;";
        
        $consulta_num_referencias = "SELECT  COUNT(*)
                                    FROM referencias_makito_vs_competidores
                                    WHERE referencias_makito_vs_competidores_frecuencia LIKE 'diaria';";
    } else if ($filtro == "act_semana") {
        $consulta_referencias = "SELECT *
                                FROM referencias_makito_vs_competidores
                                WHERE referencias_makito_vs_competidores_frecuencia LIKE 'semanal'
                                ORDER BY referencias_makito_vs_competidores_ref_makito ASC
                                LIMIT $ref_por_pag OFFSET $offset;";
        
        $consulta_num_referencias = "SELECT  COUNT(*)
                                    FROM referencias_makito_vs_competidores
                                    WHERE referencias_makito_vs_competidores_frecuencia LIKE 'semanal';";
    } else if ($filtro == "act_mes") {
        $consulta_referencias = "SELECT *
                                FROM referencias_makito_vs_competidores
                                WHERE referencias_makito_vs_competidores_frecuencia LIKE 'mensual'
                                ORDER BY referencias_makito_vs_competidores_ref_makito ASC
                                LIMIT $ref_por_pag OFFSET $offset;";
        
        $consulta_num_referencias = "SELECT  COUNT(*)
                                    FROM referencias_makito_vs_competidores
                                    WHERE referencias_makito_vs_competidores_frecuencia LIKE 'mensual';";
    } else if ($filtro == "act_nunca") {
        $consulta_referencias = "SELECT *
                                FROM referencias_makito_vs_competidores
                                WHERE referencias_makito_vs_competidores_frecuencia LIKE 'nunca'
                                ORDER BY referencias_makito_vs_competidores_ref_makito ASC
                                LIMIT $ref_por_pag OFFSET $offset;";
        
        $consulta_num_referencias = "SELECT  COUNT(*)
                                    FROM referencias_makito_vs_competidores
                                    WHERE referencias_makito_vs_competidores_frecuencia LIKE 'nunca';";
    }
    
    $resultado_consulta_referencias = $bd->Execute($consulta_referencias);
    
    $resultados_consulta_num_referencias = $bd->Execute($consulta_num_referencias);
    $num_referencias = $resultados_consulta_num_referencias->fields[0];
    
    $num_paginas = ceil($num_referencias / $ref_por_pag);
    
    ?>
    
    <div class="container-fluid">
        <!--Cabecera de Elección de Número de Referencias a mostrar por página--> 
        <div class="row">
        
            <h4 class="enmarcado2 indentado text-center">Listado de Referencias/Correspondencias</h4>
           
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
                        <option value="todos" id="todos">Todas las Referencias Makito</option>
                        <option value="con_correspondencia" id="con_correspondencia">Con Alguna Correspondencia</option>
                        <option value="con_correspondencia_total" id="con_correspondencia_total">Con Todas las Correspondencias</option>
                        <option value="sin_correspondencia" id="sin_correspondencia">Sin Ninguna Correspondencia</option>
                        <option disabled>─────────────────────────</option>
                        <option value="con_correspondencia_cifra" id="con_correspondencia_cifra">Correspondencia con CIFRA</option>
                        <option value="sin_correspondencia_cifra" id="sin_correspondencia_cifra">Sin Correspondencia con CIFRA</option>
                        <option disabled>─────────────────────────</option>
                        <option value="con_correspondencia_gvng" id="con_correspondencia_gvng">Correspondencia con GIVING</option>
                        <option value="sin_correspondencia_gvng" id="sin_correspondencia_gvng">Sin Correspondencia con GIVING</option>
                        <option disabled>─────────────────────────</option>
                        <option value="con_correspondencia_mob" id="con_correspondencia_mob">Correspondencia con MOB</option>
                        <option value="sin_correspondencia_mob" id="sin_correspondencia_mob">Sin Correspondencia con MOB</option>
                        <option disabled>─────────────────────────</option>
                        <option value="con_correspondencia_ps" id="con_correspondencia_ps">Correspondencia con PS</option>
                        <option value="sin_correspondencia_ps" id="sin_correspondencia_ps">Sin Correspondencia con PS</option>
                        <option disabled>─────────────────────────</option>
                        <option value="con_correspondencia_pf" id="con_correspondencia_pf">Correspondencia con PF</option>
                        <option value="sin_correspondencia_pf" id="sin_correspondencia_pf">Sin Correspondencia con PF</option>
                        <option disabled>─────────────────────────</option>
                        <option value="con_correspondencia_ggy" id="con_correspondencia_ggy">Correspondencia con G.GOYA</option>
                        <option value="sin_correspondencia_ggy" id="sin_correspondencia_ggy">Sin Correspondencia con G.GOYA</option>
                        <option disabled>─────────────────────────</option>
                        <option value="act_6h" id="act_6h">Actualizadas cada 6 horas</option>
                        <option value="act_12h" id="act_12h">Actualizadas cada 12 horas</option>
                        <option value="act_dia" id="act_dia">Actualizadas cada día</option>
                        <option value="act_semana" id="act_semana">Actualizadas cada semana</option>
                        <option value="act_mes" id="act_mes">Actualizadas cada mes</option>
                        <option value="act_nunca" id="act_nunca">Actualizadas nunca</option>
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
                            <a href="inicio.php?id=listar_referencias&pag=<?php echo ($pag-1); ?>">
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
                            <a href="inicio.php?id=listar_referencias&pag=<?php echo ($pag+1); ?>" aria-label="Previous">
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
        
        <!--Tabla de resultados a mostrar-->
        <div class="row">
            <div class="col-md-12">
                
                <table class="table table-bordered table-condensed table-striped">
                    <tr id="cabecera_listado">
                        <th class="info celdacentradahor celdacentradavert">Imagen</th>
                        <th class="info celdacentradahor celdacentradavert nombre_makito">Nombre</th>
                        <th class="info celdacentradahor celdacentradavert celda_homogenea"><i class="glyphicon glyphicon-barcode"></i> MAKITO</th>
                        <th class="info celdacentradahor celdacentradavert celda_homogenea"><i class="glyphicon glyphicon-barcode"></i> CIFRA</th>
                        <th class="info celdacentradahor celdacentradavert oculto celda_homogenea"><i class="glyphicon glyphicon-barcode"></i> CIFRA Extras</th>
                        <th class="info celdacentradahor celdacentradavert oculto celda_homogenea"><i class="glyphicon glyphicon-barcode"></i> CIFRA Excluir</th>
                        <th class="info celdacentradahor celdacentradavert celda_homogenea"><i class="glyphicon glyphicon-barcode"></i> GIVING</th>
                        <th class="info celdacentradahor celdacentradavert oculto celda_homogenea"><i class="glyphicon glyphicon-barcode"></i> GIVING Extras</th>
                        <th class="info celdacentradahor celdacentradavert oculto celda_homogenea"><i class="glyphicon glyphicon-barcode"></i> GIVING Excluir</th>
                        <th class="info celdacentradahor celdacentradavert celda_homogenea"><i class="glyphicon glyphicon-barcode"></i> MOB</th>
                        <th class="info celdacentradahor celdacentradavert oculto celda_homogenea"><i class="glyphicon glyphicon-barcode"></i> MOB Extras</th>
                        <th class="info celdacentradahor celdacentradavert oculto celda_homogenea"><i class="glyphicon glyphicon-barcode"></i> MOB Excluir</th>
                        <th class="info celdacentradahor celdacentradavert celda_homogenea"><i class="glyphicon glyphicon-barcode"></i> PS</th>
                        <th class="info celdacentradahor celdacentradavert oculto celda_homogenea"><i class="glyphicon glyphicon-barcode"></i> PS Extras</th>
                        <th class="info celdacentradahor celdacentradavert oculto celda_homogenea"><i class="glyphicon glyphicon-barcode"></i> PS Excluir</th>
                        <th class="info celdacentradahor celdacentradavert celda_homogenea"><i class="glyphicon glyphicon-barcode"></i> PF</th>
                        <th class="info celdacentradahor celdacentradavert oculto celda_homogenea"><i class="glyphicon glyphicon-barcode"></i> PF Extras</th>
                        <th class="info celdacentradahor celdacentradavert oculto celda_homogenea"><i class="glyphicon glyphicon-barcode"></i> PF Excluir</th>
                        <th class="info celdacentradahor celdacentradavert celda_homogenea"><i class="glyphicon glyphicon-barcode"></i> G.GOYA</th>
                        <th class="info celdacentradahor celdacentradavert oculto celda_homogenea"><i class="glyphicon glyphicon-barcode"></i> G.GOYA Extras</th>
                        <th class="info celdacentradahor celdacentradavert oculto celda_homogenea"><i class="glyphicon glyphicon-barcode"></i> G.GOYA Excluir</th>
                        <th class="info celdacentradahor celdacentradavert">Stock</th>
                        <th class="info celdacentradahor celdacentradavert">Variación</th>
                        <th class="info celdacentradahor celdacentradavert">Frecuencia</th>
                        <th class="info celdacentradahor celdacentradavert">Editar</th>
                        <th class="info celdacentradahor celdacentradavert">Borrar</th>
                    </tr>
                    
                    <tr id="cabecera_top">
                        <!--El contenido de esta cabecera lo creo dinámicamente con jQuery al cargar la página-->
                    </tr>
               
                <?php
    
                $resultado_consulta_referencias->MoveFirst();
    
                while (!($resultado_consulta_referencias->EOF)) {
                    $articulo_ref = $resultado_consulta_referencias->fields['referencias_makito_vs_competidores_ref_makito'];
                    $articulo_nombre = $resultado_consulta_referencias->fields['referencias_makito_vs_competidores_nombre_makito'];
                    $articulo_ref_cifra = $resultado_consulta_referencias->fields['referencias_makito_vs_competidores_cifra'];
                    $articulo_ref_cifra_extras = $resultado_consulta_referencias->fields['referencias_makito_vs_competidores_cifra_extras'];
                    $articulo_ref_cifra_excluir = $resultado_consulta_referencias->fields['referencias_makito_vs_competidores_cifra_excluir'];
                    $articulo_ref_gvng = $resultado_consulta_referencias->fields['referencias_makito_vs_competidores_gvng'];
                    $articulo_ref_gvng_extras = $resultado_consulta_referencias->fields['referencias_makito_vs_competidores_gvng_extras'];
                    $articulo_ref_gvng_excluir = $resultado_consulta_referencias->fields['referencias_makito_vs_competidores_gvng_excluir'];
                    $articulo_ref_mob = $resultado_consulta_referencias->fields['referencias_makito_vs_competidores_mob'];
                    $articulo_ref_mob_extras = $resultado_consulta_referencias->fields['referencias_makito_vs_competidores_mob_extras'];
                    $articulo_ref_mob_excluir = $resultado_consulta_referencias->fields['referencias_makito_vs_competidores_mob_excluir'];
                    $articulo_ref_ps = $resultado_consulta_referencias->fields['referencias_makito_vs_competidores_ps'];
                    $articulo_ref_ps_extras = $resultado_consulta_referencias->fields['referencias_makito_vs_competidores_ps_extras'];
                    $articulo_ref_ps_excluir = $resultado_consulta_referencias->fields['referencias_makito_vs_competidores_ps_excluir'];
                    $articulo_ref_pf = $resultado_consulta_referencias->fields['referencias_makito_vs_competidores_pf'];
                    $articulo_ref_pf_extras = $resultado_consulta_referencias->fields['referencias_makito_vs_competidores_pf_extras'];
                    $articulo_ref_pf_excluir = $resultado_consulta_referencias->fields['referencias_makito_vs_competidores_pf_excluir'];
                    $articulo_ref_ggy = $resultado_consulta_referencias->fields['referencias_makito_vs_competidores_ggy'];
                    $articulo_ref_ggy_extras = $resultado_consulta_referencias->fields['referencias_makito_vs_competidores_ggy_extras'];
                    $articulo_ref_ggy_excluir = $resultado_consulta_referencias->fields['referencias_makito_vs_competidores_ggy_excluir'];
                    $articulo_frecuencia = $resultado_consulta_referencias->fields['referencias_makito_vs_competidores_frecuencia'];
                    
                    if($articulo_frecuencia == "cada6h") {
                        $articulo_frecuencia = "Cada 6 horas";
                    } else if($articulo_frecuencia == "cada12h") {
                        $articulo_frecuencia = "Cada 12 horas";
                    } else if($articulo_frecuencia == "diaria") {
                        $articulo_frecuencia = "Diariamente";
                    } else if($articulo_frecuencia == "semanal") {
                        $articulo_frecuencia = "Semanalmente";
                    } else if($articulo_frecuencia == "mensual") {
                        $articulo_frecuencia = "Mensualmente";
                    } else {
                        $articulo_frecuencia = "Nunca";
                    }   
                    
                    ?>
                    
                    <tr>
                        <td class="celdacentradahor celdacentradavert">
                            <?php
                            //if(@fopen('http://www.makito.es/imagenes/0-7999/'.$articulo_ref.'.jpg',"r")==true) {
                            if(file_exists('img/articulo/'.$articulo_ref.'p.jpg')) {
                                ?>
                                <img src="img/articulo/<?php echo $articulo_ref; ?>p.jpg" width="50px" class="enmarcado3 miniatura">
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
                        <td class="celdacentradahor celdacentradavert celda_homogenea">
                            <span class="articulo_ref"><?php echo $articulo_ref; ?></span>
                        </td>
                        <td class="celdacentradahor celdacentradavert mas_info celda_homogenea">
                            <span><?php echo $articulo_ref_cifra; ?></span>
                            <?php
                            if($articulo_ref_cifra != "") {
                                ?>
                                <br>
                                <a href="../inc/muestra_web_competidor.inc.php?ref=<?php echo $articulo_ref_cifra; ?>&comp=cifra" target="_blank">
                                    <button type="button" class="btn btn-muted btn-xs contorneado"><span class="glyphicon glyphicon-link" aria-hidden="true"></span></button>
                                </a>
                                <?php
                            }
                            ?>
                        </td>
                        <td class="celdacentradahor celdacentradavert oculto celda_homogenea">
                            <span><?php echo $articulo_ref_cifra_extras; ?></span>
                        </td>
                        <td class="celdacentradahor celdacentradavert oculto celda_homogenea">
                            <span><?php echo $articulo_ref_cifra_excluir; ?></span>
                        </td>
                        <td class="celdacentradahor celdacentradavert mas_info celda_homogenea">
                            <span><?php echo $articulo_ref_gvng; ?></span>
                            <?php
                            if($articulo_ref_gvng != "") {
                                ?>
                                <br>
                                <a href="../inc/muestra_web_competidor.inc.php?ref=<?php echo $articulo_ref_gvng; ?>&comp=giving" target="_blank">
                                    <button type="button" class="btn btn-muted btn-xs contorneado"><span class="glyphicon glyphicon-link" aria-hidden="true"></span></button>
                                </a>
                                <?php
                            }
                            ?>
                        </td>
                        <td class="celdacentradahor celdacentradavert oculto celda_homogenea">
                            <span><?php echo $articulo_ref_gvng_extras; ?></span>
                        </td>
                        <td class="celdacentradahor celdacentradavert oculto celda_homogenea">
                            <span><?php echo $articulo_ref_gvng_excluir; ?></span>
                        </td>
                        <td class="celdacentradahor celdacentradavert mas_info celda_homogenea">
                            <span><?php echo $articulo_ref_mob; ?></span>
                            <?php
                            if($articulo_ref_mob != "") {
                                ?>
                                <br>
                                <a href="../inc/muestra_web_competidor.inc.php?ref=<?php echo $articulo_ref_mob; ?>&comp=mob" target="_blank">
                                    <button type="button" class="btn btn-muted btn-xs contorneado"><span class="glyphicon glyphicon-link" aria-hidden="true"></span></button>
                                </a>
                                <?php
                            }
                            ?>
                        </td>
                        <td class="celdacentradahor celdacentradavert oculto celda_homogenea">
                            <span><?php echo $articulo_ref_mob_extras; ?></span>
                        </td>
                        <td class="celdacentradahor celdacentradavert oculto celda_homogenea">
                            <span><?php echo $articulo_ref_mob_excluir; ?></span>
                        </td>
                        <td class="celdacentradahor celdacentradavert mas_info celda_homogenea">
                            <span><?php echo $articulo_ref_ps; ?></span>
                            <?php
                            if($articulo_ref_ps != "") {
                                ?>
                                <br>
                                <a href="../inc/muestra_web_competidor.inc.php?ref=<?php echo $articulo_ref_ps; ?>&comp=ps" target="_blank">
                                    <button type="button" class="btn btn-muted btn-xs contorneado"><span class="glyphicon glyphicon-link" aria-hidden="true"></span></button>
                                </a>
                                <?php
                            }
                            ?>
                        </td>
                        <td class="celdacentradahor celdacentradavert oculto celda_homogenea">
                            <span><?php echo $articulo_ref_ps_extras; ?></span>
                        </td>
                        <td class="celdacentradahor celdacentradavert oculto celda_homogenea">
                            <span><?php echo $articulo_ref_ps_excluir; ?></span>
                        </td>
                        <td class="celdacentradahor celdacentradavert mas_info celda_homogenea">
                            <span><?php echo $articulo_ref_pf; ?></span>
                            <?php
                            if($articulo_ref_pf != "") {
                                ?>
                                <br>
                                <a href="../inc/muestra_web_competidor.inc.php?ref=<?php echo $articulo_ref_pf; ?>&comp=pf" target="_blank">
                                    <button type="button" class="btn btn-muted btn-xs contorneado"><span class="glyphicon glyphicon-link" aria-hidden="true"></span></button>
                                </a>
                                <?php
                            }
                            ?>
                        </td>
                        <td class="celdacentradahor celdacentradavert oculto celda_homogenea">
                            <span><?php echo $articulo_ref_pf_extras; ?></span>
                        </td>
                        <td class="celdacentradahor celdacentradavert oculto celda_homogenea">
                            <span><?php echo $articulo_ref_pf_excluir; ?></span>
                        </td>
                        <td class="celdacentradahor celdacentradavert mas_info celda_homogenea">
                            <span><?php echo $articulo_ref_ggy; ?></span>
                            <?php
                            if($articulo_ref_ggy != "") {
                                ?>
                                <br>
                                <a href="../inc/muestra_web_competidor.inc.php?ref=<?php echo $articulo_ref_ggy; ?>&comp=ggoya" target="_blank">
                                    <button type="button" class="btn btn-muted btn-xs contorneado"><span class="glyphicon glyphicon-link" aria-hidden="true"></span></button>
                                </a>
                                <?php
                            }
                            ?>
                        </td>
                        <td class="celdacentradahor celdacentradavert oculto celda_homogenea">
                            <span><?php echo $articulo_ref_ggy_extras; ?></span>
                        </td>
                        <td class="celdacentradahor celdacentradavert oculto celda_homogenea">
                            <span><?php echo $articulo_ref_ggy_excluir; ?></span>
                        </td>
                        
                        <?php
                        if($articulo_ref_cifra == "" and $articulo_ref_gvng == "" and $articulo_ref_mob == "" and $articulo_ref_ps == "" and $articulo_ref_pf == "" and $articulo_ref_ggy == "") {
                            ?>
                            <td class="celdacentradahor celdacentradavert">
                                <a href="#" class="no_link">
                                    <button type="button" class="btn btn-muted btn-sm"><span class="glyphicon glyphicon-screenshot" aria-hidden="true"></span></button>
                                </a>  
                            </td>
                            <td class="celdacentradahor celdacentradavert">
                                <a href="#" class="no_link">
                                    <button type="button" class="btn btn-muted btn-sm"><span class="glyphicon glyphicon-stats" aria-hidden="true"></span></button>
                                </a>  
                            </td>
                            <?php
                        } else {
                            ?>
                            <td class="celdacentradahor celdacentradavert">
                                <a href="inicio.php?id=stock_actual&ref=<?php echo $articulo_ref; ?>" target="_blank">
                                    <button type="button" class="btn btn-warning btn-sm"><span class="glyphicon glyphicon-screenshot" aria-hidden="true"></span></button>
                                </a>  
                            </td>
                            <td class="celdacentradahor celdacentradavert">
                                <a href="inicio.php?id=variacion_stock&ref=<?php echo $articulo_ref; ?>" target="_blank">
                                    <button type="button" class="btn btn-info btn-sm"><span class="glyphicon glyphicon-stats" aria-hidden="true"></span></button>
                                </a>  
                            </td>
                            <?php
                        }
                        ?>
                        <td class="celdacentradahor celdacentradavert celda_homogenea">
                            <span><?php echo $articulo_frecuencia; ?></span>
                        </td>
                        <td class="celdacentradahor celdacentradavert">
                            <a href="inicio.php?id=modificar_referencia&ref=<?php echo $articulo_ref; ?>">
                                <button type="button" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></button>
                            </a>  
                        </td>
                        <td class="celdacentradahor celdacentradavert">                            
                            <a href="inicio.php?id=eliminar_referencia&ref=<?php echo $articulo_ref; ?>">
                                <button type="button" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button>
                            </a>
                        </td>
                    </tr>
                    
                    <?php

                    $resultado_consulta_referencias->MoveNext();
                }
    
                ?>
                
                </table>
            
            </div>
            <!--FIN de Listado de Items-->
    <?php
    
    
    
    while (!($resultado_consulta_referencias->EOF)) {
        $articulo_ref = $resultado_consulta_referencias->fields['referencias_makito_vs_competidores_ref_makito'];
                
        //if(@fopen('http://www.makito.es/imagenes/0-7999/'.$articulo_ref.'.jpg',"r")==true) {
        if(file_exists('img/articulo/'.$articulo_ref.'p.jpg')) {
            ?>
            <img src="img/articulo/<?php echo $articulo_ref; ?>p.jpg" width="150px">&nbsp;-&nbsp;
            <?php
        } else {
            ?>
            <img src="img/varios/noimg.jpg" width="150px">&nbsp;-&nbsp;
            <?php
        }
        
        ?>
        <span class="articulo_ref"><?php echo $articulo_ref; ?></span><br>
        <?php
        
        $resultado_consulta_referencias->MoveNext();
    }
    
    ?>
    
        </div>
        
        <hr>
        
        <!--Botones de Navegación - Paginación-->
        <div class="row">
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
                            <a href="inicio.php?id=listar_referencias&pag=<?php echo ($pag-1); ?>" aria-label="Previous">
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
                            <li class="active"><a href="inicio.php?id=listar_referencias&pag=<?php echo $i; ?>" class="btn_pg_<?php echo $i; ?>"><?php echo $i; ?></a></li>
                            <?php
                        } else {
                            ?>
                            <li><a href="inicio.php?id=listar_referencias&pag=<?php echo $i; ?>" class="btn_pg_<?php echo $i; ?>"><?php echo $i; ?></a></li>
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
                            <a href="inicio.php?id=listar_referencias&pag=<?php echo ($pag+1); ?>" aria-label="Previous">
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
    <script src="../js/jquery.js"></script>
    
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
            
            $("#<?php echo $filtro; ?>").attr("selected", "selected");
            $("#<?php echo $filtro; ?>").css("background-color", "#337ab7");
            $("#<?php echo $filtro; ?>").css("color", "white");
            
            
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
            
        });
        
    </script>
    
    <?php
    
}

?>