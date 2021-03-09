<?php
$contenidos = new Clases\Contenidos();
$imagenes = new Clases\Imagenes();
$zebra = new Clases\Zebra_Image();

$cod = $funciones->antihack_mysqli(isset($_GET['cod']) ? $_GET['cod'] : '');
$borrarImg = $funciones->antihack_mysqli(isset($_GET["borrarImg"]) ? $_GET["borrarImg"] : '');

$contenidos->set("cod", $cod);
$data = $contenidos->view();

//CAMBIAR ORDEN DE LAS IMAGENES
if (isset($_GET["ordenImg"]) && isset($_GET["idImg"])) {
    $imagenes->set("id", $_GET["idImg"]);
    $imagenes->set("orden", $_GET["ordenImg"]);
    $imagenes->setOrder();
    $funciones->headerMove(URLADMIN . "/index.php?op=contenidos&accion=modificar&cod=$cod");
}

//BORRAR IMAGEN
if ($borrarImg != '') {
    $imagenes->set("id", $borrarImg);
    $imagenes->delete();
    $funciones->headerMove(URLADMIN . "/index.php?op=contenidos&accion=modificar&cod=$cod");
}

//GUARDAR
if (isset($_POST["agregar"])) {
    $count = 0;
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
    $contenidos->edit();
    $funciones->headerMove(URLADMIN . "/index.php?op=contenidos");
}
?>
<div class="mt-20">
    <div class="col-lg-12">
        <h4>Modificar Contenidos</h4>
        <hr/>
    </div>
    <form method="post" enctype="multipart/form-data">
        <label class="col-lg-12">Título:
            <br/>
            <input type="text" name="titulo" value="<?= $data['data']["titulo"]; ?>"/>
        </label>
        <label class="col-lg-12">Subtítulo:
            <br/>
            <input type="text" name="subtitulo" value="<?= $data['data']["subtitulo"]; ?>"/>
        </label>
        <label class="col-lg-12">Desarrollo:
            <br/>
            <textarea name="contenido" class="ckeditorTextarea"><?= $data['data']["contenido"]; ?></textarea>
        </label>
        <br>
        <div class="col-md-12">
            <div class="row">
                <?php
                if (!empty($data['images'])) {
                    foreach ($data['images'] as $img) {
                        ?>
                        <div class='col-md-2 mb-20 mt-20'>
                            <div style="height:200px;background:url(<?= '../' . $img['ruta']; ?>) no-repeat center center/contain;">
                            </div>
                            <div class="row">
                                <div class="col-md-7">
                                    <a href="<?= URLADMIN . '/index.php?op=contenidos&accion=modificar&cod=' . $data['data']['cod'] . '&borrarImg=' . $img['id'] ?>" class="btn btn-sm btn-block btn-danger">
                                        BORRAR IMAGEN
                                    </a>
                                </div>
                                <div class="col-md-5 text-right">
                                    <select onchange='$(location).attr("href", "<?= CANONICAL ?>&idImg=<?= $img["id"] ?>&ordenImg="+$(this).val())'>
                                        <?php
                                        for ($i = 0; $i <= count($data['images']); $i++) {
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
        <label class="col-md-12">Imágenes:<br/>
            <input type="file" id="file" name="files[]" multiple="multiple" accept="image/*"/>
        </label>
        <div class="clearfix"></div>
        <div class="col-lg-12 mt-10">
            <input type="submit" class="btn btn-primary" name="agregar" value="Modificar Contenido"/>
        </div>
    </form>
</div>
