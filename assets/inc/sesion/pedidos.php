<?php
//Clases
$pedidos = new Clases\Pedidos();

$usuario->set("cod", $_SESSION["usuarios"]["cod"]);
$usuarioData = $usuario->view();

$filter = array("usuario = '" . $usuarioData['data']['cod'] . "'");
$pedidosData = $pedidos->list($filter, '', '');
?>
<?php
if (empty($pedidosData)) {
    ?>
    <div class="container centro">
        <h4>No hay pedidos todavía.</h4>
    </div>
    <?php
} else {
    ?>
    <div class="col-md-12 mb-10" style="margin-top:10px;">
        <?php foreach ($pedidosData as $value): ?>
            <?php $precioTotal = 0; ?>
            <?php $fecha = explode(" ", $value['data']["fecha"]); ?>
            <?php $fecha1 = explode("-", $fecha[0]); ?>
            <?php $fecha1 = $fecha1[2] . '-' . $fecha1[1] . '-' . $fecha1[0] . ' '; ?>
            <?php $fecha = $fecha1 . $fecha[1]; ?>
            <div class="panel panel-default mt-10" style="background: lightgray">
                <a data-toggle="collapse"
                   href="#collapse<?= $value['data']["cod"] ?>"
                   aria-expanded="false"
                   aria-controls="collapse<?= $value['data']["cod"] ?>"
                   class="collapsed color_a"
                   style="width: 100%">
                    <div class="panel-heading boton-cuenta p-0" role="tab" id="heading">
                        <div class="row pedido-centro text-uppercase">
                            <div class="col-md-9 dis ">
                                <span class="negro">Pedido <?= $value['data']["cod"] ?></span>
                                <span class="hidden-xs hidden-sm negro">- Fecha <?= $fecha ?></span>
                            </div>
                            <div class="col-md-3 dis pedido-right">

                                <?php
                                switch ($value['data']["estado"]) {
                                    case 0:
                                        echo "<div class='label label-default fs-12'>ESTADO: CARRITO NO CERRADO</div>";
                                        break;
                                    case 1:
                                        echo "<div class='label label-info fs-12'>ESTADO: PAGO PENDIENTE</div>";
                                        break;
                                    case 2:
                                        echo "<div class='label label-success fs-12'>ESTADO: PAGO APROBADO</div>";
                                        break;
                                    case 3:
                                        echo "<div class='label label-primary fs-12'>ESTADO: PAGO ENVIADO</div>";
                                        break;
                                    case 4:
                                        echo "<div class='label label-danger fs-12'>ESTADO: PAGO RECHAZADO</div>";
                                        break;
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </a>
                <div id="collapse<?= $value['data']["cod"] ?>"
                     class="collapse"
                     role="tabpanel"
                     aria-labelledby="headingOne"
                     aria-expanded="false"
                     style="height: 0px;">
                    <div class="panel-body panel-over" style="height: auto;background:#fff">
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-striped table-hover">
                                    <thead class="thead-dark">
                                    <tr>
                                        <th>
                                            Producto
                                        </th>
                                        <th class="hidden-xs hidden-sm">
                                            Cantidad
                                        </th>
                                        <th class="hidden-xs hidden-sm">
                                            Precio
                                        </th>
                                        <th>
                                            Precio Final
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($value['detail'] as $value2): ?>
                                        <?php if ($value2['cod'] == $value['data']["cod"]): ?>
                                            <tr>
                                                <td><?= $value2["producto"] ?>
                                                    <p class="visible-xs">
                                                        <?php
                                                        if (isset($value2['opciones']) && is_array($value2['opciones'])) {
                                                            if (isset($value2['opciones']['texto'])) {
                                                                echo $value2['opciones']['texto'];
                                                            }
                                                        }
                                                        ?>
                                                    </p>
                                                    <p class="visible-xs">Cantidad: <?= $value2["cantidad"] ?></p>
                                                    <p class="visible-xs">Precio: $<?= $value2["precio"] ?></p>
                                                </td>
                                                <td class="hidden-xs hidden-sm"><?= $value2["cantidad"] ?></td>
                                                <td class="hidden-xs hidden-sm">$<?= $value2["precio"] ?></td>
                                                <td>$<?= $value2["precio"] * $value2["cantidad"] ?></td>
                                                <?php $precioTotal = $precioTotal + ($value2["precio"] * $value2["cantidad"]); ?>
                                            </tr>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                    <tr>
                                        <td><b>TOTAL DE LA COMPRA</b></td>
                                        <td class="hidden-xs hidden-sm"></td>
                                        <td class="hidden-xs hidden-sm"></td>
                                        <td><b>$<?= $precioTotal ?></b></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <br>
                        <div class="col-md-12" style="font-size:16px">
                            <b class="mb-10">FORMA DE PAGO:</b>
                            <br class="d-sm-none">
                            <?= $value['data']["tipo"] ?>
                        </div>
                        <div class="col-md-12" style="font-size:16px">
                            <?php
                            if (!empty($value['data']['detalle'])) {
                                $detalle = json_decode($value['data']['detalle'], true);
                                if (!empty($detalle['leyenda'])) {
                                    echo "<b>DESCRIPCIÓN DEL PAGO: </b>" . $detalle['leyenda'] . "<br/>";
                                }
                                if (!empty($detalle['descuento'])) {
                                    echo "<b>SE UTILIZÓ EL CÓDIGO DE DESCUENTO: </b>" . $detalle['descuento'];
                                }
                                if (!empty($detalle['link'])) {
                                    echo "<b>URL PARA PAGAR: </b><a href='".$detalle['link']."' target='_blank'>CLICK AQUÍ</a>";
                                }
                                ?>
                                <div class="row mb-15">
                                    <div class="col-md-6">
                                        <hr>
                                        <b>INFORMACIÓN DE ENVIÓ</b>
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
                        </div>
                        <br>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <?php
}
?>
