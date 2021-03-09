<?php
$novedad = new Clases\Novedades();
$imagenes = new Clases\Imagenes();
$zebra = new Clases\Zebra_Image();

$categorias = new Clases\Categorias();
$categoriasData = $categorias->list(array("area = 'novedades'"), "titulo ASC", "");

if (isset($_POST["agregar"])) {
    $count = 0;
    $cod = substr(md5(uniqid(rand())), 0, 10);

    $novedad->set("cod", $cod);
    $novedad->set("titulo", $funciones->antihack_mysqli(isset($_POST["titulo"]) ? $_POST["titulo"] : ''));
    $novedad->set("categoria", $funciones->antihack_mysqli(isset($_POST["categoria"]) ? $_POST["categoria"] : ''));
    $novedad->set("subcategoria", $funciones->antihack_mysqli(isset($_POST["subcategoria"]) ? $_POST["subcategoria"] : ''));
    $novedad->set("desarrollo", $funciones->antihack_mysqli(isset($_POST["desarrollo"]) ? $_POST["desarrollo"] : ''));
    $novedad->set("fecha", $funciones->antihack_mysqli(isset($_POST["fecha"]) ? $_POST["fecha"] : ''));
    $novedad->set("description", $funciones->antihack_mysqli(isset($_POST["description"]) ? $_POST["description"] : ''));
    $novedad->set("keywords", $funciones->antihack_mysqli(isset($_POST["keywords"]) ? $_POST["keywords"] : ''));

    if (isset($_FILES['files'])) {
        $imagenes->resizeImages($cod, $_FILES['files'], "../assets/archivos", "../assets/archivos/recortadas");
    }

    $novedad->add();
    $funciones->headerMove(URLADMIN . "/index.php?op=novedades");
}
?>

<div class="col-md-12">
    <h4>Novedades</h4>
    <hr/>
    <form method="post" class="row" enctype="multipart/form-data">
        <label class="col-md-3">Título:<br/>
            <input type="text" name="titulo" required>
        </label>
        <label class="col-md-3">
            Categoría:<br/>
            <select name="categoria">
                <option value="" selected>-- categorías --</option>
                <?php
                foreach ($categoriasData as $categoria) {
                    echo "<option value='" . $categoria["data"]["cod"] . "'>" . mb_strtoupper($categoria["data"]["titulo"]) . "</option>";
                }
                ?>
            </select>
        </label>
        <label class="col-md-3">
            Subcategoría:<br/>
            <select name="subcategoria">
                <option value="" selected>-- Sin subcategoría --</option>
                <?php
                foreach ($categoriasData as $categoria) {
                    ?>
                    <optgroup label="<?= mb_strtoupper($categoria["data"]['titulo']) ?>">
                        <?php
                        foreach ($categoria["subcategories"] as $subcategorias) {
                            echo "<option value='" . $subcategorias["data"]["cod"] . "'>" . mb_strtoupper($subcategorias["data"]["titulo"]) . "</option>";
                        }
                        ?>
                    </optgroup>
                    <?php
                }
                ?>
            </select>
        </label>
        <label class="col-md-3">Fecha:<br/>
            <input type="date" name="fecha" value="<?= date('Y-m-d') ?>">
        </label>
        <div class="clearfix"></div>
        <label class="col-md-12">Desarrollo:<br/>
            <textarea name="desarrollo" class="ckeditorTextarea" required></textarea>
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
            <input type="submit" class="btn btn-primary" name="agregar" value="Agregar"/>
        </div>
    </form>
</div>
