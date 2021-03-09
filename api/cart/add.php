<?php
require_once dirname(__DIR__, 2) . "/Config/Autoload.php";
Config\Autoload::run();
$f = new Clases\PublicFunction();
$carrito = new Clases\Carrito();
$producto = new Clases\Productos();
$atributo = new Clases\Atributos();
$subatributo = new Clases\Subatributos();
$combinacion = new Clases\Combinaciones();
$detalleCombinacion = new Clases\DetalleCombinaciones();
$checkout = new Clases\Checkout();

$product = $f->antihack_mysqli(isset($_POST['product']) ? $_POST['product'] : ''); //TODO POST
$amount = intval($f->antihack_mysqli(isset($_POST['amount']) ? $_POST['amount'] : 1)); //TODO POST

if (empty($amount)) $amount = 1;
$product = trim(str_replace(" ", "", $product));

$producto->set("cod", $product);
$productoData = $producto->view();
if (empty($productoData)) {
    echo json_encode(["status" => false, "type" => "error", "message" => "Ocurrió un error, recargar la página."]);
    die();
}

$opciones = $error = '';

$carrito->deleteOnCheck("Envio-Seleccion");
$carrito->deleteOnCheck("Metodo-Pago");
$carrito->set("id", $productoData['data']['cod']);
$carrito->set("cantidad", $amount);
$carrito->set("titulo", $productoData['data']['titulo']);
$carrito->set("stock", $productoData['data']['stock']);
$carrito->set("peso", number_format($productoData['data']['peso'], 2, ".", ""));
$carrito->set("opciones", $opciones);
$carrito->set("precio", $productoData['data']['precio_final']);

//ATRIBUTOS
$atributeResponse = $atributo->checkAtributeOnAddCart(isset($_POST['atribute']) ? $_POST['atribute'] : '');
if ($atributeResponse) $carrito->set("opciones", $atributeResponse);
$opciones = '';
//CARGA DE STOCK PESO Y OPCIONES
$carrito->set("stock", $productoData['data']['stock']);
if ($productoData['data']['variable10'] == 1) {
    $carrito->set('peso', 0);
} else {
    $carrito->set("peso", number_format($productoData['data']['peso'], 2, ".", ""));
}
$carrito->set("opciones", $opciones);

//SI VIENE ATRIBUTO
if (!empty($_POST['atribute'])) {
    $opcion = '| ';
    $atri;
    foreach ($_POST['atribute'] as $key => $atrib) {
        $atributo->set("cod", $key);
        $titulo = $atributo->view()['atribute']['value'];
        $subatributo->set("cod", $atrib);
        $sub = $subatributo->view()['data']['value'];
        $opcion .= "<strong>$titulo: </strong>$sub | ";
        $atri[] = array($titulo => $sub);
    }
    $opciones = array("texto" => $opcion, "subatributos" => $atri);
    $carrito->set("opciones", $opciones);
}
   //SI TIENE COMBINACION
   if (!empty($_POST['combination'])) {
    $resultValidate = $combinacion->check($_POST['atribute'], $product);
    if ($resultValidate['result'] === 1) {
        $detalleCombinacion->set("codCombinacion", $resultValidate['combination']);
        $detalleData = $detalleCombinacion->view();

        if (!empty($detalleData)) {
            $carrito->set("precio", $detalleData['precio']);
            if (!empty($_SESSION['usuarios'])) {
                if ($_SESSION['usuarios']['invitado'] != 1 || $_SESSION["usuarios"]["minorista"] == 1) {
                    $carrito->set("precio", $detalleData['precio']);
                } else {
                    if (!empty($detalleData['mayorista'])) {
                        $carrito->set("precio", $detalleData['mayorista']);
                    }
                }
            }

            $opciones = array("texto" => $opcion, "combinacion" => $detalleData);

            $carrito->set("stock", $detalleData['stock']);
            $carrito->set("peso", number_format($productoData['data']['peso'], 2, ".", ""));
            $carrito->set("opciones", $opciones);
        } else {
            $ERROR = 'Ocurrió un error, intente nuevamente.';
        }
    } else {
        $ERROR = 'LO SENTIMOS NO HAY PRODUCTOS CON ESAS ESPECIFICACIONES.';
    }
}
if (!empty($error)) {
    echo json_encode(["status" => false, "type" => "error", "message" => $error]);
} else {
    if ($amount <= $productoData['data']['stock']) {
        if ($carrito->add()) {
            $checkout->destroy();
            $_SESSION['latest'] = $productoData['data']['titulo'];
            echo json_encode(["status" => true]);
        } else {
            echo json_encode(["status" => false, "type" => "error", "message" => "LO SENTIMOS NO CONTAMOS CON ESA CANTIDAD EN STOCK, COMPRUEBE SI YA POSEE ESTE PRODUCTO EN SU CARRITO."]);
        }
    } else {
        echo json_encode(["status" => false, "type" => "error", "message" => "LO SENTIMOS NO CONTAMOS CON ESA CANTIDAD EN STOCK."]);
    }
}
