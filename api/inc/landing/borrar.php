<?php
$landing = new Clases\Landing();
$funciones = new Clases\PublicFunction();
if (isset($_POST['borrar'])) {
    $cod = $funciones->antihack_mysqli(isset($_POST["cod"]) ? $_POST["cod"] : '');
    $landing->set("cod",$cod);
    $data = $landing->view();
    if (!empty($data)) {
        $landing->set("cod", $data['cod']);
        $landing->delete();
        $array = array("status" => true);
    } else {
        $array = array("status" => false);
    }
    echo json_encode($array, JSON_PRETTY_PRINT);
}
?>
