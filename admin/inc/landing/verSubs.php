<?php
$landing = new Clases\Landing();
$landingSubs = new Clases\LandingSubs();
$cod = isset($_GET["cod"]) ? $_GET["cod"] : '0';
$filter = array();
$landingRequestsArray = $landingSubs->list("landing_cod = '$cod'");
$landing->set("cod", $cod);
$landingData = $landing->view();
$landingSubs->set("landingCod", $cod);

$winner = $landingSubs->searchWinner();
if (isset($_POST["winner"])) {
    $limit = $funciones->antihack_mysqli(isset($_POST["winner"]) ? $_POST["winner"] : '');
    $ganador = $landingSubs->selectWinner($limit);
    foreach ($ganador as $key => $ganador_) {
        $landingSubs->set("id", $ganador_['id']);
        $landingSubs->set("ganador", $key + 1);
        $landingSubs->updateWinner();
    }
    $funciones->headerMove(URLADMIN . '/index.php?op=landing&accion=verSubs&cod=' . $cod);
}
if (isset($_POST["reset"])) {
    $landingSubs->resetWinner();
    $funciones->headerMove(URLADMIN . '/index.php?op=landing&accion=verSubs&cod=' . $cod);
}
?>
<div class="mt-20">
    <div class="col-lg-12 col-md-12">
        <h4>
            Peticiones
        </h4>
        <br>
        <?php
        if (!empty($winner)) {
            ?>
            <div class="alert alert-success text-center">
                <?php
                if (@count($winner) == 1) {
                    echo "<h2>Ganador/a:</h2><br>";
                } else {
                    echo "<h2>Ganadores/as:</h2><br>";
                }
                foreach ($winner as $winner_) {
                    ?>
                    <div class="inline-block mr-10 text-center">
                        <h5><?= $winner_['ganador'] ?>º</h5>
                        <b>Nombre: </b><?= $winner_['nombre'] . ' ', $winner_['apellido'] ?><br>
                        <b>Email: </b><?= $winner_['email'] ?><br>
                        <b>Celular: </b><?= $winner_['celular'] ?><br>
                        <b>DNI: </b><?= $winner_['dni'] ?><br>
                    </div>
                    <?php
                }
                ?>
                <div class="mt-10" style="text-align: right">
                    <form method="post">
                        <button name="reset" type="submit" class="btn btn-warning ml-10">
                            REINICIAR
                        </button>
                    </form>
                </div>
            </div>
            <?php
        }
        ?>
        <hr/>
        <div class="col-md-12 text-center">
            <?php
            if (empty($winner) && empty($landingRequestsArray)) {
                ?>
                <form method="post" class="inline-block">
                    <input type="hidden" name="winner" value="1">
                    <button name="winner1" type="submit" class="btn btn-success ">
                        SELECCIONAR 1 GANADOR
                    </button>
                </form>
                <form method="post" class="inline-block">
                    <input type="hidden" name="winner" value="2">
                    <button name="winner2" type="submit" class="btn btn-success ">
                        SELECCIONAR 2 GANADORES
                    </button>
                </form>
                <form method="post" class="inline-block">
                    <input type="hidden" name="winner" value="3">
                    <button name="winner3" type="submit" class="btn btn-success ">
                        SELECCIONAR 3 GANADORES
                    </button>
                </form>
                <form method="post" class="inline-block">
                    <input type="hidden" name="winner" value="4">
                    <button name="winner4" type="submit" class="btn btn-success ">
                        SELECCIONAR 4 GANADORES
                    </button>
                </form>
                <form method="post" class="inline-block">
                    <input type="hidden" name="winner" value="5">
                    <button name="winner5" type="submit" class="btn btn-success ">
                        SELECCIONAR 5 GANADORES
                    </button>
                </form>
                <?php
            }
            ?>
        </div>
        <br>
        <input class="form-control" id="myInput" type="text" placeholder="Buscar..">
        <hr/>
        <table class="table  table-bordered  ">
            <thead>
            <th>
                Landing
            </th>
            <th>
                Nombre
            </th>
            <th>
                Apellido
            </th>
            <th>
                Celular
            </th>
            <th>
                Email
            </th>
            <th>
                Dni
            </th>
            <th>
                Fecha
            </th>
            </thead>
            <tbody>
            <?php
            if (is_array($landingRequestsArray)) {
                for ($i = 0; $i < count($landingRequestsArray); $i++) {
                    echo "<tr>";
                    echo "<td>" . strtoupper($landingData['data']["titulo"]) . "</td>";
                    echo "<td>" . strtoupper($landingRequestsArray[$i]["nombre"]) . "</td>";
                    echo "<td>" . strtoupper($landingRequestsArray[$i]["apellido"]) . "</td>";
                    echo "<td>" . strtoupper($landingRequestsArray[$i]["celular"]) . "</td>";
                    echo "<td>" . strtoupper($landingRequestsArray[$i]["email"]) . "</td>";
                    echo "<td>" . strtoupper($landingRequestsArray[$i]["dni"]) . "</td>";
                    echo "<td>" . strtoupper($landingRequestsArray[$i]["fecha"]) . "</td>";
                    echo "</tr>";
                }
            }
            ?>
            </tbody>
        </table>
    </div>
</div>
