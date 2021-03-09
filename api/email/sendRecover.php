<?php
require_once "../../Config/Autoload.php";
Config\Autoload::runCurl();
$funciones = new Clases\PublicFunction();
$enviar = new Clases\Email();
$config = new Clases\Config();
$usuario = new Clases\Usuarios();
$emailData = $config->viewEmail();

$email = $funciones->antihack_mysqli(isset($_POST['email']) ? $_POST['email'] : '');
$result = '';

if (!empty($email)) {
    $usuario->set("email", $email);
    $usuarioData = $usuario->validate();

    if ($usuarioData['status'] && empty($usuarioData['data']['invitado']) ) {

        $usuario->set("cod", $usuarioData['data']['cod']);
        $password = substr(md5(uniqid(rand())), 0, 10);
        $usuario->editSingle("password", $password);

        //Envio de mail al usuario
        $mensaje = 'Hola ' . $usuarioData['data']['nombre'] . ' tu nueva contraseña es: ' . $password . '<br/>';
        $asunto = TITULO . ' - Recuperacion de contraseña';
        $receptor = $usuarioData['data']['email'];
        $emisor = $emailData['data']['remitente'];
        $enviar->set("asunto", $asunto);
        $enviar->set("receptor", $receptor);
        $enviar->set("emisor", $emisor);
        $enviar->set("mensaje", $mensaje);
        $enviar->emailEnviarCurl();

        $result = array('status' => true);
    }else{
        $result = array('status' => false);
    }
}else{
    $result = array('status' => false);
}

echo json_encode($result);