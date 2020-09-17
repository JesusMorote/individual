<?php 
$destinatario = "alara@makito.es"; 
$asunto = "Prueba Mailing"; 
$cuerpo = ' 
<html lang="es">
<head>
	<meta charset="UTF-8">
</head>
<body>
	
Estimado Cliente,<br><br>

Revisando nuestra base de datos, hemos observado que durante el presente año 2015 no ha realizado ninguna compra a Makito.<br><br>

El motivo de este correo, es saber si usted sigue trabajando en nuestro sector para enviarle nuestros catálogos y ofertas o en caso contrario cesar estas acciones que pudieran no ser de utilidad para usted.<br><br>

Por ello, rogamos haga click en la opción que corresponda de las siguientes:<br><br>


-	SIGO OPERANDO CON ESTA EMPRESA EN LA ACTIVIDAD DE VENTA DE ARTÍCULOS PROMOCIONALES.<br>

-	SIGO OPERANDO EN LA ACTIVIDAD DE VENTA DE ARTÍCULOS PROMOCIONALES PERO CON OTRA EMPRESA.<br>

-	ABANDONÉ LA ACTIVIDAD DE VENTA DE ARTÍCULOS PROMOCIONALES.<br><br>

En caso de no recibir ninguna respuesta entenderemos que las cuentas de correo a las que hemos enviado esta encuesta no están operativas por cese de actividad y procederemos a dar de baja su ficha de cliente.<br><br>

Si hubiera cambiado de domicilio o de teléfonos de contacto, rogamos nos lo haga saber remitiendo e-mail a altas@makito.es<br><br>

Le agradecemos su colaboración y aprovechamos la ocasión para ponernos a su entera disposición.<br><br>

Muy Atentamente,<br><br>

</body>
</html> 
'; 

//para el envío en formato HTML 
$headers = "MIME-Version: 1.0\r\n"; 
$headers .= "Content-type: text/html; charset=iso-8859-1\r\n"; 

//dirección del remitente 
$headers .= "From: Marketing <marketing@makito.es>\r\n"; 

//dirección de respuesta, si queremos que sea distinta que la del remitente 
$headers .= "Reply-To: Marketing <marketing@makito.es>\r\n"; 

//ruta del mensaje desde origen a destino 
$headers .= "Return-path: marketing@makito.es\r\n"; 

@mail($destinatario,$asunto,$cuerpo);
?>