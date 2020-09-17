<?php
include('cabecera.php');
?>

<div class="container">

<ul class="nav nav-tabs">
  <li role="presentation"><a href="resumen.php">Resumen</a></li>
  <li role="presentation" class="active"><a href="respondieron.php">Respondieron</a></li>
  <li role="presentation"><a href="norespondieron.php">No Respondieron</a></li>
</ul>

<br>

	<?php 
	$consulta="SELECT COUNT(*) FROM cliente WHERE responde LIKE '1';";
	$resultado=$bd->Execute($consulta);
	$valor=$resultado->fields[0];
	?>

<button type="button" class="btn btn-primary btn-xs">Respondieron <span class="badge"> <?php echo $valor; ?> </span></button> <button type="button" class="btn btn-success btn-xs" onclick="window.open('respondieronexcel.php')"><span class="glyphicon glyphicon-file" aria-hidden="true"></span> Exportar a Excel</button>
<br><br>
	<table class="table table-condensed table-striped table-hover">
	
		<th class="info">Codigo</th>
		<th class="info">Cliente</th>
		<th class="info">Fecha Pregunta</th>
		<th class="info">Fecha Respuesta</th>
		<th class="info">Respuesta</th>
		<th class="info">Respuesta2</th>
	
	<?php

		$resultados=20;

		$paginacion = new Zebra_Pagination();
		$paginacion->records($valor);
		$paginacion->records_per_page($resultados);
		$pagina=($paginacion->get_page()-1)*$resultados;

		$consulta="SELECT * FROM cliente WHERE responde=1 ORDER BY cod_cliente ASC LIMIT $pagina,$resultados;";
		$resultado=$bd->Execute($consulta);
		


		while(!($resultado->EOF)){

			$string=$resultado->fields['nombre_cliente'];

			echo "<tr><td>".$resultado->fields['cod_cliente']."</td>";
			echo "<td>".substr($string, 0, 25)."</td>";
			echo "<td>".$resultado->fields['fecha_envio']."</td>";
			echo "<td>".$resultado->fields['fecha_respuesta']."</td>";
			echo "<td>".$resultado->fields['respuesta']."</td>";
			echo "<td>".$resultado->fields['respuesta2']."</td>";
			
			$resultado->MoveNext();
			echo "</tr>";

		}

	?>

	</table>
	<?php $paginacion->render(); ?>
</div>

<?php

include('pie.php');

?>