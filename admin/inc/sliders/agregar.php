<?php
$sliders  = new Clases\Sliders();
$imagenes = new Clases\Imagenes();
$zebra    = new Clases\Zebra_Image();

$categorias = new Clases\Categorias();
$data = $categorias->list(array("area = 'sliders'"),'','');

if (isset($_POST["agregar"])) {
    $cod = substr(md5(uniqid(rand())), 0, 10);

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

    $sliders->add();
    $funciones->headerMove(URLADMIN . "/index.php?op=sliders&accion=ver");
}
?>

<div class="col-md-12 ">
    <h4>Sliders</h4>
    <hr/>
    <form method="post" class="row" enctype="multipart/form-data">
        <label class="col-md-4">Título (mostrar <input type="checkbox" name="tituloOn" value="1">):<br/>
            <input type="text" name="titulo" required>
        </label>
        <label class="col-md-4">Subtitulo (mostrar <input type="checkbox" id="chsub" name="subtituloOn" value="1">):<br/>
            <input type="text" id="sub" name="subtitulo">
        </label>
        <label class="col-md-4">Categoría:<br/>
            <select name="categoria" required>
                <?php
                foreach ($data as $categoria) {
                    echo "<option value='".$categoria['data']["cod"]."'>".$categoria['data']["titulo"]."</option>";
                }
                ?>
            </select>
        </label>
        <label class="col-md-12">Link mostrar(<input type="checkbox" id="chli" name="linkOn" value="1">):<br/>
            <input type="text" id="link" name="link">
        </label>
        <label class="col-md-7">Imágen:<br/>
            <input type="file" id="file" name="files[]" accept="image/*" required/>
        </label>
        <div class="clearfix"></div>
        <br/>
        <div class="col-md-12">
            <input type="submit" class="btn btn-primary" name="agregar" value="Crear Slider"
        </div>
    </form>
</div>
<script>
    setInterval(D, 1000);

    function D() {
        if ($('#chsub').prop('checked')) {
            $('#sub').attr('required', true);
        }else{
            $('#sub').attr('required', false);
        }
        if ($('#chli').prop('checked')) {
            $('#link').attr('required', true);
        }else{
            $('#link').attr('required', false);
        }
    }
</script>