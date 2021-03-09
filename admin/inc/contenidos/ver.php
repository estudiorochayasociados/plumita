<?php
$contenidos = new Clases\Contenidos();
$data = $contenidos->list('', '', '');
?>
<div class="mt-20">
    <div class="col-lg-12 col-md-12">
        <h4>
            Ver Contenidos
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
            if (is_array($data)) {
                foreach ($data as $contenido) {
                    ?>
                    <tr>
                        <td>
                            <?= mb_strtoupper($contenido['data']['titulo']) ?>
                        </td>
                        <td>
                            <a class="btn btn-info" data-toggle="tooltip" data-placement="top" title="Modificar" href="<?= URLADMIN . '/index.php?op=contenidos&accion=modificar&cod=' . $contenido['data']['cod'] ?>">
                                <i class="fa fa-cog"></i>
                            </a>
                            <?php if (in_array(1,$_SESSION["admin"]["rol"])) { ?>
                                <a class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Eliminar" href="<?= URLADMIN . '/index.php?op=contenidos&accion=ver&borrar=' . $contenido['data']['cod'] ?>">
                                <i class="fa fa-trash"></i>
                            </a>
                            <?php } ?>
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
$borrar = $funciones->antihack_mysqli(isset($_GET["borrar"]) ? $_GET["borrar"] : '');
if ($borrar != '') {
    $contenidos->set("cod", $borrar);
    $contenidos->delete();
    $funciones->headerMove(URLADMIN . "/index.php?op=contenidos");
}
?>

