<?php
$admin = new Clases\Admin();
$id = $funciones->antihack_mysqli(isset($_GET["id"]) ? $_GET["id"] : '');
$admin->set("id", $id);
$data = $admin->view();

if (isset($_POST["agregar"])) {
    $admin->set("id", $id);
    $admin->set("email", $funciones->antihack_mysqli(isset($_POST["email"]) ? $_POST["email"] : ''));
    $admin->set("password", $funciones->antihack_mysqli(isset($_POST["pass"]) ? $_POST["pass"] : ''));

    if ($admin->edit()) {
        $funciones->headerMove(URLADMIN . "/index.php?op=administradores");
    }
}
?>
<div class="col-md-12">
    <h4>Administradores</h4>
    <hr/>
    <form method="post" class="row" enctype="multipart/form-data">
        <label class="col-md-4">Email:<br/>
            <input type="text" value="<?= $data['data']["email"] ?>" name="email" required>
        </label>
        <label class="col-md-4">Contraseña:<br/>
            <input type="text" value="<?= $data['data']["password"] ?>" name="contraseña" required>
        </label>
        <br/>
        <div class="col-md-12">
            <input type="submit" class="btn btn-primary" name="agregar" value="Modificar Administrador"/>
        </div>
    </form>
</div>
