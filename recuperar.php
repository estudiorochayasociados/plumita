<?php
require_once "Config/Autoload.php";
Config\Autoload::runSitio();
//
$template = new Clases\TemplateSite();
$funciones = new Clases\PublicFunction();
$enviar = new Clases\Email();
$usuarios = new Clases\Usuarios();
//
$template->set("title", TITULO . " | Recuperar contraseña");
$template->set("description", "Recuperar contraseña " . TITULO);
$template->set("keywords", "Recuperar contraseña " . TITULO);
$template->set("favicon", FAVICON);
$template->themeInit();
//

?>
<!--================Categories Banner Area =================-->
<section class="solid_banner_area">
    <div class="container">
        <div class="solid_banner_inner navegador">
            <h3>Recuperar contraseña</h3>
            <ul>
                <li><a href="<?= URL ?>/index">Inicio</a></li>
                <li class="current"><a href="<?= URL ?>/productos">Recuperar contraseña</a></li>
            </ul>
        </div>
    </div>
</section>
<!--================End Categories Banner Area =================-->

<!--================Contact Area =================-->
<section class="contact_area ">
    <div class="container">
        <div class="contact_form_inner">
            <h3>Recuperar contraseña</h3>
            <?php
            if (isset($_POST["email"])) {
                $email = $funciones->antihack_mysqli(isset($_POST["email"]) ? $_POST["email"] : '');
                $pass = substr(md5(uniqid(rand())), 0, 8);

                $usuarioCod = $usuarios->list(["email = '$email'"])[0]["cod"];
                $usuarios->set("cod", $usuarioCod);
                $usuario = $usuarios->editSingle("password", $pass);

                $enviar->set("asunto", "Recuperar contraseña");
                $enviar->set("receptor", $email);
                $enviar->set("emisor", EMAIL);
                $enviar->set("mensaje", "Tu nueva contraseña es : " . $pass);
                if ($enviar->emailEnviar() == 1) {
                    echo '<div class="col-md-12 alert alert-success" role="alert">¡Tu nueva contraseña fue enviada a tu casilla de email!</div>';
                } else {
                    echo '<div class="col-md-12 alert alert-danger" role="alert">¡No se ha podido enviar!</div>';
                }
            }
            ?>
            <form class="contact_us_form row" method="post">
                <div class="col-lg-6">
                    <input type="email" class="form-control" name="email" placeholder="Email" required>
                </div>
                <div class="col-lg-3">
                    <button type="submit" name="enviar" class="btn btn-info form-control">Recuperar</button>
                </div>
            </form>
        </div>
    </div>
</section>
<!--================End Contact Area =================-->
<?php
$template->themeEnd();
?>
