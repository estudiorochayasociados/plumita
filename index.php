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
//Categorias
$ultimos_productos = $producto->listWithOps('', 'RAND()', '12');
$categoria->set("area", "productos");
$categorias_ = $categoria->listForArea('');
$ultimas_categorias = array();
foreach ($ultimos_productos as $prods) {
    $categoria->set('cod', $prods['data']['categoria']);
    $cate = $categoria->view();
    if (($key = array_search($cate, $categorias_)) !== false) {
        array_push($ultimas_categorias, $cate);
        unset($categorias_[$key]);
    }
}
$ultimas_novedades = $novedad->listWithOps('', '', '3');
//Banners
$categoria->set("area", "banners");
$categorias_banners = $categoria->listForArea('');
foreach ($categorias_banners as $catB) {
    if ($catB['titulo'] == "Pie 1/2") {
        $banner->set("categoria", $catB['cod']);
        $banner_data_pie_medio = $banner->listForCategory('RAND()', '2');
    }
}
//Sliders
$categoria->set("area", "sliders");
$categorias_sliders = $categoria->listForArea('');
foreach ($categorias_sliders as $catS) {
    if ($catS['titulo'] == "Principal") {
        $slider->set("categoria", $catS['cod']);
        $sliders_data = $slider->listForCategory();
    }
}
//
$template->themeNav();
?>
<!--================Home Carousel Area =================-->
<div id="carouselControls" class="carousel slide mb-50" data-ride="carousel">
    <div class="carousel-inner carousel-index">
        <?php
        $activo = 0;
        foreach ($sliders_data as $sli) {
            ?>
            <div class="carousel-item <?php if ($activo == 0) { echo 'active'; $activo++; } ?>" >
                <img class="d-block w-100" src="<?= URL . '/' . $sli['imagenes']['0']['ruta']; ?>" alt="First slide">
            </div>
            <?php
        }
        ?>
    </div>
    <a class="carousel-control-prev" href="#carouselControls" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#carouselControls" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
</div>
<!--================End Home Carousel Area =================-->
<!--================Special Offer Area =================-->
<section class="special_offer_area">
    <div class="container">
        <div class="row">
            <?php
            foreach ($banner_data_pie_medio as $banM) {
                $banner->set("id", $banM['data']['id']);
                $value = $banM['data']['vistas'] + 1;
                $banner->set("vistas", $value);
                $banner->increaseViews();
                ?>
                <div class="col-lg-6 mt-5">
                    <a href="<?= $banM['data']['link']; ?>">
                        <div class="special_offer_item" style="height:300px;background:url(<?= $banM['imagenes']['0']['ruta']; ?>) no-repeat center center/cover;">
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
<!--================Latest Product isotope Area =================-->
<section class="fillter_latest_product">
    <div class="container">
        <div class="single_c_title">
            <h2>Nuestros últimos productos</h2>
        </div>
        <div class="fillter_l_p_inner">
            <ul class="fillter_l_p">
                <li class="active" data-filter="*"><a href="#">Todos</a></li>
                <?php
                foreach ($ultimas_categorias as $cats) {
                    ?>
                    <li class="" data-filter=".<?= $funciones->normalizar_link($cats['titulo']); ?>"><a href="#"><?= ucfirst($cats['titulo']); ?></a></li>
                    <?php
                }
                ?>
            </ul>
            <div class="row isotope_l_p_inner">
                <?php
                foreach ($ultimos_productos as $prod) {
                    ?>
                    <div class="col-lg-3 col-md-4 col-sm-6 <?= $funciones->normalizar_link($prod['categorias']['titulo']); ?>">
                        <div class="l_product_item">
                            <a href="<?= URL . '/producto/' . $funciones->normalizar_link($prod['data']["titulo"]) . '/' . $prod['data']['cod'] ?>">
                                <div class="l_p_img" style="height:300px;background:url(<?= $prod['imagenes']['0']['ruta']; ?>) no-repeat center center/70%;">
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
                                <?php
                                if (!empty($_SESSION['usuarios'])) {
                                    if ($prod['data']['precio_mayorista'] != 0) {
                                        ?>
                                        <h5 class="precios">
                                            $ <?= $prod['data']['precio_mayorista']; ?>
                                        </h5>
                                        <h5 class="precios precio-desc">
                                            $ <?= $prod['data']['precio']; ?>
                                        </h5>
                                        <?php
                                    } else {
                                        if ($prod['data']['precio_descuento'] != 0) {
                                            ?>
                                            <h5 class="precios">
                                                $ <?= $prod['data']['precio_descuento']; ?>
                                            </h5>
                                            <h5 class="precios precio-desc">
                                                $ <?= $prod['data']['precio']; ?>
                                            </h5>
                                            <?php
                                        } else {
                                            ?>
                                            <h5>
                                                $ <?= $prod['data']['precio']; ?>
                                            </h5>
                                            <?php
                                        }
                                    }
                                } else {
                                    if ($prod['data']['precio_descuento'] != 0) {
                                        ?>
                                        <h5 class="precios">
                                            $ <?= $prod['data']['precio_descuento']; ?>
                                        </h5>
                                        <h5 class="precios precio-desc">
                                            $ <?= $prod['data']['precio']; ?>
                                        </h5>
                                        <?php
                                    } else {
                                        ?>
                                        <h5>
                                            $ <?= $prod['data']['precio']; ?>
                                        </h5>
                                        <?php
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
</section>
<!--================End Latest Product isotope Area =================-->
<!--================Form Blog Area =================-->
<section class="from_blog_area">
    <div class="container">
        <div class="from_blog_inner">
            <div class="c_main_title">
                <h2>Blog</h2>
            </div>
            <div class="row">
                <?php
                foreach ($ultimas_novedades as $nov) {
                    ?>
                    <div class="col-lg-4 col-sm-6">
                        <a href="<?= URL . '/blog/' . $funciones->normalizar_link($nov['data']["titulo"]) . '/' . $nov['data']['cod'] ?>">
                            <div class="from_blog_item" style="height:350px;background:url(<?= $nov['imagenes']['0']['ruta']; ?>) no-repeat center center/cover;">
                                <div class="f_blog_text">
                                    <?php
                                    if ($nov['data']['categoria'] != '') {
                                        ?>
                                        <h5><?= ucfirst($nov['categorias']['titulo']); ?></h5>
                                        <?php
                                    }
                                    ?>
                                    <p><?= ucfirst(substr(strip_tags($nov['data']['titulo']), 0, 60)); ?>...</p>
                                    <h6><?= $nov['fecha_actual'] ?></h6>
                                </div>
                            </div>
                        </a>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
</section>
<!--================End Form Blog Area =================-->
<?php
$template->themeEnd();
?>
