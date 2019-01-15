<?php
require_once "Config/Autoload.php";
Config\Autoload::runSitio();
//
$template = new Clases\TemplateSite();
$funciones = new Clases\PublicFunction();
$producto = new Clases\Productos();
$categoria = new Clases\Categorias();
$imagen = new Clases\Imagenes();
$banner = new Clases\Banner();
$carrito = new Clases\Carrito();
//Producto
$cod = isset($_GET["cod"]) ? $_GET["cod"] : '';
$producto->set("cod", $cod);
$producto_data = $producto->view();
$imagen->set("cod", $cod);
$imagenes_data = $imagen->listForProduct();
//Productos relacionados
$categoria_cod = $producto_data['categoria'];
$filter = array("categoria='$categoria_cod'");
$productos_relacionados_data = $producto->listWithOps($filter, '', '3');
//
$template->set("title", TITULO . " | Inicio");
$template->set("description", "");
$template->set("keywords", "");
$template->set("favicon", LOGO);
$template->themeInit();
//Carro
$carro = $carrito->return();
$carroEnvio = $carrito->checkEnvio();
$carroPago = $carrito->checkPago();
//
?>
<?php
$template->themeNav();
?>

    <!--================Categories Banner Area =================-->
    <section class="categories_banner_area">
        <div class="container">
            <div class="c_banner_inner">
                <h3><?= ucfirst($producto_data['titulo']); ?></h3>
                <ul>
                    <li><a href="<?= URL ?>/index">Inicio</a></li>
                    <li><a href="<?= URL ?>/productos">Productos</a></li>
                    <li class="current"><a href="#"><?= ucfirst($producto_data['titulo']); ?></a></li>
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
                                <?php
                                foreach ($imagenes_data as $img) {
                                    ?>
                                    <!-- SLIDE  -->
                                    <li data-index="rs-<?= $img['id'] ?>" data-transition="scaledownfrombottom" data-slotamount="7" data-easein="Power3.easeInOut" data-easeout="Power3.easeInOut" data-masterspeed="1500" data-thumb="<?= URL . '/' . $img['ruta']; ?>" data-rotate="0" data-fstransition="fade" data-fsmasterspeed="1500" data-fsslotamount="7" data-saveperformance="off" data-title="<?= ucfirst($producto_data['titulo']); ?>" data-param1="25/08/2015" data-description="<?= ucfirst($producto_data['titulo']); ?>">
                                        <!-- MAIN IMAGE -->
                                        <img src="<?= URL . '/' . $img['ruta']; ?>" alt="<?= ucfirst($producto_data['titulo']); ?>" data-bgposition="center center" data-bgfit="contain" data-bgrepeat="no-repeat" data-bgparallax="5" class="rev-slidebg" data-no-retina>
                                        <!-- LAYERS -->
                                    </li>
                                    <?php
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="product_details_text">
                        <h3><?= ucfirst($producto_data['titulo']); ?></h3>
                        <h6>Disponibilidad: <span> consultar</span></h6>
                        <h4>$<?= $producto_data['precio']; ?></h4>
                        <p><?= ucfirst(strip_tags($producto_data['desarrollo'])); ?></p>
                        <div class="quantity">
                            <?php
                            if (isset($_POST["enviar"])) {
                                if ($carroEnvio != '') {
                                    $carrito->delete($carroEnvio);
                                }

                                if ($carroPago != '') {
                                    $carrito->delete($carroPago);
                                }

                                $carrito->set("id", $producto_data['id']);
                                $carrito->set("cantidad", $_POST["cantidad"]);
                                $carrito->set("titulo", $producto_data['titulo']);
                                $carrito->set("precio", $producto_data['precio']);/*
                                if (($producto_data['precioDescuento'] <= 0) || $producto_data["precioDescuento"] == '') {
                                    $carrito->set("precio", $producto_data['precio']);
                                } else {
                                    $carrito->set("precio", $producto_data['precioDescuento']);
                                }


                                if (@$_SESSION["usuarios"]["descuento"] == 1) {
                                    if ($producto_data['precio'] != $producto_data['precio_mayorista']) {
                                        $carrito->set("precio", $producto_data['precio_mayorista']);
                                    } else {
                                        $carrito->set("precio", $producto_data['precio']);
                                    }
                                } else {
                                    $carrito->set("precio", $producto_data['precio']);
                                }
*/
                                $carrito->add();
                                //$funciones->headerMove(CANONICAL . "?success");
                            }
                            if (strpos(CANONICAL, "success") == true) {
                                echo "<div class='alert alert-success'>Agregaste un producto a tu carrito, querés <a href='" . URL . "/carrito'><b>pasar por caja</b></a> o <a href='" . URL . "/productos'><b>seguir comprando</b></a></div>";
                            }
                            ?>
                            <form method="post">

                            <div class="custom">
                                <button onclick="var result = document.getElementById('sst'); var sst = result.value; if( !isNaN( sst ) &amp;&amp; sst > 0 ) result.value--;return false;" class="reduced items-count" type="button"><i class="icon_minus-06"></i></button>
                                <input type="text" name="cantidad" id="sst" maxlength="12" value="01" title="Quantity:" class="input-text qty">
                                <button onclick="var result = document.getElementById('sst'); var sst = result.value; if( !isNaN( sst )) result.value++;return false;" class="increase items-count" type="button"><i class="icon_plus"></i></button>
                            </div>
                            <button class="add_cart_btn" name="enviar" >Añadir</button>
                            </form>
                        </div>
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
                    <?php
                    foreach ($productos_relacionados_data as $prod) {
                        $imagen->set("cod",$prod['cod']);
                        $img = $imagen->view();
                        ?>
                        <div class="col-lg-4 col-sm-6">
                            <div class="l_product_item">
                                <a href="<?= URL . '/producto/' . $funciones->normalizar_link($prod["titulo"]) . '/' . $prod['cod'] ?>">
                                    <div class="l_p_img" style="height:300px;background:url(<?=URL.'/'. $img['ruta']; ?>) no-repeat center center/70%;">
                                    </div>
                                </a>
                                <div class="l_p_text">
                                    <ul>
                                        <li>
                                            <a class="add_cart_btn" href="<?= URL . '/producto/' . $funciones->normalizar_link($prod["titulo"]) . '/' . $prod['cod'] ?>">
                                                Ver
                                            </a>
                                        </li>
                                    </ul>
                                    <h4><?= ucfirst(substr(strip_tags($prod['titulo']), 0, 40)); ?></h4>
                                    <h5>
                                        $ <?= $prod['precio']; ?>
                                    </h5>
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
    <!--================End Related Product Area =================-->
<?php
$template->themeEnd();
?>