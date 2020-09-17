<?php

//Desactivo el límite de tiempo de ejecución de un script impuesto por XAMPP (30seg)
//sin que sea necesario realizar un cambio en el php.ini que afectaría a cualquier script que se ejecute en el servidor
set_time_limit(0);

//Incluyo el fichero de conexión para poder acceder a la BD cómodamente mendiante AdoDB
include_once('conexion.php');

//Incluyo los ficheros que permiten consultar el stock de una referencia dada de los distintos competidores
include_once('consulta_stock_cifra.inc.php');
include_once('consulta_stock_giving.inc.php');
include_once('consulta_stock_mob.inc.php');
include_once('consulta_stock_ps.inc.php');
include_once('consulta_stock_pf.inc.php');
include_once('consulta_stock_ggoya.inc.php');

//Obtengo la fecha actual, en el momento en que se ha iniciado la ejecución de consulta_stock_frec_diaria.php
$fecha_actual_inicio = date('Y-m-d');
//Obtengo la hora actual
$hora_actual_inicio = date('H:i:s');

//CREO UN FICHERO DE LOG EN EL QUE ALMACENAR LA FECHA Y HORA (de inicio),
//CADA UNO DE LAS REFERENCIAS QUE SE VAYAN CONSULTANDO Y DE LAS QUE SE VAYA ALMACENANDO EL STOCK
//Y, POR ÚLTIMO, LA FECHA Y HORA EN QUE SE FINALICE TODO EL PROCESO
$nombre_fichero_log = "../log/log_stock_frec_diaria_".$fecha_actual_inicio."_".date('H-i-s').".txt";
$log = fopen($nombre_fichero_log, "w");
fwrite($log, "CONSULTA DE STOCK DE ARTÍCULOS CON FRECUENCIA DIARIA:\r\nProceso iniciado el ".$fecha_actual_inicio.", a las ".$hora_actual_inicio."\r\n***************************************************************************\r\n\r\n");
fclose($log);

//Obtengo todas las referencias para las que la frecuencia de actualización se ha establecido como diaria
$consulta_referencias_frec_diaria = "SELECT * FROM referencias_makito_vs_competidores
                                        WHERE referencias_makito_vs_competidores_frecuencia LIKE 'diaria'
                                        ORDER BY referencias_makito_vs_competidores_ref_makito ASC;";

//CONSULTA DE PRUEBA, SOLO CON DOS ARTÍCULOS (8772 Y 9416)
/*$consulta_referencias_frec_diaria = "SELECT * FROM referencias_makito_vs_competidores
                                        WHERE referencias_makito_vs_competidores_ref_makito LIKE '8772'
                                            OR referencias_makito_vs_competidores_ref_makito LIKE '9416'
                                        ORDER BY referencias_makito_vs_competidores_ref_makito ASC;";*/

$referencias_frec_diaria = $bd->Execute($consulta_referencias_frec_diaria);

//Recorro el resultado de la consulta anterior, referencia a referencia
while (!($referencias_frec_diaria->EOF)) {
    //Obtengo la fecha actual, en el momento en que se ha iniciado la consulta de stocks para esta referencia
    $fecha_actual_inicio_ref = date('Y-m-d');
    //Obtengo la hora actual
    $hora_actual_inicio_ref = date('H:i:s');
    
    $resultado_proceso_ref = "OK";    
    
    //Obtengo las correspondencias entre la referencia de makito y las de los competidores
    $ref_makito = $referencias_frec_diaria->fields['referencias_makito_vs_competidores_ref_makito'];
    
    $ref_cifra = $referencias_frec_diaria->fields['referencias_makito_vs_competidores_cifra'];
    $ref_cifra_extras = $referencias_frec_diaria->fields['referencias_makito_vs_competidores_cifra_extras'];
    $ref_cifra_excluir = $referencias_frec_diaria->fields['referencias_makito_vs_competidores_cifra_excluir'];
    
    $ref_giving = $referencias_frec_diaria->fields['referencias_makito_vs_competidores_gvng'];
    $ref_giving_extras = $referencias_frec_diaria->fields['referencias_makito_vs_competidores_gvng_extras'];
    $ref_giving_excluir = $referencias_frec_diaria->fields['referencias_makito_vs_competidores_gvng_excluir'];
    
    $ref_mob = $referencias_frec_diaria->fields['referencias_makito_vs_competidores_mob'];
    $ref_mob_extras = $referencias_frec_diaria->fields['referencias_makito_vs_competidores_mob_extras'];
    $ref_mob_excluir = $referencias_frec_diaria->fields['referencias_makito_vs_competidores_mob_excluir'];
    
    $ref_ps = $referencias_frec_diaria->fields['referencias_makito_vs_competidores_ps'];
    $ref_ps_extras = $referencias_frec_diaria->fields['referencias_makito_vs_competidores_ps_extras'];
    $ref_ps_excluir = $referencias_frec_diaria->fields['referencias_makito_vs_competidores_ps_excluir'];
    
    $ref_pf = $referencias_frec_diaria->fields['referencias_makito_vs_competidores_pf'];
    $ref_pf_extras = $referencias_frec_diaria->fields['referencias_makito_vs_competidores_pf_extras'];
    $ref_pf_excluir = $referencias_frec_diaria->fields['referencias_makito_vs_competidores_pf_excluir'];
    
    $ref_ggoya = $referencias_frec_diaria->fields['referencias_makito_vs_competidores_ggy'];
    $ref_ggoya_extras = $referencias_frec_diaria->fields['referencias_makito_vs_competidores_ggy_extras'];
    $ref_ggoya_excluir = $referencias_frec_diaria->fields['referencias_makito_vs_competidores_ggy_excluir'];
    
    //Realizo las consultas de stock de las distintas referencias de los competidores
    
    //CONSULTO Y ALMACENO EL STOCK EN CIFRA (si hay correspondencia con alguna de sus referencias)
        if($ref_cifra != "") {
            try {
                $stock_cifra = consulta_stock_cifra($ref_cifra);
                
                if($stock_cifra == -2) { //Se ha producido un error al consultar el stock de esa referencia
                    $resultado_proceso_ref = "ERROR";
                
                    $log = fopen($nombre_fichero_log, "a");
                    fwrite($log, "#### ERROR al procesar la ref.".$ref_makito." - ".$ref_cifra." (CIFRA)\r\n");
                    fclose($log);
                }
                
            } catch (Exception $e) {
                $resultado_proceso_ref = "ERROR";
                
                $log = fopen($nombre_fichero_log, "a");
                fwrite($log, "#### ERROR al procesar la ref.".$ref_makito." - ".$ref_cifra." (CIFRA)\r\n");
                fclose($log);
            }
            
            //INSERTO EL RESULTADO DE LA CONSULTA DE STOCK EN LA BD
            //Paso a almacenar el valor de stock de esa referencia en la Base de Datos (así aprovecho el acceso a la web para hacer scrapping)
            $consulta_inserta_stock_cifra = "INSERT INTO stock_cifra
                                               (stock_cifra_ref,
                                                stock_cifra_fecha,
                                                stock_cifra_hora,
                                                stock_cifra_stock)
                                            VALUES
                                               ('$ref_cifra',
                                                '$fecha_actual_inicio_ref',
                                                '$hora_actual_inicio_ref',
                                                '$stock_cifra');";
            $bd->Execute($consulta_inserta_stock_cifra);
            
            //Compruebo si hay ref. extras o a excluir, consulto su stock y añado o sustraigo sus stock de $stock_cifra
            if($ref_cifra_extras != "") {
                $array_ref_cifra_extras = explode("/", $ref_cifra_extras);
                
                foreach($array_ref_cifra_extras as $ref_cifra_extra) {
                    try {
                        $stock_cifra_extra = consulta_stock_cifra($ref_cifra_extra);
                
                        if($stock_cifra_extra == -2) { //Se ha producido un error al consultar el stock de esa referencia
                            $resultado_proceso_ref = "ERROR";

                            $log = fopen($nombre_fichero_log, "a");
                            fwrite($log, "#### ERROR al procesar la ref.".$ref_makito." - ".$ref_cifra_extra." (CIFRA)\r\n");
                            fclose($log);
                        }

                    } catch (Exception $e) {
                        $resultado_proceso_ref = "ERROR";

                        $log = fopen($nombre_fichero_log, "a");
                        fwrite($log, "#### ERROR al procesar la ref.".$ref_makito." - ".$ref_cifra_extra." (CIFRA)\r\n");
                        fclose($log);
                    }
            
                    //INSERTO EL RESULTADO DE LA CONSULTA DE STOCK EN LA BD
                    //Paso a almacenar el valor de stock de esa referencia en la Base de Datos (así aprovecho el acceso a la web para hacer scrapping)
                    $consulta_inserta_stock_cifra_extra = "INSERT INTO stock_cifra
                                                               (stock_cifra_ref,
                                                                stock_cifra_fecha,
                                                                stock_cifra_hora,
                                                                stock_cifra_stock)
                                                            VALUES
                                                               ('$ref_cifra_extra',
                                                                '$fecha_actual_inicio_ref',
                                                                '$hora_actual_inicio_ref',
                                                                '$stock_cifra_extra');";
                    $bd->Execute($consulta_inserta_stock_cifra_extra);
                    
                    //Anado el stock de cada ref. extra al de la ref. ppal.
                    $stock_cifra = $stock_cifra + $stock_cifra_extra;
                }
            }
            
            if($ref_cifra_excluir != "") {
                $array_ref_cifra_excluir = explode("/", $ref_cifra_excluir);
                
                foreach($array_ref_cifra_excluir as $ref_cifra_excl) {
                    try {
                        $stock_cifra_excl = consulta_stock_cifra($ref_cifra_excl);
                
                        if($stock_cifra_excl == -2) { //Se ha producido un error al consultar el stock de esa referencia
                            $resultado_proceso_ref = "ERROR";

                            $log = fopen($nombre_fichero_log, "a");
                            fwrite($log, "#### ERROR al procesar la ref.".$ref_makito." - ".$ref_cifra_excl." (CIFRA)\r\n");
                            fclose($log);
                        }


                    } catch (Exception $e) {
                        $resultado_proceso_ref = "ERROR";

                        $log = fopen($nombre_fichero_log, "a");
                        fwrite($log, "#### ERROR al procesar la ref.".$ref_makito." - ".$ref_cifra_excl." (CIFRA)\r\n");
                        fclose($log);
                    }
            
                    //INSERTO EL RESULTADO DE LA CONSULTA DE STOCK EN LA BD
                    //Paso a almacenar el valor de stock de esa referencia en la Base de Datos (así aprovecho el acceso a la web para hacer scrapping)
                    $consulta_inserta_stock_cifra_excl = "INSERT INTO stock_cifra
                                                               (stock_cifra_ref,
                                                                stock_cifra_fecha,
                                                                stock_cifra_hora,
                                                                stock_cifra_stock)
                                                            VALUES
                                                               ('$ref_cifra_excl',
                                                                '$fecha_actual_inicio_ref',
                                                                '$hora_actual_inicio_ref',
                                                                '$stock_cifra_excl');";
                    $bd->Execute($consulta_inserta_stock_cifra_excl);
                    
                    //Sustraigo el stock de cada ref. excl del de la ref. ppal.
                    $stock_cifra = $stock_cifra - $stock_cifra_excl;
                }
            }
            
            //Muestro el stock del competidor respecto a la referencia de Makito
            //echo $ref_makito." Stock Actual del Artículo en CIFRA(".$ref_cifra."): ".$stock_cifra." unidades<br>";
            
        } else {
            $stock_cifra = -1;
            
            //echo $ref_makito." Stock Actual del Artículo en CIFRA: No se ha podido obtener el dato, puesto que no existe aún correspondencia de este artículo con ninguna referencia de CIFRA<br>";
        }
        
        
        //CONSULTO Y ALMACENO EL STOCK EN GIVING (si hay correspondencia con alguna de sus referencias)
        if($ref_giving != "") {
            try {
                $stock_giving = consulta_stock_giving($ref_giving);
                
                if($stock_giving == -2) { //Se ha producido un error al consultar el stock de esa referencia
                    $resultado_proceso_ref = "ERROR";

                    $log = fopen($nombre_fichero_log, "a");
                    fwrite($log, "#### ERROR al procesar la ref.".$ref_makito." - ".$ref_giving." (GIVING)\r\n");
                    fclose($log);
                }
                
            } catch (Exception $e) {
                $resultado_proceso_ref = "ERROR";

                $log = fopen($nombre_fichero_log, "a");
                fwrite($log, "#### ERROR al procesar la ref.".$ref_makito." - ".$ref_giving." (GIVING)\r\n");
                fclose($log);
            }
            
            //INSERTO EL RESULTADO DE LA CONSULTA DE STOCK EN LA BD
            //Paso a almacenar el valor de stock de esa referencia en la Base de Datos (así aprovecho el acceso a la web para hacer scrapping)
            $consulta_inserta_stock_giving = "INSERT INTO stock_giving
                                               (stock_giving_ref,
                                                stock_giving_fecha,
                                                stock_giving_hora,
                                                stock_giving_stock)
                                            VALUES
                                               ('$ref_giving',
                                                '$fecha_actual_inicio_ref',
                                                '$hora_actual_inicio_ref',
                                                '$stock_giving');";
            $bd->Execute($consulta_inserta_stock_giving);
            
            //Compruebo si hay ref. extras o a excluir, consulto su stock y añado o sustraigo sus stock de $stock_giving
            if($ref_giving_extras != "") {
                $array_ref_giving_extras = explode("/", $ref_giving_extras);
                
                foreach($array_ref_giving_extras as $ref_giving_extra) {
                    try {
                        $stock_giving_extra = consulta_stock_giving($ref_giving_extra);
                
                        if($stock_giving_extra == -2) { //Se ha producido un error al consultar el stock de esa referencia
                            $resultado_proceso_ref = "ERROR";

                            $log = fopen($nombre_fichero_log, "a");
                            fwrite($log, "#### ERROR al procesar la ref.".$ref_makito." - ".$ref_giving_extra." (GIVING)\r\n");
                            fclose($log);
                        }

                    } catch (Exception $e) {
                        $resultado_proceso_ref = "ERROR";

                        $log = fopen($nombre_fichero_log, "a");
                        fwrite($log, "#### ERROR al procesar la ref.".$ref_makito." - ".$ref_giving_extra." (GIVING)\r\n");
                        fclose($log);
                    }
            
                    //INSERTO EL RESULTADO DE LA CONSULTA DE STOCK EN LA BD
                    //Paso a almacenar el valor de stock de esa referencia en la Base de Datos (así aprovecho el acceso a la web para hacer scrapping)
                    $consulta_inserta_stock_giving_extra = "INSERT INTO stock_giving
                                                               (stock_giving_ref,
                                                                stock_giving_fecha,
                                                                stock_giving_hora,
                                                                stock_giving_stock)
                                                            VALUES
                                                               ('$ref_giving_extra',
                                                                '$fecha_actual_inicio_ref',
                                                                '$hora_actual_inicio_ref',
                                                                '$stock_giving_extra');";
                    $bd->Execute($consulta_inserta_stock_giving_extra);
                    
                    //Anado el stock de cada ref. extra al de la ref. ppal.
                    $stock_giving = $stock_giving + $stock_giving_extra;
                }
            }
            
            if($ref_giving_excluir != "") {
                $array_ref_giving_excluir = explode("/", $ref_giving_excluir);
                
                foreach($array_ref_giving_excluir as $ref_giving_excl) {
                    try {
                        $stock_giving_excl = consulta_stock_giving($ref_giving_excl);
                
                        if($stock_giving_excl == -2) { //Se ha producido un error al consultar el stock de esa referencia
                            $resultado_proceso_ref = "ERROR";

                            $log = fopen($nombre_fichero_log, "a");
                            fwrite($log, "#### ERROR al procesar la ref.".$ref_makito." - ".$ref_giving_excl." (GIVING)\r\n");
                            fclose($log);
                        }


                    } catch (Exception $e) {
                        $resultado_proceso_ref = "ERROR";

                        $log = fopen($nombre_fichero_log, "a");
                        fwrite($log, "#### ERROR al procesar la ref.".$ref_makito." - ".$ref_giving_excl." (GIVING)\r\n");
                        fclose($log);
                    }
            
                    //INSERTO EL RESULTADO DE LA CONSULTA DE STOCK EN LA BD
                    //Paso a almacenar el valor de stock de esa referencia en la Base de Datos (así aprovecho el acceso a la web para hacer scrapping)
                    $consulta_inserta_stock_giving_excl = "INSERT INTO stock_giving
                                                               (stock_giving_ref,
                                                                stock_giving_fecha,
                                                                stock_giving_hora,
                                                                stock_giving_stock)
                                                            VALUES
                                                               ('$ref_giving_excl',
                                                                '$fecha_actual_inicio_ref',
                                                                '$hora_actual_inicio_ref',
                                                                '$stock_giving_excl');";
                    $bd->Execute($consulta_inserta_stock_giving_excl);
                    
                    //Sustraigo el stock de cada ref. excl del de la ref. ppal.
                    $stock_giving = $stock_giving - $stock_giving_excl;
                }
            }
            
            //Muestro el stock del competidor respecto a la referencia de Makito
            //echo $ref_makito." Stock Actual del Artículo en GIVING(".$ref_giving."): ".$stock_giving." unidades<br>";
            
        }  else {
            $stock_giving = -1;
            
            //echo $ref_makito." Stock Actual del Artículo en GIVING: No se ha podido obtener el dato, puesto que no existe aún correspondencia de este artículo con ninguna referencia de GIVING<br>";
        }
        
        //CONSULTO Y ALMACENO EL STOCK EN MOB (si hay correspondencia con alguna de sus referencias)
        if($ref_mob != "") {
            try {
                $stock_mob = consulta_stock_mob($ref_mob);
                
                if($stock_mob == -2) { //Se ha producido un error al consultar el stock de esa referencia
                    $resultado_proceso_ref = "ERROR";

                    $log = fopen($nombre_fichero_log, "a");
                    fwrite($log, "#### ERROR al procesar la ref.".$ref_makito." - ".$ref_mob." (MOB)\r\n");
                    fclose($log);
                }
                
            } catch (Exception $e) {
                $resultado_proceso_ref = "ERROR";

                $log = fopen($nombre_fichero_log, "a");
                fwrite($log, "#### ERROR al procesar la ref.".$ref_makito." - ".$ref_mob." (MOB)\r\n");
                fclose($log);
            }
            
            //INSERTO EL RESULTADO DE LA CONSULTA DE STOCK EN LA BD
            //Paso a almacenar el valor de stock de esa referencia en la Base de Datos (así aprovecho el acceso a la web para hacer scrapping)
            $consulta_inserta_stock_mob = "INSERT INTO stock_mob
                                               (stock_mob_ref,
                                                stock_mob_fecha,
                                                stock_mob_hora,
                                                stock_mob_stock)
                                            VALUES
                                               ('$ref_mob',
                                                '$fecha_actual_inicio_ref',
                                                '$hora_actual_inicio_ref',
                                                '$stock_mob');";
            $bd->Execute($consulta_inserta_stock_mob);
            
            //Compruebo si hay ref. extras o a excluir, consulto su stock y añado o sustraigo sus stock de $stock_mob
            if($ref_mob_extras != "") {
                $array_ref_mob_extras = explode("/", $ref_mob_extras);
                
                foreach($array_ref_mob_extras as $ref_mob_extra) {
                    try {
                        $stock_mob_extra = consulta_stock_mob($ref_mob_extra);
                
                        if($stock_mob_extra == -2) { //Se ha producido un error al consultar el stock de esa referencia
                            $resultado_proceso_ref = "ERROR";

                            $log = fopen($nombre_fichero_log, "a");
                            fwrite($log, "#### ERROR al procesar la ref.".$ref_makito." - ".$ref_mob_extra." (MOB)\r\n");
                            fclose($log);
                        }

                    } catch (Exception $e) {
                        $resultado_proceso_ref = "ERROR";

                        $log = fopen($nombre_fichero_log, "a");
                        fwrite($log, "#### ERROR al procesar la ref.".$ref_makito." - ".$ref_mob_extra." (MOB)\r\n");
                        fclose($log);
                    }
            
                    //INSERTO EL RESULTADO DE LA CONSULTA DE STOCK EN LA BD
                    //Paso a almacenar el valor de stock de esa referencia en la Base de Datos (así aprovecho el acceso a la web para hacer scrapping)
                    $consulta_inserta_stock_mob_extra = "INSERT INTO stock_mob
                                                               (stock_mob_ref,
                                                                stock_mob_fecha,
                                                                stock_mob_hora,
                                                                stock_mob_stock)
                                                            VALUES
                                                               ('$ref_mob_extra',
                                                                '$fecha_actual_inicio_ref',
                                                                '$hora_actual_inicio_ref',
                                                                '$stock_mob_extra');";
                    $bd->Execute($consulta_inserta_stock_mob_extra);
                    
                    //Anado el stock de cada ref. extra al de la ref. ppal.
                    $stock_mob = $stock_mob + $stock_mob_extra;
                }
            }
            
            if($ref_mob_excluir != "") {
                $array_ref_mob_excluir = explode("/", $ref_mob_excluir);
                
                foreach($array_ref_mob_excluir as $ref_mob_excl) {
                    try {
                        $stock_mob_excl = consulta_stock_mob($ref_mob_excl);
                
                        if($stock_mob_excl == -2) { //Se ha producido un error al consultar el stock de esa referencia
                            $resultado_proceso_ref = "ERROR";

                            $log = fopen($nombre_fichero_log, "a");
                            fwrite($log, "#### ERROR al procesar la ref.".$ref_makito." - ".$ref_mob_excl." (MOB)\r\n");
                            fclose($log);
                        }


                    } catch (Exception $e) {
                        $resultado_proceso_ref = "ERROR";

                        $log = fopen($nombre_fichero_log, "a");
                        fwrite($log, "#### ERROR al procesar la ref.".$ref_makito." - ".$ref_mob_excl." (MOB)\r\n");
                        fclose($log);
                    }
            
                    //INSERTO EL RESULTADO DE LA CONSULTA DE STOCK EN LA BD
                    //Paso a almacenar el valor de stock de esa referencia en la Base de Datos (así aprovecho el acceso a la web para hacer scrapping)
                    $consulta_inserta_stock_mob_excl = "INSERT INTO stock_mob
                                                               (stock_mob_ref,
                                                                stock_mob_fecha,
                                                                stock_mob_hora,
                                                                stock_mob_stock)
                                                            VALUES
                                                               ('$ref_mob_excl',
                                                                '$fecha_actual_inicio_ref',
                                                                '$hora_actual_inicio_ref',
                                                                '$stock_mob_excl');";
                    $bd->Execute($consulta_inserta_stock_mob_excl);
                    
                    //Sustraigo el stock de cada ref. excl del de la ref. ppal.
                    $stock_mob = $stock_mob - $stock_mob_excl;
                }
            }
            
            //Muestro el stock del competidor respecto a la referencia de Makito
            //echo $ref_makito." Stock Actual del Artículo en MOB(".$ref_mob."): ".$stock_mob." unidades<br>";
            
        }  else {
            $stock_mob = -1;
            
            //echo $ref_makito." Stock Actual del Artículo en MOB: No se ha podido obtener el dato, puesto que no existe aún correspondencia de este artículo con ninguna referencia de MOB<br>";
        }
        
        //CONSULTO Y ALMACENO EL STOCK EN PS (si hay correspondencia con alguna de sus referencias)
        if($ref_ps != "") {
            try {
                $stock_ps = consulta_stock_ps($ref_ps);
                
                if($stock_ps == -2) { //Se ha producido un error al consultar el stock de esa referencia
                    $resultado_proceso_ref = "ERROR";

                    $log = fopen($nombre_fichero_log, "a");
                    fwrite($log, "#### ERROR al procesar la ref.".$ref_makito." - ".$ref_ps." (PS)\r\n");
                    fclose($log);
                }
                
            } catch (Exception $e) {
                $resultado_proceso_ref = "ERROR";

                $log = fopen($nombre_fichero_log, "a");
                fwrite($log, "#### ERROR al procesar la ref.".$ref_makito." - ".$ref_ps." (PS)\r\n");
                fclose($log);
            }
            
            //INSERTO EL RESULTADO DE LA CONSULTA DE STOCK EN LA BD
            //Paso a almacenar el valor de stock de esa referencia en la Base de Datos (así aprovecho el acceso a la web para hacer scrapping)
            $consulta_inserta_stock_ps = "INSERT INTO stock_ps
                                               (stock_ps_ref,
                                                stock_ps_fecha,
                                                stock_ps_hora,
                                                stock_ps_stock)
                                            VALUES
                                               ('$ref_ps',
                                                '$fecha_actual_inicio_ref',
                                                '$hora_actual_inicio_ref',
                                                '$stock_ps');";
            $bd->Execute($consulta_inserta_stock_ps);
            
            //Compruebo si hay ref. extras o a excluir, consulto su stock y añado o sustraigo sus stock de $stock_ps
            if($ref_ps_extras != "") {
                $array_ref_ps_extras = explode("/", $ref_ps_extras);
                
                foreach($array_ref_ps_extras as $ref_ps_extra) {
                    try {
                        $stock_ps_extra = consulta_stock_ps($ref_ps_extra);
                
                        if($stock_ps_extra == -2) { //Se ha producido un error al consultar el stock de esa referencia
                            $resultado_proceso_ref = "ERROR";

                            $log = fopen($nombre_fichero_log, "a");
                            fwrite($log, "#### ERROR al procesar la ref.".$ref_makito." - ".$ref_ps_extra." (PS)\r\n");
                            fclose($log);
                        }

                    } catch (Exception $e) {
                        $resultado_proceso_ref = "ERROR";

                        $log = fopen($nombre_fichero_log, "a");
                        fwrite($log, "#### ERROR al procesar la ref.".$ref_makito." - ".$ref_ps_extra." (PS)\r\n");
                        fclose($log);
                    }
            
                    //INSERTO EL RESULTADO DE LA CONSULTA DE STOCK EN LA BD
                    //Paso a almacenar el valor de stock de esa referencia en la Base de Datos (así aprovecho el acceso a la web para hacer scrapping)
                    $consulta_inserta_stock_ps_extra = "INSERT INTO stock_ps
                                                               (stock_ps_ref,
                                                                stock_ps_fecha,
                                                                stock_ps_hora,
                                                                stock_ps_stock)
                                                            VALUES
                                                               ('$ref_ps_extra',
                                                                '$fecha_actual_inicio_ref',
                                                                '$hora_actual_inicio_ref',
                                                                '$stock_ps_extra');";
                    $bd->Execute($consulta_inserta_stock_ps_extra);
                    
                    //Anado el stock de cada ref. extra al de la ref. ppal.
                    $stock_ps = $stock_ps + $stock_ps_extra;
                }
            }
            
            if($ref_ps_excluir != "") {
                $array_ref_ps_excluir = explode("/", $ref_ps_excluir);
                
                foreach($array_ref_ps_excluir as $ref_ps_excl) {
                    try {
                        $stock_ps_excl = consulta_stock_ps($ref_ps_excl);
                
                        if($stock_ps_excl == -2) { //Se ha producido un error al consultar el stock de esa referencia
                            $resultado_proceso_ref = "ERROR";

                            $log = fopen($nombre_fichero_log, "a");
                            fwrite($log, "#### ERROR al procesar la ref.".$ref_makito." - ".$ref_ps_excl." (PS)\r\n");
                            fclose($log);
                        }


                    } catch (Exception $e) {
                        $resultado_proceso_ref = "ERROR";

                        $log = fopen($nombre_fichero_log, "a");
                        fwrite($log, "#### ERROR al procesar la ref.".$ref_makito." - ".$ref_ps_excl." (PS)\r\n");
                        fclose($log);
                    }
            
                    //INSERTO EL RESULTADO DE LA CONSULTA DE STOCK EN LA BD
                    //Paso a almacenar el valor de stock de esa referencia en la Base de Datos (así aprovecho el acceso a la web para hacer scrapping)
                    $consulta_inserta_stock_ps_excl = "INSERT INTO stock_ps
                                                               (stock_ps_ref,
                                                                stock_ps_fecha,
                                                                stock_ps_hora,
                                                                stock_ps_stock)
                                                            VALUES
                                                               ('$ref_ps_excl',
                                                                '$fecha_actual_inicio_ref',
                                                                '$hora_actual_inicio_ref',
                                                                '$stock_ps_excl');";
                    $bd->Execute($consulta_inserta_stock_ps_excl);
                    
                    //Sustraigo el stock de cada ref. excl del de la ref. ppal.
                    $stock_ps = $stock_ps - $stock_ps_excl;
                }
            }
            
            //Muestro el stock del competidor respecto a la referencia de Makito
            //echo $ref_makito." Stock Actual del Artículo en PS(".$ref_ps."): ".$stock_ps." unidades<br>";
            
        }  else {
            $stock_ps = -1;
            
            //echo $ref_makito." Stock Actual del Artículo en PS: No se ha podido obtener el dato, puesto que no existe aún correspondencia de este artículo con ninguna referencia de PS<br>";
        }
        
        //CONSULTO Y ALMACENO EL STOCK EN PF (si hay correspondencia con alguna de sus referencias)
        if($ref_pf != "") {
            try {
                $stock_pf = consulta_stock_pf($ref_pf);
                
                if($stock_pf == -2) { //Se ha producido un error al consultar el stock de esa referencia
                    $resultado_proceso_ref = "ERROR";

                    $log = fopen($nombre_fichero_log, "a");
                    fwrite($log, "#### ERROR al procesar la ref.".$ref_makito." - ".$ref_pf." (PF)\r\n");
                    fclose($log);
                }
                
            } catch (Exception $e) {
                $resultado_proceso_ref = "ERROR";

                $log = fopen($nombre_fichero_log, "a");
                fwrite($log, "#### ERROR al procesar la ref.".$ref_makito." - ".$ref_pf." (PF)\r\n");
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
            
            //Compruebo si hay ref. extras o a excluir, consulto su stock y añado o sustraigo sus stock de $stock_pf
            if($ref_pf_extras != "") {
                $array_ref_pf_extras = explode("/", $ref_pf_extras);
                
                foreach($array_ref_pf_extras as $ref_pf_extra) {
                    try {
                        $stock_pf_extra = consulta_stock_pf($ref_pf_extra);
                
                        if($stock_pf_extra == -2) { //Se ha producido un error al consultar el stock de esa referencia
                            $resultado_proceso_ref = "ERROR";

                            $log = fopen($nombre_fichero_log, "a");
                            fwrite($log, "#### ERROR al procesar la ref.".$ref_makito." - ".$ref_pf_extra." (PF)\r\n");
                            fclose($log);
                        }

                    } catch (Exception $e) {
                        $resultado_proceso_ref = "ERROR";

                        $log = fopen($nombre_fichero_log, "a");
                        fwrite($log, "#### ERROR al procesar la ref.".$ref_makito." - ".$ref_pf_extra." (PF)\r\n");
                        fclose($log);
                    }
            
                    //INSERTO EL RESULTADO DE LA CONSULTA DE STOCK EN LA BD
                    //Paso a almacenar el valor de stock de esa referencia en la Base de Datos (así aprovecho el acceso a la web para hacer scrapping)
                    $consulta_inserta_stock_pf_extra = "INSERT INTO stock_pf
                                                               (stock_pf_ref,
                                                                stock_pf_fecha,
                                                                stock_pf_hora,
                                                                stock_pf_stock)
                                                            VALUES
                                                               ('$ref_pf_extra',
                                                                '$fecha_actual_inicio_ref',
                                                                '$hora_actual_inicio_ref',
                                                                '$stock_pf_extra');";
                    $bd->Execute($consulta_inserta_stock_pf_extra);
                    
                    //Anado el stock de cada ref. extra al de la ref. ppal.
                    $stock_pf = $stock_pf + $stock_pf_extra;
                }
            }
            
            if($ref_pf_excluir != "") {
                $array_ref_pf_excluir = explode("/", $ref_pf_excluir);
                
                foreach($array_ref_pf_excluir as $ref_pf_excl) {
                    try {
                        $stock_pf_excl = consulta_stock_pf($ref_pf_excl);
                
                        if($stock_pf_excl == -2) { //Se ha producido un error al consultar el stock de esa referencia
                            $resultado_proceso_ref = "ERROR";

                            $log = fopen($nombre_fichero_log, "a");
                            fwrite($log, "#### ERROR al procesar la ref.".$ref_makito." - ".$ref_pf_excl." (PF)\r\n");
                            fclose($log);
                        }


                    } catch (Exception $e) {
                        $resultado_proceso_ref = "ERROR";

                        $log = fopen($nombre_fichero_log, "a");
                        fwrite($log, "#### ERROR al procesar la ref.".$ref_makito." - ".$ref_pf_excl." (PF)\r\n");
                        fclose($log);
                    }
            
                    //INSERTO EL RESULTADO DE LA CONSULTA DE STOCK EN LA BD
                    //Paso a almacenar el valor de stock de esa referencia en la Base de Datos (así aprovecho el acceso a la web para hacer scrapping)
                    $consulta_inserta_stock_pf_excl = "INSERT INTO stock_pf
                                                               (stock_pf_ref,
                                                                stock_pf_fecha,
                                                                stock_pf_hora,
                                                                stock_pf_stock)
                                                            VALUES
                                                               ('$ref_pf_excl',
                                                                '$fecha_actual_inicio_ref',
                                                                '$hora_actual_inicio_ref',
                                                                '$stock_pf_excl');";
                    $bd->Execute($consulta_inserta_stock_pf_excl);
                    
                    //Sustraigo el stock de cada ref. excl del de la ref. ppal.
                    $stock_pf = $stock_pf - $stock_pf_excl;
                }
            }
            
            //Muestro el stock del competidor respecto a la referencia de Makito
            //echo $ref_makito." Stock Actual del Artículo en PF(".$ref_pf."): ".$stock_pf." unidades<br>";
            
        }  else {
            $stock_pf = -1;
            
            //echo $ref_makito." Stock Actual del Artículo en PF: No se ha podido obtener el dato, puesto que no existe aún correspondencia de este artículo con ninguna referencia de PF<br>";
        }
        
        //CONSULTO Y ALMACENO EL STOCK EN GGOYA (si hay correspondencia con alguna de sus referencias)
        if($ref_ggoya != "") {
            try {
                $stock_ggoya = consulta_stock_ggoya($ref_ggoya);
                
                if($stock_ggoya == -2) { //Se ha producido un error al consultar el stock de esa referencia
                    $resultado_proceso_ref = "ERROR";

                    $log = fopen($nombre_fichero_log, "a");
                    fwrite($log, "#### ERROR al procesar la ref.".$ref_makito." - ".$ref_ggoya." (GGOYA)\r\n");
                    fclose($log);
                }
                
            } catch (Exception $e) {
                $resultado_proceso_ref = "ERROR";

                $log = fopen($nombre_fichero_log, "a");
                fwrite($log, "#### ERROR al procesar la ref.".$ref_makito." - ".$ref_ggoya." (GGOYA)\r\n");
                fclose($log);
            }
            
            //INSERTO EL RESULTADO DE LA CONSULTA DE STOCK EN LA BD
            //Paso a almacenar el valor de stock de esa referencia en la Base de Datos (así aprovecho el acceso a la web para hacer scrapping)
            $consulta_inserta_stock_ggoya = "INSERT INTO stock_ggoya
                                               (stock_ggoya_ref,
                                                stock_ggoya_fecha,
                                                stock_ggoya_hora,
                                                stock_ggoya_stock)
                                            VALUES
                                               ('$ref_ggoya',
                                                '$fecha_actual_inicio_ref',
                                                '$hora_actual_inicio_ref',
                                                '$stock_ggoya');";
            $bd->Execute($consulta_inserta_stock_ggoya);
            
            //Compruebo si hay ref. extras o a excluir, consulto su stock y añado o sustraigo sus stock de $stock_ggoya
            if($ref_ggoya_extras != "") {
                $array_ref_ggoya_extras = explode("/", $ref_ggoya_extras);
                
                foreach($array_ref_ggoya_extras as $ref_ggoya_extra) {
                    try {
                        $stock_ggoya_extra = consulta_stock_ggoya($ref_ggoya_extra);
                
                        if($stock_ggoya_extra == -2) { //Se ha producido un error al consultar el stock de esa referencia
                            $resultado_proceso_ref = "ERROR";

                            $log = fopen($nombre_fichero_log, "a");
                            fwrite($log, "#### ERROR al procesar la ref.".$ref_makito." - ".$ref_ggoya_extra." (GGOYA)\r\n");
                            fclose($log);
                        }

                    } catch (Exception $e) {
                        $resultado_proceso_ref = "ERROR";

                        $log = fopen($nombre_fichero_log, "a");
                        fwrite($log, "#### ERROR al procesar la ref.".$ref_makito." - ".$ref_ggoya_extra." (GGOYA)\r\n");
                        fclose($log);
                    }
            
                    //INSERTO EL RESULTADO DE LA CONSULTA DE STOCK EN LA BD
                    //Paso a almacenar el valor de stock de esa referencia en la Base de Datos (así aprovecho el acceso a la web para hacer scrapping)
                    $consulta_inserta_stock_ggoya_extra = "INSERT INTO stock_ggoya
                                                               (stock_ggoya_ref,
                                                                stock_ggoya_fecha,
                                                                stock_ggoya_hora,
                                                                stock_ggoya_stock)
                                                            VALUES
                                                               ('$ref_ggoya_extra',
                                                                '$fecha_actual_inicio_ref',
                                                                '$hora_actual_inicio_ref',
                                                                '$stock_ggoya_extra');";
                    $bd->Execute($consulta_inserta_stock_ggoya_extra);
                    
                    //Anado el stock de cada ref. extra al de la ref. ppal.
                    $stock_ggoya = $stock_ggoya + $stock_ggoya_extra;
                }
            }
            
            if($ref_ggoya_excluir != "") {
                $array_ref_ggoya_excluir = explode("/", $ref_ggoya_excluir);
                
                foreach($array_ref_ggoya_excluir as $ref_ggoya_excl) {
                    try {
                        $stock_ggoya_excl = consulta_stock_ggoya($ref_ggoya_excl);
                
                        if($stock_ggoya_excl == -2) { //Se ha producido un error al consultar el stock de esa referencia
                            $resultado_proceso_ref = "ERROR";

                            $log = fopen($nombre_fichero_log, "a");
                            fwrite($log, "#### ERROR al procesar la ref.".$ref_makito." - ".$ref_ggoya_excl." (GGOYA)\r\n");
                            fclose($log);
                        }


                    } catch (Exception $e) {
                        $resultado_proceso_ref = "ERROR";

                        $log = fopen($nombre_fichero_log, "a");
                        fwrite($log, "#### ERROR al procesar la ref.".$ref_makito." - ".$ref_ggoya_excl." (GGOYA)\r\n");
                        fclose($log);
                    }
            
                    //INSERTO EL RESULTADO DE LA CONSULTA DE STOCK EN LA BD
                    //Paso a almacenar el valor de stock de esa referencia en la Base de Datos (así aprovecho el acceso a la web para hacer scrapping)
                    $consulta_inserta_stock_ggoya_excl = "INSERT INTO stock_ggoya
                                                               (stock_ggoya_ref,
                                                                stock_ggoya_fecha,
                                                                stock_ggoya_hora,
                                                                stock_ggoya_stock)
                                                            VALUES
                                                               ('$ref_ggoya_excl',
                                                                '$fecha_actual_inicio_ref',
                                                                '$hora_actual_inicio_ref',
                                                                '$stock_ggoya_excl');";
                    $bd->Execute($consulta_inserta_stock_ggoya_excl);
                    
                    //Sustraigo el stock de cada ref. excl del de la ref. ppal.
                    $stock_ggoya = $stock_ggoya - $stock_ggoya_excl;
                }
            }
            
            //Muestro el stock del competidor respecto a la referencia de Makito
            //echo $ref_makito." Stock Actual del Artículo en GGOYA(".$ref_ggoya."): ".$stock_ggoya." unidades<br>";
            
        }  else {
            $stock_ggoya = -1;
            
            //echo $ref_makito." Stock Actual del Artículo en GGOYA: No se ha podido obtener el dato, puesto que no existe aún correspondencia de este artículo con ninguna referencia de GGOYA<br>";
        }
        
        
        //He establecido valores de control (-1) para los casos en que no se disponga del dato de stock de cualquier competidor
        //para luego si recupero -1 saber que se trata de que el dato no estaba disponible cuando se guardó el registro
                
        //ALMACENO LOS STOCKS DE CADA COMPETIDOR EN LA BD (respecto a la referencia de Makito) 
        $consulta_inserta_stock_makito_vs_todos = "INSERT INTO stock_makito_vs_todos
                                                               (stock_makito_vs_todos_ref_makito,
                                                                stock_makito_vs_todos_stock_cifra,
                                                                stock_makito_vs_todos_stock_giving,
                                                                stock_makito_vs_todos_stock_mob,
                                                                stock_makito_vs_todos_stock_ps,
                                                                stock_makito_vs_todos_stock_pf,
                                                                stock_makito_vs_todos_stock_ggoya,
                                                                stock_makito_vs_todos_fecha,
                                                                stock_makito_vs_todos_hora)
                                                            VALUES
                                                               ('$ref_makito',
                                                                 $stock_cifra,
                                                                 $stock_giving,
                                                                 $stock_mob,
                                                                 $stock_ps,
                                                                 $stock_pf,
                                                                 $stock_ggoya,
                                                                '$fecha_actual_inicio_ref',
                                                                '$hora_actual_inicio_ref');";
        $bd->Execute($consulta_inserta_stock_makito_vs_todos);
    
    
    //Obtengo la fecha actual, en el momento en que se ha finalizado la consulta de stocks para esta referencia
    $fecha_actual_fin_ref = date('Y-m-d');
    //Obtengo la hora actual
    $hora_actual_fin_ref = date('H:i:s');
    
    //ALMACENO EN UN FICHERO DE LOG, PREVIAMENTE CREADO, CADA UNA DE LAS OPERACIONES REALIZADAS
    //Y, CONVENDRÍA ALMACENAR LOS ERRORES SI ES QUE SE PRODUCEN
    $log = fopen($nombre_fichero_log, "a");
    fwrite($log, "Ref: ".$ref_makito." / Inicio: ".$fecha_actual_inicio_ref." - ".$hora_actual_inicio_ref." / Fin: ".$fecha_actual_fin_ref." - ".$hora_actual_fin_ref." / ".$resultado_proceso_ref."\r\n");
    fclose($log);
    
    //Paso a la siguiente referencia
    $referencias_frec_diaria->MoveNext();
}

//Obtengo la fecha actual, en el momento en que se ha finalizado la ejecución de consulta_stock_frec_diaria.php
$fecha_actual_fin = date('Y-m-d');
//Obtengo la hora actual
$hora_actual_fin = date('H:i:s');

//ALMACENO EN EL FICHERO DE LOG, PREVIAMENTE CREADO, LA FECHA Y HORA DE FINALIZACIÓN DEL PROCESO
$log = fopen($nombre_fichero_log, "a");
fwrite($log, "\r\n***************************************************************************\r\nCONSULTA DE STOCK DE ARTÍCULOS CON FRECUENCIA DIARIA:\r\nProceso finalizado el ".$fecha_actual_fin.", a las ".$hora_actual_fin."\r\n");
fclose($log);

//Muestro por pantalla el contenido almacenado durante el proceso en el fichero de log
$log = fopen($nombre_fichero_log, "r");
$contenido_log = fread($log, filesize($nombre_fichero_log));
fclose($log);

echo str_replace("\r\n", "<br>", $contenido_log);

?>