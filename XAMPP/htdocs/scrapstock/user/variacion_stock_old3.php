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
    
    if(isset($_POST['filtro_referencia'])) {
        $ref_elegida = $_POST['filtro_referencia'];
        
        ?>
        <script>
            location.replace('inicio.php?id=variacion_stock&ref=<?php echo $ref_elegida; ?>');
        </script>
        <?php
    }
    
    //Obtengo los datos correspondientes a las referencias que tienen alguna correspondencia (para montar los select)
    $consulta_referencias_con_correspondencias = "SELECT referencias_makito_vs_competidores_ref_makito, 
                                                        referencias_makito_vs_competidores_nombre_makito
                                                    FROM referencias_makito_vs_competidores
                                                    WHERE referencias_makito_vs_competidores_cifra NOT LIKE ''
                                                        OR referencias_makito_vs_competidores_mob NOT LIKE ''
                                                        OR referencias_makito_vs_competidores_ps NOT LIKE ''
                                                        OR referencias_makito_vs_competidores_gvng NOT LIKE ''
                                                        OR referencias_makito_vs_competidores_pf NOT LIKE ''
                                                        OR referencias_makito_vs_competidores_ggy NOT LIKE ''
                                                    ORDER BY referencias_makito_vs_competidores_ref_makito ASC;";

    $resultado_consulta_referencias_con_correspondencias = $bd->Execute($consulta_referencias_con_correspondencias);
    
    
    //Obtengo los datos correspondientes a la referencia obtenida por GET (si se ha pasado ese parámetro)
    if(isset($_GET['ref'])) {
        $ref = $_GET['ref'];
        
        //Obtengo la fecha actual
        $fecha_actual = date('Y-m-d');
        //Obtengo la hora actual
        $hora_actual = date('H:i:s');
        
        //Consulto los datos
        $consulta_ref = "SELECT *
                            FROM referencias_makito_vs_competidores
                            WHERE referencias_makito_vs_competidores_ref_makito LIKE '$ref';";
        
        $resultado_consulta_ref = $bd->Execute($consulta_ref);
        
        $ref_makito = $resultado_consulta_ref->fields['referencias_makito_vs_competidores_ref_makito'];
        $nombre_makito = $resultado_consulta_ref->fields['referencias_makito_vs_competidores_nombre_makito'];
        $ref_cifra = $resultado_consulta_ref->fields['referencias_makito_vs_competidores_cifra'];
        $ref_cifra_extras = $resultado_consulta_ref->fields['referencias_makito_vs_competidores_cifra_extras'];
        $ref_cifra_excluir = $resultado_consulta_ref->fields['referencias_makito_vs_competidores_cifra_excluir'];
        $ref_giving = $resultado_consulta_ref->fields['referencias_makito_vs_competidores_gvng'];
        $ref_giving_extras = $resultado_consulta_ref->fields['referencias_makito_vs_competidores_gvng_extras'];
        $ref_giving_excluir = $resultado_consulta_ref->fields['referencias_makito_vs_competidores_gvng_excluir'];
        $ref_mob = $resultado_consulta_ref->fields['referencias_makito_vs_competidores_mob'];
        $ref_mob_extras = $resultado_consulta_ref->fields['referencias_makito_vs_competidores_mob_extras'];
        $ref_mob_excluir = $resultado_consulta_ref->fields['referencias_makito_vs_competidores_mob_excluir'];
        $ref_ps = $resultado_consulta_ref->fields['referencias_makito_vs_competidores_ps'];
        $ref_ps_extras = $resultado_consulta_ref->fields['referencias_makito_vs_competidores_ps_extras'];
        $ref_ps_excluir = $resultado_consulta_ref->fields['referencias_makito_vs_competidores_ps_excluir'];
        $ref_pf = $resultado_consulta_ref->fields['referencias_makito_vs_competidores_pf'];
        $ref_pf_extras = $resultado_consulta_ref->fields['referencias_makito_vs_competidores_pf_extras'];
        $ref_pf_excluir = $resultado_consulta_ref->fields['referencias_makito_vs_competidores_pf_excluir'];
        $ref_ggoya = $resultado_consulta_ref->fields['referencias_makito_vs_competidores_ggy'];
        $ref_ggoya_extras = $resultado_consulta_ref->fields['referencias_makito_vs_competidores_ggy_extras'];
        $ref_ggoya_excluir = $resultado_consulta_ref->fields['referencias_makito_vs_competidores_ggy_excluir'];
        $frecuencia = $resultado_consulta_ref->fields['referencias_makito_vs_competidores_frecuencia'];
        
        
        //RECUPERO LOS DATOS QUE HAYA ALMACENADOS PARA LA REFERENCIA DESEADA
        //EN PRINCIPIO NO HABRÁ UN SELECTOR DE FECHAS
        //PORQUE LOS RESULTADOS LOS VOY A MOSTRAR MEDIANTE UN PANEL DE GGOGLE CHARTS
        //QUE PERMITA FILTRAR DINÁMICAMENTE LOS RESULTADOS POR FECHA
        $consulta_stocks = "SELECT * FROM stock_makito_vs_todos
                                WHERE stock_makito_vs_todos_fecha IN
                                    (SELECT DISTINCT stock_makito_vs_todos_fecha FROM stock_makito_vs_todos
                                        WHERE stock_makito_vs_todos_ref_makito LIKE '$ref')
                                AND stock_makito_vs_todos_ref_makito LIKE '$ref'
                                ORDER BY stock_makito_vs_todos_fecha ASC, stock_makito_vs_todos_hora ASC;";
        $resultado_consulta_stocks = $bd->Execute($consulta_stocks);
        
        //Obtengo la fecha más antigua para la que existan datos guardados de la referencia deseada
        $resultado_consulta_stocks->MoveFirst();
        $fecha_inicio_datos = $resultado_consulta_stocks->fields['stock_makito_vs_todos_fecha'];
        
        //echo "Inicio datos: ".$fecha_inicio_datos."<br>";
        
        //Obtengo la fecha más reciente para la que existan datos guardados de la referencia deseada
        $resultado_consulta_stocks->MoveLast();
        $fecha_fin_datos = $resultado_consulta_stocks->fields['stock_makito_vs_todos_fecha'];
        
        //echo "Fin datos: ".$fecha_fin_datos."<br>";
        
        //Obtengo la diferencia en días entre ambas fechas
        $fecha_inicio_datos_ts = strtotime($fecha_inicio_datos);
        $fecha_fin_datos_ts = strtotime($fecha_fin_datos);
        $diff_ts = $fecha_fin_datos_ts - $fecha_inicio_datos_ts;
        $dias_diferencia = $diff_ts / 86400;
        
        //echo "Dias de diferencia: ".$dias_diferencia."<br>";
        
        //Obtengo los datos (más recientes) de cada uno de los días
        
        $dia = 0;
        
        $array_stocks_dias = array();
        
        while ($dia <= ($dias_diferencia+1)) {
            
            $fecha_dia = date('Y-m-d', strtotime($fecha_inicio_datos) + (86400 * $dia));
            
            $consulta_stocks_dia = "SELECT * FROM stock_makito_vs_todos
                                        WHERE stock_makito_vs_todos_fecha LIKE '$fecha_dia'
                                            AND stock_makito_vs_todos_ref_makito LIKE '$ref'
                                        ORDER BY stock_makito_vs_todos_hora DESC LIMIT 1;";
            $resultado_consulta_stocks_dia = $bd->Execute($consulta_stocks_dia);
            
            if($resultado_consulta_stocks_dia->fields['stock_makito_vs_todos_fecha'] != "") {
                //echo "Dia ".$resultado_consulta_stocks_dia->fields['stock_makito_vs_todos_fecha']." / Hora ".$resultado_consulta_stocks_dia->fields['stock_makito_vs_todos_hora']."<br>";
                
                $dia_fecha = $resultado_consulta_stocks_dia->fields['stock_makito_vs_todos_fecha'];
                $stock_cifra_dia = $resultado_consulta_stocks_dia->fields['stock_makito_vs_todos_stock_cifra'];
                $stock_giving_dia = $resultado_consulta_stocks_dia->fields['stock_makito_vs_todos_stock_giving'];
                $stock_mob_dia = $resultado_consulta_stocks_dia->fields['stock_makito_vs_todos_stock_mob'];
                $stock_ps_dia = $resultado_consulta_stocks_dia->fields['stock_makito_vs_todos_stock_ps'];
                $stock_pf_dia = $resultado_consulta_stocks_dia->fields['stock_makito_vs_todos_stock_pf'];
                $stock_ggoya_dia = $resultado_consulta_stocks_dia->fields['stock_makito_vs_todos_stock_ggoya'];
                
                if($stock_cifra_dia < 0) {
                    if($dia == 0) {
                        $stock_cifra_dia = 0;
                    } else {
                        $stock_cifra_dia = $array_stocks_dias[$dia-1][1];
                    }
                }
                
                if($stock_giving_dia < 0) {
                    if($dia == 0) {
                        $stock_giving_dia = 0;
                    } else {
                        $stock_giving_dia = $array_stocks_dias[$dia-1][2];
                    }
                }
                
                if($stock_mob_dia < 0) {
                    if($dia == 0) {
                        $stock_mob_dia = 0;
                    } else {
                        $stock_mob_dia = $array_stocks_dias[$dia-1][3];
                    }
                }
                
                if($stock_ps_dia < 0) {
                    if($dia == 0) {
                        $stock_ps_dia = 0;
                    } else {
                        $stock_ps_dia = $array_stocks_dias[$dia-1][4];
                    }
                }
                
                if($stock_pf_dia < 0) {
                    if($dia == 0) {
                        $stock_pf_dia = 0;
                    } else {
                        $stock_pf_dia = $array_stocks_dias[$dia-1][5];
                    }
                }
                
                if($stock_ggoya_dia < 0) {
                    if($dia == 0) {
                        $stock_ggoya_dia = 0;
                    } else {
                        $stock_ggoya_dia = $array_stocks_dias[$dia-1][6];
                    }
                }
                
                //Convierto la fecha de string a milisegundos antes de pasarla para ser interpretado su valor en la gráfica
                $fecha_dia_seg_mod = (strtotime($fecha_dia))*1000;
                
                $array_stock_dia = array($fecha_dia_seg_mod, $stock_cifra_dia, $stock_giving_dia, $stock_mob_dia, $stock_ps_dia, $stock_pf_dia, $stock_ggoya_dia);
                
                //print_r($array_stock_dia);
                //echo "<br>";
                
                array_push($array_stocks_dias, $array_stock_dia);
                
                
            } else {
                
                if($dia == 0) {
                    $stock_cifra_dia = 0;
                } else {
                    $stock_cifra_dia = $array_stocks_dias[$dia-1][1];
                }

                if($dia == 0) {
                    $stock_giving_dia = 0;
                } else {
                    $stock_giving_dia = $array_stocks_dias[$dia-1][2];
                }

                if($dia == 0) {
                    $stock_mob_dia = 0;
                } else {
                    $stock_mob_dia = $array_stocks_dias[$dia-1][3];
                }

                if($dia == 0) {
                    $stock_ps_dia = 0;
                } else {
                    $stock_ps_dia = $array_stocks_dias[$dia-1][4];
                }

                if($dia == 0) {
                    $stock_pf_dia = 0;
                } else {
                    $stock_pf_dia = $array_stocks_dias[$dia-1][5];
                }

                if($dia == 0) {
                    $stock_ggoya_dia = 0;
                } else {
                    $stock_ggoya_dia = $array_stocks_dias[$dia-1][6];
                }
                
                //Convierto la fecha de string a milisegundos antes de pasarla para ser interpretado su valor en la gráfica
                $fecha_dia_seg_mod = (strtotime($fecha_dia))*1000;
                
                $array_stock_dia = array($fecha_dia_seg_mod, $stock_cifra_dia, $stock_giving_dia, $stock_mob_dia, $stock_ps_dia, $stock_pf_dia, $stock_ggoya_dia);
                
                //print_r($array_stock_dia);
                //echo "<br>";
                
                array_push($array_stocks_dias, $array_stock_dia);
                
                //echo "Dia ".$fecha_dia." / DIA SIN DATOS<br>";
            }
            
            $dia++;
        }
        
        
        //MUESTRO LOS DATOS DE LOS STOCKS OBTENIDOS DEL ARTÍCULO ELEGIDO
        ?>
        
        <!--Load the AJAX API-->
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <script type="text/javascript">
            
          google.charts.load('current', {'packages':['corechart', 'controls']});
          //google.charts.setOnLoadCallback(drawChart);
          google.charts.setOnLoadCallback(drawDashboard);
            
          function drawDashboard() {
              
              //La Variable dashboard la declaro como global, porque luego voy a usarla con jQuery, tras cargar la página, para modificar los competidores que se muestran o no
              dashboard = new google.visualization.Dashboard(document.getElementById('dashboard_div'));
              
              var data = google.visualization.arrayToDataTable([
                  
                  //TODOS LOS COMPETIDORES
                  ['Día', 'Cifra', 'Giving', 'Mob', 'Ps', 'Pf', 'GGoya'],

                  //Datos obtenidos de la BD    
                  <?php
                    $array_stocks_str = "";

                    foreach ($array_stocks_dias as $stocks_dia) {
                        $array_str = "[new Date(".$stocks_dia[0]."), ".$stocks_dia[1].", ".$stocks_dia[2].", ".$stocks_dia[3].", ".$stocks_dia[4].", ".$stocks_dia[5].", ".$stocks_dia[6]."]";

                        $array_stocks_str = $array_stocks_str."".$array_str.",";    
                    }

                    echo substr($array_stocks_str, 0, -1);
                  ?>
                  
                  //Datos de prueba
                  /*  
                  ['2017-10-19', 400, 1000, 800, 300, 1200, 200],
                  ['2017-10-20', 1170, 360, 2170, 60, 1270, 760],
                  ['2017-10-21', 660, 1120, 950, 1125, 650, 1020],
                  ['2017-10-22', 540, 2030, 300, 750, 540, 1930]
                   */ 

              ], false);
              
              //Puedo usar un DataView para ocultar columnas
              //La Variable view la declaro como global, porque luego voy a usarla con jQuery, tras cargar la página, para modificar los competidores que se muestran o no
              view = new google.visualization.DataView(data);
              
              <?php
                if($ref_cifra == "") {
                    ?>
                    view.hideColumns([1]);
                    <?php
                }
        
                if($ref_giving == "") {
                    ?>
                    view.hideColumns([2]);
                    <?php
                }
        
                if($ref_mob == "") {
                    ?>
                    view.hideColumns([3]);
                    <?php
                }
        
                if($ref_ps == "") {
                    ?>
                    view.hideColumns([4]);
                    <?php
                }
        
                if($ref_pf == "") {
                    ?>
                    view.hideColumns([5]);
                    <?php
                }
        
                if($ref_ggoya == "") {
                    ?>
                    view.hideColumns([6]);
                    <?php
                }
              ?>
              
              //view.hideColumns([2, 3, 4, 5, 6]); //(por ejemplo, todas menos la de Cifra)
              //OJO, SI ELIMINO O COMENTO ESTAS 2 LÍNEAS, TENGO QUE MODIFICAR MÁS ABAJO LA SENTENCIA dashboard.draw(view); POR dashboard.draw(data); Y SI LAS DESCOMENTO VOLVER A PONER dashboard.draw(view);
              
              var fecha_disc_Filter = new google.visualization.ControlWrapper({
                'controlType': 'CategoryFilter',
                'containerId': 'fecha_filter_div2',
                options: {
                    filterColumnIndex: 0,
                    useFormattedValue: true,
                    ui: {
                        caption: 'Elija los días que desea mostrar',
                        //selectedValuesLayout: 'belowStacked',
                        selectedValuesLayout: 'aside',
                        label: 'Filtrar fechas a mostrar individualmente',
                        labelStacking: 'vertical'
                    }
                }
              }); 
              
              var rangeFilter = new google.visualization.ControlWrapper({
                'controlType': 'ChartRangeFilter',
                'containerId': 'filter_div2',
                options: {
                    filterColumnIndex: 0,
                    ui: {
                        chartType: 'LineChart',
                        chartOptions: {
                            colors: ['#e2975d', '#447c69', '#a34974', '#e9d78e', '#4e2472', '#7c9fb0'],
                            backgroundColor: {'stroke':'#aaa', 'strokeWidth':2},
                            'width':1100,
                            'height':60,
                        },
                        snapToData: true
                    }
                }
              }); 
              
              var titulo = "Ref: "+"<?php echo $ref_makito." / ".$nombre_makito; ?> - Variaciones de Stock de los Competidores";
              
              var mainLineChart = new google.visualization.ChartWrapper({
                'chartType': 'LineChart',
                'containerId': 'chart_div2',
                'options': {
                      title: titulo,
                      titleTextStyle: {
                        color: '#133b12',
                        fontSize: 18,
                        bold: false
                      },
                      'width':1100,
                      'height':500,
                      //Descomentar la siguiente línea si se quiere una gráfica con splines y no con líneas quebradas
                      //curveType: 'function',
                      colors: ['#e2975d', '#447c69', '#a34974', '#e9d78e', '#4e2472', '#7c9fb0'],
                      lineWidth: 3,
                      //lineDashStyle: [3, 2],
                      crosshair: { trigger: 'both' },
                      //pointShape: 'star',
                      pointSize: 7,
                      tooltip: {
                          trigger: 'both',
                          showColorCode: 'true'
                      },
                      legend: {
                          position: 'top',
                          alignment: 'center'
                      },
                      //Activando la opción explorer, se permite hacer zoom en la gráfica (arrastrando la ventana a ampliar) y restablecer el nivel (con el botón derecho del ratón), pero el texto de leyenda del eje horizontal no se mostrará ni inclinado ni con el formato d-M-yy deseado
                      /*explorer: {
                          actions: ['dragToZoom', 'rightClickToReset'],
                          keepInBounds: true
                      },*/
                      hAxis: {
                          title: 'Días',
                          titleTextStyle: {fontSize: 18, color: '#666', bold: true, italic: false},
                          slantedText: true,
                          slantedTextAngle: 60,
                          //showTextEvery: 2,
                          textStyle: {fontSize: 12, color: '#666'},
                          gridlines: {
                            count: 31
                          },
                          format:'d-M-yy'
                      },
                      vAxis: {
                          title: 'Stock',
                          titleTextStyle: {fontSize: 18, color: '#666', bold: true, italic: false},
                          gridlines: {color: '#999', count: 5},
                          minorGridlines: {color: '#eee', count: 5},
                          textStyle: {color: '#666', bold: true}
                      },
                      //vAxis: { title: 'Stock', scaleType: 'log' }, // Eje vertical en escala logarítmica, no lineal. En este caso no funciona la animación
                      animation:{
                        duration: 1000,
                        easing: 'out',
                        startup: true
                      },
                      selectionMode: 'multiple',
                      backgroundColor: {'stroke':'#aaa', 'strokeWidth':2}
                }
              });
              
              dashboard.bind([rangeFilter, fecha_disc_Filter], mainLineChart);
              
              //dashboard.draw(data);
              dashboard.draw(view)
              
              //Código jQuery para exportar lo datos como CSV, delimitados por,
              $('#export').click(function () {
                var csvFormattedDataTable = google.visualization.dataTableToCsv(data);
                var encodedUri = 'data:application/csv;charset=utf-8,' + encodeURIComponent(csvFormattedDataTable);
                this.href = encodedUri;
                this.download = 'table-data.csv';
                this.target = '_blank';
              });
          }
            
        </script>
        
        
        <div class="container" id="cuerpo_home">
            
            <!--Tabla de correspondencias entre referencias-->
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-bordered table-condensed table-striped" id="correspondencias">
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
                            <th class="info celdacentradahor celdacentradavert">Frecuencia</th>
                            <th class="info celdacentradahor celdacentradavert">Editar</th>
                        </tr>

                        <tr>
                            <td class="celdacentradahor celdacentradavert">
                                <?php
                                //if(@fopen('http://www.makito.es/imagenes/0-7999/'.$articulo_ref.'.jpg',"r")==true) {
                                if(file_exists('img/articulo/'.$ref_makito.'p.jpg')) {
                                    ?>
                                    <img src="img/articulo/<?php echo $ref_makito; ?>p.jpg" width="50px" class="enmarcado3 miniatura">
                                    <?php
                                } else {
                                    ?>
                                    <img src="img/varios/noimg.jpg" width="50px" class="enmarcado3 miniatura">
                                    <?php
                                }
                                ?>  
                            </td>
                            <td class="celdacentradahor celdacentradavert nombre_makito">
                                <span><strong><?php echo $nombre_makito; ?></strong></span>
                            </td>
                            <td class="celdacentradahor celdacentradavert celda_homogenea">
                                <span class="articulo_ref"><?php echo $ref_makito; ?></span>
                            </td>
                            <td class="celdacentradahor celdacentradavert mas_info celda_homogenea">
                                <span><?php echo $ref_cifra; ?></span>
                                <?php
                                if($ref_cifra != "") {
                                    ?>
                                    <br>
                                    <a href="../inc/muestra_web_competidor.inc.php?ref=<?php echo $ref_cifra; ?>&comp=cifra" target="_blank">
                                        <button type="button" class="btn btn-muted btn-xs contorneado"><span class="glyphicon glyphicon-link" aria-hidden="true"></span></button>
                                    </a>
                                    <?php
                                }
                                ?>
                            </td>
                            <td class="celdacentradahor celdacentradavert oculto celda_homogenea">
                                <span><?php echo $ref_cifra_extras; ?></span>
                            </td>
                            <td class="celdacentradahor celdacentradavert oculto celda_homogenea">
                                <span><?php echo $ref_cifra_excluir; ?></span>
                            </td>
                            <td class="celdacentradahor celdacentradavert mas_info celda_homogenea">
                                <span><?php echo $ref_giving; ?></span>
                                <?php
                                if($ref_giving != "") {
                                    ?>
                                    <br>
                                    <a href="../inc/muestra_web_competidor.inc.php?ref=<?php echo $ref_giving; ?>&comp=giving" target="_blank">
                                        <button type="button" class="btn btn-muted btn-xs contorneado"><span class="glyphicon glyphicon-link" aria-hidden="true"></span></button>
                                    </a>
                                    <?php
                                }
                                ?>
                            </td>
                            <td class="celdacentradahor celdacentradavert oculto celda_homogenea">
                                <span><?php echo $ref_giving_extras; ?></span>
                            </td>
                            <td class="celdacentradahor celdacentradavert oculto celda_homogenea">
                                <span><?php echo $ref_giving_excluir; ?></span>
                            </td>
                            <td class="celdacentradahor celdacentradavert mas_info celda_homogenea">
                                <span><?php echo $ref_mob; ?></span>
                                <?php
                                if($ref_mob != "") {
                                    ?>
                                    <br>
                                    <a href="../inc/muestra_web_competidor.inc.php?ref=<?php echo $ref_mob; ?>&comp=mob" target="_blank">
                                        <button type="button" class="btn btn-muted btn-xs contorneado"><span class="glyphicon glyphicon-link" aria-hidden="true"></span></button>
                                    </a>
                                    <?php
                                }
                                ?>
                            </td>
                            <td class="celdacentradahor celdacentradavert oculto celda_homogenea">
                                <span><?php echo $ref_mob_extras; ?></span>
                            </td>
                            <td class="celdacentradahor celdacentradavert oculto celda_homogenea">
                                <span><?php echo $ref_mob_excluir; ?></span>
                            </td>
                            <td class="celdacentradahor celdacentradavert mas_info celda_homogenea">
                                <span><?php echo $ref_ps; ?></span>
                                <?php
                                if($ref_ps != "") {
                                    ?>
                                    <br>
                                    <a href="../inc/muestra_web_competidor.inc.php?ref=<?php echo $ref_ps; ?>&comp=ps" target="_blank">
                                        <button type="button" class="btn btn-muted btn-xs contorneado"><span class="glyphicon glyphicon-link" aria-hidden="true"></span></button>
                                    </a>
                                    <?php
                                }
                                ?>
                            </td>
                            <td class="celdacentradahor celdacentradavert oculto celda_homogenea">
                                <span><?php echo $ref_ps_extras; ?></span>
                            </td>
                            <td class="celdacentradahor celdacentradavert oculto celda_homogenea">
                                <span><?php echo $ref_ps_excluir; ?></span>
                            </td>
                            <td class="celdacentradahor celdacentradavert mas_info celda_homogenea">
                                <span><?php echo $ref_pf; ?></span>
                                <?php
                                if($ref_pf != "") {
                                    ?>
                                    <br>
                                    <a href="../inc/muestra_web_competidor.inc.php?ref=<?php echo $ref_pf; ?>&comp=pf" target="_blank">
                                        <button type="button" class="btn btn-muted btn-xs contorneado"><span class="glyphicon glyphicon-link" aria-hidden="true"></span></button>
                                    </a>
                                    <?php
                                }
                                ?>
                            </td>
                            <td class="celdacentradahor celdacentradavert oculto celda_homogenea">
                                <span><?php echo $ref_pf_extras; ?></span>
                            </td>
                            <td class="celdacentradahor celdacentradavert oculto celda_homogenea">
                                <span><?php echo $ref_pf_excluir; ?></span>
                            </td>
                            <td class="celdacentradahor celdacentradavert mas_info celda_homogenea">
                                <span><?php echo $ref_ggoya; ?></span>
                                <?php
                                if($ref_ggoya != "") {
                                    ?>
                                    <br>
                                    <a href="../inc/muestra_web_competidor.inc.php?ref=<?php echo $ref_ggoya; ?>&comp=ggoya" target="_blank">
                                        <button type="button" class="btn btn-muted btn-xs contorneado"><span class="glyphicon glyphicon-link" aria-hidden="true"></span></button>
                                    </a>
                                    <?php
                                }
                                ?>
                            </td>
                            <td class="celdacentradahor celdacentradavert oculto celda_homogenea">
                                <span><?php echo $ref_ggoya_extras; ?></span>
                            </td>
                            <td class="celdacentradahor celdacentradavert oculto celda_homogenea">
                                <span><?php echo $ref_ggoya_excluir; ?></span>
                            </td>

                            <?php
                            if($ref_cifra == "" and $ref_giving == "" and $ref_mob == "" and $ref_ps == "" and $ref_pf == "" and $ref_ggoya == "") {
                                ?>
                                <td class="celdacentradahor celdacentradavert">
                                    <a href="#" class="no_link">
                                        <button type="button" class="btn btn-muted btn-sm"><span class="glyphicon glyphicon-screenshot" aria-hidden="true"></span></button>
                                    </a>  
                                </td>
                                <?php
                            } else {
                                ?>
                                <td class="celdacentradahor celdacentradavert">
                                    <a href="inicio.php?id=stock_actual&ref=<?php echo $ref_makito; ?>" target="_blank">
                                        <button type="button" class="btn btn-warning btn-sm"><span class="glyphicon glyphicon-screenshot" aria-hidden="true"></span></button>
                                    </a>  
                                </td>
                                <?php
                            }
                            ?>
                            <td class="celdacentradahor celdacentradavert celda_homogenea">
                                <span><?php echo $frecuencia; ?></span>
                            </td>
                            <td class="celdacentradahor celdacentradavert">
                                <a href="inicio.php?id=modificar_referencia&ref=<?php echo $ref_makito; ?>">
                                    <button type="button" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></button>
                                </a>  
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <!--FIN de Tabla de correspondencias entre referencias-->    
            
            <hr>
               
            <!--DIVs para mostrar los diagramas de resultado-->
            <div class="row text-center">
                <div class="col-md-12">
                    <h5 class="text-center sin_margen_sup">
                        <strong>Ocultar Competidores en la Gráfica <small>(desmarque los que no quiera mostrar, si desmarca todos se recargará de nuevo la gráfica completa)</small></strong>
                    </h5>
                </div>
                <div class="col-md-12">
                    <form class="row form-inline">
                        <?php
                        if($ref_cifra != "") {
                            ?>
                            <div class="col-md-2 checkbox">
                                <input class="chbx_mostrar" type="checkbox" name="cifra_chbx" id="cifra_chbx" value="cifra_check" checked> CIFRA
                            </div>
                            <?php
                        }

                        if($ref_giving != "") {
                            ?>
                            <div class="col-md-2 checkbox">
                                <input class="chbx_mostrar" type="checkbox" name="giving_chbx" id="giving_chbx" value="giving_check" checked> GIVING
                            </div>
                            <?php
                        }

                        if($ref_mob != "") {
                            ?>
                            <div class="col-md-2 checkbox">
                                <input class="chbx_mostrar" type="checkbox" name="mob_chbx" id="mob_chbx" value="mob_check" checked> MOB
                            </div>
                            <?php
                        }

                        if($ref_ps != "") {
                            ?>
                            <div class="col-md-2 checkbox">
                                <input class="chbx_mostrar" type="checkbox" name="ps_chbx" id="ps_chbx" value="ps_check" checked> PS
                            </div>
                            <?php
                        }

                        if($ref_pf != "") {
                            ?>
                            <div class="col-md-2 checkbox">
                                <input class="chbx_mostrar" type="checkbox" name="pf_chbx" id="pf_chbx" value="pf_check" checked> PF
                            </div>
                            <?php
                        }

                        if($ref_ggoya != "") {
                            ?>
                            <div class="col-md-2 checkbox">
                                <input class="chbx_mostrar" type="checkbox" name="ggoya_chbx" id="ggoya_chbx" value="ggoya_check" checked> GGOYA
                            </div>
                            <?php
                        }
                        ?>
                    </form>
                </div>
            </div>

            <div class="row" id="dashboard_div">
                <div class="col-md-12">
                    <div id="chart_div2">

                    </div>
                    <div id="filter_div2">

                    </div>
                    <div id="fecha_filter_div2" class="fechaDiscFilter">

                    </div>
                    <hr>
                    <div id="exportarCSV" class="text-center">
                        <a id="export" href="#">Descargar datos como CSV</a>
                    </div>
                </div>
                <!--<div class="col-md-12">
                    <div class="row">
                        <div class="fechaDiscFilter">
                            <div class="col-md-4 col-md-offset-4">
                                <div id="fecha_filter_div2">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>-->
            </div>
            <!--FIN de DIVs para mostrar los diagramas de resultado-->     

            <hr>
              
            <!--Tabla de variaciones-->
            <!--<div class="row">
                <div class="col-md-12">
                    <table class="table table-bordered table-condensed table-striped" id="correspondencias">
                        <tr id="cabecera_listado">
                            <th class="info celdacentradahor celdacentradavert celda_homogenea"><i class="glyphicon glyphicon-calendar"></i> Fecha</th>
                            <th class="info celdacentradahor celdacentradavert celda_homogenea"><i class="glyphicon glyphicon-random"></i> Variación CIFRA</th>
                            <th class="info celdacentradahor celdacentradavert celda_homogenea"><i class="glyphicon glyphicon-random"></i> Variación GIVING</th>
                            <th class="info celdacentradahor celdacentradavert celda_homogenea"><i class="glyphicon glyphicon-random"></i> Variación MOB</th>
                            <th class="info celdacentradahor celdacentradavert celda_homogenea"><i class="glyphicon glyphicon-random"></i> Variación PS</th>
                            <th class="info celdacentradahor celdacentradavert celda_homogenea"><i class="glyphicon glyphicon-random"></i> Variación PF</th>
                            <th class="info celdacentradahor celdacentradavert celda_homogenea"><i class="glyphicon glyphicon-random"></i> Variación GGOYA</th>
                        </tr>
                        
                        <?php
                        $dia = 0;                
                        
                        while ($dia <= ($dias_diferencia+1)) {
                            
                            $fecha_dia = date('Y-m-d', strtotime($fecha_inicio_datos) + (86400 * $dia));
                            
                            if ($dia == 0) {
                                $variacion_cifra = 0;
                                $variacion_giving = 0;
                                $variacion_mob = 0;
                                $variacion_ps = 0;
                                $variacion_pf = 0;
                                $variacion_ggoya = 0;
                                
                            }else {
                                $consulta_stocks_dia = "SELECT * FROM stock_makito_vs_todos
                                                            WHERE stock_makito_vs_todos_fecha LIKE '$fecha_dia'
                                                                AND stock_makito_vs_todos_ref_makito LIKE '$ref'
                                                            ORDER BY stock_makito_vs_todos_hora DESC LIMIT 1;";
                                $resultado_consulta_stocks_dia = $bd->Execute($consulta_stocks_dia);
                                
                                $stock_cifra_dia = $resultado_consulta_stocks_dia->fields['stock_makito_vs_todos_stock_cifra'];
                                $stock_giving_dia = $resultado_consulta_stocks_dia->fields['stock_makito_vs_todos_stock_giving'];
                                $stock_mob_dia = $resultado_consulta_stocks_dia->fields['stock_makito_vs_todos_stock_mob'];
                                $stock_ps_dia = $resultado_consulta_stocks_dia->fields['stock_makito_vs_todos_stock_ps'];
                                $stock_pf_dia = $resultado_consulta_stocks_dia->fields['stock_makito_vs_todos_stock_pf'];
                                $stock_ggoya_dia = $resultado_consulta_stocks_dia->fields['stock_makito_vs_todos_stock_ggoya'];
                                
                                
                                $fecha_dia_anterior = date('Y-m-d', strtotime($fecha_inicio_datos) + (86400 * ($dia - 1)));
                                
                                $consulta_stocks_dia_anterior = "SELECT * FROM stock_makito_vs_todos
                                                            WHERE stock_makito_vs_todos_fecha LIKE '$fecha_dia_anterior'
                                                                AND stock_makito_vs_todos_ref_makito LIKE '$ref'
                                                            ORDER BY stock_makito_vs_todos_hora DESC LIMIT 1;";
                                $resultado_consulta_stocks_dia_anterior = $bd->Execute($consulta_stocks_dia_anterior);
                                
                                $stock_cifra_dia_anterior = $resultado_consulta_stocks_dia_anterior->fields['stock_makito_vs_todos_stock_cifra'];
                                $stock_giving_dia_anterior = $resultado_consulta_stocks_dia_anterior->fields['stock_makito_vs_todos_stock_giving'];
                                $stock_mob_dia_anterior = $resultado_consulta_stocks_dia_anterior->fields['stock_makito_vs_todos_stock_mob'];
                                $stock_ps_dia_anterior = $resultado_consulta_stocks_dia_anterior->fields['stock_makito_vs_todos_stock_ps'];
                                $stock_pf_dia_anterior = $resultado_consulta_stocks_dia_anterior->fields['stock_makito_vs_todos_stock_pf'];
                                $stock_ggoya_dia_anterior = $resultado_consulta_stocks_dia_anterior->fields['stock_makito_vs_todos_stock_ggoya'];
                                
                                $variacion_cifra = $stock_cifra_dia - $stock_cifra_dia_anterior;
                                $variacion_giving = $stock_giving_dia - $stock_giving_dia_anterior;
                                $variacion_mob = $stock_mob_dia - $stock_mob_dia_anterior;
                                $variacion_ps = $stock_ps_dia - $stock_ps_dia_anterior;
                                $variacion_pf = $stock_pf_dia - $stock_pf_dia_anterior;
                                $variacion_ggoya = $stock_ggoya_dia - $stock_ggoya_dia_anterior;
                            }
                            
                            ?>
                            
                            <tr>
                                <td class="celdacentradahor celdacentradavert celda_homogenea">
                                    <span class="articulo_ref"><?php echo $fecha_dia; ?></span>
                                </td>
                                <td class="text-right celdacentradavert celda_homogenea indentada_dcha">
                                    <?php
                                    if ($variacion_cifra > 0) {
                                        ?>
                                        <span class="articulo_ref" style="color: green"><?php echo number_format($variacion_cifra, 0, ',', '.'); ?></span>
                                        <?php
                                    } else if ($variacion_cifra < 0) {
                                        ?>
                                        <span class="articulo_ref" style="color: red"><?php echo number_format($variacion_cifra, 0, ',', '.'); ?></span>
                                        <?php
                                    } else {
                                        ?>
                                        <span class="articulo_ref"><?php echo $variacion_cifra; ?></span>
                                        <?php
                                    }
                                    ?>
                                </td>
                                <td class="text-right celdacentradavert celda_homogenea indentada_dcha">
                                    <?php
                                    if ($variacion_giving > 0) {
                                        ?>
                                        <span class="articulo_ref" style="color: green"><?php echo number_format($variacion_giving, 0, ',', '.'); ?></span>
                                        <?php
                                    } else if ($variacion_giving < 0) {
                                        ?>
                                        <span class="articulo_ref" style="color: red"><?php echo number_format($variacion_giving, 0, ',', '.'); ?></span>
                                        <?php
                                    } else {
                                        ?>
                                        <span class="articulo_ref"><?php echo $variacion_giving; ?></span>
                                        <?php
                                    }
                                    ?>
                                </td>
                                <td class="text-right celdacentradavert celda_homogenea indentada_dcha">
                                    <?php
                                    if ($variacion_mob > 0) {
                                        ?>
                                        <span class="articulo_ref" style="color: green"><?php echo number_format($variacion_mob, 0, ',', '.'); ?></span>
                                        <?php
                                    } else if ($variacion_mob < 0) {
                                        ?>
                                        <span class="articulo_ref" style="color: red"><?php echo number_format($variacion_mob, 0, ',', '.'); ?></span>
                                        <?php
                                    } else {
                                        ?>
                                        <span class="articulo_ref"><?php echo $variacion_mob; ?></span>
                                        <?php
                                    }
                                    ?>
                                </td>
                                <td class="text-right celdacentradavert celda_homogenea indentada_dcha">
                                    <?php
                                    if ($variacion_ps > 0) {
                                        ?>
                                        <span class="articulo_ref" style="color: green"><?php echo number_format($variacion_ps, 0, ',', '.'); ?></span>
                                        <?php
                                    } else if ($variacion_ps < 0) {
                                        ?>
                                        <span class="articulo_ref" style="color: red"><?php echo number_format($variacion_ps, 0, ',', '.'); ?></span>
                                        <?php
                                    } else {
                                        ?>
                                        <span class="articulo_ref"><?php echo $variacion_ps; ?></span>
                                        <?php
                                    }
                                    ?>
                                </td>
                                <td class="text-right celdacentradavert celda_homogenea indentada_dcha">
                                    <?php
                                    if ($variacion_pf > 0) {
                                        ?>
                                        <span class="articulo_ref" style="color: green"><?php echo number_format($variacion_pf, 0, ',', '.'); ?></span>
                                        <?php
                                    } else if ($variacion_pf < 0) {
                                        ?>
                                        <span class="articulo_ref" style="color: red"><?php echo number_format($variacion_pf, 0, ',', '.'); ?></span>
                                        <?php
                                    } else {
                                        ?>
                                        <span class="articulo_ref"><?php echo $variacion_pf; ?></span>
                                        <?php
                                    }
                                    ?>
                                </td>
                                <td class="text-right celdacentradavert celda_homogenea indentada_dcha">
                                    <?php
                                    if ($variacion_ggoya > 0) {
                                        ?>
                                        <span class="articulo_ref" style="color: green"><?php echo number_format($variacion_ggoya, 0, ',', '.'); ?></span>
                                        <?php
                                    } else if ($variacion_ggoya < 0) {
                                        ?>
                                        <span class="articulo_ref" style="color: red"><?php echo number_format($variacion_ggoya, 0, ',', '.'); ?></span>
                                        <?php
                                    } else {
                                        ?>
                                        <span class="articulo_ref"><?php echo $variacion_ggoya; ?></span>
                                        <?php
                                    }
                                    ?>
                                </td>
                            </tr>
                            <?php
                            
                            $dia++;
                        }
                        ?>
                    </table>
                </div>
            </div>-->
            <!--FIN de Tabla de variaciones-->
            
            <hr>
            
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <h5 class="text-center enmarcado2">Selector para Nueva Consulta de Variaciones de Stocks de los Competidores</h5>
                </div>
            </div>

            <!--Bloque para permitir elegir la referencia a modificar-->
            <div class="row">
                <div class="col-md-3 col-md-offset-1 text-center">
                    <h4 class="enmarcado5">Referencia</h4>
                </div>
                <div class="col-md-4">
                    <form class="form-horizontal" name="selector_ref2" method="post" action="">
                        <div class="form-group">                
                            <select class="form-control" name="filtro_referencia" id="filtro_referencia" required>
                                <option class="tenue" value="">Elija una Referencia para Consultar las Variaciones</option>
                                <option disabled>─────────────────────────</option>

                                <?php

                                $resultado_consulta_referencias_con_correspondencias->MoveFirst(); 

                                while (!($resultado_consulta_referencias_con_correspondencias->EOF)){
                                    $ref_articulo = $resultado_consulta_referencias_con_correspondencias->fields['referencias_makito_vs_competidores_ref_makito'];
                                    $nombre_articulo = $resultado_consulta_referencias_con_correspondencias->fields['referencias_makito_vs_competidores_nombre_makito'];

                                    echo '<option value="'.$ref_articulo.'">'.$ref_articulo.' - '.$nombre_articulo.'</option>';

                                    $resultado_consulta_referencias_con_correspondencias->MoveNext();    
                                }

                                ?>

                            </select>
                        </div>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary btn-block">
                        <span class="glyphicon glyphicon-hand-right" aria-hidden="true"></span> Consultar Variaciones de Stocks
                    </button>
                    </form>
                </div>            
            </div>
            <!--FIN de Bloque para permitir elegir la referencia a modificar-->

            <hr>
            
        </div>
         
        <!-### SCRIPTS JS Y jQuery ###->
        <script src="../js/jquery.js"></script>

        <script>

            $(document).ready(function() {

                $(".no_link").click(function(event) {
                    event.preventDefault();
                });

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
                
                //Script que comprueba los checkbox de competidores que están marcados y muestra sólo en la gráfica los datos de esos competidores
                $(".chbx_mostrar").change(function() {
                    
                    var num_checked = $(".chbx_mostrar").length;
                    
                    if($("#cifra_chbx").prop("checked") == false) {
                        $("#cifra_chbx").parent().hide(1000);
                        view.hideColumns([1]);
                        num_checked--;
                    }
                    
                    if($("#giving_chbx").prop("checked") == false) {
                        $("#giving_chbx").parent().hide(1000);
                        view.hideColumns([2]);
                        num_checked--;
                    }
                    
                    if($("#mob_chbx").prop("checked") == false) {
                        $("#mob_chbx").parent().hide(1000);
                        view.hideColumns([3]);
                        num_checked--;
                    }
                    
                    if($("#ps_chbx").prop("checked") == false) {
                        $("#ps_chbx").parent().hide(1000);
                        view.hideColumns([4]);
                        num_checked--;
                    }
                    
                    if($("#pf_chbx").prop("checked") == false) {
                        $("#pf_chbx").parent().hide(1000);
                        view.hideColumns([5]);
                        num_checked--;
                    }
                    
                    if($("#ggoya_chbx").prop("checked") == false) {
                        $("#ggoya_chbx").parent().hide(1000);
                        view.hideColumns([6]);
                        num_checked--;
                    }
                    
                    if(num_checked != 0) {
                        dashboard.draw(view);
                    } else {
                        location.reload();
                    }
                    
                });
                
                //FIN del Script que comprueba los checkbox de competidores que están marcados y muestra sólo en la gráfica los datos de esos competidores

            });

        </script>
          
        <?php
    
    } else { //Si no se ha pasado una referencia por GET, muestro un Select para elegir la referencia a Modificar
        ?>        
        
        <div class="container" id="cuerpo_home">
      
            <div class="row">
                <div class="col-md-12">
                    <h3 class="text-center enmarcado2">Consulta de Variaciones de Stocks de los Competidores</h3>
                </div>
            </div>

            <!--Bloque para permitir elegir la referencia a modificar-->
            <div class="row">
                <div class="col-md-3 col-md-offset-1 text-center">
                    <h4 class="enmarcado5">Referencia</h4>
                </div>
                <div class="col-md-4">
                    <form class="form-horizontal" name="selector_ref1" method="post" action="">
                        <div class="form-group">                
                            <select class="form-control" name="filtro_referencia" id="filtro_referencia" required>
                                <option class="tenue" value="">Elija una Referencia para Consultar las Variaciones</option>
                                <option disabled>─────────────────────────</option>

                                <?php
                                
                                $resultado_consulta_referencias_con_correspondencias->MoveFirst(); 
        
                                while (!($resultado_consulta_referencias_con_correspondencias->EOF)){
                                    $ref_articulo = $resultado_consulta_referencias_con_correspondencias->fields['referencias_makito_vs_competidores_ref_makito'];
                                    $nombre_articulo = $resultado_consulta_referencias_con_correspondencias->fields['referencias_makito_vs_competidores_nombre_makito'];

                                    echo '<option value="'.$ref_articulo.'">'.$ref_articulo.' - '.$nombre_articulo.'</option>';

                                    $resultado_consulta_referencias_con_correspondencias->MoveNext();    
                                }

                                ?>

                            </select>
                        </div>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary btn-block">
                        <span class="glyphicon glyphicon-hand-right" aria-hidden="true"></span> Consultar Variaciones de Stocks
                    </button>
                    </form>
                </div>            
            </div>
            <!--FIN de Bloque para permitir elegir la referencia a modificar-->
            
            <hr>
            
        </div>
        
        <?php
        
    }
    
}

?>