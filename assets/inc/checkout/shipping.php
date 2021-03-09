<?php
$f = new Clases\PublicFunction;
$envios = new Clases\Envios();
if (isset($_SESSION['stages'])) {
    if ($_SESSION['stages']['status'] == 'OPEN') {
?>
        <div class="">
            <div class="">
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <form id="shipping-f" method="post" data-url="<?= URL ?>" onsubmit="addShipping()">
                            <div class="form-register-title">
                                <?php
                                $pesoFinal = $carrito->finalWeight();
                                $tope = $envios->peso($pesoFinal);
                                $metodos_de_envios = $envios->list(["((peso BETWEEN " . $pesoFinal . " AND " . $tope . ") OR peso=0) AND estado = 1"], '', '');
                                $precioFinal = $carrito->totalPrice();
                                ?>
                                <div id="formEnvio" class=" mt-10 alert alert-warning">
                                    <p class="text-uppercase bold fs-20 text-center">Elegí el tipo de envío para tus productos: (<?= $pesoFinal ?>kg) </p>
                                    <select name="envio" class="form-control text-uppercase" id="envio" data-validation="required" required>
                                        <?php
                                        if (!empty($_SESSION['stages']['stage-1'])) {
                                            $envios->set("cod", $_SESSION['stages']['stage-1']['cod']);
                                            $envioData = $envios->view();
                                            if (!empty($envioData)) {
                                                echo "<option value='" . $envioData['data']['cod'] . "' selected>" . $envioData['data']['titulo'] . "</option>";
                                            } else {
                                                echo "<option selected disabled>Elegir método de envío</option>";
                                            }
                                        } else {
                                            echo "<option selected disabled>Elegir método de envío</option>";
                                        }
                                        foreach ($metodos_de_envios as $metodos_de_envio_) {
                                            if ($metodos_de_envio_['data']['limite'] != 0) {
                                                if ($precioFinal >= $metodos_de_envio_['data']['limite']) {
                                                    $metodos_de_envio_precio = "¡ENVIO GRATIS!";
                                                    $metodos_de_envio_['data']["precio"] = 0;
                                                } else {
                                                    $metodos_de_envio_precio = ($metodos_de_envio_['data']["precio"] != 0) ? "-> $" . $metodos_de_envio_['data']["precio"] : "";
                                                }
                                            } else {
                                                $metodos_de_envio_precio = ($metodos_de_envio_['data']["precio"] != 0) ? "-> $" . $metodos_de_envio_['data']["precio"] : "";
                                            }
                                            echo "<option " . ((count($metodos_de_envios) < 2)  ? "selected" : '') . " value='" . $metodos_de_envio_['data']["cod"] . "'>" . $metodos_de_envio_['data']["titulo"] . " " . $metodos_de_envio_precio . "</option>";
                                        }

                                        ?>
                                    </select>
                                </div>
                            </div>
                            <hr>
                            <div class="form-register-title">
                                <h2>INFORMACIÓN DE ENVÍO</h2>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="mt-10">Nombre:</label>
                                        <input class="form-control  mb-10" type="text" value="<?= isset($_SESSION["stages"]['stage-1']['data']['nombre']) ? $_SESSION["stages"]['stage-1']['data']['nombre'] : '' ?>" placeholder="Escribir nombre" name="nombre" data-validation="required" required />
                                    </div>
                                    <div class="col-md-6">
                                        <label class="mt-10">Apellido:</label>
                                        <input class="form-control  mb-10" type="text" value="<?= isset($_SESSION["stages"]['stage-1']['data']['apellido']) ? $_SESSION["stages"]['stage-1']['data']['apellido'] : '' ?>" placeholder="Escribir apellido" name="apellido" data-validation="required" required />
                                    </div>
                                    <div class="col-md-12">
                                        <label class="mt-10">Email:</label>
                                        <input class="form-control  mb-10" type="email" value="<?= isset($_SESSION["stages"]['stage-1']['data']['email']) ? $_SESSION["stages"]['stage-1']['data']['email'] : '' ?>" placeholder="Escribir email" name="email" data-validation="required" required />
                                    </div>
                                    <div class="col-md-12">
                                        <label class="mt-10">Teléfono:</label>
                                        <input class="form-control  mb-10" type="text" value="<?= isset($_SESSION["stages"]['stage-1']['data']['telefono']) ? $_SESSION["stages"]['stage-1']['data']['telefono'] : '' ?>" placeholder="Escribir telefono" name="telefono" data-validation="required" required />
                                    </div>
                                    <div class="col-md-4">
                                        <label class="mt-10">Provincia</label>
                                        <!-- Dropdown -->
                                        <select id='provincia' data-url="<?= URL ?>" class="form-control" name="provincia" data-validation="required" required>
                                            <?php
                                            if (!empty($_SESSION["stages"]['stage-1']['data']['provincia'])) {
                                            ?>
                                                <option value="<?= $_SESSION["stages"]['stage-1']['data']['provincia'] ?>" selected>
                                                    <?= $_SESSION["stages"]['stage-1']['data']['provincia'] ?>
                                                </option>
                                            <?php
                                            } else {
                                            ?>
                                                <option value="" selected>Seleccionar Provincia</option>
                                            <?php
                                            }
                                            $f->provincias();
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-md-4 form-row-wide">
                                        <label class="mt-10">Localidad</label>
                                        <!-- Dropdown -->
                                        <select id='localidad' class="form-control" name="localidad" data-validation="required" required>
                                            <?php if (!empty($_SESSION["stages"]['stage-1']['data']['localidad'])) { ?>
                                                <option value="<?= $_SESSION["stages"]['stage-1']['data']['localidad'] ?>">
                                                    <?= $_SESSION["stages"]['stage-1']['data']['localidad'] ?>
                                                </option>
                                            <?php } else { ?>
                                                <option value="" selected>Seleccionar Localidad</option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="mt-10">Dirección:</label>
                                        <input class="form-control  mb-10" type="text" value="<?= isset($_SESSION["stages"]['stage-1']['data']['calle']) ? $_SESSION["stages"]['stage-1']['data']['calle'] : '' ?>" placeholder="Escribir dirección" name="direccion" data-validation="required" required />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <hr />
                                        <a href="<?= URL ?>/carrito" style="line-height: 46px"><i class="fa fa-chevron-left"></i> VOLVER</a>
                                        <button class="btn btn-info pull-right text-uppercase btn-lg" type="submit" id="btn-shipping-1">Siguiente paso <i class="fa fa-chevron-circle-right"></i></button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
<?php
    } else {
        $f->headerMove(URL . '/checkout/detail');
    }
} else {
    $f->headerMove(URL . '/carrito');
}
?>