<?php
$novedades = new Clases\Novedades();
$imagenes = new Clases\Imagenes();
$zebra = new Clases\Zebra_Image();
$funciones=new Clases\PublicFunction();

$cod = $funciones->antihack_mysqli(isset($_GET["cod"]) ? $_GET["cod"] : '');
$borrarImg = $funciones->antihack_mysqli(isset($_GET["borrarImg"]) ? $_GET["borrarImg"] : '');

$novedades->set("cod", $cod);
$novedad = $novedades->view();

$categorias = new Clases\Categorias();
$data = $categorias->list(array("area = 'novedades'"));
$imagenes->set("cod", $novedad["cod"]);
$imagenes->set("link", "novedades&accion=modificar");

if ($borrarImg != '') {
    $imagenes->set("id", $borrarImg);
    $imagenes->delete();
    $funciones->headerMove(URL . "/index.php?op=novedades&accion=modificar&cod=$cod");
}

if (isset($_POST["agregar"])) {
    $count = 0;
    $cod = $novedad["cod"];
    $novedades->set("cod", $cod);
    $novedades->set("titulo", $funciones->antihack_mysqli(isset($_POST["titulo"]) ? $_POST["titulo"] : $novedad["titulo"]));
    $novedades->set("categoria", $funciones->antihack_mysqli(isset($_POST["categoria"]) ? $_POST["categoria"] : $novedad["categoria"]));
    $novedades->set("desarrollo", $funciones->antihack_mysqli(isset($_POST["desarrollo"]) ? $_POST["desarrollo"] : $novedad["desarrollo"]));
    $novedades->set("fecha", $funciones->antihack_mysqli(isset($_POST["fecha"]) ? $_POST["fecha"] : $novedad["fecha"]));
    $novedades->set("description", $funciones->antihack_mysqli(isset($_POST["description"]) ? $_POST["description"] : $novedad["description"]));
    $novedades->set("keywords", $funciones->antihack_mysqli(isset($_POST["keywords"]) ? $_POST["keywords"] : $novedad["keywords"]));

    //foreach ($_FILES['files']['name'] as $f => $name) {
    //    $imgInicio = $_FILES["files"]["tmp_name"][$f];
    //    $tucadena = $_FILES["files"]["name"][$f];
    //    $partes = explode(".", $tucadena);
    //    $dom = (count($partes) - 1);
    //    $dominio = $partes[$dom];
    //    $prefijo = substr(md5(uniqid(rand())), 0, 10);
    //    if ($dominio != '') {
    //        $destinoFinal = "../assets/archivos/" . $prefijo . "." . $dominio;
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
    //    $count++;
    //}

    if ($novedades->edit()){
        $array = array("status" => true);
    }else{
        $array = array("status" => false);
    }
echo json_encode($array,JSON_PRETTY_PRINT);
}
?>