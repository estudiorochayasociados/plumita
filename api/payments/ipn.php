<?php
require_once dirname(__DIR__, 2) . "/Config/Autoload.php";
Config\Autoload::run();
include(dirname(__DIR__, 2) . "/vendor/mercadopago/sdk/lib/mercadopago.php");
$config = new Clases\Config();
$pedidos = new Clases\Pedidos();
$config->set("id", 1);
$paymentsData = $config->viewPayment();
$mp = new MP($paymentsData['data']['variable1'], $paymentsData['data']['variable2']);
$payment_info = $mp->get_payment_info($_GET["id"]);
if ($payment_info["status"] == 200) {
    $cod = $payment_info["response"]["external_reference"];
    $status = $payment_info["response"]["status"];

    switch ($status) {
        case "null":
            $estado = 0;
            break;
        case "pending":
            $estado = 1;
            break;
        case "approved":
            $estado = 2;
            break;
        case "rejected":
            $estado = 4;
            break;
    }

    $pedidos->set("estado", $estado);
    $pedidos->set("cod", $cod);
    $pedidos->changeState();
}
