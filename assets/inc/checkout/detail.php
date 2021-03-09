<?php
$pedidos = new Clases\Pedidos();
$usuarios = new Clases\Usuarios();
$producto = new Clases\Productos();
$funciones = new Clases\PublicFunction();
$checkout = new Clases\Checkout();
$detalleCombinacion = new Clases\DetalleCombinaciones();
$googleAnalyticsProduct = ''; //gtags armar metodo
$messageDetail = isset($_GET["message"]) ? $funciones->antihack_mysqli($_GET["message"]) : '';
if (isset($_SESSION['stages'])) {
    $pedidos->set("cod", $_SESSION['cod_pedido']);
    $pedido_info = $pedidos->view();
    $usuarios->cod = $_SESSION["usuarios"]["cod"];
    $usuario_data = $usuarios->view();

    switch ($pedido_info['data']["estado"]) {
        case 0:
            $estado = "CARRITO NO CERRADO";
            break;
        case 1:
            $estado = "PENDIENTE";
            break;
        case 2:
            $estado = "APROBADO";
            break;
        case 3:
            $estado = "ENVIADO";
            break;
        case 4:
            $estado = "RECHAZADO";
            break;
    }
    $carro = $carrito->return();
    if ($_SESSION['stages']['status'] == 'CLOSED') {
?>
        <div class="section pt-50  pb-70 pb-lg-50 pb-md-40 pb-sm-30 pb-xs-20">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <div class="customer-login-register register-pt-0">
                            <form id="payment-f" method="post">
                                <div class="form-register-title">
                                    <h2>COMPRA FINALIZADA</h2>
                                    <h4>CÓDIGO: <?= $_SESSION['cod_pedido'] ?></h4>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <hr>
                                            <?= ($messageDetail) ? "<div class='alert alert-danger'><b>DETALLE DEL PAGO</b>: " . $messageDetail . "</div>" : '';  ?>
                                            <b>ESTADO:</b> <?= $estado ?><br />
                                            <b>MÉTODO DE PAGO:</b> <?= mb_strtoupper($pedido_info['data']["tipo"]); ?><br />
                                            <?php
                                            if (!empty($pedido_info['data']['detalle'])) {
                                                $detalle = json_decode($pedido_info['data']['detalle'], true);
                                                if (!empty($detalle['leyenda'])) {
                                                    echo "<b>DESCRIPCIÓN DEL PAGO: </b>" . $detalle['leyenda'] . "<br/>";
                                                }
                                                if (!empty($detalle['descuento'])) {
                                                    echo "<b>SE UTILIZÓ EL CÓDIGO DE DESCUENTO: </b>" . $detalle['descuento'];
                                                }
                                                if (!empty($detalle['link'])) {
                                                    echo "<b>URL PARA PAGAR: </b><a href='" . $detalle['link'] . "' target='_blank'>CLICK AQUÍ</a>";
                                                }
                                            ?>
                                                <div class="row mb-15">
                                                    <div class="col-md-6">
                                                        <hr>
                                                        <b>INFORMACIÓN DE ENVIO</b>
                                                        <br>
                                                        <p class='mb-0 fs-13'><b>Tipo: </b><?= $detalle['envio']['tipo'] ?></p>
                                                        <p class='mb-0 fs-13'><b>Nombre: </b><?= $detalle['envio']['nombre'] ?></p>
                                                        <p class='mb-0 fs-13'><b>Apellido: </b><?= $detalle['envio']['apellido'] ?></p>
                                                        <p class='mb-0 fs-13'><b>Email: </b><?= $detalle['envio']['email'] ?></p>
                                                        <p class='mb-0 fs-13'><b>Provincia: </b><?= $detalle['envio']['provincia'] ?></p>
                                                        <p class='mb-0 fs-13'><b>Localidad: </b><?= $detalle['envio']['localidad'] ?></p>
                                                        <p class='mb-0 fs-13'><b>Dirección: </b><?= $detalle['envio']['direccion'] ?></p>
                                                        <p class='mb-0 fs-13'><b>Teléfono: </b><?= $detalle['envio']['telefono'] ?></p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <hr>
                                                        <b>INFORMACIÓN DE FACTURACIÓN</b>
                                                        <br>
                                                        <p class='mb-0 fs-13'><b>Nombre: </b><?= $detalle['pago']['nombre'] ?></p>
                                                        <p class='mb-0 fs-13'><b>Apellido: </b><?= $detalle['pago']['apellido'] ?></p>
                                                        <p class='mb-0 fs-13'><b>Email: </b><?= $detalle['pago']['email'] ?></p>
                                                        <p class='mb-0 fs-13'><b>DNI/CUIT: </b><?= $detalle['pago']['dni'] ?></p>
                                                        <p class='mb-0 fs-13'><b>Provincia: </b><?= $detalle['pago']['provincia'] ?></p>
                                                        <p class='mb-0 fs-13'><b>Localidad: </b><?= $detalle['pago']['localidad'] ?></p>
                                                        <p class='mb-0 fs-13'><b>Dirección: </b><?= $detalle['pago']['direccion'] ?></p>
                                                        <p class='mb-0 fs-13'><b>Teléfono: </b><?= $detalle['pago']['telefono'] ?></p>
                                                        <?php
                                                        if ($detalle['pago']['factura']) {
                                                            echo "<p class='mb-0 fs-13'><b>Factura A al CUIT: </b>" . $detalle['pago']['dni'] . "</p>";
                                                        }
                                                        ?>
                                                    </div>
                                                </div>
                                            <?php
                                            }
                                            ?>
                                            <table class="table table-hover text-left table-striped">
                                                <thead class="thead-dark">
                                                    <th>
                                                        <b>PRODUCTO</b>
                                                    </th>
                                                    <th class="hidden-xs-down">
                                                        <b>PRECIO UNITARIO</b>
                                                    </th>
                                                    <th class="hidden-xs-down">
                                                        <b>CANTIDAD</b>
                                                    </th>
                                                    <th>
                                                        <b>TOTAL</b>
                                                    </th>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $precio = 0;
                                                    foreach ($carro as $carroItem) {
                                                        $precio += ($carroItem["precio"] * $carroItem["cantidad"]);
                                                        if ($carroItem["id"] == "Envio-Seleccion" || $carroItem["id"] == "Metodo-Pago") {
                                                            $clase = "text-bold";
                                                            $none = "hidden";
                                                        } else {
                                                            $producto->set("cod", $carroItem['id']);
                                                            $producto_data = $producto->view();
                                                            if ($pedido_info['data']["estado"] == 1 || $pedido_info['data']["estado"] == 2 || $pedido_info['data']["estado"] == 3) {
                                                                if (is_array($carroItem['opciones'])) {
                                                                    if (isset($carroItem['opciones']['combinacion'])) {
                                                                        $detalleCombinacion->set("codCombinacion", $carroItem['opciones']['combinacion']['cod_combinacion']);
                                                                        $detalleCombinacion->editSingle("stock", $carroItem['opciones']['combinacion']['stock'] - $carroItem['cantidad']);
                                                                    } else {
                                                                        $producto->editSingle("stock", $producto_data['data']['stock'] - $carroItem['cantidad']);
                                                                    }
                                                                } else {
                                                                    $producto->editSingle("stock", $producto_data['data']['stock'] - $carroItem['cantidad']);
                                                                }
                                                            }
                                                            $clase = '';
                                                            $none = '';
                                                        }
                                                    ?>
                                                        <tr class="<?= $clase ?>">
                                                            <td>
                                                                <div class="media hidden-sm-down">
                                                                    <div class="media-body">
                                                                        <?= mb_strtoupper($carroItem["titulo"]); ?>
                                                                        <?php if (isset($carroItem["descuento"]["monto"])) { ?>
                                                                            <b class="descuento-monto">$<?= $carroItem["descuento"]["monto"]; ?></b>
                                                                        <?php } ?>
                                                                        <br>
                                                                        <?php
                                                                        if (is_array($carroItem['opciones'])) {
                                                                            if (isset($carroItem['opciones']['texto'])) {
                                                                                echo $carroItem['opciones']['texto'];
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                </div>
                                                                <div class="hidden-md-up text-left">
                                                                    <?= mb_strtoupper($carroItem["titulo"]); ?>
                                                                    <?php if (isset($carroItem["descuento"]["monto"])) { ?>
                                                                        <b class="descuento-monto">$<?= $carroItem["descuento"]["monto"]; ?></b>
                                                                    <?php } ?>
                                                                    <?php
                                                                    if (is_array($carroItem['opciones'])) {
                                                                        if (isset($carroItem['opciones']['texto'])) {
                                                                            echo "<p>" . $carroItem['opciones']['texto'] . "</p>";
                                                                        }
                                                                    }
                                                                    ?>
                                                                    <p class="<?= $none ?>">
                                                                        Precio: <?= "$" . $carroItem["precio"]; ?>
                                                                        <?php if (isset($carroItem["descuento"]["precio-antiguo"])) { ?>
                                                                            <span class="descuento-precio">$<?= $carroItem["descuento"]["precio-antiguo"]; ?></span>
                                                                        <?php } ?>
                                                                    </p>
                                                                    <p class="<?= $none ?>">Cantidad: <?= $carroItem["cantidad"]; ?></p>
                                                                </div>
                                                            </td>
                                                            <td class="hidden-xs-down">
                                                                <p class="<?= $none ?>">
                                                                    <?= "$" . $carroItem["precio"]; ?>
                                                                    <?php if (isset($carroItem["descuento"]["precio-antiguo"])) { ?>
                                                                        <span class="descuento-precio">$<?= $carroItem["descuento"]["precio-antiguo"]; ?></span>
                                                                    <?php } ?>
                                                                </p>
                                                            </td>
                                                            <td class="hidden-xs-down">
                                                                <p class="<?= $none ?>"><?= $carroItem["cantidad"]; ?></p>
                                                            </td>
                                                            <?php
                                                            if ($carroItem["precio"] != 0) {
                                                            ?>
                                                                <td><?= "$" . ($carroItem["precio"] * $carroItem["cantidad"]); ?></td>
                                                            <?php
                                                            } else {
                                                                echo "<td>Sin recargo</td>";
                                                            }
                                                            ?>
                                                        </tr>
                                                    <?php

                                                        $googleAnalyticsProduct .= "{
                                                    'name': '" . $producto_data['data']['titulo'] . "', 
                                                    'id': '" . $producto_data['data']['cod'] . "',
                                                    'price': '" . number_format($carroItem["precio"], "2", ".", "") . "',
                                                    'brand': '',
                                                    'category': '" . $producto_data['category']['data']['titulo'] . "',
                                                    'variant': '',
                                                    'quantity': " . $carroItem['cantidad'] . ",
                                                    'coupon': ''
                                                    },";
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                            <div class="mt-10 pull-right">
                                                <h3>TOTAL: $<?= number_format($precio, "2", ",", ".") ?></h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div id="btn-payment-d" class="col-md-12 col-xs-12 mt-10 mb-50">
                                            <a class="btn btn-success text-center" href="<?= URL ?>" id="btn-payment-1">
                                                Volver a la página principal
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            var precioGoogleAds = <?= number_format($precio, "2", ".", "") ?>;
            var pedidoGoogleAds = "<?= $_SESSION["cod_pedido"] ?>";
            var monedaGoogleAds = "<?= $_SESSION["cod_pedido"] ?>";

            dataLayer.push({
                'event': 'transaction',
                'ecommerce': {
                    'purchase': {
                        'actionField': {
                            'id': '<?= $_SESSION["cod_pedido"] ?>',
                            'affiliation': '',
                            'revenue': <?= number_format($precio, "2", ".", "") ?>,
                            'tax': '',
                            'shipping': '',
                            'coupon': ''
                        },
                        'products': [<?= substr($googleAnalyticsProduct, 0, -1); ?>] //expand this array if more product exists
                    }
                }
            });
        </script>

<?php
    } else {
        $f->headerMove(URL . '/carrito');
    }
} else {
    $f->headerMove(URL . '/carrito');
}
?>