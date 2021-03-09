<?php
$pagos = new Clases\Pagos();
$data = $pagos->list('', '', '');
?>
    <div class="mt-20">
        <div class="col-lg-12 col-md-12">
            <h4>
                Pagos
                <a class="btn btn-success pull-right" href="<?= URLADMIN ?>/index.php?op=pagos&accion=agregar">
                    AGREGAR PAGOS
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
                if (is_array($data)) {
                    foreach ($data as $data_) {
                        echo "<tr>";
                        echo "<td>" . strtoupper($data_['data']["titulo"]) . "</td>";
                        echo "<td>";
                        echo '<a class="btn btn-info" data-toggle="tooltip" data-placement="top" title="Modificar" href="' . URLADMIN . '/index.php?op=pagos&accion=modificar&cod=' . $data_['data']["cod"] . '">
                        <i class="fa fa-cog"></i></a>';

                        echo '<a class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Eliminar" href="' . URLADMIN . '/index.php?op=pagos&accion=ver&borrar=' . $data_['data']["cod"] . '">
                        <i class="fa fa-trash"></i></a>';
                        echo "</td>";
                        echo "</tr>";
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
    $pagos->set("cod", $cod);
    $pagos->delete();
    $funciones->headerMove(URLADMIN . "/index.php?op=pagos");
}
?>