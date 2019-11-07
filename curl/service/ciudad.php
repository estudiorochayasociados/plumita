<?php
require_once "../../Config/Autoload.php";
Config\Autoload::runCurl();
$funciones = new Clases\PublicFunction();
$service = new Clases\ServicioTecnico();

$ciudad = $funciones->antihack_mysqli(isset($_POST['ciudad']) ? $_POST['ciudad'] : '');

$ciudadData = $service->listForCiudad($ciudad);
if (!empty($ciudadData)) {
    $response = '';
    foreach ($ciudadData as $ciudadData_) {
        $response .= "<div class='col-md-4 distribuidores col-xs-6 col-sm-6 mt-30 mb-10'>";
        $response .= "<div style='padding: 10px'>";
        $response .= "<h4 class=' label label-danger fs-14'>" . $ciudadData_['data']['tecnico'] . "</h4>";
        $response .= "<p><i class='fa fa-phone'></i> " . $ciudadData_['data']['telefono'] . "</p>";
        $response .= "<p><i class='fa fa-envelope-o'></i> " . $ciudadData_['data']['email'] . "</p>";
        $response .= "<p class='text-uppercase fs-12'><i class='fa fa-map-marker'></i> " . $ciudadData_['data']['direccion']." - ".$ciudadData_['data']['ciudad']." - " . $ciudadData_['data']['provincia'] . "</p>";
        $response .= "</div>";
        $response .= "</div>";
    }
    $result = array("status" => true, "response" => $response);
    echo json_encode($result);
} else {
    $result = array("status" => false, "message" => "No hay ningún distribuidor.");
    echo json_encode($result);
}
