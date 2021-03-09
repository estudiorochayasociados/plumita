<?php
$video = new Clases\Videos();
$categorias = new Clases\Categorias();
$cod = $funciones->antihack_mysqli(isset($_GET['cod']) ? $_GET['cod'] : '');
$video->set("cod", $cod);

$categoriasData = $categorias->list(array("area = 'videos'"), "titulo ASC", "");

$data = $video->view();

if (isset($_POST["agregar"])) {
    $video->set("cod", $cod);
    $video->set("titulo", isset($_POST["titulo"]) ? $_POST["titulo"] : '');
    $video->set("descripcion", isset($_POST["descripcion"]) ? $_POST["descripcion"] : '');
    $video->set("categoria", isset($_POST["categoria"]) ? $_POST["categoria"] : '');
    $video->set("subcategoria", isset($_POST["subcategoria"]) ? $_POST["subcategoria"] : '');
    $video->set("link", $_POST["link"]);
    $video->edit();
    $funciones->headerMove(URLADMIN . "/index.php?op=videos");
}
?>
<div class="mt-20">
    <div class="col-md-12">
        <h4>Modificar Videos</h4>
        <hr/>
    </div>
    <form method="post" class="row">
        <label class="col-md-6">Título:
            <br/>
            <input type="text" name="titulo" value="<?= strtoupper($data['data']['titulo']); ?>" required/>
        </label>
        <label class="col-md-3">
            Categoría:<br/>
            <select name="categoria">
                <option value="">-- categorías --</option>
                <?php
                foreach ($categoriasData as $categoria) {
                    if ($data["data"]["categoria"] == $categoria["data"]["cod"]) {
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
                            if ($data["data"]["subcategoria"] == $subcategorias["data"]["cod"]) {
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
        <label class="col-md-12">
            Descripción<br/>
            <textarea name="descripcion"><?= $data["data"]["descripcion"] ?></textarea>
        </label>
        <label class="col-md-12">Link de video:
            <br/>
            <input type="url" name="link" value="<?= $data["data"]["link"]; ?>" required/>
        </label>
        <div class="clearfix"></div>
        <div class="col-md-12">
            <input type="submit" class="btn btn-primary" name="agregar" value="Modificar Video"/>
        </div>
    </form>
</div>
