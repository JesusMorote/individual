<?php
require_once '../phpexcel/PHPExcel.php';
require_once('../adodb/adodb.inc.php');

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
            ->setCellValue('A1', 'Ref')
            ->setCellValue('B1', 'Tecnica')
            ->setCellValue('C1', 'Col Max')
            ->setCellValue('D1', 'Col Inc');

$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);


// Consulta

$y=1;            
$consulta="SELECT DISTINCT art_tecnica_refarticulo FROM art_tecnica ORDER BY art_tecnica_refarticulo;";
$resultado=$bd->Execute($consulta);


while(!($resultado->EOF)){
$art=$resultado->fields['art_tecnica_refarticulo'];			
	$y++;
	$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue("A".$y, $art);

	$consulta2="SELECT DISTINCT art_tecnica_refarticulo, art_tecnica_reftecnica, art_tecnica_colmax, art_tecnica_colinc FROM art_tecnica WHERE art_articulo_refarticulo = '$art';";
	$resultado2=$bd->Execute($consulta2);
	$a='B';
	while(!($resultado2->EOF)){
		$tec=$resultado2->fields['art_tecnica_reftecnica'];
		$colmax=$resultado2->fields['art_tecnica_colmax'];
		$coling=$resultado2->fields['art_tecnica_colinc'];

		$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue($a.$y, $tec)
				->setCellValue($a.$y, $colmax)
				->setCellValue($a.$y, $colinc);
	$a++;
		$resultado2->MoveNext();
	}
	$resultado->MoveNext();
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