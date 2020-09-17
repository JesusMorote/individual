<?php

//Descomentar la siguiente línea cuando la aplicación esté en producción y comentar para desarrollo
//error_reporting(0);

//Inicio la Sesión, para poder usar y comprobar el valor de las variables de sesión necesarias
session_start();

if(!isset($_SESSION['user'])) { //Se intenta acceder a la aplicación sin un login correcto
    //Muestro un mensaje de advertencia y redirijo al usuario a la página de acceso    
    ?>
    <script>
        alert ("Para Acceder a la Aplicación introduzca sus Credenciales de Acceso.");
        location.replace("../index.php");
    </script>
    <?php
} else { //El acceso a la aplicación se produce tras un login correcto
    //Compruebo si se ha pasado por GET el id de la página a mostrar en el cuerpo de la aplicación
    if(isset($_GET['id'])) {
        $id = $_GET['id'];
    }
    
    //Importo el fichero de conexión mediante AdoDB con la BD ($bd)
    require_once('../inc/conexion.php');
    
    //Muestro la cabecera, que mantendré fija    
    include('cabecera.php');
	?>
    <!--Muestro el cuerpo de la página, cuyo contenido será el que irá cambiando-->
	<div class="container-fluid">
		<?php
		if(isset($id)) { //El parámetro id, recibido por GET me permitirá cambiar el contenido del cuerpo de la página
			include("$id.php");
		} else { //Si no se ha pasado por GET ningún valor para el parámetro id, se muestra la home
			include("home.php");
		}
		?>
	</div>
	<?php
    //Muestro el pie, que mantendré fijo también 
    include('pie.php');
    }

?>