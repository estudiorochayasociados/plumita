<?php
$landing = new Clases\Landing();
$funciones = new Clases\PublicFunction();
$data = $landing->list('', '', '');
?>
    <div class="mt-20">
        <div class="col-lg-12 col-md-12">
            <h4>
                Landing
                <a class="btn btn-success pull-right" href="<?= URLADMIN ?>/index.php?op=landing&accion=agregar">
                    AGREGAR LANDING
                </a>
            </h4>
            <hr/>
            <input class="form-control" id="myInput" type="text" placeholder="Buscar..">
            <hr/>
            <table class="table  table-bordered">
                <thead>
                <th>
                    Título
                </th>
                <th>
                    Link de Campaña
                </th>
                <th>
                    Ajustes
                </th>
                </thead>
                <tbody>
                <?php
                if (is_array($data)) {
                    foreach ($data as $data_) {
                        $link = URL . "/landing/" . $funciones->normalizar_link($data_['data']["titulo"]) . "/" . $data_['data']["cod"];
                        echo "<tr>";
                        echo "<td>" . strtoupper($data_['data']["titulo"]) . "</td>";
                        echo "<td><a href='" . $link . "' target='_blank'>" . $link . "</a></td>";
                        echo "<td>";
                        echo '<a class="btn btn-info" data-toggle="tooltip" data-placement="top" title="Peticiones" href="' . URLADMIN . '/index.php?op=landing&accion=verSubs&cod=' . $data_['data']["cod"] . '">
                        <i class="fa fa-users"></i></a>';
                        echo '<a class="btn btn-info" data-toggle="tooltip" data-placement="top" title="Modificar" href="' . URLADMIN . '/index.php?op=landing&accion=modificar&cod=' . $data_['data']["cod"] . '">
                        <i class="fa fa-cog"></i></a>';

                        echo '<a class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Eliminar" href="' . URLADMIN . '/index.php?op=landing&accion=ver&borrar=' . $data_['data']["cod"] . '">
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
    $landing->set("cod", $cod);
    $landing->delete();
    $funciones->headerMove(URLADMIN . "/index.php?op=landing");
}
?>