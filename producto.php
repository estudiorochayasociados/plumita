<?php
require_once "Config/Autoload.php";
Config\Autoload::runSitio();
//
$template = new Clases\TemplateSite();
$funciones = new Clases\PublicFunction();
$producto = new Clases\Productos();
$atributo = new Clases\Atributos();
$combinacion = new Clases\Combinaciones();
//Producto
$cod = $funciones->antihack_mysqli(isset($_GET["cod"]) ? $_GET["cod"] : '');
$producto->set("cod", $cod);
$producto_data = $producto->view();
//Productos relacionados
$categoria_cod = $producto_data['data']['categoria'];
$filter = array("categoria='$categoria_cod'", "cod!='$cod'");
$productos_relacionados_data = $producto->list($filter, '', '3');
//
if (!empty($producto_data['imagenes'][0]['ruta'])) {
    $ruta_ = URL . "/" . $producto_data['imagenes'][0]['ruta'];
} else {
    $ruta_ = '';
}

$atributo->set("productoCod", $producto_data['data']['cod']);
$atributosData = $atributo->list();
$combinacion->set("codProducto", $producto_data['data']['cod']);
$combinacionData = $combinacion->listByProductCod();

$template->set("title", TITULO . " | " . ucfirst(strip_tags($producto_data['data']['titulo'])));
$template->set("description", ucfirst(strip_tags($producto_data['data']['desarrollo'])));
$template->set("keywords", ucfirst(strip_tags($producto_data['data']['titulo'])));
$template->set("imagen", $ruta_);
$template->set("favicon", FAVICON);
$template->themeInit();

?>
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
<section class="product_details_area">
    <div class="container">
        <div class="row">
            <div class="col-lg-4">
                <div class="product_details_slider">
                    <div id="product_slider" class="rev_slider" data-version="5.3.1.6">
                        <ul>
                            <?php foreach ($producto_data['images'] as $img) { ?>
                                <!-- SLIDE  -->
                                <li data-index="rs-<?= $img['id'] ?>" data-transition="scaledownfrombottom" data-slotamount="7" data-easein="Power3.easeInOut" data-easeout="Power3.easeInOut" data-masterspeed="1500" data-thumb="<?= URL . '/' . $img['ruta']; ?>" data-rotate="0" data-fstransition="fade" data-fsmasterspeed="1500" data-fsslotamount="7" data-saveperformance="off" data-title="<?= ucfirst($producto_data['data']['titulo']); ?>" data-param1="25/08/2015" data-description="<?= ucfirst($producto_data['data']['titulo']); ?>">
                                    <!-- MAIN IMAGE -->
                                    <img src="<?= URL . '/' . $img['ruta']; ?>" alt="<?= ucfirst($producto_data['data']['titulo']); ?>" data-bgposition="center center" data-bgfit="contain" data-bgrepeat="no-repeat" data-bgparallax="5" class="rev-slidebg" data-no-retina>
                                    <!-- LAYERS -->
                                </li>
                            <?php } ?>
                        </ul>
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
            <div class="col-lg-8">
                <div class="product_details_text">
                    <h3><?= ucfirst($producto_data['data']['titulo']); ?></h3>
                    <div class="product-details-content">
                        <?php
                        if (!empty($producto_data['data']['precio'])) {
                        ?>
                            <div class="single-product-price">
                                <span id="s-price" class="price new-price fs-25"><?= !empty($producto_data['data']['precio_descuento']) ? "$" . $producto_data['data']['precio_descuento'] : "$" . $producto_data['data']['precio'] ?></span>
                                <span class="regular-price tachado fs-18"><?= !empty($producto_data['data']['precio_descuento']) ? "$" . $producto_data['data']['precio'] : '' ?></span>
                            </div>
                        <?php
                        }

                        if (!empty($producto_data['data']['variable1'])) {
                        ?>
                            <b>Unidad de medida: </b><?= $producto_data['data']['variable1'] ?><br>
                        <?php
                        }

                        if (!empty($producto_data['data']['variable2'])) {
                            /* ?>
                                <b>Colores: </b><?= $producto_data['data']['variable2'] ?><br>
                            <?php*/
                        }

                        if (!empty($producto_data['data']['variable3'])) {
                        ?>
                            <b>Proveedor: </b><?= $producto_data['data']['variable3'] ?><br>
                        <?php
                        }

                        if (!empty($producto_data['data']['description'])) {
                        ?>
                            <div class="product-description">
                                <p><?= $producto_data['data']['description'] ?></p>
                            </div>
                        <?php
                        }
                        if (!empty($producto_data['data']['stock']) && !empty($producto_data['data']['precio']) && $producto_data['data']['variable9'] == 1) {
                        ?>
                            <div class="single-product-quantity mt-20">
                                <form class="add-quantity" id="cart-f" data-url="<?= URL ?>" onsubmit="addToCart()">
                                    <?php
                                    if (!empty($atributosData)) {
                                        foreach ($atributosData as $atrib) {
                                    ?>
                                            <label class="mb-10">
                                                <p class="m-0 p-0"><?= mb_strtoupper($atrib['atribute']['value']) ?></p>
                                                <select class="form-control width-200" name="atribute[<?= $atrib['atribute']['cod'] ?>]" <?= !empty($combinacionData) ? "onchange=\"refreshFront()\"" : '' ?> required>
                                                    <option disabled selected></option>
                                                    <?php
                                                    foreach ($atrib['atribute']['subatributes'] as $sub) {
                                                    ?>
                                                        <option value="<?= $sub['cod'] ?>">
                                                            <?= $sub['value'] ?>
                                                        </option>
                                                    <?php
                                                    }
                                                    ?>
                                                </select>
                                            </label>
                                    <?php
                                        }
                                        if (!empty($combinacionData)) {
                                            echo "<input type='hidden' name='combination' value='combination'>";
                                            echo "<input type='hidden' name='amount-atributes' value='" . count($atributosData) . "'>";
                                        }
                                    }
                                    ?>
                                    <p class="m-0 p-0">Cantidad</p>
                                    <input type="hidden" name="product" value="<?= $producto_data['data']['cod'] ?>">
                                    <div class="product-quantity">
                                        <input value="1" type="number" min="1" name="amount" id="amount" class="mb-20 form-control width-200" oninvalid="this.setCustomValidity('Ingresar un numero válido.')" oninput="this.setCustomValidity('')" onkeydown="return (event.keyCode!=13);">
                                    </div>
                                    <div id="btn-a" class="add-to-link mt-20">
                                        <button id="btn-a-1" type="submit" class="btn btn-success mb-10" disabled>
                                            <i class="fa fa-shopping-bag"></i> AGREGAR AL CARRITO
                                        </button>
                                    </div>
                                </form>
                            </div>
                        <?php
                        } else {
                        ?>
                            <h3 style="color: red">Sin Stock</h3>
                        <?php   }
                        ?>
                        <p><?= ucfirst($producto_data['data']['desarrollo']); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="related_product_area">
    <div class="container">
        <div class="related_product_inner">
            <h2 class="single_c_title">Productos relacionados</h2>
            <div class="row">
                <?php foreach ($productos_relacionados_data as $prod) { ?>
                    <div class="col-lg-4 col-sm-6">
                        <div class="l_product_item">
                            <a href="<?= URL . '/producto/' . $funciones->normalizar_link($prod['data']["titulo"]) . '/' . $prod['data']['cod'] ?>">
                                <div class="l_p_img" style="height:400px;background:url(<?= URL . '/' . $prod['images'][0]['ruta']; ?>) no-repeat center center/70%;">
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
<?php $template->themeEnd(); ?>