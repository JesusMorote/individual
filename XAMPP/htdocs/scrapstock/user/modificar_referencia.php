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
            location.replace('inicio.php?id=modificar_referencia&ref=<?php echo $ref_elegida; ?>');
        </script>
        <?php
    }
    
    //Obtengo los datos correspondientes a la referencia obtenida por GET
    if(isset($_GET['ref'])) {
        $ref = $_GET['ref'];
        
        //Consulto los datos
        $consulta_ref = "SELECT *
                            FROM referencias_makito_vs_competidores
                            WHERE referencias_makito_vs_competidores_ref_makito LIKE '$ref';";
        
        $resultado_consulta_ref = $bd->Execute($consulta_ref);
        
        $ref_makito_almac = $resultado_consulta_ref->fields['referencias_makito_vs_competidores_ref_makito'];
        $nombre_makito_almac = $resultado_consulta_ref->fields['referencias_makito_vs_competidores_nombre_makito'];
        $ref_cifra_almac = $resultado_consulta_ref->fields['referencias_makito_vs_competidores_cifra'];
        $ref_cifra_extras_almac = $resultado_consulta_ref->fields['referencias_makito_vs_competidores_cifra_extras'];
        $ref_cifra_excluir_almac = $resultado_consulta_ref->fields['referencias_makito_vs_competidores_cifra_excluir'];
        $ref_giving_almac = $resultado_consulta_ref->fields['referencias_makito_vs_competidores_gvng'];
        $ref_giving_extras_almac = $resultado_consulta_ref->fields['referencias_makito_vs_competidores_gvng_extras'];
        $ref_giving_excluir_almac = $resultado_consulta_ref->fields['referencias_makito_vs_competidores_gvng_excluir'];
        $ref_mob_almac = $resultado_consulta_ref->fields['referencias_makito_vs_competidores_mob'];
        $ref_mob_extras_almac = $resultado_consulta_ref->fields['referencias_makito_vs_competidores_mob_extras'];
        $ref_mob_excluir_almac = $resultado_consulta_ref->fields['referencias_makito_vs_competidores_mob_excluir'];
        $ref_ps_almac = $resultado_consulta_ref->fields['referencias_makito_vs_competidores_ps'];
        $ref_ps_extras_almac = $resultado_consulta_ref->fields['referencias_makito_vs_competidores_ps_extras'];
        $ref_ps_excluir_almac = $resultado_consulta_ref->fields['referencias_makito_vs_competidores_ps_excluir'];
        $ref_pf_almac = $resultado_consulta_ref->fields['referencias_makito_vs_competidores_pf'];
        $ref_pf_extras_almac = $resultado_consulta_ref->fields['referencias_makito_vs_competidores_pf_extras'];
        $ref_pf_excluir_almac = $resultado_consulta_ref->fields['referencias_makito_vs_competidores_pf_excluir'];
        $ref_ggoya_almac = $resultado_consulta_ref->fields['referencias_makito_vs_competidores_ggy'];
        $ref_ggoya_extras_almac = $resultado_consulta_ref->fields['referencias_makito_vs_competidores_ggy_extras'];
        $ref_ggoya_excluir_almac = $resultado_consulta_ref->fields['referencias_makito_vs_competidores_ggy_excluir'];
        $frecuencia_almac = $resultado_consulta_ref->fields['referencias_makito_vs_competidores_frecuencia'];
        
        
        //Actualización de la Referencia
        if (isset($_POST['nombre_makito'])) {

            $ref_makito = $ref;
            $nombre_makito = $_POST['nombre_makito'];

            //Guardado de la Imagen del Artículo
            if (isset($_FILES['imagen_makito'])) {
                $nombre_imagen_makito = $ref_makito.'p.jpg';
                $nombre_temporal_imagen_makito = $_FILES['imagen_makito']['tmp_name'];
                
                if($_FILES['imagen_makito']['name'] != "") {
                    //Elimino antes la Imagen del Artículo Previa (si existiera)
                    if(file_exists('img/articulo/'.$nombre_imagen_makito)) {
                        unlink("img/articulo/".$nombre_imagen_makito);
                    }
                    //Subo la nueva imagen del artículo
                    move_uploaded_file($nombre_temporal_imagen_makito,"img/articulo/".$nombre_imagen_makito);
                }
            }    
            //FIN de Guardado de la Imagen del Artículo

            //Correspondencia con Referencias de Cifra
            if (isset($_POST['ref_cifra'])) {
                $ref_cifra = $_POST['ref_cifra'];
            } else {
                $ref_cifra = "";
            }

            if (isset($_POST['ref_cifra_extras'])) {
                $ref_cifra_extras = $_POST['ref_cifra_extras'];
            } else {
                $ref_cifra_extras = "";
            }

            if (isset($_POST['ref_cifra_excluir'])) {
                $ref_cifra_excluir = $_POST['ref_cifra_excluir'];
            } else {
                $ref_cifra_excluir = "";
            }
            //FIN de Correspondencia con Referencias de Cifra

            //Correspondencia con Referencias de Giving
            if (isset($_POST['ref_giving'])) {
                $ref_giving = $_POST['ref_giving'];
            } else {
                $ref_giving = "";
            }

            if (isset($_POST['ref_giving_extras'])) {
                $ref_giving_extras = $_POST['ref_giving_extras'];
            } else {
                $ref_giving_extras = "";
            }

            if (isset($_POST['ref_giving_excluir'])) {
                $ref_giving_excluir = $_POST['ref_giving_excluir'];
            } else {
                $ref_giving_excluir = "";
            }
            //FIN de Correspondencia con Referencias de Giving

            //Correspondencia con Referencias de Mob
            if (isset($_POST['ref_mob'])) {
                $ref_mob = $_POST['ref_mob'];
            } else {
                $ref_mob = "";
            }

            if (isset($_POST['ref_mob_extras'])) {
                $ref_mob_extras = $_POST['ref_mob_extras'];
            } else {
                $ref_mob_extras = "";
            }

            if (isset($_POST['ref_mob_excluir'])) {
                $ref_mob_excluir = $_POST['ref_mob_excluir'];
            } else {
                $ref_mob_excluir = "";
            }
            //FIN de Correspondencia con Referencias de Mob

            //Correspondencia con Referencias de PS
            if (isset($_POST['ref_ps'])) {
                $ref_ps = $_POST['ref_ps'];
            } else {
                $ref_ps = "";
            }

            if (isset($_POST['ref_ps_extras'])) {
                $ref_ps_extras = $_POST['ref_ps_extras'];
            } else {
                $ref_ps_extras = "";
            }

            if (isset($_POST['ref_ps_excluir'])) {
                $ref_ps_excluir = $_POST['ref_ps_excluir'];
            } else {
                $ref_ps_excluir = "";
            }
            //FIN de Correspondencia con Referencias de PS

            //Correspondencia con Referencias de PF
            if (isset($_POST['ref_pf'])) {
                $ref_pf = $_POST['ref_pf'];
            } else {
                $ref_pf = "";
            }

            if (isset($_POST['ref_pf_extras'])) {
                $ref_pf_extras = $_POST['ref_pf_extras'];
            } else {
                $ref_pf_extras = "";
            }

            if (isset($_POST['ref_pf_excluir'])) {
                $ref_pf_excluir = $_POST['ref_pf_excluir'];
            } else {
                $ref_pf_excluir = "";
            }
            //FIN de Correspondencia con Referencias de PF

            //Correspondencia con Referencias de G.GOYA
            if (isset($_POST['ref_ggoya'])) {
                $ref_ggoya = $_POST['ref_ggoya'];
            } else {
                $ref_ggoya = "";
            }

            if (isset($_POST['ref_ggoya_extras'])) {
                $ref_ggoya_extras = $_POST['ref_ggoya_extras'];
            } else {
                $ref_ggoya_extras = "";
            }

            if (isset($_POST['ref_ggoya_excluir'])) {
                $ref_ggoya_excluir = $_POST['ref_ggoya_excluir'];
            } else {
                $ref_ggoya_excluir = "";
            }
            //FIN de Correspondencia con Referencias de G.GOYA

            //Frecuencia de Actualización de Stock
            $frecuencia = $_POST['frecuencia'];
            
            if ($frecuencia == "") {
                $frecuencia = "nunca";
            }
            
            if($ref_cifra == "" and $ref_giving == "" and $ref_mob == "" and $ref_ps == "" and $ref_pf == "" and $ref_ggoya == "") {
                $frecuencia = "nunca";
            }
            //FIN de Frecuencia de Actualización de Stock

            //Actualización de la referencia en la BD
            $consulta_insercion = "UPDATE referencias_makito_vs_competidores
                                    SET referencias_makito_vs_competidores_nombre_makito = '$nombre_makito',
                                        referencias_makito_vs_competidores_cifra = '$ref_cifra',
                                        referencias_makito_vs_competidores_cifra_extras = '$ref_cifra_extras',
                                        referencias_makito_vs_competidores_cifra_excluir = '$ref_cifra_excluir',
                                        referencias_makito_vs_competidores_gvng = '$ref_giving',
                                        referencias_makito_vs_competidores_gvng_extras = '$ref_giving_extras',
                                        referencias_makito_vs_competidores_gvng_excluir = '$ref_giving_excluir',
                                        referencias_makito_vs_competidores_mob = '$ref_mob',
                                        referencias_makito_vs_competidores_mob_extras = '$ref_mob_extras',
                                        referencias_makito_vs_competidores_mob_excluir = '$ref_mob_excluir',
                                        referencias_makito_vs_competidores_ps = '$ref_ps',
                                        referencias_makito_vs_competidores_ps_extras = '$ref_ps_extras',
                                        referencias_makito_vs_competidores_ps_excluir = '$ref_ps_excluir',
                                        referencias_makito_vs_competidores_pf = '$ref_pf',
                                        referencias_makito_vs_competidores_pf_extras = '$ref_pf_extras',
                                        referencias_makito_vs_competidores_pf_excluir = '$ref_pf_excluir',
                                        referencias_makito_vs_competidores_ggy = '$ref_ggoya',
                                        referencias_makito_vs_competidores_ggy_extras = '$ref_ggoya_extras',
                                        referencias_makito_vs_competidores_ggy_excluir = '$ref_ggoya_excluir',
                                        referencias_makito_vs_competidores_frecuencia = '$frecuencia'
                                    WHERE referencias_makito_vs_competidores_ref_makito LIKE '$ref_makito';";
            //echo $consulta_insercion;
            $insercion = $bd->Execute($consulta_insercion);
            //FIN de la Inserción de la nueva referencia en la BD

            ?>
            <script>
                alert("La referencia se ha actualizado correctamente");
                location.replace("inicio.php?id=listar_referencias");
            </script>
            <?php
        }
        //Fin de Actualización de la Referencia
        
        ?>
        <div class="container">
            <div class="row">
                <div class="col-md-12">            
                    <h3 class="enmarcado2 indentado text-center">Modificación de Referencia Existente</h3>
                </div>
            </div>

            <div class="form-group row">
                <form name="modificar_ref" action="" method="post" class="form-horizontal" enctype="multipart/form-data">

                <div class="col-md-12 text-center">
                    <img src="img/logos/makito.png" width="125px">
                    <br>
                    <br>
                </div>

                <div class="col-md-4">
                    <div class="enmarcado4 margin_inf">
                        <h5 class="text-center">Imagen del Artículo <small>(actual)</small></h5>
                        <div class="text-center">
                        <?php
                        if(file_exists('img/articulo/'.$ref_makito_almac.'p.jpg')) {
                            ?>
                            <img src="img/articulo/<?php echo $ref_makito_almac; ?>p.jpg" width="150px" class="enmarcado3">
                            <?php
                        } else {
                            ?>
                            <img src="img/varios/noimg.jpg" width="150px" class="enmarcado3">
                            <?php
                        }
                        ?>
                        </div>
                        <hr>
                        <small>
                            <input type="file" name="imagen_makito" accept="image/jpeg" class="ancho_completo">
                            <p class="help-block">
                                &nbsp;&nbsp;&nbsp;Elija otra imagen si desea sustituir la imagen actual
                                <br>
                                &nbsp;&nbsp;&nbsp;Extensiones soportadas: .jpg
                                <br>
                                &nbsp;&nbsp;&nbsp;Resolución recomendada: 150x150 px.
                            </p>
                        </small>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="row">
                        <label for="ref_makito" class="sr-only">Referencia:</label>
                        <div class="input-group margin_inf">
                            <div class="input-group-addon">Referencia:</div>
                            <input type="text" class="form-control" name="ref_makito" id="ref_makito" maxlength="12" required placeholder="Campo Obligatorio" value="<?php echo $ref_makito_almac; ?>" disabled>
                        </div>
                    </div>
                    <div class="row margin_inf">
                        <h5 class="indentado">Captura de Stock</h5>
                        <select class="form-control" name="frecuencia" id="frecuencia">
                            <option value="" style="color: grey;">Frecuencia de Captura de Stock</option>
                            <option disabled>────────────────────</option>
                            <?php
                            if ($frecuencia_almac == "cada6h") {
                                ?>
                                <option value="cada6h" selected>Cada 6 horas</option>
                                <?php
                            } else {
                                ?>
                                <option value="cada6h">Cada 6 horas</option>
                                <?php
                            }

                            if ($frecuencia_almac == "cada12h") {
                                ?>
                                <option value="cada12h" selected>Cada 12 horas</option>
                                <?php
                            } else {
                                ?>
                                <option value="cada12h">Cada 12 horas</option>
                                <?php
                            }

                            if ($frecuencia_almac == "diaria") {
                                ?>
                                <option value="diaria" selected>Diariamente</option>
                                <?php
                            } else {
                                ?>
                                <option value="diaria">Diariamente</option>
                                <?php
                            }

                            if ($frecuencia_almac == "semanal") {
                                ?>
                                <option value="semanal" selected>Semanalmente</option> 
                                <?php
                            } else {
                                ?>
                                <option value="semanal">Semanalmente</option> 
                                <?php
                            }

                            if ($frecuencia_almac == "mensual") {
                                ?>
                                <option value="mensual" selected>Mensualmente</option>
                                <?php
                            } else {
                                ?>
                                <option value="mensual">Mensualmente</option>
                                <?php
                            }
                            ?>
                            <option disabled>────────────────────</option>
                            <?php
                            if ($frecuencia_almac == "nunca") {
                                ?>
                                <option value="nunca" selected>Nunca</option> 
                                <?php
                            } else {
                                ?>
                                <option value="nunca">Nunca</option> 
                                <?php
                            }
                            ?>

                        </select>
                    </div>
                </div>

                <div class="col-md-5">
                    <label for="nombre_makito" class="sr-only">Nombre:</label>
                    <div class="input-group">
                        <div class="input-group-addon">Nombre:</div>
                        <input type="text" class="form-control" name="nombre_makito" id="nombre_makito" maxlength="70" required placeholder="Campo Obligatorio" value="<?php echo $nombre_makito_almac; ?>">
                    </div>
                </div>            
            </div>

            <hr>

            <div class="form-group row">
                <div class="col-md-12 text-center">
                    <img src="img/logos/cifra.jpg" width="85px">
                    <br>
                    <br>
                </div>
                <div class="col-md-3">
                    <label for="ref_cifra" class="sr-only">Ref. Ppal:</label>
                    <div class="input-group margin_inf">
                        <div class="input-group-addon">Ref. Ppal:</div>
                        <input type="text" class="form-control ref_ppal" name="ref_cifra" id="ref_cifra" maxlength="30" placeholder="Referencia Principal" value="<?php echo $ref_cifra_almac; ?>">
                    </div>
                </div>
                <div class="col-md-5">
                    <label for="ref_cifra_extras" class="sr-only">Ref. Extras:</label>
                    <div class="input-group margin_inf">
                        <div class="input-group-addon">Ref. Extras:</div>
                        <input type="text" class="form-control" name="ref_cifra_extras" id="ref_cifra_extras" maxlength="100" placeholder="Introduzca una Ref. Ppal." disabled value="<?php echo $ref_cifra_extras_almac; ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <label for="ref_cifra_excluir" class="sr-only">Ref. Excluir:</label>
                    <div class="input-group">
                        <div class="input-group-addon">Ref. Excluir:</div>
                        <input type="text" class="form-control" name="ref_cifra_excluir" id="ref_cifra_excluir" maxlength="100" placeholder="Introduzca una Ref. Ppal." disabled value="<?php echo $ref_cifra_excluir_almac; ?>">
                    </div>
                </div>
                <div class="col-md-12">
                    <br>
                    <h5 class="text-center">Correspondencia con Referencias CIFRA</h5> 
                </div>           
            </div>

            <hr>

            <div class="form-group row">
                <div class="col-md-12 text-center">
                    <img src="img/logos/giving.png" width="159px">
                    <br>
                    <br>
                </div>
                <div class="col-md-3">
                    <label for="ref_giving" class="sr-only">Ref. Ppal:</label>
                    <div class="input-group">
                        <div class="input-group-addon">Ref. Ppal:</div>
                        <input type="text" class="form-control ref_ppal" name="ref_giving" id="ref_giving" maxlength="30" placeholder="Referencia Principal" value="<?php echo $ref_giving_almac; ?>">
                    </div>
                </div>
                <div class="col-md-5">
                    <label for="ref_giving_extras" class="sr-only">Ref. Extras:</label>
                    <div class="input-group">
                        <div class="input-group-addon">Ref. Extras:</div>
                        <input type="text" class="form-control" name="ref_giving_extras" id="ref_giving_extras" maxlength="100" placeholder="Introduzca una Ref. Ppal." disabled value="<?php echo $ref_giving_extras_almac; ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <label for="ref_giving_excluir" class="sr-only">Ref. Excluir:</label>
                    <div class="input-group">
                        <div class="input-group-addon">Ref. Excluir:</div>
                        <input type="text" class="form-control" name="ref_giving_excluir" id="ref_giving_excluir" maxlength="100" placeholder="Introduzca una Ref. Ppal." disabled value="<?php echo $ref_giving_excluir_almac; ?>">
                    </div>
                </div>
                <div class="col-md-12">
                    <br>
                    <h5 class="text-center">Correspondencia con Referencias GIVING</h5> 
                </div>            
            </div>

            <hr>

            <div class="form-group row">
                <div class="col-md-12 text-center">
                    <img src="img/logos/mob.png" width="250px">
                    <br>
                    <br>
                </div>
                <div class="col-md-3">
                    <label for="ref_mob" class="sr-only">Ref. Ppal:</label>
                    <div class="input-group">
                        <div class="input-group-addon">Ref. Ppal:</div>
                        <input type="text" class="form-control ref_ppal" name="ref_mob" id="ref_mob" maxlength="30" placeholder="Referencia Principal" value="<?php echo $ref_mob_almac; ?>">
                    </div>
                </div>
                <div class="col-md-5">
                    <label for="ref_mob_extras" class="sr-only">Ref. Extras:</label>
                    <div class="input-group">
                        <div class="input-group-addon">Ref. Extras:</div>
                        <input type="text" class="form-control" name="ref_mob_extras" id="ref_mob_extras" maxlength="100" placeholder="Introduzca una Ref. Ppal." disabled value="<?php echo $ref_mob_extras_almac; ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <label for="ref_mob_excluir" class="sr-only">Ref. Excluir:</label>
                    <div class="input-group">
                        <div class="input-group-addon">Ref. Excluir:</div>
                        <input type="text" class="form-control" name="ref_mob_excluir" id="ref_mob_excluir" maxlength="100" placeholder="Introduzca una Ref. Ppal." disabled value="<?php echo $ref_mob_excluir_almac; ?>">
                    </div>
                </div>   
                <div class="col-md-12">
                    <br>
                    <h5 class="text-center">Correspondencia con Referencias MOB</h5> 
                </div>            
            </div>

            <hr>

            <div class="form-group row">
                <div class="col-md-12 text-center">
                    <img src="img/logos/ps.svg" height="50px">
                    <br>
                    <br>
                </div>
                <div class="col-md-3">
                    <label for="ref_ps" class="sr-only">Ref. Ppal:</label>
                    <div class="input-group">
                        <div class="input-group-addon">Ref. Ppal:</div>
                        <input type="text" class="form-control ref_ppal" name="ref_ps" id="ref_ps" maxlength="30" placeholder="Referencia Principal" value="<?php echo $ref_ps_almac; ?>">
                    </div>
                </div>
                <div class="col-md-5">
                    <label for="ref_ps_extras" class="sr-only">Ref. Extras:</label>
                    <div class="input-group">
                        <div class="input-group-addon">Ref. Extras:</div>
                        <input type="text" class="form-control" name="ref_ps_extras" id="ref_ps_extras" maxlength="100" placeholder="Introduzca una Ref. Ppal." disabled value="<?php echo $ref_ps_extras_almac; ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <label for="ref_ps_excluir" class="sr-only">Ref. Excluir:</label>
                    <div class="input-group">
                        <div class="input-group-addon">Ref. Excluir:</div>
                        <input type="text" class="form-control" name="ref_ps_excluir" id="ref_ps_excluir" maxlength="100" placeholder="Introduzca una Ref. Ppal." disabled value="<?php echo $ref_ps_excluir_almac; ?>">
                    </div>
                </div>   
                <div class="col-md-12">
                    <br>
                    <h5 class="text-center">Correspondencia con Referencias PS</h5>
                </div>             
            </div>

            <hr>

            <div class="form-group row">
                <div class="col-md-12 text-center">
                    <img src="img/logos/pf.png" width="175px">
                    <br>
                    <br>
                </div>
                <div class="col-md-3">
                    <label for="ref_pf" class="sr-only">Ref. Ppal:</label>
                    <div class="input-group">
                        <div class="input-group-addon">Ref. Ppal:</div>
                        <input type="text" class="form-control ref_ppal" name="ref_pf" id="ref_pf" maxlength="30" placeholder="Referencia Principal" value="<?php echo $ref_pf_almac; ?>">
                    </div>
                </div>
                <div class="col-md-5">
                    <label for="ref_pf_extras" class="sr-only">Ref. Extras:</label>
                    <div class="input-group">
                        <div class="input-group-addon">Ref. Extras:</div>
                        <input type="text" class="form-control" name="ref_pf_extras" id="ref_pf_extras" maxlength="100" placeholder="Introduzca una Ref. Ppal." disabled value="<?php echo $ref_pf_extras_almac; ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <label for="ref_pf_excluir" class="sr-only">Ref. Excluir:</label>
                    <div class="input-group">
                        <div class="input-group-addon">Ref. Excluir:</div>
                        <input type="text" class="form-control" name="ref_pf_excluir" id="ref_pf_excluir" maxlength="100" placeholder="Introduzca una Ref. Ppal." disabled value="<?php echo $ref_pf_excluir_almac; ?>">
                    </div>
                </div>    
                <div class="col-md-12">
                    <br>
                    <h5 class="text-center">Correspondencia con Referencias PF</h5>
                </div>             
            </div>

            <hr>

            <div class="form-group row">
                <div class="col-md-12 text-center">
                    <img src="img/logos/ggoya.svg" height="70px">
                    <br>
                    <br>
                </div>
                <div class="col-md-3">
                    <label for="ref_ggoya" class="sr-only">Ref. Ppal:</label>
                    <div class="input-group">
                        <div class="input-group-addon">Ref. Ppal:</div>
                        <input type="text" class="form-control ref_ppal" name="ref_ggoya" id="ref_ggoya" maxlength="30" placeholder="Referencia Principal" value="<?php echo $ref_ggoya_almac; ?>">
                    </div>
                </div>
                <div class="col-md-5">
                    <label for="ref_ggoya_extras" class="sr-only">Ref. Extras:</label>
                    <div class="input-group">
                        <div class="input-group-addon">Ref. Extras:</div>
                        <input type="text" class="form-control" name="ref_ggoya_extras" id="ref_ggoya_extras" maxlength="100" placeholder="Introduzca una Ref. Ppal." disabled value="<?php echo $ref_ggoya_extras_almac; ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <label for="ref_ggoya_excluir" class="sr-only">Ref. Excluir:</label>
                    <div class="input-group">
                        <div class="input-group-addon">Ref. Excluir:</div>
                        <input type="text" class="form-control" name="ref_ggoya_excluir" id="ref_ggoya_excluir" maxlength="100" placeholder="Introduzca una Ref. Ppal." disabled value="<?php echo $ref_ggoya_excluir_almac; ?>">
                    </div>
                </div>      
                <div class="col-md-12">
                    <br>
                    <h5 class="text-center">Correspondencia con Referencias G.GOYA</h5>
                </div>            
            </div>

            <hr>

            <div class="form-group row text-center">
                <button type="submit" class="btn btn-primary" id="btn_alta">Modificar la referencia</button>
                </form>
            </div>

        </div>
        
        <?php
        
    } else { //Si no se ha pasado una referencia por GET, muestro un Select para elegir la referencia a Modificar
        ?>        
        
        <div class="container" id="cuerpo_home">
      
            <div class="row">
                <div class="col-md-12">
                    <h3 class="text-center enmarcado2">Modificación de Referencia Existente</h3>
                </div>
            </div>

            <!--Bloque para permitir elegir la referencia a modificar-->
            <div class="row">
                <div class="col-md-3 col-md-offset-1 text-center">
                    <h4 class="enmarcado5">Referencia</h4>
                </div>
                <div class="col-md-4">
                    <form class="form-horizontal" name="selector_ref" method="post" action="">
                        <div class="form-group">                
                            <select class="form-control" name="filtro_referencia" id="filtro_referencia" required>
                                <option class="tenue" value="">Elija una Referencia para Modificar</option>
                                <option disabled>─────────────────────────</option>

                                <?php

                                $consulta_referencias = "SELECT referencias_makito_vs_competidores_ref_makito, 
                                                                referencias_makito_vs_competidores_nombre_makito
                                                            FROM referencias_makito_vs_competidores
                                                            ORDER BY referencias_makito_vs_competidores_ref_makito ASC;";

                                $resultado_consulta_referencias = $bd->Execute($consulta_referencias);

                                while (!($resultado_consulta_referencias->EOF)){
                                    $ref_articulo = $resultado_consulta_referencias->fields['referencias_makito_vs_competidores_ref_makito'];
                                    $nombre_articulo = $resultado_consulta_referencias->fields['referencias_makito_vs_competidores_nombre_makito'];

                                    echo '<option value="'.$ref_articulo.'">'.$ref_articulo.' - '.$nombre_articulo.'</option>';

                                    $resultado_consulta_referencias->MoveNext();    
                                }

                                ?>

                            </select>
                        </div>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary btn-block">
                        <span class="glyphicon glyphicon-hand-right" aria-hidden="true"></span> Modificar Referencia
                    </button>
                    </form>
                </div>            
            </div>
            <!--FIN de Bloque para permitir elegir la referencia a modificar-->
            
            <hr>
            
        </div>
        
        <?php
        
    }
    
    ?>
    
    <script src="../js/jquery.js"></script>
    
    <script>
        
        $(document).ready(function()  {
            
            var inputs = $("input");
            
            inputs.each(function() {
                if ($(this).val() != "") {
                    $(this).removeAttr("disabled");
                }
            });
            
            $("#ref_makito").attr("disabled", "disabled");
            
            var inputs_ppales = $(".ref_ppal");
            
            inputs_ppales.each(function() {
                if ($(this).val() != "") {
                    $(this).parent().parent().next().find("input").removeAttr("disabled");
                    $(this).parent().parent().next().find("input").attr("placeholder","Correspondencias Extra (separar con /)");
                    $(this).parent().parent().next().next().find("input").removeAttr("disabled");
                    $(this).parent().parent().next().next().find("input").attr("placeholder","Referencias a Excluir (separar con /)");
                }
            });
            
            
            $(".ref_ppal").blur(function() {
                
                var contenido_input = $(this).val();
                
                if (contenido_input != "") {
                    $(this).parent().parent().next().find("input").removeAttr("disabled");
                    $(this).parent().parent().next().find("input").attr("placeholder","Correspondencias Extra (separar con /)");
                    $(this).parent().parent().next().next().find("input").removeAttr("disabled");
                    $(this).parent().parent().next().next().find("input").attr("placeholder","Referencias a Excluir (separar con /)");
                } else {
                    $(this).parent().parent().next().find("input").val("");
                    $(this).parent().parent().next().find("input").attr("disabled","disabled");
                    $(this).parent().parent().next().find("input").attr("placeholder","Introduzca una Ref. Ppal.");
                    $(this).parent().parent().next().next().find("input").val("");
                    $(this).parent().parent().next().next().find("input").attr("disabled","disabled");
                    $(this).parent().parent().next().next().find("input").attr("placeholder","Introduzca una Ref. Ppal.");
                }
                
            });
            
        });
        
    </script>    
    
    <?php
    
}

?>