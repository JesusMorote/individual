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
//CABECERA COMÚN PARA TODA LA APLICACIÓN
?>
<!DOCTYPE html>
<html lang="es">
   
    <head>
        <meta http-equiv="Content-type" content="text/html; charset=utf-8" /> 
        <title>MAKITO - CONTROL DE STOCK DE COMPETIDORES -</title>
        <link rel="icon" href="../img/stock.ico">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta name="description" content="Aplicación Web para control de Stocks">
        <link rel="stylesheet" href="../css/bootstrap.min.css">
        <link rel="stylesheet" href="../css/bootstrap-theme.min.css">
        <link rel="stylesheet" href="../css/custom.css">
        <link rel="stylesheet" href="../font-awesome/css/font-awesome.min.css">
        
        <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
        
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-107927512-1"></script>
        <script>
          window.dataLayer = window.dataLayer || [];
          function gtag(){dataLayer.push(arguments);}
          gtag('js', new Date());

          gtag('config', 'UA-107927512-1');
        </script>
    </head>

    <body>
        
        <!-## ENCABEZADO (BANNER) ##->
        <div class="container noimprimir" id="banner">
            <div class="row">                           
                <div class="col-md-12 text-center">
                    <a href="inicio.php?id=home"> 
                        <img class="superpuesto" id="banner_stock" src="img/varios/cabecera.jpg" width="100%">
                    </a>  
                </div>                              
            </div>
        </div>
        <!-## FIN DE ENCABEZADO (BANNER) ##->

        <!-## BARRA DE LOGIN ##->
        <div class="container noimprimir" id="barraLogin">
            <div class="row">            
                <div class="col-md-4 col-sm-6 col-xs-12 text-center" id="login">
                    <span class="glyphicon glyphicon-user" aria-hidden="true" id="icono_user"></span>
                    <strong>
                        <?php echo $_SESSION['user']?>
                    </strong>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <a href="salir.php" id="link_salir">
                        <span class="glyphicon glyphicon-off" aria-hidden="true" style="border: 1px solid Lightgrey; border-radius: 50%; padding: 4px;"></span> Salir
                    </a>
                </div>
            </div>
        </div>
        <!-## FIN DE BARRA DE LOGIN ##->
        
        <!-## MENÚ DE NAVEGACIÓN ##->
        <nav class="navbar navbar-inverse superpuesto" id="navegacion">
        <!--<nav class="navbar navbar-default navbar-fixed-top">-->
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse-1" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                    </button>
                    
                    <a class="navbar-brand" href="inicio.php?id=home" title="Volver al INICIO" id="boton_inicio"><i class="glyphicon glyphicon-home"></i> Inicio</a>

                </div>
                <div class="collapse navbar-collapse" id="navbar-collapse-1">
                    <ul class="nav navbar-nav">
                        <li  class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="menu_con_icono"><i class="glyphicon glyphicon-barcode"></i> MAKITO <i class="glyphicon glyphicon-option-vertical"></i> <span class="caret"></span></span></a>

                            <ul class="dropdown-menu">
                                <li>
                                    <a href="inicio.php?id=listar_referencias">
                                        <i class="glyphicon glyphicon-th-list"></i> Listar Referencias
                                    </a>
                                </li>
                                <li>
                                    <a href="inicio.php?id=alta_referencia">
                                        <i class="glyphicon glyphicon-file"></i> Álta Nueva Referencia
                                    </a>
                                </li>
                                <li>
                                    <a href="inicio.php?id=modificar_referencia">
                                        <i class="glyphicon glyphicon-pencil"></i> Modificar Referencia
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li  class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="menu_con_icono"><i class="glyphicon glyphicon-equalizer"></i> Consultar Stock <i class="glyphicon glyphicon-option-vertical"></i> <span class="caret"></span></span></a>

                            <ul class="dropdown-menu">
                                <li>
                                    <a href="inicio.php?id=stock_actual">
                                        <i class="glyphicon glyphicon-screenshot"></i> Stock Actual
                                    </a>
                                </li>
                                <li>
                                    <a href="inicio.php?id=variacion_stock">
                                        <i class="glyphicon glyphicon-stats"></i> Variaciones de Stock
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li  class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="menu_con_icono"><i class="glyphicon glyphicon-eye-open"></i> Competidores <i class="glyphicon glyphicon-option-vertical"></i> <span class="caret"></span></span></a>

                            <ul class="dropdown-menu">
                                <li>
                                    <a href="inicio.php?id=listar_referencias_cifra">
                                        <i class="glyphicon glyphicon-th-list"></i> CIFRA
                                    </a>
                                </li>
                                <li>
                                    <a href="inicio.php?id=listar_referencias_giving">
                                        <i class="glyphicon glyphicon-th-list"></i> GIVING
                                    </a>
                                </li>
                                <li>
                                    <a href="inicio.php?id=listar_referencias_mob">
                                        <i class="glyphicon glyphicon-th-list"></i> MOB
                                    </a>
                                </li>
                                <li>
                                    <a href="inicio.php?id=listar_referencias_ps">
                                        <i class="glyphicon glyphicon-th-list"></i> PS
                                    </a>
                                </li>
                                <li>
                                    <a href="inicio.php?id=listar_referencias_pf">
                                        <i class="glyphicon glyphicon-th-list"></i> PF
                                    </a>
                                </li>
                                <li>
                                    <a href="inicio.php?id=listar_referencias_ggoya">
                                        <i class="glyphicon glyphicon-th-list"></i> GGOYA
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <!--<li style="position: relative; top: 4px;">
                            <a href="#">
                                Menu Izq. 2
                            </a>
                        </li>   -->                     
                    </ul>
                   
                    <!--<ul class="nav navbar-nav navbar-right">                           
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                <span class="menu_con_icono">
                                    <i class="glyphicon glyphicon-globe"></i> Menú Despl. Dcha. 1 <span class="caret"></span>
                                </span>
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="#">
                                        <span>Opción 1</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <span>Opción 2</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="#" data-toggle="modal" data-target="#modalLogin">
                                <span class="menu_con_icono">
                                    Menú Despl. Dcha. 2 <i class="glyphicon glyphicon-log-in"></i>
                                </span>
                            </a>
                        </li>
                    </ul>-->
                </div>
            </div>
        </nav>
        <!-## FIN DE MENÚ DE NAVEGACIÓN ##->

<?php
    
}

?>