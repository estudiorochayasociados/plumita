<?php
require_once dirname(__DIR__, 2) . "/Config/Autoload.php";
Config\Autoload::run();
$funciones = new Clases\PublicFunction();
$checkout = new Clases\Checkout();
$pedidos = new Clases\Pedidos();
$config = new Clases\Config();
//$cod = $funciones->antihack_mysqli(isset($_POST['cod']) ? $_POST['cod'] : '');
$cod = $funciones->antihack_mysqli(isset($_SESSION['cod_pedido']) ? $_SESSION['cod_pedido'] : '');

$config->set("id", 3);
$paymentsData = $config->viewPayment();
$mode = $paymentsData['data']['variable3']; //identificador de entorno obligatorio, la otra opción es "prod"
$http_header = array('Authorization' => "TODOPAGO " . $paymentsData['data']['variable2']); //authorization key del ambiente requerido
$merchant = $paymentsData['data']['variable1'];
$connector = new TodoPago\Sdk($http_header, $mode);
$dataPayment = $connector->getStatus(array('MERCHANT' => $merchant, 'OPERATIONID' => $_SESSION['cod_pedido']));
if ($dataPayment["Operations"]["RESULTCODE"] == "-1") {
    $estado = 2;
    $link = URL . "/checkout/detail";
} else {
    $estado = 4;
    $link = URL . "/checkout/detail&message=" . $dataPayment["Operations"]["RESULTMESSAGE"];
}

$pedidos->set("estado", $estado);
$pedidos->set("cod", $cod);
$pedidos->changeState();
$funciones->headerMove($link);
