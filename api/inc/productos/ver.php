<?php
$productos = new Clases\Productos();
//--------------------------------------------------
$filtro_prueba=isset($_POST["filter2"]) ? $_POST["filter2"] : '';
//--------------------------------------------------
//$filter = isset($_POST["filter"]) ? $_POST["filter"] : '';
$order = isset($_GET["order"]) ? $_GET["order"] : '';
$limit = isset($_GET["limit"]) ? $_GET["limit"] : '';
//--------------------------------------------------
if ($filtro_prueba!=''){
$filter= array($filtro_prueba);
}else{
    $filter='';
}
//--------------------------------------------------
$data = $productos->listWithOps($filter, $order, $limit);
echo json_encode($data, JSON_PRETTY_PRINT);
?>