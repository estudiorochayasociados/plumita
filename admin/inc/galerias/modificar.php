<?php
$galerias = new Clases\Galerias();
$imagenes = new Clases\Imagenes();
$zebra = new Clases\Zebra_Image();

$categorias = new Clases\Categorias();
$data = $categorias->list(array("area = 'galerias'"), '', '');

$cod = $funciones->antihack_mysqli(isset($_GET["cod"]) ? $_GET["cod"] : '');
$borrarImg = $funciones->antihack_mysqli(isset($_GET["borrarImg"]) ? $_GET["borrarImg"] : '');

$galerias->set("cod", $cod);
$galeria = $galerias->view();

$imagenes->set("cod", $galeria['data']["cod"]);
$imagenes->set("link", "galerias&accion=modificar");

if ($borrarImg != '') {
    $imagenes->set("id", $borrarImg);
    $imagenes->delete();
    $funciones->headerMove(URLADMIN . "/index.php?op=galerias&accion=modificar&cod=$cod");
}

if (isset($_POST["agregar"])) {
    $count = 0;
    $cod = $galeria['data']["cod"];
    $galerias->set("cod", $cod);
    $galerias->set("titulo", isset($_POST["titulo"]) ? $funciones->antihack_mysqli($_POST["titulo"]) : '');
    $galerias->set("categoria", isset($_POST["categoria"]) ? $funciones->antihack_mysqli($_POST["categoria"]) : '');
    $galerias->set("desarrollo", isset($_POST["desarrollo"]) ? $funciones->antihack_mysqli($_POST["desarrollo"]) : '');
    $galerias->set("fecha", !empty($_POST["fecha"]) ? $funciones->antihack_mysqli($_POST["fecha"]) :  date("Y-m-d"));
    $galerias->set("description", isset($_POST["description"]) ? $funciones->antihack_mysqli($_POST["description"]) : '');
    $galerias->set("keywords", isset($_POST["keywords"]) ? $funciones->antihack_mysqli($_POST["keywords"]) : '');

    if (isset($_FILES['files'])) {
        $imagenes->resizeImages($cod, $_FILES['files'], "../assets/archivos", "../assets/archivos/recortadas");
    }

    $galerias->edit();
    $funciones->headerMove(URLADMIN . "/index.php?op=galerias");
}
?>

<div class="col-md-12 ">
    <h4>Galerias</h4>
    <hr />
    <form method="post" class="row" enctype="multipart/form-data">
        <label class="col-md-4">Título:<br />
            <input type="text" value="<?= $galeria['data']["titulo"] ?>" name="titulo">
        </label>
        <label class="col-md-4">Categoría:<br />
            <select name="categoria">
                <?php
                foreach ($data as $categoria) {
                    if ($galeria['data']["categoria"] == $categoria["cod"]) {
                        echo "<option value='" . $categoria['data']["cod"] . "' selected>" . $categoria['data']["titulo"] . "</option>";
                    } else {
                        echo "<option value='" . $categoria['data']["cod"] . "'>" . $categoria['data']["titulo"] . "</option>";
                    }
                }
                ?>
            </select>
        </label>
        <label class="col-md-4">Fecha:<br />
            <input type="date" name="fecha" value="<?= $galeria['data']["fecha"] ?>">
        </label>

        <div class="clearfix"></div>
        <label class="col-md-12">Desarrollo:<br />
            <textarea name="desarrollo" class="ckeditorTextarea"><?= $galeria['data']["desarrollo"]; ?></textarea>
        </label>
        <div class="clearfix"></div>
        <label class="col-md-12">Palabras claves dividas por ,<br />
            <input type="text" name="keywords" value="<?= $galeria['data']["keywords"] ?>">
        </label>
        <label class="col-md-12">Descripción breve<br />
            <textarea name="description"><?= $galeria['data']["description"] ?></textarea>
        </label>
        <br />
        <div class="col-md-12">
            <div class="row">
                <?php
                if (!empty($galeria['images'])) {
                    foreach ($galeria['images'] as $img) {
                ?>
                        <div class='col-md-2 mb-20 mt-20'>
                            <div style="height:200px;background:url(<?= '../' . $img['ruta']; ?>) no-repeat center center/contain;">
                            </div>
                            <div class="row">
                                <div class="col-md-7">
                                    <a href="<?= URLADMIN . '/index.php?op=galerias&accion=modificar&cod=' . $img['cod'] . '&borrarImg=' . $img['id'] ?>" class="btn btn-sm btn-block btn-danger">
                                        BORRAR IMAGEN
                                    </a>
                                </div>
                                <div class="col-md-5 text-right">
                                    <select onchange='$(location).attr("href", "<?= CANONICAL ?>&idImg=<?= $img["id"] ?>&ordenImg="+$(this).val())'>
                                        <?php
                                        for ($i = 0; $i <= count($galeria['images']); $i++) {
                                            if ($img["orden"] == $i) {
                                                echo "<option value='$i' selected>$i</option>";
                                            } else {
                                                echo "<option value='$i'>$i</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                    <i>orden</i>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                <?php
                    }
                }
                ?>
            </div>
        </div>
        <div class="clearfix">
        </div>
        <label class="col-md-12">Imágenes:<br />
            <input type="file" id="file" name="files[]" multiple="multiple" accept="image/*" />
        </label>
        <div class="clearfix"></div>
        <br />
        <div class="col-md-12">
            <input type="submit" class="btn btn-primary" name="agregar" value="Modificar Galeria" />
        </div>
    </form>
</div>