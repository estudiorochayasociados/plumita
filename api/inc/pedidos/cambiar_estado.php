<?php
$estado = isset($_GET["estado"]) ? $_GET["estado"] : '';
$cod = isset($_GET["cod"]) ? $_GET["cod"] : '';
if ($estado != '' && $cod != '') {
    $pedidos->set("estado", $estado);
    $pedidos->set("cod", $cod);
    $pedidos->cambiar_estado();
    $array = array("status" => true);
}else{
    $array = array("status" => false);
}
echo json_encode($array, JSON_PRETTY_PRINT);
?>