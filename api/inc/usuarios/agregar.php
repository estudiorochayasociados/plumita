<?php
$usuarios = new Clases\Usuarios();
$funciones = new Clases\PublicFunction();

$cod = substr(md5(uniqid(rand())), 0, 10);
$usuarios->set("cod", $cod);
$usuarios->set("nombre", $funciones->antihack_mysqli(isset($_GET["nombre"]) ? $_GET["nombre"] : ''));
$usuarios->set("apellido", $funciones->antihack_mysqli(isset($_GET["apellido"]) ? $_GET["apellido"] : ''));
$usuarios->set("email", $funciones->antihack_mysqli(isset($_GET["email"]) ? $_GET["email"] : ''));
$usuarios->set("password", $funciones->antihack_mysqli(isset($_GET["password"]) ? $_GET["password"] : ''));
$usuarios->set("postal", $funciones->antihack_mysqli(isset($_GET["postal"]) ? $_GET["postal"] : ''));
$usuarios->set("localidad", $funciones->antihack_mysqli(isset($_GET["localidad"]) ? $_GET["localidad"] : ''));
$usuarios->set("provincia", $funciones->antihack_mysqli(isset($_GET["provincia"]) ? $_GET["provincia"] : ''));
$usuarios->set("pais", $funciones->antihack_mysqli(isset($_GET["pais"]) ? $_GET["pais"] : ''));
$usuarios->set("telefono", $funciones->antihack_mysqli(isset($_GET["telefono"]) ? $_GET["telefono"] : ''));
$usuarios->set("celular", $funciones->antihack_mysqli(isset($_GET["celular"]) ? $_GET["celular"] : ''));
$usuarios->set("invitado", $funciones->antihack_mysqli(isset($_GET["invitado"]) ? $_GET["invitado"] : '0'));
$usuarios->set("descuento", $funciones->antihack_mysqli(isset($_GET["descuento"]) ? $_GET["descuento"] : ''));
$usuarios->set("fecha", $funciones->antihack_mysqli(isset($_GET["fecha"]) ? $_GET["fecha"] : date("Y-m-d")));
$usuarios->add();
$array = array("cod" => $cod, "nombre" => $_GET['nombre'], "apellido" => $_GET['apellido'], "status" => true);
echo json_encode($array, JSON_PRETTY_PRINT);
?>
