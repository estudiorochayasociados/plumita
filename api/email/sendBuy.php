<?php
require_once dirname(__DIR__,2)."/Config/Autoload.php";
Config\Autoload::run();
$funciones = new Clases\PublicFunction();
$enviar = new Clases\Email();
$pedido = new Clases\Pedidos();
$config = new Clases\Config();
$emailData = $config->viewEmail();

$codPedido = $funciones->antihack_mysqli(isset($_POST['cod']) ? $_POST['cod'] : '');

if (!empty($codPedido)) {
    $pedido->set("cod", $codPedido);
    $pedidoData = $pedido->view();
    if (!empty($pedidoData['data'])) {
        $detalle = json_decode($pedidoData['data']['detalle'], true);
        $carroTotal = 0;
        $mensaje_carro = '<table border="1" style="text-align:left;width:100%;font-size:13px !important">';
        $mensaje_carro .= "<thead><th>Nombre producto</th><th>Cantidad</th><th>Precio</th><th>Total</th></thead>";
        foreach ($pedidoData['detail'] as $detail) {
            $unserialized = unserialize($detail['variable2']);
            if (!empty($unserialized) && isset($unserialized['cod'])) {
                $descuentoCod = $unserialized["cod"];
                $descuentoMonto = $unserialized["monto"];
                $descuentoPrecio = $unserialized["precio-antiguo"];
            } else {
                $descuentoCod = '';
                $descuentoMonto = '';
                $descuentoPrecio = '';
            }
            $opciones = '';
            if (!empty($detail['variable3'])) {
                $opciones = "<br>" . $detail['variable3'];
            }
            $carroTotal += $detail['cantidad'] * $detail['precio'];
            $mensaje_carro .= "<tr>";
            $mensaje_carro .= "<td>" . $detail['producto'] . " <b>" . $descuentoMonto . "</b>" . $opciones . "</td>";
            $mensaje_carro .= "<td>" . $detail["cantidad"] . "</td>";
            if ($detail['precio'] != 0) {
                $mensaje_carro .= "<td>$" . $detail['precio'] . " <span style='text-decoration: line-through'>" . $descuentoPrecio . "</span></td>";
            } else {
                $mensaje_carro .= "<td></td>";
            }
            $mensaje_carro .= "<td>$" . $detail['cantidad'] * $detail['precio'] . "</td>";
            $mensaje_carro .= "</tr>";

        }
        $mensaje_carro .= '<tr><td></td><td></td><td></td><td>$' . $carroTotal . '</td></tr>';
        $mensaje_carro .= '</table>';

        //MENSAJE = DATOS USUARIO COMPRADOR
        $datos_usuario = "<b>Nombre y apellido:</b> " . $pedidoData['user']['data']["nombre"] . " " . $pedidoData['user']['data']["apellido"] . "<br/>";
        $datos_usuario .= "<b>Email:</b> " . $pedidoData['user']['data']["email"] . "<br/>";
        $datos_usuario .= "<b>Provincia:</b> " . $pedidoData['user']['data']["provincia"] . "<br/>";
        $datos_usuario .= "<b>Localidad:</b> " . $pedidoData['user']['data']["localidad"] . "<br/>";
        $datos_usuario .= "<b>Dirección:</b> " . $pedidoData['user']['data']["direccion"] . "<br/>";
        $datos_usuario .= "<b>Teléfono:</b> " . $pedidoData['user']['data']["telefono"] . "<br/>";

        if ($pedidoData['data']["estado"] == 1 || $pedidoData['data']["estado"] == 2) {
            //USUARIO EMAIL
            $mensajeCompraUsuario = '¡Muchas gracias por tu nueva compra!<br/>';
            $mensajeCompraUsuario .= "En el transcurso de las 24 hs un operador se estará contactando con usted para pactar la entrega y/o pago del pedido. A continuación te dejamos el pedido que nos realizaste.<hr/>";
            $mensajeCompraUsuario .= "<h3>Pedido realizado:</h3>";
            $mensajeCompraUsuario .= $mensaje_carro;

            $mensajeCompraUsuario .= '<br/><hr/>';
            $mensajeCompraUsuario .= '<h3>Información de envio:</h3>';
            $mensajeCompraUsuario .= '<b>Tipo: </b>' . $detalle['envio']['tipo'] . '<br/>>';
            $mensajeCompraUsuario .= '<b>Nombre: </b>' . $detalle['envio']['nombre'] . '<br/>';
            $mensajeCompraUsuario .= '<b>Apellido: </b>' . $detalle['envio']['apellido'] . '<br/>';
            $mensajeCompraUsuario .= '<b>Email: </b>' . $detalle['envio']['email'] . '<br/>';
            $mensajeCompraUsuario .= '<b>Provincia: </b>' . $detalle['envio']['provincia'] . '<br/>';
            $mensajeCompraUsuario .= '<b>Localidad: </b>' . $detalle['envio']['localidad'] . '<br/>';
            $mensajeCompraUsuario .= '<b>Dirección: </b>' . $detalle['envio']['direccion'] . '<br/>';
            $mensajeCompraUsuario .= '<b>Teléfono: </b>' . $detalle['envio']['telefono'] . '<br/>';
            $mensajeCompraUsuario .= '<br/><hr/>';

            $mensajeCompraUsuario .= '<h3>Información de pago:</h3>';
            $mensajeCompraUsuario .= '<b>Nombre: </b>' . $detalle['pago']['nombre'] . '<br/>';
            $mensajeCompraUsuario .= '<b>Apellido: </b>' . $detalle['pago']['apellido'] . '<br/>';
            $mensajeCompraUsuario .= '<b>Email: </b>' . $detalle['pago']['email'] . '<br/>';
            $mensajeCompraUsuario .= '<b>DNI/CUIT: </b>' . $detalle['pago']['dni'] . '<br/>';
            $mensajeCompraUsuario .= '<b>Provincia: </b>' . $detalle['pago']['provincia'] . '<br/>';
            $mensajeCompraUsuario .= '<b>Localidad: </b>' . $detalle['pago']['localidad'] . '<br/>';
            $mensajeCompraUsuario .= '<b>Dirección: </b>' . $detalle['pago']['direccion'] . '<br/>';
            $mensajeCompraUsuario .= '<b>Teléfono: </b>' . $detalle['pago']['telefono'] . '<br/>';
            if ($detalle['pago']['factura']) {
                $mensajeCompraUsuario .= '<p><b>Factura A al CUIT: </b>' . $detalle['pago']['dni'] . '</p>';
            }
            $mensajeCompraUsuario .= '<br/><hr/>';

            $mensajeCompraUsuario .= '<h3>MÉTODO DE PAGO ELEGIDO: ' . mb_strtoupper($pedidoData['data']["tipo"]) . '</h3>';
            $mensajeCompraUsuario .= '<br/><hr/>';
            $mensajeCompraUsuario .= '<h3>Tus datos:</h3>';
            $mensajeCompraUsuario .= $datos_usuario;

            $enviar->set("asunto", "Muchas gracias por tu nueva compra");
            $enviar->set("receptor", $pedidoData['user']['data']["email"]);
            $enviar->set("emisor", $emailData['data']['remitente']);
            $enviar->set("mensaje", $mensajeCompraUsuario);
            $enviar->emailEnviar();

            //ADMIN EMAIL
            $mensajeCompra = '¡Nueva compra desde la web!<br/>A continuación te dejamos el detalle del pedido.<hr/> <h3>Pedido realizado:</h3>';
            $mensajeCompra .= $mensaje_carro;

            $mensajeCompra .= '<br/><hr/>';
            $mensajeCompra .= '<h3>Información de envio:</h3>';
            $mensajeCompra .= '<b>Tipo: </b>' . $detalle['envio']['tipo'] . '<br/>';
            $mensajeCompra .= '<b>Nombre: </b>' . $detalle['envio']['nombre'] . '<br/>';
            $mensajeCompra .= '<b>Apellido: </b>' . $detalle['envio']['apellido'] . '<br/>';
            $mensajeCompra .= '<b>Email: </b>' . $detalle['envio']['email'] . '<br/>';
            $mensajeCompra .= '<b>Provincia: </b>' . $detalle['envio']['provincia'] . '<br/>';
            $mensajeCompra .= '<b>Localidad: </b>' . $detalle['envio']['localidad'] . '<br/>';
            $mensajeCompra .= '<b>Dirección: </b>' . $detalle['envio']['direccion'] . '<br/>';
            $mensajeCompra .= '<b>Teléfono: </b>' . $detalle['envio']['telefono'] . '<br/>';
            $mensajeCompra .= '<br/><hr/>';

            $mensajeCompra .= '<h3>Información de pago:</h3>';
            $mensajeCompra .= '<b>Nombre: </b>' . $detalle['pago']['nombre'] . '<br/>';
            $mensajeCompra .= '<b>Apellido: </b>' . $detalle['pago']['apellido'] . '<br/>';
            $mensajeCompra .= '<b>Email: </b>' . $detalle['pago']['email'] . '<br/>';
            $mensajeCompra .= '<b>DNI/CUIT: </b>' . $detalle['pago']['dni'] . '<br/>';
            $mensajeCompra .= '<b>Provincia: </b>' . $detalle['pago']['provincia'] . '<br/>';
            $mensajeCompra .= '<b>Localidad: </b>' . $detalle['pago']['localidad'] . '<br/>';
            $mensajeCompra .= '<b>Dirección: </b>' . $detalle['pago']['direccion'] . '<br/>';
            $mensajeCompra .= '<b>Teléfono: </b>' . $detalle['pago']['telefono'] . '<br/>';
            if ($detalle['pago']['factura']) {
                $mensajeCompra .= '<p><b>Factura A al CUIT: </b>' . $detalle['pago']['dni'] . '</p>';
            }
            $mensajeCompra .= '<br/><hr/>';

            $mensajeCompra .= '<h3>MÉTODO DE PAGO ELEGIDO: ' . mb_strtoupper($pedidoData['data']["tipo"]) . '</h3>';
            $mensajeCompra .= '<br/><hr/>';
            $mensajeCompra .= '<h3>Datos de usuario:</h3>';
            $mensajeCompra .= $datos_usuario;

            $enviar->set("asunto", "NUEVA COMPRA ONLINE");
            $enviar->set("receptor", $emailData['data']['remitente']);
            $enviar->set("emisor", $emailData['data']['remitente']);
            $enviar->set("mensaje", $mensajeCompra);
            $enviar->emailEnviar();
        }
    }
}