<?php
require_once "Config/Autoload.php";
Config\Autoload::runSitio();
//
$template = new Clases\TemplateSite();
$funciones = new Clases\PublicFunction();
$producto = new Clases\Productos();
$categoria = new Clases\Categorias();
$novedad = new Clases\Novedades();
$banner = new Clases\Banner();
$slider = new Clases\Sliders();
//
$template->set("title", TITULO . " | Inicio");
$template->set("description", "Plumita S.R.L es una fábrica de Cortadoras y Bordeadoras de cesped, de muy alto nivel con distribución en todo el país.");
$template->set("keywords", "bordeadora, cortadora, cesped, pasto, yuyo, alto nivel, durardera, maquinas, compra");
$template->set("favicon", FAVICON);
$template->themeInit();
//Banners
$bannerCategorias = $banner->list(["categoria='825bc91df3'"], '', '');
//Sliders
$sliders_data = $slider->list([("categoria = '3473ddb26e'")], "", "");
//

?>
<!--================Home Carousel Area =================-->
<div id="carouselControls" class="carousel slide mb-50" data-ride="carousel">
    <div class="carousel-inner carousel-index">
        <?php foreach ($sliders_data as $key => $sli) { ?>
            <div class="carousel-item <?= $key == 0 ? 'active' : '' ?>">
                <img class="d-block w-100" src="<?= URL . '/' . $sli['image']['ruta']; ?>" alt="First slide">
            </div>
        <?php } ?>
    </div>
    <?php if (count($sliders_data) > 1) { ?>
        <a class="carousel-control-prev" href="#carouselControls" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselControls" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    <?php } ?>
</div>
<!--================End Home Carousel Area =================-->
<!--================Special Offer Area =================-->
<section class="special_offer_area">
    <div class="container">
        <div class="row">
            <?php
            $amount = count($bannerCategorias);
            foreach ($bannerCategorias as $key => $ban_) {
                if ($key == ($amount - 1)) {
                    if ($amount % 2 != 0) {
                        echo "<div class='col-lg-3'></div>";
                    }
                }
            ?>
                <div class="col-lg-6 mt-5">
                    <a href="<?= URL . "/" . $ban_['data']['link']; ?>">
                        <div class="special_offer_item" style="height:300px;background:url(<?= $ban_['image']['ruta']; ?>) no-repeat center center/cover;">
                        </div>
                    </a>
                </div>
            <?php
            }
            ?>
        </div>
    </div>
</section>
<!--================End Special Offer Area =================-->
<?php $template->themeEnd(); ?>