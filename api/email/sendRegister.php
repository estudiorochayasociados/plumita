<?php
require_once dirname(__DIR__,2)."/Config/Autoload.php";
Config\Autoload::run();
$funciones = new Clases\PublicFunction();
$enviar = new Clases\Email();
$config = new Clases\Config();
$usuario = new Clases\Usuarios();
$emailData = $config->viewEmail();

$cod = $funciones->antihack_mysqli(isset($_POST['cod']) ? $_POST['cod'] : '');

if (!empty($cod)) {
    $usuario->set("cod", $cod);
    $usuarioData = $usuario->view();

    if (!empty($usuarioData)) {
        //Envio de mail al usuario
        $mensaje = 'Gracias por registrarse ' . ucfirst($usuarioData['data']['nombre']) . '<br/>';
        $asunto = TITULO . ' - Registro';
        $receptor = $usuarioData['data']['email'];
        $emisor = $emailData['data']['remitente'];
        $enviar->set("asunto", $asunto);
        $enviar->set("receptor", $receptor);
        $enviar->set("emisor", $emisor);
        $enviar->set("mensaje", $mensaje);
        $enviar->emailEnviar();

        //Envio de mail a la empresa
        $mensaje2 = 'El usuario ' . ucfirst($usuarioData['data']['nombre']) . ' ' . ucfirst($usuarioData['data']['apellido']) . ' acaba de registrarse en nuestra plataforma' . '<br/>';
        $asunto2 = TITULO . ' - Registro';
        $receptor2 = $emailData['data']['remitente'];
        $emisor2 = $emailData['data']['remitente'];
        $enviar->set("asunto", $asunto2);
        $enviar->set("receptor", $receptor2);
        $enviar->set("emisor", $emisor2);
        $enviar->set("mensaje", $mensaje2);
        $enviar->emailEnviar();
    }
}