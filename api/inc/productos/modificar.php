<?php
$productos = new Clases\Productos();
$imagenes  = new Clases\Imagenes();
$zebra     = new Clases\Zebra_Image();
$funciones = new Clases\PublicFunction();

$cod       = $funciones->antihack_mysqli(isset($_GET["cod"]) ? $_GET["cod"] : '');
$borrarImg = $funciones->antihack_mysqli(isset($_GET["borrarImg"]) ? $_GET["borrarImg"] : '');

$productos->set("cod", $cod);
$producto = $productos->view();
$imagenes->set("cod", $producto["cod"]);
$imagenes->set("link", "productos&accion=modificar");

$categorias = new Clases\Categorias();
$data = $categorias->list(array("area = 'productos'"));

if ($borrarImg != '') {
    $imagenes->set("id", $borrarImg);
    $imagenes->delete();
        $funciones->headerMove(URL . "/index.php?op=productos&accion=modificar&cod=$cod");
}

if (isset($_POST["agregar"])) {
    $count = 0;
    $cod   = $producto["cod"];
    $productos->set("id", $producto["id"]);
    $productos->set("cod", $producto["cod"]);
    $productos->set("titulo", $funciones->antihack_mysqli(isset($_POST["titulo"]) ? $_POST["titulo"] : $producto["titulo"]));
    $productos->set("cod_producto", $funciones->antihack_mysqli(isset($_POST["cod_producto"]) ? $_POST["cod_producto"] : $producto["cod_producto"]));
    $productos->set("precio", $funciones->antihack_mysqli(isset($_POST["precio"]) ? $_POST["precio"] : $producto["precio"]));
    $productos->set("precio_mayorista", $funciones->antihack_mysqli(isset($_POST["precio_mayorista"]) ? $_POST["precio_mayorista"] : $producto["precio_mayorista"]));
    $productos->set("peso", $funciones->antihack_mysqli(isset($_POST["peso"]) ? $_POST["peso"] : $producto["peso"]));
    $productos->set("precio_descuento", $funciones->antihack_mysqli(isset($_POST["precio_descuento"]) ? $_POST["precio_descuento"] : $producto["precio_descuento"]));
    $productos->set("stock", $funciones->antihack_mysqli(isset($_POST["stock"]) ? $_POST["stock"] : $producto["stock"]));
    $productos->set("desarrollo", $funciones->antihack_mysqli(isset($_POST["desarrollo"]) ? $_POST["desarrollo"] : $producto["desarrollo"]));
    $productos->set("categoria", $funciones->antihack_mysqli(isset($_POST["categoria"]) ? $_POST["categoria"] : $producto["categoria"]));
    $productos->set("subcategoria", $funciones->antihack_mysqli(isset($_POST["subcategoria"]) ? $_POST["subcategoria"] : $producto["subcategoria"]));
    $productos->set("keywords", $funciones->antihack_mysqli(isset($_POST["keywords"]) ? $_POST["keywords"] : $producto["keywords"]));
    $productos->set("description", $funciones->antihack_mysqli(isset($_POST["description"]) ? $_POST["description"] : $producto["description"]));
    $productos->set("fecha", $funciones->antihack_mysqli(isset($_POST["fecha"]) ? $_POST["fecha"] : $producto["fecha"]));
    $productos->set("meli", $funciones->antihack_mysqli(isset($_POST["meli"]) ? $_POST["meli"] : $producto["meli"]));
    $productos->set("url", $funciones->antihack_mysqli(isset($_POST["url"]) ? $_POST["url"] : $producto["url"]));


   // foreach ($_FILES['files']['name'] as $f => $name)
    //$img_=isset($_POST["img"]) ? $_POST["img"] :'';
    //if ($img_!=''){
    //    $imgstring_=explode( ',', $img_ );
    //    //$imgInicio = $_FILES["files"]["tmp_name"][$f];
    //    $tucadena  = substr(md5(uniqid(rand())), 0, 4);
    //    $partes    = explode(".", $tucadena);
    //    $dom       = (count($partes) - 1);
    //    $dominio   = $partes[$dom];
    //    $prefijo   = substr(md5(uniqid(rand())), 0, 10);
    //    if ($dominio != '') {
    //        $destinoFinal = "../assets/archivos/" . $prefijo . "." . $dominio;
    //        move_uploaded_file(base64_decode( $imgstring_[1]), $destinoFinal);
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
    //    $count++;
    //}
    if ($productos->edit()){
        $array = array("status" => true);
    }else{
        $array = array("status" => false);
    }
    echo json_encode($array,JSON_PRETTY_PRINT);
}
?>