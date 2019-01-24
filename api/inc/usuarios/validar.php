<?php
$usuarios = new Clases\Usuarios();
$email = isset($_GET["email"]) ? $_GET["email"] : '';
$password = isset($_GET["password"]) ? $_GET["password"] : '';
$data = $usuarios->list(array("email= '$email' AND password = '$password'"));
if (!empty($data)) {
    $array = array("cod"=>$data['0']['cod'],"nombre"=>$data['0']['nombre'],"apellido"=>$data['0']['apellido'],"status" => true);
} else {
    $array = array("status" => false);
}
echo json_encode($array,JSON_PRETTY_PRINT);
?>