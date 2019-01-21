<?php
require_once "Config/Autoload.php";
Config\Autoload::runSitio();
header('Content-Type: application/json');
$producto = new Clases\Productos();
$filterP = array("");
$ultimos_productos = $producto->listWithOps('', 'RAND()', '12');
echo (json_encode($ultimos_productos,JSON_PRETTY_PRINT));
?>