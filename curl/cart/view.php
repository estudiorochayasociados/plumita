<?php
require_once "../../Config/Autoload.php";
Config\Autoload::runCurl();
$funciones = new Clases\PublicFunction();
$carrito = new Clases\Carrito();
$carro = $carrito->return();

if (!empty($carro)) {
    $response = "";
    $response .= "<table class='table table-striped'>";
    $response .= "<thead>";
    $response .= "<th width='70%'>Nombre producto</th>";
//    $response .= "<th>Precio Unidad</th>";
    $response .= "<th>Cantidad</th>";
    $response .= "<th></th>";
    $precio = 0;//$carrito->totalPrice();

    foreach ($carro as $key => $carroItem) {
        $precio += ($carroItem["precio"] * $carroItem["cantidad"]);
        if ($carroItem["id"] == "Envio-Seleccion" || $carroItem["id"] == "Metodo-Pago") {
            $clase = "text-bold";
            $none = "hidden";
        } else {
            $clase;
            $none = "";
        }
        $response .= "<tr>";
        $response .= "<td>";
        $response .= mb_strtoupper($carroItem["titulo"]);
        $response .= "</td>";
        $response .= "<td>";
        $response .= "<span class='amount $none'>" . $carroItem['cantidad'] . "</span>";
        $response .= "</td>";
        $response .= "<td>";
        $url = URL;
        $response .= "<a href='$url/armar-pedido?remover=$key'>";
        $response .= "<i class='fa fa-times' aria-hidden='true'></i>";
        $response .= "</a>";
        $response .= "</td>";
        $response .= "</tr>";
    }
    $response .= "</table>";
    $response .= "<a href=\"" . URL . "/checkout\" class=\"btn btn-block btn-success\" id='btnSend' >
                    <i class=\"fa fa-shopping-cart\"></i> ENVIAR PEDIDO
                </a>";
    $result = array("status" => true, "texto" => $response);
    echo json_encode($result);
} else {
    $response = "<div>";
    $response .= "<i class='fa fa-shopping-cart fs-70' style='color:gray'> Vacío</i>";
    $response .= "</div>";
    $result = array("status" => false, "texto" => $response);
    echo json_encode($result);
}