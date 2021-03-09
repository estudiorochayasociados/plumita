<?php
$admin = new Clases\Admin();

if (isset($_POST["agregar"])) {
    $admin->set("email", $funciones->antihack_mysqli(isset($_POST["email"]) ? $_POST["email"] : ''));
    $admin->set("password", $funciones->antihack_mysqli(isset($_POST["pass"]) ? $_POST["pass"] : ''));

    $admin->add();
    $funciones->headerMove(URLADMIN . "/index.php?op=administradores");
}
?>

<div class="col-md-12">
    <h4>Administradores</h4>
    <hr />
    <form method="post" class="row" enctype="multipart/form-data">
        <label class="col-md-4">Email:<br />
            <input type="text" name="email" value="" required>
        </label>
        <label class="col-md-4">Contraseña:<br />
            <input type="text" name="pass" required>
        </label>
        <br />
        <div class="col-md-12">
            <input type="submit" class="btn btn-primary" name="agregar" value="Crear Administrador" />
        </div>

    </form>
</div>