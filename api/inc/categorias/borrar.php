<?php
$categorias = new Clases\Categorias();
$funciones = new Clases\PublicFunction();
if (isset($_POST['borrar'])) {
    $cod = $funciones->antihack_mysqli(isset($_POST["cod"]) ? $_POST["cod"] : '');
    $categorias->set("cod",$cod);
    $data = $categorias->view();
    if (!empty($data)) {
        $categorias->set("cod", $data['cod']);
        $categorias->delete();
        $array = array("status" => true);
    } else {
        $array = array("status" => false);
    }
    echo json_encode($array, JSON_PRETTY_PRINT);
}
?>
