<?php
$productos = new Clases\Productos();
$imagenes = new Clases\Imagenes();
$zebra = new Clases\Zebra_Image();
$funciones = new Clases\PublicFunction();
$categorias = new Clases\Categorias();
$data = $categorias->list(array("area = 'productos'"));

if (isset($_POST["agregar"])) {
    $count = 0;
    $cod = substr(md5(uniqid(rand())), 0, 10);

    $productos->set("cod", $funciones->antihack_mysqli(isset($cod) ? $cod : ''));
    $productos->set("titulo", $funciones->antihack_mysqli(isset($_POST["titulo"]) ? $_POST["titulo"] : ''));
    $productos->set("cod_producto", $funciones->antihack_mysqli(isset($_POST["cod_producto"]) ? $_POST["cod_producto"] : ''));
    $productos->set("precio", $funciones->antihack_mysqli(isset($_POST["precio"]) ? $_POST["precio"] : ''));
    $productos->set("precio_mayorista", $funciones->antihack_mysqli(isset($_POST["precio_mayorista"]) ? $_POST["precio_mayorista"] : ''));
    $productos->set("peso", $funciones->antihack_mysqli(isset($_POST["peso"]) ? $_POST["peso"] : 0));
    $productos->set("precio_descuento", $funciones->antihack_mysqli(isset($_POST["precio_descuento"]) ? $_POST["precio_descuento"] : ''));
    $productos->set("stock", $funciones->antihack_mysqli(isset($_POST["stock"]) ? $_POST["stock"] : ''));
    $productos->set("desarrollo", $funciones->antihack_mysqli(isset($_POST["desarrollo"]) ? $_POST["desarrollo"] : ''));
    $productos->set("categoria", $funciones->antihack_mysqli(isset($_POST["categoria"]) ? $_POST["categoria"] : ''));
    $productos->set("subcategoria", $funciones->antihack_mysqli(isset($_POST["subcategoria"]) ? $_POST["subcategoria"] : ''));
    $productos->set("keywords", $funciones->antihack_mysqli(isset($_POST["keywords"]) ? $_POST["keywords"] : ''));
    $productos->set("description", $funciones->antihack_mysqli(isset($_POST["description"]) ? $_POST["description"] : ''));
    $productos->set("fecha", $funciones->antihack_mysqli(isset($_POST["fecha"]) ? $_POST["fecha"] : date("Y-m-d")));
    $productos->set("meli", $funciones->antihack_mysqli(isset($_POST["meli"]) ? $_POST["meli"] : ''));
    $productos->set("url", $funciones->antihack_mysqli(isset($_POST["url"]) ? $_POST["url"] : ''));

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

    echo json_encode($productos, JSON_PRETTY_PRINT);

    $productos->add();
    //$funciones->headerMove(URL . "/index.php?op=productos");
}
?>