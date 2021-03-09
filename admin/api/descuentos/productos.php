<?php
require_once dirname( __DIR__,3)."/Config/Autoload.php";
Config\Autoload::run();

$productos = new Clases\Productos();

$string = $_POST['string'];

$productosArray = $productos->list(["cod_producto LIKE '$string%' OR titulo LIKE '$string%'"], "", "");

echo json_encode(["status" => true, "productos" => $productosArray]);