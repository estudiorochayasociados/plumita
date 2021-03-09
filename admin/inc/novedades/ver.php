<?php
$novedad = new Clases\Novedades();
$novedades = $novedad->list("", "id DESC", "");
?>
    <div class="mt-20">
        <div class="col-lg-12 col-md-12">
            <h4>
                Novedades
                <a class="btn btn-success pull-right" href="<?= URLADMIN ?>/index.php?op=novedades&accion=agregar">
                    AGREGAR NOVEDADES
                </a>
            </h4>
            <hr/>
            <input class="form-control" id="myInput" type="text" placeholder="Buscar..">
            <hr/>
            <table class="table  table-bordered  ">
                <thead>
                <th>
                    Título
                </th>
                <th>
                    Ajustes
                </th>
                </thead>
                <tbody>
                <?php
                if (is_array($novedades)) {
                    foreach ($novedades as $novedad_) {
                        ?>
                        <tr>
                            <td> <?= mb_strtoupper($novedad_["data"]["titulo"]) ?> </td>
                            <td>
                                <a class="btn btn-info"
                                   data-toggle="tooltip"
                                   data-placement="top"
                                   title="Modificar"
                                   href="<?= URLADMIN ?>/index.php?op=novedades&accion=modificar&cod=<?= $novedad_["data"]["cod"] ?>">
                                    <i class="fa fa-cog"></i>
                                </a>

                                <a class="btn btn-danger"
                                   data-toggle="tooltip"
                                   data-placement="top"
                                   title="Eliminar"
                                   href="<?= URLADMIN ?>/index.php?op=novedades&accion=ver&borrar=<?= $novedad_["data"]["cod"] ?>">
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
    $novedad->set("cod", $cod);
    $novedad->delete();
    $funciones->headerMove(URLADMIN . "/index.php?op=novedades");
}
?>