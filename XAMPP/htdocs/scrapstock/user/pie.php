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
    <nav class="navbar navbar-inverse" id="pie">
        <div class="container">
            <div class="row">
                <div class=" col-md-6">                    
                    <p class="navbar-text text-center" style="float: none;"><small>Stock Control by Makito - 2017</small></p>
                </div>
                <div class=" col-md-6">                    
                    <p class="navbar-text text-center" style="float: none;">
                        <small>App desarrollada por Makito - Departamento TI</small>
                    </p>
                </div>
            </div>
        </div>
    </nav>
    
    <script src="../js/jquery.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>

    <script>$('.mytooltip').tooltip();</script>

    <script>
        $(function () {
            $('[data-toggle="popover"]').popover()
        });
    </script>
        
    </body>

</html>
   
    <?php
    
}

?>