<?php

session_start();
session_destroy();

?>

<!DOCTYPE html>
<html lang="es">   
    <head>
        <meta http-equiv="Content-type" content="text/html; charset=utf-8" /> 
    </head>
</html>

<script type="text/javascript">
    alert("Hasta pronto. Gracias por su visita.");
    location.replace("../index.php");
</script>