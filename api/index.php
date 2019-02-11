<?php
require_once "../Config/Autoload.php";
Config\Autoload::runAdmin();
$op = isset($_GET["op"]) ? $_GET["op"] : 'inicio';
$accion = isset($_GET["accion"]) ? $_GET["accion"] : 'ver';
include "inc/" . $op . "/" . $accion . ".php";
?>