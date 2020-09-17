<?php 

//######################## DETECCIÓN DE IDIOMA DEL NAVEGADOR ########################
session_start();

if(!(isset($_GET['idioma']))){
    
    if(!(isset($_SESSION['idioma']))){

        $idiomanav=substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);

        if($idiomanav=='es'){
            $idioma = 1;
        }
        if($idiomanav=='en'){
            $idioma = 2;
        }
        
        if(($idiomanav!='nl') or ($idiomanav!='en') or ($idiomanav!='fr') or ($idiomanav!='pt') or ($idiomanav!='de') or ($idiomanav!='it') or ($idiomanav!='nl')){
            $idioma = 1;
        }
        
        $_SESSION['idioma']=$idioma;
        
    } else {
        
        $idioma=$_SESSION['idioma'];
        
    }   
    
}else{
    
    $idioma=$_GET['idioma'];
    $_SESSION['idioma']=$idioma;
    
}

//echo $idioma;
//######################## FIN DETECCIÓN DE IDIOMA DEL NAVEGADOR ########################

require_once('user/variables.php');

$img=rand(1, 6);


//######################## LOGGIN AUTOMATICO PARA CLIENTES QUE VIENEN DESDE MAKITO.ES ########################

if ((isset($_POST['mktocd'])) AND (isset($_POST['mktocln']))){

    $mktocln=$_POST['mktocln'];
    $mktocd=$_POST['mktocd'];

    $inicializador=md5($mktocln.".mak%85LaraGarro");

    if ($inicializador==$mktocd){

        require_once 'inc/conexion2.php';

        //session_start();

        $consulta="SELECT * FROM cli_cliente WHERE cli_cliente_codigo=$mktocln;";
        $resultado=$bd->Execute($consulta);

        $_SESSION['cli_cliente_codigo']=$resultado->fields['cli_cliente_codigo'];
        $_SESSION['cli_cliente_nombre']=$resultado->fields['cli_cliente_nombre'];
        $_SESSION['cli_cliente_tarifa']=$resultado->fields['cli_cliente_tarifa'];
        $_SESSION['cli_cliente_moneda']=$resultado->fields['cli_cliente_moneda'];
        $visitas=$resultado->fields['cli_cliente_visitaspresupuestador']+1;
        $cliinicio=$_SESSION['cli_cliente_codigo'];

        $insertavisita="UPDATE cli_cliente SET cli_cliente_visitaspresupuestador = $visitas WHERE cli_cliente_codigo='$mktocln';";
        $bd->Execute($insertavisita);

        $insertaacceso="INSERT INTO cli_acceso (cli_acceso_cliente, cli_acceso_fecha, cli_acceso_hora) VALUES ($cliinicio, curdate(), curtime());";
        $bd->Execute($insertaacceso);
        
        // Bucle que pèrmite conocer el nombre y valor de todos los parámetros que están llegando por POST
        /*while ($post = each($_POST)) {
            echo $post[0]." = ".$post[1]."<br>";
        }*/
        
        if(isset($_POST['idioma'])) {
            $idioma=$_POST['idioma'];
            $_SESSION['idioma']=$idioma;
        }

        ?>

        <script type=text/javascript>
           location.replace("user/inicio.php?id=prspstdr1");
        </script>

        <?php

    }

}

//######################## FIN LOGGIN AUTOMATICO PARA CLIENTES QUE VIENEN DESDE MAKITO.ES ########################

 ?>



<!DOCTYPE html>
<html lang="es">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Intranet Corporativa SegurGestion SL">
        <meta name="Antonio Lara" content="Loggin" >
        <link rel="icon" href="img/favicon.ico">
        <title><?php echo $presupuestadorIndex; ?> - V1.1</title>
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/custom_login.css" rel="stylesheet">
        <script src="js/ie-emulation-modes-warning.js"></script>
        <style type="text/css">

            .login-screen {
                background-image: url(../img/<?php echo $img; ?>.jpg);
                background-position: center;
                background-repeat: no-repeat;
                background-attachment: fixed;
                background-size: cover;
                -moz-background-size: cover;
                -webkit-background-size: cover;
                position: fixed;
                top: 0;
                bottom: 0;
                right: 0;
                left: 0;
            }

        </style>
    </head>
<body class="main">

<div class="login-screen"></div>
    <div class="login-center">
        <div class="container min-height" style="margin-top: 20px;">
        	<div class="row">
            <div class="container noimprimir">
              <div class="row">

                  <div class="col-md-6 col-xs-12 text-left">
                      <a href="index.php?idioma=1">
                          <?php if($_SESSION['idioma']==1){
                              echo "<img src='user/img/banderas/esp_act.gif' alt='Español'>";
                          }else{
                              echo "<img src='user/img/banderas/esp.gif' alt='Español'>";
                              }?>
                      </a>
                      <a href="index.php?idioma=2">
                          <?php if($_SESSION['idioma']==2){
                              echo "<img src='user/img/banderas/ing_act.gif' alt='Inglés'>";
                          }else{
                              echo "<img src='user/img/banderas/ing.gif' alt='Inglés'>";
                              }?>
                      </a>
                      <a href="index.php?idioma=3">
                          <?php if($_SESSION['idioma']==3){
                              echo "<img src='user/img/banderas/ita_act.gif' alt='Francés'>";
                          }else{
                              echo "<img src='user/img/banderas/ita.gif' alt='Francés'>";
                              }?>
                      </a>
                      <a href="index.php?idioma=4">
                          <?php if($_SESSION['idioma']==4){
                              echo "<img src='user/img/banderas/fra_act.gif' alt='Francés'>";
                          }else{
                              echo "<img src='user/img/banderas/fra.gif' alt='Francés'>";
                              }?>
                      </a>
                      <a href="index.php?idioma=5">
                          <?php if($_SESSION['idioma']==5){
                              echo "<img src='user/img/banderas/por_act.gif' alt='Portugués'>";
                          }else{
                              echo "<img src='user/img/banderas/por.gif' alt='Portugués'>";
                              }?>
                      </a>
                      <a href="index.php?idioma=6">
                          <?php if($_SESSION['idioma']==6){
                              echo "<img src='user/img/banderas/grm_act.gif' alt='Alemán'>";
                          }else{
                              echo "<img src='user/img/banderas/grm.gif' alt='Alemán'>";
                              }?>
                      </a>
                      <a href="index.php?idioma=7">
                          <?php if($_SESSION['idioma']==7){
                              echo "<img src='user/img/banderas/hol_act.gif' alt='Holandés'>";
                          }else{
                              echo "<img src='user/img/banderas/hol.gif' alt='Holandés'>";
                              }?>
                      </a>
                      <br>
                      <br>
                      <br><br>
                      <img src="user/img/logo/logomktoblanco.png"><br><br>
                      <h1 class="nombre"><?php echo $presupuestadorIndex; ?> <h5 class="nombre">v1.1</h5></h1>
                    </div>

                <div class="col-md-2"></div>

                <div class="col-md-4">
                
                    <br><br><br><br>
                    <div class="login" id="card">
                    	<div class="front signin_form"> 
                        <p><?php echo $iniciarSesion; ?></p>
                          <form class="login-form" action="inc/login.inc.php" method="post">
                              <div class="form-group">
                                  <div class="input-group">
                                      <input type="text" name="user" id="user" class="form-control" placeholder="<?php echo $usuarioLoggin; ?>">
                                      <span class="input-group-addon">
                                          <i class="glyphicon glyphicon-user"></i>
                                      </span>
                                  </div>
                              </div>
                              <div class="form-group">
                                  <div class="input-group">
                                      <input type="password" name="pass" name="pass" class="form-control" placeholder="<?php echo $passLoggin; ?>">
                                      <span class="input-group-addon">
                                          <i class="glyphicon glyphicon-lock"></i>
                                      </span>
                                  </div>
                              </div>
                              
                              <div class="form-group sign-btn">
                                  <input type="submit" class="btn" value="<?php echo $btnEntrar; ?>">
                              </div>
                          </form>
                        </div>
                      
                    </div>
                </div>
            </div>
        </div>
    </div>

        <script src="js/jquery.js"></script>
        <script src="js/bootstrap.min.js"></script>
    
    </body>