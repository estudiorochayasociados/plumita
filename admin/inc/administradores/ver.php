<?php
$admin = new Clases\Admin();
?>
<div class="mt-20">
    <div class="col-lg-12 col-md-12">
        <h4>Administradores
            <a class="btn btn-success pull-right" href="<?= URLADMIN ?>/index.php?op=administradores&accion=agregar">
                AGREGAR
                ADMINISTRADOR
            </a>
        </h4>
        <hr />
        <input class="form-control" id="myInput" type="text" placeholder="Buscar..">
        <hr />
        <table class="table  table-bordered  ">
            <thead>
                <th>Email</th>
                <th>Super Administrador</th>
                <th>Marketing</th>
                <th>Ecommerce</th>
                <th>Creador de Contenidos</th>
                <th><i class="fa fa-wrench"></i></th>
            </thead>
            <tbody>
                <?php
                if (isset($_POST["id"])) {
                    $id = $_POST["id"];
                    $roles = isset($_POST["rol" . $id]) ? $_POST["rol" . $id] : array();

                    $admin->set("id", $id);
                    $adminData = $admin->view()["data"]["rol"];
                    $adminData = !empty($adminData) ? $adminData : array();

                    $addRol = array_diff($roles, $adminData);
                    $deleteRol = array_diff($adminData, $roles);

                    if (!empty($addRol)) {
                        $admin->set("rol", implode("", $addRol));
                        $admin->addRolAdmin();
                    }
                    if (!empty($deleteRol)) {
                        $admin->set("rol", implode("", $deleteRol));
                        $admin->deleteRolAdmin();
                    }
                }

                $adminArray = $admin->list(array("id != 1"), "", "");
                if (is_array($adminArray)) {
                    foreach ($adminArray as $data) {
                        $superAdmin = in_array(2, $data["data"]["rol"]) ? "checked" : "";
                        $marketing = in_array(3, $data["data"]["rol"]) ? "checked" : "";
                        $ecommerce = in_array(4, $data["data"]["rol"]) ? "checked" : "";
                        $creadorContenidos = in_array(5, $data["data"]["rol"]) ? "checked" : "";
                ?>
                        <tr>
                            <td><?= mb_strtolower($data['data']["email"]) ?></td>
                            <form method="post">
                                <td><input type="checkbox" value="2" name="rol<?= $data["data"]["id"] ?>[]" onclick="this.form.submit()" <?= $superAdmin ?>></td>
                                <td><input type="checkbox" value="3" name="rol<?= $data["data"]["id"] ?>[]" onclick="this.form.submit()" <?= $marketing ?>></td>
                                <td><input type="checkbox" value="4" name="rol<?= $data["data"]["id"] ?>[]" onclick="this.form.submit()" <?= $ecommerce ?>></td>
                                <td><input type="checkbox" value="5" name="rol<?= $data["data"]["id"] ?>[]" onclick="this.form.submit()" <?= $creadorContenidos ?>></td>
                                <td>
                                    <a href="<?= URLADMIN . '/index.php?op=administradores&accion=modificar&id=' . $data['data']['id'] ?>"><i class="fa fa-cog"></i></a>
                                    <a href="<?= URLADMIN . '/index.php?op=administradores&accion=ver&borrar=' . $data['data']['id'] ?>"><i class="fa fa-trash"></i></a>
                                </td>
                                <input type="hidden" name="id" value="<?= $data["data"]["id"] ?>">
                            </form>
                        </tr>
                <?php

                    }
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
<?php
if (isset($_GET["borrar"])) {
    $id = $funciones->antihack_mysqli(isset($_GET["borrar"]) ? $_GET["borrar"] : '');
    $admin->set("id", $id);
    $admin->delete();
    $funciones->headerMove(URLADMIN . "/index.php?op=administradores");
}
?>