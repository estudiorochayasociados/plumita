<?php
require_once "../Config/Autoload.php";
Config\Autoload::runAdmin();
header('Content-Type: application/json');
$op = isset($_GET["op"]) ? $_GET["op"] : 'inicio';
$accion = isset($_GET["accion"]) ? $_GET["accion"] : 'ver';
include "inc/" . $op . "/" . $accion . ".php";
?>