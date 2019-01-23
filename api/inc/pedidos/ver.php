<?php
//Clases
$pedidos = new Clases\Pedidos();
$funciones = new Clases\PublicFunction();
$usuarios = new Clases\Usuarios();

$estadoFiltro = isset($_GET["estadoFiltro"]) ? $_GET["estadoFiltro"] : '';
$estado = isset($_GET["estado"]) ? $_GET["estado"] : '';
$cod = isset($_GET["cod"]) ? $_GET["cod"] : '';
$tipo = isset($_GET["tipo"]) ? $_GET["tipo"] : '';
$usuario = isset($_GET["usuario"]) ? $_GET["usuario"] : '';

if ($estado != '' && $cod != '' && $tipo != '' && $usuario != '') {
    $pedidos->set("estado", $estado);
    $pedidos->set("cod", $cod);
    $pedidos->set("tipo", $tipo);
    $pedidos->set("usuario", $usuario);
    $pedidos->cambiar_estado();
    $funciones->headerMove(URL . '/?op=pedidos&accion=ver');
}

$filter = '';
if ($estado != '') {
    $filter = array("estado = $estado");
}
$data = $pedidos->list($filter);

if ($estadoFiltro != '' && $estadoFiltro != 5) {
    $filterPedidosAgrupados = array("estado = '" . $estadoFiltro . "' GROUP BY cod");
    $filterPedidosSinAgrupar = array("estado = '" . $estadoFiltro . "'");
} else {
    $filterPedidosAgrupados = array("cod != '' GROUP BY cod");
    $filterPedidosSinAgrupar = "";
}

$pedidosArrayAgrupados = $pedidos->list($filterPedidosAgrupados);
$pedidosArraySinAgrupar = $pedidos->list($filterPedidosSinAgrupar);
asort($pedidosArraySinAgrupar);

echo json_encode($pedidosArrayAgrupados,JSON_PRETTY_PRINT);

?>

