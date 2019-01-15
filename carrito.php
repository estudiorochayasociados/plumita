<?php
require_once "Config/Autoload.php";
Config\Autoload::runSitio();
$template = new Clases\TemplateSite();
$funciones = new Clases\PublicFunction();
$template->set("title", "Admin");
$template->set("description", "Admin");
$template->set("keywords", "Inicio");
$template->set("favicon", LOGO);
$template->themeInit();
//Clases
$productos = new Clases\Productos();
$imagenes = new Clases\Imagenes();
$categorias = new Clases\Categorias();
$banners = new Clases\Banner();
$carrito = new Clases\Carrito();
$envios = new Clases\Envios();
$pagos = new Clases\Pagos();
$carro = $carrito->return();
$carroEnvio = $carrito->checkEnvio();

?>
<?php $template->themeNav(); ?>
    <!--================Categories Banner Area =================-->
    <section class="categories_banner_area">
        <div class="container">
            <div class="c_banner_inner">
                <h3>Tu carrito</h3>
                <ul>
                    <li><a href="<?= URL ?>/index">Inicio</a></li>
                    <li class="current"><a href="#">Tu carrito</a></li>
                </ul>
            </div>
        </div>
    </section>
    <!--================End Categories Banner Area =================-->
    <section class="shopping_cart_area p_100">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="cart_product_list">
                        <div class="table-responsive-md">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th scope="col"></th>
                                    <th scope="col">Producto</th>
                                    <th scope="col">Precio</th>
                                    <th scope="col">Cantidad</th>
                                    <th scope="col">Total</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                if (isset($_GET["remover"])) {
                                    $carrito->delete($_GET["remover"]);
                                    $funciones->headerMove(URL . "/carrito");
                                }

                                $i = 0;
                                $precio = 0;
                                foreach ($carro as $key => $carroItem) {
                                    $precio += ($carroItem["precio"] * $carroItem["cantidad"]);
                                    $opciones = @implode(" - ", $carroItem["opciones"]);
                                    if ($carroItem["id"] == "Envio-Seleccion" || $carroItem["id"] == "Metodo-Pago") {
                                        $clase = "text-bold";
                                        $none = "hidden";
                                    } else {
                                        $clase;
                                        $none;
                                    }
                                    var_dump($carroItem);
                                    $productos->set("id", $carroItem['id']);
                                    $pro = $productos->view();
                                    $imagenes->set("cod", $pro['cod']);
                                    $img = $imagenes->view();
                                    ?>
                                    <tr>
                                        <th scope="row">
                                            <a href="<?= URL ?>/carrito.php?remover=<?= $key ?>">
                                                <img src="<?= URL ?>/assets/img/icon/close-icon.png" alt="">
                                            </a>
                                        </th>
                                        <td>
                                            <div class="media">
                                                <div class="d-flex" style="width:70px;height:100px;background:url(<?= URL . '/' . $img['ruta']; ?>) no-repeat center center/contain">
                                                </div>
                                                <div class="media-body">
                                                    <h4><?= mb_strtoupper($carroItem["titulo"]); ?></h4>
                                                </div>
                                            </div>
                                        </td>
                                        <td><p><?= "$" . $carroItem["precio"]; ?></p></td>
                                        <td><p><?= $carroItem["cantidad"]; ?></p></td>
                                        <td><p><?php
                                                if ($carroItem["precio"] != 0) {
                                                    echo "$" . ($carroItem["precio"] * $carroItem["cantidad"]);
                                                } else {
                                                    echo "¡Gratis!";
                                                }
                                                ?></p></td>
                                    </tr>
                                    <!--
                                    <tr class="<?= $clase ?>">
                                        <td><b><?= mb_strtoupper($carroItem["titulo"]); ?></b><br/><?= mb_strtoupper($opciones) ?></td>
                                        <td><span class="<?= $none ?>"><?= "$" . $carroItem["precio"]; ?></span></td>
                                        <td><span class="<?= $none ?>"><?= $carroItem["cantidad"]; ?></span></td>
                                        <td>
                                            <?php
                                    if ($carroItem["precio"] != 0) {
                                        echo "$" . ($carroItem["precio"] * $carroItem["cantidad"]);
                                    } else {
                                        echo "¡Gratis!";
                                    }
                                    ?>
                                        </td>
                                        <td>
                                            <a href="<?= URL ?>/carrito.php?remover=<?= $key ?>"><i class="fa fa-remove"></i></a>
                                        </td>
                                    </tr>-->
                                    <?php
                                    $i++;
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="calculate_shoping_area">
                        <h3 class="cart_single_title">Calculate Shoping <span><i class="icon_minus-06"></i></span></h3>
                        <div class="calculate_shop_inner">
                            <form class="calculate_shoping_form row" action="contact_process.php" method="post" id="contactForm" novalidate="novalidate">
                                <div class="form-group col-lg-12">
                                    <select class="selectpicker">
                                        <option>United State America (USA)</option>
                                        <option>United State America (USA)</option>
                                        <option>United State America (USA)</option>
                                    </select>
                                </div>
                                <div class="form-group col-lg-6">
                                    <input type="text" class="form-control" id="state" name="state" placeholder="State / Country">
                                </div>
                                <div class="form-group col-lg-6">
                                    <input type="text" class="form-control" id="zip" name="zip" placeholder="Postcode / Zip">
                                </div>
                                <div class="form-group col-lg-12">
                                    <button type="submit" value="submit" class="btn submit_btn form-control">update totals</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="cart_totals_area">
                        <h4>Cart Totals</h4>
                        <div class="cart_t_list">
                            <div class="media">
                                <div class="d-flex">
                                    <h5>Subtotal</h5>
                                </div>
                                <div class="media-body">
                                    <h6>$14</h6>
                                </div>
                            </div>
                            <div class="media">
                                <div class="d-flex">
                                    <h5>Shipping</h5>
                                </div>
                                <div class="media-body">
                                    <p>Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model tex</p>
                                </div>
                            </div>
                            <div class="media">
                                <div class="d-flex">

                                </div>
                                <div class="media-body">
                                    <select class="selectpicker">
                                        <option>Calculate Shipping</option>
                                        <option>Calculate Shipping</option>
                                        <option>Calculate Shipping</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="total_amount row m0 row_disable">
                            <div class="float-left">
                                Total
                            </div>
                            <div class="float-right">
                                $400
                            </div>
                        </div>
                    </div>
                    <button type="submit" value="submit" class="btn subs_btn form-control">Proceed to checkout</button>
                </div>
            </div>
        </div>
    </section>
    <!-- CONTENT -->
    <div id="sns_content" class="wrap layout-m">
        <div class="container">
            <div class="row">
                <div class="shoppingcart">
                    <div class="sptitle col-md-12">
                        <h3>Tu carrito</h3>
                    </div>
                    <div class="col-md-12">
                        <div class="envio">
                            <?php
                            $metodos_de_envios = $envios->list(array("peso >= " . $carrito->peso_final() . " OR peso = 0"));
                            if ($carroEnvio == '') {
                                echo "<h3>Seleccioná el envió que más te convenga:</h3>";
                                if (isset($_POST["envio"])) {
                                    if ($carroEnvio != '') {
                                        $carrito->delete($carroEnvio);
                                    }
                                    $envio_final = $_POST["envio"];
                                    $envios->set("cod", $envio_final);
                                    $envio_final_ = $envios->view();
                                    $carrito->set("id", "Envio-Seleccion");
                                    $carrito->set("cantidad", 1);
                                    $carrito->set("titulo", $envio_final_["titulo"]);
                                    $carrito->set("precio", $envio_final_["precio"]);
                                    $carrito->add();
                                    $funciones->headerMove(CANONICAL . "");
                                }
                                ?>
                                <form method="post" id="envio">
                                    <select name="envio" class="form-control" id="envio" onchange="this.form.submit()">
                                        <option value="" selected disabled>Elegir envío</option>
                                        <?php
                                        foreach ($metodos_de_envios as $metodos_de_envio_) {
                                            if ($metodos_de_envio_["precio"] == 0) {
                                                $metodos_de_envio_precio = "¡Gratis!";
                                            } else {
                                                $metodos_de_envio_precio = "$" . $metodos_de_envio_["precio"];
                                            }
                                            echo "<option value='" . $metodos_de_envio_["cod"] . "'>" . $metodos_de_envio_["titulo"] . " -> " . $metodos_de_envio_precio . "</option>";
                                        }
                                        ?>
                                    </select>
                                </form>
                                <hr/>
                                <?php
                            }
                            ?>
                        </div>
                        <table class="table table-hover">
                            <thead>
                            <th>PRODUCTO</th>
                            <th>PRECIO UNITARIO</th>
                            <th>CANTIDAD</th>
                            <th>TOTAL</th>
                            <th></th>
                            </thead>
                            <tbody>
                            <?php
                            if (isset($_GET["remover"])) {
                                $carrito->delete($_GET["remover"]);
                                $funciones->headerMove(URL . "/carrito");
                            }

                            $i = 0;
                            $precio = 0;
                            foreach ($carro as $key => $carroItem) {
                                $precio += ($carroItem["precio"] * $carroItem["cantidad"]);
                                $opciones = @implode(" - ", $carroItem["opciones"]);
                                if ($carroItem["id"] == "Envio-Seleccion" || $carroItem["id"] == "Metodo-Pago") {
                                    $clase = "text-bold";
                                    $none = "hidden";
                                } else {
                                    $clase;
                                    $none;
                                }
                                ?>
                                <tr class="<?= $clase ?>">
                                    <td><b><?= mb_strtoupper($carroItem["titulo"]); ?></b><br/><?= mb_strtoupper($opciones) ?></td>
                                    <td><span class="<?= $none ?>"><?= "$" . $carroItem["precio"]; ?></span></td>
                                    <td><span class="<?= $none ?>"><?= $carroItem["cantidad"]; ?></span></td>
                                    <td>
                                        <?php
                                        if ($carroItem["precio"] != 0) {
                                            echo "$" . ($carroItem["precio"] * $carroItem["cantidad"]);
                                        } else {
                                            echo "¡Gratis!";
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <a href="<?= URL ?>/carrito.php?remover=<?= $key ?>"><i class="fa fa-remove"></i></a>
                                    </td>
                                </tr>
                                <?php
                                $i++;
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                    <form class="form-right pull-right col-md-6" method="post">
                        <?php
                        $metodo = isset($_POST["metodos-pago"]) ? $_POST["metodos-pago"] : '';
                        $metodo_get = isset($_GET["metodos-pago"]) ? $_GET["metodos-pago"] : '';
                        if ($metodo != '') {
                            $key_metodo = $carrito->checkPago();
                            $carrito->delete($key_metodo);
                            $pagos->set("cod", $metodo);
                            $pago__ = $pagos->view();
                            $precio_final_metodo = $carrito->precio_total();
                            if ($pago__["aumento"] != 0 || $pago__["disminuir"] != '') {
                                if ($pago__["aumento"]) {
                                    $numero = (($precio_final_metodo * $pago__["aumento"]) / 100);
                                    $carrito->set("id", "Metodo-Pago");
                                    $carrito->set("cantidad", 1);
                                    $carrito->set("titulo", "CARGO +" . $pago__['aumento'] . "% / " . mb_strtoupper($pago__["titulo"]));
                                    $carrito->set("precio", $numero);
                                    $carrito->add();
                                } else {
                                    $numero = (($precio_final_metodo * $pago__["disminuir"]) / 100);
                                    $carrito->set("id", "Metodo-Pago");
                                    $carrito->set("cantidad", 1);
                                    $carrito->set("titulo", "DESCUENTO -" . $pago__['disminuir'] . "% / " . mb_strtoupper($pago__["titulo"]));
                                    $carrito->set("precio", "-" . $numero);
                                    $carrito->add();
                                }
                                $funciones->headerMove(CANONICAL . "/" . $metodo);
                            }
                        }
                        ?>
                        <div class="form-bd">
                            <h3 class="mb-0">
                                <span class="text3">TOTAL:</span>
                                <span class="text4">$<?= number_format($carrito->precio_total(), "2", ",", "."); ?></span>
                            </h3>
                            <?php if ($carroEnvio == '') { ?>
                                <span class="style-bd" onclick="$('#envio').addClass('alert alert-danger');">¿CÓMO PEREFERÍS EL ENVÍO DEL PEDIDO?</span>
                                <p class="checkout text-bold">¡Necesitamos que nos digas como querés realizar <br/>tu envío para que lo tengas listo cuanto antes!</p>
                                <?php
                            } else {
                                $lista_pagos = $pagos->list(array(" estado = 0 "));
                                foreach ($lista_pagos as $pago) {
                                    ?>
                                    <div class="radioButtonPay mb-10">
                                        <input type="radio" id="<?= ($pago["cod"]) ?>" name="metodos-pago" value="<?= ($pago["cod"]) ?>" onclick="this.form.submit()" <?php if ($metodo_get === $pago["cod"]) {
                                            echo " checked ";
                                        } ?>>
                                        <label for="<?= ($pago["cod"]) ?>"><b><?= mb_strtoupper($pago["titulo"]) ?></b></label>
                                        <p>
                                            <?= $pago["leyenda"] ?>
                                        </p>
                                    </div>
                                    <?php
                                }
                                ?>
                            <?php } ?>
                            <?php if ($metodo_get != '') { ?>
                                <a href="<?= URL ?>/pagar/<?= $metodo_get ?>" class="mb-40 btn btn-success">PAGAR EL CARRITO</a>
                            <?php } ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- AND CONTENT -->
<?php
$template->themeEnd();
?>