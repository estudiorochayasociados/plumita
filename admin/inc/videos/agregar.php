<?php
$video = new Clases\Videos();
$categorias = new Clases\Categorias();
$categoriasData = $categorias->list(array("area = 'videos'"), "titulo ASC", "");

if (isset($_POST["agregar"])) {
    $cod = substr(md5(uniqid(rand())), 0, 10);

    $video->set("cod", $cod);
    $video->set("titulo", isset($_POST["titulo"]) ? $_POST["titulo"] : '');
    $video->set("descripcion", isset($_POST["descripcion"]) ? $_POST["descripcion"] : '');
    $video->set("categoria", isset($_POST["categoria"]) ? $_POST["categoria"] : '');
    $video->set("subcategoria", isset($_POST["subcategoria"]) ? $_POST["subcategoria"] : '');
    $video->set("link", $_POST["link"]);
    $video->add();
    $funciones->headerMove(URLADMIN . "/index.php?op=videos");
}
?>
<div class="mt-20">
    <div class="col-md-12">
        <h4>Subir Videos</h4>
        <hr/>
    </div>
    <form method="post" class="row">
        <label class="col-md-6">Título:
            <br/>
            <input type="text" name="titulo" value="" required/>
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
        <label class="col-md-12">Descripción<br/>
            <textarea name="descripcion"></textarea>
        </label>
        <label class="col-md-12">Link Video:
            <br/>
            <input type="url" name="link" value="" required/>
        </label>
        <div class="clearfix"></div>
        <div class="col-md-12">
            <input type="submit" class="btn btn-primary" name="agregar" value="Subir Video"/>
        </div>
    </form>
</div>
