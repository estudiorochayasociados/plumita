<?php
$landing = new Clases\Landing();
$imagenes = new Clases\Imagenes();
$zebra = new Clases\Zebra_Image();

$cod = $funciones->antihack_mysqli(isset($_GET["cod"]) ? $_GET["cod"] : '');
$borrarImg = $funciones->antihack_mysqli(isset($_GET["borrarImg"]) ? $_GET["borrarImg"] : '');

$landing->set("cod", $cod);
$landingInd = $landing->view();
$imagenes->set("cod", $landingInd['data']["cod"]);
$imagenes->set("link", "landing&accion=modificar");

$categorias = new Clases\Categorias();
$data = $categorias->list(array("area = 'landing'"), '', '');

if ($borrarImg != '') {
    $imagenes->set("id", $borrarImg);
    $imagenes->delete();
    $funciones->headerMove(URLADMIN . "/index.php?op=landing&cod=$cod");
}

if (isset($_POST["agregar"])) {
    $count = 0;
    $cod = $landingInd['data']["cod"];
    $landing->set("cod", $cod);
    $landing->set("titulo", $funciones->antihack_mysqli(isset($_POST["titulo"]) ? $_POST["titulo"] : ''));
    $landing->set("categoria", $funciones->antihack_mysqli(isset($_POST["categoria"]) ? $_POST["categoria"] : ''));
    $landing->set("desarrollo", $funciones->antihack_mysqli(isset($_POST["desarrollo"]) ? $_POST["desarrollo"] : ''));
    $landing->set("fecha", $funciones->antihack_mysqli(isset($_POST["fecha"]) ? $_POST["fecha"] : ''));
    $landing->set("description", trim($funciones->antihack_mysqli(isset($_POST["description"]) ? $_POST["description"] : '')));
    $landing->set("keywords", $funciones->antihack_mysqli(isset($_POST["keywords"]) ? $_POST["keywords"] : ''));

    if (isset($_FILES['files'])) {
        $imagenes->resizeImages($cod, $_FILES['files'], "../assets/archivos", "../assets/archivos/recortadas");
    }

    $landing->edit();
    $funciones->headerMove(URLADMIN . "/index.php?op=landing");
}
?>
<div class="col-md-12 ">
    <h4>
        Landing
    </h4>
    <hr/>
    <form method="post" class="row" enctype="multipart/form-data">
        <label class="col-md-4">
            Título:<br/>
            <input type="text" value="<?= $landingInd['data']["titulo"] ?>" name="titulo" required>
        </label>
        <label class="col-md-4">
            Categoría:<br/>
            <select name="categoria">
                <?php
                foreach ($data as $categoria) {
                    if ($landingInd['data']["categoria"] == $categoria['data']["cod"]) {
                        echo "<option value='" . $categoria['data']["cod"] . "' selected>" . $categoria['data']["titulo"] . "</option>";
                    } else {
                        echo "<option value='" . $categoria['data']["cod"] . "'>" . $categoria['data']["titulo"] . "</option>";
                    }
                }
                ?>
            </select>
        </label>
        <label class="col-md-4">
            Fecha:<br/>
            <input type="date" name="fecha" value="<?= $landingInd['data']["fecha"] ?>">
        </label>
        <div class="clearfix">
        </div>
        <label class="col-md-12">
            Desarrollo:<br/>
            <textarea name="desarrollo" class="ckeditorTextarea">
                <?= $landingInd['data']["desarrollo"]; ?>
            </textarea>
        </label>
        <div class="clearfix">
        </div>
        <label class="col-md-12">
            Palabras claves dividas por ,<br/>
            <input type="text" name="keywords" value="<?= $landingInd['data']["keywords"] ?>">
        </label>
        <label class="col-md-12">
            Descripción breve<br/>
            <textarea name="description"><?= $landingInd['data']["description"] ?></textarea>
        </label>
        <br/>
        <div class="col-md-12">
            <div class="row">
                <?php
                if (!empty($landingInd['images'])) {
                    foreach ($landingInd['images'] as $img) {
                        ?>
                        <div class='col-md-2 mb-20 mt-20'>
                            <div style="height:200px;background:url(<?= '../' . $img['ruta']; ?>) no-repeat center center/contain;">
                            </div>
                            <div class="row">
                                <div class="col-md-7">
                                    <a href="<?= URLADMIN . '/index.php?op=novedades&accion=modificar&cod=' . $img['cod'] . '&borrarImg=' . $img['id'] ?>" class="btn btn-sm btn-block btn-danger">
                                        BORRAR IMAGEN
                                    </a>
                                </div>
                                <div class="col-md-5 text-right">
                                    <select onchange='$(location).attr("href", "<?= CANONICAL ?>&idImg=<?= $img["id"] ?>&ordenImg="+$(this).val())'>
                                        <?php
                                        for ($i = 0; $i <= count($landingInd['images']); $i++) {
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
        <label class="col-md-12">
            Imágenes:<br/>
            <input type="file" id="file" name="files[]" multiple="multiple" accept="image/*"/>
        </label>
        <div class="clearfix">
        </div>
        <br/>
        <div class="col-md-12">
            <input type="submit" class="btn btn-primary" name="agregar" value="Modificar"/>
        </div>
    </form>
</div>