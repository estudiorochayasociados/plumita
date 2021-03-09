<?php
$categoria = new Clases\Categorias();
$imagen = new Clases\Imagenes();
$zebra = new Clases\Zebra_Image();
$subcategorias = new Clases\Subcategorias();
$funciones = new Clases\PublicFunction();
$categorias = $categoria->list("", "area ASC", "");
$cod = substr(md5(uniqid(rand())), 0, 10);

if (isset($_POST["agregar"])) {
    $count = 0;
    $subcategorias->set("cod", $funciones->antihack_mysqli(isset($_POST["cod"]) ? $_POST["cod"] : ''));
    $subcategorias->set("titulo", $funciones->antihack_mysqli(isset($_POST["titulo"]) ? $_POST["titulo"] : ''));
    $subcategorias->set("categoria", $funciones->antihack_mysqli(isset($_POST["categoria"]) ? $_POST["categoria"] : ''));

    if (isset($_FILES['files'])) {
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
                $zebra->png_compression = true;
                $zebra->enlarge_smaller_images = true;
                $zebra->preserve_time = true;

                if ($zebra->resize(800, 700, ZEBRA_IMAGE_NOT_BOXED, $background_color = -1)) {
                    unlink($destinoFinal);
                }

                $imagen->set("cod", $funciones->antihack_mysqli(isset($_POST["cod"]) ? $_POST["cod"] : ''));
                $imagen->set("ruta", str_replace("../", "", $destinoRecortado));
                $imagen->add();
            }
            $count++;
        }
    }
    $subcategorias->add();
    $funciones->headerMove(URLADMIN . "/index.php?op=categorias");
}
?>

<div class="col-md-12">
    <h4>
        Sub Categorías
    </h4>
    <hr/>
    <form method="post" class="row" enctype="multipart/form-data">
        <label class="col-md-4">Código:<br/>
            <input type="text" name="cod" value="<?= $cod ?>" required>
        </label>
        <label class="col-md-4">
            Título:<br/>
            <input type="text" name="titulo" required>
        </label>
        <label class="col-md-4">
            Categoria:<br/>
            <select name="categoria">
                <?php
                foreach ($categorias as $categoria_) {
                    echo "<option value='" . $categoria_["data"]["cod"] . "'>" . mb_strtoupper($categoria_["data"]["area"]) . " -> " . mb_strtoupper($categoria_["data"]["titulo"]) . "</option>";
                }
                ?>
            </select>
        </label>
        <div class="clearfix">
        </div>
        <label class="col-md-7">Imagen:<br/>
            <input type="file" id="file" name="files[]" accept="image/*"/>
        </label>
        <br/>
        <div class="col-md-12">
            <input type="submit" class="btn btn-primary" name="agregar" value="Crear Subcategoría"/>
        </div>
    </form>
</div>
