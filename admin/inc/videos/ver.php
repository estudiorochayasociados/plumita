<?php
$video = new Clases\Videos();
$videos = $video->list('', '', '');
?>
<div class="mt-20">
    <div class="col-lg-12 col-md-12">
        <h4>
            Videos
            <a class="btn btn-success pull-right" href="<?= URLADMIN ?>/index.php?op=videos&accion=agregar">
                AGREGAR VIDEOS
            </a>
        </h4>
        <hr/>
        <input class="form-control" id="myInput" type="text" placeholder="Buscar..">
        <hr/>
        <table class="table  table-bordered  ">
            <thead>
            <th width="50%">
                Título
            </th>
            <th width="10%">
                Ajustes
            </th>
            </thead>
            <tbody>
            <?php
            if (is_array($videos)) {
                foreach ($videos as $data) {
                    echo "<tr>";
                    echo "<td>" . strtoupper($data['data']["titulo"]) . "</td>";
                    echo "<td>";
                    echo '<a class="btn btn-info" data-toggle="tooltip" data-placement="top" title="Modificar" href="' . URLADMIN . '/index.php?op=videos&accion=modificar&cod=' . $data['data']["cod"] . '">
                        <i class="fa fa-cog"></i></a>';

                    echo '<a class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Eliminar" href="' . URLADMIN . '/index.php?op=videos&accion=ver&borrar=' . $data['data']["cod"] . '">
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
    $video->set("cod", $funciones->antihack_mysqli(isset($_GET["borrar"]) ? $_GET["borrar"] : ''));
    $video->delete();
    $funciones->headerMove(URLADMIN . "/index.php?op=videos");
}
?>

