<?php
$banner = new Clases\Banner();
$filter    = isset($_POST["filter"]) ? $_POST["filter"] : '';
$order    = isset($_GET["order"]) ? $_GET["order"] : '';
$limit    = isset($_GET["limit"]) ? $_GET["limit"] : '';
array_push($filter,$decoded);
var_dump($filter) ;
$data      = $banner->listWithOps($filter,$order,$limit) ;
echo json_encode($data,JSON_PRETTY_PRINT);
?>