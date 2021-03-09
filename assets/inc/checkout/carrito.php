<?php
$f = new Clases\PublicFunction();
$carrito = new Clases\Carrito();
$usuario = new Clases\Usuarios();
$descuento = new Clases\Descuentos();
$checkout = new Clases\Checkout();

$usuarioData = $usuario->viewSession();
$descuentos = $descuento->list("", "", "");
$refreshCartDescuento = $descuento->refreshCartDescuento($carrito->return(), $usuarioData);
$carro = $carrito->return();


$i = 0;
$precio = 0;
?>
<table class="table table-striped ">
    <thead class="thead-dark ">
    <th class="text-left">Nombre</th>
    <th class="text-left hidden-xs hidden-sm">Cantidad</th>
    <th class="text-left">Precio u.</th>
    <th class="text-left">Total</th>
    </thead>
    <?php
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
                <span class="amount <?= $none ?>"><?= "$" . $carroItem["precio"]; ?></span>
                <?php if (isset($carroItem["descuento"]["precio-antiguo"])) { ?>
                    <span class="<?= $none ?> descuento-precio">$<?= $carroItem["descuento"]["precio-antiguo"]; ?></span>
                <?php } ?>
            </td>
            <td>
                <?php
                if ($carroItem["precio"] != 0) {
                    echo "$" . ($carroItem["precio"] * $carroItem["cantidad"]);
                }
                ?>
            </td>
        </tr>
        <?php
        $i++;
    }
    ?>
    <tr>
        <td>
            <h4>TOTAL DE LA COMPRA</h4>
        </td>
        <td class="hidden-xs hidden-sm">
        </td>
        <td></td>
        <td>
            <h4>$<?= number_format($carrito->totalPrice(), "2", ",", "."); ?></h4>
        </td>
    </tr>
</table>
