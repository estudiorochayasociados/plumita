<?php
$categorias = new Clases\Categorias();
$imagenes = new Clases\Imagenes();
$funciones = new Clases\PublicFunction();
$zebra = new Clases\Zebra_Image();

if (isset($_POST["agregar"])) {
    $count = 0;
    $cod = substr(md5(uniqid(rand())), 0, 10);
    $categorias->set("cod", $cod);
    $categorias->set("titulo", $funciones->antihack_mysqli(isset($_POST["titulo"]) ? $_POST["titulo"] : ''));
    $categorias->set("area", $funciones->antihack_mysqli(isset($_POST["area"]) ? $_POST["area"] : ''));

    if ($categorias->add()) {
        $array = array("status" => true);
    } else {
        $array = array("status" => false);
    }
echo json_encode($array, JSON_PRETTY_PRINT);
}
?>