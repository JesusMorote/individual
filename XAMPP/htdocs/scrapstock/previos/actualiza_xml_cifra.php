<?php

//Conexión para poder usar la bd con ADODB ($bd)
require_once('conexion.php');

//encabezado para que se muestren correctamente los acentos, caracteres especiales,...
header('Content-Type: text/html; charset=UTF-8');

//Obtengo la fecha actual
$fecha_actual = date('Y-m-d');
//Obtengo la hora actual
$hora_actual = date('H:i:s');

// Variable con el nombre del fichero a importar
$xml_file = 'xml/product-2017-10-09.xml';

if (file_exists($xml_file)) 
{
   $xml = simplexml_load_file($xml_file);
} 
else 
{ 
exit('Error al intentar abrir el fichero '.$xml_file);
}


//Si todo va bien,borro los registros anteriores de ls BD, recorremos el xml y vamos añadiendo registros a la BD
$consulta_borrado = "DELETE FROM datosxml_cifra";
$bd->Execute($consulta_borrado);

$reinicia_id = "ALTER TABLE datosxml_cifra AUTO_INCREMENT = 1;";
$bd->Execute($reinicia_id);

$contador = 0;

foreach($xml->product as $producto) {
    
    $ref = $producto->product_model;
    $ref_raiz = $producto->product_root_model;
    $nombre = $producto->product_name;
    $descripcion = $producto->product_description;
    $categoria = $producto->product_category;
    $subcategoria = $producto->product_subcategory;
    $imagen = $producto->product_image;
    $fecha_mod = $producto->product_date;
    $entrada1_fecha = $producto->product_fecha1;
    $entrada1_stock = $producto->product_cantidad1;
    $entrada2_fecha = $producto->product_fecha2;
    $entrada2_stock = $producto->product_cantidad2;
    $entrada3_fecha = $producto->product_fecha3;
    $entrada3_stock = $producto->product_cantidad3;
    
    $consulta_insercion = "INSERT INTO datosxml_cifra
                               (datosxml_cifra_ref,
                                datosxml_cifra_ref_raiz,
                                datosxml_cifra_nombre,
                                datosxml_cifra_descripcion,
                                datosxml_cifra_categoria,
                                datosxml_cifra_subcategoria,
                                datosxml_cifra_imagen,
                                datosxml_cifra_fecha_mod,
                                datosxml_cifra_entrada1_fecha,
                                datosxml_cifra_entrada1_stock,
                                datosxml_cifra_entrada2_fecha,
                                datosxml_cifra_entrada2_stock,
                                datosxml_cifra_entrada3_fecha,
                                datosxml_cifra_entrada3_stock,
                                datosxml_cifra_fecha,
                                datosxml_cifra_hora)
                            VALUES
                               ('$ref',
                                '$ref_raiz',
                                '$nombre',
                                '$descripcion',
                                '$categoria',
                                '$subcategoria',
                                '$imagen',
                                '$fecha_mod',
                                '$entrada1_fecha',
                                '$entrada1_stock',
                                '$entrada2_fecha',
                                '$entrada2_stock',
                                '$entrada3_fecha',
                                '$entrada3_stock',
                                '$fecha_actual',
                                '$hora_actual');";
    
    $bd->Execute($consulta_insercion);
    
    $contador++;
}

//Obtengo la hora de finalizaicón del proceso de inserción de datos XML
$hora_actual_fin = date('H:i:s');

echo "Número de Productos incorporados a la BD del Competidor CIFRA: ".$contador."<br>";
echo "La Importación de datos desde el fichero XML comenzó a las: ".$hora_actual." horas<br>";
echo "La Importación de datos desde el fichero XML finalizó a las: ".$hora_actual_fin." horas<br>";

?>