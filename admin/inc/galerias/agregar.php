<?php
$galerias = new Clases\Galerias();
$categorias = new Clases\Categorias();
$imagenes = new Clases\Imagenes();
$zebra = new Clases\Zebra_Image();
$data = $categorias->list(array("area = 'galerias'"), '', '');

if (isset($_POST["agregar"])) {
    $count = 0;
    $cod = substr(md5(uniqid(rand())), 0, 10);

    $galerias->set("cod", $cod);
    $galerias->set("titulo", isset($_POST["titulo"]) ? $funciones->antihack_mysqli($_POST["titulo"]) : '');
    $galerias->set("categoria", isset($_POST["categoria"]) ? $funciones->antihack_mysqli($_POST["categoria"]) : '');
    $galerias->set("desarrollo", isset($_POST["desarrollo"]) ? $funciones->antihack_mysqli($_POST["desarrollo"]) : '');
    $galerias->set("fecha", !empty($_POST["fecha"]) ? $funciones->antihack_mysqli($_POST["fecha"]) : date("Y-m-d"));
    $galerias->set("description", isset($_POST["description"]) ? $funciones->antihack_mysqli($_POST["description"]) : '');
    $galerias->set("keywords", isset($_POST["keywords"]) ? $funciones->antihack_mysqli($_POST["keywords"]) : '');

    if (isset($_FILES['files'])) {

        $imagenes->resizeImages($cod, $_FILES['files'], "../assets/archivos", "../assets/archivos/recortadas");
    }

    $galerias->add();
    $funciones->headerMove(URLADMIN . "/index.php?op=galerias");
}
?>

<div class="col-md-12 ">
    <h4>Galerias</h4>
    <hr/>
    <form method="post" class="row" enctype="multipart/form-data">
        <label class="col-md-4">Título:<br/>
            <input type="text" name="titulo" required>
        </label>
        <label class="col-md-4">Categoría:<br/>
            <select name="categoria" >
                <option value="" disabled selected>-- categorías --</option>
                <?php
                foreach ($data as $categoria) {
                    echo "<option value='" . $categoria['data']["cod"] . "'>" . $categoria['data']["titulo"] . "</option>";
                }
                ?>
            </select>
        </label>
        <label class="col-md-4">Fecha:<br/>
            <input type="date" name="fecha">
        </label>
        <div class="clearfix"></div>
        <label class="col-md-12">Desarrollo:<br/>
            <textarea name="desarrollo" class="ckeditorTextarea"></textarea>
        </label>
        <div class="clearfix"></div>
        <label class="col-md-12">Palabras claves dividas por ,<br/>
            <input type="text" name="keywords">
        </label>
        <label class="col-md-12">Descripción breve<br/>
            <textarea name="description"></textarea>
        </label>
        <br/>
        <label class="col-md-7">Imágenes:<br/>
            <input type="file" id="file" name="files[]" multiple="multiple" accept="image/*" required/>
        </label>
        <div class="clearfix"></div>
        <br/>
        <div class="col-md-12">
            <input type="submit" class="btn btn-primary" name="agregar" value="Crear Galerias"/>
        </div>
    </form>
</div>
