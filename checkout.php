<?php
require_once "Config/Autoload.php";
Config\Autoload::run();
$template = new Clases\TemplateSite();
$template->set("title", TITULO);
$template->set("description", "Finalizá tu compra");
$template->set("keywords", "");
$template->set("favicon", LOGO);
$template->themeInitStages();
$funciones = new Clases\PublicFunction();
$checkout = new Clases\Checkout();
$carrito = new Clases\Carrito();
$config = new Clases\Config();

$captchaData = $config->viewCaptcha();
$op = isset($_GET["op"]) ? $_GET["op"] : '';
if (empty($_SESSION['stages'])) {
    $funciones->headerMove(URL . '/carrito');
} else {
    if ($_SESSION['stages']['cod'] != $_SESSION['cod_pedido']) {
        $funciones->headerMove(URL . '/carrito');
    }
}

$progress = $checkout->progress();
?>
<div class="checkout-estudiorocha">
    <div class="login-register-section section pt-20  pb-70 pb-lg-50 pb-md-40 pb-sm-30 pb-xs-20 ">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-<?= ($op != 'detail') ? 8 : 12 ?>">
                    <ul class="progress-indicator" style="width: 100%">
                        <li class="<?= ($progress["stage-1"]) ? "completed" : ''; ?>">
                            <span class="bubble"></span>
                            <?= ($progress["stage-1"]) ? "<a href='" . URL . "/checkout/shipping'> ENVIO </a>" : 'ENVIO'; ?>
                        </li>

                        <li class="<?= ($progress["stage-1"] && $progress["stage-2"]) ? "completed" : ''; ?>">
                            <span class="bubble"></span>
                            <?= ($progress["stage-1"] && $progress["stage-2"]) ? "<a href='" . URL . "/checkout/billing'> FACTURACIÓN </a>" : 'FACTURACIÓN'; ?>
                        </li>

                        <li class="<?= ($progress["stage-1"] && $progress["stage-2"]) ? "completed" : ''; ?>">
                            <span class="bubble"></span>
                            <?= ($progress["stage-1"] && $progress["stage-2"] && $progress["stage-3"]) ? "<a href='" . URL . "/checkout/payment'> PAGO </a>" : 'PAGO'; ?>
                        </li>

                        <li class="<?= ($progress["stage-1"] && $progress["stage-2"] && $progress["stage-3"]) ? "completed" : ''; ?>">
                            <span class="bubble"></span>
                            <?= ($progress["stage-1"] && $progress["stage-2"] && $progress["stage-3"]) ? "<a href='" . URL . "/checkout/detail'> DETALLE </a>" : 'DETALLE'; ?>
                        </li>
                    </ul>
                    <?php
                    if ($op != '') {
                        if (!empty($progress)) {
                            include("assets/inc/checkout/" . $op . ".php");
                        } else {
                            include("assets/inc/checkout/shipping.php");
                        }
                    } else {
                        $funciones->headerMove(URL . '/carrito');
                    }
                    ?>
                </div>
                <?php
                if ($op != 'detail') {
                ?>
                    <div class="col-md-4 hidden-xs hidden-sm">
                        <ul class="progress-indicator" style="width: 100%">
                            <li class="completed">
                                <span class="bubble"></span>
                                TU COMPRA
                            </li>
                        </ul>
                        <?php include("assets/inc/checkout/carrito.php"); ?>
                    </div>
                <?php
                }
                ?>
            </div>
        </div>
    </div>
</div>
<div class="height-200"></div>
<?php
$template->themeEndStages();
?>
<script src="<?= URL ?>/assets/js/services/checkout.js"></script>
<script src="<?= URL ?>/assets/js/services/email.js"></script>

<?php

if ($op == 'detail') {
?>
    <script>
        sendBuy('<?= URL ?>', '<?= $_SESSION['cod_pedido'] ?>')
    </script>
<?php
    if ($_SESSION['stages']['status'] == 'CLOSED') {
        $carrito->destroy();
        $checkout->destroy();
        unset($_SESSION["cod_pedido"]);
        if (!empty($_SESSION['invitado'])) {
            if ($_SESSION["invitado"] == 1) {
                unset($_SESSION["usuarios"]);
            }
        }
    }
}
?>