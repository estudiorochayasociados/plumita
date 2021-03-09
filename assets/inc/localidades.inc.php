<?php
require_once dirname(__DIR__,2)."/Config/Autoload.php";
Config\Autoload::run();
$funcion = new Clases\PublicFunction();
$funcion->localidades();
?>