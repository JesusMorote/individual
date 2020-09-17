<?php

include('adodb/adodb.inc.php');
include('inc/conexion.php');
include('cabecera2.php');

if(!(isset($_GET['cli']))){

	header("Location:http://www.makito.es");

}else{

	$cli=$_GET['cli'];
	$res=$_GET['res'];

	$inserta="UPDATE cliente SET fecha_respuesta=CURDATE(), hora_respuesta=CURTIME(), responde=1, respuesta='$res' WHERE cod_cliente=$cli;";
	$bd->Execute($inserta);

	
	if ($res<=3 and $res>=1){

		if($res==3){
			
			header("Location:gracias.php");

		}else{
			if($res==2){
				?>
				<div class="container">
				<br><br><br><br>
				<div><img src="img/logomkto.png" alt=""></div>
					<br><br><br>
					<form action="gracias.php">
					  <div class="form-group">
					    <label>En virtud de la mejora diaria de nuestro servicios, podria indicarnos el nombre de la empresa con la que realiza actualmente su actividad. Esta información se usará a modo de estadistica interna.</label>
					    <input type="text" class="form-control" id="res2">
					  </div>
					  <button type="submit" class="btn btn-default">Enviar</button>
					</form>
				</div>
				<?php
			}

			if($res==1){
				?>
				<div class="container">
				<br><br><br><br>
					<div><img src="img/logomkto.png" alt=""></div>
					<br><br><br>
					<form action="gracias.php">
					  <div class="form-group">
					    <label>En virtud de la mejora diaria de nuestro servicios, podria indicarnos los motivos por los que no ha realizado ninguna compra en Makito en 2015 con esta empresa</label>
					    <input type="text" class="form-control" id="res2">
					  </div>
					  <button type="submit" class="btn btn-default">Enviar</button>
					</form>
				</div>	
				<?php
			}

		}
	}else{
		header("Location:http://www.makito.es");
	}

}

?>