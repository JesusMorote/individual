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
    ?>    
    <div class="container" id="cuerpo_home">
        <div class="row">
            <div class="col-md-offset-2 col-md-8">
                <div class="enmarcado separado">
                    <h3 class="indentado"><i class="glyphicon glyphicon-barcode"></i> MAKITO vs Competidores</h3>
                    <hr class="hr_gris">
                    <ul>
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
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-offset-2 col-md-8">
                <div class="enmarcado separado">
                    <h3 class="indentado"><i class="glyphicon glyphicon-equalizer"></i> Consultar Stock</h3>
                    <hr class="hr_gris">
                    <ul>
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
                </div>
            </div>                 
        </div>
        
        <div class="row">
            <div class="col-md-offset-2 col-md-8">
                <div class="enmarcado separado">
                    <h3 class="indentado"><i class="glyphicon glyphicon-eye-open"></i> Competidores</h3>
                    <hr class="hr_gris">
                    <ul>
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
                </div>
            </div>                
        </div>

        <!--<div class="row">
            <div class="col-md-offset-2 col-md-4">
                <div class="enmarcado separado">
                    <h3 class="indentado"><i class="glyphicon glyphicon-asterisk"></i> Menú 3</h3>
                    <hr class="hr_gris">
                    <ul>
                        <li>
                            <a href="#">
                                <i class="glyphicon glyphicon-minus"></i> Opción 3.1
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <i class="glyphicon glyphicon-minus"></i> Opción 3.2
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <i class="glyphicon glyphicon-minus"></i> Opción 3.3
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="col-md-4">
                <div class="enmarcado separado">
                    <h3 class="indentado"><i class="glyphicon glyphicon-asterisk"></i> Menú 4</h3>
                    <hr class="hr_gris">
                    <ul>
                        <li>
                            <a href="#">
                                <i class="glyphicon glyphicon-minus"></i> Opción 4.1
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <i class="glyphicon glyphicon-minus"></i> Opción 4.2
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <i class="glyphicon glyphicon-minus"></i> Opción 4.3
                            </a>
                        </li><li>
                            <a href="#">
                                <i class="glyphicon glyphicon-minus"></i> Opción 4.4
                            </a>
                        </li>
                    </ul>
                </div>
            </div>      
        </div>

        <div class="row">
            <div class="col-md-offset-2 col-md-4">
                <div class="enmarcado separado">
                    <h3 class="indentado"><i class="glyphicon glyphicon-asterisk"></i> Menú 5</h3>
                    <hr class="hr_gris">
                    <ul>
                        <li>
                            <a href="#">
                                <i class="glyphicon glyphicon-minus"></i> Opción 5.1
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <i class="glyphicon glyphicon-minus"></i> Opción 5.2
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <i class="glyphicon glyphicon-minus"></i> Opción 5.3
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <i class="glyphicon glyphicon-minus"></i> Opción 5.4
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>-->
    </div>
    
    <?php
    
}

?>