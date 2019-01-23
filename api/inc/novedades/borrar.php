<?php
$novedades = new Clases\Novedades();
$funciones = new Clases\PublicFunction();
if (isset($_POST['borrar'])) {
    $cod = $funciones->antihack_mysqli(isset($_POST["cod"]) ? $_POST["cod"] : '');
    $novedades->set("cod",$cod);
    $data = $novedades->view();
    if (!empty($data)) {
        $novedades->set("cod", $data['cod']);
        $novedades->delete();
        $array = array("status" => true);
    } else {
        $array = array("status" => false);
    }
    echo json_encode($array, JSON_PRETTY_PRINT);
}
?>
