<?php
$portfolio = new Clases\Portfolio();
$imagenes = new Clases\Imagenes();
$zebra = new Clases\Zebra_Image();
$categorias = new Clases\Categorias();

$cod = $funciones->antihack_mysqli(isset($_GET["cod"]) ? $_GET["cod"] : '');
$borrarImg = $funciones->antihack_mysqli(isset($_GET["borrarImg"]) ? $_GET["borrarImg"] : '');
$categoriasData = $categorias->list(array("area = 'portfolio'"), "titulo ASC", "");

$portfolio->set("cod", $cod);
$portfolioSingle = $portfolio->view();

//CAMBIAR ORDEN DE LAS IMAGENES
if (isset($_GET["ordenImg"]) && isset($_GET["idImg"])) {
    $imagenes->set("id", $_GET["idImg"]);
    $imagenes->set("orden", $_GET["ordenImg"]);
    $imagenes->setOrder();
    $funciones->headerMove(URLADMIN . "/index.php?op=portfolio&accion=modificar&cod=$cod");
}

//BORRAR IMAGEN
if ($borrarImg != '') {
    $imagenes->set("id", $borrarImg);
    $imagenes->delete();
    $funciones->headerMove(URLADMIN . "/index.php?op=portfolio&accion=modificar&cod=$cod");
}

//GUARDAR
if (isset($_POST["modificar"])) {
    $count = 0;
    $img_meli = '';
    $portfolio->set("cod", $cod);
    $portfolio->set("titulo", $funciones->antihack_mysqli(isset($_POST["titulo"]) ? $_POST["titulo"] : ''));
    $portfolio->set("categoria", $funciones->antihack_mysqli(isset($_POST["categoria"]) ? $_POST["categoria"] : ''));
    $portfolio->set("subcategoria", $funciones->antihack_mysqli(isset($_POST["subcategoria"]) ? $_POST["subcategoria"] : ''));
    $portfolio->set("desarrollo", $funciones->antihack_mysqli(isset($_POST["desarrollo"]) ? $_POST["desarrollo"] : ''));
    $portfolio->set("fecha", $funciones->antihack_mysqli(isset($_POST["fecha"]) ? $_POST["fecha"] : ''));
    $portfolio->set("description", $funciones->antihack_mysqli(isset($_POST["description"]) ? $_POST["description"] : ''));
    $portfolio->set("keywords", $funciones->antihack_mysqli(isset($_POST["keywords"]) ? $_POST["keywords"] : ''));

    if (isset($_FILES['files'])) {
        $imagenes->resizeImages($cod, $_FILES['files'], "../assets/archivos", "../assets/archivos/recortadas");
    }

    $portfolio->edit();
    $funciones->headerMove(URLADMIN . "/index.php?op=portfolio");
}
?>

<div class="col-md-12 ">
    <h4>
        Portfolio
    </h4>
    <hr/>
    <form method="post" class="row" enctype="multipart/form-data">
        <label class="col-md-3">
            Título:<br/>
            <input type="text" value="<?= $portfolioSingle["data"]["titulo"] ?>" name="titulo" required>
        </label>
        <label class="col-md-3">
            Categoría:<br/>
            <select name="categoria">
                <option value="">-- categorías --</option>
                <?php
                foreach ($categoriasData as $categoria) {
                    if ($portfolioSingle["data"]["categoria"] == $categoria["data"]["cod"]) {
                        echo "<option value='" . $categoria["data"]["cod"] . "' selected>" . mb_strtoupper($categoria["data"]["titulo"]) . "</option>";
                    } else {
                        echo "<option value='" . $categoria["data"]["cod"] . "'>" . mb_strtoupper($categoria["data"]["titulo"]) . "</option>";
                    }
                }
                ?>
            </select>
        </label>
        <label class="col-md-3">
            Subcategoría:<br/>
            <select name="subcategoria">
                <option value="">-- Sin subcategoría --</option>
                <?php
                foreach ($categoriasData as $categoria) {
                    ?>
                    <optgroup label="<?= mb_strtoupper($categoria["data"]['titulo']) ?>">
                        <?php
                        foreach ($categoria["subcategories"] as $subcategorias) {
                            if ($portfolioSingle["data"]["subcategoria"] == $subcategorias["data"]["cod"]) {
                                echo "<option value='" . $subcategorias["data"]["cod"] . "' selected>" . mb_strtoupper($subcategorias["data"]["titulo"]) . "</option>";
                            } else {
                                echo "<option value='" . $subcategorias["data"]["cod"] . "'>" . mb_strtoupper($subcategorias["data"]["titulo"]) . "</option>";
                            }
                        }
                        ?>
                    </optgroup>
                    <?php
                }
                ?>
            </select>
        </label>
        <label class="col-md-3">
            Fecha:<br/>
            <input type="date" name="fecha" value="<?= $portfolioSingle["data"]["fecha"] ?>">
        </label>

        <div class="clearfix">
        </div>
        <label class="col-md-12">
            Desarrollo:<br/>
            <textarea name="desarrollo" class="ckeditorTextarea" required>
                <?= $portfolioSingle["data"]["desarrollo"]; ?>
            </textarea>
        </label>
        <div class="clearfix">
        </div>
        <label class="col-md-12">
            Palabras claves dividas por ,<br/>
            <input type="text" name="keywords" value="<?= $portfolioSingle["data"]["keywords"] ?>">
        </label>
        <label class="col-md-12">
            Descripción breve<br/>
            <textarea name="description"><?= $portfolioSingle["data"]["description"] ?></textarea>
        </label>
        <br/>
        <div class="col-md-12">
            <div class="row">
                <?php
                if (!empty($portfolioSingle['images'])) {
                    foreach ($portfolioSingle['images'] as $img) {
                        ?>
                        <div class='col-md-2 mb-20 mt-20'>
                            <div style="height:200px;background:url(<?= '../' . $img['ruta']; ?>) no-repeat center center/contain;">
                            </div>
                            <div class="row">
                                <div class="col-md-7">
                                    <a href="<?= URLADMIN . '/index.php?op=portfolio&accion=modificar&cod=' . $img['cod'] . '&borrarImg=' . $img['id'] ?>" class="btn btn-sm btn-block btn-danger">
                                        BORRAR IMAGEN
                                    </a>
                                </div>
                                <div class="col-md-5 text-right">
                                    <select onchange='$(location).attr("href", "<?= CANONICAL ?>&idImg=<?= $img["id"] ?>&ordenImg="+$(this).val())'>
                                        <?php
                                        for ($i = 0; $i <= count($portfolioSingle['images']); $i++) {
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
            <input type="submit" class="btn btn-primary" name="modificar" value="Modificar"/>
        </div>
    </form>
</div>