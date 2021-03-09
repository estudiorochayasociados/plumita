<?php
$categoria = new Clases\Categorias();
$imagen = new Clases\Imagenes();
$zebra = new Clases\Zebra_Image();
$cod = substr(md5(uniqid(rand())), 0, 10);

if (isset($_POST["agregar"])) {
    $count = 0;
    $categoria->set("cod", $funciones->antihack_mysqli(isset($_POST["cod"]) ? $_POST["cod"] : ''));
    $categoria->set("titulo", $funciones->antihack_mysqli(isset($_POST["titulo"]) ? $_POST["titulo"] : ''));
    $categoria->set("area", $funciones->antihack_mysqli(isset($_POST["area"]) ? $_POST["area"] : ''));
    $categoria->set("descripcion", $funciones->antihack_mysqli(isset($_POST["descripcion"]) ? $_POST["descripcion"] : ''));

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
    $categoria->add();
    $funciones->headerMove(URLADMIN . "/index.php?op=categorias");
}
?>

<div class="col-md-12">
    <h4>Categorías</h4>
    <hr/>
    <form method="post" class="row" enctype="multipart/form-data">
        <label class="col-md-4">Código:<br/>
            <input type="text" name="cod" value="<?= $cod ?>" required>
        </label>
        <label class="col-md-4">Título:<br/>
            <input type="text" name="titulo" required>
        </label>
        <label class="col-md-4">Área:<br/>
            <select name="area" required>
                <option value="" disabled selected>-- categorías --</option>
                <option value="sliders">Sliders</option>
                <option value="banners">Banners</option>
                <option value="novedades">Novedades</option>
                <option value="portfolio">Portfolio</option>
                <option value="servicios">Servicios</option>
                <option value="galerias">Galerias</option>
                <option value="productos">Productos</option>
                <option value="landing">Landing</option>
                <option value="videos">Videos</option>
            </select>
        </label>
        <label class="col-md-12">Descripción:<br/>
            <textarea class="form-control" name="descripcion"></textarea>
        </label>
        <div class="clearfix"></div>
        <label class="col-md-7">Imagen:<br/>
            <input type="file" id="file" name="files[]" accept="image/*"/>
        </label>
        <br/>
        <div class="col-md-12">
            <input type="submit" class="btn btn-primary" name="agregar" value="Crear Categoría"/>
        </div>

    </form>
</div>
