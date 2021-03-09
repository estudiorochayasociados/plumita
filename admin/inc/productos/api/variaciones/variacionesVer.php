<?php
require_once (dirname(__DIR__,5)."/Config/Autoload.php");
Config\Autoload::run();

$combinacion = new Clases\Combinaciones();
$cod = isset($_GET["cod"]) ? $_GET["cod"] : '';
$combinacion->set("codProducto", $cod);
$combinacionData = $combinacion->listByProductCod();
echo json_encode($combinacionData);