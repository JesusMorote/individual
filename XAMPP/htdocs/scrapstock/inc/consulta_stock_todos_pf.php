<?php

//Desactivo el límite de tiempo de ejecución de un script impuesto por XAMPP (30seg)
//sin que sea necesario realizar un cambio en el php.ini que afectaría a cualquier script que se ejecute en el servidor
set_time_limit(0);

//Incluyo el fichero de conexión para poder acceder a la BD cómodamente mendiante AdoDB
include_once('conexion.php');

//Incluyo el fichero que permite consultar el stock de una referencia dada de PF
include_once('consulta_stock_pf.inc.php');

//Obtengo la fecha actual, en el momento en que se ha iniciado la ejecución de consulta_stock_todos_pf.php
$fecha_actual_inicio = date('Y-m-d');
//Obtengo la hora actual
$hora_actual_inicio = date('H:i:s');

//Obtengo el día de la semana actual
$dia_semana_actual = date('N');

//Obtengo las referencias almacenadas en la tabla refs_pf que no tengan correspondencia con una referencia de makito
$consulta_referencias = "SELECT DISTINCT refs_pf_referencia FROM refs_pf
                                WHERE refs_pf_referencia NOT IN
                                    (SELECT DISTINCT referencias_makito_vs_competidores_pf FROM referencias_makito_vs_competidores
                                        WHERE referencias_makito_vs_competidores_pf NOT LIKE '');";

$resultado_consulta_referencias = $bd->Execute($consulta_referencias);

$num_referencias = 0;

while (!($resultado_consulta_referencias->EOF)) {
    $num_referencias++;
    $resultado_consulta_referencias->moveNext();
}

$lote_referencias = ceil($num_referencias / 6);

//Obtengo todas las referencias, del lote correspondiente al día de la semana actual
$offset = $lote_referencias * ((int)$dia_semana_actual - 1);

$consulta_lote = "SELECT DISTINCT refs_pf_referencia
                    FROM refs_pf
                        WHERE refs_pf_referencia NOT IN
                                    (SELECT DISTINCT referencias_makito_vs_competidores_pf FROM referencias_makito_vs_competidores
                                        WHERE referencias_makito_vs_competidores_pf NOT LIKE '')
                    ORDER BY refs_pf_referencia ASC
                    LIMIT $lote_referencias OFFSET $offset;";

/*
//CONSULTA PARA PODER OBTENER UN RANGO DISTINTO DEL LOTE DE REFERENCIAS
//(por ejemplo para volver a capturar stocks no almacenados por un corte de línea del pincho USB)
$consulta_lote = "SELECT DISTINCT refs_pf_referencia
                    FROM refs_pf
                        WHERE refs_pf_referencia NOT IN
                                    (SELECT DISTINCT referencias_makito_vs_competidores_pf FROM referencias_makito_vs_competidores
                                        WHERE referencias_makito_vs_competidores_pf NOT LIKE '')
                    ORDER BY refs_pf_referencia ASC
                    LIMIT 35 OFFSET 1377;";
*/

$resultado_consulta_lote = $bd->Execute($consulta_lote);


//CREO UN FICHERO DE LOG EN EL QUE ALMACENAR LA FECHA Y HORA (de inicio),
//CADA UNO DE LAS REFERENCIAS QUE SE VAYAN CONSULTANDO Y DE LAS QUE SE VAYA ALMACENANDO EL STOCK
//Y, POR ÚLTIMO, LA FECHA Y HORA EN QUE SE FINALICE TODO EL PROCESO
$nombre_fichero_log = "../log/log_stock_todos_pf_".$fecha_actual_inicio."_".date('H-i-s').".txt";
$log = fopen($nombre_fichero_log, "w");
fwrite($log, "CONSULTA DE STOCK DE TODOS LOS ARTÍCULOS DE PF:\r\nProceso iniciado el ".$fecha_actual_inicio.", a las ".$hora_actual_inicio."\r\n***************************************************************************\r\n\r\n");
fclose($log);

//Recorro el resultado de la consulta anterior, referencia a referencia
while (!($resultado_consulta_lote->EOF)) {
    //Obtengo la fecha actual, en el momento en que se ha iniciado la consulta de stocks para esta referencia
    $fecha_actual_inicio_ref = date('Y-m-d');
    //Obtengo la hora actual
    $hora_actual_inicio_ref = date('H:i:s');
    
    $resultado_proceso_ref = "OK";    
    
    $ref_pf = $resultado_consulta_lote->fields['refs_pf_referencia'];
    
    //Realizo las consultas de stock AGREGADO de las distintas referencias en PF del lote
    
        //CONSULTO Y ALMACENO EL STOCK EN PF (si hay correspondencia con alguna de sus referencias)
        if($ref_pf != "") {
            try {
                $stock_pf = consulta_stock_pf($ref_pf);
                
                if($stock_pf == -2) { //Se ha producido un error al consultar el stock de esa referencia
                    $resultado_proceso_ref = "ERROR";
                
                    $log = fopen($nombre_fichero_log, "a");
                    fwrite($log, "#### ERROR al procesar la ref.".$ref_pf." - ".$ref_pf." (PF)\r\n");
                    fclose($log);
                }
                
            } catch (Exception $e) {
                $resultado_proceso_ref = "ERROR";
                
                $log = fopen($nombre_fichero_log, "a");
                fwrite($log, "#### ERROR al procesar la ref.".$ref_pf." - ".$ref_pf." (PF)\r\n");
                fclose($log);
            }
            
            //INSERTO EL RESULTADO DE LA CONSULTA DE STOCK EN LA BD
            //Paso a almacenar el valor de stock de esa referencia en la Base de Datos (así aprovecho el acceso a la web para hacer scrapping)
            $consulta_inserta_stock_pf = "INSERT INTO stock_pf
                                               (stock_pf_ref,
                                                stock_pf_fecha,
                                                stock_pf_hora,
                                                stock_pf_stock)
                                            VALUES
                                               ('$ref_pf',
                                                '$fecha_actual_inicio_ref',
                                                '$hora_actual_inicio_ref',
                                                '$stock_pf');";
            $bd->Execute($consulta_inserta_stock_pf);
            
        } else {
            $stock_pf = -1;
        }
        
        
        //He establecido valores de control (-1) para los casos en que no se disponga del dato de stock
        //para luego si recupero -1 saber que se trata de que el dato no estaba disponible cuando se guardó el registro
    
    //Obtengo la fecha actual, en el momento en que se ha finalizado la consulta de stocks para esta referencia
    $fecha_actual_fin_ref = date('Y-m-d');
    //Obtengo la hora actual
    $hora_actual_fin_ref = date('H:i:s');
    
    //ALMACENO EN UN FICHERO DE LOG, PREVIAMENTE CREADO, CADA UNA DE LAS OPERACIONES REALIZADAS
    //Y, CONVENDRÍA ALMACENAR LOS ERRORES SI ES QUE SE PRODUCEN
    $log = fopen($nombre_fichero_log, "a");
    fwrite($log, "Ref: ".$ref_pf." / Inicio: ".$fecha_actual_inicio_ref." - ".$hora_actual_inicio_ref." / Fin: ".$fecha_actual_fin_ref." - ".$hora_actual_fin_ref." / ".$resultado_proceso_ref."\r\n");
    fclose($log);
    
    //Paso a la siguiente referencia
    $resultado_consulta_lote->MoveNext();
}

//Obtengo la fecha actual, en el momento en que se ha finalizado la ejecución de la primera vuelta de consulta_stock_frec_semanal.php
$fecha_actual_fin = date('Y-m-d');
//Obtengo la hora actual
$hora_actual_fin = date('H:i:s');

//ALMACENO EN EL FICHERO DE LOG, PREVIAMENTE CREADO, LA FECHA Y HORA DE FINALIZACIÓN DEL PROCESO
$log = fopen($nombre_fichero_log, "a");
fwrite($log, "\r\n***************************************************************************\r\nCONSULTA DE STOCK DE TODOS LOS ARTÍCULOS DE PF:\r\nProceso finalizado el ".$fecha_actual_fin.", a las ".$hora_actual_fin."\r\n");
fclose($log);



//REALIZO UNA "SEGUNDA VUELTA" E INTENTO ALMACENAR DE NUEVO LOS STOCKS DE AQUELLAS REFERENCIAS PARA LAS QUE SE HAYA PRODUCIDO UN ERROR
//Las que han devuelto $num_unidades = -2

//Obtengo todas las referencias para las que se ha obtenido stock -2 entre las fechas y horas de la "Primera Vuelta"
$consulta_referencias_erroneas = "SELECT * FROM stock_pf
                                    WHERE stock_pf_stock = -2
                                        AND (stock_pf_fecha BETWEEN '$fecha_actual_inicio' AND '$fecha_actual_fin')
                                        AND (stock_pf_hora BETWEEN '$hora_actual_inicio' AND '$hora_actual_fin');";

$referencias_erroneas = $bd->Execute($consulta_referencias_erroneas);

if(count($referencias_erroneas) != 0) {
    
    //ALMACENO EN EL FICHERO DE LOG, PREVIAMENTE CREADO, LOS DATOS RECOGIDOS EN LA SEGUNDA VUELTA
    $log = fopen($nombre_fichero_log, "a");
    fwrite($log, "\r\nCONSULTA DE STOCK DE TODOS LOS ARTÍCULOS DE PF:\r\nReferencias de las que no se ha podido obtener stock de competidores por Errores de Proceso\r\nResultados del Segundo Intento\r\nProceso iniciado el ".$fecha_actual_fin.", a las ".$hora_actual_fin."\r\n***************************************************************************\r\n\r\n");
    fclose($log);
    
    while (!($referencias_erroneas->EOF)) {
    
        $referencia_pf_con_errores = $referencias_erroneas->fields['stock_pf_ref'];
        $referencia_pf_con_errores_stock_pf_almacenado = $referencias_erroneas->fields['stock_pf_stock'];
        
        //Obtengo la fecha actual, en el momento en que se ha iniciado la consulta de stocks para esta referencia
        $fecha_actual_inicio_ref = date('Y-m-d');
        //Obtengo la hora actual
        $hora_actual_inicio_ref = date('H:i:s');

        $resultado_proceso_ref = "OK";    

        $ref_pf = $referencia_pf_con_errores;
        
        //CONSULTO Y ALMACENO DE NUEVO EL STOCK EN PF (si su stock almacenado en la primera vuelta es -2)
        if($referencia_pf_con_errores_stock_pf_almacenado == -2) {
            try {
                $stock_pf = consulta_stock_pf($ref_pf);
                
                if($stock_pf == -2) { //Se ha producido un error al consultar el stock de esa referencia
                    $resultado_proceso_ref = "ERROR";
                
                    $log = fopen($nombre_fichero_log, "a");
                    fwrite($log, "#### ERROR al procesar la ref.".$ref_pf." - ".$ref_pf." (PF)\r\n");
                    fclose($log);
                }
                
            } catch (Exception $e) {
                $resultado_proceso_ref = "ERROR";
                
                $log = fopen($nombre_fichero_log, "a");
                fwrite($log, "#### ERROR al procesar la ref.".$ref_pf." - ".$ref_pf." (PF)\r\n");
                fclose($log);
            }
            
            //INSERTO EL RESULTADO DE LA CONSULTA DE STOCK EN LA BD
            //Paso a almacenar el valor de stock de esa referencia en la Base de Datos (así aprovecho el acceso a la web para hacer scrapping)
            $consulta_inserta_stock_pf = "INSERT INTO stock_pf
                                               (stock_pf_ref,
                                                stock_pf_fecha,
                                                stock_pf_hora,
                                                stock_pf_stock)
                                            VALUES
                                               ('$ref_pf',
                                                '$fecha_actual_inicio_ref',
                                                '$hora_actual_inicio_ref',
                                                '$stock_pf');";
            $bd->Execute($consulta_inserta_stock_pf);
        }
    
    
        //Obtengo la fecha actual, en el momento en que se ha finalizado la consulta de stocks para esta referencia
        $fecha_actual_fin_ref = date('Y-m-d');
        //Obtengo la hora actual
        $hora_actual_fin_ref = date('H:i:s');

        //ALMACENO EN UN FICHERO DE LOG, PREVIAMENTE CREADO, CADA UNA DE LAS OPERACIONES REALIZADAS
        //Y, CONVENDRÍA ALMACENAR LOS ERRORES SI ES QUE SE PRODUCEN
        $log = fopen($nombre_fichero_log, "a");
        fwrite($log, "Ref: ".$ref_pf." / Inicio: ".$fecha_actual_inicio_ref." - ".$hora_actual_inicio_ref." / Fin: ".$fecha_actual_fin_ref." - ".$hora_actual_fin_ref." / ".$resultado_proceso_ref."\r\n");
        fclose($log);
        
        //Paso a la siguiente referencia con errores en el proceso durante la primera vuelta
        $referencias_erroneas->MoveNext(); 
    }
    
    //Obtengo la fecha actual, en el momento en que se ha finalizado la ejecución de la segunda vuelta consulta_stock_frec_diaria.php
    $fecha_actual_fin2 = date('Y-m-d');
    //Obtengo la hora actual
    $hora_actual_fin2 = date('H:i:s');

    //ALMACENO EN EL FICHERO DE LOG, PREVIAMENTE CREADO, LA FECHA Y HORA DE FINALIZACIÓN DEL PROCESO
    $log = fopen($nombre_fichero_log, "a");
    fwrite($log, "\r\n***************************************************************************\r\nCONSULTA DE STOCK DE TODOS LOS ARTÍCULOS DE PF:\r\nProceso finalizado el ".$fecha_actual_fin2.", a las ".$hora_actual_fin2."\r\n");
    fclose($log);
    
}

//FIN DE LA "SEGUNDA VUELTA"...



//Muestro por pantalla el contenido almacenado durante el proceso en el fichero de log
$log = fopen($nombre_fichero_log, "r");
$contenido_log = fread($log, filesize($nombre_fichero_log));
fclose($log);

echo str_replace("\r\n", "<br>", $contenido_log);

?>