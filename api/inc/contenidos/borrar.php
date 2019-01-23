<?php
$contenidos = new Clases\Contenidos();
$funciones = new Clases\PublicFunction();
if (isset($_POST['borrar'])) {
    $cod = $funciones->antihack_mysqli(isset($_POST["cod"]) ? $_POST["cod"] : '');
    $contenidos->set("cod",$cod);
    $data = $contenidos->view();
    if (!empty($data)) {
        $contenidos->set("cod", $data['cod']);
        $contenidos->delete();
        $array = array("status" => true);
    } else {
        $array = array("status" => false);
    }
    echo json_encode($array, JSON_PRETTY_PRINT);
}
?>
