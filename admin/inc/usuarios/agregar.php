<?php
$usuarios = new Clases\Usuarios();
$pedido = $funciones->antihack_mysqli(isset($_GET["pedido"]) ? $_GET["pedido"] : '');
?>

<div class="col-md-12 ">
    <h4>
        Usuarios
    </h4>
    <hr/>
    <?php
    if (isset($_POST["agregar"])) {
        $cod = substr(md5(uniqid(rand())), 0, 10);

        $usuarios->set("cod", $cod);
        $usuarios->set("nombre", $funciones->antihack_mysqli(isset($_POST["nombre"]) ? $_POST["nombre"] : ''));
        $usuarios->set("apellido", $funciones->antihack_mysqli(isset($_POST["apellido"]) ? $_POST["apellido"] : ''));
        $usuarios->set("doc", $funciones->antihack_mysqli(isset($_POST["doc"]) ? $_POST["doc"] : ''));
        $usuarios->set("email", $funciones->antihack_mysqli(isset($_POST["email"]) ? $_POST["email"] : ''));
        $usuarios->set("password",$funciones->antihack_mysqli(isset($_POST["password"]) ? $_POST["password"] : ""));
        $usuarios->set("postal", $funciones->antihack_mysqli(isset($_POST["postal"]) ? $_POST["postal"] : ''));
        $usuarios->set("localidad", $funciones->antihack_mysqli(isset($_POST["localidad"]) ? $_POST["localidad"] : ''));
        $usuarios->set("direccion", $funciones->antihack_mysqli(isset($_POST["direccion"]) ? $_POST["direccion"] : ''));
        $usuarios->set("provincia", $funciones->antihack_mysqli(isset($_POST["provincia"]) ? $_POST["provincia"] : ''));
        $usuarios->set("pais", $funciones->antihack_mysqli(isset($_POST["pais"]) ? $_POST["pais"] : ''));
        $usuarios->set("telefono", $funciones->antihack_mysqli(isset($_POST["telefono"]) ? $_POST["telefono"] : ''));
        $usuarios->set("celular", $funciones->antihack_mysqli(isset($_POST["celular"]) ? $_POST["celular"] : ''));
        $usuarios->set("minorista", $funciones->antihack_mysqli(isset($_POST["minorista"]) ? $_POST["minorista"] : 1));
        $usuarios->set("estado", $funciones->antihack_mysqli(isset($_POST["activo"]) ? $_POST["activo"] : 1));
        $usuarios->set("invitado", $funciones->antihack_mysqli(isset($_POST["invitado"]) ? $_POST["invitado"] : 1));
        $usuarios->set("descuento", $funciones->antihack_mysqli(isset($_POST["descuento"]) ? $_POST["descuento"] : 0));
        $usuarios->set("fecha", $funciones->antihack_mysqli(isset($_POST["fecha"]) ? $_POST["fecha"] : date("Y-m-d")));
        echo "<hr>";
        if ($usuarios->add()) {
            if ($pedido == 1) {
                $funciones->headerMove(URLADMIN . '/index.php?op=pedidos&accion=agregar&usuario=' . $cod);
            } else {
                $funciones->headerMove(URLADMIN . "/index.php?op=usuarios");
            }
        } else {
            echo "<span class='d-block alert alert-danger'>El email ya se encuentra registrado</span>";
        }

    }
    ?>
    <form method="post" class="row">
        <label class="col-md-4">
            Nombre:<br/>
            <input type="text" name="nombre" value="<?= isset($_POST["nombre"]) ? $_POST["nombre"] : '' ?>" required/>
        </label>
        <label class="col-md-4">
            Apellido:<br/>
            <input type="text" name="apellido" value="<?= isset($_POST["apellido"]) ? $_POST["apellido"] : '' ?>" required/>
        </label>
        <label class="col-md-4">
            DNI/CUIT/CUIL:<br/>
            <input type="text" name="doc" value="<?= isset($_POST["doc"]) ? $_POST["doc"] : '' ?>"/>
        </label>
        <label class="col-md-6">
            Email:<br/>
            <input type="text" name="email" value="<?= isset($_POST["email"]) ? $_POST["email"] : '' ?>" required/>
        </label>
        <label class="col-md-6">
            Password:<br/>
            <input type="password" id="password" class="form-control" name="password"/>
        </label>
        <label class="col-md-4">
            Dirección:<br/>
            <input type="text" name="direccion" value="<?= isset($_POST["direccion"]) ? $_POST["direccion"] : '' ?>" required/>
        </label>
        <label class="col-md-4">
            Localidad:<br/>
            <input type="text" name="localidad" value="<?= isset($_POST["localidad"]) ? $_POST["localidad"] : '' ?>" required/>
        </label>
        <label class="col-md-4">
            Provincia:<br/>
            <input type="text" name="provincia" value="<?= isset($_POST["provincia"]) ? $_POST["provincia"] : '' ?>" required/>
        </label>
        <label class="col-md-4">
            Pais:<br/>
            <input type="text" name="pais" value="<?= isset($_POST["pais"]) ? $_POST["pais"] : '' ?>"/>
        </label>
        <label class="col-md-2">
            Postal:<br/>
            <input type="text" name="postal" value="<?= isset($_POST["postal"]) ? $_POST["postal"] : '' ?>"/>
        </label>
        <label class="col-md-3">
            Telefono:<br/>
            <input type="text" name="telefono" value="<?= isset($_POST["telefono"]) ? $_POST["telefono"] : '' ?>" required/>
        </label>
        <label class="col-md-3">
            Celular:<br/>
            <input type="text" name="celular" value="<?= isset($_POST["celular"]) ? $_POST["celular"] : '' ?>"/>
        </label>
        <label class="col-md-3">
            Activo:<br/>
            <select name="activo" class="form-control" required>
                <option></option>
                <option value="1">SI</option>
                <option value="0">NO</option>
            </select>
        </label>
        <label class="col-md-3">
            Invitado:<br/>
            <select name="invitado" id="invitado" class="form-control" required>
                <option></option>
                <option value="1">SI</option>
                <option value="0">NO</option>
            </select>
        </label>
        <label class="col-md-3">
            Minorista:<br/>
            <select name="minorista" class="form-control" required>
                <option></option>
                <option value="1">SI</option>
                <option value="0">NO</option>
            </select>
        </label>
        <label class="col-md-3">
            Descuento (%)<br/>
            <input type="number" name="descuento" min="0" max="100" value="<?= isset($_POST["descuento"]) ? $_POST["descuento"] : 0 ?>" placeholder="%"/>
        </label>
        <div class="clearfix">
        </div>
        <br/>
        <div class="col-md-12">
            <input type="submit" class="btn btn-primary" name="agregar" value="Crear Usuarios"/>
        </div>
    </form>
</div>
<script>
    setInterval(U, 1000);

    function U() {
        if ($('#invitado').val()== 1) {
            $('#password').attr('required', true);
        }else{
            $('#password').attr('required', false);
        }
    }
</script>