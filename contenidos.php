<?php
require_once "Config/Autoload.php";
Config\Autoload::runSitio();
$template = new Clases\TemplateSite();
$id = isset($_GET["id"]) ? $_GET["id"] : '';
$contenido = new Clases\Contenidos();
$contenido->set("cod", $id);
$contenido_data = $contenido->view();
$template->set("title", TITULO . " | Empresa");
$template->set("imagen", LOGO);
$template->set("keywords", "");
$template->set("description", ucfirst(substr(strip_tags($contenido_data['contenido']), 0, 160)));
$template->themeInit();

$template->themeNav();
?>
<section class="categories_banner_area">
    <div class="container">
        <div class="c_banner_inner">
            <h3><?= ucfirst($contenido_data['cod']); ?></h3>
            <ul>
                <li><a href="<?= URL ?>/index">Inicio</a></li>
                <li class="current"><a href="#"><?= ucfirst($contenido_data['cod']); ?></a></li>
            </ul>
        </div>
    </div>
</section>
<section class="">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="product_details_text blog-description">
                    <p><?= ucfirst(strip_tags($contenido_data['contenido'])); ?></p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php $template->themeEnd(); ?>
