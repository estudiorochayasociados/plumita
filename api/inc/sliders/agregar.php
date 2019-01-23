<?php
$sliders  = new Clases\Sliders();
$imagenes = new Clases\Imagenes();
$zebra    = new Clases\Zebra_Image();
$funciones = new Clases\PublicFunction();
$categorias = new Clases\Categorias();
$data = $categorias->list(array("area = 'sliders'"));

if (isset($_POST["agregar"])) {
    $cod = substr(md5(uniqid(rand())), 0, 10);

    $sliders->set("cod", $cod);
    $sliders->set("titulo", $funciones->antihack_mysqli(isset($_POST["titulo"]) ? $_POST["titulo"] : ''));
    $sliders->set("subtitulo", $funciones->antihack_mysqli(isset($_POST["subtitulo"]) ? $_POST["subtitulo"] : ''));
    $sliders->set("categoria", $funciones->antihack_mysqli(isset($_POST["categoria"]) ? $_POST["categoria"] : ''));
    $sliders->set("link", $funciones->antihack_mysqli(isset($_POST["link"]) ? $_POST["link"] : ''));
    $sliders->set("fecha", $funciones->antihack_mysqli(isset($_POST["fecha"]) ? $_POST["fecha"] : date("Y-m-d")));

    //foreach ($_FILES['files']['name'] as $f => $name) {
    //    $imgInicio = $_FILES["files"]["tmp_name"][0];
    //    $tucadena  = $name;
    //    $partes    = explode(".", $tucadena);
    //    $dom       = (count($partes) - 1);
    //    $dominio   = $partes[$dom];
    //    $prefijo   = substr(md5(uniqid(rand())), 0, 10);
    //    if ($dominio != '') {
    //        $destinoFinal = "../assets/archivos/" . $prefijo . "." . $dominio;
    //        move_uploaded_file($imgInicio, $destinoFinal);
    //        chmod($destinoFinal, 0777);
    //        $destinoRecortado = "../assets/archivos/recortadas/a_" . $prefijo . "." . $dominio;
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
    //}
    $sliders->add();
    echo json_encode($sliders,JSON_PRETTY_PRINT);

    //$funciones->headerMove(URL . "/index.php?op=sliders&accion=ver");
}
?>