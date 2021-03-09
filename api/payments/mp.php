<?php
require_once dirname(__DIR__, 2) . "/Config/Autoload.php";
Config\Autoload::run();
$f = new Clases\PublicFunction();
$carrito = new Clases\Carrito();
$pagos = new Clases\Pagos();
$checkout = new Clases\Checkout();
$pedidos = new Clases\Pedidos();
$config = new Clases\Config();

$cod = $f->antihack_mysqli(isset($_POST['cod']) ? $_POST['cod'] : '');

if (!empty($cod)) {
    $pedidos->set("cod", $cod);
    $pedidosData = $pedidos->view();

    if (!empty($pedidosData['data'])) {
        $config->set("id", 1);
        $paymentsData = $config->viewPayment();
        $mp = new MP($paymentsData['data']['variable1'], $paymentsData['data']['variable2']);
        $preference_data = array(
            "items" => array(
                array(
                    "id" => $_SESSION['cod_pedido'],
                    "title" => "COMPRA CÓDIGO N°:" . $_SESSION['cod_pedido'],
                    "quantity" => 1,
                    "currency_id" => "ARS",
                    "unit_price" => floatval($pedidosData['data']['total'])
                )
            ),
            "payer" => array(
                "name" => $_SESSION['usuarios']["nombre"],
                "surname" => $_SESSION['usuarios']["apellido"],
                "email" => $_SESSION['usuarios']["email"]
            ),
            "back_urls" => array(
                "success" => URL . "/checkout/detail",
                "pending" => URL . "/checkout/detail",
                "failure" => URL . "/checkout/detail"
            ),
            "notification_url" => URL . "/api/payments/ipn.php",
            "external_reference" => $_SESSION['cod_pedido'],
            "auto_return" => "all"
        );
        $preference = $mp->create_preference($preference_data);

        $detalle = json_decode($pedidosData['data']['detalle'], true);
        $url = $preference['response']['init_point'];
        $detalle['link'] = $url;
        $detalleJSON = json_encode($detalle);
        $pedidos->set("detalle", $detalleJSON);
        $pedidos->changeValue('detalle');

        $checkout->close();
        $response = array("status" => true, "url" => $url);
        echo json_encode($response);
    } else {
        $response = array("status" => false, "message" => "Ocurrió un error, recargar la página.");
        echo json_encode($response);
    }
} else {
    $response = array("status" => false, "message" => "Ocurrió un error, recargar la página.");
    echo json_encode($response);
}
