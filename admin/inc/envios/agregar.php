<?php
$envios = new Clases\Envios();
if (isset($_POST["agregar"])) {
    $count = 0;
    $cod = substr(md5(uniqid(rand())), 0, 10);
    $envios->set("cod", $cod);
    $envios->set("titulo", $funciones->antihack_mysqli(isset($_POST["titulo"]) ? $_POST["titulo"] : ''));
    $envios->set("peso", $funciones->antihack_mysqli(isset($_POST["peso"]) ? $_POST["peso"] : ''));
    $envios->set("precio", $funciones->antihack_mysqli(isset($_POST["precio"]) ? $_POST["precio"] : ''));
    $envios->set("estado", $funciones->antihack_mysqli(isset($_POST["estado"]) ? $_POST["estado"] : ''));
    $envios->set("limite", $funciones->antihack_mysqli(isset($_POST["limite"]) ? $_POST["limite"] : ''));

    $envios->add();
    $funciones->headerMove(URL_ADMIN . "/index.php?op=envios");
}
?>

<div class="col-md-12 ">
    <h4>Envios</h4>
    <hr/>
    <form method="post" class="row">
        <label class="col-md-4">Título:<br/>
            <input type="text" name="titulo" required>
        </label>
        <label class="col-md-2">Peso:<br/>
            <input data-suffix="Kg" id="pes" value="0" min="0" name="peso" type="number" required/>
        </label>
        <label class="col-md-2">Precio:<br/>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text">$</span>
                </div>
                <input type="number" class="form-control" min="0" name="precio" required>
            </div>
        </label>
        
        <label class="col-md-2">Limite:<br/>
        <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text">$</span>
                </div>
                <input type="float" class="form-control" name="limite" >
            </div>
        </label>
        <label class="col-md-2">Estado:<br/>
            <select name="estado" required>
                <option value="1">Activado</option>
                <option value="0">Desactivado</option>
            </select>
        </label>
        <div class="clearfix"></div>
        <br/>
        <div class="col-md-12">
            <input type="submit" class="btn btn-primary" name="agregar" value="Crear Envio"/>
        </div>
    </form>
</div>
<script>
    $("#pes").inputSpinner()
</script>
