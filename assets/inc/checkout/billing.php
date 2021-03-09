<?php
if (isset($_SESSION['stages'])) {
    if ($_SESSION['stages']['status'] == 'OPEN' && !empty($_SESSION['stages']['stage-1']) && !empty($_SESSION['stages']['user_cod'])) {
        ?>
        <div class="mt-20">
            <form id="billing-f" method="post" data-url="<?= URL ?>" onsubmit="addBilling()">
                <div class="form-register-title">
                    <h2>INFORMACIÓN DE FACTURACIÓN</h2>
                    <div class="row">

                        <div class="col-md-6 col-xs-12">
                            <label>Nombre:</label>
                            <input class="form-control mb-10" type="text"
                                   value="<?= (!empty($_SESSION['stages']['stage-2'])) ? $_SESSION['stages']['stage-2']['data']['nombre'] : $_SESSION['stages']['stage-1']['data']['nombre']; ?>"
                                   name="nombre" data-validation="required" required/>
                        </div>
                        <div class="col-md-6 col-xs-12">
                            <label>Apellido:</label>
                            <input class="form-control  mb-10" type="text"
                                   value="<?= (!empty($_SESSION['stages']['stage-2'])) ? $_SESSION['stages']['stage-2']['data']['apellido'] : $_SESSION['stages']['stage-1']['data']['apellido']; ?>"
                                   name="apellido" data-validation="required" required/>
                        </div>
                        <div class="col-md-12 col-xs-12">
                            <label>Email:</label>
                            <input class="form-control  mb-10" type="email"
                                   value="<?= (!empty($_SESSION['stages']['stage-2'])) ? $_SESSION['stages']['stage-2']['data']['email'] : $_SESSION['stages']['stage-1']['data']['email']; ?>"
                                   name="email" data-validation="required" required />
                        </div>
                        <div class="col-md-12 col-xs-12">
                            <label>DNI/CUIT:</label>
                            <input class="form-control mb-10" type="text"
                                   value="<?= (!empty($_SESSION['stages']['stage-2'])) ? $_SESSION['stages']['stage-2']['data']['dni'] : '' ?>" name="dni" data-validation="required" required />
                        </div>
                        <div class="col-md-12 col-xs-12">
                            <label>Teléfono:</label>
                            <input class="form-control mb-10" type="text"
                                   value="<?= (!empty($_SESSION['stages']['stage-2'])) ? $_SESSION['stages']['stage-2']['data']['telefono'] : $_SESSION['stages']['stage-1']['data']['telefono']; ?>" name="telefono" data-validation="required" required/>
                        </div>
                        <div class="col-md-4 col-xs-12 form-row-wide">

                            <label>Provincia</label>
                            <!-- Dropdown -->
                            <select id='provincia' data-url="<?= URL ?>" class="form-control" name="provincia" data-validation="required" required>
                                <?php
                                if (!empty($_SESSION['stages']['stage-2']['data']['provincia'])) {
                                    ?>
                                    <option value="<?= $_SESSION['stages']['stage-2']['data']['provincia'] ?>">
                                        <?= $_SESSION['stages']['stage-2']['data']['provincia'] ?>
                                    </option>
                                    <?php
                                } else {
                                    if (!empty($_SESSION['stages']['stage-1']['data']['provincia'])) {
                                        ?>
                                        <option value="<?= $_SESSION['stages']['stage-1']['data']['provincia'] ?>">
                                            <?= $_SESSION['stages']['stage-1']['data']['provincia'] ?>
                                        </option>
                                        <?php
                                    } else {
                                        ?>
                                        <option value="" selected>Seleccionar Provincia</option>
                                        <?php
                                    }
                                }
                                $funciones->provincias();
                                ?>
                            </select>
                        </div>
                        <div class="col-md-4 col-xs-12 form-row-wide">
                            <label>Localidad</label>
                            <!-- Dropdown -->
                            <select id='localidad' class="form-control" name="localidad" data-validation="required" required>
                                <?php
                                if (!empty($_SESSION['stages']['stage-2']['data']['localidad'])) {
                                    ?>
                                    <option value="<?= $_SESSION['stages']['stage-2']['data']['localidad'] ?>" selected>
                                        <?= $_SESSION['stages']['stage-2']['data']['localidad'] ?>
                                    </option>
                                    <?php
                                } else {
                                    if (!empty($_SESSION['stages']['stage-1']['data']['localidad'])) {
                                        ?>
                                        <option value="<?= $_SESSION['stages']['stage-1']['data']['localidad'] ?>" selected>
                                            <?= $_SESSION['stages']['stage-1']['data']['localidad'] ?>
                                        </option>
                                        <?php
                                    } else {
                                        ?>
                                        <option value="" selected>Seleccionar Localidad</option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-4 col-xs-12">
                            <label>Dirección:</label>
                            <input class="form-control mb-10" type="text"
                                   value="<?= (!empty($_SESSION['stages']['stage-2'])) ? $_SESSION['stages']['stage-2']['data']['calle'] : $_SESSION['stages']['stage-1']['data']['calle']; ?>" name="direccion" data-validation="required" required/>
                        </div>
                        <label class="col-md-12 col-xs-12 mt-10 mb-10 fs-15 text-uppercase">
                            <hr/>
                            <input type="checkbox" name="factura" value="1" <?php
                            if (!empty($_SESSION['stages']['stage-2'])) {
                                if (!empty($_SESSION['stages']['stage-2']['data']['factura'])) {
                                    echo "checked";
                                }
                            }
                            ?>>
                            ¿Deseas solicitar "FACTURA A" para tu compra?
                        </label>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <hr/>
                            <a href="<?= URL ?>/checkout/shipping" class="btn btn-default"><i class="fa fa-chevron-left"></i> VOLVER</a>
                            <button style="font-size: 15px !important;" class="btn btn-info pull-right btn-lg text-uppercase" type="submit" id="btn-billing-1">SIGUIENTE PASO <i class="fa fa-chevron-circle-right"></i></button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <?php
    } else {
        if ($_SESSION['stages']['status'] == 'CLOSED') {
            $funciones->headerMove(URL . '/checkout/detail');
        } else {
            if (empty($_SESSION['stages']['user_cod'])) {
                $funciones->headerMove(URL . '/login');
            } else {
                $funciones->headerMove(URL . '/checkout/shipping');
            }
        }
    }
} else {
    $funciones->headerMove(URL . '/carrito');
}
?>
