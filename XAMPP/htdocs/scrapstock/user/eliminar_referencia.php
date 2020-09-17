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
    
    if(isset($_GET['ref'])) {
        $ref = $_GET['ref'];
        
        $consulta_eliminar = "DELETE FROM referencias_makito_vs_competidores
                                WHERE referencias_makito_vs_competidores_ref_makito LIKE '$ref';";
        $bd->Execute($consulta_eliminar);
        
        //Elimino tambien la Imagen del Artículo Previa (si existiera)
        $nombre_imagen_makito = $ref.'p.jpg';
        if(file_exists('img/articulo/'.$nombre_imagen_makito)) {
            unlink("img/articulo/".$nombre_imagen_makito);
        }
        
        //Informo al Usuario y lo redirijo al listado de referencias
        $_SESSION['filtro'] = "todos";
        ?>
        <script>
            alert ("La referencia ha sido eliminada con éxito.");
            location.replace("inicio.php?id=listar_referencias");
        </script>
        <?php
        
    } else {
        ?>
        <script>
            location.replace("inicio.php?id=home");
        </script>
        <?php
    }  
    
}

?>