<?php
require_once "Config/Autoload.php";
Config\Autoload::runSitio();
//
$template = new Clases\TemplateSite();
$funciones = new Clases\PublicFunction();
$producto = new Clases\Productos();
$carrito = new Clases\Carrito();
//Producto
$cod = $funciones->antihack_mysqli(isset($_GET["cod"]) ? $_GET["cod"] : '');
$producto->set("cod", $cod);
$producto_data = $producto->view_();
//Productos relacionados
$categoria_cod = $producto_data['data']['categoria'];
$filter = array("categoria='$categoria_cod'", "cod!='$cod'");
$productos_relacionados_data = $producto->listWithOps($filter, '', '3');
//
if (!empty($producto_data['imagenes'][0]['ruta'])) {
    $ruta_ = URL . "/" . $producto_data['imagenes'][0]['ruta'];
} else {
    $ruta_ = '';
}
$template->set("title", TITULO . " | " . ucfirst(strip_tags($producto_data['data']['titulo'])));
$template->set("description", ucfirst(strip_tags($producto_data['data']['desarrollo'])));
$template->set("keywords", ucfirst(strip_tags($producto_data['data']['titulo'])));
$template->set("imagen", $ruta_);
$template->set("favicon", FAVICON);
$template->themeInit();
//Carro
$carro = $carrito->return();
$url_limpia = CANONICAL;
$url_limpia = str_replace("?success", "", $url_limpia);
$url_limpia = str_replace("?error", "", $url_limpia);
$template->themeNav();
?>
    <!--================Categories Banner Area =================-->
    <section class="solid_banner_area">
        <div class="container">
            <div class="solid_banner_inner navegador">
                <h3><?= ucfirst($producto_data['data']['titulo']); ?></h3>
                <ul>
                    <li><a href="<?= URL ?>/index">Inicio</a></li>
                    <li><a href="<?= URL ?>/productos">Productos</a></li>
                    <li class="current"><a href="#"><?= ucfirst($producto_data['data']['titulo']); ?></a></li>
                </ul>
            </div>
        </div>
    </section>
    <!--================End Categories Banner Area =================-->
    <!--================Product Details Area =================-->
    <section class="product_details_area">
        <div class="container">
            <div class="row">
                <div class="col-lg-4">
                    <div class="product_details_slider">
                        <div id="product_slider" class="rev_slider" data-version="5.3.1.6">
                            <ul>
                                <?php foreach ($producto_data['imagenes'] as $img) { ?>
                                    <!-- SLIDE  -->
                                    <li data-index="rs-<?= $img['id'] ?>" data-transition="scaledownfrombottom" data-slotamount="7" data-easein="Power3.easeInOut" data-easeout="Power3.easeInOut" data-masterspeed="1500" data-thumb="<?= URL . '/' . $img['ruta']; ?>" data-rotate="0" data-fstransition="fade" data-fsmasterspeed="1500" data-fsslotamount="7" data-saveperformance="off" data-title="<?= ucfirst($producto_data['data']['titulo']); ?>" data-param1="25/08/2015"
                                        data-description="<?= ucfirst($producto_data['data']['titulo']); ?>">
                                        <!-- MAIN IMAGE -->
                                        <img src="<?= URL . '/' . $img['ruta']; ?>" alt="<?= ucfirst($producto_data['data']['titulo']); ?>" data-bgposition="center center" data-bgfit="contain" data-bgrepeat="no-repeat" data-bgparallax="5" class="rev-slidebg" data-no-retina>
                                        <!-- LAYERS -->
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="product_details_text">
                        <h3><?= ucfirst($producto_data['data']['titulo']); ?></h3>
                        <p><?= ucfirst($producto_data['data']['desarrollo']); ?></p>
                        <div class="shareing_icon">
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
    <!--================End Related Product Area =================-->
    <section class="related_product_area">
        <div class="container">
            <div class="related_product_inner">
                <h2 class="single_c_title">Productos relacionados</h2>
                <div class="row">
                    <?php foreach ($productos_relacionados_data as $prod) { ?>
                        <div class="col-lg-4 col-sm-6">
                            <div class="l_product_item">
                                <a href="<?= URL . '/producto/' . $funciones->normalizar_link($prod['data']["titulo"]) . '/' . $prod['data']['cod'] ?>">
                                    <div class="l_p_img" style="height:400px;background:url(<?= URL . '/' . $prod['imagenes']['0']['ruta']; ?>) no-repeat center center/70%;">
                                    </div>
                                </a>
                                <div class="l_p_text">
                                    <ul>
                                        <li>
                                            <a class="add_cart_btn" href="<?= URL . '/producto/' . $funciones->normalizar_link($prod['data']["titulo"]) . '/' . $prod['data']['cod'] ?>">
                                                Ver
                                            </a>
                                        </li>
                                    </ul>
                                    <h4><?= ucfirst(substr(strip_tags($prod['data']['titulo']), 0, 40)); ?></h4>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </section>
    <!--================End Related Product Area =================-->
<?php $template->themeEnd(); ?>