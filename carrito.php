<?php
require_once "Config/Autoload.php";
Config\Autoload::run();
$template = new Clases\TemplateSite();
$f = new Clases\PublicFunction();
$carrito = new Clases\Carrito();
$usuario = new Clases\Usuarios();
$descuento = new Clases\Descuentos();
$checkout = new Clases\Checkout();

$usuarioData = $usuario->viewSession();
$descuentos = $descuento->list("", "", "");
$refreshCartDescuento = $descuento->refreshCartDescuento($carrito->return(), $usuarioData);
$carro = $carrito->return();
$remover = $f->antihack_mysqli(isset($_GET["remover"]));
(!empty($remover)) ? $carrito->delete($_GET["remover"]) . $f->headerMove(URL . "/carrito") : '';

$template->set("title", "Carrito de compra | " . TITULO);
$template->set("description", "Carrito de compra " . TITULO);
$template->set("keywords", "Carrito de compra " . TITULO);
$template->themeInit();


?>
<section class="solid_banner_area">
    <div class="container">
        <div class="solid_banner_inner navegador">
            <h3>Carrito</h3>
            <ul>
                <li><a href="<?= URL ?>/index">Inicio</a></li>
                <li><a href="<?= URL ?>/productos">Carrito</a></li>
            </ul>
        </div>
    </div>
</section>
<div class="container">
    <h3>TU CARRITO</h3>
    <hr />
    <div class="row">
        <div class="col-md-12">
            <table class="table table-striped ">
                <thead class="thead-dark ">
                    <th class="text-left">Nombre</th>
                    <th class="text-left hidden-xs hidden-sm">Cantidad</th>
                    <th class="text-left">Precio u.</th>
                    <th class="text-left">Total</th>
                    <th class="text-left"></th>
                </thead>
                <?php
                $i = 0;
                $precio = 0;
                foreach ($carro as $key => $carroItem) {
                    $precio += ($carroItem["precio"] * $carroItem["cantidad"]);
                    $opciones = @implode(" - ", $carroItem["opciones"]);
                    if ($carroItem["id"] == "Envio-Seleccion" || $carroItem["id"] == "Metodo-Pago") {
                        $clase = "text-bold";
                        $none = "hidden";
                    } else {
                        $clase;
                        $none = "";
                    }
                ?>
                    <tr>
                        <td>
                            <b><?= mb_strtoupper($carroItem["titulo"]); ?></b>
                            <?php
                            if ($carroItem['descuento']['status']) {
                                foreach ($carroItem['descuento']['products'] as $itemDescuento) {
                                    echo '<br> - <span class="item-titulo-descuento">' . $itemDescuento['titulo'] . ' <b class="item-monto-descuento">' . $itemDescuento['monto'] . '</b></span>';
                                }
                            }
                            ?>
                            <br>
                            <?php
                            if (is_array($carroItem['opciones'])) {
                                if (isset($carroItem['opciones']['texto'])) {
                                    echo $carroItem['opciones']['texto'];
                                }
                            }
                            ?>
                            <?php if (!$carroItem['descuento']['status']) { ?>
                                <span class="amount d-md-none <?= $none ?>">Cantidad: <?= $carroItem["cantidad"]; ?></span>
                            <?php } ?>
                        </td>
                        <td class="hidden-xs hidden-sm">
                            <?php if (!$carroItem['descuento']['status']) { ?>
                                <span class="amount <?= $none ?>"><?= $carroItem["cantidad"]; ?></span>
                            <?php } ?>
                        </td>
                        <td>
                            <?php if (!$carroItem['descuento']['status']) { ?>
                                <span class="amount <?= $none ?>"><?= "$" . $carroItem["precio"]; ?></span>
                            <?php } ?>
                        </td>
                        <td>
                            <?php
                            if ($carroItem["precio"] != 0) {
                                echo "$" . ($carroItem["precio"] * $carroItem["cantidad"]);
                            } else {
                                //echo "Sin recargo";
                            }
                            ?>
                        </td>
                        <td>
                            <a href="<?= URL ?>/carrito.php?remover=<?= $key ?>">
                                <i class="fa fa-times" aria-hidden="true"></i>
                            </a>
                        </td>
                    </tr>
                <?php
                    $i++;
                }
                ?>
            </table>

            <div class="col-md-12">
                <?php
                if ($descuentos) {
                    if (isset($_POST["btn_codigo"])) {
                        $codigoDescuento = $f->antihack_mysqli(isset($_POST["codigoDescuento"]) ? $_POST["codigoDescuento"] : '');
                        $descuento->set("cod", $codigoDescuento);

                        $response = $descuento->addCartDescuento($carro, $usuarioData);
                        if ($response['status']['applied']) {
                            $f->headerMove(URL . "/carrito");
                        } else {
                            echo "<div class='alert alert-danger'>" . $response['status']['error']['errorMsg'] . "</div>";
                        }
                    }
                }
                ?>
                <hr>
                <form method="post" class="row">
                    <div class="col-md-6 text-center">
                        <p class="mt-7"><b>¿Tenés algún código de descuento para tus compras?</b></p>
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="codigoDescuento" class="form-control" placeholder="CÓDIGO DE DESCUENTO">
                        <br class="d-md-none">
                    </div>
                    <div class="col-md-2">
                        <input type="submit" value="USAR CÓDIGO" name="btn_codigo" class="btn btn-default" />
                    </div>
                </form>

            </div>
            <div class="row mb-5">
                <div class="col-md-12 col-sm-12">
                    <div class="row mb-10">
                        <div class="col-md-12 col-sm-12 mb-15" id="buy">
                            <div class="cart_totals float-md-right text-md-right text-center">
                                <hr>
                                <div class="mb-20">
                                    <strong class="text-uppercase fs-36">Total: $<?= number_format($carrito->totalPrice(), "2", ",", "."); ?></strong>
                                </div>
                                <?php
                                if ($carrito->totalPrice() != 0) {
                                    if (!empty($_SESSION['usuarios'])) {
                                        if (empty($_SESSION['stages'])) {
                                            $checkout->initial('USER', $_SESSION['usuarios']['cod']);
                                        }
                                ?>
                                        <div class="mt-20 wc-proceed-to-checkout">
                                            <a class="btn btn-success fs-20 btn-block btn-lg" href="<?= URL ?>/checkout/shipping">
                                                <i class="fa fa-check"></i> Finalizar Compra
                                            </a>
                                        </div>
                                    <?php
                                    } else {
                                        if (empty($_SESSION['stages'])) {
                                            $checkout->initial('GUEST', '');
                                        }
                                    ?>
                                        <div class="mt-20">
                                            <a class="btn fs-20 btn-block btn-success btn-lg" href="<?= URL ?>/login">
                                                <i class="fa fa-check"></i> Finalizar Compra
                                            </a>
                                        </div>
                                    <?php
                                    }
                                } else {
                                    ?>
                                    <div class="mt-20">
                                        <div class="alert alert-danger">Ups! Tu carro tiene un precio de $0</div>
                                    </div>
                                <?php
                                }
                                ?>
                            </div>
                            <hr>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$template->themeEnd();

if (!empty($_SESSION['latest'])) {
?>
    <script>
        success('<?= $_SESSION['latest'] ?>');
    </script>
<?php
    $_SESSION['latest'] = '';
}

if (!empty($error)) {
?>
    <script>
        $(document).ready(function() {
            alertSide('<?= $error ?>');
        });
    </script>
<?php
}
?>