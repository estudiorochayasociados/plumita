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
    $categorias->edit();
    echo json_encode($categorias,JSON_PRETTY_PRINT);

    //$funciones->headerMove(URL . "/index.php?op=categorias");
}
?>