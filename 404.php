<?php
require_once "Config/Autoload.php";
Config\Autoload::runSitio();
//
$template = new Clases\TemplateSite();
$template->set("title", TITULO . " | Página no encontrada");
$template->set("description", "Página no encontrada");
$template->set("keywords", "Página no encontrada");
$template->set("favicon", LOGO);
$template->themeInit();

$template->themeNav();
?>
<!--================login Area =================-->
<section class="error_area p_100">
    <div class="container">
        <div class="error_inner">
            <h4>404</h4>
            <h5>Error - Página no encontrada</h5>
            <h6>volver al <a href="<?=URL?>/index">Inicio</a></h6>
        </div>
    </div>
</section>
<!--================End login Area =================-->
<?php
$template->themeEnd();
?>
