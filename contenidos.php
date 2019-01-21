<?php
require_once "Config/Autoload.php";
Config\Autoload::runSitio();
$template = new Clases\TemplateSite();
$id = isset($_GET["id"]) ? $_GET["id"] : '';
$contenido = new Clases\Contenidos();
$contenido->set("cod", $id);
$contenido_data = $contenido->view();
$template->set("title", TITULO . " | ".ucfirst(strip_tags($contenido_data['cod'])));
$template->set("description", ucfirst(substr(strip_tags($contenido_data['contenido']), 0, 160)));
$template->set("keywords", TITULO . " | Empresa");
$template->set("favicon", FAVICON);
$template->themeInit();

$template->themeNav();
?>
<section class="solid_banner_area">
    <div class="container">
        <div class="solid_banner_inner navegador">
            <h3><?= ucfirst($contenido_data['cod']); ?></h3>
            <ul>
                <li><a href="<?= URL ?>/index">Inicio</a></li>
                <li class="current"><a href="#"><?= ucfirst($contenido_data['cod']); ?></a></li>
            </ul>
        </div>
    </div>
</section>
<section class="mt-15">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="">
                    <p><?= ucfirst($contenido_data['contenido']); ?></p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php $template->themeEnd(); ?>
