<?php
require_once dirname(__DIR__,2)."/Config/Autoload.php";
Config\Autoload::run();
$funciones = new Clases\PublicFunction();
$usuario = new Clases\Usuarios();
$config = new Clases\Config();
$captchaData = $config->viewCaptcha();

// Verify the reCAPTCHA response
$verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $captchaData['data']['captcha_secret'] . '&response=' . $_POST['g-recaptcha-response']);
$responseData = json_decode($verifyResponse);
if ($responseData->success) {
    $email = $funciones->antihack_mysqli(isset($_POST['email']) ? $_POST['email'] : '');
    if (!empty($email)) {
        $usuario->set("email", $email);
        $response = $usuario->validate();
        if ($response['status']) {
            $result = array("status" => true);
            echo json_encode($result);
        } else {
            $result = array("status" => false, "message" => "No existe un usuario registrado con ese correo");
            echo json_encode($result);
        }
    } else {
        $result = array("status" => false, "message" => "Completar el campo correctamente.");
        echo json_encode($result);
    }
} else {
    $result = array("status" => false, "message" => "¡Completar el CAPTCHA correctamente!");
    echo json_encode($result);
}




