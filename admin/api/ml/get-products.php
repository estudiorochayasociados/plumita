<?php
require_once dirname(__DIR__, 3) . "/Config/Autoload.php";
Config\Autoload::run();
$producto = new Clases\Productos();
$ml = new Clases\MercadoLibre();
if (isset($_SESSION['access_token'])) {
    $type = isset($_POST["type"]) ? $_POST["type"] : 1;
    $productos = $producto->list(["meli = 1 AND precio != 0"], "id ASC", "", false);
    $response = [];
    foreach ($productos as $producto_) {
        $response[] = $producto_['data']['cod'];
    }
    echo json_encode(["status" => true, "products" => $response, "count" => count($response)]);
} else {
    echo json_encode(["status" => false, "message" => "Ingresar con su cuenta de MercadoLibre."]);
}
