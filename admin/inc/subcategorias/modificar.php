<?php
$categoria = new Clases\Categorias();
$subcategoria = new Clases\Subcategorias();
$imagen = new Clases\Imagenes();
$zebra = new Clases\Zebra_Image();
$funciones = new Clases\PublicFunction();

$cod = $funciones->antihack_mysqli(isset($_GET["cod"]) ? $_GET["cod"] : '');

$subcategoria->set("cod", $cod);

$dataSubcategoria = $subcategoria->view();
$categoria->set("cod", $dataSubcategoria["data"]["categoria"]);
$dataCategoria = $categoria->view();
$categorias = $categoria->list('', 'area ASC', '');


$borrarImg = $funciones->antihack_mysqli(isset($_GET["borrarImg"]) ? $_GET["borrarImg"] : '');
if ($borrarImg != '') {
    $imagen->set("id", $borrarImg);
    $imagen->delete();
    $funciones->headerMove(URLADMIN . "/index.php?op=subcategorias&accion=modificar&cod=$cod");
}


if (isset($_POST["agregar"])) {
    $count = 0;
    $codPost = $funciones->antihack_mysqli(isset($_POST["cod"]) ? $_POST["cod"] : '');
    $subcategoria->set("cod", $codPost);
    $subcategoria->set("titulo", $funciones->antihack_mysqli(isset($_POST["titulo"]) ? $_POST["titulo"] : ''));
    $subcategoria->set("categoria", $funciones->antihack_mysqli(isset($_POST["categoria"]) ? $_POST["categoria"] : ''));
    $subcategoria->set("id", $dataSubcategoria['data']['id']);

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

                $imagen->set("cod", $codPost);
                $imagen->set("ruta", str_replace("../", "", $destinoRecortado));
                $imagen->add();
            }
            $count++;
        }
    }
    if ($subcategoria->edit()) {
        $imagen->set("cod", $codPost);
        $imagen->editAllCod($cod);
        $funciones->headerMove(URLADMIN . "/index.php?op=categorias");
    }
}
?>
<div class="col-md-12">
    <h4>
        Subcategorías
    </h4>
    <hr/>
    <form method="post" class="row" enctype="multipart/form-data">
        <label class="col-md-4">Código:<br/>
            <input type="text" name="cod" value="<?= $cod ?>" required>
        </label>
        <label class="col-md-4">
            Título:<br/>
            <input type="text" name="titulo" value="<?= $dataSubcategoria["data"]["titulo"] ?>" required>
        </label>
        <label class="col-md-4">
            Categoria:<br/>
            <select name="categoria" required>
                <option value="<?= $dataCategoria["data"]["cod"] ?>" selected>
                    <?= mb_strtoupper($dataCategoria["data"]["area"] . " -> " . $dataCategoria["data"]["titulo"]); ?>
                </option>
                <?php
                foreach ($categorias as $categoria_) {
                    echo "<option value='" . $categoria_["data"]["cod"] . "'>" . mb_strtoupper($categoria_["data"]["area"]) . " -> " . mb_strtoupper($categoria_["data"]["titulo"]) . "</option>";
                }
                ?>
            </select>
        </label>
        <div class="clearfix">
        </div>
        <?php
        if (!empty($dataSubcategoria['image'])) {
            ?>
            <div class='col-md-2 mb-20 mt-20'>
                <div style="height:200px;background:url(<?= '../' . $dataSubcategoria['image']['ruta']; ?>) no-repeat center center/contain;">
                </div>
                <a href="<?= URLADMIN . '/index.php?op=subcategorias&accion=modificar&cod=' . $dataSubcategoria['data']['cod'] . '&borrarImg=' . $dataSubcategoria['image']['id'] ?>"
                   class="btn btn-sm pull-left btn-danger">
                    BORRAR IMAGEN
                </a>
                <?php
                if ($dataSubcategoria['image']["orden"] == 0) {
                    ?>
                    <a href="<?= URLADMIN . '/index.php?op=subcategorias&accion=modificar&cod=' . $dataSubcategoria['data']['cod'] . '&ordenImg=' . $dataSubcategoria['image']['cod'] ?>"
                       class="btn btn-sm pull-right btn-warning">
                        <i class="fa fa-star"></i>
                    </a>
                    <?php
                } else {
                    ?>
                    <a href="#" class="btn btn-sm pull-right btn-success">
                        <i class="fa fa-star"></i>
                    </a>
                    <?php
                }
                ?>
                <div class="clearfix"></div>
            </div>
            <?php
        } else {
            ?>
            <label class="col-md-7">Imagen:<br/>
                <input type="file" id="file" name="files[]" accept="image/*"/>
            </label>
            <?php
        }
        ?>
        <div class="col-md-12">
            <input type="submit" class="btn btn-primary" name="agregar" value="Modificar Subcategoría"/>
        </div>
    </form>
</div>
