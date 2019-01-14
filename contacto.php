<?php
require_once "Config/Autoload.php";
Config\Autoload::runSitio();
//
$template = new Clases\TemplateSite();
$funciones = new Clases\PublicFunction();
$enviar = new Clases\Email();
//
$template->set("title", TITULO . " | Inicio");
$template->set("description", "");
$template->set("keywords", "");
$template->set("favicon", LOGO);
$template->themeInit();
//
$template->themeNav();
?>
<!--================Categories Banner Area =================-->
<section class="solid_banner_area">
    <div class="container">
        <div class="solid_banner_inner">
            <h3>Contacto</h3>
            <ul>
                <li><a href="<?= URL ?>/index">Inicio</a></li>
                <li><a href="#">Contacto</a></li>
            </ul>
        </div>
    </div>
</section>
<!--================End Categories Banner Area =================-->

<!--================Contact Area =================-->
<section class="contact_area p_100">
    <div class="container">
        <div class="row contact_details">
            <div class="col-lg-4 col-md-6">
                <div class="media">
                    <div class="d-flex">
                        <i class="fa fa-map-marker" aria-hidden="true"></i>
                    </div>
                    <div class="media-body">
                        <p><?= DIRECCION ?>.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="media">
                    <div class="d-flex">
                        <i class="fa fa-phone" aria-hidden="true"></i>
                    </div>
                    <div class="media-body">
                        <a href="tel:+1109171234567"><?= TELEFONO ?></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="media">
                    <div class="d-flex">
                        <i class="fa fa-envelope" aria-hidden="true"></i>
                    </div>
                    <div class="media-body">
                        <a href="mailto:<?= EMAIL ?>"><?= EMAIL ?></a>
                        <a href="mailto:<?= EMAIL2 ?>"><?= EMAIL2 ?></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="contact_form_inner">
            <h3>Déjanos un mensaje</h3>
            <?php
            if (isset($_POST["enviar"])) {
                $nombre = $funciones->antihack_mysqli(isset($_POST["nombre"]) ? $_POST["nombre"] : '');
                $email = $funciones->antihack_mysqli(isset($_POST["email"]) ? $_POST["email"] : '');
                $consulta = $funciones->antihack_mysqli(isset($_POST["mensaje"]) ? $_POST["mensaje"] : '');

                $mensajeFinal = "<b>Nombre</b>: " . $nombre . " <br/>";
                $mensajeFinal .= "<b>Email</b>: " . $email . "<br/>";
                $mensajeFinal .= "<b>Consulta</b>: " . $consulta . "<br/>";

                //USUARIO
                $enviar->set("asunto", "Realizaste tu consulta");
                $enviar->set("receptor", $email);
                $enviar->set("emisor", EMAIL);
                $enviar->set("mensaje", $mensajeFinal);
                if ($enviar->emailEnviar() == 1) {
                    echo '<div class="alert alert-success" role="alert">¡Consulta enviada correctamente!</div>';
                }

                $mensajeFinalAdmin = "<b>Nombre</b>: " . $nombre . " <br/>";
                $mensajeFinalAdmin .= "<b>Email</b>: " . $email . "<br/>";
                $mensajeFinalAdmin .= "<b>Consulta</b>: " . $consulta . "<br/>";
                //ADMIN
                $enviar->set("asunto", "Consulta Web");
                $enviar->set("receptor", EMAIL);
                $enviar->set("mensaje", $mensajeFinalAdmin);
                if ($enviar->emailEnviar() == 0) {
                    echo '<div class="alert alert-danger" role="alert">¡No se ha podido enviar la consulta!</div>';
                }
            }
            ?>
            <form class="contact_us_form row" method="post" >
                <div class="form-group col-lg-6">
                    <input type="text" class="form-control"  name="nombre" placeholder="Nombre completo *">
                </div>
                <div class="form-group col-lg-6">
                    <input type="email" class="form-control"  name="email" placeholder="Email *">
                </div>
                <div class="form-group col-lg-12">
                    <textarea class="form-control" name="mensaje"  rows="1" placeholder="Su mensaje..."></textarea>
                </div>
                <div class="form-group col-lg-12">
                    <button type="submit" name="enviar" class="btn update_btn form-control">Enviar</button>
                </div>
            </form>
        </div>
        <br>
        <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d13616.663078982812!2d-62.1037877!3d-31.437103!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x4677b94ec2697cb!2sPlumita.!5e0!3m2!1ses!2sar!4v1547498604531" width="100%" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>
    </div>
</section>
<!--================End Contact Area =================-->
<?php
$template->themeEnd();
?>
