<?php
require_once "../../Config/Autoload.php";
Config\Autoload::runCurl();
$funciones = new Clases\PublicFunction();
$producto = new Clases\Productos();
$atributo = new Clases\Atributos();
$subatributo = new Clases\Subatributos();
$combinacion = new Clases\Combinaciones();
$detalleCombinacion = new Clases\DetalleCombinaciones();

$product = $funciones->antihack_mysqli(isset($_POST['product']) ? $_POST['product'] : '');
$amountAtributes = $funciones->antihack_mysqli(isset($_POST['amount-atributes']) ? $_POST['amount-atributes'] : '');

if ($amountAtributes == count($_POST['atribute'])) {
    if (!empty($product)) {
        $ERROR = '';
        $precio = 0;
        $stock = 0;
        $producto->set("cod", $product);
        $productoData = $producto->view();

        if (!empty($productoData['data'])) {

            //SI TIENE COMBINACION
            if (!empty($_POST['combination'])) {
                $resultValidate = $combinacion->check($_POST['atribute'], $product);

                if ($resultValidate['result'] === 1) {

                    $detalleCombinacion->set("codCombinacion", $resultValidate['combination']);
                    $detalleData = $detalleCombinacion->view();
                    if (!empty($detalleData)) {

                        $precio = $detalleData['precio'];
                        $stock = ($detalleData['stock']!= 0) ? $detalleData['stock'] :'¡Agotado!';
                        if (!empty($_SESSION['usuarios'])) {

                            if ($_SESSION['usuarios']['invitado'] != 1 || $_SESSION["usuarios"]["minorista"] == 1) {
                                $precio = $detalleData['precio'];
                                $stock = ($detalleData['stock']!= 0) ? $detalleData['stock'] :'¡Agotado!';
                            } else {
                                if (!empty($detalleData['mayorista'])) {
                                    $precio = $detalleData['mayorista'];
                                    $stock = ($detalleData['stock']!= 0) ? $detalleData['stock'] :'¡Agotado!';
                                }
                            }
                        }
                    } else {
                        $ERROR = 'Ocurrió un error, intente nuevamente.';
                    }
                } else {
                    $ERROR = 'Lo sentimos no hay producto con esos atributos.';
                }
            }

            if (!empty($ERROR)) {
                $result = array("status" => false, "message" => $ERROR);
                echo json_encode($result);
            } else {
                $result = array("status" => true, "price" => $precio, "stock" => $stock);
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
}
