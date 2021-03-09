<?php
$envios = new Clases\Envios();
$cod = $funciones->antihack_mysqli(isset($_GET["cod"]) ? $_GET["cod"] : '');
$envios->set("cod", $cod);
$envios_ = $envios->view();
if (isset($_POST["agregar"])) {
    $count = 0;
    $cod = $envios_['data']["cod"];
    $envios->set("cod", $cod);
    $envios->set("titulo", $funciones->antihack_mysqli(isset($_POST["titulo"]) ? $_POST["titulo"] : ''));
    $envios->set("precio", $funciones->antihack_mysqli(isset($_POST["precio"]) ? $_POST["precio"] : '0'));
    $envios->set("peso", $funciones->antihack_mysqli(isset($_POST["peso"]) ? $_POST["peso"] : ''));
    $envios->set("estado", $funciones->antihack_mysqli(isset($_POST["estado"]) ? $_POST["estado"] : ''));
    $envios->set("limite", $funciones->antihack_mysqli(isset($_POST["limite"]) ? $_POST["limite"] : ''));
    $envios->edit();
    $funciones->headerMove(URL_ADMIN . "/index.php?op=envios");
}
?>

<div class="col-md-12 ">
    <h4>
        Envios
    </h4>
    <hr />
    <form method="post" class="row" enctype="multipart/form-data">
        <label class="col-md-4">
            Título:<br />
            <input type="text" value="<?= $envios_['data']["titulo"] ?>" name="titulo" required>
        </label>
        <label class="col-md-2">
            Peso:<br />
            <input data-suffix="Kg" id="pes" value="<?= $envios_['data']["peso"] ?>" min="0" name="peso" type="number" required />
        </label>
        <label class="col-md-2">
            Precio:<br />
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text">$</span>
                </div>
                <input type="number" class="form-control" min="0" value="<?= $envios_['data']["precio"] ?>" name="precio" required>
            </div>
        </label>
        <label class="col-md-2">Limite:<br />
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text">$</span>
                </div>
                <input type="float" class="form-control" value="<?= $envios_['data']["limite"] ?>" name="limite">
            </div>
        </label>
        <label class="col-md-2">Estado:<br />
            <select name="estado" required>
                <option value="1" <?php if ($envios_['data']['estado'] == 1) {
                                        echo "selected";
                                    } ?>>Activado
                </option>
                <option value="0" <?php if ($envios_['data']['estado'] == 0) {
                                        echo "selected";
                                    } ?>>Desactivado
                </option>
            </select>
        </label>
        <div class="clearfix"></div>
        <div class="col-md-12">
            <input type="submit" class="btn btn-primary" name="agregar" value="Modificar Envio" />
        </div>
    </form>
</div>
<script>
    $("#pes").inputSpinner()
</script>