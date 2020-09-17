<?php
include('cabecera.php');
?>

<div class="container">

<br><br><br><br>

<ul class="nav nav-tabs">
  <li role="presentation" class="active"><a href="resumen.php">Resumen</a></li>
  <li role="presentation"><a href="respondieron.php">Respondieron</a></li>
  <li role="presentation"><a href="norespondieron.php">No Respondieron</a></li>
</ul>

<br><br>
</div>

<?php
	$consulta="SELECT COUNT(*) FROM cliente WHERE responde LIKE '0' ;";
	$resultado=$bd->Execute($consulta);
	$valor=$resultado->fields[0];

include('pie.php');

?>