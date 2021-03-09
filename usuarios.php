<?php
require_once "Config/Autoload.php";
Config\Autoload::run();
$template = new Clases\TemplateSite();
$funciones = new Clases\PublicFunction();
$config = new Clases\Config();
$usuario = new Clases\Usuarios();

///Datos
$emailData = $config->viewEmail();
$userData = $usuario->viewSession();
$captchaData = $config->viewCaptcha();
if (!empty($userData)) {
    $funciones->headerMove(URL . '/sesion');
}
$link = isset($_GET["link"]) ? $funciones->antihack_mysqli($_GET["link"]) : '';
//
$template->set("title", "USUARIOS | " . TITULO);
$template->set("description", "Panel de para usuarios de " . TITULO);
$template->set("keywords", "");
$template->set("body", "contacts");
$template->themeInit();
?>
<section class="solid_banner_area">
    <div class="container">
        <div class="solid_banner_inner navegador">
            <h3>Inicio de sesion</h3>
            <ul>
                <li><a href="<?= URL ?>/index">Inicio</a></li>
                <li class="current"><a href="<?= URL ?>/productos">Inicio de sesion</a></li>
            </ul>
        </div>
    </div>
</section>
<br>
<div class="container">
    <div class="row">
        <div class="container">
            <div class="row">
                <div class="col-md-4 col-sm-4 ">
                    <div class="box">
                        <h2>Ingresar</h2>
                        <div id="l-error"></div>

                        <form id="login" data-url="<?= URL ?>" data-type="usuarios" onsubmit="loginUser()">
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
                            <div class="lost-password">
                                <a href="<?= URL ?>/recuperar">Olvidaste tu contraseña? Haz click aquí.</a>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-md-8 col-sm-8">
                    <div class="box">
                        <h2>Registrarse</h2>
                        <div id="r-error"></div>
                        <form id="register" data-url="<?= URL ?>" data-type="usuarios" onsubmit="registerUser()">
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
                        <br />
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--Login Register section end-->
<?php
$template->themeEnd();
?>
<script src="<?= URL ?>/assets/js/services/user.js"></script>
<script>
    function captchaTimer() {
        try {
            CaptchaCallback('RecaptchaField1', '<?= $captchaData['data']['captcha_key'] ?>');
            CaptchaCallback('RecaptchaField2', '<?= $captchaData['data']['captcha_key'] ?>');
        } catch (err) {
            setTimeout(captchaTimer, 1000);
        }
    }
    captchaTimer();
</script>