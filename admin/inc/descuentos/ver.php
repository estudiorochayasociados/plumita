<?php
$descuento = new Clases\Descuentos();
$descuentos = $descuento->list("", "id DESC", "");
?>
    <div class="mt-20">
        <div class="col-lg-12 col-md-12">
            <h4>
                Descuentos
                <a class="btn btn-success pull-right" href="<?= URLADMIN ?>/index.php?op=descuentos&accion=agregar">
                    AGREGAR DESCUENTO
                </a>
            </h4>
            <hr/>
            <input class="form-control" id="myInput" type="text" placeholder="Buscar..">
            <hr/>
            <table class="table  table-bordered  ">
                <thead>
                <th>
                    Titulo
                </th>
                <th>
                    Código Descuento
                </th>
                <th>
                    Monto
                </th>
                <th>
                    Ajustes
                </th>
                </thead>
                <tbody>
                <?php
                if (is_array($descuentos)) {
                    foreach ($descuentos as $descuento_) {

                        if ($descuento_["data"]["tipo"] == 0) {
                            $monto = '$' . $descuento_["data"]["monto"];
                        } elseif ($descuento_["data"]["tipo"] == 1) {
                            $monto = '%' . $descuento_["data"]["monto"];
                        }

                        ?>
                        <tr>
                            <td> <?= mb_strtoupper($descuento_["data"]["titulo"]) ?> </td>
                            <td> <?= mb_strtoupper($descuento_["data"]["cod"]) ?> </td>
                            <td><?= mb_strtoupper($monto) ?></td>
                            <td>
                                <a class="btn btn-info"
                                   data-toggle="tooltip"
                                   data-placement="top"
                                   title="Modificar"
                                   href="<?= URLADMIN ?>/index.php?op=descuentos&accion=modificar&cod=<?= $descuento_["data"]["cod"] ?>">
                                    <i class="fa fa-cog"></i>
                                </a>

                                <a class="btn btn-danger"
                                   data-toggle="tooltip"
                                   data-placement="top"
                                   title="Eliminar"
                                   href="<?= URLADMIN ?>/index.php?op=descuentos&accion=ver&borrar=<?= $descuento_["data"]["cod"] ?>">
                                    <i class="fa fa-trash"></i>
                                </a>
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
    $descuento->set("cod", $cod);
    $descuento->delete();
    $funciones->headerMove(URLADMIN . "/index.php?op=descuentos");
}
?>