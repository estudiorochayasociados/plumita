<?php
require_once dirname(__DIR__, 2) . "/Config/Autoload.php";
Config\Autoload::run();
$f = new Clases\PublicFunction();
$carrito = new Clases\Carrito();
$checkout = new Clases\Checkout();
$envios = new Clases\Envios();
$usuarios = new Clases\Usuarios();

$envio = $f->antihack_mysqli(isset($_POST['envio']) ? $_POST['envio'] : '');

$nombre = $f->antihack_mysqli(isset($_POST['nombre']) ? $_POST['nombre'] : '');
$apellido = $f->antihack_mysqli(isset($_POST['apellido']) ? $_POST['apellido'] : '');
$email = $f->antihack_mysqli(isset($_POST['email']) ? $_POST['email'] : '');
$telefono = $f->antihack_mysqli(isset($_POST['telefono']) ? $_POST['telefono'] : '');
$provincia = $f->antihack_mysqli(isset($_POST['provincia']) ? $_POST['provincia'] : '');
$localidad = $f->antihack_mysqli(isset($_POST['localidad']) ? $_POST['localidad'] : '');
$direccion = $f->antihack_mysqli(isset($_POST['direccion']) ? $_POST['direccion'] : '');

if (!empty($envio)) {
    if (!empty($nombre) && !empty($apellido) && !empty($email) && !empty($telefono) && !empty($provincia) && !empty($localidad) && !empty($direccion)) {

        $envios->set("cod", $envio);
        $envioData = $envios->view();

        if (!empty($envioData)) {

            $data = array(
                "envio" => $envio,
                "nombre" => $nombre,
                "apellido" => $apellido,
                "email" => $email,
                "telefono" => $telefono,
                "provincia" => $provincia,
                "localidad" => $localidad,
                "direccion" => $direccion,
            );

            if ($checkout->stage1($data)) {

                $carrito->deleteOnCheck("Envio-Seleccion");
                $carrito->deleteOnCheck("Metodo-Pago");

                if ($carrito->precioSinMetodoDePago() >= $envioData['data']['limite']) {
                    if ($envioData['data']['limite'] != 0){
                    $envioData['data']["titulo"] = "¡GRATIS COMPRA SUPERIOR A $".$envioData['data']['limite']."!";
                    $envioData['data']["precio"] = 0;
                    }
                }

                $carrito->set("id", "Envio-Seleccion");
                $carrito->set("cantidad", 1);
                $carrito->set("titulo", $envioData['data']["titulo"]);
                $carrito->set("precio", $envioData['data']["precio"]);
                $carrito->add();

                //Por si eligio invitado, ver de guardarlo en la base o updatearlo
                if ($_SESSION['stages']['type'] == 'GUEST') {
                    $usuarios->set("nombre", $nombre);
                    $usuarios->set("apellido", $apellido);
                    $usuarios->set("doc", '');
                    $usuarios->set("email", $email);
                    $usuarios->set("password", '');
                    $usuarios->set("direccion", $direccion);
                    $usuarios->set("localidad", $localidad);
                    $usuarios->set("provincia", $provincia);
                    $usuarios->set("telefono", $telefono);
                    $usuarios->set("invitado", 1);
                    $usuarios->set("fecha", date("Y-m-d"));
                    $usuarios->set("estado", 1);
                    $usuarios->set("minorista", 1);

                    $emailData = $usuarios->validate();
                    if ($emailData['status']) {
                        $cod = $emailData['data']['cod'];
                    } else {
                        $cod = substr(md5(uniqid(rand())), 0, 10);
                    }

                    $usuarios->set("cod", $cod);

                    if ($emailData['status']) {
                        $usuarios->guestSession();
                        $checkout->user($cod, 'GUEST');
                        $response = array("status" => true);
                        echo json_encode($response);
                    } else {
                        $usuarios->firstGuestSession();
                        $checkout->user($cod, 'GUEST');
                        $response = array("status" => true);
                        echo json_encode($response);
                    }
                } else {
                    $response = array("status" => true);
                    echo json_encode($response);
                }
            } else {
                $response = array("status" => false, "type" => "error", "message" => "[101] Ocurrió un error, recargar la página.");
                echo json_encode($response);
            }
        } else {
            $response = array("status" => false, "type" => "error", "message" => "[102] Ocurrió un error, recargar la página.");
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

        $response = array("status" => false, "type" => "error", "message" => $message);
        echo json_encode($response);
    }
} else {
    $response = array("status" => false, "type" => "error", "message" => 'Seleccionar un tipo de envío.');
    echo json_encode($response);
}
