<?php
require_once 'phpexcel/PHPExcel.php';

//Conexión Base de datos
mysql_connect("localhost","root","catal2006");
mysql_select_db("catal");

//Codificación del País
date_default_timezone_set('Europe/London');

$objPHPExcel = new PHPExcel();
$NombreArchivo = "Respondieron";

// Set document properties
$objPHPExcel->getProperties()->setCreator("Makito")
							 ->setLastModifiedBy("Makito")
							 ->setTitle("Reporte XLS")
							 ->setSubject("Office 2007 xls Document")
							 ->setDescription("")
							 ->setKeywords("")
							 ->setCategory("");

//Propiedades del tipo de letra y tamaño
$objPHPExcel->getDefaultStyle()->getFont()->setName('Calibri');
$objPHPExcel->getDefaultStyle()->getFont()->setSize(8);



// Propiedades de la cabecera
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Codigo')
            ->setCellValue('B1', 'Cliente')
            ->setCellValue('C1', 'Fecha Consulta')
            ->setCellValue('D1', 'Fecha Respuesta')
            ->setCellValue('E1', 'Respuesta1')
            ->setCellValue('F1', 'Respuesta2');

$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);

// Consulta

$y=1;            
$sql="SELECT * FROM cliente WHERE responde LIKE '1';";
$rec=mysql_query($sql);

while($row=mysql_fetch_array($rec)){
			
			$y++;
			$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue("A".$y, $row['cod_cliente'])
						->setCellValue("B".$y, $row['nombre_cliente'])
						->setCellValue("C".$y, $row['fecha_envio'])
						->setCellValue("D".$y, $row['fecha_respuesta'])
						->setCellValue("E".$y, $row['respuesta'])
						->setCellValue("F".$y, $row['respuesta2']);
		}



// Datos de salida para excel
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$NombreArchivo.'".xls');
header('Cache-Control: max-age=0');
// Para IExplorer 9
header('Cache-Control: max-age=1');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');

exit;

?>