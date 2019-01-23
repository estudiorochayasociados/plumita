<?php
$productos = new Clases\Productos();
$funciones = new Clases\PublicFunction();
if (isset($_POST['borrar'])) {
    $cod = $funciones->antihack_mysqli(isset($_POST["cod"]) ? $_POST["cod"] : '');
    $productos->set("cod",$cod);
    $data = $productos->view();
    if (!empty($data)) {
        $productos->set("cod", $data['cod']);
        $productos->delete();
        $array = array("status" => true);
    } else {
        $array = array("status" => false);
    }
    echo json_encode($array, JSON_PRETTY_PRINT);
}
?>
