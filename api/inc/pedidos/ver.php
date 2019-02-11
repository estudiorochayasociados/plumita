<?php
header('Content-Type: application/json');
//Clases
$pedidos = new Clases\Pedidos();
$funciones = new Clases\PublicFunction();
$usuarios = new Clases\Usuarios();

$usuario = isset($_GET["cod"]) ? $_GET["cod"] : '';

$filterPedidosAgrupados = array("usuario = '".$usuario."' GROUP BY cod");
$filterPedidosSinAgrupar = array("usuario = '".$usuario."'");


$pedidosArrayAgrupados = $pedidos->list($filterPedidosAgrupados);
$pedidosArraySinAgrupar = $pedidos->list($filterPedidosSinAgrupar);

asort($pedidosArraySinAgrupar);

$pedidos_array=array();

foreach ($pedidosArrayAgrupados as $key => $value) {
    $usuarios->set("cod", $value["usuario"]);
    $usuarioData = $usuarios->view();
    $precioTotal = 0;
    $fecha = explode(" ", $value["fecha"]);
    $fecha1 = explode("-", $fecha[0]);
    $fecha1 = $fecha1[2] . '-' . $fecha1[1] . '-' . $fecha1[0] . '-';
    $fecha = $fecha1 . $fecha[1];
    $detalle_=array();

    switch ($value['estado']) {
        case 0:
            //Estado: Carrito no cerrado
            $estado="Carrito no cerrado";
            break;
        case 1:
            //Estado: Pago pendiente
            $estado="Pago pendiente";
            break;
        case 2:
            //Estado: Pago aprobado
            $estado="Pago aprobado";
            break;
        case 3:
            //Estado: Pago enviado
            $estado="Pago enviado";
            break;
        case 4:
            //Estado: Pago rechazado
            $estado="Pago rechazado";
            break;
    }

    foreach ($pedidosArraySinAgrupar as $key2 => $value2) {
        if ($value2['cod'] == $value['cod']) {
            $precioTotal = $precioTotal + ($value2["precio"] * $value2["cantidad"]);
            $detalle_1=array(
              "producto"=>$value2['producto'],
              "cantidad"=>$value2['cantidad'],
              "precio"=>$value2['precio'],
              "precio_final"=>$value2["precio"] * $value2["cantidad"]
            );
            array_push($detalle_,$detalle_1);
        }
    }
    $usuario_=array(
        "usuario"=>$usuarioData['cod'],
        "nombre"=>$usuarioData['nombre'],
        "direccion"=>$usuarioData['direccion'],
        "localidad"=>$usuarioData['localidad'],
        "provincia"=>$usuarioData['provincia'],
        "telefono"=>$usuarioData['telefono'],
        "email"=>$usuarioData['email']
    );
    $pedido_=array(
        "cod"=>$value['cod'],
        "fecha"=>$fecha,
        "estado"=>$estado,
        "usuario"=>$usuario_,
        "detalle"=>$detalle_,
        "total"=>$precioTotal,
        "forma"=>$value['tipo'],
        "observaciones"=>$value['detalle']
    );
    array_push($pedidos_array,$pedido_);
}
echo json_encode($pedidos_array, JSON_PRETTY_PRINT);
?>

