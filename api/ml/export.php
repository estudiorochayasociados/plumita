<?php
require_once "../../Config/Autoload.php";
Config\Autoload::run();
$funciones = new Clases\PublicFunction();
$producto = new Clases\Productos();
$ml = new Clases\MercadoLibre();

$product = $funciones->antihack_mysqli(isset($_POST['product']) ? $_POST['product'] : '');

$classic = $funciones->antihack_mysqli(isset($_POST['classic']) ? $_POST['classic'] : '');
$premium = $funciones->antihack_mysqli(isset($_POST['premium']) ? $_POST['premium'] : '');
$check1 = $funciones->antihack_mysqli(isset($_POST['check1']) ? $_POST['check1'] : '');

$ml->checkExpiration();

if (!empty($_SESSION['access_token'])) {
    $producto->set("cod", $product);
    $productData = $producto->view();

    $ml->titulo = $productData['data']["titulo"];
    $ml->stock = $productData['data']['stock'];
    $ml->variable7 = $productData['data']['variable7'];//TIPO DE PUBLICACION
    $ml->variable9 = !empty($productData['data']['variable9']) ? $productData['data']['variable9'] : $productData['data']['titulo'];//Prediccion
    $ml->cod_producto = $productData['data']['cod_producto'];

    $description = $ml->titulo . '\n';
    $description .= 'LAS COMPRAS SUPERIORES A $2000 TIENEN ENVÍO GRATIS (tiene que utilizar el carrito de compras)\n';
    $description .= '\n_____________\n';
    $description .= '*Si necesita factura coloque su CUIT o datos de facturación en *Datos para Facturar* al momento de hacer la compra, caso contrario se utilizan los datos de la cuenta y NO se realizan modificaciones.\n';
    $description .= 'ATENCIÓN: Si su envío es Full no podrá contactarnos por mensaje! Si o si debe cargar los datos en la cuenta\n';
    $ml->desarrollo = $description;

    $ml->precio = ceil($productData['data']["precio"]);
    if (!empty($productData['data']['cod_producto'])) {
        //$ml->img = [
        //    "source" => URL_IMG . str_replace('/', '-', str_replace(" ", "%20", $productData['data']["cod_producto"])) . URL_IMG_FORMAT
        //];
    }
    $ml->setOptions(["classic" => $classic, "premium" => $premium, "check1" => $check1]);

    $response = [
        "title" => $productData['data']['titulo'],
        "cod" => $productData['data']['cod_producto'],
        "price" => '',
        "shipment" => '',
        "status" => '',
        "message" => ''
    ];

    if (!empty($productData['data']["meli"])) {
        $ml->meli = $productData['data']["meli"];
        $result = $ml->read($productData['data']['meli']);

        if (!isset($result['status'])) echo ["response" => false];

        switch ($result['status']) {
            case "closed":
                $response['status'] = false;
                $response['message'] = "ESTE PRODUCTO FUE ELIMINADO DE MERCADOLIBRE";
                break;
            default:
                if ($productData['data']['stock'] > 0) {
                    $ml->activate();
                } else {
                    $ml->pause();
                    $ml->stock = 0;
                }
                $result = $ml->update($result['category_id']);
                break;
        }
    } else {
        if ($productData['data']['stock'] > 0) {
            $result = $ml->create();
        } else {
            $response['status'] = false;
            $response['message'] = "No se puede publicar este producto porque su stock es 0.";
        }
    }

    if (isset($result['data']["id"]) && empty($productData['data']['meli'])) {
        $ml->set("cod", $productData['data']['cod']);
        $ml->editSingle("meli", "'" . $result['data']["id"] . "'");
    }

    $response['price'] = $result['price'];
    $response['shipment'] = $result['shipment'];
    $response['status'] = $result['status'];
    if (!$result['status']) {
        $error = '';
        if (is_array($result['error'])) {
            foreach ($result['error'] as $err) {
                $error .= $err['message'];
            }
        } else {
            $error .= $result['error'];
        }
        $response['message'] = $error;
    }

    $text = "<tr>";
    $text .= "<th>" . $response['title'] . "</th>";
    $text .= "<th class='text-center'>" . $response['cod'] . "</th>";
    $text .= "<th class='text-center'>$" . $response['price'] . "</th>";
    $text .= "<th class='text-center'>$" . $response['shipment'] . "</th>";
    if ($response['status']) {
        $status = "<i style='color:green' class='fa fa-check-circle'></i>";
    } else {
        $status = "<i style='color:red' class='fa fa-times-circle'></i>";
    }
    $text .= "<th class='text-center'>" . $status . "</th>";
    $text .= "<th class='text-center'>" . $response['message'] . "</th>";
    $text .= "</tr>";

    echo json_encode(["response" => true, "text" => $text]);
} else {
    echo json_encode(["response" => false]);
}

