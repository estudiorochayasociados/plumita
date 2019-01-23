<?php
$categorias = new Clases\Categorias();
$funciones = new Clases\PublicFunction();

$cod = isset($_GET["cod"]) ? $_GET["cod"] : '';
$categorias->set("cod", $cod);
$data = $categorias->view();
$zebra = new Clases\Zebra_Image();

if (isset($_POST["agregar"])) {
    $count = 0;
    $categorias->set("cod", $cod);
    $categorias->set("titulo", $funciones->antihack_mysqli(isset($_POST["titulo"]) ? $_POST["titulo"] : $data['titulo']));
    $categorias->set("area", $funciones->antihack_mysqli(isset($_POST["area"]) ? $_POST["area"] : $data['area']));
    if ($categorias->edit()) {
        $array = array("status" => true);
    } else {
        $array = array("status" => false);
    }
    echo json_encode($array, JSON_PRETTY_PRINT);
}
?>