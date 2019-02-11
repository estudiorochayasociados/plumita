<?php
$pedidos = new Clases\Pedidos();
$usuarios = new Clases\Usuarios();

if (isset($_GET["user"])) {
    $user = json_decode($_GET["user"], true);
    $usuario = $user["user"];
    $carrito = $user["carrito"];
    $cod_pedido = substr(md5(uniqid(rand())), 0, 10);
    if (is_array($carrito)) {
        $pedidos->set("cod", $cod_pedido);
        foreach ($carrito as $datos) {
            $pedidos->set("cod", $cod_pedido);
            $pedidos->set("producto", $datos["titulo"]);
            $pedidos->set("cantidad", $datos["cantidad"]);
            $pedidos->set("precio", $datos["precio"]);
            $pedidos->set("estado", 0);
            $pedidos->set("tipo", "APP TELÉFONO");
            $pedidos->set("usuario", $usuario["cod"]);
            $pedidos->set("detalle", "Tomado desde la app");
            $pedidos->set("fecha", date("Y-m-d"));
            if($pedidos->add()) {
                $val = array("status" => "true");
            } else {
                $val = array("status" => "false");
            }
        }
        echo json_encode($val);
    }
} 
?>

