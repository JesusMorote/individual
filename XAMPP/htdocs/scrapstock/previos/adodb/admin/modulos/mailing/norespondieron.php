<?php
include('cabecera.php');
?>

<div class="container">

<br>

<ul class="nav nav-tabs">
  <li role="presentation"><a href="resumen.php">Resumen</a></li>
  <li role="presentation"><a href="respondieron.php">Respondieron</a></li>
  <li role="presentation" class="active"><a href="norespondieron.php">No Respondieron</a></li>
</ul>
<br><br>
	
	<?php 
	$consulta="SELECT COUNT(*) FROM cliente WHERE responde LIKE '0' ;";
	$resultado=$bd->Execute($consulta);
	$valor=$resultado->fields[0];

	?>

<button type="button" class="btn btn-primary btn-xs">No Respondieron <span class="badge"> <?php echo $valor; ?> </span></button> <button type="button" class="btn btn-success btn-xs" onclick="window.open('norespondieronexcel.php')"><span class="glyphicon glyphicon-file" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="importar a un archivo.xls"></span> Exportar a Excel</button>
<br><br>
	<table class="table table-condensed table-striped table-hover">
	
		<th class="info">Codigo</th>
		<th class="info">Cliente</th>
		<th class="info">Fecha Pregunta</th>
	
	<?php

		$resultados=20;

		$paginacion = new Zebra_Pagination();
		$paginacion->records($valor);
		$paginacion->records_per_page($resultados);
		$pagina=($paginacion->get_page()-1)*$resultados;

		$consulta="SELECT * FROM cliente WHERE responde=0 ORDER BY cod_cliente ASC LIMIT $pagina,$resultados;";
		$resultado=$bd->Execute($consulta);

		while(!($resultado->EOF)){

			$string=$resultado->fields['nombre_cliente'];

			echo "<tr><td>".$resultado->fields['cod_cliente']."</td>";
			echo "<td>".substr($string, 0, 200)."</td>";
			echo "<td>".$resultado->fields['fecha_envio']."</td>";			
			$resultado->MoveNext();
			echo "</tr>";

		}

	?>
	</table>
	<div>

	<?php $paginacion->render(); ?>

	</div>


<?php

include('pie.php');

?>