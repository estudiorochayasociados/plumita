<?php
$sliders = new Clases\Sliders();
$imagenes= new Clases\Imagenes();
$zebra   = new Clases\Zebra_Image();
$funciones = new Clases\PublicFunction();

$cod     = $funciones->antihack_mysqli(isset($_GET["cod"]) ? $_GET["cod"] : '');
$borrarImg = $funciones->antihack_mysqli(isset($_GET["borrarImg"]) ? $_GET["borrarImg"] : '');

$sliders->set("cod", $cod);
$slide = $sliders->view();
$imagenes->set("cod", $slide["cod"]);
$imagenes->set("link", "sliders&accion=modificar");


$categorias = new Clases\Categorias();
$data = $categorias->list(array("area = 'sliders'"));

if ($borrarImg != '') {
    $imagenes->set("id", $borrarImg);
    $imagenes->delete();
    $funciones->headerMove(URL . "/index.php?op=sliders&accion=modificar&cod=$cod");
}

if (isset($_POST["agregar"])) {
    $count = 0;
    $cod = $slide["cod"];
    $sliders->set("cod", $cod);
    $sliders->set("titulo", $funciones->antihack_mysqli(isset($_POST["titulo"]) ? $_POST["titulo"] : $slide["titulo"]));
    $sliders->set("subtitulo", $funciones->antihack_mysqli(isset($_POST["subtitulo"]) ? $_POST["subtitulo"] : $slide["subtitulo"]));
    $sliders->set("categoria", $funciones->antihack_mysqli(isset($_POST["categoria"]) ? $_POST["categoria"] : $slide["categoria"]));
    $sliders->set("link", $funciones->antihack_mysqli(isset($_POST["link"]) ? $_POST["link"] : $slide["link"]));
    $sliders->set("fecha", $funciones->antihack_mysqli(isset($_POST["fecha"]) ? $_POST["fecha"] : $slide["fecha"]));

 
    //foreach ($_FILES['files']['name'] as $f => $name) {
    //    $imgInicio = $_FILES["files"]["tmp_name"][0];
    //    $tucadena  = $name;
    //    $partes    = explode('.', $tucadena);
    //    $dom       = (count($partes) - 1);
    //    $dominio   = $partes[$dom];
    //    $prefijo   = substr(md5(uniqid(rand())), 0, 10);
    //    if ($dominio != '') {
    //        $destinoFinal     = "../assets/archivos/" . $prefijo . "." . $dominio;
    //        move_uploaded_file($imgInicio, $destinoFinal);
    //        chmod($destinoFinal, 0777);
    //        $destinoRecortado = "../assets/archivos/recortadas/a_" . $prefijo . "." . $dominio;
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
    //}
     

    $sliders->edit();
    echo json_encode($sliders,JSON_PRETTY_PRINT);

    //$funciones->headerMove(URL . "/index.php?op=sliders");
}
?>