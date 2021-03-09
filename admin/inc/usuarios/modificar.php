<?php
$usuarios = new Clases\Usuarios();

$cod = $funciones->antihack_mysqli(isset($_GET["cod"]) ? $_GET["cod"] : '');
$cod_error = $funciones->antihack_mysqli(isset($_GET["pedido"]) ? $_GET["pedido"] : '');

$usuarios->set("cod", $cod);
$usuario = $usuarios->view();

if (isset($_POST["modificar"])) {
    $usuarios->set("cod", $usuario['data']["cod"]);
    $usuarios->set("nombre", $funciones->antihack_mysqli(isset($_POST["nombre"]) ? $_POST["nombre"] : ''));
    $usuarios->set("apellido", $funciones->antihack_mysqli(isset($_POST["apellido"]) ? $_POST["apellido"] : ''));
    $usuarios->set("doc", $funciones->antihack_mysqli(isset($_POST["doc"]) ? $_POST["doc"] : ''));
    $usuarios->set("email", $funciones->antihack_mysqli(isset($_POST["email"]) ? $_POST["email"] : ''));
    $usuarios->set("password", $funciones->antihack_mysqli(isset($_POST["password"]) ? $_POST["password"] : ''));
    $usuarios->set("postal", $funciones->antihack_mysqli(isset($_POST["postal"]) ? $_POST["postal"] : ''));
    $usuarios->set("localidad", $funciones->antihack_mysqli(isset($_POST["localidad"]) ? $_POST["localidad"] : ''));
    $usuarios->set("direccion", $funciones->antihack_mysqli(isset($_POST["direccion"]) ? $_POST["direccion"] : ''));
    $usuarios->set("provincia", $funciones->antihack_mysqli(isset($_POST["provincia"]) ? $_POST["provincia"] : ''));
    $usuarios->set("pais", $funciones->antihack_mysqli(isset($_POST["pais"]) ? $_POST["pais"] : ''));
    $usuarios->set("telefono", $funciones->antihack_mysqli(isset($_POST["telefono"]) ? $_POST["telefono"] : ''));
    $usuarios->set("celular", $funciones->antihack_mysqli(isset($_POST["celular"]) ? $_POST["celular"] : ''));
    $usuarios->set("minorista", $funciones->antihack_mysqli(isset($_POST["minorista"]) ? $_POST["minorista"] : 1));
    $usuarios->set("invitado", $funciones->antihack_mysqli(isset($_POST["invitado"]) ? $_POST["invitado"] : 1));
    $usuarios->set("descuento", $funciones->antihack_mysqli(isset($_POST["descuento"]) ? $_POST["descuento"] : 0));
    $usuarios->set("estado", $funciones->antihack_mysqli(isset($_POST["estado"]) ? $_POST["estado"] : 1));
    $usuarios->set("fecha", $funciones->antihack_mysqli(isset($_POST["fecha"]) ? $_POST["fecha"] : date("Y-m-d")));

    $usuarios->edit();
    if (isset($_GET['pedido'])) {
        if ($cod_error == 2) {
            $funciones->headerMove(URLADMIN . "/index.php?op=pedidos&accion=agregar&usuario=" . $usuario['data']['cod'] . "&doc=1");
        }
    } else {
        $funciones->headerMove(URLADMIN . "/index.php?op=usuarios");
    }
}
$active = 0;
if (isset($_GET['pedido'])) {
    $error = "Completar los siguientes campos para poder terminar el pedido anterior:<br>";
    if ($cod_error == 2) {
        if (empty($usuario['data']['doc'])) {
            $error .= "- DNI para poder hacer factura A<br>";
            $active++;
        }
    }
}
?>
<div class="col-md-12 ">
    <h4>Usuarios</h4>
    <hr/>
    <?php
    if ($active > 0) {
        ?>
        <div class="alert alert-danger" role="alert">
            <?= $error ?>
        </div>
        <?php
    }
    ?>
    <form method="post" class="row">
        <label class="col-md-4">
            Nombre:<br/>
            <input type="text" name="nombre" value="<?= $usuario['data']['nombre']; ?>" required/>
        </label>
        <label class="col-md-4">
            Apellido:<br/>
            <input type="text" name="apellido" value="<?= $usuario['data']['apellido']; ?>" required/>
        </label>
        <label class="col-md-4">
            DNI/CUIT/CUIL:<br/>
            <input type="text" name="doc" value="<?= $usuario['data']['doc']; ?>"/>
        </label>
        <label class="col-md-6">
            Email:<br/>
            <input type="text" name="email" value="<?= $usuario['data']['email']; ?>" required/>
        </label>
        <label class="col-md-6">
            Password:<br/>
            <input type="password" id="password" class="form-control" name="password" value="<?= $usuario['data']['password']; ?>"/>
        </label>
        <label class="col-md-4">
            Dirección:<br/>
            <input type="text" name="direccion" value="<?= $usuario['data']['direccion']; ?>" required/>
        </label>
        <label class="col-md-4">
            Localidad:<br/>
            <input type="text" name="localidad" value="<?= $usuario['data']['localidad']; ?>" required/>
        </label>
        <label class="col-md-4">
            Provincia:<br/>
            <input type="text" name="provincia" value="<?= $usuario['data']['provincia']; ?>" required/>
        </label>
        <label class="col-md-4">
            Pais:<br/>
            <input type="text" name="pais" value="<?= $usuario['data']['pais']; ?>"/>
        </label>
        <label class="col-md-2">
            Postal:<br/>
            <input type="text" name="postal" value="<?= $usuario['data']['postal']; ?>"/>
        </label>
        <label class="col-md-3">
            Telefono:<br/>
            <input type="text" name="telefono" value="<?= $usuario['data']['telefono']; ?>" required/>
        </label>
        <label class="col-md-3">
            Celular:<br/>
            <input type="text" name="celular" value="<?= $usuario['data']['celular']; ?>"/>
        </label>
        <label class="col-md-3">
            Activo:<br/>
            <select name="estado" class="form-control" required>
                <option selected></option>
                <option value="1" <?php if ($usuario['data']["estado"] == 1) {
                    echo "selected";
                } ?>>SI
                </option>
                <option value="0" <?php if ($usuario['data']["estado"] == 0) {
                    echo "selected";
                } ?>>NO
                </option>
            </select>
        </label>
        <label class="col-md-3">
            Invitado:<br/>
            <select name="invitado" id="invitado" class="form-control" required>
                <option selected></option>
                <option value="1" <?php if ($usuario['data']["invitado"] == 1) {
                    echo "selected";
                } ?>>SI
                </option>
                <option value="0" <?php if ($usuario['data']["invitado"] == 0) {
                    echo "selected";
                } ?>>NO
                </option>
            </select>
        </label>
        <label class="col-md-3">
            Minorista:<br/>
            <select name="minorista" class="form-control" required>
                <option selected></option>
                <option value="1" <?php if ($usuario['data']["minorista"] == 1) {
                    echo "selected";
                } ?>>SI
                </option>
                <option value="0" <?php if ($usuario['data']["minorista"] == 0) {
                    echo "selected";
                } ?>>NO
                </option>
            </select>
        </label>
        <label class="col-md-3">
            Descuento (%)<br/>
            <input type="number" name="descuento" min="0" max="100" value="<?= $usuario['data']["descuento"] ?>" placeholder="%"/>
        </label>
        <div class="clearfix"></div>
        <br/>
        <div class="col-md-12">
            <input type="submit" class="btn btn-primary" name="modificar" value="Modificar Usuarios"/>
        </div>
    </form>
</div>
<script>
    setInterval(U, 1000);

    function U() {
        if ($('#invitado').val() == 1) {
            $('#password').attr('required', true);
        } else {
            $('#password').attr('required', false);
        }
    }
</script>