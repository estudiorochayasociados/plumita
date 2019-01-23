<?php
$imagenes  = new Clases\Imagenes();
$zebra     = new Clases\Zebra_Image();
$banners = new Clases\Banner();
$funciones = new Clases\PublicFunction();

$cod       = $funciones->antihack_mysqli(isset($_GET["cod"]) ? $_GET["cod"] : '');
$borrarImg = $funciones->antihack_mysqli(isset($_GET["borrarImg"]) ? $_GET["borrarImg"] : '');

$banners->set("cod", $cod);
$banner = $banners->view();

$categorias = new Clases\Categorias();
$data = $categorias->list(array("area = 'banners'"));

$imagenes->set("cod", $banner["cod"]);
$imagenes->set("link", "banners&accion=modificar");

if ($borrarImg != '') {
    $imagenes->set("id", $borrarImg);
    $imagenes->delete();
    $funciones->headerMove(URL . "/index.php?op=banners&accion=modificar&cod=$cod");
}

if (isset($_POST["agregar"])) {
    $count = 0;
    $cod   = $banner["cod"];
    //$banners->set("id", $id);
    $banners->set("cod", $cod);
    $banners->set("nombre", $funciones->antihack_mysqli(isset($_POST["nombre"]) ? $_POST["nombre"] : $banner["nombre"]));
    $banners->set("categoria", $funciones->antihack_mysqli(isset($_POST["categoria"]) ? $_POST["categoria"] : $banner["categoria"]));
    $banners->set("link", $funciones->antihack_mysqli(isset($_POST["link"]) ? $_POST["link"] : $banner["link"]));

    //foreach ($_FILES['files']['name'] as $f => $name) {
    //    $imgInicio = $_FILES["files"]["tmp_name"][$f];
    //    $tucadena  = $_FILES["files"]["name"][$f];
    //    $partes    = explode(".", $tucadena);
    //    $dom       = (count($partes) - 1);
    //    $dominio   = $partes[$dom];
    //    $prefijo   = substr(md5(uniqid(rand())), 0, 10);
    //    if ($dominio != '') {
    //        $destinoFinal     = "../assets/archivos/" . $prefijo . "." . $dominio;
    //        move_uploaded_file($imgInicio, $destinoFinal);
    //        chmod($destinoFinal, 0777);
    //        $destinoRecortado = "../assets/archivos/banner/" . $prefijo . "." . $dominio;
//
    //        $zebra->source_path = $destinoFinal;
    //        $zebra->target_path = $destinoRecortado;
    //        $zebra->jpeg_quality = 80;
    //        $zebra->preserve_aspect_ratio = true;
    //        $zebra->enlarge_smaller_images = true;
    //        $zebra->preserve_time = true;
//
    //        if ($zebra->resize(800, 700, ZEBRA_IMAGE_NOT_BOXED)) {
    //            unlink($destinoFinal);
    //        }
//
    //        $imagenes->set("cod", $cod);
    //        $imagenes->set("ruta", str_replace("../", "", $destinoRecortado));
    //        $imagenes->add();
    //    }
//
    //    $count++;
    //}

    if ($banners->edit()) {
        $array = array("status" => true);
    } else {
        $array = array("status" => false);
    }
echo json_encode($array, JSON_PRETTY_PRINT);
}
?>