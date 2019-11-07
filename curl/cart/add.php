<?php
require_once "../../Config/Autoload.php";
Config\Autoload::runCurl();
$funciones = new Clases\PublicFunction();
$carrito = new Clases\Carrito();
$producto = new Clases\Productos();

$product = $funciones->antihack_mysqli(isset($_POST['product']) ? $_POST['product'] : '');
$amount = $funciones->antihack_mysqli(isset($_POST['amount']) ? $_POST['amount'] : '');

if (!empty($product)) {
    $carroEnvio = $carrito->checkEnvio();
    if (!empty($carroEnvio)) $carrito->delete($carroEnvio);

    $carroPago = $carrito->checkPago();
    if (!empty($carroPago)) $carrito->delete($carroPago);

    $producto->set("cod", $product);
    $productoData = $producto->view();

    $carrito->set("id", $productoData['cod']);
    $carrito->set("cantidad", $amount);
    $carrito->set("titulo", $productoData['cod_producto'] . " - " . $productoData['titulo']);
    $carrito->set("precio", 0);

    $opciones = '';

    $carrito->set("stock", $productoData['stock']);
    $carrito->set("peso", (int)$productoData['peso']);
    $carrito->set("opciones", $opciones);
    if ($carrito->add()) {
        echo json_encode(["status" => true]);
    } else {
        echo json_encode(["status" => false, "message" => "LO SENTIMOS NO CONTAMOS CON ESA CANTIDAD EN STOCK."]);
    }
}