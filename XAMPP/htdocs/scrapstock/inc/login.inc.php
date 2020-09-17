<?php

//Descomentar la siguiente línea cuando la aplicación esté en producción y comentar para desarrollo
//error_reporting(0);

if((!isset($_POST['user'])) or (!isset($_POST['pass']))) { //Si no llego al login desde la página de inicio (index.php)
    //Muestro un mensaje de advertencia y redirijo al usuario a la página de acceso    
    ?>
    <script>
        alert ("Debe introducir sus Credenciales de Acceso.");
        location.replace("../index.php");
    </script>
    <?php
} else { 
    //Inicio la Sesión, para poder usar y comprobar el valor de las variables de sesión necesarias
    session_start();

    //Importo el fichero de conexión mediante AdoDB con la BD ($bd)
    require_once 'conexion.php';

    //Recojo los parámetros recibidos por POST
    $user=$_POST['user'];
    $pass=$_POST['pass'];
    
    //Compruebo si existe un usuario con las credenciales de acceso que he recibido por POST
    $consulta_usuario = "SELECT COUNT(*)
                            FROM stock_control_usuarios
                            WHERE stock_control_usuarios_usuario LIKE '$user'
                                AND stock_control_usuarios_password LIKE '$pass';";
    $consulta_existe_usuario = $bd->Execute($consulta_usuario);
    $existe_usuario = $consulta_existe_usuario->fields[0];
        
    if($existe_usuario == 1) { //Existe un usuario con las credenciales introducidas en el formulario de acceso       
        $consulta_id = "SELECT stock_control_usuarios_id
                            FROM stock_control_usuarios
                            WHERE stock_control_usuarios_usuario LIKE '$user'
                                AND stock_control_usuarios_password LIKE '$pass';";
        $resultado_id_usuario = $bd->Execute($consulta_id);
        $id_usuario = $resultado_id_usuario->fields[0];
        
        //Registro el accceso del usuario a la aplicación
        $consulta_inserta_acceso="INSERT INTO stock_control_usuarios_acceso
                           (stock_control_usuarios_acceso_usuario,
                            stock_control_usuarios_acceso_fecha,
                            stock_control_usuarios_acceso_hora)
                        VALUES
                           ($id_usuario,
                            curdate(),
                            curtime());";
        $bd->Execute($consulta_inserta_acceso);
        
        //Establezco el valor de la variable de sesión $_SESSION['user']
        $_SESSION['user'] = $user;
        
        //Redirijo al usuario a la home de la aplicación
        ?>
        <script type=text/javascript>
            location.replace("../user/inicio.php?id=home");
        </script>
        <?php
    } else { //NO existe un usuario con las credenciales introducidas en el formulario de acceso 
        //Muestro un mensaje de advertencia y redirijo al usuario a la página de acceso    
        ?>
        <script>
            alert ("Las credenciales de Acceso no son Correctas.\nPor favor, inténtelo de nuevo.");
            location.replace("../index.php");
        </script>
        <?php
    }
}

?>
