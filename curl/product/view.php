<?php
require_once "../../Config/Autoload.php";
Config\Autoload::runCurl();
$funciones = new Clases\PublicFunction();
$producto = new Clases\Productos();

$product = $funciones->antihack_mysqli(isset($_POST['cod']) ? $_POST['cod'] : '');

if (isset($_SESSION['usuarios'])) {
    if (!empty($product)) {
        $producto->set("cod", $product);
        $productoData = $producto->view();
        if (!empty($productoData)) {
            $precio = $productoData['precio'];
            $response = '';

            $price_ = $productoData['precio'];
            if (!empty($_SESSION['usuarios']['descuento'])) {
                $price_ = $productoData['precio'] - (($_SESSION['usuarios']['descuento'] * $productoData['precio']) / 100);
            }
            $header = $productoData['titulo'] . '- ' . $productoData['cod_producto'];

            $response .= "<form class='form-box' id='cartForm$product'>";
            $response .= "<h5>HACER PEDIDO</h5>";
            $response .= "<hr>";
            $response .= "<input type='hidden' name='product' value='$product'>";
            $response .= "<div class='row'>";
            $response .= "<div class='col-md-12'>";
            $response .= "<b>Cantidad: </b>";
            $response .= "<input type='number' id='amount' name='amount' min='1' class='form-control form-value' value='1' onkeydown='return (event.keyCode!=13);' required>";
            $response .= "</div>";
            $response .= "</div>";
            $response .= "<hr class='space m'>";
            $response .= "</form>";
            $result = array("status" => true, "header" => $header, "response" => $response);
            echo json_encode($result);
        } else {
            $result = array("status" => false, "message" => "Ocurrió un error, recargar la página.");
            echo json_encode($result);
        }
    } else {
        $result = array("status" => false, "message" => "Ocurrió un error, recargar la página.");
        echo json_encode($result);
    }
} else {
    $result = array("status" => false, "message" => "Ocurrió un error, recargar la página.");
    echo json_encode($result);
}
