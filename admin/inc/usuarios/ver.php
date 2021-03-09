<?php
$usuario = new Clases\Usuarios();
$pedido = $funciones->antihack_mysqli(isset($_GET["pedido"]) ? $_GET["pedido"] : '0');
?>
    <div class="mt-20">
        <div class="col-lg-12 col-md-12">
            <h4>Usuarios
                <a class="btn btn-success pull-right" href="<?= URLADMIN ?>/index.php?op=usuarios&accion=agregar<?php if ($pedido == 1) {
                    echo '&pedido=1';
                } ?>">
                    AGREGAR
                    USUARIOS
                </a>
            </h4>
            <hr/>
            <input class="form-control" id="myInput" type="text" placeholder="Buscar..">
            <hr/>
            <?php
            if ($pedido == 1) {
                ?>
                <div class="alert alert-success" role="alert">
                    Seleccion un usuario para comenzar a armar el pedido o agrega un usuario nuevo.
                </div>
                <?php
            }
            ?>
            <table class="table  table-bordered  ">
                <thead>
                <th>Nombre</th>
                <th>Email</th>
                <th>Tipo</th>
                <th>Ajustes</th>
                </thead>
                <tbody>
                <?php
                $filter = array();
                $usuariosData = $usuario->list('', '', '');
                if (is_array($usuariosData)) {
                    foreach ($usuariosData as $data) {
                        ?>
                        <tr>
                            <td><?= mb_strtoupper($data['data']["nombre"]) . " " . mb_strtoupper($data['data']["apellido"]) ?></td>

                            <td><?= mb_strtolower($data['data']["email"]) ?></td>
                            <td>
                                <?php
                                if ($data['data']["minorista"] == 1) {
                                    echo "MINORISTA";
                                } else {
                                    echo "MAYORISTA";
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                if ($data['data']["estado"] == 1) {
                                    ?>
                                    <a class="btn btn-success"
                                       data-toggle="tooltip"
                                       data-placement="top"
                                       title="Activo"
                                       href="<?= URLADMIN . '/index.php?op=usuarios&cod=' . $data['data']['cod'] . '&active=0' ?>">
                                        <i class="fa fa-check-circle" aria-hidden="true"></i>
                                    </a>
                                    <?php
                                } else {
                                    ?>
                                    <a class="btn btn-primary"
                                       data-toggle="tooltip"
                                       data-placement="top"
                                       title="No activo"
                                       href="<?= URLADMIN . '/index.php?op=usuarios&cod=' . $data['data']['cod'] . '&active=1' ?>">
                                        <i class="fa fa-ban" aria-hidden="true"></i>
                                    </a>
                                    <?php
                                }
                                ?>
                                <span>
                            </span>
                                <a class="btn btn-warning"
                                   data-toggle="tooltip"
                                   data-placement="top"
                                   title="Ver Pedidos"
                                   href="<?= URLADMIN ?>/index.php?op=pedidos&accion=ver&usuario=<?= $data['data']["cod"] ?>">
                                    <i class="fa fa-list"></i></a>

                                <a class="btn btn-dark"
                                   data-toggle="tooltip"
                                   data-placement="top"
                                   title="Agregar Pedido"
                                   href="<?= URLADMIN ?>/index.php?op=pedidos&accion=agregar&usuario=<?= $data['data']["cod"] ?>">
                                    <i class="fa fa-plus"></i></a>

                                <a class="btn btn-info"
                                   data-toggle="tooltip"
                                   data-placement="top"
                                   title="Modificar"
                                   href="<?= URLADMIN ?>/index.php?op=usuarios&accion=modificar&cod=<?= $data['data']["cod"] ?>">
                                    <i class="fa fa-cog"></i></a>
                                <!--
                                <a class="btn btn-danger"
                                   data-toggle="tooltip"
                                   data-placement="top"
                                   title="Eliminar"
                                   href="<?= URLADMIN ?>/index.php?op=usuarios&accion=ver&borrar=<?= $data['data']["cod"] ?>">
                                    <i class="fa fa-trash"></i></a>
                                    -->
                            </td>
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
    $cod = $funciones->antihack_mysqli(isset($_GET["borrar"]) ? $_GET["borrar"] : '');
    $usuario->set("cod", $cod);
    $usuario->delete();
    $funciones->headerMove(URLADMIN . "/index.php?op=usuarios");
}
if (isset($_GET["active"])) {
    $estado = $funciones->antihack_mysqli(isset($_GET["active"]) ? $_GET["active"] : '');
    $usuario->set("cod", $funciones->antihack_mysqli(isset($_GET["cod"]) ? $_GET["cod"] : ''));
    $usuario->editSingle("estado", $estado);
    $funciones->headerMove(URLADMIN . "/index.php?op=usuarios");
}
?>