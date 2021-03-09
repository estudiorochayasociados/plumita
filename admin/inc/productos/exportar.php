<?php
$public = new Clases\PublicFunction();
$productos = new Clases\Productos();

include "../vendor/phpoffice/phpexcel/Classes/PHPExcel.php";
require "../vendor/phpoffice/phpexcel/Classes/PHPExcel/IOFactory.php";

$filename = "LISTA DE PRECIOS " . date("d-m-Y") . ".xls";

$productosTotal = $productos->list("","","");

$objPHPExcel = new PHPExcel();
$objPHPExcel->setActiveSheetIndex(0);

$objPHPExcel->getActiveSheet()->SetCellValue("A1", "COD");
$objPHPExcel->getActiveSheet()->SetCellValue("B1", "TITULO");
$objPHPExcel->getActiveSheet()->SetCellValue("C1", "DESARROLLO");
$objPHPExcel->getActiveSheet()->SetCellValue("D1", "PRECIO");
$objPHPExcel->getActiveSheet()->SetCellValue("E1", "PRECIO DESCUENTO");
$objPHPExcel->getActiveSheet()->SetCellValue("F1", "PRECIO MAYORISTA");
$objPHPExcel->getActiveSheet()->SetCellValue("G1", "STOCK");
$objPHPExcel->getActiveSheet()->SetCellValue("H1", "PESO");
$objPHPExcel->getActiveSheet()->SetCellValue("I1", "CATEOGRIA");
$objPHPExcel->getActiveSheet()->SetCellValue("J1", "SUBCATEGORIA");
$objPHPExcel->getActiveSheet()->SetCellValue("K1", "VARIABLE1");
$objPHPExcel->getActiveSheet()->SetCellValue("L1", "VARIABLE3");
$objPHPExcel->getActiveSheet()->SetCellValue("M1", "VARIABLE2");
$objPHPExcel->getActiveSheet()->SetCellValue("N1", "VARIABLE4");

$rowCount = 2;
foreach ($productosTotal as $producto) {
    $objPHPExcel->getActiveSheet()->SetCellValue("A" . $rowCount, $producto["data"]["cod_producto"]);
    $objPHPExcel->getActiveSheet()->SetCellValue("B" . $rowCount, $producto["data"]["titulo"]);
    $objPHPExcel->getActiveSheet()->SetCellValue("C" . $rowCount, $producto["data"]["desarrollo"]);
    $objPHPExcel->getActiveSheet()->SetCellValue("D" . $rowCount, $producto["data"]["precio"]);
    $objPHPExcel->getActiveSheet()->SetCellValue("E" . $rowCount, $producto["data"]["precio_descuento"]);
    $objPHPExcel->getActiveSheet()->SetCellValue("F" . $rowCount, $producto["data"]["precio_mayorista"]);
    $objPHPExcel->getActiveSheet()->SetCellValue("G" . $rowCount, $producto["data"]["stock"]);
    $objPHPExcel->getActiveSheet()->SetCellValue("H" . $rowCount, $producto["data"]["peso"]);
    $objPHPExcel->getActiveSheet()->SetCellValue("I" . $rowCount, $producto["category"]["data"]["titulo"]);
    $objPHPExcel->getActiveSheet()->SetCellValue("J" . $rowCount, $producto["subcategory"]["data"]["titulo"]);
    $objPHPExcel->getActiveSheet()->SetCellValue("K" . $rowCount, $producto["data"]["variable1"]);
    $objPHPExcel->getActiveSheet()->SetCellValue("L" . $rowCount, $producto["data"]["variable2"]);
    $objPHPExcel->getActiveSheet()->SetCellValue("M" . $rowCount, $producto["data"]["variable3"]);
    $objPHPExcel->getActiveSheet()->SetCellValue("N" . $rowCount, $producto["data"]["variable4"]);
    $rowCount++;
}
$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
$objWriter->save($filename);
$public->headerMove($filename);