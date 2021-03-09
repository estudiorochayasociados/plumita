<?php
require_once dirname(__DIR__,2)."/Config/Autoload.php";
Config\Autoload::run();
$funciones = new Clases\PublicFunction();
$checkout = new Clases\Checkout();
$pedidos = new Clases\Pedidos();
$config = new Clases\Config();

$cod = $funciones->antihack_mysqli(isset($_POST['cod']) ? $_POST['cod'] : '');

if (!empty($cod)) {
    $pedidos->set("cod", $cod);
    $pedidosData = $pedidos->view();

    if (!empty($pedidosData['data'])) {
        $config->set("id", 1);
        $paymentsData = $config->viewPayment();
        MercadoPago\SDK::setAccessToken($paymentsData['data']['variable3']);
        //MercadoPago\SDK::setAccessToken('TEST-7886107849656513-051317-fa8a35f007c198d7da43da0463e631fc-140101258');

        $preference = new MercadoPago\Preference();

        $item = new MercadoPago\Item();
        $item->title = "COMPRA CÓDIGO N°:" . $_SESSION['cod_pedido'];
        $item->quantity = 1;
        $item->unit_price = floatval($pedidosData['data']['total']);


        $payer = new MercadoPago\Payer();
        $payer->name = $_SESSION['usuarios']["nombre"];
        $payer->surname = $_SESSION['usuarios']["apellido"];
        $payer->email = $_SESSION['usuarios']["email"];

        $preference->items = array($item);

        $preference->back_urls = array(
            "success" => URL . "/checkout/detail",
            "failure" => URL . "/checkout/detail",
            "pending" => URL . "/checkout/detail"
        );

        $preference->notification_url = URL . "/api/payments/mercadopago/ipn.php";
        $preference->external_reference = $_SESSION['cod_pedido'];

        $preference->auto_return = "all";


        $preference->save();
        $url = $preference->sandbox_init_point;

        if (empty($url)) {
            echo json_encode(["status" => false, "message" => "Ocurrió un error, recargar la página."]);
            die();
        }

        $detalle = json_decode($pedidosData['data']['detalle'], true);
        $detalle['link'] = $url;
        $detalleJSON = json_encode($detalle);
        $pedidos->set("detalle", $detalleJSON);
        $pedidos->changeValue('detalle');

        $checkout->close();
        echo json_encode(["status" => true, "url" => $url]);
    } else {
        echo json_encode(["status" => false, "message" => "Ocurrió un error, recargar la página."]);
    }
} else {
    echo json_encode(["status" => false, "message" => "Ocurrió un error, recargar la página."]);
}
