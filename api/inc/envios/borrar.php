<?php
$envios = new Clases\Envios();
$funciones = new Clases\PublicFunction();
if (isset($_POST['borrar'])) {
    $cod = $funciones->antihack_mysqli(isset($_POST["cod"]) ? $_POST["cod"] : '');
    $envios->set("cod",$cod);
    $data = $envios->view();
    if (!empty($data)) {
        $envios->set("cod", $data['cod']);
        $envios->delete();
        $array = array("status" => true);
    } else {
        $array = array("status" => false);
    }
    echo json_encode($array, JSON_PRETTY_PRINT);
}
?>
