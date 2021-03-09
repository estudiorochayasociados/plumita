<?php
require_once dirname(__DIR__, 2) . "/Config/Autoload.php";
Config\Autoload::run();
$funciones = new Clases\PublicFunction();
$producto = new Clases\Productos();

$type = $funciones->antihack_mysqli(isset($_POST['type']) ? $_POST['type'] : '1');

if (isset($_SESSION['access_token'])) {
    switch ($type) {
        //Todos
        default:
            $productos = $producto->list(["precio > 0"], "id ASC", "");
            break;
        case 2:
            $productos = $producto->list(["precio > 0", "IsNull(meli)"], "id ASC", "");
            break;
        case 3:
            $productos = $producto->list(["precio > 0", "meli!=''"], "id ASC", "");
            break;
    }
    $response = [];
    foreach ($productos as $producto_) {
        $response[] = $producto_['data']['cod'];
    }
    echo json_encode(["status" => true, "products" => $response]);
} else {
    echo json_encode(["status" => false, "message" => "Ingresar con su cuenta de MercadoLibre."]);
}