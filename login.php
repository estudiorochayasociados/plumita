<?php
require_once "Config/Autoload.php";
Config\Autoload::run();
$template = new Clases\TemplateSite();
$template->set("title", TITULO);
$template->set("description", "Finalizá tu compra eligiendo tu medio de pago y la forma de envío");
$template->set("keywords", "");
$template->set("favicon", LOGO);
$template->themeInitStages();
$funciones = new Clases\PublicFunction();
$checkout = new Clases\Checkout();
$carrito = new Clases\Carrito();
$config = new Clases\Config();

$captchaData = $config->viewCaptcha();
$progress = $checkout->progress();
?>
<div class="checkout-estudiorocha">
    <?php
    if (!empty($_SESSION['stages'])) {
        if (empty($_SESSION['stages']['user_cod'])) {
            ?>
            <div class="container">
                <div class="row">
                    <div class="col-md-4 col-sm-4 ">
                        <div class="box">
                            <div class="text-center">
                                Si estás interesado comprar sin necesidad de crear tu cuenta, hacé click en el siguiente botón
                                <br/>
                                <i class="fa fa-arrow-down"></i>
                                <br/>
                                <a href="<?= URL ?>/checkout/shipping" class="btn btn-primary btn-lg btn-block">COMPRAR COMO INVITADO</a>
                            </div>
                        </div>
                        <div class="box mt-100">
                            <h2>Ingresar</h2>
                            <div id="l-error"></div>
                            <form id="login" data-url="<?= URL ?>" data-type="stages" onsubmit="loginUser()">
                                <input class="form-control" type="hidden" name="stg-l" value="1">
                                <div class="form-fild">
                                    <span><label>Email <span class="required">*</span></label></span>
                                    <input class="form-control" name="l-user" value="" type="email" required>
                                </div>
                                <div class="form-fild">
                                    <span><label>Contraseña <span class="required">*</span></label></span>
                                    <input class="form-control" name="l-pass" id="l-pass" value="" type="password" required>
                                </div>
                                <div class="form-fild mt-15">
                                    <div id="RecaptchaField1"></div>
                                </div>
                                <div id="btn-l" class="login-submit mt-10 mb-10">
                                    <input type="submit" value="INGRESAR" id="ingresar" class="btn btn-success">
                                </div>
                                <div class="lost-password mb-20">
                                    <a href="<?= URL ?>/recuperar">Olvidaste tu contraseña? Haz click aquí.</a>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-md-8 col-sm-8">
                        <div class="box">
                            <h2>Registrarse</h2>
                            <div id="r-error"></div>
                            <form id="register" data-url="<?= URL ?>" data-type="stages" onsubmit="registerUser()">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-fild">
                                            <span><label>Nombre <span class="required">*</span></label></span>
                                            <input class="form-control" name="r-nombre" value="" type="text" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-fild">
                                            <span><label>Apellido <span class="required">*</span></label></span>
                                            <input class="form-control" name="r-apellido" value="" type="text" required>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-fild">
                                            <span><label>Email <span class="required">*</span></label></span>
                                            <input class="form-control" name="r-email" value="" type="email" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-fild">
                                            <span><label>Contraseña <span class="required">*</span></label></span>
                                            <input class="form-control" name="r-password1" value="" type="password" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-fild">
                                            <span><label>Repetir contraseña <span class="required">*</span></label></span>
                                            <input class="form-control" name="r-password2" value="" type="password" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-fild">
                                            <span><label>Dirección <span class="required">*</span></label></span>
                                            <input class="form-control" name="r-direccion" value="" type="text" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-fild">
                                            <span><label>Teléfono <span class="required">*</span></label></span>
                                            <input class="form-control" name="r-telefono" value="" type="text" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-fild">
                                            <span><label>Provincia <span class="required">*</span></label></span>
                                            <select id='provincia' data-url="<?= URL ?>" class="form-control" name="provincia" required>
                                                <option value="" selected>Seleccionar Provincia</option>
                                                <?php $funciones->provincias(); ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-fild">
                                            <span><label>Localidad <span class="required">*</span></label></span>
                                            <select id='localidad' class="form-control" name="localidad" required>
                                                <option value="" selected>Seleccionar Localidad</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-fild mt-15">
                                    <div id="RecaptchaField2"></div>
                                </div>
                                <div id="btn-r" class="register-submit mt-10 mb-10">
                                    <input type="submit" value="Registrar" id="registrar" class="btn btn-success">
                                </div>
                            </form>
                            <br/>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        } else {
            $funciones->headerMove(URL . '/checkout/shipping');
        }
    } else {
        $funciones->headerMove(URL . '/carrito');
    }
    ?>
    <div class="height-200"></div>
</div>
<?php
$template->themeEndStages();
?>

<script src="<?= URL ?>/assets/js/services/user.js"></script>

<script>
    CaptchaCallback('RecaptchaField1', '<?= $captchaData['data']['captcha_key'] ?>');
    CaptchaCallback('RecaptchaField2', '<?= $captchaData['data']['captcha_key'] ?>');
</script>