<?php
$config = new Clases\Config();
$funcion = new Clases\PublicFunction();
$tab = $funciones->antihack_mysqli(isset($_GET["tab"]) ? $_GET["tab"] : '');
$emailData = $config->viewEmail();
$marketingData = $config->viewMarketing();
$contactoData = $config->viewContact();
$socialData = $config->viewSocial();
$mercadoLibreData = $config->viewMercadoLibre();
$andreaniData = $config->viewAndreani();
$captchaData = $config->viewCaptcha();
$configHeader = $config->viewConfigHeader();
$exportadorMeliData = $config->viewExportadorMeli();

//Metodos de pagos
$config->set("id", 1);
$pagosData1 = $config->viewPayment();
$config->set("id", 2);
$pagosData2 = $config->viewPayment();
$config->set("id", 3);
$pagosData3 = $config->viewPayment();
$config->set("id", 4);
$pagosData4 = $config->viewPayment();

?>
<section id="tabs" class="project-tab">
    <div class="row">
        <div class="col-md-12">
            <nav>
                <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
                    <a class="nav-item nav-link active" id="email-tab" data-toggle="tab" href="#email-home" role="tab" aria-controls="nav-home" aria-selected="true">CONFIGURACIÓN EMAIL</a>
                    <a class="nav-item nav-link" id="marketing-tab" data-toggle="tab" href="#marketing-home" role="tab" aria-controls="nav-profile" aria-selected="false">MARKETING</a>
                    <a class="nav-item nav-link" id="contact-tab" data-toggle="tab" href="#contact-home" role="tab" aria-controls="nav-contact" aria-selected="false">DATOS EMPRESA</a>
                    <a class="nav-item nav-link" id="social-tab" data-toggle="tab" href="#social-home" role="tab" aria-controls="nav-home" aria-selected="true">REDES SOCIALES</a>
                    <a class="nav-item nav-link" id="api-tab" data-toggle="tab" href="#api-home" role="tab" aria-controls="nav-profile" aria-selected="false">API</a>
                    <a class="nav-item nav-link" id="pagos-tab" data-toggle="tab" href="#pagos-home" role="tab" aria-controls="nav-contact" aria-selected="false">PAGOS</a>
                    <a class="nav-item nav-link" id="captcha-tab" data-toggle="tab" href="#captcha-home" role="tab" aria-controls="nav-contact" aria-selected="false">CAPTCHA</a>
                    <a class="nav-item nav-link" id="cnf-tab" data-toggle="tab" href="#config-header" role="tab" aria-controls="nav-contact" aria-selected="false">HEADER</a>
                    <a class="nav-item nav-link" id="exportadorMeli-tab" data-toggle="tab" href="#exportadorMeli-home" role="tab" aria-controls="nav-profile" aria-selected="false">EXPORTADOR MELI</a>
                </div>
            </nav>
            <div class="tab-content mt-15" id="nav-tabContent">
                <?php
                if (isset($_POST["agregar-email"])) {
                    $config->set("remitente", $funciones->antihack_mysqli(isset($_POST["e-remitente"]) ? $_POST["e-remitente"] : ''));
                    $config->set("smtp", $funciones->antihack_mysqli(isset($_POST["e-smtp"]) ? $_POST["e-smtp"] : ''));
                    $config->set("smtp_secure", $funciones->antihack_mysqli(isset($_POST["e-smtp-secure"]) ? $_POST["e-smtp-secure"] : ''));
                    $config->set("puerto", $funciones->antihack_mysqli(isset($_POST["e-puerto"]) ? $_POST["e-puerto"] : ''));
                    $config->set("email_", $funciones->antihack_mysqli(isset($_POST["e-email"]) ? $_POST["e-email"] : ''));
                    $config->set("password", $funciones->antihack_mysqli(isset($_POST["e-password"]) ? $_POST["e-password"] : ''));
                    $error = $config->addEmail();
                    if ($error) {
                        $funciones->headerMove(URLADMIN . '/index.php?op=configuracion&accion=modificar&tab=email-tab');
                    } else {
                        echo "<div class=\"alert alert-danger\" role=\"alert\">$error</div>";
                    }
                }
                ?>
                <div class="tab-pane fade show active" id="email-home" role="tabpanel" aria-labelledby="nav-home-tab">
                    <form method="post" action="<?= URLADMIN ?>/index.php?op=configuracion&accion=modificar&tab=email-tab">
                        <div class="row mt-5">
                            <label class="col-md-6">
                                Remitente:<br/>
                                <input type="email" class="form-control" name="e-remitente"
                                       value="<?= $emailData['data']["remitente"] ? $emailData['data']["remitente"] : '' ?>" required/>
                            </label>
                            <label class="col-md-6">
                                Email:<br/>
                                <input type="email" class="form-control" name="e-email"
                                       value="<?= $emailData['data']["email"] ? $emailData['data']["email"] : '' ?>" required/>
                            </label>
                            <label class="col-md-4">
                                SMTP Server:<br/>
                                <input type="text" class="form-control" name="e-smtp"
                                       value="<?= $emailData['data']["smtp"] ? $emailData['data']["smtp"] : '' ?>" required/>
                            </label>
                            <label class="col-md-2">
                                SMTP Secure:<br/>
                                <select name="e-smtp-secure" required>
                                    <?php
                                    if (!empty($emailData['data']['smtp_secure'])) {
                                        $secure = $emailData['data']['smtp_secure'];
                                        ?>
                                        <option value="tls" <?php if ($secure == "tls") {
                                            echo "selected";
                                        } ?>>
                                            TLS
                                        </option>
                                        <option value="ssl" <?php if ($secure == "ssl") {
                                            echo "selected";
                                        } ?>>
                                            SSL
                                        </option>
                                        <?php
                                    } else {
                                        ?>
                                        <option value="tls">TLS</option>
                                        <option value="ssl">SSL</option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </label>
                            <label class="col-md-2">
                                Puerto:<br/>
                                <input type="number" class="form-control" name="e-puerto"
                                       value="<?= $emailData['data']["puerto"] ? $emailData['data']["puerto"] : '' ?>" required/>
                            </label>
                            <label class="col-md-4">
                                Password:<br/>
                                <input type="password" class="form-control" name="e-password"
                                       value="<?= $emailData['data']["password"] ? $emailData['data']["password"] : '' ?>" required/>
                            </label>
                            <div class="col-md-12">
                                <button class="btn btn-primary" type="submit" name="agregar-email">Guardar cambios</button>
                            </div>
                        </div>
                    </form>
                </div>

                <?php
                if (isset($_POST["agregar-marketing"])) {
                    $config->set("googleDataStudioId", $funciones->antihack_mysqli(isset($_POST["m-google-id"]) ? $_POST["m-google-id"] : ''));
                    $config->set("googleAnalytics", $funciones->antihack_mysqli(isset($_POST["m-google-analytics"]) ? $_POST["m-google-analytics"] : ''));
                    $config->set("hubspot", $funciones->antihack_mysqli(isset($_POST["m-hubspot"]) ? $_POST["m-hubspot"] : ''));
                    $config->set("mailrelay", $funciones->antihack_mysqli(isset($_POST["m-mailrelay"]) ? $_POST["m-mailrelay"] : ''));
                    $config->set("onesignal", $funciones->antihack_mysqli(isset($_POST["m-onesignal"]) ? $_POST["m-onesignal"] : ''));
                    $config->set("facebookPixel", $funciones->antihack_mysqli(isset($_POST["m-facebook-pixel"]) ? $_POST["m-facebook-pixel"] : ''));
                    $error = $config->addMarketing();
                    if ($error) {
                        $funciones->headerMove(URLADMIN . '/index.php?op=configuracion&accion=modificar&tab=marketing-tab');
                    } else {
                        echo "<div class=\"alert alert-danger\" role=\"alert\">$error</div>";
                    }
                }
                ?>
                <div class="tab-pane fade" id="marketing-home" role="tabpanel" aria-labelledby="nav-profile-tab">
                    <form method="post" action="<?= URLADMIN ?>/index.php?op=configuracion&accion=modificar&tab=marketing-tab">
                        <div class="row mt-5">
                            <label class="col-md-12">
                                Google Data Studio ID:<br/>
                                <input type="text" class="form-control" name="m-google-id"
                                       value="<?= $marketingData['data']["google_data_studio_id"] ? $marketingData['data']["google_data_studio_id"] : '' ?>"/>
                            </label>
                            <label class="col-md-12">
                                Google Analytics:<br/>
                                <input type="text" class="form-control" name="m-google-analytics"
                                       value="<?= $marketingData['data']["google_analytics"] ? $marketingData['data']["google_analytics"] : '' ?>"/>
                            </label>
                            <label class="col-md-12">
                                Hubspot:<br/>
                                <input type="text" class="form-control" name="m-hubspot"
                                       value="<?= $marketingData['data']["hubspot"] ? $marketingData['data']["hubspot"] : '' ?>"/>
                            </label>
                            <label class="col-md-12">
                                Mailrelay:<br/>
                                <input type="text" class="form-control" name="m-mailrelay"
                                       value="<?= $marketingData['data']["mailrelay"] ? $marketingData['data']["mailrelay"] : '' ?>"/>
                            </label>
                            <label class="col-md-12">
                                OneSignal:<br/>
                                <input type="text" class="form-control" name="m-onesignal"
                                       value="<?= $marketingData['data']["onesignal"] ? $marketingData['data']["onesignal"] : '' ?>"/>
                            </label>
                            <label class="col-md-12">
                                Facebook Pixel:<br/>
                                <input type="text" class="form-control" name="m-facebook-pixel"
                                       value="<?= $marketingData['data']["facebook_pixel"] ? $marketingData['data']["facebook_pixel"] : '' ?>"/>
                            </label>
                            <div class="col-md-12">
                                <button class="btn btn-primary" type="submit" name="agregar-marketing">Guardar cambios</button>
                            </div>
                        </div>
                    </form>
                </div>

                <?php
                if (isset($_POST["agregar-contacto"])) {
                    $config->set("email", $funciones->antihack_mysqli(isset($_POST["email"]) ? $_POST["email"] : ''));
                    $config->set("telefono", $funciones->antihack_mysqli(isset($_POST["telefono"]) ? $_POST["telefono"] : ''));
                    $config->set("whatsapp", $funciones->antihack_mysqli(isset($_POST["whatsapp"]) ? $_POST["whatsapp"] : ''));
                    $config->set("domicilio", $funciones->antihack_mysqli(isset($_POST["domicilio"]) ? $_POST["domicilio"] : ''));
                    $config->set("localidad", $funciones->antihack_mysqli(isset($_POST["localidad"]) ? $_POST["localidad"] : ''));
                    $config->set("provincia", $funciones->antihack_mysqli(isset($_POST["provincia"]) ? $_POST["provincia"] : ''));
                    $config->set("pais", $funciones->antihack_mysqli(isset($_POST["pais"]) ? $_POST["pais"] : ''));
                    $error = $config->addContact();
                    if ($error) {
                        $funciones->headerMove(URLADMIN . '/index.php?op=configuracion&accion=modificar&tab=contact-tab');
                    } else {
                        echo "<div class=\"alert alert-danger\" role=\"alert\">$error</div>";
                    }
                }
                ?>
                <div class="tab-pane fade" id="contact-home" role="tabpanel" aria-labelledby="nav-contact-tab">
                    <form method="post" action="<?= URLADMIN ?>/index.php?op=configuracion&accion=modificar&tab=contact-tab">
                        <div class="row mt-5">
                            <label class="col-md-12">
                                Email:<br/>
                                <input type="email" class="form-control" name="email"
                                       value="<?= $contactoData['data']["email"] ? $contactoData['data']["email"] : '' ?>" required/>
                            </label>
                            <label class="col-md-6">
                                Teléfono:<br/>
                                <input type="text" class="form-control" name="telefono"
                                       value="<?= $contactoData['data']["telefono"] ? $contactoData['data']["telefono"] : '' ?>" required/>
                            </label>
                            <label class="col-md-6">
                                Whatsapp:<br/>
                                <input type="text" class="form-control" name="whatsapp"
                                       value="<?= $contactoData['data']["whatsapp"] ? $contactoData['data']["whatsapp"] : '' ?>"/>
                            </label>
                            <label class="col-md-3">
                                Domicilio:<br/>
                                <input type="text" class="form-control" name="domicilio"
                                       value="<?= $contactoData['data']["domicilio"] ? $contactoData['data']["domicilio"] : '' ?>" required/>
                            </label>
                            <label class="col-md-3">
                                Localidad:<br/>
                                <input type="text" class="form-control" name="localidad"
                                       value="<?= $contactoData['data']["localidad"] ? $contactoData['data']["localidad"] : '' ?>" required/>
                            </label>
                            <label class="col-md-3">
                                Provincia:<br/>
                                <input type="text" class="form-control" name="provincia"
                                       value="<?= $contactoData['data']["provincia"] ? $contactoData['data']["provincia"] : '' ?>" required/>
                            </label>
                            <label class="col-md-3">
                                País:<br/>
                                <input type="text" class="form-control" name="pais"
                                       value="<?= $contactoData['data']["pais"] ? $contactoData['data']["pais"] : '' ?>" required/>
                            </label>
                            <div class="col-md-12">
                                <button class="btn btn-primary" type="submit" name="agregar-contacto">Guardar cambios</button>
                            </div>
                        </div>
                    </form>
                </div>

                <?php
                if (isset($_POST["agregar-redes"])) {
                    $config->set("facebook", $funciones->antihack_mysqli(isset($_POST["s-facebook"]) ? $_POST["s-facebook"] : ''));
                    $config->set("twitter", $funciones->antihack_mysqli(isset($_POST["s-twitter"]) ? $_POST["s-twitter"] : ''));
                    $config->set("instagram", $funciones->antihack_mysqli(isset($_POST["s-instagram"]) ? $_POST["s-instagram"] : ''));
                    $config->set("linkedin", $funciones->antihack_mysqli(isset($_POST["s-linkedin"]) ? $_POST["s-linkedin"] : ''));
                    $config->set("youtube", $funciones->antihack_mysqli(isset($_POST["s-youtube"]) ? $_POST["s-youtube"] : ''));
                    $config->set("googleplus", $funciones->antihack_mysqli(isset($_POST["s-google"]) ? $_POST["s-google"] : ''));
                    $error = $config->addSocial();
                    if ($error) {
                        $funciones->headerMove(URLADMIN . '/index.php?op=configuracion&accion=modificar&tab=social-tab');
                    } else {
                        echo "<div class=\"alert alert-danger\" role=\"alert\">$error</div>";
                    }
                }
                ?>
                <div class="tab-pane fade" id="social-home" role="tabpanel" aria-labelledby="nav-contact-tab">
                    <form method="post" action="<?= URLADMIN ?>/index.php?op=configuracion&accion=modificar&tab=social-tab">
                        <div class="row mt-5">
                            <label class="col-md-12">
                                Facebook:<br/>
                                <input type="text" class="form-control" name="s-facebook"
                                       value="<?= $socialData['data']["facebook"] ? $socialData['data']["facebook"] : '' ?>"/>
                            </label>
                            <label class="col-md-12">
                                Twitter:<br/>
                                <input type="text" class="form-control" name="s-twitter"
                                       value="<?= $socialData['data']["twitter"] ? $socialData['data']["twitter"] : '' ?>"/>
                            </label>
                            <label class="col-md-12">
                                Instragram:<br/>
                                <input type="text" class="form-control" name="s-instagram"
                                       value="<?= $socialData['data']["instagram"] ? $socialData['data']["instagram"] : '' ?>"/>
                            </label>
                            <label class="col-md-12">
                                Linkedin:<br/>
                                <input type="text" class="form-control" name="s-linkedin"
                                       value="<?= $socialData['data']["linkedin"] ? $socialData['data']["linkedin"] : '' ?>"/>
                            </label>
                            <label class="col-md-12">
                                YouTube:<br/>
                                <input type="text" class="form-control" name="s-youtube"
                                       value="<?= $socialData['data']["youtube"] ? $socialData['data']["youtube"] : '' ?>"/>
                            </label>
                            <label class="col-md-12">
                                Google Plus:<br/>
                                <input type="text" class="form-control" name="s-google"
                                       value="<?= $socialData['data']["googleplus"] ? $socialData['data']["googleplus"] : '' ?>"/>
                            </label>
                            <div class="col-md-12">
                                <button class="btn btn-primary" type="submit" name="agregar-redes">Guardar cambios</button>
                            </div>
                        </div>
                    </form>
                </div>

                <?php
                if (isset($_POST["agregar-api-ml"])) {
                    $config->set("app_id", $funciones->antihack_mysqli(isset($_POST["ml-id"]) ? $_POST["ml-id"] : ''));
                    $config->set("app_secret", $funciones->antihack_mysqli(isset($_POST["ml-secret"]) ? $_POST["ml-secret"] : ''));
                    $error = $config->addMercadoLibre();
                    if ($error) {
                        $funciones->headerMove(URLADMIN . '/index.php?op=configuracion&accion=modificar&tab=api-tab');
                    } else {
                        echo "<div class=\"alert alert-danger\" role=\"alert\">$error</div>";
                    }
                }
                if (isset($_POST["agregar-api-andreani"])) {
                    $config->set("usuario", $funciones->antihack_mysqli(isset($_POST["api-andreani-usuario"]) ? $_POST["api-andreani-usuario"] : ''));
                    $config->set("contrasenia", $funciones->antihack_mysqli(isset($_POST["api-andreani-contraseña"]) ? $_POST["api-andreani-contraseña"] : ''));
                    $config->set("codCliente", $funciones->antihack_mysqli(isset($_POST["api-andreani-cod"]) ? $_POST["api-andreani-cod"] : ''));
                    $config->set("envioSucursal", $funciones->antihack_mysqli(isset($_POST["api-andreani-enviosucursal"]) ? $_POST["api-andreani-enviosucursal"] : ''));
                    $config->set("envioDomicilio", $funciones->antihack_mysqli(isset($_POST["api-andreani-enviodomicilio"]) ? $_POST["api-andreani-enviodomicilio"] : ''));
                    $config->set("envioUrgente", $funciones->antihack_mysqli(isset($_POST["api-andreani-enviourgente"]) ? $_POST["api-andreani-enviourgente"] : ''));
                    $error = $config->addAndreani();
                    if ($error) {
                        $funciones->headerMove(URLADMIN . '/index.php?op=configuracion&accion=modificar&tab=api-tab');
                    } else {
                        echo "<div class=\"alert alert-danger\" role=\"alert\">$error</div>";
                    }
                }
                ?>
                <div class="tab-pane fade" id="api-home" role="tabpanel" aria-labelledby="nav-contact-tab">
                    <div id="accordion">
                        <div class="card mt-5">
                            <a class="btn btn-primary" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                <div class="card-header" id="headingOne" style="border-bottom: unset">
                                    <h5 class="mb-0 " style="color: white">
                                        Mercado Libre
                                    </h5>
                                </div>
                            </a>
                            <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                                <div class="card-body">
                                    <form method="post" action="<?= URLADMIN ?>/index.php?op=configuracion&accion=modificar&tab=api-tab">
                                        <div class="row mt-5">
                                            <label class="col-md-12">
                                                APP ID:<br/>
                                                <input type="text" class="form-control" name="ml-id"
                                                       value="<?= $mercadoLibreData['data']["app_id"] ? $mercadoLibreData['data']["app_id"] : '' ?>" required/>
                                            </label>
                                            <label class="col-md-12">
                                                APP SECRET:<br/>
                                                <input type="text" class="form-control" name="ml-secret"
                                                       value="<?= $mercadoLibreData['data']["app_secret"] ? $mercadoLibreData['data']["app_secret"] : '' ?>" required/>
                                            </label>
                                            <div class="col-md-12">
                                                <button class="btn btn-primary" type="submit" name="agregar-api-ml">Guardar cambios</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="card mt-5">
                            <a class="btn btn-primary collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                <div class="card-header" id="headingTwo" style="border-bottom: unset">
                                    <h5 class="mb-0" style="color: white">
                                        Andreani
                                    </h5>
                                </div>
                            </a>
                            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                                <div class="card-body">
                                    <form method="post" action="<?= URLADMIN ?>/index.php?op=configuracion&accion=modificar&tab=api-tab">
                                        <div class="row mt-5">
                                            <label class="col-md-4">
                                                Nombre de usuario:<br/>
                                                <input type="text" class="form-control" name="api-andreani-usuario"
                                                       value="<?= $andreaniData['data']["usuario"] ? $andreaniData['data']["usuario"] : '' ?>" required/>
                                            </label>
                                            <label class="col-md-4">
                                                Contraseña:<br/>
                                                <input type="text" class="form-control" name="api-andreani-contraseña"
                                                       value="<?= $andreaniData['data']["contraseña"] ? $andreaniData['data']["contraseña"] : '' ?>" required/>
                                            </label>
                                            <label class="col-md-4">
                                                Código de cliente:<br/>
                                                <input type="text" class="form-control" name="api-andreani-cod"
                                                       value="<?= $andreaniData['data']["cod"] ? $andreaniData['data']["cod"] : '' ?>" required/>
                                            </label>
                                            <label class="col-md-4">
                                                Contrato para envíos a sucursal:<br/>
                                                <input type="text" class="form-control" name="api-andreani-enviosucursal"
                                                       value="<?= $andreaniData['data']["envio_sucursal"] ? $andreaniData['data']["envio_sucursal"] : '' ?>" required/>
                                            </label>
                                            <label class="col-md-4">
                                                Contrato para envíos estándar a domicilio:<br/>
                                                <input type="text" class="form-control" name="api-andreani-enviodomicilio"
                                                       value="<?= $andreaniData['data']["envio_domicilio"] ? $andreaniData['data']["envio_domicilio"] : '' ?>" required/>
                                            </label>
                                            <label class="col-md-4">
                                                Contrato para envíos urgentes a domicilio:<br/>
                                                <input type="text" class="form-control" name="api-andreani-enviourgente"
                                                       value="<?= $andreaniData['data']["envio_urgente"] ? $andreaniData['data']["envio_urgente"] : '' ?>" required/>
                                            </label>
                                            <div class="col-md-12">
                                                <button class="btn btn-primary" type="submit" name="agregar-api-andreani">Guardar cambios</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <?php
                if (isset($_POST["p1-guardar"])) {
                    $config->set("variable1", $funciones->antihack_mysqli(isset($_POST["p1-v1"]) ? $_POST["p1-v1"] : ''));
                    $config->set("variable2", $funciones->antihack_mysqli(isset($_POST["p1-v2"]) ? $_POST["p1-v2"] : ''));
                    $config->set("variable3", $funciones->antihack_mysqli(isset($_POST["p1-v3"]) ? $_POST["p1-v3"] : ''));
                    $config->set("id", 1);
                    $error = $config->updatePayment();
                    if ($error) {
                        $funciones->headerMove(URLADMIN . '/index.php?op=configuracion&accion=modificar&tab=pagos-tab');
                    } else {
                        echo "<div class=\"alert alert-danger\" role=\"alert\">$error</div>";
                    }
                }
                if (isset($_POST["p2-guardar"])) {
                    $config->set("variable1", $funciones->antihack_mysqli(isset($_POST["p2-v1"]) ? $_POST["p2-v1"] : ''));
                    $config->set("variable2", $funciones->antihack_mysqli(isset($_POST["p2-v2"]) ? $_POST["p2-v2"] : ''));
                    $config->set("variable3", $funciones->antihack_mysqli(isset($_POST["p2-v3"]) ? $_POST["p2-v3"] : ''));
                    $config->set("id", 2);
                    $error = $config->updatePayment();
                    if ($error) {
                        $funciones->headerMove(URLADMIN . '/index.php?op=configuracion&accion=modificar&tab=pagos-tab');
                    } else {
                        echo "<div class=\"alert alert-danger\" role=\"alert\">$error</div>";
                    }
                }
                if (isset($_POST["p3-guardar"])) {
                    $config->set("variable1", $funciones->antihack_mysqli(isset($_POST["p3-v1"]) ? $_POST["p3-v1"] : ''));
                    $config->set("variable2", $funciones->antihack_mysqli(isset($_POST["p3-v2"]) ? $_POST["p3-v2"] : ''));
                    $config->set("variable3", $funciones->antihack_mysqli(isset($_POST["p3-v3"]) ? $_POST["p3-v3"] : ''));
                    $config->set("id", 3);
                    $error = $config->updatePayment();
                    if ($error) {
                        $funciones->headerMove(URLADMIN . '/index.php?op=configuracion&accion=modificar&tab=pagos-tab');
                    } else {
                        echo "<div class=\"alert alert-danger\" role=\"alert\">$error</div>";
                    }
                }
                if (isset($_POST["p4-guardar"])) {
                    $config->set("variable1", $funciones->antihack_mysqli(isset($_POST["p4-v1"]) ? $_POST["p4-v1"] : ''));
                    $config->set("variable2", $funciones->antihack_mysqli(isset($_POST["p4-v2"]) ? $_POST["p4-v2"] : ''));
                    $config->set("variable3", $funciones->antihack_mysqli(isset($_POST["p4-v3"]) ? $_POST["p4-v3"] : ''));
                    $config->set("id", 4);
                    $error = $config->updatePayment();
                    if ($error) {
                        $funciones->headerMove(URLADMIN . '/index.php?op=configuracion&accion=modificar&tab=pagos-tab');
                    } else {
                        echo "<div class=\"alert alert-danger\" role=\"alert\">$error</div>";
                    }
                }
                $empresa1 = $pagosData1['data']["empresa"] ? $pagosData1['data']["empresa"] : '';
                $empresa2 = $pagosData2['data']["empresa"] ? $pagosData2['data']["empresa"] : '';
                $empresa3 = $pagosData3['data']["empresa"] ? $pagosData3['data']["empresa"] : '';
                $empresa4 = $pagosData4['data']["empresa"] ? $pagosData4['data']["empresa"] : '';
                ?>
                <div class="tab-pane fade" id="pagos-home" role="tabpanel" aria-labelledby="nav-contact-tab">
                    <form method="post" class="row" action="<?= URLADMIN ?>/index.php?op=configuracion&accion=modificar&tab=pagos-tab">
                        <div class="col-md-2">
                            <input type="text" class="form-control mb-2 mr-sm-2" id="inlineFormInputName2" placeholder="<?= $empresa1 ?>" readonly>
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control mb-2 mr-sm-2" id="inlineFormInputName2" placeholder="KEY" name="p1-v1"
                                   value="<?= $pagosData1['data']["variable1"] ? $pagosData1['data']["variable1"] : '' ?>" required>
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control mb-2 mr-sm-2" id="inlineFormInputName2" placeholder="SECRET" name="p1-v2"
                                   value="<?= $pagosData1['data']["variable2"] ? $pagosData1['data']["variable2"] : '' ?>" required>
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control mb-2 mr-sm-2" id="inlineFormInputName2" placeholder="" name="p1-v3"
                                   value="<?= $pagosData1['data']["variable3"] ? $pagosData1['data']["variable3"] : '' ?>">
                        </div>
                        <div class="col-md-1">
                            <button type="submit" class="btn btn-primary mb-2" name="p1-guardar">Guardar</button>
                        </div>
                    </form>
                    <hr>
                    <form method="post" class="row" action="<?= URLADMIN ?>/index.php?op=configuracion&accion=modificar&tab=pagos-tab">
                        <div class="col-md-2">
                            <input type="text" class="form-control mb-2 mr-sm-2" id="inlineFormInputName2" placeholder="<?= $empresa2 ?>" readonly>
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control mb-2 mr-sm-2" id="inlineFormInputName2" placeholder="KEY" name="p2-v1"
                                   value="<?= $pagosData2['data']["variable1"] ? $pagosData2['data']["variable1"] : '' ?>" required>
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control mb-2 mr-sm-2" id="inlineFormInputName2" placeholder="SECRET" name="p2-v2"
                                   value="<?= $pagosData2['data']["variable2"] ? $pagosData2['data']["variable2"] : '' ?>" required>
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control mb-2 mr-sm-2" id="inlineFormInputName2" placeholder="" name="p2-v3"
                                   value="<?= $pagosData2['data']["variable3"] ? $pagosData2['data']["variable3"] : '' ?>">
                        </div>
                        <div class="col-md-1">
                            <button type="submit" class="btn btn-primary mb-2" name="p2-guardar">Guardar</button>
                        </div>
                    </form>
                    <hr>
                    <form method="post" class="row" action="<?= URLADMIN ?>/index.php?op=configuracion&accion=modificar&tab=pagos-tab">
                        <div class="col-md-2">
                            <input type="text" class="form-control mb-2 mr-sm-2" id="inlineFormInputName2" placeholder="<?= $empresa3 ?>" readonly>
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control mb-2 mr-sm-2" id="inlineFormInputName2" placeholder="KEY" name="p3-v1"
                                   value="<?= $pagosData3['data']["variable1"] ? $pagosData3['data']["variable1"] : '' ?>" required>
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control mb-2 mr-sm-2" id="inlineFormInputName2" placeholder="SECRET" name="p3-v2"
                                   value="<?= $pagosData3['data']["variable2"] ? $pagosData3['data']["variable2"] : '' ?>" required>
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control mb-2 mr-sm-2" id="inlineFormInputName2" placeholder="" name="p3-v3"
                                   value="<?= $pagosData3['data']["variable3"] ? $pagosData3['data']["variable3"] : '' ?>">
                        </div>
                        <div class="col-md-1">
                            <button type="submit" class="btn btn-primary mb-2" name="p3-guardar">Guardar</button>
                        </div>
                    </form>
                    <hr>
                    <form method="post" class="row" action="<?= URLADMIN ?>/index.php?op=configuracion&accion=modificar&tab=pagos-tab">
                        <div class="col-md-2">
                            <input type="text" class="form-control mb-2 mr-sm-2" id="inlineFormInputName2" placeholder="<?= $empresa4 ?>" readonly>
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control mb-2 mr-sm-2" id="inlineFormInputName2" placeholder="KEY" name="p4-v1"
                                   value="<?= $pagosData4['data']["variable1"] ? $pagosData4['data']["variable1"] : '' ?>" required>
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control mb-2 mr-sm-2" id="inlineFormInputName2" placeholder="SECRET" name="p4-v2"
                                   value="<?= $pagosData4['data']["variable2"] ? $pagosData4['data']["variable2"] : '' ?>" required>
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control mb-2 mr-sm-2" id="inlineFormInputName2" placeholder="" name="p4-v3"
                                   value="<?= $pagosData4['data']["variable3"] ? $pagosData4['data']["variable3"] : '' ?>">
                        </div>
                        <div class="col-md-1">
                            <button type="submit" class="btn btn-primary mb-2" name="p4-guardar">Guardar</button>
                        </div>
                    </form>
                </div>

                <?php
                if (isset($_POST["agregar-captcha"])) {
                    $config->set("captcha_key", $funciones->antihack_mysqli(isset($_POST["c-key"]) ? $_POST["c-key"] : ''));
                    $config->set("captcha_secret", $funciones->antihack_mysqli(isset($_POST["c-secret"]) ? $_POST["c-secret"] : ''));
                    $error = $config->addCaptcha();
                    if ($error) {
                        $funciones->headerMove(URLADMIN . '/index.php?op=configuracion&accion=modificar&tab=captcha-tab');
                    } else {
                        echo "<div class=\"alert alert-danger\" role=\"alert\">$error</div>";
                    }
                }
                ?>
                <div class="tab-pane fade" id="captcha-home" role="tabpanel" aria-labelledby="nav-contact-tab">
                    <form method="post" action="<?= URLADMIN ?>/index.php?op=configuracion&accion=modificar&tab=captcha-tab">
                        <div class="row mt-5">
                            <label class="col-md-12">
                                CAPTCHA KEY:<br/>
                                <input type="text" class="form-control" name="c-key"
                                       value="<?= $captchaData['data']["captcha_key"] ? $captchaData['data']["captcha_key"] : '' ?>" required/>
                            </label>
                            <label class="col-md-12">
                                CAPTCHA SECRET:<br/>
                                <input type="text" class="form-control" name="c-secret"
                                       value="<?= $captchaData['data']["captcha_secret"] ? $captchaData['data']["captcha_secret"] : '' ?>" required/>
                            </label>
                            <div class="col-md-12">
                                <button class="btn btn-primary" type="submit" name="agregar-captcha">Guardar cambios</button>
                            </div>
                        </div>
                    </form>
                </div>

                <?php
                if (isset($_POST["agregar-config"])) {
                    $config->set("content_header", $funciones->antihack_mysqli(isset($_POST["cnf-header"]) ? $_POST["cnf-header"] : ''));
                    $error = $config->addConfigHeader();
                    if ($error) {
                        $funciones->headerMove(URLADMIN . '/index.php?op=configuracion&accion=modificar&tab=config-header');
                    } else {
                        echo "<div class=\"alert alert-danger\" role=\"alert\">$error</div>";
                    }
                }
                ?>
                <div class="tab-pane fade" id="config-header" role="tabpanel" aria-labelledby="nav-header-tab">

                    <form method="post" action="<?= URLADMIN ?>/index.php?op=configuracion&accion=modificar&tab=config-header">
                        <div class="row mt-5">
                            <label class="col-md-12">
                                CONFIG HEADER:<br/>
                                <textarea class="form-control" name="cnf-header" rows="20"><?= $configHeader["data"]["content_header"] ?></textarea>
                            </label>
                            <div class="col-md-12">
                                <button class="btn btn-primary" type="submit" name="agregar-config">Guardar cambios</button>
                            </div>
                        </div>
                    </form>
                </div>

                                
                <?php
                if (isset($_POST["agregar-exportadorMeli"])) {
                    $config->set("clasica", isset($_POST["clasica"]) ? $funciones->antihack_mysqli($_POST["clasica"]) : '');
                    $config->set("premium", isset($_POST["premium"]) ? $funciones->antihack_mysqli($_POST["premium"]) : '');
                    $config->set("link_json", isset($_POST["link_json"]) ? $funciones->antihack_mysqli($_POST["link_json"]) : '');
                    $config->set("carpeta_img", isset($_POST["carpeta_img"]) ? $funciones->antihack_mysqli($_POST["carpeta_img"]) : '');
                    $config->calcular_envio = isset($_POST["calcular_envio"]) ? $funciones->antihack_mysqli($_POST["calcular_envio"]) : 0;
                    if ($config->addExportadorMeli()) {
                        $funciones->headerMove(URL_ADMIN . '/index.php?op=configuracion&accion=modificar&tab=exportadorMeli-home');
                    } else {
                        echo "<div class=\"alert alert-danger\" role=\"alert\">$error</div>";
                    }
                }
                ?>
                <div class="tab-pane fade" id="exportadorMeli-home" role="tabpanel" aria-labelledby="nav-home-tab">
                    <form method="post" action="<?= URL_ADMIN ?>/index.php?op=configuracion&accion=modificar&tab=exportadorMeli-tab">
                        <div class="row mt-5">
                            <div class="col-md-6">
                                <label for="basic-URL_ADMIN">Porcentaje publicación clásica:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1">%</span>
                                    </div>
                                    <input required class="form-control" name="clasica" min="-100" max="100" type="number" value="<?= $exportadorMeliData['data']["clasica"] ? $exportadorMeliData['data']["clasica"] : '0' ?>" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="basic-URL_ADMIN">Porcentaje publicación premium:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1">%</span>
                                    </div>
                                    <input required class="form-control" name="premium" min="-100" max="100" type="number" value="<?= $exportadorMeliData['data']["premium"] ? $exportadorMeliData['data']["premium"] : '0' ?>" />
                                </div>
                            </div>
                            <div class="col-md-12">
                                <br><input type="checkbox" name="calcular_envio" value="<?= $exportadorMeliData['data']["calcular_envio"] ? $exportadorMeliData['data']["calcular_envio"] : 1 ?>" <?= ($exportadorMeliData['data']["calcular_envio"] == 1) ? 'checked' : '' ?> />
                                <label for="basic-URL_ADMIN">¿Calcular automaticamente el costo del envío por medio de MercadoLibre?:</label><br><br>
                            </div>
                            <div class="col-md-6">
                                <label for="basic-URL_ADMIN">Link Json:</label>
                                <div class="input-group">
                                    <input class="form-control" name="link_json" type="url" value="<?= $exportadorMeliData['data']["link_json"] ? $exportadorMeliData['data']["link_json"] : '' ?>" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="basic-URL_ADMIN">Carpeta Imágenes:</label>
                                <div class="input-group">
                                    <input class="form-control" name="carpeta_img" type="url" value="<?= $exportadorMeliData['data']["carpeta_img"] ? $exportadorMeliData['data']["carpeta_img"] : '' ?>" />
                                </div>
                            </div>
                            <div class="col-md-12">
                                <hr />
                                <button class="btn btn-primary" type="submit" name="agregar-exportadorMeli">Guardar cambios</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>


<script>
    $(document).ready(function () {
        $('#<?= $tab ?>').click();
    })
</script>