<?php
require_once (dirname(__DIR__,5)."/Config/Autoload.php");
Config\Autoload::run();

$config = new Clases\Config();
$atributo = new Clases\Atributos();
$subatributo = new Clases\Subatributos();

$cod = isset($_GET["cod"]) ? $_GET["cod"] : '';

$atributo->set("productoCod",$cod);
echo json_encode($atributo->list());