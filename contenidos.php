<?php
require_once "Config/Autoload.php";
Config\Autoload::runSitio();
$template = new Clases\TemplateSite();
$id = isset($_GET["id"]) ? $_GET["id"] : '';
$contenido = new Clases\Contenidos();
$contenido->set("cod", $id);
$contenido_data = $contenido->view();
$template->set("title", TITULO . " | ".ucfirst(strip_tags($contenido_data['data']['cod'])));
$template->set("description", ucfirst(substr(strip_tags($contenido_data['data']['contenido']), 0, 160)));
$template->set("keywords", TITULO . " | Empresa");
$template->set("favicon", FAVICON);
$template->themeInit();
if ($contenido_data['data']['cod']=="servicio-técnico"){
    $cod="Servicio Técnico";
}else{
    $cod=$contenido_data['data']['cod'];
}

?>
<section class="solid_banner_area">
    <div class="container">
        <div class="solid_banner_inner navegador">
            <h3><?= ucfirst($cod); ?></h3>
            <ul>
                <li><a href="<?= URL ?>/index">Inicio</a></li>
                <li class="current"><a href="#"><?= ucfirst($cod); ?></a></li>
            </ul>
        </div>
    </div>
</section>
<section class="mt-15">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="">
                    <p><?= ucfirst($contenido_data['data']['contenido']); ?></p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php $template->themeEnd(); ?>
