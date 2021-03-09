<?php
$banners = new Clases\Banner();
$imagenes = new Clases\Imagenes();
$zebra = new Clases\Zebra_Image();
$categorias = new Clases\Categorias();
$data = $categorias->list(array("area = 'banners'"), '', '');

if (isset($_POST["agregar"])) {
    $count = 0;
    $cod = substr(md5(uniqid(rand())), 0, 10);

    $banners->set("cod", $cod);
    $banners->set("nombre", $funciones->antihack_mysqli(isset($_POST["nombre"]) ? $_POST["nombre"] : ''));
    $banners->set("categoria", $funciones->antihack_mysqli(isset($_POST["categoria"]) ? $_POST["categoria"] : ''));
    $banners->set("link", $funciones->antihack_mysqli(isset($_POST["link"]) ? $_POST["link"] : ''));

    if (isset($_FILES['files'])) {
        $imagenes->resizeImages($cod, $_FILES['files'], "../assets/archivos", "../assets/archivos/recortadas");
    }

    $banners->add();
    $funciones->headerMove(URLADMIN . "/index.php?op=banners");
}
?>
<div class="col-md-12 ">
    <h4>Banners</h4>
    <hr/>
    <form method="post" class="row" enctype="multipart/form-data">
        <label class="col-md-8">Nombre:<br/>
            <input type="text" name="nombre" required>
        </label>
        <label class="col-md-4">Categoría:<br/>
            <select name="categoria" required>
                <option value="" disabled selected>-- categorías --</option>
                <?php
                foreach ($data as $categoria) {
                    echo "<option value='" . $categoria['data']["cod"] . "'>" . $categoria['data']["titulo"] . "</option>";
                }
                ?>
            </select>
        </label>
        <div class="clearfix"></div>
        <div class="clearfix"></div>
        <label class="col-md-12">Url del banner<br/>
            <small>*Opcional</small>
            <input type="text" name="link">
        </label>
        <br/>
        <label class="col-md-7">Imágenes:<br/>
            <input type="file" id="file" name="files[]" accept="image/*" required/>
        </label>
        <div class="clearfix"></div>
        <br/>
        <div class="col-md-12">
            <input type="submit" class="btn btn-primary" name="agregar" value="Crear Banner"/>
        </div>
    </form>
</div>
