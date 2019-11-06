<?php
require_once "../../Config/Autoload.php";
Config\Autoload::runCurl();
$funciones = new Clases\PublicFunction();
$service = new Clases\ServicioTecnico();

$provincia = $funciones->antihack_mysqli(isset($_POST['provincia']) ? $_POST['provincia'] : '');

//if (!empty($provincia)) {
$provinciaData = $service->listForProvincia($provincia);
if (!empty($provinciaData)) {
    $response = '';
    foreach ($provinciaData as $provinciaData_) {
        $response .= "<div class='col-md-4 distribuidores col-xs-6 col-sm-6 mt-30 mb-10'>";
        $response .= "<div style='padding: 10px'>";
        $response .= "<h4 class=' label label-danger fs-14'>" . $provinciaData_['data']['tecnico'] . "</h4>";
        $response .= "<p><i class='fa fa-phone'></i> " . $provinciaData_['data']['telefono'] . "</p>";
        $response .= "<p><i class='fa fa-envelope-o'></i> " . $provinciaData_['data']['email'] . "</p>";
        $response .= "<p class='text-uppercase fs-12'><i class='fa fa-map-marker'></i> " . $provinciaData_['data']['direccion'] . " - " . $provinciaData_['data']['ciudad'] . " - " . $provinciaData_['data']['provincia'] . "</p>";
        $response .= "</div>";
        $response .= "</div>";
    }
    $ciudadData = $service->listCiudad($provincia);
    $ciudad = '<option disabled selected>Elegir localidad</option>';
    if (!empty($ciudadData)) {
        $ciudad = '<option disabled selected>Elegir ciudad</option>';
        foreach ($ciudadData as $ciudadData_) {
            $ciudad .= "<option value='" . $ciudadData_['ciudad'] . "'>" . $ciudadData_['ciudad'] . "</option>";
        }
    }

    $result = array("status" => true, "response" => $response, "ciudad" => $ciudad);
    echo json_encode($result);
} else {
    $result = array("status" => false, "message" => "No hay ningún distribuidor.");
    echo json_encode($result);
}
//} else {
//    $result = array("status" => false, "message" => "Ocurrió un error, recargar la página.");
//    echo json_encode($result);
//}
