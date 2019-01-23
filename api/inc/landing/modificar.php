<?php
$landing = new Clases\Landing();
$imagenes = new Clases\Imagenes();
$zebra = new Clases\Zebra_Image();
$funciones = new Clases\PublicFunction();

$cod = $funciones->antihack_mysqli(isset($_GET["cod"]) ? $_GET["cod"] : '');
$borrarImg = $funciones->antihack_mysqli(isset($_GET["borrarImg"]) ? $_GET["borrarImg"] : '');

$landing->set("cod", $cod);
$landingInd = $landing->view();
$imagenes->set("cod", $landingInd["cod"]);
$imagenes->set("link", "landing&accion=modificar");

$categorias = new Clases\Categorias();
$data = $categorias->list(array("area = 'landing'"));

if ($borrarImg != '') {
    $imagenes->set("id", $borrarImg);
    $imagenes->delete();
    $funciones->headerMove(URL . "/index.php?op=landing&cod=$cod");
}

if (isset($_POST["agregar"])) {
    $count = 0;
    $cod = $landingInd["cod"];
    //$landing->set("id", $id);
    $landing->set("cod", $cod);
    $landing->set("titulo", $funciones->antihack_mysqli(isset($_POST["titulo"]) ? $_POST["titulo"] : $landingInd["titulo"]));
    $landing->set("categoria", $funciones->antihack_mysqli(isset($_POST["categoria"]) ? $_POST["categoria"] : $landingInd["categoria"]));
    $landing->set("desarrollo", $funciones->antihack_mysqli(isset($_POST["desarrollo"]) ? $_POST["desarrollo"] : $landingInd["desarrollo"]));
    $landing->set("fecha", $funciones->antihack_mysqli(isset($_POST["fecha"]) ? $_POST["fecha"] : $landingInd["fecha"]));
    $landing->set("description", trim($funciones->antihack_mysqli(isset($_POST["description"]) ? $_POST["description"] : $landingInd["description"])));
    $landing->set("keywords", $funciones->antihack_mysqli(isset($_POST["keywords"]) ? $_POST["keywords"] : $landingInd["keywords"]));

    foreach ($_FILES['files']['name'] as $f => $name) {
        $imgInicio = $_FILES["files"]["tmp_name"][$f];
        $tucadena = $_FILES["files"]["name"][$f];
        $partes = explode(".", $tucadena);
        $dom = (count($partes) - 1);
        $dominio = $partes[$dom];
        $prefijo = substr(md5(uniqid(rand())), 0, 10);
        if ($dominio != '') {
            $destinoFinal = "../assets/archivos/" . $prefijo . "." . $dominio;
            move_uploaded_file($imgInicio, $destinoFinal);
            chmod($destinoFinal, 0777);
            $destinoRecortado = "../assets/archivos/recortadas/a_" . $prefijo . "." . $dominio;

            $zebra->source_path = $destinoFinal;
            $zebra->target_path = $destinoRecortado;
            $zebra->jpeg_quality = 80;
            $zebra->preserve_aspect_ratio = true;
            $zebra->enlarge_smaller_images = true;
            $zebra->preserve_time = true;

            if ($zebra->resize(800, 700, ZEBRA_IMAGE_NOT_BOXED)) {
                unlink($destinoFinal);
            }

            $imagenes->set("cod", $cod);
            $imagenes->set("ruta", str_replace("../", "", $destinoRecortado));
            $imagenes->add();
        }

        $count++;
    }
    if ($landing->edit()){
        $array = array("status" => true);
    }else{
        $array = array("status" => false);
    }
    echo json_encode($array,JSON_PRETTY_PRINT);
}
?>