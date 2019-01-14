<?php
require_once "Config/Autoload.php";
Config\Autoload::runSitio();
//
$template = new Clases\TemplateSite();
$funciones = new Clases\PublicFunction();
$novedades = new Clases\Novedades();
$imagen = new Clases\Imagenes();
$categoria = new Clases\Categorias();
//Blog
$cod = isset($_GET["cod"]) ? $_GET["cod"] : '';
$novedades->set("cod", $cod);
$novedades_data = $novedades->view();
$fecha = explode("-", $novedades_data['fecha']);
$imagen->set("cod", $cod);
$imagenes_data = $imagen->listForProduct();
//
$template->set("title", TITULO . " | Inicio");
$template->set("description", "");
$template->set("keywords", "");
$template->set("favicon", LOGO);
$template->themeInit();

$template->themeNav();
?>
<!--================Categories Banner Area =================-->
<section class="categories_banner_area">
    <div class="container">
        <div class="c_banner_inner">
            <h3><?= ucfirst($novedades_data['titulo']); ?></h3>
            <ul>
                <li><a href="<?= URL ?>/index">Inicio</a></li>
                <li><a href="<?= URL ?>/blogs">Blogs</a></li>
                <li class="current"><a href="#"><?= ucfirst($novedades_data['titulo']); ?></a></li>
            </ul>
        </div>
    </div>
</section>
<!--================End Categories Banner Area =================-->

<!--================Product Details Area =================-->
<section class="product_details_area">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="product_details_text blog-description">
                    <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
                        <div class="carousel-inner">
                            <?php
                            $activo = 0;
                            foreach ($imagenes_data as $img) {
                                ?>
                                <div class="carousel-item <?php if ($activo==0){echo 'active';$activo++;} ?>" style=" height: 600px; background: url(<?= URL . '/'.$img['ruta'] ?>) no-repeat center center/contain;">
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                        <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>

                    <h3><?= ucfirst($novedades_data['titulo']); ?></h3>
                    <div class="add_review pl-0">
                        <?= $fecha[2] . '/' . $fecha[1] . '/' . $fecha[0] ?>
                    </div>
                    <p><?= ucfirst(strip_tags($novedades_data['desarrollo'])); ?></p>
                    <div class="shareing_icon mb-10">
                        <h5>Compartir :</h5>
                        <div class="a2a_kit a2a_kit_size_32 a2a_default_style">
                            <a class="a2a_button_facebook"></a>
                            <a class="a2a_button_twitter"></a>
                            <a class="a2a_button_google_plus"></a>
                            <a class="a2a_button_pinterest"></a>
                            <a class="a2a_button_whatsapp"></a>
                            <a class="a2a_button_facebook_messenger"></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--================End Product Details Area =================-->
<?php
$template->themeEnd();
?>