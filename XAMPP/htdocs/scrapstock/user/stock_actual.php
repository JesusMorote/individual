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
    
    include_once('../inc/consulta_stock_cifra.inc.php');
    include_once('../inc/consulta_stock_giving.inc.php');
    include_once('../inc/consulta_stock_mob.inc.php');
    include_once('../inc/consulta_stock_ps.inc.php');
    include_once('../inc/consulta_stock_pf.inc.php');
    include_once('../inc/consulta_stock_ggoya.inc.php');
    
    if(isset($_POST['filtro_referencia'])) {
        $ref_elegida = $_POST['filtro_referencia'];
        
        ?>
        <script>
            location.replace('inicio.php?id=stock_actual&ref=<?php echo $ref_elegida; ?>');
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
        
        //CONSULTO Y ALMACENO EL STOCK EN CIFRA (si hay correspondencia con alguna de sus referencias)
        try {
            
            if($ref_cifra != "") {
                $stock_cifra = consulta_stock_cifra($ref_cifra);

                //INSERTO EL RESULTADO DE LA CONSULTA DE STOCK EN LA BD
                //Paso a almacenar el valor de stock de esa referencia en la Base de Datos (así aprovecho el acceso a la web para hacer scrapping)
                $consulta_inserta_stock_cifra = "INSERT INTO stock_cifra
                                                   (stock_cifra_ref,
                                                    stock_cifra_fecha,
                                                    stock_cifra_hora,
                                                    stock_cifra_stock)
                                                VALUES
                                                   ('$ref_cifra',
                                                    '$fecha_actual',
                                                    '$hora_actual',
                                                    '$stock_cifra');";
                $bd->Execute($consulta_inserta_stock_cifra);

                //Compruebo si hay ref. extras o a excluir, consulto su stock y añado o sustraigo sus stock de $stock_cifra
                if($ref_cifra_extras != "") {
                    $array_ref_cifra_extras = explode("/", $ref_cifra_extras);

                    foreach($array_ref_cifra_extras as $ref_cifra_extra) {
                        $stock_cifra_extra = consulta_stock_cifra($ref_cifra_extra);

                        //INSERTO EL RESULTADO DE LA CONSULTA DE STOCK EN LA BD
                        //Paso a almacenar el valor de stock de esa referencia en la Base de Datos (así aprovecho el acceso a la web para hacer scrapping)
                        $consulta_inserta_stock_cifra_extra = "INSERT INTO stock_cifra
                                                                   (stock_cifra_ref,
                                                                    stock_cifra_fecha,
                                                                    stock_cifra_hora,
                                                                    stock_cifra_stock)
                                                                VALUES
                                                                   ('$ref_cifra_extra',
                                                                    '$fecha_actual',
                                                                    '$hora_actual',
                                                                    '$stock_cifra_extra');";
                        $bd->Execute($consulta_inserta_stock_cifra_extra);

                        //Anado el stock de cada ref. extra al de la ref. ppal.
                        $stock_cifra = $stock_cifra + $stock_cifra_extra;
                    }
                }

                if($ref_cifra_excluir != "") {
                    $array_ref_cifra_excluir = explode("/", $ref_cifra_excluir);

                    foreach($array_ref_cifra_excluir as $ref_cifra_excl) {
                        $stock_cifra_excl = consulta_stock_cifra($ref_cifra_excl);

                        //INSERTO EL RESULTADO DE LA CONSULTA DE STOCK EN LA BD
                        //Paso a almacenar el valor de stock de esa referencia en la Base de Datos (así aprovecho el acceso a la web para hacer scrapping)
                        $consulta_inserta_stock_cifra_excl = "INSERT INTO stock_cifra
                                                                   (stock_cifra_ref,
                                                                    stock_cifra_fecha,
                                                                    stock_cifra_hora,
                                                                    stock_cifra_stock)
                                                                VALUES
                                                                   ('$ref_cifra_excl',
                                                                    '$fecha_actual',
                                                                    '$hora_actual',
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
            
        } catch (Exception $e) {
            $stock_cifra = -2; //Se ha producido un error al consultar el stock de esa referencia
        }
        
        
        
        
        //CONSULTO Y ALMACENO EL STOCK EN GIVING (si hay correspondencia con alguna de sus referencias)
        try {
            
            if($ref_giving != "") {
                $stock_giving = consulta_stock_giving($ref_giving);

                //INSERTO EL RESULTADO DE LA CONSULTA DE STOCK EN LA BD
                //Paso a almacenar el valor de stock de esa referencia en la Base de Datos (así aprovecho el acceso a la web para hacer scrapping)
                $consulta_inserta_stock_giving = "INSERT INTO stock_giving
                                                   (stock_giving_ref,
                                                    stock_giving_fecha,
                                                    stock_giving_hora,
                                                    stock_giving_stock)
                                                VALUES
                                                   ('$ref_giving',
                                                    '$fecha_actual',
                                                    '$hora_actual',
                                                    '$stock_giving');";
                $bd->Execute($consulta_inserta_stock_giving);

                //Compruebo si hay ref. extras o a excluir, consulto su stock y añado o sustraigo sus stock de $stock_giving
                if($ref_giving_extras != "") {
                    $array_ref_giving_extras = explode("/", $ref_giving_extras);

                    foreach($array_ref_giving_extras as $ref_giving_extra) {
                        $stock_giving_extra = consulta_stock_giving($ref_giving_extra);

                        //INSERTO EL RESULTADO DE LA CONSULTA DE STOCK EN LA BD
                        //Paso a almacenar el valor de stock de esa referencia en la Base de Datos (así aprovecho el acceso a la web para hacer scrapping)
                        $consulta_inserta_stock_giving_extra = "INSERT INTO stock_giving
                                                                   (stock_giving_ref,
                                                                    stock_giving_fecha,
                                                                    stock_giving_hora,
                                                                    stock_giving_stock)
                                                                VALUES
                                                                   ('$ref_giving_extra',
                                                                    '$fecha_actual',
                                                                    '$hora_actual',
                                                                    '$stock_giving_extra');";
                        $bd->Execute($consulta_inserta_stock_giving_extra);

                        //Anado el stock de cada ref. extra al de la ref. ppal.
                        $stock_giving = $stock_giving + $stock_giving_extra;
                    }
                }

                if($ref_giving_excluir != "") {
                    $array_ref_giving_excluir = explode("/", $ref_giving_excluir);

                    foreach($array_ref_giving_excluir as $ref_giving_excl) {
                        $stock_giving_excl = consulta_stock_giving($ref_giving_excl);

                        //INSERTO EL RESULTADO DE LA CONSULTA DE STOCK EN LA BD
                        //Paso a almacenar el valor de stock de esa referencia en la Base de Datos (así aprovecho el acceso a la web para hacer scrapping)
                        $consulta_inserta_stock_giving_excl = "INSERT INTO stock_giving
                                                                   (stock_giving_ref,
                                                                    stock_giving_fecha,
                                                                    stock_giving_hora,
                                                                    stock_giving_stock)
                                                                VALUES
                                                                   ('$ref_giving_excl',
                                                                    '$fecha_actual',
                                                                    '$hora_actual',
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
            
        } catch (Exception $e) {
            $stock_giving = -2; //Se ha producido un error al consultar el stock de esa referencia
        }
        
        
        
        //CONSULTO Y ALMACENO EL STOCK EN MOB (si hay correspondencia con alguna de sus referencias)
        try {
            
            if($ref_mob != "") {
                $stock_mob = consulta_stock_mob($ref_mob);

                //INSERTO EL RESULTADO DE LA CONSULTA DE STOCK EN LA BD
                //Paso a almacenar el valor de stock de esa referencia en la Base de Datos (así aprovecho el acceso a la web para hacer scrapping)
                $consulta_inserta_stock_mob = "INSERT INTO stock_mob
                                                   (stock_mob_ref,
                                                    stock_mob_fecha,
                                                    stock_mob_hora,
                                                    stock_mob_stock)
                                                VALUES
                                                   ('$ref_mob',
                                                    '$fecha_actual',
                                                    '$hora_actual',
                                                    '$stock_mob');";
                $bd->Execute($consulta_inserta_stock_mob);

                //Compruebo si hay ref. extras o a excluir, consulto su stock y añado o sustraigo sus stock de $stock_mob
                if($ref_mob_extras != "") {
                    $array_ref_mob_extras = explode("/", $ref_mob_extras);

                    foreach($array_ref_mob_extras as $ref_mob_extra) {
                        $stock_mob_extra = consulta_stock_mob($ref_mob_extra);

                        //INSERTO EL RESULTADO DE LA CONSULTA DE STOCK EN LA BD
                        //Paso a almacenar el valor de stock de esa referencia en la Base de Datos (así aprovecho el acceso a la web para hacer scrapping)
                        $consulta_inserta_stock_mob_extra = "INSERT INTO stock_mob
                                                                   (stock_mob_ref,
                                                                    stock_mob_fecha,
                                                                    stock_mob_hora,
                                                                    stock_mob_stock)
                                                                VALUES
                                                                   ('$ref_mob_extra',
                                                                    '$fecha_actual',
                                                                    '$hora_actual',
                                                                    '$stock_mob_extra');";
                        $bd->Execute($consulta_inserta_stock_mob_extra);

                        //Anado el stock de cada ref. extra al de la ref. ppal.
                        $stock_mob = $stock_mob + $stock_mob_extra;
                    }
                }

                if($ref_mob_excluir != "") {
                    $array_ref_mob_excluir = explode("/", $ref_mob_excluir);

                    foreach($array_ref_mob_excluir as $ref_mob_excl) {
                        $stock_mob_excl = consulta_stock_mob($ref_mob_excl);

                        //INSERTO EL RESULTADO DE LA CONSULTA DE STOCK EN LA BD
                        //Paso a almacenar el valor de stock de esa referencia en la Base de Datos (así aprovecho el acceso a la web para hacer scrapping)
                        $consulta_inserta_stock_mob_excl = "INSERT INTO stock_mob
                                                                   (stock_mob_ref,
                                                                    stock_mob_fecha,
                                                                    stock_mob_hora,
                                                                    stock_mob_stock)
                                                                VALUES
                                                                   ('$ref_mob_excl',
                                                                    '$fecha_actual',
                                                                    '$hora_actual',
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
            
        } catch (Exception $e) {
            $stock_mob = -2; //Se ha producido un error al consultar el stock de esa referencia
        }
        
        
        
        //CONSULTO Y ALMACENO EL STOCK EN PS (si hay correspondencia con alguna de sus referencias)
        try {
            
            if($ref_ps != "") {
                $stock_ps = consulta_stock_ps($ref_ps);

                //INSERTO EL RESULTADO DE LA CONSULTA DE STOCK EN LA BD
                //Paso a almacenar el valor de stock de esa referencia en la Base de Datos (así aprovecho el acceso a la web para hacer scrapping)
                $consulta_inserta_stock_ps = "INSERT INTO stock_ps
                                                   (stock_ps_ref,
                                                    stock_ps_fecha,
                                                    stock_ps_hora,
                                                    stock_ps_stock)
                                                VALUES
                                                   ('$ref_ps',
                                                    '$fecha_actual',
                                                    '$hora_actual',
                                                    '$stock_ps');";
                $bd->Execute($consulta_inserta_stock_ps);

                //Compruebo si hay ref. extras o a excluir, consulto su stock y añado o sustraigo sus stock de $stock_ps
                if($ref_ps_extras != "") {
                    $array_ref_ps_extras = explode("/", $ref_ps_extras);

                    foreach($array_ref_ps_extras as $ref_ps_extra) {
                        $stock_ps_extra = consulta_stock_ps($ref_ps_extra);

                        //INSERTO EL RESULTADO DE LA CONSULTA DE STOCK EN LA BD
                        //Paso a almacenar el valor de stock de esa referencia en la Base de Datos (así aprovecho el acceso a la web para hacer scrapping)
                        $consulta_inserta_stock_ps_extra = "INSERT INTO stock_ps
                                                                   (stock_ps_ref,
                                                                    stock_ps_fecha,
                                                                    stock_ps_hora,
                                                                    stock_ps_stock)
                                                                VALUES
                                                                   ('$ref_ps_extra',
                                                                    '$fecha_actual',
                                                                    '$hora_actual',
                                                                    '$stock_ps_extra');";
                        $bd->Execute($consulta_inserta_stock_ps_extra);

                        //Anado el stock de cada ref. extra al de la ref. ppal.
                        $stock_ps = $stock_ps + $stock_ps_extra;
                    }
                }

                if($ref_ps_excluir != "") {
                    $array_ref_ps_excluir = explode("/", $ref_ps_excluir);

                    foreach($array_ref_ps_excluir as $ref_ps_excl) {
                        $stock_ps_excl = consulta_stock_ps($ref_ps_excl);

                        //INSERTO EL RESULTADO DE LA CONSULTA DE STOCK EN LA BD
                        //Paso a almacenar el valor de stock de esa referencia en la Base de Datos (así aprovecho el acceso a la web para hacer scrapping)
                        $consulta_inserta_stock_ps_excl = "INSERT INTO stock_ps
                                                                   (stock_ps_ref,
                                                                    stock_ps_fecha,
                                                                    stock_ps_hora,
                                                                    stock_ps_stock)
                                                                VALUES
                                                                   ('$ref_ps_excl',
                                                                    '$fecha_actual',
                                                                    '$hora_actual',
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
            
        } catch (Exception $e) {
            $stock_ps = -2; //Se ha producido un error al consultar el stock de esa referencia
        }
        
        
        
        //CONSULTO Y ALMACENO EL STOCK EN PF (si hay correspondencia con alguna de sus referencias)
        try {
            
            if($ref_pf != "") {
                $stock_pf = consulta_stock_pf($ref_pf);

                //INSERTO EL RESULTADO DE LA CONSULTA DE STOCK EN LA BD
                //Paso a almacenar el valor de stock de esa referencia en la Base de Datos (así aprovecho el acceso a la web para hacer scrapping)
                $consulta_inserta_stock_pf = "INSERT INTO stock_pf
                                                   (stock_pf_ref,
                                                    stock_pf_fecha,
                                                    stock_pf_hora,
                                                    stock_pf_stock)
                                                VALUES
                                                   ('$ref_pf',
                                                    '$fecha_actual',
                                                    '$hora_actual',
                                                    '$stock_pf');";
                $bd->Execute($consulta_inserta_stock_pf);

                //Compruebo si hay ref. extras o a excluir, consulto su stock y añado o sustraigo sus stock de $stock_pf
                if($ref_pf_extras != "") {
                    $array_ref_pf_extras = explode("/", $ref_pf_extras);

                    foreach($array_ref_pf_extras as $ref_pf_extra) {
                        $stock_pf_extra = consulta_stock_pf($ref_pf_extra);

                        //INSERTO EL RESULTADO DE LA CONSULTA DE STOCK EN LA BD
                        //Paso a almacenar el valor de stock de esa referencia en la Base de Datos (así aprovecho el acceso a la web para hacer scrapping)
                        $consulta_inserta_stock_pf_extra = "INSERT INTO stock_pf
                                                                   (stock_pf_ref,
                                                                    stock_pf_fecha,
                                                                    stock_pf_hora,
                                                                    stock_pf_stock)
                                                                VALUES
                                                                   ('$ref_pf_extra',
                                                                    '$fecha_actual',
                                                                    '$hora_actual',
                                                                    '$stock_pf_extra');";
                        $bd->Execute($consulta_inserta_stock_pf_extra);

                        //Anado el stock de cada ref. extra al de la ref. ppal.
                        $stock_pf = $stock_pf + $stock_pf_extra;
                    }
                }

                if($ref_pf_excluir != "") {
                    $array_ref_pf_excluir = explode("/", $ref_pf_excluir);

                    foreach($array_ref_pf_excluir as $ref_pf_excl) {
                        $stock_pf_excl = consulta_stock_pf($ref_pf_excl);

                        //INSERTO EL RESULTADO DE LA CONSULTA DE STOCK EN LA BD
                        //Paso a almacenar el valor de stock de esa referencia en la Base de Datos (así aprovecho el acceso a la web para hacer scrapping)
                        $consulta_inserta_stock_pf_excl = "INSERT INTO stock_pf
                                                                   (stock_pf_ref,
                                                                    stock_pf_fecha,
                                                                    stock_pf_hora,
                                                                    stock_pf_stock)
                                                                VALUES
                                                                   ('$ref_pf_excl',
                                                                    '$fecha_actual',
                                                                    '$hora_actual',
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
            
        } catch (Exception $e) {
            $stock_pf = -2; //Se ha producido un error al consultar el stock de esa referencia
        }
        
        
        
        //CONSULTO Y ALMACENO EL STOCK EN GGOYA (si hay correspondencia con alguna de sus referencias)
        try {
            
            if($ref_ggoya != "") {
                $stock_ggoya = consulta_stock_ggoya($ref_ggoya);

                //INSERTO EL RESULTADO DE LA CONSULTA DE STOCK EN LA BD
                //Paso a almacenar el valor de stock de esa referencia en la Base de Datos (así aprovecho el acceso a la web para hacer scrapping)
                $consulta_inserta_stock_ggoya = "INSERT INTO stock_ggoya
                                                   (stock_ggoya_ref,
                                                    stock_ggoya_fecha,
                                                    stock_ggoya_hora,
                                                    stock_ggoya_stock)
                                                VALUES
                                                   ('$ref_ggoya',
                                                    '$fecha_actual',
                                                    '$hora_actual',
                                                    '$stock_ggoya');";
                $bd->Execute($consulta_inserta_stock_ggoya);

                //Compruebo si hay ref. extras o a excluir, consulto su stock y añado o sustraigo sus stock de $stock_ggoya
                if($ref_ggoya_extras != "") {
                    $array_ref_ggoya_extras = explode("/", $ref_ggoya_extras);

                    foreach($array_ref_ggoya_extras as $ref_ggoya_extra) {
                        $stock_ggoya_extra = consulta_stock_ggoya($ref_ggoya_extra);

                        //INSERTO EL RESULTADO DE LA CONSULTA DE STOCK EN LA BD
                        //Paso a almacenar el valor de stock de esa referencia en la Base de Datos (así aprovecho el acceso a la web para hacer scrapping)
                        $consulta_inserta_stock_ggoya_extra = "INSERT INTO stock_ggoya
                                                                   (stock_ggoya_ref,
                                                                    stock_ggoya_fecha,
                                                                    stock_ggoya_hora,
                                                                    stock_ggoya_stock)
                                                                VALUES
                                                                   ('$ref_ggoya_extra',
                                                                    '$fecha_actual',
                                                                    '$hora_actual',
                                                                    '$stock_ggoya_extra');";
                        $bd->Execute($consulta_inserta_stock_ggoya_extra);

                        //Anado el stock de cada ref. extra al de la ref. ppal.
                        $stock_ggoya = $stock_ggoya + $stock_ggoya_extra;
                    }
                }

                if($ref_ggoya_excluir != "") {
                    $array_ref_ggoya_excluir = explode("/", $ref_ggoya_excluir);

                    foreach($array_ref_ggoya_excluir as $ref_ggoya_excl) {
                        $stock_ggoya_excl = consulta_stock_ggoya($ref_ggoya_excl);

                        //INSERTO EL RESULTADO DE LA CONSULTA DE STOCK EN LA BD
                        //Paso a almacenar el valor de stock de esa referencia en la Base de Datos (así aprovecho el acceso a la web para hacer scrapping)
                        $consulta_inserta_stock_ggoya_excl = "INSERT INTO stock_ggoya
                                                                   (stock_ggoya_ref,
                                                                    stock_ggoya_fecha,
                                                                    stock_ggoya_hora,
                                                                    stock_ggoya_stock)
                                                                VALUES
                                                                   ('$ref_ggoya_excl',
                                                                    '$fecha_actual',
                                                                    '$hora_actual',
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
            
        } catch (Exception $e) {
            $stock_ggoya = -2; //Se ha producido un error al consultar el stock de esa referencia
        }
        
        
        
        
        //He establecido valores de control (-1) para los casos en que no se disponga del dato de stock de cualquier competidor
        //para luego si recupero -1 saber que se trata de que el dato no estaba disponible cuando se guardó el registro
        //He establecido valores de control (-2) para los casos en que se produzca un error en el proceso
        //para luego si recupero -2 saber que se trata de que el dato no se ha obtenido por un error en el proceso
                
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
                                                                '$fecha_actual',
                                                                '$hora_actual');";
        $bd->Execute($consulta_inserta_stock_makito_vs_todos);
        
        
        //MUESTRO LOS DATOS DEL ARTÍCULO ELEGIDO Y LOS DE LOS STOCKS OBTENIDOS
        
        //Antes de hacerlo sustituyo los valores de stock = -1 (significa en la BD que aún no se ha establecido correspondencia entre referencias para ese comeptidor) o -1 (significa en la BD que no se ha obtenido ese dato por un error en el proceso) por 0 (para que el valor que se muestra en la gráfica no esté alterado)
        
        if ($stock_cifra == -1 or $stock_cifra == -2) {
            $stock_cifra = 0;
        }
        
        if ($stock_giving == -1 or $stock_giving == -2) {
            $stock_giving = 0;
        }
        
        if ($stock_mob == -1 or $stock_mob == -2) {
            $stock_mob = 0;
        }
        
        if ($stock_ps == -1 or $stock_ps == -2) {
            $stock_ps = 0;
        }
        
        if ($stock_pf == -1 or $stock_pf == -2) {
            $stock_pf = 0;
        }
        
        if ($stock_ggoya == -1 or $stock_ggoya == -2) {
            $stock_ggoya = 0;
        }
        
        
        ?>
        
        <!--Load the AJAX API-->
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <script type="text/javascript">

          // Load the Visualization API and the corechart package.
          google.charts.load('current', {'packages':['corechart']});

          // Set a callback to run when the Google Visualization API is loaded.
          google.charts.setOnLoadCallback(drawChart);

          // Callback that creates and populates a data table,
          // instantiates the pie chart, passes in the data and
          // draws it.
          function drawChart() {

            // Create the data table.
            /*var data = new google.visualization.DataTable();
            data.addColumn('string', 'Competidor');
            data.addColumn('number', 'Stock Actual');
            data.addRows([
              ['Cifra', <?php echo $stock_cifra; ?>],
              ['Giving', <?php echo $stock_giving; ?>],
              ['Mob', <?php echo $stock_mob; ?>],
              ['Ps', <?php echo $stock_ps; ?>],
              ['Pf', <?php echo $stock_pf; ?>],
              ['GGoya', <?php echo $stock_ggoya; ?>]
            ]);*/
              
            var data = google.visualization.arrayToDataTable([
                ['Competidor', 'Stock Actual', { role: 'style' }, { role: 'annotation' } ],
                ['Cifra', <?php echo $stock_cifra; ?>, 'stroke-color: #e2975d; stroke-width: 4; fill-color: #e2975d; fill-opacity: 0.5', '<?php if ($stock_cifra != 0) { echo number_format($stock_cifra, 0, ',', '.'); } ?>'],
                ['Giving', <?php echo $stock_giving; ?>, 'stroke-color: #447c69; stroke-width: 4; fill-color: #447c69; fill-opacity: 0.5', '<?php if ($stock_giving != 0) { echo number_format($stock_giving, 0, ',', '.');; } ?>'],
                ['Mob', <?php echo $stock_mob; ?>, 'stroke-color: #a34974; stroke-width: 4; fill-color: #a34974; fill-opacity: 0.5', '<?php if ($stock_mob != 0) { echo number_format($stock_mob, 0, ',', '.');; } ?>'],
                ['Ps', <?php echo $stock_ps; ?>, 'stroke-color: #e9d78e; stroke-width: 4; fill-color: #e9d78e; fill-opacity: 0.5', '<?php if ($stock_ps != 0) { echo number_format($stock_ps, 0, ',', '.');; } ?>'],
                ['Pf', <?php echo $stock_pf; ?>, 'stroke-color: #4e2472; stroke-width: 4; fill-color: #4e2472; fill-opacity: 0.5', '<?php if ($stock_pf != 0) { echo number_format($stock_pf, 0, ',', '.');; } ?>'],
                ['GGoya', <?php echo $stock_ggoya; ?>, 'stroke-color: #7c9fb0; stroke-width: 4; fill-color: #7c9fb0; fill-opacity: 0.5', '<?php if ($stock_ggoya != 0) { echo number_format($stock_ggoya, 0, ',', '.');; } ?>']
            ]);
              
            var titulo = "Ref: <?php echo $ref_makito." / ".$nombre_makito; ?> - Stock de los Competidores: <?php echo $fecha_actual; ?>";

            // Set chart options
            var options = {title: titulo,
                           titleTextStyle: {
                             color: '#133b12',
                             fontSize: 16,
                             bold: false
                           },
                           'width':800,
                           'height':300,
                            annotations: {'alwaysOutside':true},
                            animation:{
                              duration: 5000,
                              easing: 'out',
                              startup: true
                            },
                            legend: { position: 'none' },
                            backgroundColor: {'stroke':'#aaa', 'strokeWidth':2}};

            // Instantiate and draw our chart, passing in some options.
            var chart = new google.visualization.BarChart(document.getElementById('chart_div'));
            chart.draw(data, options);
          }
        </script>
        
        
        <div class="container" id="cuerpo_home">
      
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
                            <th class="info celdacentradahor celdacentradavert">Variación</th>
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
                                        <button type="button" class="btn btn-muted btn-sm"><span class="glyphicon glyphicon-stats" aria-hidden="true"></span></button>
                                    </a>  
                                </td>
                                <?php
                            } else {
                                ?>                            
                                <td class="celdacentradahor celdacentradavert">
                                    <a href="inicio.php?id=variacion_stock&ref=<?php echo $ref_makito; ?>" target="_blank">
                                        <button type="button" class="btn btn-info btn-sm"><span class="glyphicon glyphicon-stats" aria-hidden="true"></span></button>
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
            
            <hr>

            <!--<div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <?php
                    /*
                    if($stock_cifra != -1) {
                        echo $ref_makito." - Stock Actual del Artículo en CIFRA(".$ref_cifra."): ".$stock_cifra." unidades<br>";
                    } else {
                        echo $ref_makito." - Stock Actual del Artículo en CIFRA: No existe aún correspondencia con ninguna referencia de CIFRA<br>";
                    }

                    if($stock_giving != -1) {
                        echo $ref_makito." - Stock Actual del Artículo en GIVING(".$ref_giving."): ".$stock_giving." unidades<br>";
                    } else {
                        echo $ref_makito." - Stock Actual del Artículo en GIVING: No existe aún correspondencia con ninguna referencia de GIVING<br>";
                    }

                    if($stock_mob != -1) {
                        echo $ref_makito." - Stock Actual del Artículo en MOB(".$ref_mob."): ".$stock_mob." unidades<br>";
                    } else {
                        echo $ref_makito." - Stock Actual del Artículo en MOB: No existe aún correspondencia con ninguna referencia de MOB<br>";
                    }

                    if($stock_ps != -1) {
                        echo $ref_makito." - Stock Actual del Artículo en PS(".$ref_ps."): ".$stock_ps." unidades<br>";
                    } else {
                        echo $ref_makito." - Stock Actual del Artículo en PS: No existe aún correspondencia con ninguna referencia de PS<br>";
                    }

                    if($stock_pf != -1) {
                        echo $ref_makito." - Stock Actual del Artículo en PF(".$ref_pf."): ".$stock_pf." unidades<br>";
                    } else {
                        echo $ref_makito." - Stock Actual del Artículo en PF: No existe aún correspondencia con ninguna referencia de PF<br>";
                    }

                    if($stock_ggoya != -1) {
                        echo $ref_makito." - Stock Actual del Artículo en GGOYA(".$ref_ggoya."): ".$stock_ggoya." unidades<br>";
                    } else {
                        echo $ref_makito." - Stock Actual del Artículo en GGOYA: No existe aún correspondencia con ninguna referencia de GGOYA<br>";
                    }
                    */
                    ?>
                </div>
            </div>
            
            <hr>-->
            
                <!--DIV que mostrará el diagrama de barras-->
                <div class="row">
                    <div class="col-md-12">
                        <div id="chart_div">
                            
                        </div>
                    </div>
                </div>     

            <hr>   

            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <h5 class="text-center enmarcado2">Selector para Nueva Consulta de Stocks Actuales de los Competidores</h5>
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
                                <option class="tenue" value="">Elija una Referencia para Consultar los Stocks</option>
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
                        <span class="glyphicon glyphicon-hand-right" aria-hidden="true"></span> Consultar Stocks
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

            });

        </script>
          
        <?php
    
    } else { //Si no se ha pasado una referencia por GET, muestro un Select para elegir la referencia a Modificar
        ?>        
        
        <div class="container" id="cuerpo_home">
      
            <div class="row">
                <div class="col-md-12">
                    <h3 class="text-center enmarcado2">Consulta de Stocks Actuales de los Competidores</h3>
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
                                <option class="tenue" value="">Elija una Referencia para Consultar los Stocks</option>
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
                        <span class="glyphicon glyphicon-hand-right" aria-hidden="true"></span> Consultar Stocks
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