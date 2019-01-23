<?php
$banners = new Clases\Banner();
$imagenes = new Clases\Imagenes();
$zebra = new Clases\Zebra_Image();
$categorias = new Clases\Categorias();
$funciones = new Clases\PublicFunction();

$data = $categorias->list(array("area = 'banners'"));

if (isset($_POST["agregar"])) {
    $count = 0;
    $cod = substr(md5(uniqid(rand())), 0, 10);

    $banners->set("cod", $cod);
    $banners->set("nombre", $funciones->antihack_mysqli(isset($_POST["nombre"]) ? $_POST["nombre"] : ''));
    $banners->set("categoria", $funciones->antihack_mysqli(isset($_POST["categoria"]) ? $_POST["categoria"] : ''));
    $banners->set("link", $funciones->antihack_mysqli(isset($_POST["link"]) ? $_POST["link"] : ''));

    //foreach ($_FILES['files']['name'] as $f => $name) {
    //    $imgInicio = $_FILES["files"]["tmp_name"][$f];
    //    $tucadena  = $_FILES["files"]["name"][$f];
    //    $partes    = explode(".", $tucadena);
    //    $dom       = (count($partes) - 1);
    //    $dominio   = $partes[$dom];
    //    $prefijo   = substr(md5(uniqid(rand())), 0, 10);
    //    if ($dominio != '') {
    //        $destinoFinal = "../assets/archivos/" . $prefijo . "." . $dominio;
    //        move_uploaded_file($imgInicio, $destinoFinal);
    //        chmod($destinoFinal, 0777);
    //        $destinoRecortado = "../assets/archivos/banner/" . $prefijo . "." . $dominio;
//
    //        $zebra->source_path            = $destinoFinal;
    //        $zebra->target_path            = $destinoRecortado;
    //        $zebra->jpeg_quality           = 80;
    //        $zebra->preserve_aspect_ratio  = true;
    //        $zebra->enlarge_smaller_images = true;
    //        $zebra->preserve_time          = true;
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

    if ($banners->add()) {
        $array = array("status" => true);
    } else {
        $array = array("status" => false);
    }
echo json_encode($array, JSON_PRETTY_PRINT);
}
?>