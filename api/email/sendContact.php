<?php
require_once dirname(__DIR__,2)."/Config/Autoload.php";
Config\Autoload::run();
$funciones = new Clases\PublicFunction();
$enviar = new Clases\Email();
$pedido = new Clases\Pedidos();
$config = new Clases\Config();
$emailData = $config->viewEmail();
$captchaData = $config->viewCaptcha();

$nombre = $funciones->antihack_mysqli(isset($_POST['nombre']) ? $_POST['nombre'] : '');
$email = $funciones->antihack_mysqli(isset($_POST['email']) ? $_POST['email'] : '');
$consulta = $funciones->antihack_mysqli(isset($_POST['mensaje']) ? $_POST['mensaje'] : '');
$telefono = $funciones->antihack_mysqli(isset($_POST['telefono']) ? $_POST['telefono'] : '');
$email = $funciones->antihack_mysqli(isset($_POST['email']) ? $_POST['email'] : '');

// Verify the reCAPTCHA response
//$verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $captchaData['data']['captcha_secret'] . '&response=' . $_POST['g-recaptcha-response']);
//$responseData = json_decode($verifyResponse);
//if ($responseData->success) {
    $mensajeFinal = "<b>Nombre</b>: " . $nombre . " <br/>";
    $mensajeFinal .= "<b>Teléfono</b>: " . $telefono . "<br/>";
    $mensajeFinal .= "<b>Email</b>: " . $email . "<br/>";
    $mensajeFinal .= "<b>Consulta</b>: " . $consulta . "<br/>";

    //USUARIO
    $enviar->set("asunto", "Realizaste tu consulta");
    $enviar->set("receptor", $email);
    $enviar->set("emisor", $emailData['data']['remitente']);
    $enviar->set("mensaje", $mensajeFinal);
    $enviar->emailEnviar();
//    if ($enviar->emailEnviar() == 1) {
//        echo '<div class="alert alert-success" role="alert">¡Consulta enviada correctamente!</div>';
//    }

    $mensajeFinalAdmin = "<b>Nombre</b>: " . $nombre . " <br/>";
    $mensajeFinalAdmin .= "<b>Teléfono</b>: " . $telefono . "<br/>";
    $mensajeFinalAdmin .= "<b>Email</b>: " . $email . "<br/>";
    $mensajeFinalAdmin .= "<b>Consulta</b>: " . $consulta . "<br/>";
    //ADMIN
    $enviar->set("asunto", "Consulta Web");
    $enviar->set("receptor", $emailData['data']['remitente']);
    $enviar->set("mensaje", $mensajeFinalAdmin);
//    if ($enviar->emailEnviar() == 0) {
//        echo '<div class="alert alert-danger" role="alert">¡No se ha podido enviar la consulta!</div>';
//    }

//}else{
//
//}

