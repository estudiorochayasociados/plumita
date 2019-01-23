<?php
$usuarios = new Clases\Usuarios();
if (isset($_POST['borrar'])){
    $email = isset($_GET["email"]) ? $_GET["email"] : '';
    $data = $usuarios->list(array("email= '$email'"));
    if (!empty($data)) {
        $usuarios->set("cod", $data[0]['cod']);
        $usuarios->delete();
        $array = array("status" => true);
    } else {
        $array = array("status" => false);
    }
    echo json_encode($array, JSON_PRETTY_PRINT);
}
?>