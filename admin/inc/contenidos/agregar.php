<?php
$contenidos = new Clases\Contenidos();
$imagenes = new Clases\Imagenes();
$zebra = new Clases\Zebra_Image();

if (isset($_POST["agregar"])) {
    $count = 0;
    $cod = $cod = substr(md5(uniqid(rand())), 0, 10);
    $contenidos->set("cod", $cod);
    $contenidos->set("titulo", $funciones->antihack_mysqli(isset($_POST["titulo"]) ? $_POST["titulo"] : ''));
    $contenidos->set("subtitulo", $funciones->antihack_mysqli(isset($_POST["subtitulo"]) ? $_POST["subtitulo"] : ''));
    $contenidos->set("contenido", $funciones->antihack_mysqli(isset($_POST["contenido"]) ? $_POST["contenido"] : ''));

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
            $destinoRecortado = "../assets/archivos/recortadas/" . $prefijo . "." . $dominio;

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

    $contenidos->add();
    $funciones->headerMove(URLADMIN . "/index.php?op=contenidos");
}
?>
<div class="mt-20">
    <div class="col-lg-12">
        <h4>Subir Contenidos</h4>
        <hr/>
    </div>
    <form method="post" enctype="multipart/form-data">
        <label class="col-lg-12">Título:
            <br/>
            <input type="text" name="titulo" value=""  required/>
        </label>
        <label class="col-lg-12">Subtítulo:
            <br/>
            <input type="text" name="subtitulo" value="" />
        </label>
        <label class="col-lg-12" >Desarrollo:
            <br/>
            <textarea name="contenido" class="ckeditorTextarea" required></textarea>
        </label>
        <br/>
        <label class="col-md-7">Imágenes:<br/>
            <input type="file" id="file" name="files[]"  multiple="multiple"  accept="image/*"/>
        </label>
        <div class="clearfix"></div>
        <div class="col-lg-12">
            <input type="submit" class="btn btn-primary" name="agregar" value="Subir Contenido" />
        </div>
    </form>
</div>
