<?php
$banners = new Clases\Usuarios();
$funciones = new Clases\PublicFunction();
if (isset($_POST['borrar'])) {
    $cod = $funciones->antihack_mysqli(isset($_POST["cod"]) ? $_POST["cod"] : '');
    $banners->set("cod",$cod);
    $data = $banners->view();
    if (!empty($data)) {
        $banners->set("cod", $data['cod']);
        $banners->delete();
        $array = array("status" => true);
    } else {
        $array = array("status" => false);
    }
    echo json_encode($array, JSON_PRETTY_PRINT);
}
?>
