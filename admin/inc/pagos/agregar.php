<?php
$pagos = new Clases\Pagos();
$config = new Clases\Config();
$payments = $config->listPayment();
if (isset($_POST["agregar"])) {
    $count = 0;
    $cod = substr(md5(uniqid(rand())), 0, 10);
    $pagos->set("cod", $cod);
    $pagos->set("titulo", isset($_POST["titulo"]) ? $funciones->antihack_mysqli($_POST["titulo"]) : '');
    $pagos->set("leyenda", isset($_POST["leyenda"]) ? $funciones->antihack_mysqli($_POST["leyenda"]) : '');
    $pagos->set("estado", isset($_POST["estado"]) ? $funciones->antihack_mysqli($_POST["estado"]) : '');
    $pagos->set("tipo", isset($_POST["tipo"]) ? $funciones->antihack_mysqli($_POST["tipo"]) : '');
    $aumento = isset($_POST["aumento"]) ? $funciones->antihack_mysqli($_POST["aumento"]) : '';
    switch ($aumento) {
        case 0:
            $pagos->set("aumento", 0);
            $pagos->set("disminuir", 0);
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
    $pagos->add();
    $funciones->headerMove(URLADMIN . "/index.php?op=pagos");
}
?>
<div class="col-md-12 ">
    <h4>Pagos</h4>
    <hr/>
    <form method="post" class="row">
        <label class="col-md-9">Método de pago:<br/>
            <input type="text" name="titulo" required>
        </label>
        <label class="col-md-3">
            Estado
            <select name="estado" class="form-control" required>
                <option value="1">Activo</option>
                <option value="0">Desactivado</option>
            </select>
        </label>
        <label class="col-md-12">Descripción del método de pago:<br/>
            <textarea name="leyenda"></textarea>
        </label>
        <label class="col-md-5">
            Tipo de pago online:
            <select name="tipo" class="form-control">
                <option value="" disabled selected>--- Sin elegir ---</option>
                <?php
                if (!empty($payments)) {
                    foreach ($payments as $payment) {
                        ?>
                        <option value="<?= $payment['data']['id']; ?>"><?= $payment['data']['empresa']; ?></option>
                        <?php
                    }
                }
                ?>
            </select>
        </label>
        <label class="col-md-5">
            Defecto:
            <select name="defecto" class="form-control" required>
                <option value="0">Carrito no cerrado</option>
                <option value="1">Pendiente</option>
                <option value="2">Exitoso</option>
                <option value="3">Enviado</option>
                <option value="4">Rechazado</option>
            </select>
        </label>
        <label class="col-md-2">
            Aumento o Disminución (%)<br/>
            <input data-suffix="%" value="0" min="-100" max="100" type="number" name="aumento" onkeydown="return (event.keyCode!=13);"/>
        </label>
        <div class="clearfix"></div>
        <br/>
        <div class="col-md-12">
            <input type="submit" class="btn btn-primary" name="agregar" value="Crear Pago"/>
        </div>
    </form>
</div>
<script>
    $("input[type='number']").inputSpinner()
</script>
