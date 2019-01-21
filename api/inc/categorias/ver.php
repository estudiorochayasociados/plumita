<?php
$categorias = new Clases\Categorias();
$filter    = isset($_POST["filter"]) ? $_POST["filter"] : '';
$order    = isset($_GET["order"]) ? $_GET["order"] : '';
$limit    = isset($_GET["limit"]) ? $_GET["limit"] : '';
$data      = $categorias->listWithOps($filter,$order,$limit) ;
echo json_encode($data,JSON_PRETTY_PRINT);
?>