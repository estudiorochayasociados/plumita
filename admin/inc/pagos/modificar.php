<?php
$pagos = new Clases\Pagos();
$config = new Clases\Config();
$cod = $funciones->antihack_mysqli(isset($_GET["cod"]) ? $_GET["cod"] : '');
$pagos->set("cod", $cod);
$pagos_ = $pagos->view();
$payments = $config->listPayment();

if (isset($_POST["agregar"])) {
    $pagos->set("cod", $pagos_['data']["cod"]);
    $pagos->set("titulo", $funciones->antihack_mysqli(isset($_POST["titulo"]) ? $_POST["titulo"] : ''));
    $pagos->set("leyenda", $funciones->antihack_mysqli(isset($_POST["leyenda"]) ? $_POST["leyenda"] : ''));
    $pagos->set("estado", $funciones->antihack_mysqli(isset($_POST["estado"]) ? $_POST["estado"] : ''));
    $pagos->set("tipo", $funciones->antihack_mysqli(isset($_POST["tipo"]) ? $_POST["tipo"] : ''));
    $aumento = $funciones->antihack_mysqli(isset($_POST["aumento"]) ? $_POST["aumento"] : '');
    switch ($aumento) {
        case 0:
            $pagos->set("aumento", "");
            $pagos->set("disminuir", "");
            break;
        default:
            if ($aumento > 0) {
                $pagos->set("aumento", $aumento);
                $pagos->set("disminuir", "");
            } else {
                $pagos->set("disminuir", abs($aumento));
                $pagos->set("aumento", "");
            }
            break;
    }
    $pagos->set("defecto", $funciones->antihack_mysqli(isset($_POST["defecto"]) ? $_POST["defecto"] : ''));
    $pagos->edit();
    $funciones->headerMove(URLADMIN . "/index.php?op=pagos");
}
?>

<div class="col-md-12 ">
    <h4>Pagos</h4>
    <hr/>
    <form method="post" class="row">
        <label class="col-md-9">Método de pago:<br/>
            <input type="text" name="titulo" value="<?= $pagos_['data']["titulo"] ? $pagos_['data']["titulo"] : '' ?>" required>
        </label>
        <label class="col-md-3">
            Estado
            <select name="estado" class="form-control" required>
                <option value="1" <?php if ($pagos_['data']['estado'] == 1) {
                    echo "selected";
                } ?>>
                    Activo
                </option>
                <option value="0" <?php if ($pagos_['data']['estado'] == 0) {
                    echo "selected";
                } ?>>
                    Desactivado
                </option>
            </select>
        </label>
        <label class="col-md-12">Descripción del método de pago:<br/>
            <textarea name="leyenda"><?= $pagos_['data']["leyenda"] ? $pagos_['data']["leyenda"] : '' ?></textarea>
        </label>
        <label class="col-md-5">
            Tipo de pago online:
            <select name="tipo" class="form-control">
                <option value="" <?php if ($pagos_['data']['tipo'] == '') {
                    echo "selected";
                } ?>>
                    --- Sin elegir ---
                </option>
                <?php
                if (!empty($payments)) {
                    foreach ($payments as $payment) {
                        ?>
                        <option value="<?= $payment['data']['id']; ?>" <?php if ($pagos_['data']['tipo'] == $payment['data']['id']) {
                            echo "selected";
                        } ?>>
                            <?= $payment['data']['empresa']; ?>
                        </option>
                        <?php
                    }
                }
                ?>
            </select>
        </label>
        <label class="col-md-5">
            Defecto:
            <select name="defecto" class="form-control" required>
                <option value="0" <?php if ($pagos_['data']['defecto'] == 0) {
                    echo "selected";
                } ?>>Carrito no cerrado
                </option>
                <option value="1"<?php if ($pagos_['data']['defecto'] == 1) {
                    echo "selected";
                } ?>>Pendiente
                </option>
                <option value="2"<?php if ($pagos_['data']['defecto'] == 2) {
                    echo "selected";
                } ?>>Exitoso
                </option>
                <option value="3"<?php if ($pagos_['data']['defecto'] == 3) {
                    echo "selected";
                } ?>>Enviado
                </option>
                <option value="4"<?php if ($pagos_['data']['defecto'] == 4) {
                    echo "selected";
                } ?>>Rechazado
                </option>
            </select>
        </label>
        <?php
        if ($pagos_['data']['aumento'] != NULL) {
            $aumento = $pagos_['data']['aumento'];
        } else {
            if ($pagos_['data']['disminuir']!=NULL) {
                $aumento = -$pagos_['data']['disminuir'];
            } else {
                $aumento = 0;
            }
        }
        ?>
        <label class="col-md-2">
            Aumento o Disminución (%)<br/>
            <input data-suffix="%" value="<?= $aumento ?>" min="-100" max="100" type="number" name="aumento" onkeydown="return (event.keyCode!=13);"/>
        </label>
        <div class="clearfix"></div>
        <br/>
        <div class="col-md-12">
            <input type="submit" class="btn btn-primary" name="agregar" value="Modificar Pago"/>
        </div>
    </form>
</div>
<script>
    $("input[type='number']").inputSpinner()
</script>
