<?php
$usuarios = new Clases\Usuarios();
$funciones = new Clases\PublicFunction();
$cod = $funciones->antihack_mysqli(isset($_GET["cod"]) ? $_GET["cod"] : '');
$usuarios->set("cod", $cod);
$usuario = $usuarios->view();

if (isset($_POST["agregar"])) {
    $usuarios->set("cod", $usuario["cod"]);
    $usuarios->set("nombre", $funciones->antihack_mysqli(isset($_POST["nombre"]) ? $_POST["nombre"] : $usuario["nombre"]));
    $usuarios->set("apellido", $funciones->antihack_mysqli(isset($_POST["apellido"]) ? $_POST["apellido"] : $usuario["apellido"]));
    $usuarios->set("doc", $funciones->antihack_mysqli(isset($_POST["doc"]) ? $_POST["doc"] : $usuario["doc"]));
    $usuarios->set("email", $funciones->antihack_mysqli(isset($_POST["email"]) ? $_POST["email"] : $usuario["email"]));
    $usuarios->set("password", $funciones->antihack_mysqli(isset($_POST["password"]) ? $_POST["password"] : $usuario["password"]));
    $usuarios->set("postal", $funciones->antihack_mysqli(isset($_POST["postal"]) ? $_POST["postal"] : $usuario["postal"]));
    $usuarios->set("localidad", $funciones->antihack_mysqli(isset($_POST["localidad"]) ? $_POST["localidad"] : $usuario["localidad"]));
    $usuarios->set("provincia", $funciones->antihack_mysqli(isset($_POST["provincia"]) ? $_POST["provincia"] : $usuario["provincia"]));
    $usuarios->set("pais", $funciones->antihack_mysqli(isset($_POST["pais"]) ? $_POST["pais"] : $usuario["pais"]));
    $usuarios->set("telefono", $funciones->antihack_mysqli(isset($_POST["telefono"]) ? $_POST["telefono"] : $usuario["telefono"]));
    $usuarios->set("celular", $funciones->antihack_mysqli(isset($_POST["celular"]) ? $_POST["celular"] : $usuario["celular"]));
    $usuarios->set("invitado", $funciones->antihack_mysqli(isset($_POST["invitado"]) ? $_POST["invitado"] : $usuario["invitado"]));
    $usuarios->set("descuento", $funciones->antihack_mysqli(isset($_POST["descuento"]) ? $_POST["descuento"] : $usuario["descuento"]));
    $usuarios->set("fecha", $funciones->antihack_mysqli(isset($_POST["fecha"]) ? $_POST["fecha"] : $usuario["fecha"]));

    $usuarios->edit();
    echo json_encode($usuarios,JSON_PRETTY_PRINT);

    //$funciones->headerMove(URL . "/index.php?op=usuarios");
}
?>
