<?php
$sliders = new Clases\Sliders();
$imagenes = new Clases\Imagenes();
$zebra = new Clases\Zebra_Image();

$cod = $funciones->antihack_mysqli(isset($_GET["cod"]) ? $_GET["cod"] : '');
$borrarImg = $funciones->antihack_mysqli(isset($_GET["borrarImg"]) ? $_GET["borrarImg"] : '');

$sliders->set("cod", $cod);
$slide = $sliders->view();
$imagenes->set("cod", $slide['data']["cod"]);
$imagenes->set("link", "sliders&accion=modificar");

$categorias = new Clases\Categorias();
$data = $categorias->list(array("area = 'sliders'"), '', '');

if ($borrarImg != '') {
    $imagenes->set("id", $borrarImg);
    $imagenes->delete();
    $funciones->headerMove(URLADMIN . "/index.php?op=sliders&accion=modificar&cod=$cod");
}

if (isset($_POST["agregar"])) {
    $count = 0;
    $cod = $slide['data']["cod"];
    $sliders->set("cod", $cod);
    $sliders->set("titulo", $funciones->antihack_mysqli(isset($_POST["titulo"]) ? $_POST["titulo"] : ''));
    $sliders->set("tituloOn", $funciones->antihack_mysqli(isset($_POST["tituloOn"]) ? $_POST["tituloOn"] : ''));
    $sliders->set("subtitulo", $funciones->antihack_mysqli(isset($_POST["subtitulo"]) ? $_POST["subtitulo"] : ''));
    $sliders->set("subtituloOn", $funciones->antihack_mysqli(isset($_POST["subtituloOn"]) ? $_POST["subtituloOn"] : ''));
    $sliders->set("categoria", $funciones->antihack_mysqli(isset($_POST["categoria"]) ? $_POST["categoria"] : ''));
    $sliders->set("link", $funciones->antihack_mysqli(isset($_POST["link"]) ? $_POST["link"] : ''));
    $sliders->set("linkOn", $funciones->antihack_mysqli(isset($_POST["linkOn"]) ? $_POST["linkOn"] : ''));
    $sliders->set("fecha", $funciones->antihack_mysqli(isset($_POST["fecha"]) ? $_POST["fecha"] : date("Y-m-d")));


    if (isset($_FILES['files'])) {
        $imagenes->resizeImages($cod, $_FILES['files'], "../assets/archivos", "../assets/archivos/recortadas");
    }

    $sliders->edit();
    $funciones->headerMove(URLADMIN . "/index.php?op=sliders");
}
?>
<div class="col-md-12 ">
    <h4>
        Sliders
    </h4>
    <hr/>
    <form method="post" class="row" enctype="multipart/form-data">
        <label class="col-md-4">
            Título (mostrar <input type="checkbox" name="tituloOn" value="1" <?php if ($slide['data']['titulo_on']) {
                echo "checked";
            } ?>>):<br/>
            <input type="text" value="<?= $slide['data']["titulo"] ?>" name="titulo">
        </label>
        <label class="col-md-4">
            Subtitulo (mostrar <input type="checkbox" name="subtituloOn" id="chsub" value="1" <?php if ($slide['data']['subtitulo_on']) {
                echo "checked";
            } ?>>):<br/>
            <input type="text" id="sub" value="<?= $slide['data']["subtitulo"] ?>" name="subtitulo">
        </label>
        <label class="col-md-4">Categoría:<br/>
            <select name="categoria">
                <?php
                foreach ($data as $categoria) {
                    if ($slide['data']["categoria"] == $categoria['data']["cod"]) {
                        echo "<option value='" . $categoria['data']["cod"] . "' selected>" . $categoria['data']["titulo"] . "</option>";
                    } else {
                        echo "<option value='" . $categoria['data']["cod"] . "'>" . $categoria['data']["titulo"] . "</option>";
                    }
                }
                ?>
            </select>
        </label>
        <label class="col-md-12">
            Link mostrar(<input type="checkbox" id="chli" name="linkOn" value="1" <?php if ($slide['data']['link_on']) {
                echo "checked";
            } ?>>):<br/>
            <input type="text" id="link" value="<?= $slide['data']["link"] ?>" name="link">
        </label>
        <br/>
        <div class="col-md-12">
            <div class="row">
                <?php
                if (!empty($slide['image'])) {
                    ?>
                    <div class='col-md-2 mb-20 mt-20'>
                        <div style="height:200px;background:url(<?= '../' . $slide['image']['ruta']; ?>) no-repeat center center/contain;">
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <a href="<?= URLADMIN . '/index.php?op=sliders&accion=modificar&cod=' . $slide['image']['cod'] . '&borrarImg=' . $slide['image']['id'] ?>" class="btn btn-sm btn-block btn-danger">
                                    BORRAR IMAGEN
                                </a>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <?php
                } else {
                    ?>
                    <label class="col-md-7">Imágen:<br/>
                        <input type="file" id="file"  name="files[]" multiple="multiple" accept="image/*"/>
                    </label>
                    <?php
                }
                ?>
            </div>
        </div>
        <div class="clearfix">
        </div>
        <br/>
        <div class="col-md-12">
            <input type="submit" class="btn btn-primary" name="agregar" value="Modificar Sliders"/>
        </div>
    </form>
</div>
<script>
    setInterval(checkSliderProps, 1000);
</script>