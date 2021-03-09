<?php
$categorias = new Clases\Categorias();
$imagenes = new Clases\Imagenes();
$zebra = new Clases\Zebra_Image();
$cod = $funciones->antihack_mysqli(isset($_GET["cod"]) ? $_GET["cod"] : '');
$categorias->set("cod", $cod);
$data = $categorias->view();

$cod = $funciones->antihack_mysqli(isset($_GET["cod"]) ? $_GET["cod"] : '');
$borrarImg = $funciones->antihack_mysqli(isset($_GET["borrarImg"]) ? $_GET["borrarImg"] : '');

if ($borrarImg != '') {
    $imagenes->set("id", $borrarImg);
    $imagenes->delete();
    $funciones->headerMove(URLADMIN . "/index.php?op=categorias&accion=modificar&cod=$cod");
}

if (isset($_POST["agregar"])) {
    $categorias->set("cod", $funciones->antihack_mysqli(isset($_POST["cod"]) ? $_POST["cod"] : ''));
    $categorias->set("titulo", $funciones->antihack_mysqli(isset($_POST["titulo"]) ? $_POST["titulo"] : ''));
    $categorias->set("area", $funciones->antihack_mysqli(isset($_POST["area"]) ? $_POST["area"] : ''));
    $categorias->set("descripcion", $funciones->antihack_mysqli(isset($_POST["descripcion"]) ? $_POST["descripcion"] : ''));
    $categorias->set("id", $data['data']['id']);
    $count = 0;
    if (isset($_FILES['files'])) {
        $imagenes->resizeImages($cod, $_FILES['files'], "../assets/archivos", "../assets/archivos/recortadas");
    }
    if ($categorias->edit()) {
        $imagenes->set("cod", $funciones->antihack_mysqli(isset($_POST["cod"]) ? $_POST["cod"] : ''));
        $imagenes->editAllCod($cod);
        $funciones->headerMove(URLADMIN . "/index.php?op=categorias");
    }
}
?>
<div class="col-md-12">
    <h4>Categorías</h4>
    <hr/>
    <form method="post" class="row" enctype="multipart/form-data">
        <label class="col-md-4">Código:<br/>
            <input type="text" value="<?= $data['data']["cod"] ?>" name="cod" required>
        </label>
        <label class="col-md-4">Título:<br/>
            <input type="text" value="<?= $data['data']["titulo"] ?>" name="titulo" required>
        </label>
        <label class="col-md-4">Área:<br/>
            <select name="area" required>
                <option value="<?= $data['data']["area"] ?>" selected><?= ucwords($data['data']["area"]) ?></option>
                <option>---------------</option>
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
            <textarea class="form-control" name="descripcion"><?= $data['data']["descripcion"] ?></textarea>
        </label>
        <div class="clearfix"></div>
        <br/>
        <?php
        if (!empty($data['image'])) {
            ?>
            <div class='col-md-2 mb-20 mt-20'>
                <div style="height:200px;background:url(<?= '../' . $data['image']['ruta']; ?>) no-repeat center center/contain;">
                </div>
                <a href="<?= URLADMIN . '/index.php?op=categorias&accion=modificar&cod=' . $data['data']['cod'] . '&borrarImg=' . $data['image']['id'] ?>"
                   class="btn btn-sm pull-left btn-danger">
                    BORRAR IMAGEN
                </a>
                <?php
                if ($data['image']["orden"] == 0) {
                    ?>
                    <a href="<?= URLADMIN . '/index.php?op=categorias&accion=modificar&cod=' . $data['data']['cod'] . '&ordenImg=' . $data['image']['cod'] ?>"
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
            <label class="col-md-7">Imágenes:<br/>
                <input type="file" id="file" name="files[]" accept="image/*"/>
            </label>
            <?php
        }
        ?>
        <div class="clearfix">
        </div>
        <br/>
        <div class="col-md-12">
            <input type="submit" class="btn btn-primary" name="agregar" value="Modificar Categoría"/>
        </div>
    </form>
</div>
