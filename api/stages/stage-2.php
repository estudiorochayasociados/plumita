<?php
require_once dirname(__DIR__,2)."/Config/Autoload.php";
Config\Autoload::run();
$funciones = new Clases\PublicFunction();
$carrito = new Clases\Carrito();
$checkout = new Clases\Checkout();

$nombre = $funciones->antihack_mysqli(isset($_POST['nombre']) ? $_POST['nombre'] : '');
$apellido = $funciones->antihack_mysqli(isset($_POST['apellido']) ? $_POST['apellido'] : '');
$email = $funciones->antihack_mysqli(isset($_POST['email']) ? $_POST['email'] : '');
$dni = $funciones->antihack_mysqli(isset($_POST['dni']) ? $_POST['dni'] : '');
$telefono = $funciones->antihack_mysqli(isset($_POST['telefono']) ? $_POST['telefono'] : '');
$provincia = $funciones->antihack_mysqli(isset($_POST['provincia']) ? $_POST['provincia'] : '');
$localidad = $funciones->antihack_mysqli(isset($_POST['localidad']) ? $_POST['localidad'] : '');
$direccion = $funciones->antihack_mysqli(isset($_POST['direccion']) ? $_POST['direccion'] : '');
$factura = $funciones->antihack_mysqli(isset($_POST['factura']) ? $_POST['factura'] : '');

if (!empty($nombre) && !empty($apellido) && !empty($email) && !empty($dni) && !empty($telefono) && !empty($provincia) && !empty($localidad) && !empty($direccion)) {

    $data = array(
        "nombre" => $nombre,
        "apellido" => $apellido,
        "email" => $email,
        "dni" => $dni,
        "telefono" => $telefono,
        "provincia" => $provincia,
        "localidad" => $localidad,
        "direccion" => $direccion,
        "factura" => $factura,
    );

    if ($checkout->stage2($data)) {
        $response = array("status" => true);
        echo json_encode($response);
    } else {
        $response = array("status" => false, "message" => "[201] Ocurrió un error, recargar la página.");
        echo json_encode($response);
    }
} else {
    $message = 'Completar los siguientes campos correctamente:<br>';
    if (empty($nombre)) {
        $message .= '- Nombre<br>';
    }
    if (empty($apellido)) {
        $message .= '- Apellido<br>';
    }
    if (empty($email)) {
        $message .= '- Email<br>';
    }
    if (empty($dni)) {
        $message .= '- DNI/CUIT<br>';
    }
    if (empty($telefono)) {
        $message .= '- Telefono<br>';
    }
    if (empty($provincia)) {
        $message .= '- Provincia<br>';
    }
    if (empty($localidad)) {
        $message .= '- Localidad<br>';
    }
    if (empty($direccion)) {
        $message .= '- Direccion<br>';
    }

    $response = array("status" => false, "message" => $message);
    echo json_encode($response);
}
