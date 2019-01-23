<?php
$productos = new Clases\Productos();
$filter = isset($_POST["filter"]) ? $_POST["filter"] : '';
$order = isset($_GET["order"]) ? $_GET["order"] : '';
$limit = isset($_GET["limit"]) ? $_GET["limit"] : '';
$data = $productos->listWithOps($filter, $order, $limit);
echo json_encode($data, JSON_PRETTY_PRINT);
?>