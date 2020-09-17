<?php

//Descomentar la siguiente línea cuando la aplicación esté en producción y comentar para desarrollo
//error_reporting(0);

//Esta variable la empleo para cargar aleatoriamente 4 fondos distintos en la página
$img=rand(1, 4);

?>

<!DOCTYPE html>
<html lang="es">
   
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Aplicación Web para control de Stocks">
        <link rel="icon" href="img/stock.ico">
        <title>MAKITO - CONTROL DE STOCK DE COMPETIDORES -</title>
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/custom_login.css" rel="stylesheet">
        
        <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
        
        <!--<script src="js/ie-emulation-modes-warning.js"></script>-->
        
        <style type="text/css">
            .login-screen {
                background-image: url(img/<?php echo $img; ?>_stock.jpg);
                background-position: center;
                background-repeat: no-repeat;
                background-attachment: fixed;
                background-size: cover;
                -moz-background-size: cover;
                -webkit-background-size: cover;
                
                /*position: fixed;*/
                /*Sustituir estas dos lineas por la anterior para volver al estado inicial que no permite scroll en móviles*/
                position: absolute;
                min-height: 640px;
                
                top: 0;
                bottom: 0;
                right: 0;
                left: 0;
            }
        </style>
    </head>

    <body class="main">
       
        <div class="login-screen">
           
            <div class="login-center">
               
                <div class="container-fluid min-height">
                   
                    <div class="row">
                       
                        <div class="container noimprimir">
                           
                            <div class="row" id="principal">
                               
                                <div class="col-md-8 col-xs-12 text-left">
                                    <img src="img/logomktoblanco.png">
                                    <br>
                                    <h1 class="nombre2">- STOCK CONTROL -
                                        <h2 class="nombre2" id="subtitulo">
                                            Consulta y Control del Stock de Competidores
                                        </h2>
                                    </h1>
                                </div>                                

                                <div class="col-md-4 col-xs-12">
                                    <div class="login" id="card">
                                        <div class="front signin_form"> 
                                            <p>
                                                Iniciar Sesión
                                            </p>
                                            <form class="login-form" action="inc/login.inc.php" method="post">
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <input type="text" name="user" id="user" class="form-control" placeholder="Usuario">
                                                        <span class="input-group-addon">
                                                            <i class="glyphicon glyphicon-user"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <input type="password" name="pass" name="pass" class="form-control" placeholder="Contraseña">
                                                        <span class="input-group-addon">
                                                            <i class="glyphicon glyphicon-lock"></i>
                                                        </span>
                                                    </div>
                                                </div>                        
                                                <div class="form-group sign-btn">
                                                    <input type="submit" class="btn" value="Entrar">
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                            
                        </div>
                        
                    </div>
                    
                    <div class="row" id="pie_login">
                        <h6 class="text-center" id="texto_pie">
                            MAKITO promotional products
                            <br>
                            &copy; 2017 Makito. Todos los derechos reservados.
                        </h6>
                    </div>
                    
                </div>
                
            </div>
            
        </div>

        <script src="js/jquery.js"></script>
        <script src="js/bootstrap.min.js"></script>

    </body>

</html>