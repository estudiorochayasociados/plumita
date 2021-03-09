<?php
require_once dirname(__DIR__, 2) . "/Config/Autoload.php";
Config\Autoload::run();
$producto = new Clases\Productos();
echo json_encode($producto->list("","","500"));