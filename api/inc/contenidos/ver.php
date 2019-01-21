<?php
$contenidos = new Clases\Contenidos();
$filter    = isset($_POST["filter"]) ? $_POST["filter"] : '';
$data      = $contenidos->list($filter) ;
echo json_encode($data,JSON_PRETTY_PRINT);
?>