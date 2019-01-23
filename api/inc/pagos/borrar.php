<?php
$pagos = new Clases\Pagos();
$funciones = new Clases\PublicFunction();
if (isset($_POST['borrar'])) {
    $cod = $funciones->antihack_mysqli(isset($_POST["cod"]) ? $_POST["cod"] : '');
    $pagos->set("cod",$cod);
    $data = $pagos->view();
    if (!empty($data)) {
        $pagos->set("cod", $data['cod']);
        $pagos->delete();
        $array = array("status" => true);
    } else {
        $array = array("status" => false);
    }
    echo json_encode($array, JSON_PRETTY_PRINT);
}
?>
