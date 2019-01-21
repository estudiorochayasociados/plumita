<?php
$usuarios = new Clases\Usuarios();
$email = isset($_GET["email"]) ? $_GET["email"] : '';
$data = $usuarios->list(array("email= '$email'"));
if (!empty($data)) {
    $array = array("status" => true);
} else {
    $array = array("status" => false);
}
echo json_encode($array,JSON_PRETTY_PRINT);
?>